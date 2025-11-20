<?php
/**
 * Documentación de API - Seven Service
 * Este archivo muestra todos los endpoints disponibles para consumo externo
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Permitir CORS para desarrollo
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Si es una petición OPTIONS (preflight), responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Cargar configuración para obtener la URL base
require_once __DIR__ . '/../config/config.php';
$baseUrl = rtrim(APP_URL, '/');

$apiDocs = [
    'nombre' => 'Seven Service API',
    'version' => '1.0.0',
    'descripcion' => 'API REST para el sistema de gestión de taller de bicicletas',
    'base_url' => $baseUrl,
    'documentacion_swagger' => $baseUrl . '/swagger-ui.html',
    
    'autenticacion' => [
        'tipo' => 'Session-based',
        'descripcion' => 'Después del login, se crea una sesión PHP que se mantiene con cookies',
        'endpoints' => [
            [
                'metodo' => 'POST',
                'ruta' => '/process_login.php',
                'descripcion' => 'Iniciar sesión',
                'parametros' => [
                    'correo' => 'string (email del usuario)',
                    'password' => 'string (contraseña)'
                ],
                'respuesta_exitosa' => 'Redirección al dashboard y creación de sesión',
                'ejemplo' => [
                    'correo' => 'admin@sevenservice.com',
                    'password' => 'admin123'
                ]
            ],
            [
                'metodo' => 'GET',
                'ruta' => '/logout',
                'descripcion' => 'Cerrar sesión',
                'respuesta' => 'Destruye la sesión y redirige al login'
            ]
        ]
    ],
    
    'endpoints' => [
        [
            'categoria' => 'Dashboard',
            'rutas' => [
                [
                    'metodo' => 'GET',
                    'ruta' => '/api/estadisticas',
                    'descripcion' => 'Obtiene estadísticas generales del sistema',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'fecha_inicio' => 'opcional (YYYY-MM-DD)',
                        'fecha_fin' => 'opcional (YYYY-MM-DD)'
                    ],
                    'respuesta_ejemplo' => [
                        'total_ordenes' => 150,
                        'ordenes_pendientes' => 12,
                        'ordenes_en_reparacion' => 8,
                        'ingresos_totales' => 15000.00,
                        'ingresos_mes_actual' => 3500.00
                    ]
                ]
            ]
        ],
        [
            'categoria' => 'Clientes',
            'rutas' => [
                [
                    'metodo' => 'GET',
                    'ruta' => '/clientes',
                    'descripcion' => 'Lista todos los clientes (vista HTML)',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'GET',
                    'ruta' => '/api/clientes/buscar',
                    'descripcion' => 'Buscar clientes (JSON)',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'term' => 'string (término de búsqueda)'
                    ],
                    'respuesta_ejemplo' => [
                        [
                            'id_cliente' => 1,
                            'nombre' => 'Juan Pérez',
                            'telefono' => '987654321',
                            'correo' => 'juan@example.com'
                        ]
                    ]
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/clientes/crear',
                    'descripcion' => 'Crear nuevo cliente',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'nombre' => 'string (requerido)',
                        'telefono' => 'string (requerido)',
                        'correo' => 'string (email, opcional)',
                        'direccion' => 'string (opcional)',
                        'dni' => 'string (opcional)'
                    ]
                ],
                [
                    'metodo' => 'GET',
                    'ruta' => '/clientes/{id}',
                    'descripcion' => 'Ver detalle de un cliente',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/clientes/{id}/actualizar',
                    'descripcion' => 'Actualizar datos de cliente',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/clientes/{id}/eliminar',
                    'descripcion' => 'Eliminar cliente (soft delete)',
                    'autenticacion_requerida' => true
                ]
            ]
        ],
        [
            'categoria' => 'Órdenes de Servicio',
            'rutas' => [
                [
                    'metodo' => 'GET',
                    'ruta' => '/ordenes',
                    'descripcion' => 'Lista todas las órdenes',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/ordenes/crear',
                    'descripcion' => 'Crear nueva orden de servicio',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'id_cliente' => 'int (requerido)',
                        'id_bicicleta' => 'int (requerido)',
                        'descripcion_problema' => 'text (requerido)',
                        'prioridad' => 'enum: normal, urgente (requerido)',
                        'productos' => 'array de productos usados (opcional)'
                    ]
                ],
                [
                    'metodo' => 'GET',
                    'ruta' => '/ordenes/{id}',
                    'descripcion' => 'Ver detalle de una orden',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/ordenes/{id}/cambiar-estado',
                    'descripcion' => 'Cambiar estado de una orden',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'nuevo_estado' => 'enum: pendiente, en_reparacion, esperando_repuestos, completada, entregada, cancelada'
                    ]
                ]
            ]
        ],
        [
            'categoria' => 'Inventario/Productos',
            'rutas' => [
                [
                    'metodo' => 'GET',
                    'ruta' => '/inventario',
                    'descripcion' => 'Lista todos los productos',
                    'autenticacion_requerida' => true
                ],
                [
                    'metodo' => 'GET',
                    'ruta' => '/api/productos/buscar',
                    'descripcion' => 'Buscar productos (JSON)',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'term' => 'string (término de búsqueda)'
                    ]
                ],
                [
                    'metodo' => 'POST',
                    'ruta' => '/inventario/crear',
                    'descripcion' => 'Agregar nuevo producto',
                    'autenticacion_requerida' => true,
                    'parametros' => [
                        'sku' => 'string (único, requerido)',
                        'nombre' => 'string (requerido)',
                        'id_categoria' => 'int (requerido)',
                        'id_proveedor' => 'int (requerido)',
                        'precio_compra' => 'decimal (requerido)',
                        'precio_venta' => 'decimal (requerido)',
                        'stock_actual' => 'int (requerido)',
                        'stock_minimo' => 'int (requerido)'
                    ]
                ]
            ]
        ]
    ],
    
    'modelos_de_datos' => [
        'Cliente' => [
            'id_cliente' => 'int',
            'nombre' => 'string',
            'telefono' => 'string',
            'correo' => 'string|null',
            'direccion' => 'string|null',
            'dni' => 'string|null',
            'activo' => 'boolean',
            'fecha_registro' => 'timestamp'
        ],
        'Producto' => [
            'id_producto' => 'int',
            'sku' => 'string',
            'nombre' => 'string',
            'descripcion' => 'text|null',
            'id_categoria' => 'int',
            'id_proveedor' => 'int',
            'precio_compra' => 'decimal(10,2)',
            'precio_venta' => 'decimal(10,2)',
            'stock_actual' => 'int',
            'stock_minimo' => 'int',
            'activo' => 'boolean'
        ],
        'OrdenServicio' => [
            'id_orden' => 'int',
            'numero_orden' => 'string',
            'id_cliente' => 'int',
            'id_bicicleta' => 'int',
            'descripcion_problema' => 'text',
            'diagnostico' => 'text|null',
            'estado' => 'enum',
            'prioridad' => 'enum',
            'fecha_ingreso' => 'timestamp',
            'fecha_entrega_estimada' => 'date|null',
            'fecha_entrega_real' => 'timestamp|null',
            'costo_mano_obra' => 'decimal(10,2)',
            'costo_repuestos' => 'decimal(10,2)',
            'costo_total' => 'decimal(10,2)'
        ]
    ],
    
    'configuracion_cors' => [
        'descripcion' => 'Para desarrollo, CORS está habilitado en este endpoint',
        'produccion' => 'En producción deberás configurar los orígenes permitidos',
        'headers_permitidos' => ['Content-Type', 'Authorization']
    ],
    
    'notas_importantes' => [
        'Las rutas que devuelven HTML son para el frontend integrado',
        'Las rutas bajo /api/ devuelven JSON puro',
        'Todos los endpoints requieren autenticación excepto el login',
        'La autenticación se maneja con sesiones PHP y cookies',
        'Para usar desde un frontend externo (React, Vue, etc.) necesitarás manejar las cookies de sesión',
        'Base de datos remota en túnel SSH (localhost:5060)'
    ],
    
    'ejemplo_uso_fetch' => [
        'javascript' => "
// Login
fetch('$baseUrl/process_login.php', {
    method: 'POST',
    credentials: 'include', // Importante para mantener la sesión
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'correo=admin@sevenservice.com&password=admin123'
});

// Obtener estadísticas
fetch('$baseUrl/api/estadisticas', {
    credentials: 'include' // Envía la cookie de sesión
})
.then(response => response.json())
.then(data => console.log(data));

// Buscar clientes
fetch('$baseUrl/api/clientes/buscar?term=juan', {
    credentials: 'include'
})
.then(response => response.json())
.then(data => console.log(data));
        "
    ],
    
    'swagger_ui' => [
        'url' => $baseUrl . '/swagger-ui.html',
        'descripcion' => 'Interfaz interactiva de Swagger para probar los endpoints',
        'archivo_yaml' => $baseUrl . '/../docs/swagger/swagger.yaml'
    ]
];

echo json_encode($apiDocs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
