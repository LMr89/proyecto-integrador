<?php
/**
 * Controlador de Órdenes de Servicio
 * 
 * Gestiona el CRUD de órdenes y sus estados
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\OrdenServicio;
use App\Models\Cliente;
use App\Models\Producto;

class OrdenController extends Controller
{
    private OrdenServicio $ordenModel;
    private Cliente $clienteModel;
    private Producto $productoModel;
    
    public function __construct()
    {
        $this->ordenModel = new OrdenServicio();
        $this->clienteModel = new Cliente();
        $this->productoModel = new Producto();
    }
    
    /**
     * Lista todas las órdenes
     */
    public function index(): void
    {
        $estado = $this->get('estado');
        $fechaDesde = $this->get('fecha_desde');
        $fechaHasta = $this->get('fecha_hasta');
        
        $filters = [];
        if ($estado) $filters['estado'] = $estado;
        if ($fechaDesde) $filters['fecha_desde'] = $fechaDesde;
        if ($fechaHasta) $filters['fecha_hasta'] = $fechaHasta;
        
        $ordenes = $this->ordenModel->getAllWithDetails($filters);
        
        $this->view('ordenes/index', [
            'ordenes' => $ordenes,
            'filters' => $filters
        ]);
    }
    
    /**
     * Muestra el formulario para crear una orden
     */
    public function create(): void
    {
        // Obtener clientes activos para el selector
        $clientes = $this->clienteModel->getActiveClientes();
        
        $this->view('ordenes/create', [
            'clientes' => $clientes
        ]);
    }
    
    /**
     * Guarda una nueva orden
     */
    public function store(): void
    {
        try {
            // 1. PROCESAR CLIENTE (nuevo o existente)
            $clienteId = $this->post('cliente_id');
            
            if (empty($clienteId) || $clienteId === 'nuevo') {
                // Crear nuevo cliente
                $clienteData = [
                    'nombre' => $this->post('cliente_nombre'),
                    'contacto_telefono' => $this->post('cliente_telefono'),
                    'contacto_email' => $this->post('cliente_email'),
                    'direccion' => $this->post('cliente_direccion'),
                    'notas' => ''
                ];
                
                // Validar datos del cliente
                if (empty($clienteData['nombre']) || empty($clienteData['contacto_telefono'])) {
                    $_SESSION['error'] = 'El nombre y teléfono del cliente son requeridos';
                    header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/nuevo');
                    exit;
                }
                
                $clienteId = $this->clienteModel->create($clienteData);
            }
            
            // 2. CREAR BICICLETA
            $bicicletaData = [
                'id_cliente' => $clienteId,
                'marca' => $this->post('bicicleta_marca'),
                'modelo' => $this->post('bicicleta_modelo'),
                'tipo' => $this->post('bicicleta_tipo'),
                'color' => $this->post('bicicleta_color'),
                'numero_serie' => $this->post('bicicleta_numero_serie'),
                'año_fabricacion' => $this->post('bicicleta_año') ?: null,
                'notas' => $this->post('bicicleta_notas')
            ];
            
            // Validar datos de la bicicleta
            if (empty($bicicletaData['marca']) || empty($bicicletaData['modelo'])) {
                $_SESSION['error'] = 'La marca y modelo de la bicicleta son requeridos';
                header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/nuevo');
                exit;
            }
            
            // Insertar bicicleta usando el método del modelo
            $bicicletaId = $this->ordenModel->createBicicleta($bicicletaData);
            
            // 3. CREAR ORDEN DE SERVICIO
            $ordenData = [
                'id_cliente' => $clienteId,
                'id_bicicleta' => $bicicletaId,
                'id_usuario_creador' => $_SESSION['usuario_id'],
                'id_usuario_asignado' => $_SESSION['usuario_id'], // Asignar al técnico que crea
                'descripcion_problema' => $this->post('descripcion_problema'),
                'diagnostico_tecnico' => $this->post('diagnostico_inicial') ?: null,
                'observaciones_entrega' => $this->post('observaciones') ?: null,
                'estado' => 'pendiente',
                'prioridad' => $this->post('prioridad') ?: 'Normal',
                'fecha_estimada_entrega' => $this->post('fecha_estimada') ?: null,
                'requiere_aprobacion' => isset($_POST['requiere_aprobacion']) ? 1 : 0,
                'aprobado_por_cliente' => 0
            ];
            
            // Validar descripción del problema
            if (empty($ordenData['descripcion_problema'])) {
                $_SESSION['error'] = 'La descripción del problema es requerida';
                header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/nuevo');
                exit;
            }
            
            $ordenId = $this->ordenModel->createOrden($ordenData);
            
            // Obtener el código de seguimiento generado
            $ordenCreada = $this->ordenModel->find($ordenId);
            $codigoSeguimiento = $ordenCreada['codigo_seguimiento'];
            
            $_SESSION['success'] = "Orden #{$ordenId} creada exitosamente. Código de seguimiento: <strong>{$codigoSeguimiento}</strong>";
            header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/' . $ordenId);
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al crear la orden: ' . $e->getMessage();
            header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/nuevo');
            exit;
        }
    }
    
    /**
     * Muestra una orden específica
     */
    public function show(int $id): void
    {
        $orden = $this->ordenModel->getFullDetails($id);
        
        if (!$orden) {
            http_response_code(404);
            echo "Orden no encontrada";
            return;
        }
        
        $detalleProductos = $this->ordenModel->getDetalleProductos($id);
        $historialEstados = $this->ordenModel->getHistorialEstados($id);
        
        $this->view('ordenes/show', [
            'orden' => $orden,
            'detalleProductos' => $detalleProductos,
            'historialEstados' => $historialEstados
        ]);
    }
    
    /**
     * Cambia el estado de una orden
     */
    public function cambiarEstado(int $id): void
    {
        $nuevoEstado = $this->post('estado');
        $comentario = $this->post('comentario') ?: '';
        
        if (empty($nuevoEstado)) {
            $_SESSION['error'] = 'El estado es requerido';
            header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/' . $id);
            exit;
        }
        
        try {
            // Obtener la orden actual
            $orden = $this->ordenModel->find($id);
            
            if (!$orden) {
                $_SESSION['error'] = 'Orden no encontrada';
                header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes');
                exit;
            }
            
            $estadoAnterior = $orden['estado'];
            
            // Si el estado es el mismo, no hacer nada
            if ($estadoAnterior === $nuevoEstado) {
                $_SESSION['error'] = 'La orden ya tiene ese estado';
                header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/' . $id);
                exit;
            }
            
            // Actualizar el estado
            $resultado = $this->ordenModel->actualizarEstado(
                $id, 
                $nuevoEstado, 
                $_SESSION['usuario_id'],
                $comentario
            );
            
            if ($resultado) {
                $_SESSION['success'] = "Estado actualizado exitosamente: {$estadoAnterior} → {$nuevoEstado}";
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el estado';
            }
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al cambiar el estado: ' . $e->getMessage();
        }
        
        header('Location: /UNIVERSIDAD/Integrador/7service/public/ordenes/' . $id);
        exit;
    }
    
    /**
     * API: Busca clientes (para autocompletado)
     */
    public function apiSearchClientes(): void
    {
        $term = $this->get('term');
        
        if (!$term) {
            $this->json(['success' => false, 'data' => []]);
            return;
        }
        
        $clientes = $this->clienteModel->search($term);
        
        $this->json([
            'success' => true,
            'data' => $clientes
        ]);
    }
}
