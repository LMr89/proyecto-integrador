<?php
/**
 * Controlador de Clientes
 * 
 * Gestiona el CRUD de clientes y su historial
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\Cliente;

class ClienteController extends Controller
{
    private Cliente $clienteModel;
    
    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Lista todos los clientes
     */
    public function index(): void
    {
        $search = $this->get('search');
        
        if ($search) {
            $clientes = $this->clienteModel->search($search);
        } else {
            $clientes = $this->clienteModel->getActiveClientes();
        }
        
        $this->view('clientes/index', [
            'clientes' => $clientes,
            'search' => $search
        ]);
    }
    
    /**
     * Muestra el formulario para crear un cliente
     */
    public function create(): void
    {
        $this->view('clientes/create');
    }
    
    /**
     * Guarda un nuevo cliente
     */
    public function store(): void
    {
        $data = [
            'nombre' => $this->post('nombre'),
            'contacto_telefono' => $this->post('contacto_telefono'),
            'contacto_email' => $this->post('contacto_email'),
            'direccion' => $this->post('contacto_direccion'),
            'notas' => '' // Campo opcional
        ];
        
        try {
            // Validar datos requeridos
            if (empty($data['nombre']) || strlen($data['nombre']) < 3) {
                $_SESSION['error'] = 'El nombre debe tener al menos 3 caracteres';
                redirect('/clientes/nuevo');
            }

            if (empty($data['contacto_telefono'])) {
                $_SESSION['error'] = 'El teléfono es requerido';
                redirect('/clientes/nuevo');
            }

            // Crear cliente
            $id = $this->clienteModel->create($data);

            $_SESSION['success'] = 'Cliente creado exitosamente';
            redirect('/clientes');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al crear cliente: ' . $e->getMessage();
            redirect('/clientes/nuevo');
        }
    }
    
    /**
     * Muestra un cliente específico
     */
    public function show(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            http_response_code(404);
            echo "Cliente no encontrado";
            return;
        }
        
        $historial = $this->clienteModel->getHistorial($id);
        $bicicletas = $this->clienteModel->getBicicletas($id);
        $ordenes = $this->clienteModel->getOrdenes($id, 5);
        
        $this->view('clientes/show', [
            'cliente' => $cliente,
            'historial' => $historial,
            'bicicletas' => $bicicletas,
            'ordenes' => $ordenes
        ]);
    }
    
    /**
     * Muestra el formulario para editar un cliente
     */
    public function edit(int $id): void
    {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            http_response_code(404);
            echo "Cliente no encontrado";
            return;
        }
        
        $this->view('clientes/edit', ['cliente' => $cliente]);
    }
    
    /**
     * Actualiza un cliente
     */
    public function update(int $id): void
    {
        $data = [
            'nombre' => $this->post('nombre'),
            'contacto_telefono' => $this->post('contacto_telefono'),
            'contacto_email' => $this->post('contacto_email'),
            'direccion' => $this->post('direccion'),
            'notas' => $this->post('notas')
        ];
        
        // Validar
        $errors = $this->validate($data, [
            'nombre' => 'required|min:3',
            'contacto_telefono' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 400);
            return;
        }
        
        try {
            $this->clienteModel->update($id, $this->sanitize($data));
            
            $this->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al actualizar cliente'], 500);
        }
    }
    
    /**
     * Elimina (desactiva) un cliente
     */
    public function delete(int $id): void
    {
        try {
            $this->clienteModel->delete($id);
            
            $this->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar cliente'], 500);
        }
    }
    
    /**
     * API: Busca clientes (para autocompletado)
     */
    public function apiSearch(): void
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
