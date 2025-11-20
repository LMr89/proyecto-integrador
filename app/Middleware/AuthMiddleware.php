<?php
/**
 * Middleware de Autenticación
 * 
 * Verifica que el usuario esté autenticado antes de acceder a rutas protegidas
 * 
 * @package App\Middleware
 */

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Maneja la verificación de autenticación
     */
    public function handle(): void
    {
        // Verificar si existe una sesión activa
        if (!isset($_SESSION['usuario_id'])) {
            // Redirigir al login
            redirect('/login');
        }
    }
}
