<?php
/**
 * Controlador de Inventario
 * 
 * Gestiona el CRUD de productos y su stock
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\Producto;

class InventarioController extends Controller
{
    private Producto $productoModel;
    
    public function __construct()
    {
        $this->productoModel = new Producto();
    }
    
    /**
     * Lista todos los productos del inventario
     */
    public function index(): void
    {
        $search = $this->get('search');
        $categoria = $this->get('categoria');
        $stockBajo = $this->get('stock_bajo');
        
        // Construir filtros
        $filters = [];
        if ($categoria) {
            $filters['categoria'] = $categoria;
        }
        
        // Obtener productos
        if ($stockBajo === '1') {
            $productos = $this->productoModel->getProductosStockBajo();
        } elseif ($search) {
            $productos = $this->productoModel->search($search);
        } else {
            $productos = $this->productoModel->getProductosActivos($filters);
        }
        
        // Obtener categorías para filtro
        $categorias = $this->productoModel->getCategorias();
        
        // Estadísticas
        $stats = [
            'total_productos' => $this->productoModel->count(['activo' => 1]),
            'stock_bajo' => count($this->productoModel->getProductosStockBajo()),
            'valor_inventario' => $this->productoModel->getValorTotalInventario(),
            'sin_stock' => $this->productoModel->count(['stock_actual' => 0, 'activo' => 1])
        ];
        
        $this->view('inventario/index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'categoria' => $categoria,
                'stock_bajo' => $stockBajo
            ]
        ]);
    }
    
    /**
     * Muestra el formulario para crear un producto
     */
    public function create(): void
    {
        $categorias = $this->productoModel->getCategorias();
        $proveedores = $this->productoModel->getProveedores();
        
        $this->view('inventario/create', [
            'categorias' => $categorias,
            'proveedores' => $proveedores
        ]);
    }
    
    /**
     * Guarda un nuevo producto
     */
    public function store(): void
    {
        try {
            $data = [
                'sku' => $this->post('sku'),
                'nombre' => $this->post('nombre'),
                'descripcion' => $this->post('descripcion'),
                'id_categoria' => $this->post('id_categoria') ?: null,
                'id_proveedor' => $this->post('id_proveedor') ?: null,
                'precio_compra' => $this->post('precio_compra') ?: 0,
                'precio_venta' => $this->post('precio_venta') ?: 0,
                'stock_actual' => $this->post('stock_actual') ?: 0,
                'stock_minimo' => $this->post('stock_minimo') ?: 0,
                'unidad_medida' => $this->post('unidad_medida') ?: 'unidad',
                'activo' => 1
            ];
            
            // Validar campos requeridos
            if (empty($data['nombre'])) {
                $_SESSION['error'] = 'El nombre del producto es requerido';
                header('Location: /UNIVERSIDAD/Integrador/7service/public/inventario/nuevo');
                exit;
            }
            
            $id = $this->productoModel->create($data);
            
            $_SESSION['success'] = 'Producto creado exitosamente';
            header('Location: /UNIVERSIDAD/Integrador/7service/public/inventario');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al crear producto: ' . $e->getMessage();
            header('Location: /UNIVERSIDAD/Integrador/7service/public/inventario/nuevo');
            exit;
        }
    }
    
    /**
     * Muestra el formulario para editar un producto
     */
    public function edit(int $id): void
    {
        $producto = $this->productoModel->find($id);
        
        if (!$producto) {
            http_response_code(404);
            echo "Producto no encontrado";
            return;
        }
        
        $categorias = $this->productoModel->getCategorias();
        $proveedores = $this->productoModel->getProveedores();
        
        $this->view('inventario/edit', [
            'producto' => $producto,
            'categorias' => $categorias,
            'proveedores' => $proveedores
        ]);
    }
    
    /**
     * Actualiza un producto
     */
    public function update(int $id): void
    {
        try {
            $data = [
                'sku' => $this->post('sku'),
                'nombre' => $this->post('nombre'),
                'descripcion' => $this->post('descripcion'),
                'id_categoria' => $this->post('id_categoria') ?: null,
                'id_proveedor' => $this->post('id_proveedor') ?: null,
                'precio_compra' => $this->post('precio_compra') ?: 0,
                'precio_venta' => $this->post('precio_venta') ?: 0,
                'stock_actual' => $this->post('stock_actual') ?: 0,
                'stock_minimo' => $this->post('stock_minimo') ?: 0,
                'unidad_medida' => $this->post('unidad_medida') ?: 'unidad'
            ];
            
            $this->productoModel->update($id, $data);
            
            $_SESSION['success'] = 'Producto actualizado exitosamente';
            header('Location: /UNIVERSIDAD/Integrador/7service/public/inventario');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar producto: ' . $e->getMessage();
            header('Location: /UNIVERSIDAD/Integrador/7service/public/inventario/' . $id . '/editar');
            exit;
        }
    }
    
    /**
     * Elimina (desactiva) un producto
     */
    public function delete(int $id): void
    {
        try {
            $this->productoModel->delete($id);
            
            $this->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Error al eliminar producto'
            ], 500);
        }
    }
    
    /**
     * API: Busca productos (para autocompletado en órdenes)
     */
    public function apiSearch(): void
    {
        $term = $this->get('term');
        
        if (!$term) {
            $this->json(['success' => false, 'data' => []]);
            return;
        }
        
        $productos = $this->productoModel->search($term);
        
        $this->json([
            'success' => true,
            'data' => $productos
        ]);
    }
}
