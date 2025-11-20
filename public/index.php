<?php
/**
 * Punto de Entrada Principal (Front Controller)
 * 
 * Este archivo recibe todas las peticiones y las dirige al controlador apropiado.
 * 
 * Flujo:
 * 1. Cargar configuración
 * 2. Cargar autoloader
 * 3. Obtener URI y método HTTP
 * 4. Despachar la petición al router
 * 
 * @package Public
 */

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Autoloader simple (en producción usarías Composer)
spl_autoload_register(function ($class) {
    // Convertir namespace a ruta de archivo
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $file = APP_PATH . '/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Cargar rutas
$router = require_once CONFIG_PATH . '/routes.php';

// Obtener la URI solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remover la ruta base si existe
$basePath = '/UNIVERSIDAD/Integrador/7service/public';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Si está vacía, usar '/'
$uri = $uri ?: '/';

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Soporte para métodos PUT y DELETE vía POST
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Despachar la petición
try {
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    // Manejo de errores
    if (APP_DEBUG) {
        echo "<h1>Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "Ha ocurrido un error. Por favor, contacte al administrador.";
    }
}
