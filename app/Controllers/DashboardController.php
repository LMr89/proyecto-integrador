<?php
/**
 * Controlador del Dashboard
 * 
 * Muestra estadísticas y visión general del sistema
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\OrdenServicio;
use App\Models\Cliente;
use App\Models\Producto;

class DashboardController extends Controller
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
     * Muestra el dashboard principal
     */
    public function index(): void
    {
        $usuario = $this->auth();
        
        // Si es técnico, mostrar dashboard específico
        if ($_SESSION['usuario_rol'] === 'tecnico') {
            $this->dashboardTecnico();
            return;
        }
        
        // Dashboard de admin
        // Obtener estadísticas generales
        $estadisticas = $this->ordenModel->getEstadisticas();
        
        // Órdenes pendientes
        $ordenesPendientes = $this->ordenModel->getAllWithDetails(['estado' => 'Pendiente']);
        
        // Productos con stock bajo
        $productosStockBajo = $this->productoModel->getProductosStockBajo();
        
        // Órdenes recientes (últimas 10)
        $ordenesRecientes = $this->ordenModel->getAllWithDetails();
        $ordenesRecientes = array_slice($ordenesRecientes, 0, 10);
        
        $this->view('dashboard/index', [
            'usuario' => $usuario,
            'estadisticas' => $estadisticas,
            'ordenesPendientes' => $ordenesPendientes,
            'productosStockBajo' => $productosStockBajo,
            'ordenesRecientes' => $ordenesRecientes
        ]);
    }
    
    /**
     * Dashboard específico para técnicos
     */
    private function dashboardTecnico(): void
    {
        $tecnicoId = $_SESSION['usuario_id'];
        
        // Estadísticas del técnico
        $stats = [
            'ordenes_hoy' => $this->ordenModel->countBy([
                'id_usuario_asignado' => $tecnicoId,
                'fecha' => date('Y-m-d')
            ]),
            'ordenes_pendientes' => $this->ordenModel->countBy([
                'id_usuario_asignado' => $tecnicoId,
                'estado' => 'pendiente'
            ]),
            'ordenes_proceso' => $this->ordenModel->countBy([
                'id_usuario_asignado' => $tecnicoId,
                'estado' => 'en_proceso'
            ]),
            'ordenes_completadas_hoy' => $this->ordenModel->countBy([
                'id_usuario_asignado' => $tecnicoId,
                'estado' => 'completado',
                'fecha' => date('Y-m-d')
            ])
        ];
        
        // Órdenes activas del técnico
        $ordenes_activas = $this->ordenModel->getOrdenesByTecnico($tecnicoId);
        
        $this->view('dashboard/tecnico', [
            'stats' => $stats,
            'ordenes_activas' => $ordenes_activas
        ]);
    }
    
    /**
     * Obtiene estadísticas en formato JSON (para AJAX)
     */
    public function getEstadisticas(): void
    {
        $fechaDesde = $this->get('fecha_desde');
        $fechaHasta = $this->get('fecha_hasta');
        
        $filters = [];
        if ($fechaDesde) $filters['fecha_desde'] = $fechaDesde;
        if ($fechaHasta) $filters['fecha_hasta'] = $fechaHasta;
        
        $estadisticas = $this->ordenModel->getEstadisticas($filters);
        
        $this->json([
            'success' => true,
            'data' => $estadisticas
        ]);
    }
}
