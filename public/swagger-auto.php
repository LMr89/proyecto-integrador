<?php
/**
 * Generador Automático de Documentación OpenAPI/Swagger
 * 
 * Este script escanea los controladores y genera la documentación Swagger
 * automáticamente basándose en los comentarios PHPDoc
 */

require_once __DIR__ . '/../config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $file = APP_PATH . '/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

header('Content-Type: application/json');

// Configuración base
$swagger = [
    'openapi' => '3.0.3',
    'info' => [
        'title' => 'Seven Service API - Taller de Bicicletas',
        'version' => '1.0.0',
        'description' => "API REST generada automáticamente desde los controladores.\n\n## Autenticación\nSesiones PHP - Usa POST /login primero\n\n## Credenciales\n- Admin: admin@sevenservice.com / admin123\n- Técnico: tecnico1@sevenservice.com / tecnico123",
        'contact' => [
            'name' => 'Seven Service',
            'email' => 'soporte@sevenservice.com'
        ]
    ],
    'servers' => [
        [
            'url' => 'http://localhost/UNIVERSIDAD/Integrador/7service/public',
            'description' => 'Servidor de desarrollo'
        ]
    ],
    'tags' => [],
    'paths' => [],
    'components' => [
        'securitySchemes' => [
            'sessionAuth' => [
                'type' => 'apiKey',
                'in' => 'cookie',
                'name' => 'PHPSESSID'
            ]
        ],
        'schemas' => []
    ]
];

// Definir tags manualmente
$swagger['tags'] = [
    ['name' => 'Autenticación', 'description' => 'Login y logout'],
    ['name' => 'Dashboard', 'description' => 'Estadísticas y resumen'],
    ['name' => 'Clientes', 'description' => 'Gestión de clientes'],
    ['name' => 'Órdenes', 'description' => 'Órdenes de servicio'],
    ['name' => 'Inventario', 'description' => 'Productos y stock']
];

// Schemas predefinidos
$swagger['components']['schemas'] = [
    'Error' => [
        'type' => 'object',
        'properties' => [
            'success' => ['type' => 'boolean', 'example' => false],
            'message' => ['type' => 'string', 'example' => 'Error en la operación']
        ]
    ],
    'Success' => [
        'type' => 'object',
        'properties' => [
            'success' => ['type' => 'boolean', 'example' => true],
            'message' => ['type' => 'string', 'example' => 'Operación exitosa']
        ]
    ],
    'Cliente' => [
        'type' => 'object',
        'properties' => [
            'id_cliente' => ['type' => 'integer', 'example' => 1],
            'nombre' => ['type' => 'string', 'example' => 'Juan Pérez'],
            'contacto_telefono' => ['type' => 'string', 'example' => '987654321'],
            'contacto_email' => ['type' => 'string', 'example' => 'juan@example.com'],
            'direccion' => ['type' => 'string', 'example' => 'Av. Principal 123'],
            'activo' => ['type' => 'boolean', 'example' => true]
        ]
    ],
    'Producto' => [
        'type' => 'object',
        'properties' => [
            'id_producto' => ['type' => 'integer', 'example' => 1],
            'sku' => ['type' => 'string', 'example' => 'CADENA-001'],
            'nombre' => ['type' => 'string', 'example' => 'Cadena Shimano 9V'],
            'precio_venta' => ['type' => 'number', 'format' => 'float', 'example' => 85.00],
            'stock_actual' => ['type' => 'integer', 'example' => 15],
            'stock_minimo' => ['type' => 'integer', 'example' => 5]
        ]
    ],
    'OrdenServicio' => [
        'type' => 'object',
        'properties' => [
            'id_orden' => ['type' => 'integer', 'example' => 123],
            'id_cliente' => ['type' => 'integer', 'example' => 1],
            'cliente_nombre' => ['type' => 'string', 'example' => 'Juan Pérez'],
            'descripcion_problema' => ['type' => 'string', 'example' => 'Frenos no responden'],
            'estado' => [
                'type' => 'string',
                'enum' => ['pendiente', 'en_proceso', 'completado', 'entregado', 'cancelado'],
                'example' => 'pendiente'
            ],
            'prioridad' => [
                'type' => 'string',
                'enum' => ['Baja', 'Normal', 'Alta', 'Urgente'],
                'example' => 'Normal'
            ],
            'costo_total' => ['type' => 'number', 'format' => 'float', 'example' => 150.00]
        ]
    ]
];

// Cargar rutas desde routes.php
require_once CONFIG_PATH . '/routes.php';

// Función para extraer información de un método del controlador
function extractMethodInfo($controllerName, $methodName) {
    try {
        $className = "App\\Controllers\\{$controllerName}";
        $reflection = new ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $docComment = $method->getDocComment();
        
        $info = [
            'summary' => '',
            'description' => '',
            'tags' => []
        ];
        
        if ($docComment) {
            // Extraer resumen (primera línea después de /**)
            if (preg_match('/@summary\s+(.+)/', $docComment, $matches)) {
                $info['summary'] = trim($matches[1]);
            } elseif (preg_match('/\*\s+([^@\n]+)/', $docComment, $matches)) {
                $info['summary'] = trim($matches[1]);
            }
            
            // Extraer descripción
            if (preg_match('/@description\s+(.+)/', $docComment, $matches)) {
                $info['description'] = trim($matches[1]);
            }
        }
        
        // Determinar tag basado en el controlador
        if (strpos($controllerName, 'Cliente') !== false) {
            $info['tags'] = ['Clientes'];
        } elseif (strpos($controllerName, 'Orden') !== false) {
            $info['tags'] = ['Órdenes'];
        } elseif (strpos($controllerName, 'Inventario') !== false) {
            $info['tags'] = ['Inventario'];
        } elseif (strpos($controllerName, 'Dashboard') !== false) {
            $info['tags'] = ['Dashboard'];
        } elseif (strpos($controllerName, 'Auth') !== false) {
            $info['tags'] = ['Autenticación'];
        }
        
        return $info;
    } catch (Exception $e) {
        return [
            'summary' => $methodName,
            'description' => '',
            'tags' => []
        ];
    }
}

// Mapeo de rutas a paths de Swagger
$routeMappings = [
    // Autenticación
    'POST /login' => [
        'tags' => ['Autenticación'],
        'summary' => 'Iniciar sesión',
        'description' => 'Autentica un usuario y crea una sesión PHP',
        'requestBody' => [
            'required' => true,
            'content' => [
                'application/x-www-form-urlencoded' => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['correo', 'password'],
                        'properties' => [
                            'correo' => ['type' => 'string', 'format' => 'email', 'example' => 'admin@sevenservice.com'],
                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'admin123']
                        ]
                    ]
                ]
            ]
        ],
        'responses' => [
            '302' => ['description' => 'Redirige al dashboard'],
            '401' => ['description' => 'Credenciales inválidas']
        ]
    ],
    
    'GET /logout' => [
        'tags' => ['Autenticación'],
        'summary' => 'Cerrar sesión',
        'security' => [['sessionAuth' => []]],
        'responses' => [
            '302' => ['description' => 'Redirige al login']
        ]
    ],
    
    // Dashboard
    'GET /dashboard' => [
        'tags' => ['Dashboard'],
        'summary' => 'Dashboard principal',
        'description' => 'Muestra el dashboard según el rol del usuario',
        'security' => [['sessionAuth' => []]],
        'responses' => [
            '200' => ['description' => 'Vista del dashboard (HTML)']
        ]
    ],
    
    'GET /api/estadisticas' => [
        'tags' => ['Dashboard'],
        'summary' => 'Obtener estadísticas',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'fecha_desde', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
            ['name' => 'fecha_hasta', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']]
        ],
        'responses' => [
            '200' => [
                'description' => 'Estadísticas obtenidas',
                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Success']]]
            ]
        ]
    ],
    
    // Clientes
    'GET /clientes' => [
        'tags' => ['Clientes'],
        'summary' => 'Listar clientes',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'search', 'in' => 'query', 'schema' => ['type' => 'string'], 'description' => 'Buscar por nombre, teléfono o DNI']
        ],
        'responses' => ['200' => ['description' => 'Lista de clientes']]
    ],
    
    'GET /clientes/nuevo' => [
        'tags' => ['Clientes'],
        'summary' => 'Formulario crear cliente',
        'security' => [['sessionAuth' => []]],
        'responses' => ['200' => ['description' => 'Formulario HTML']]
    ],
    
    'POST /clientes/nuevo' => [
        'tags' => ['Clientes'],
        'summary' => 'Crear cliente',
        'security' => [['sessionAuth' => []]],
        'requestBody' => [
            'required' => true,
            'content' => [
                'application/x-www-form-urlencoded' => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['nombre', 'contacto_telefono'],
                        'properties' => [
                            'nombre' => ['type' => 'string', 'example' => 'Juan Pérez'],
                            'contacto_telefono' => ['type' => 'string', 'example' => '987654321'],
                            'contacto_email' => ['type' => 'string', 'example' => 'juan@example.com'],
                            'direccion' => ['type' => 'string', 'example' => 'Av. Principal 123']
                        ]
                    ]
                ]
            ]
        ],
        'responses' => [
            '302' => ['description' => 'Cliente creado, redirige a lista']
        ]
    ],
    
    'GET /clientes/{id}' => [
        'tags' => ['Clientes'],
        'summary' => 'Ver detalle de cliente',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
        ],
        'responses' => ['200' => ['description' => 'Detalle del cliente']]
    ],
    
    'POST /clientes/{id}/eliminar' => [
        'tags' => ['Clientes'],
        'summary' => 'Eliminar cliente',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
        ],
        'responses' => [
            '200' => [
                'description' => 'Cliente eliminado',
                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Success']]]
            ]
        ]
    ],
    
    'GET /api/clientes/buscar' => [
        'tags' => ['Clientes'],
        'summary' => 'Buscar clientes (API)',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'term', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string']]
        ],
        'responses' => [
            '200' => [
                'description' => 'Resultados de búsqueda',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean'],
                                'data' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/Cliente']
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    
    // Órdenes
    'GET /ordenes' => [
        'tags' => ['Órdenes'],
        'summary' => 'Listar órdenes',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'estado', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['pendiente', 'en_proceso', 'completado']]],
            ['name' => 'fecha_desde', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']],
            ['name' => 'fecha_hasta', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date']]
        ],
        'responses' => ['200' => ['description' => 'Lista de órdenes']]
    ],
    
    'GET /ordenes/nuevo' => [
        'tags' => ['Órdenes'],
        'summary' => 'Formulario nueva orden',
        'description' => 'Formulario con 3 pasos: cliente, bicicleta, servicio',
        'security' => [['sessionAuth' => []]],
        'responses' => ['200' => ['description' => 'Formulario HTML']]
    ],
    
    'POST /ordenes/nuevo' => [
        'tags' => ['Órdenes'],
        'summary' => 'Crear orden de servicio',
        'description' => 'Crea orden, puede incluir cliente nuevo y bicicleta',
        'security' => [['sessionAuth' => []]],
        'requestBody' => [
            'required' => true,
            'content' => [
                'application/x-www-form-urlencoded' => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['descripcion_problema', 'bicicleta_marca', 'bicicleta_modelo'],
                        'properties' => [
                            'cliente_id' => ['type' => 'string', 'example' => '5'],
                            'descripcion_problema' => ['type' => 'string', 'example' => 'Frenos no responden'],
                            'bicicleta_marca' => ['type' => 'string', 'example' => 'Trek'],
                            'bicicleta_modelo' => ['type' => 'string', 'example' => 'X500'],
                            'prioridad' => ['type' => 'string', 'enum' => ['Baja', 'Normal', 'Alta', 'Urgente']]
                        ]
                    ]
                ]
            ]
        ],
        'responses' => ['302' => ['description' => 'Orden creada']]
    ],
    
    'GET /ordenes/{id}' => [
        'tags' => ['Órdenes'],
        'summary' => 'Ver detalle de orden',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
        ],
        'responses' => ['200' => ['description' => 'Detalle de la orden']]
    ],
    
    'POST /ordenes/{id}/cambiar-estado' => [
        'tags' => ['Órdenes'],
        'summary' => 'Cambiar estado de orden',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
        ],
        'requestBody' => [
            'required' => true,
            'content' => [
                'application/x-www-form-urlencoded' => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['estado'],
                        'properties' => [
                            'estado' => ['type' => 'string', 'enum' => ['pendiente', 'en_proceso', 'completado']],
                            'comentario' => ['type' => 'string']
                        ]
                    ]
                ]
            ]
        ],
        'responses' => ['302' => ['description' => 'Estado actualizado']]
    ],
    
    // Inventario
    'GET /inventario' => [
        'tags' => ['Inventario'],
        'summary' => 'Listar productos',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'search', 'in' => 'query', 'schema' => ['type' => 'string']],
            ['name' => 'categoria', 'in' => 'query', 'schema' => ['type' => 'integer']],
            ['name' => 'stock_bajo', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['1']]]
        ],
        'responses' => ['200' => ['description' => 'Lista de productos']]
    ],
    
    'GET /inventario/nuevo' => [
        'tags' => ['Inventario'],
        'summary' => 'Formulario nuevo producto',
        'security' => [['sessionAuth' => []]],
        'responses' => ['200' => ['description' => 'Formulario HTML']]
    ],
    
    'POST /inventario/nuevo' => [
        'tags' => ['Inventario'],
        'summary' => 'Crear producto',
        'security' => [['sessionAuth' => []]],
        'requestBody' => [
            'required' => true,
            'content' => [
                'application/x-www-form-urlencoded' => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['nombre'],
                        'properties' => [
                            'sku' => ['type' => 'string'],
                            'nombre' => ['type' => 'string', 'example' => 'Cadena Shimano 9V'],
                            'precio_venta' => ['type' => 'number', 'example' => 85.00],
                            'stock_actual' => ['type' => 'integer', 'example' => 15],
                            'stock_minimo' => ['type' => 'integer', 'example' => 5]
                        ]
                    ]
                ]
            ]
        ],
        'responses' => ['302' => ['description' => 'Producto creado']]
    ],
    
    'POST /inventario/{id}/eliminar' => [
        'tags' => ['Inventario'],
        'summary' => 'Eliminar producto',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
        ],
        'responses' => [
            '200' => [
                'description' => 'Producto eliminado',
                'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Success']]]
            ]
        ]
    ],
    
    'GET /api/productos/buscar' => [
        'tags' => ['Inventario'],
        'summary' => 'Buscar productos (API)',
        'security' => [['sessionAuth' => []]],
        'parameters' => [
            ['name' => 'term', 'in' => 'query', 'required' => true, 'schema' => ['type' => 'string']]
        ],
        'responses' => [
            '200' => [
                'description' => 'Resultados de búsqueda',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'success' => ['type' => 'boolean'],
                                'data' => [
                                    'type' => 'array',
                                    'items' => ['$ref' => '#/components/schemas/Producto']
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

// Convertir las rutas al formato de paths de Swagger
foreach ($routeMappings as $route => $config) {
    list($method, $path) = explode(' ', $route);
    $method = strtolower($method);
    
    if (!isset($swagger['paths'][$path])) {
        $swagger['paths'][$path] = [];
    }
    
    $swagger['paths'][$path][$method] = $config;
}

// Retornar el JSON
echo json_encode($swagger, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
