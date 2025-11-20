<?php
/**
 * Clase Router
 * 
 * Maneja el enrutamiento de URLs a controladores y métodos.
 * Implementa el patrón Front Controller.
 * 
 * Ejemplo de uso:
 * $router = new Router();
 * $router->get('/clientes', 'ClienteController@index');
 * $router->post('/clientes/crear', 'ClienteController@store');
 * 
 * @package App\Core
 */

namespace App\Core;

use Exception;

class Router
{
    private array $routes = [];
    private string $basePath;
    
    /**
     * Constructor
     * 
     * @param string $basePath La ruta base de la aplicación
     */
    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }
    
    /**
     * Registra una ruta GET
     * 
     * @param string $uri La URI de la ruta
     * @param string|callable $action El controlador@método o callback
     * @param array $middleware Middleware a aplicar
     */
    public function get(string $uri, $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }
    
    /**
     * Registra una ruta POST
     * 
     * @param string $uri La URI de la ruta
     * @param string|callable $action El controlador@método o callback
     * @param array $middleware Middleware a aplicar
     */
    public function post(string $uri, $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }
    
    /**
     * Registra una ruta PUT
     * 
     * @param string $uri La URI de la ruta
     * @param string|callable $action El controlador@método o callback
     * @param array $middleware Middleware a aplicar
     */
    public function put(string $uri, $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }
    
    /**
     * Registra una ruta DELETE
     * 
     * @param string $uri La URI de la ruta
     * @param string|callable $action El controlador@método o callback
     * @param array $middleware Middleware a aplicar
     */
    public function delete(string $uri, $action, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }
    
    /**
     * Agrega una ruta al array de rutas
     * 
     * @param string $method El método HTTP
     * @param string $uri La URI de la ruta
     * @param string|callable $action El controlador@método o callback
     * @param array $middleware Middleware a aplicar
     */
    private function addRoute(string $method, string $uri, $action, array $middleware): void
    {
        $uri = '/' . trim($uri, '/');
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware
        ];
    }
    
    /**
     * Despacha la petición a la ruta correspondiente
     * 
     * @param string $uri La URI solicitada
     * @param string $method El método HTTP
     */
    public function dispatch(string $uri, string $method): void
    {
        // Limpiar la URI
        $uri = '/' . trim($uri, '/');
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Buscar la ruta
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                // Convertir parámetros de ruta en regex
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route['uri']);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remover el primer elemento (la URI completa)
                    
                    // Ejecutar middleware
                    foreach ($route['middleware'] as $middleware) {
                        $this->executeMiddleware($middleware);
                    }
                    
                    // Ejecutar la acción
                    $this->executeAction($route['action'], $matches);
                    return;
                }
            }
        }
        
        // No se encontró la ruta
        $this->notFound();
    }
    
    /**
     * Ejecuta el middleware
     * 
     * @param string $middleware El nombre del middleware
     */
    private function executeMiddleware(string $middleware): void
    {
        $middlewareClass = "App\\Middleware\\{$middleware}";
        
        if (class_exists($middlewareClass)) {
            $instance = new $middlewareClass();
            $instance->handle();
        }
    }
    
    /**
     * Ejecuta la acción del controlador
     * 
     * @param string|callable $action El controlador@método o callback
     * @param array $params Los parámetros de la ruta
     */
    private function executeAction($action, array $params = []): void
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }
        
        if (is_string($action)) {
            list($controller, $method) = explode('@', $action);
            
            $controllerClass = "App\\Controllers\\{$controller}";
            
            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                
                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $params);
                    return;
                }
            }
        }
        
        throw new Exception("Acción no válida: {$action}");
    }
    
    /**
     * Maneja el error 404
     */
    private function notFound(): void
    {
        http_response_code(404);
        echo "404 - Página no encontrada";
        exit;
    }
    
    /**
     * Obtiene todas las rutas registradas
     * 
     * @return array Las rutas
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
