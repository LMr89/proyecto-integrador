<?php
/**
 * Clase Model (Modelo Base)
 * 
 * Todos los modelos heredan de esta clase.
 * Proporciona métodos comunes para interactuar con la base de datos
 * usando el patrón Active Record simplificado.
 * 
 * Conceptos clave:
 * - $table: nombre de la tabla en la BD
 * - $primaryKey: clave primaria (por defecto 'id')
 * - Métodos CRUD: find(), findAll(), create(), update(), delete()
 * 
 * @package App\Models
 */

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected Database $db;
    protected PDO $connection;
    
    // Propiedades que deben ser definidas en cada modelo
    protected string $table = '';
    protected string $primaryKey = 'id';
    
    /**
     * Constructor
     * Inicializa la conexión a la base de datos
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }
    
    /**
     * Obtiene todos los registros de la tabla
     * 
     * @param array $conditions Condiciones WHERE (opcional)
     * @param string $orderBy Ordenamiento (opcional)
     * @return array Los registros
     */
    public function findAll(array $conditions = [], string $orderBy = ''): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
        
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }
        
        return $this->db->query($query, $params);
    }
    
    /**
     * Busca un registro por su ID (clave primaria)
     * 
     * @param int|string $id El ID del registro
     * @return array|false El registro o false si no existe
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->queryOne($query, [':id' => $id]);
    }
    
    /**
     * Busca registros por una condición específica
     * 
     * @param array $conditions Condiciones WHERE
     * @param string $orderBy Ordenamiento (opcional)
     * @return array Los registros encontrados
     */
    public function findBy(array $conditions, string $orderBy = ''): array
    {
        return $this->findAll($conditions, $orderBy);
    }
    
    /**
     * Busca un solo registro por condición
     * 
     * @param array $conditions Condiciones WHERE
     * @return array|false El registro o false si no existe
     */
    public function findOneBy(array $conditions)
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
        
        $query .= " LIMIT 1";
        
        return $this->db->queryOne($query, $params);
    }
    
    /**
     * Crea un nuevo registro
     * 
     * @param array $data Los datos a insertar (columna => valor)
     * @return int El ID del registro insertado
     */
    public function create(array $data): int
    {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columnsList = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);
        
        $query = "INSERT INTO {$this->table} ({$columnsList}) VALUES ({$placeholders})";
        
        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }
        
        $this->db->execute($query, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un registro existente
     * 
     * @param int|string $id El ID del registro
     * @param array $data Los datos a actualizar
     * @return int El número de filas afectadas
     */
    public function update($id, array $data): int
    {
        $set = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        $setClause = implode(', ', $set);
        $query = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        return $this->db->execute($query, $params);
    }
    
    /**
     * Elimina un registro (soft delete si existe columna 'activo')
     * 
     * @param int|string $id El ID del registro
     * @return int El número de filas afectadas
     */
    public function delete($id): int
    {
        // Verificar si existe columna 'activo' para soft delete
        $columns = $this->getTableColumns();
        
        if (in_array('activo', $columns)) {
            // Soft delete: marcar como inactivo
            return $this->update($id, ['activo' => 0]);
        }
        
        // Hard delete: eliminar físicamente
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->execute($query, [':id' => $id]);
    }
    
    /**
     * Cuenta los registros que cumplen una condición
     * 
     * @param array $conditions Condiciones WHERE (opcional)
     * @return int El número de registros
     */
    public function count(array $conditions = []): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
        
        $result = $this->db->queryOne($query, $params);
        return (int) ($result['total'] ?? 0);
    }
    
    /**
     * Ejecuta una consulta SQL personalizada
     * 
     * @param string $query La consulta SQL
     * @param array $params Los parámetros
     * @return array Los resultados
     */
    public function query(string $query, array $params = []): array
    {
        return $this->db->query($query, $params);
    }
    
    /**
     * Obtiene las columnas de la tabla
     * 
     * @return array Las columnas
     */
    private function getTableColumns(): array
    {
        $query = "SHOW COLUMNS FROM {$this->table}";
        $result = $this->db->query($query);
        return array_column($result, 'Field');
    }
    
    /**
     * Inicia una transacción
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    
    /**
     * Confirma una transacción
     */
    public function commit(): void
    {
        $this->db->commit();
    }
    
    /**
     * Revierte una transacción
     */
    public function rollback(): void
    {
        $this->db->rollback();
    }
}
