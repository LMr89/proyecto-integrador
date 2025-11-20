<?php
/**
 * Modelo Cliente
 * 
 * Representa a los clientes del taller
 * Gestiona información de contacto e historial
 * 
 * @package App\Models
 */

namespace App\Models;

class Cliente extends Model
{
    protected string $table = 'clientes';
    protected string $primaryKey = 'id_cliente';
    
    /**
     * Obtiene todos los clientes activos con conteos
     * 
     * @return array Los clientes activos
     */
    public function getActiveClientes(): array
    {
        $query = "SELECT 
                    c.*,
                    COUNT(DISTINCT b.id_bicicleta) as total_bicicletas,
                    COUNT(DISTINCT o.id_orden) as total_ordenes
                  FROM clientes c
                  LEFT JOIN bicicletas b ON c.id_cliente = b.id_cliente
                  LEFT JOIN ordenes_servicio o ON c.id_cliente = o.id_cliente
                  WHERE c.activo = 1
                  GROUP BY c.id_cliente
                  ORDER BY c.nombre ASC";
        
        return $this->db->query($query);
    }
    
    /**
     * Busca clientes por nombre, teléfono o DNI
     * 
     * @param string $search El término de búsqueda
     * @return array Los clientes encontrados
     */
    public function search(string $search): array
    {
        $query = "SELECT 
                    c.*,
                    COUNT(DISTINCT b.id_bicicleta) as total_bicicletas,
                    COUNT(DISTINCT o.id_orden) as total_ordenes
                  FROM clientes c
                  LEFT JOIN bicicletas b ON c.id_cliente = b.id_cliente
                  LEFT JOIN ordenes_servicio o ON c.id_cliente = o.id_cliente
                  WHERE c.activo = 1 
                  AND (c.nombre LIKE :search 
                    OR c.telefono LIKE :search 
                    OR c.correo LIKE :search
                    OR c.dni LIKE :search)
                  GROUP BY c.id_cliente
                  ORDER BY c.nombre ASC";
        
        return $this->db->query($query, [':search' => "%{$search}%"]);
    }
    
    /**
     * Obtiene el historial completo de un cliente
     * Incluye órdenes de servicio y bicicletas
     * 
     * @param int $id El ID del cliente
     * @return array El historial del cliente
     */
    public function getHistorial(int $id): array
    {
        $query = "SELECT 
                    c.*,
                    COUNT(DISTINCT b.id_bicicleta) as total_bicicletas,
                    COUNT(DISTINCT o.id_orden) as total_ordenes,
                    COALESCE(SUM(o.costo_total), 0) as total_gastado,
                    MAX(o.fecha_creacion) as ultima_visita
                  FROM clientes c
                  LEFT JOIN bicicletas b ON c.id_cliente = b.id_cliente
                  LEFT JOIN ordenes_servicio o ON c.id_cliente = o.id_cliente
                  WHERE c.id_cliente = :id
                  GROUP BY c.id_cliente";
        
        return $this->db->queryOne($query, [':id' => $id]) ?: [];
    }
    
    /**
     * Obtiene las bicicletas de un cliente
     * 
     * @param int $id El ID del cliente
     * @return array Las bicicletas del cliente
     */
    public function getBicicletas(int $id): array
    {
        $query = "SELECT * FROM bicicletas WHERE id_cliente = :id ORDER BY fecha_registro DESC";
        return $this->db->query($query, [':id' => $id]);
    }
    
    /**
     * Obtiene las órdenes de servicio de un cliente
     * 
     * @param int $id El ID del cliente
     * @param int $limit Límite de resultados (opcional)
     * @return array Las órdenes del cliente
     */
    public function getOrdenes(int $id, int $limit = 0): array
    {
        $query = "SELECT o.*, b.marca, b.modelo 
                  FROM ordenes_servicio o
                  JOIN bicicletas b ON o.id_bicicleta = b.id_bicicleta
                  WHERE o.id_cliente = :id 
                  ORDER BY o.fecha_creacion DESC";
        
        if ($limit > 0) {
            $query .= " LIMIT {$limit}";
        }
        
        return $this->db->query($query, [':id' => $id]);
    }
}
