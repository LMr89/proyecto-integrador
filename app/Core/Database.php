<?php
/**
 * Clase Database
 * 
 * Maneja la conexión a MySQL usando el patrón Singleton.
 * Esto garantiza que solo exista UNA conexión activa durante toda
 * la ejecución de la aplicación, optimizando recursos.
 * 
 * Patrón Singleton:
 * - Constructor privado: no se puede instanciar directamente
 * - Método estático getInstance(): devuelve la única instancia
 * 
 * @package App\Core
 */

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    
    /**
     * Constructor privado para implementar Singleton
     * Se conecta a MySQL con manejo de errores robusto
     */
    private function __construct()
    {
        try {
            $host = DB_HOST;
            $port = DB_PORT;
            $dbname = DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;
            
            // DSN (Data Source Name) - cadena de conexión
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            
            // Opciones de PDO para mejor manejo de errores y seguridad
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lanza excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Retorna arrays asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                   // Usa prepared statements reales
                PDO::ATTR_PERSISTENT         => false,                   // No usar conexiones persistentes
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"      // Encoding UTF-8
            ];
            
            $this->connection = new PDO($dsn, $username, $password, $options);
            
            $this->log("Conexión a base de datos establecida exitosamente");
            
        } catch (PDOException $e) {
            $this->log("Error de conexión: " . $e->getMessage(), 'ERROR');
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene la única instancia de Database (Singleton)
     * 
     * @return Database La instancia única de la clase
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Obtiene la conexión PDO
     * 
     * @return PDO La conexión a la base de datos
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    /**
     * Ejecuta una consulta SELECT y retorna todos los resultados
     * 
     * @param string $query La consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return array Los resultados de la consulta
     */
    public function query(string $query, array $params = []): array
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->log("Error en query: " . $e->getMessage(), 'ERROR');
            throw new Exception("Error al ejecutar consulta: " . $e->getMessage());
        }
    }
    
    /**
     * Ejecuta una consulta SELECT y retorna un solo resultado
     * 
     * @param string $query La consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return array|false El resultado o false si no hay datos
     */
    public function queryOne(string $query, array $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->log("Error en queryOne: " . $e->getMessage(), 'ERROR');
            throw new Exception("Error al ejecutar consulta: " . $e->getMessage());
        }
    }
    
    /**
     * Ejecuta una consulta INSERT, UPDATE o DELETE
     * 
     * @param string $query La consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return int El número de filas afectadas
     */
    public function execute(string $query, array $params = []): int
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->log("Error en execute: " . $e->getMessage(), 'ERROR');
            throw new Exception("Error al ejecutar consulta: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene el ID del último registro insertado
     * 
     * @return int El ID insertado
     */
    public function lastInsertId(): int
    {
        return (int) $this->connection->lastInsertId();
    }
    
    /**
     * Inicia una transacción
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }
    
    /**
     * Confirma una transacción
     */
    public function commit(): void
    {
        $this->connection->commit();
    }
    
    /**
     * Revierte una transacción
     */
    public function rollback(): void
    {
        $this->connection->rollBack();
    }
    
    /**
     * Registra eventos en el log
     * 
     * @param string $message El mensaje a registrar
     * @param string $level Nivel del log (INFO, WARNING, ERROR)
     */
    private function log(string $message, string $level = 'INFO'): void
    {
        if (APP_DEBUG) {
            $date = date('Y-m-d H:i:s');
            $logMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;
            file_put_contents(LOGS_PATH . '/database.log', $logMessage, FILE_APPEND);
        }
    }
    
    /**
     * Previene la clonación del objeto
     */
    private function __clone() {}
    
    /**
     * Previene la deserialización del objeto
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
