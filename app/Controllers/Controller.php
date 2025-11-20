<?php
/**
 * Clase Controller Base
 * 
 * Todos los controladores heredan de esta clase.
 * Proporciona métodos comunes para renderizar vistas y manejar respuestas JSON.
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

class Controller
{
    /**
     * Renderiza una vista
     * 
     * @param string $view La ruta de la vista (ej: 'auth/login')
     * @param array $data Datos a pasar a la vista
     */
    protected function view(string $view, array $data = []): void
    {
        // Extraer datos para que estén disponibles como variables
        extract($data);
        
        // Construir la ruta completa de la vista
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: {$view}");
        }
        
        require_once $viewPath;
    }
    
    /**
     * Retorna una respuesta JSON
     * 
     * @param mixed $data Los datos a enviar
     * @param int $statusCode El código de estado HTTP
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redirige a otra URL
     * 
     * @param string $url La URL de destino
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Obtiene datos POST
     * 
     * @param string|null $key La clave específica o null para todos
     * @return mixed Los datos POST
     */
    protected function post(?string $key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? null;
    }
    
    /**
     * Obtiene datos GET
     * 
     * @param string|null $key La clave específica o null para todos
     * @return mixed Los datos GET
     */
    protected function get(?string $key = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? null;
    }
    
    /**
     * Valida datos de entrada
     * 
     * @param array $data Los datos a validar
     * @param array $rules Las reglas de validación
     * @return array Errores de validación (vacío si todo es válido)
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            
            foreach ($rulesArray as $rule) {
                // Regla: required
                if ($rule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "El campo {$field} es obligatorio";
                    continue;
                }
                
                // Regla: email
                if ($rule === 'email' && !empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "El campo {$field} debe ser un email válido";
                }
                
                // Regla: min:X
                if (strpos($rule, 'min:') === 0) {
                    $min = (int) substr($rule, 4);
                    if (!empty($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
                    }
                }
                
                // Regla: max:X
                if (strpos($rule, 'max:') === 0) {
                    $max = (int) substr($rule, 4);
                    if (!empty($data[$field]) && strlen($data[$field]) > $max) {
                        $errors[$field][] = "El campo {$field} no puede exceder {$max} caracteres";
                    }
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Limpia datos de entrada (prevención básica de XSS)
     * 
     * @param mixed $data Los datos a limpiar
     * @return mixed Los datos limpios
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Verifica si la petición es AJAX
     * 
     * @return bool True si es AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Obtiene el usuario autenticado
     * 
     * @return array|null Los datos del usuario o null
     */
    protected function auth(): ?array
    {
        if (isset($_SESSION['usuario_id'])) {
            return [
                'id' => $_SESSION['usuario_id'],
                'nombre' => $_SESSION['usuario_nombre'] ?? '',
                'correo' => $_SESSION['usuario_correo'] ?? '',
                'rol' => $_SESSION['usuario_rol'] ?? ''
            ];
        }
        
        return null;
    }
}
