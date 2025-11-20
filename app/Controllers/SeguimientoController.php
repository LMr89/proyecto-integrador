<?php
/**
 * Controlador de Seguimiento Público
 * 
 * Portal público para que los clientes puedan consultar el estado de sus órdenes
 * sin necesidad de registro o login.
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\OrdenServicio;

class SeguimientoController extends Controller
{
    private OrdenServicio $ordenModel;
    
    public function __construct()
    {
        $this->ordenModel = new OrdenServicio();
    }
    
    /**
     * Muestra el formulario de búsqueda por código
     */
    public function index(): void
    {
        // Vista pública sin autenticación
        $this->viewPublic('seguimiento/index');
    }
    
    /**
     * Procesa la búsqueda de una orden por código
     */
    public function buscar(): void
    {
        $codigo = strtoupper(trim($this->post('codigo')));
        
        if (empty($codigo)) {
            $_SESSION['error'] = 'Por favor ingresa un código de seguimiento';
            redirect('/seguimiento');
            exit;
        }
        
        // Validar formato (8 caracteres alfanuméricos)
        if (!preg_match('/^[A-Z0-9]{8}$/', $codigo)) {
            $_SESSION['error'] = 'El código debe tener 8 caracteres alfanuméricos';
            redirect('/seguimiento');
            exit;
        }
        
        // Buscar la orden
        $orden = $this->ordenModel->getByCodigo($codigo);
        
        if (!$orden) {
            $_SESSION['error'] = 'No se encontró ninguna orden con ese código';
            redirect('/seguimiento');
            exit;
        }
        
        // Redirigir a la vista de la orden
        header('Location: /UNIVERSIDAD/Integrador/7service/public/seguimiento/' . $codigo);
        exit;
    }
    
    /**
     * Muestra el estado de una orden específica
     */
    public function ver(string $codigo): void
    {
        $codigo = strtoupper($codigo);
        
        // Buscar la orden
        $orden = $this->ordenModel->getByCodigo($codigo);
        
        if (!$orden) {
            $_SESSION['error'] = 'No se encontró ninguna orden con ese código';
            redirect('/seguimiento');
            exit;
        }
        
        // Obtener historial de estados
        $historial = $this->ordenModel->getHistorialByCodigo($codigo);
        
        // Vista pública
        $this->viewPublic('seguimiento/ver', [
            'orden' => $orden,
            'historial' => $historial
        ]);
    }
    
    /**
     * API para obtener el estado actual (AJAX)
     */
    public function apiEstado(string $codigo): void
    {
        header('Content-Type: application/json');
        
        $codigo = strtoupper($codigo);
        $orden = $this->ordenModel->getByCodigo($codigo);
        
        if (!$orden) {
            http_response_code(404);
            echo json_encode(['error' => 'Orden no encontrada']);
            exit;
        }
        
        echo json_encode([
            'success' => true,
            'estado' => $orden['estado'],
            'fecha_estimada' => $orden['fecha_estimada_entrega'],
            'tecnico' => $orden['tecnico_asignado']
        ]);
        exit;
    }
    
    /**
     * Renderiza una vista pública (sin autenticación)
     */
    private function viewPublic(string $view, array $data = []): void
    {
        // Extraer datos a variables
        extract($data);
        
        // Incluir la vista
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("Vista no encontrada: {$view}");
        }
    }
}
