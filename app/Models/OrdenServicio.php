<?php
/**
 * Modelo OrdenServicio
 * 
 * Gestiona las órdenes de servicio del taller
 * Es el corazón del sistema: vincula clientes, bicicletas, productos y usuarios
 * 
 * @package App\Models
 */

namespace App\Models;

class OrdenServicio extends Model
{
    protected string $table = 'ordenes_servicio';
    protected string $primaryKey = 'id_orden';
    
    /**
     * Obtiene todas las órdenes con información relacionada
     * 
     * @param array $filters Filtros opcionales (estado, fecha_desde, fecha_hasta)
     * @return array Las órdenes con información completa
     */
    public function getAllWithDetails(array $filters = []): array
    {
        $query = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    c.contacto_telefono as cliente_telefono,
                    b.marca as bicicleta_marca,
                    b.modelo as bicicleta_modelo,
                    u.nombre as tecnico_asignado,
                    COALESCE(SUM(p.monto), 0) as total_pagado,
                    (o.costo_total - COALESCE(SUM(p.monto), 0)) as saldo_pendiente
                  FROM {$this->table} o
                  JOIN clientes c ON o.id_cliente = c.id_cliente
                  JOIN bicicletas b ON o.id_bicicleta = b.id_bicicleta
                  LEFT JOIN usuarios u ON o.id_usuario_asignado = u.id_usuario
                  LEFT JOIN pagos p ON o.id_orden = p.id_orden";
        
        $conditions = [];
        $params = [];
        
        if (!empty($filters['estado'])) {
            $conditions[] = "o.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }
        
        if (!empty($filters['fecha_desde'])) {
            $conditions[] = "DATE(o.fecha_creacion) >= :fecha_desde";
            $params[':fecha_desde'] = $filters['fecha_desde'];
        }
        
        if (!empty($filters['fecha_hasta'])) {
            $conditions[] = "DATE(o.fecha_creacion) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filters['fecha_hasta'];
        }
        
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $query .= " GROUP BY o.id_orden ORDER BY o.fecha_creacion DESC";
        
        return $this->db->query($query, $params);
    }
    
    /**
     * Obtiene una orden con todos sus detalles
     * 
     * @param int $id El ID de la orden
     * @return array|false La orden con todos sus detalles
     */
    public function getFullDetails(int $id)
    {
        $query = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    c.contacto_telefono,
                    c.contacto_email,
                    b.marca,
                    b.modelo,
                    b.tipo as bicicleta_tipo,
                    b.color,
                    u.nombre as creador_nombre,
                    ua.nombre as asignado_nombre
                  FROM {$this->table} o
                  JOIN clientes c ON o.id_cliente = c.id_cliente
                  JOIN bicicletas b ON o.id_bicicleta = b.id_bicicleta
                  JOIN usuarios u ON o.id_usuario_creador = u.id_usuario
                  LEFT JOIN usuarios ua ON o.id_usuario_asignado = ua.id_usuario
                  WHERE o.id_orden = :id";
        
        return $this->db->queryOne($query, [':id' => $id]);
    }
    
    /**
     * Obtiene los productos/repuestos usados en una orden
     * 
     * @param int $id El ID de la orden
     * @return array Los detalles de productos de la orden
     */
    public function getDetalleProductos(int $id): array
    {
        $query = "SELECT 
                    d.*,
                    p.nombre as producto_nombre,
                    p.sku,
                    p.unidad_medida
                  FROM detalle_orden d
                  JOIN productos p ON d.id_producto = p.id_producto
                  WHERE d.id_orden = :id";
        
        return $this->db->query($query, [':id' => $id]);
    }
    
    /**
     * Crea una nueva bicicleta
     * 
     * @param array $data Los datos de la bicicleta
     * @return int El ID de la bicicleta creada
     */
    public function createBicicleta(array $data): int
    {
        // Verificar si la columna se llama año_fabricacion o anio_fabricacion
        $query = "INSERT INTO bicicletas (id_cliente, marca, modelo, tipo, color, numero_serie, año_fabricacion, notas) 
                  VALUES (:id_cliente, :marca, :modelo, :tipo, :color, :numero_serie, :anio_fabricacion, :notas)";
        
        $params = [
            ':id_cliente' => $data['id_cliente'],
            ':marca' => $data['marca'],
            ':modelo' => $data['modelo'],
            ':tipo' => $data['tipo'] ?? null,
            ':color' => $data['color'] ?? null,
            ':numero_serie' => $data['numero_serie'] ?? null,
            ':anio_fabricacion' => $data['año_fabricacion'] ?? null,
            ':notas' => $data['notas'] ?? null
        ];
        
        $this->db->execute($query, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Genera un código único de seguimiento
     * 
     * @return string El código de seguimiento (8 caracteres alfanuméricos)
     */
    public function generarCodigoSeguimiento(): string
    {
        do {
            // Generar código alfanumérico de 8 caracteres
            $codigo = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            
            // Verificar que no exista
            $existe = $this->findOneBy(['codigo_seguimiento' => $codigo]);
        } while ($existe);
        
        return $codigo;
    }
    
    /**
     * Busca una orden por su código de seguimiento
     * 
     * @param string $codigo El código de seguimiento
     * @return array|false La orden con información para cliente
     */
    public function getByCodigo(string $codigo)
    {
        $query = "SELECT 
                    o.id_orden,
                    o.codigo_seguimiento,
                    o.fecha_creacion,
                    o.fecha_estimada_entrega,
                    o.fecha_finalizacion,
                    o.estado,
                    o.prioridad,
                    o.descripcion_problema,
                    o.diagnostico_tecnico,
                    o.costo_total,
                    o.requiere_aprobacion,
                    o.aprobado_por_cliente,
                    b.marca as bicicleta_marca,
                    b.modelo as bicicleta_modelo,
                    b.tipo as bicicleta_tipo,
                    b.color as bicicleta_color,
                    ua.nombre as tecnico_asignado
                  FROM {$this->table} o
                  JOIN bicicletas b ON o.id_bicicleta = b.id_bicicleta
                  LEFT JOIN usuarios ua ON o.id_usuario_asignado = ua.id_usuario
                  WHERE o.codigo_seguimiento = :codigo";
        
        return $this->db->queryOne($query, [':codigo' => $codigo]);
    }
    
    /**
     * Obtiene el historial de estados para vista pública
     * 
     * @param string $codigo El código de seguimiento
     * @return array El historial de estados
     */
    public function getHistorialByCodigo(string $codigo): array
    {
        $query = "SELECT 
                    h.fecha_cambio,
                    h.estado_anterior,
                    h.estado_nuevo,
                    h.comentario
                  FROM historial_estados_orden h
                  JOIN ordenes_servicio o ON h.id_orden = o.id_orden
                  WHERE o.codigo_seguimiento = :codigo
                  ORDER BY h.fecha_cambio ASC";
        
        return $this->db->query($query, [':codigo' => $codigo]);
    }
    
    /**
     * Crea una nueva orden de servicio
     * 
     * @param array $data Los datos de la orden
     * @return int El ID de la orden creada
     */
    public function createOrden(array $data): int
    {
        // Generar código de seguimiento si no existe
        if (!isset($data['codigo_seguimiento'])) {
            $data['codigo_seguimiento'] = $this->generarCodigoSeguimiento();
        }
        
        // Asegurar que tenga valores por defecto
        $defaults = [
            'estado' => 'Pendiente',
            'prioridad' => 'Normal',
            'costo_mano_obra' => 0.00,
            'costo_productos' => 0.00,
            'descuento' => 0.00,
            'costo_total' => 0.00,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];
        
        $data = array_merge($defaults, $data);
        
        return $this->create($data);
    }
    
    /**
     * Agrega productos a una orden
     * 
     * @param int $idOrden El ID de la orden
     * @param array $productos Array de productos [id_producto, cantidad, precio_unitario]
     * @return bool True si se agregaron correctamente
     */
    public function agregarProductos(int $idOrden, array $productos): bool
    {
        try {
            $this->beginTransaction();
            
            foreach ($productos as $producto) {
                $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
                
                $query = "INSERT INTO detalle_orden 
                          (id_orden, id_producto, cantidad, precio_unitario_congelado, subtotal) 
                          VALUES (:id_orden, :id_producto, :cantidad, :precio, :subtotal)";
                
                $this->db->execute($query, [
                    ':id_orden' => $idOrden,
                    ':id_producto' => $producto['id_producto'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $producto['precio_unitario'],
                    ':subtotal' => $subtotal
                ]);
            }
            
            // El trigger de la BD actualizará automáticamente el costo_total
            
            $this->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }
    
    /**
     * Actualiza el estado de una orden
     * 
     * @param int $id El ID de la orden
     * @param string $nuevoEstado El nuevo estado
     * @param int $idUsuario El ID del usuario que hace el cambio
     * @param string $comentario Comentario sobre el cambio (opcional)
     * @return bool True si se actualizó correctamente
     */
    public function actualizarEstado(int $id, string $nuevoEstado, int $idUsuario, string $comentario = ''): bool
    {
        try {
            $this->beginTransaction();
            
            // Actualizar el estado
            $this->update($id, ['estado' => $nuevoEstado]);
            
            // El trigger registrará automáticamente en historial_estados_orden
            
            $this->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }
    
    /**
     * Obtiene el historial de cambios de estado de una orden
     * 
     * @param int $id El ID de la orden
     * @return array El historial de estados
     */
    public function getHistorialEstados(int $id): array
    {
        $query = "SELECT h.*, u.nombre as usuario_nombre
                  FROM historial_estados_orden h
                  JOIN usuarios u ON h.id_usuario = u.id_usuario
                  WHERE h.id_orden = :id
                  ORDER BY h.fecha_cambio DESC";
        
        return $this->db->query($query, [':id' => $id]);
    }
    
    /**
     * Obtiene estadísticas de órdenes
     * 
     * @param array $filters Filtros de fecha
     * @return array Estadísticas
     */
    public function getEstadisticas(array $filters = []): array
    {
        $conditions = [];
        $params = [];
        
        if (!empty($filters['fecha_desde'])) {
            $conditions[] = "DATE(fecha_creacion) >= :fecha_desde";
            $params[':fecha_desde'] = $filters['fecha_desde'];
        }
        
        if (!empty($filters['fecha_hasta'])) {
            $conditions[] = "DATE(fecha_creacion) <= :fecha_hasta";
            $params[':fecha_hasta'] = $filters['fecha_hasta'];
        }
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $query = "SELECT 
                    COUNT(*) as total_ordenes,
                    SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = 'En Reparacion' THEN 1 ELSE 0 END) as en_reparacion,
                    SUM(CASE WHEN estado = 'Entregado' THEN 1 ELSE 0 END) as entregadas,
                    COALESCE(SUM(costo_total), 0) as total_ventas,
                    COALESCE(AVG(costo_total), 0) as promedio_venta
                  FROM {$this->table}
                  {$whereClause}";
        
        return $this->db->queryOne($query, $params) ?: [];
    }
    
    /**
     * Cuenta órdenes por condiciones
     * 
     * @param array $conditions Condiciones para el conteo
     * @return int El número de órdenes
     */
    public function countBy(array $conditions): int
    {
        $where = [];
        $params = [];
        
        foreach ($conditions as $key => $value) {
            if ($key === 'fecha') {
                $where[] = "DATE(fecha_creacion) = :fecha";
                $params[':fecha'] = $value;
            } else {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $query = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $result = $this->db->queryOne($query, $params);
        
        return (int) ($result['total'] ?? 0);
    }
    
    /**
     * Obtiene las órdenes asignadas a un técnico
     * 
     * @param int $tecnicoId El ID del técnico
     * @return array Las órdenes del técnico
     */
    public function getOrdenesByTecnico(int $tecnicoId): array
    {
        $query = "SELECT 
                    o.*,
                    c.nombre as cliente_nombre,
                    b.marca as bicicleta_marca,
                    b.modelo as bicicleta_modelo
                  FROM {$this->table} o
                  JOIN clientes c ON o.id_cliente = c.id_cliente
                  JOIN bicicletas b ON o.id_bicicleta = b.id_bicicleta
                  WHERE o.id_usuario_asignado = :tecnico_id
                  AND o.estado IN ('pendiente', 'en_proceso', 'en_espera')
                  ORDER BY o.fecha_creacion DESC";
        
        return $this->db->query($query, [':tecnico_id' => $tecnicoId]);
    }
}
