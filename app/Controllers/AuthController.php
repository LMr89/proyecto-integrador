<?php
/**
 * Controlador de Autenticación
 * 
 * Maneja login, logout y registro de usuarios
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\Usuario;

class AuthController extends Controller
{
    private Usuario $usuarioModel;
    
    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Muestra el formulario de login
     */
    public function showLogin(): void
    {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['usuario_id'])) {
            redirect('/dashboard');
        }

        $this->view('auth/login');
    }
    
    /**
     * Procesa el login
     */
    public function login(): void
    {
        $correo = $this->post('correo');
        $password = $this->post('password');
        
        // Validar datos
        $errors = $this->validate([
            'correo' => $correo,
            'password' => $password
        ], [
            'correo' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
            return;
        }
        
        // Intentar autenticar
        $usuario = $this->usuarioModel->authenticate($correo, $password);
        
        if ($usuario) {
            // Establecer sesión
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $usuario['correo'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            $this->json([
                'success' => true,
                'message' => 'Login exitoso',
                'redirect' => url('/dashboard')
            ]);
        } else {
            $this->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }
    }
    
    /**
     * Cierra la sesión
     */
    public function logout(): void
    {
        session_destroy();
        redirect('/login');
    }
    
    /**
     * Muestra el formulario de registro (solo para admin)
     */
    public function showRegister(): void
    {
        // Verificar que sea admin
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            redirect('/dashboard');
        }

        $this->view('auth/register');
    }
    
    /**
     * Procesa el registro de nuevo usuario
     */
    public function register(): void
    {
        // Verificar que sea admin
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $this->json(['success' => false, 'message' => 'No autorizado'], 403);
            return;
        }
        
        $data = [
            'nombre' => $this->post('nombre'),
            'correo' => $this->post('correo'),
            'password' => $this->post('password'),
            'rol' => $this->post('rol')
        ];
        
        // Validar datos
        $errors = $this->validate($data, [
            'nombre' => 'required|min:3',
            'correo' => 'required|email',
            'password' => 'required|min:6',
            'rol' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
            return;
        }
        
        // Verificar si el correo ya existe
        if ($this->usuarioModel->findByEmail($data['correo'])) {
            $this->json(['success' => false, 'message' => 'El correo ya está registrado'], 400);
            return;
        }
        
        // Crear usuario
        try {
            $userId = $this->usuarioModel->createUser($data);
            
            $this->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al crear usuario'], 500);
        }
    }
}
