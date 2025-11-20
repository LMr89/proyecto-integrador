<?php
/**
 * Modelo Producto
 * 
 * Representa los productos en el inventario
 * Maneja stock, precios y alertas de inventario bajo
 * 
 * @package App\Models
 */

namespace App\Models;

class Producto extends Model
{
    protected string $table = 'productos';
    protected string $primaryKey = 'id_producto';
    
    /**
     * Obtiene todos los productos activos
     * 
     * @return array Los productos activos
     */
    public function getActiveProducts(): array
    {
        $query = "SELECT p.*, c.nombre as categoria_nombre, pr.nombre as proveedor_nombre
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                  LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
                  WHERE p.activo = 1
                  ORDER BY p.nombre ASC";
        
        return $this->db->query($query);
    }
    
    /**
     * Busca productos por nombre o SKU
     * 
     * @param string $search El término de búsqueda
     * @return array Los productos encontrados
     */
    public function search(string $search): array
    {
        $query = "SELECT p.*, c.nombre as categoria_nombre
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                  WHERE p.activo = 1 
                  AND (p.nombre LIKE :search OR p.sku LIKE :search)
                  ORDER BY p.nombre ASC";
        
        return $this->db->query($query, [':search' => "%{$search}%"]);
    }
    
    /**
     * Obtiene productos con stock bajo
     * 
     * @return array Los productos con stock menor o igual al mínimo
     */
    public function getProductosStockBajo(): array
    {
        $query = "SELECT p.*, 
                         c.nombre as categoria,
                         pr.nombre as proveedor,
                         (p.stock_minimo - p.stock_actual) as unidades_faltantes
                  FROM {$this->table} p
                  JOIN categorias c ON p.id_categoria = c.id_categoria
                  LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
                  WHERE p.stock_actual <= p.stock_minimo 
                  AND p.activo = 1
                  ORDER BY unidades_faltantes DESC";
        
        return $this->db->query($query);
    }
    
    /**
     * Actualiza el stock de un producto
     * 
     * @param int $id El ID del producto
     * @param int $cantidad La cantidad a sumar o restar
     * @param string $tipo Tipo de movimiento: 'Entrada' o 'Salida'
     * @return bool True si se actualizó correctamente
     */
    public function updateStock(int $id, int $cantidad, string $tipo = 'Salida'): bool
    {
        $producto = $this->find($id);
        
        if (!$producto) {
            return false;
        }
        
        $nuevoStock = $tipo === 'Entrada' 
            ? $producto['stock_actual'] + $cantidad 
            : $producto['stock_actual'] - $cantidad;
        
        // No permitir stock negativo
        if ($nuevoStock < 0) {
            return false;
        }
        
        return $this->update($id, ['stock_actual' => $nuevoStock]) > 0;
    }
    
    /**
     * Obtiene productos por categoría
     * 
     * @param int $idCategoria El ID de la categoría
     * @return array Los productos de la categoría
     */
    public function getByCategoria(int $idCategoria): array
    {
        return $this->findBy(['id_categoria' => $idCategoria, 'activo' => 1], 'nombre ASC');
    }
    
    /**
     * Obtiene las categorías disponibles
     * 
     * @return array Las categorías activas
     */
    public function getCategorias(): array
    {
        $query = "SELECT * FROM categorias WHERE activo = 1 ORDER BY nombre ASC";
        return $this->db->query($query);
    }
    
    /**
     * Obtiene los proveedores disponibles
     * 
     * @return array Los proveedores activos
     */
    public function getProveedores(): array
    {
        $query = "SELECT * FROM proveedores WHERE activo = 1 ORDER BY nombre ASC";
        return $this->db->query($query);
    }
    
    /**
     * Obtiene productos activos con filtros opcionales
     * 
     * @param array $filters Filtros opcionales (categoria, etc)
     * @return array Los productos
     */
    public function getProductosActivos(array $filters = []): array
    {
        $query = "SELECT p.*, 
                         c.nombre as categoria_nombre,
                         pr.nombre as proveedor_nombre,
                         (p.precio_venta - p.precio_compra) as margen
                  FROM {$this->table} p
                  LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                  LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
                  WHERE p.activo = 1";
        
        $params = [];
        
        if (!empty($filters['categoria'])) {
            $query .= " AND p.id_categoria = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }
        
        $query .= " ORDER BY p.nombre ASC";
        
        return $this->db->query($query, $params);
    }
    
    /**
     * Obtiene el valor total del inventario
     * 
     * @return float El valor total
     */
    public function getValorTotalInventario(): float
    {
        $query = "SELECT SUM(stock_actual * precio_compra) as total 
                  FROM {$this->table} 
                  WHERE activo = 1";
        
        $result = $this->db->queryOne($query);
        return (float) ($result['total'] ?? 0);
    }
}
