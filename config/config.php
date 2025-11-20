<?php
/**
 * Archivo de Configuración Principal
 * 
 * Este archivo carga las variables de entorno y define constantes
 * globales para la aplicación.
 */

// Cargar variables de entorno
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        
        // Separar clave y valor
        $parts = explode('=', $line, 2);
        $key = trim($parts[0]);
        $value = isset($parts[1]) ? trim($parts[1]) : '';
        
        // Remover comillas si existen
        if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
            $value = $matches[1];
        }
        
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
}

// Función helper para obtener variables de entorno
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Función helper para generar URLs relativas al base path
function url($path = '') {
    $basePath = env('APP_BASE_PATH', '');
    // Asegurar que el path comience con /
    $path = '/' . ltrim($path, '/');
    // Retornar base path + path
    return $basePath . $path;
}

// Función helper para generar URL completa
function full_url($path = '') {
    return rtrim(env('APP_URL', 'http://localhost'), '/') . url($path);
}

// Función helper para redirecciones
function redirect($path = '', $statusCode = 302) {
    header('Location: ' . url($path), true, $statusCode);
    exit;
}

// Definir constantes de la aplicación
define('APP_NAME', env('APP_NAME', 'Seven Service'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', env('APP_DEBUG', 'true') === 'true');
define('APP_URL', env('APP_URL', 'http://localhost'));

// Constantes de base de datos
define('DB_HOST', env('DB_HOST', 'fw48koc4oggg88c4sgcg4k88'));
define('DB_PORT', env('DB_PORT', '5060'));
define('DB_NAME', env('DB_NAME', 'taller_bicicletas'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', 'wbWpZlPGVj8rlEmvaRzGwHutwEvjbo1PrJzeqizFXASco6q1lxeipB1v9lvsb7gJ'));

// Constantes de rutas
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('LOGS_PATH', STORAGE_PATH . '/logs');

// Configuración de seguridad
define('APP_KEY', env('APP_KEY', 'cambiar-esto-en-produccion'));
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 7200));

// Configuración de zona horaria
date_default_timezone_set('America/Lima');

// Configuración de errores según el ambiente
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', LOGS_PATH . '/php_errors.log');
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
