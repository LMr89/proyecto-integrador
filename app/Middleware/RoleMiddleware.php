<?php
/**
 * Middleware de Autorización por Rol
 * 
 * Verifica que el usuario tenga el rol necesario para acceder a una ruta
 * 
 * @package App\Middleware
 */

namespace App\Middleware;

class RoleMiddleware
{
    private array $allowedRoles;
    
    /**
     * Constructor
     * 
     * @param array $allowedRoles Los roles permitidos
     */
    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }
    
    /**
     * Maneja la verificación de roles
     */
    public function handle(): void
    {
        if (!isset($_SESSION['usuario_rol'])) {
            http_response_code(403);
            echo "Acceso denegado";
            exit;
        }
        
        if (!empty($this->allowedRoles) && !in_array($_SESSION['usuario_rol'], $this->allowedRoles)) {
            http_response_code(403);
            echo "No tienes permisos para acceder a esta sección";
            exit;
        }
    }
}
