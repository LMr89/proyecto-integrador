<?php
/**
 * Archivo de Rutas
 * 
 * Define todas las rutas de la aplicación
 * 
 * @package Config
 */

use App\Core\Router;

$router = new Router();

// ========================================
// RUTAS PÚBLICAS (Sin autenticación)
// ========================================

// Autenticación
$router->get('/', 'AuthController@showLogin');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Seguimiento Público (sin autenticación)
$router->get('/seguimiento', 'SeguimientoController@index');
$router->post('/seguimiento/buscar', 'SeguimientoController@buscar');
$router->get('/seguimiento/{codigo}', 'SeguimientoController@ver');
$router->get('/api/seguimiento/{codigo}', 'SeguimientoController@apiEstado');

// ========================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// ========================================

// Dashboard
$router->get('/dashboard', 'DashboardController@index', ['AuthMiddleware']);
$router->get('/api/estadisticas', 'DashboardController@getEstadisticas', ['AuthMiddleware']);

// Clientes
$router->get('/clientes', 'ClienteController@index', ['AuthMiddleware']);
$router->get('/clientes/nuevo', 'ClienteController@create', ['AuthMiddleware']);
$router->post('/clientes/nuevo', 'ClienteController@store', ['AuthMiddleware']);
$router->get('/clientes/{id}', 'ClienteController@show', ['AuthMiddleware']);
$router->get('/clientes/{id}/editar', 'ClienteController@edit', ['AuthMiddleware']);
$router->post('/clientes/{id}/actualizar', 'ClienteController@update', ['AuthMiddleware']);
$router->post('/clientes/{id}/eliminar', 'ClienteController@delete', ['AuthMiddleware']);
$router->get('/api/clientes/buscar', 'ClienteController@apiSearch', ['AuthMiddleware']);

// Órdenes de Servicio
$router->get('/ordenes', 'OrdenController@index', ['AuthMiddleware']);
$router->get('/ordenes/nuevo', 'OrdenController@create', ['AuthMiddleware']);
$router->post('/ordenes/nuevo', 'OrdenController@store', ['AuthMiddleware']);
$router->get('/ordenes/{id}', 'OrdenController@show', ['AuthMiddleware']);
$router->get('/ordenes/{id}/editar', 'OrdenController@edit', ['AuthMiddleware']);
$router->post('/ordenes/{id}/actualizar', 'OrdenController@update', ['AuthMiddleware']);
$router->post('/ordenes/{id}/cambiar-estado', 'OrdenController@cambiarEstado', ['AuthMiddleware']);

// Inventario/Productos
$router->get('/inventario', 'InventarioController@index', ['AuthMiddleware']);
$router->get('/inventario/nuevo', 'InventarioController@create', ['AuthMiddleware']);
$router->post('/inventario/nuevo', 'InventarioController@store', ['AuthMiddleware']);
$router->get('/inventario/{id}/editar', 'InventarioController@edit', ['AuthMiddleware']);
$router->post('/inventario/{id}/actualizar', 'InventarioController@update', ['AuthMiddleware']);
$router->post('/inventario/{id}/eliminar', 'InventarioController@delete', ['AuthMiddleware']);
$router->get('/api/productos/buscar', 'InventarioController@apiSearch', ['AuthMiddleware']);

// Usuarios (solo admin)
$router->get('/usuarios', 'UsuarioController@index', ['AuthMiddleware']);
$router->get('/usuarios/crear', 'AuthController@showRegister', ['AuthMiddleware']);
$router->post('/usuarios/crear', 'AuthController@register', ['AuthMiddleware']);

return $router;
