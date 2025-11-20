<?php
/**
 * Script para poblar el inventario con productos de ejemplo
 * Incluye categorías, proveedores y productos típicos de un taller de bicicletas
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

use App\Core\Database;

$db = Database::getInstance();

echo "<h2>🚴 Poblando Inventario de Seven Service</h2>";
echo "<hr>";

try {
    // 1. CREAR CATEGORÍAS
    echo "<h3>1️⃣ Creando Categorías...</h3>";
    
    $categorias = [
        ['nombre' => 'Transmisión', 'descripcion' => 'Cadenas, piñones, platos, desviadores'],
        ['nombre' => 'Frenos', 'descripcion' => 'Pastillas, discos, cables, palancas de freno'],
        ['nombre' => 'Neumáticos', 'descripcion' => 'Llantas, cámaras, sellante'],
        ['nombre' => 'Suspensión', 'descripcion' => 'Horquillas, amortiguadores, aceites'],
        ['nombre' => 'Lubricantes', 'descripcion' => 'Aceites, grasas, limpiadores'],
        ['nombre' => 'Accesorios', 'descripcion' => 'Luces, timbre, espejos, portabidones'],
        ['nombre' => 'Herramientas', 'descripcion' => 'Llaves, extractores, herramientas especiales'],
        ['nombre' => 'Componentes', 'descripcion' => 'Manubrios, potencias, tijas, sillines']
    ];
    
    $categoriaIds = [];
    foreach ($categorias as $cat) {
        $existe = $db->queryOne("SELECT id_categoria FROM categorias WHERE nombre = :nombre", [':nombre' => $cat['nombre']]);
        
        if ($existe) {
            echo "<p>⚠️ Categoría '{$cat['nombre']}' ya existe</p>";
            $categoriaIds[$cat['nombre']] = $existe['id_categoria'];
        } else {
            $db->execute(
                "INSERT INTO categorias (nombre, descripcion, activo) VALUES (:nombre, :descripcion, 1)",
                [':nombre' => $cat['nombre'], ':descripcion' => $cat['descripcion']]
            );
            $categoriaIds[$cat['nombre']] = $db->lastInsertId();
            echo "<p>✅ Categoría '{$cat['nombre']}' creada</p>";
        }
    }
    
    // 2. CREAR PROVEEDORES
    echo "<hr><h3>2️⃣ Creando Proveedores...</h3>";
    
    $proveedores = [
        [
            'nombre' => 'Distribuidora Bike Parts SAC',
            'contacto_nombre' => 'Carlos Rodríguez',
            'telefono' => '987123456',
            'email' => 'ventas@bikeparts.com',
            'direccion' => 'Av. Industrial 456, Lima'
        ],
        [
            'nombre' => 'Shimano Perú',
            'contacto_nombre' => 'María González',
            'telefono' => '987234567',
            'email' => 'info@shimanoperu.com',
            'direccion' => 'Jr. Los Ciclistas 789, San Isidro'
        ],
        [
            'nombre' => 'Trek Distribution',
            'contacto_nombre' => 'Juan Pérez',
            'telefono' => '987345678',
            'email' => 'ventas@trek.pe',
            'direccion' => 'Calle Las Bicis 123, Miraflores'
        ],
        [
            'nombre' => 'Repuestos MTB',
            'contacto_nombre' => 'Ana Torres',
            'telefono' => '987456789',
            'email' => 'contacto@repuestosmtb.com',
            'direccion' => 'Av. Aventura 321, Surco'
        ]
    ];
    
    $proveedorIds = [];
    foreach ($proveedores as $prov) {
        $existe = $db->queryOne("SELECT id_proveedor FROM proveedores WHERE nombre = :nombre", [':nombre' => $prov['nombre']]);
        
        if ($existe) {
            echo "<p>⚠️ Proveedor '{$prov['nombre']}' ya existe</p>";
            $proveedorIds[$prov['nombre']] = $existe['id_proveedor'];
        } else {
            $db->execute(
                "INSERT INTO proveedores (nombre, contacto_nombre, telefono, email, direccion, activo) 
                 VALUES (:nombre, :contacto, :telefono, :email, :direccion, 1)",
                [
                    ':nombre' => $prov['nombre'],
                    ':contacto' => $prov['contacto_nombre'],
                    ':telefono' => $prov['telefono'],
                    ':email' => $prov['email'],
                    ':direccion' => $prov['direccion']
                ]
            );
            $proveedorIds[$prov['nombre']] = $db->lastInsertId();
            echo "<p>✅ Proveedor '{$prov['nombre']}' creado</p>";
        }
    }
    
    // 3. CREAR PRODUCTOS
    echo "<hr><h3>3️⃣ Creando Productos...</h3>";
    
    $productos = [
        // TRANSMISIÓN
        ['sku' => 'CADENA-SH-001', 'nombre' => 'Cadena Shimano HG-95 10V', 'descripcion' => 'Cadena de 10 velocidades para MTB', 'categoria' => 'Transmisión', 'proveedor' => 'Shimano Perú', 'precio_compra' => 45.00, 'precio_venta' => 85.00, 'stock_actual' => 20, 'stock_minimo' => 5, 'unidad_medida' => 'unidad'],
        ['sku' => 'CADENA-SH-002', 'nombre' => 'Cadena Shimano HG-53 9V', 'descripcion' => 'Cadena de 9 velocidades', 'categoria' => 'Transmisión', 'proveedor' => 'Shimano Perú', 'precio_compra' => 38.00, 'precio_venta' => 70.00, 'stock_actual' => 15, 'stock_minimo' => 5, 'unidad_medida' => 'unidad'],
        ['sku' => 'CASSETTE-001', 'nombre' => 'Cassette Shimano 11-42T 10V', 'descripcion' => 'Cassette 10 velocidades MTB', 'categoria' => 'Transmisión', 'proveedor' => 'Shimano Perú', 'precio_compra' => 120.00, 'precio_venta' => 220.00, 'stock_actual' => 8, 'stock_minimo' => 3, 'unidad_medida' => 'unidad'],
        ['sku' => 'PLATO-001', 'nombre' => 'Plato 32T 104BCD', 'descripcion' => 'Plato monoplato para MTB', 'categoria' => 'Transmisión', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 65.00, 'precio_venta' => 120.00, 'stock_actual' => 12, 'stock_minimo' => 4, 'unidad_medida' => 'unidad'],
        
        // FRENOS
        ['sku' => 'PASTILLA-001', 'nombre' => 'Pastillas Shimano BR-M375', 'descripcion' => 'Pastillas freno mecánico', 'categoria' => 'Frenos', 'proveedor' => 'Shimano Perú', 'precio_compra' => 15.00, 'precio_venta' => 35.00, 'stock_actual' => 30, 'stock_minimo' => 10, 'unidad_medida' => 'par'],
        ['sku' => 'PASTILLA-002', 'nombre' => 'Pastillas Shimano BR-M446 Hidráulico', 'descripcion' => 'Pastillas freno hidráulico', 'categoria' => 'Frenos', 'proveedor' => 'Shimano Perú', 'precio_compra' => 25.00, 'precio_venta' => 50.00, 'stock_actual' => 25, 'stock_minimo' => 8, 'unidad_medida' => 'par'],
        ['sku' => 'DISCO-001', 'nombre' => 'Disco Shimano RT56 180mm', 'descripcion' => 'Disco de freno 6 agujeros', 'categoria' => 'Frenos', 'proveedor' => 'Shimano Perú', 'precio_compra' => 35.00, 'precio_venta' => 70.00, 'stock_actual' => 18, 'stock_minimo' => 6, 'unidad_medida' => 'unidad'],
        ['sku' => 'CABLE-FRENO-001', 'nombre' => 'Cable de Freno Shimano 1.6mm', 'descripcion' => 'Cable freno MTB/Ruta', 'categoria' => 'Frenos', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 8.00, 'precio_venta' => 18.00, 'stock_actual' => 40, 'stock_minimo' => 15, 'unidad_medida' => 'unidad'],
        
        // NEUMÁTICOS
        ['sku' => 'LLANTA-001', 'nombre' => 'Llanta Maxxis Ikon 29x2.2', 'descripcion' => 'Llanta MTB tubeless ready', 'categoria' => 'Neumáticos', 'proveedor' => 'Trek Distribution', 'precio_compra' => 85.00, 'precio_venta' => 160.00, 'stock_actual' => 10, 'stock_minimo' => 4, 'unidad_medida' => 'unidad'],
        ['sku' => 'LLANTA-002', 'nombre' => 'Llanta Schwalbe Rapid Rob 26x2.25', 'descripcion' => 'Llanta MTB uso general', 'categoria' => 'Neumáticos', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 45.00, 'precio_venta' => 90.00, 'stock_actual' => 14, 'stock_minimo' => 6, 'unidad_medida' => 'unidad'],
        ['sku' => 'CAMARA-001', 'nombre' => 'Cámara 29x1.9-2.3', 'descripcion' => 'Cámara MTB válvula presta', 'categoria' => 'Neumáticos', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 12.00, 'precio_venta' => 25.00, 'stock_actual' => 50, 'stock_minimo' => 20, 'unidad_medida' => 'unidad'],
        ['sku' => 'SELLANTE-001', 'nombre' => 'Sellante Tubeless 500ml', 'descripcion' => 'Sellante para neumáticos tubeless', 'categoria' => 'Neumáticos', 'proveedor' => 'Repuestos MTB', 'precio_compra' => 35.00, 'precio_venta' => 65.00, 'stock_actual' => 15, 'stock_minimo' => 5, 'unidad_medida' => 'botella'],
        
        // LUBRICANTES
        ['sku' => 'ACEITE-001', 'nombre' => 'Aceite para Cadena Finish Line Seco', 'descripcion' => 'Lubricante seco 120ml', 'categoria' => 'Lubricantes', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 22.00, 'precio_venta' => 45.00, 'stock_actual' => 25, 'stock_minimo' => 8, 'unidad_medida' => 'botella'],
        ['sku' => 'ACEITE-002', 'nombre' => 'Aceite para Cadena Finish Line Húmedo', 'descripcion' => 'Lubricante húmedo 120ml', 'categoria' => 'Lubricantes', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 22.00, 'precio_venta' => 45.00, 'stock_actual' => 20, 'stock_minimo' => 8, 'unidad_medida' => 'botella'],
        ['sku' => 'GRASA-001', 'nombre' => 'Grasa para Rodamientos Park Tool', 'descripcion' => 'Grasa multiuso 113g', 'categoria' => 'Lubricantes', 'proveedor' => 'Repuestos MTB', 'precio_compra' => 28.00, 'precio_venta' => 55.00, 'stock_actual' => 12, 'stock_minimo' => 4, 'unidad_medida' => 'tubo'],
        ['sku' => 'LIMPIADOR-001', 'nombre' => 'Desengrasante Muc-Off 1L', 'descripcion' => 'Limpiador biodegradable', 'categoria' => 'Lubricantes', 'proveedor' => 'Trek Distribution', 'precio_compra' => 35.00, 'precio_venta' => 70.00, 'stock_actual' => 18, 'stock_minimo' => 6, 'unidad_medida' => 'botella'],
        
        // ACCESORIOS
        ['sku' => 'LUZ-001', 'nombre' => 'Luz Delantera LED USB 400 Lúmenes', 'descripcion' => 'Luz recargable alta potencia', 'categoria' => 'Accesorios', 'proveedor' => 'Trek Distribution', 'precio_compra' => 45.00, 'precio_venta' => 90.00, 'stock_actual' => 15, 'stock_minimo' => 5, 'unidad_medida' => 'unidad'],
        ['sku' => 'LUZ-002', 'nombre' => 'Luz Trasera LED USB', 'descripcion' => 'Luz trasera recargable', 'categoria' => 'Accesorios', 'proveedor' => 'Trek Distribution', 'precio_compra' => 25.00, 'precio_venta' => 50.00, 'stock_actual' => 20, 'stock_minimo' => 8, 'unidad_medida' => 'unidad'],
        ['sku' => 'BIDON-001', 'nombre' => 'Bidón 750ml', 'descripcion' => 'Bidón de agua', 'categoria' => 'Accesorios', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 8.00, 'precio_venta' => 18.00, 'stock_actual' => 35, 'stock_minimo' => 10, 'unidad_medida' => 'unidad'],
        ['sku' => 'PORTABIDON-001', 'nombre' => 'Portabidón Aluminio', 'descripcion' => 'Portabidón ligero', 'categoria' => 'Accesorios', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 12.00, 'precio_venta' => 25.00, 'stock_actual' => 25, 'stock_minimo' => 8, 'unidad_medida' => 'unidad'],
        
        // HERRAMIENTAS
        ['sku' => 'LLAVE-001', 'nombre' => 'Llave Allen Set 2-10mm', 'descripcion' => 'Juego 9 llaves allen', 'categoria' => 'Herramientas', 'proveedor' => 'Repuestos MTB', 'precio_compra' => 35.00, 'precio_venta' => 70.00, 'stock_actual' => 10, 'stock_minimo' => 3, 'unidad_medida' => 'set'],
        ['sku' => 'EXTRACTOR-001', 'nombre' => 'Extractor de Cassette Shimano', 'descripcion' => 'Herramienta para cassette', 'categoria' => 'Herramientas', 'proveedor' => 'Shimano Perú', 'precio_compra' => 28.00, 'precio_venta' => 55.00, 'stock_actual' => 8, 'stock_minimo' => 3, 'unidad_medida' => 'unidad'],
        ['sku' => 'BOMBA-001', 'nombre' => 'Bomba de Piso con Manómetro', 'descripcion' => 'Bomba alta presión 160 PSI', 'categoria' => 'Herramientas', 'proveedor' => 'Trek Distribution', 'precio_compra' => 65.00, 'precio_venta' => 130.00, 'stock_actual' => 6, 'stock_minimo' => 2, 'unidad_medida' => 'unidad'],
        ['sku' => 'KIT-PARCHES-001', 'nombre' => 'Kit de Parches Rema Tip Top', 'descripcion' => 'Kit reparación cámaras', 'categoria' => 'Herramientas', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 8.00, 'precio_venta' => 18.00, 'stock_actual' => 30, 'stock_minimo' => 10, 'unidad_medida' => 'kit'],
        
        // COMPONENTES
        ['sku' => 'MANUBRIO-001', 'nombre' => 'Manubrio MTB 31.8mm 720mm', 'descripcion' => 'Manubrio aluminio plano', 'categoria' => 'Componentes', 'proveedor' => 'Trek Distribution', 'precio_compra' => 45.00, 'precio_venta' => 90.00, 'stock_actual' => 8, 'stock_minimo' => 3, 'unidad_medida' => 'unidad'],
        ['sku' => 'SILLIN-001', 'nombre' => 'Sillín Ergonómico MTB', 'descripcion' => 'Sillín con gel', 'categoria' => 'Componentes', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 35.00, 'precio_venta' => 70.00, 'stock_actual' => 12, 'stock_minimo' => 4, 'unidad_medida' => 'unidad'],
        ['sku' => 'TIJA-001', 'nombre' => 'Tija de Sillín 27.2mm 400mm', 'descripcion' => 'Tija aluminio', 'categoria' => 'Componentes', 'proveedor' => 'Trek Distribution', 'precio_compra' => 28.00, 'precio_venta' => 55.00, 'stock_actual' => 10, 'stock_minimo' => 3, 'unidad_medida' => 'unidad'],
        ['sku' => 'POTENCIA-001', 'nombre' => 'Potencia 31.8mm 70mm', 'descripcion' => 'Potencia aluminio', 'categoria' => 'Componentes', 'proveedor' => 'Distribuidora Bike Parts SAC', 'precio_compra' => 32.00, 'precio_venta' => 65.00, 'stock_actual' => 8, 'stock_minimo' => 3, 'unidad_medida' => 'unidad']
    ];
    
    $contadorCreados = 0;
    $contadorExistentes = 0;
    
    foreach ($productos as $prod) {
        // Verificar si ya existe
        $existe = $db->queryOne("SELECT id_producto FROM productos WHERE sku = :sku", [':sku' => $prod['sku']]);
        
        if ($existe) {
            $contadorExistentes++;
            continue;
        }
        
        // Obtener IDs de categoría y proveedor
        $idCategoria = $categoriaIds[$prod['categoria']] ?? null;
        $idProveedor = $proveedorIds[$prod['proveedor']] ?? null;
        
        // Insertar producto
        $db->execute(
            "INSERT INTO productos (sku, nombre, descripcion, id_categoria, id_proveedor, 
             precio_compra, precio_venta, stock_actual, stock_minimo, unidad_medida, activo) 
             VALUES (:sku, :nombre, :descripcion, :categoria, :proveedor, 
             :precio_compra, :precio_venta, :stock_actual, :stock_minimo, :unidad, 1)",
            [
                ':sku' => $prod['sku'],
                ':nombre' => $prod['nombre'],
                ':descripcion' => $prod['descripcion'],
                ':categoria' => $idCategoria,
                ':proveedor' => $idProveedor,
                ':precio_compra' => $prod['precio_compra'],
                ':precio_venta' => $prod['precio_venta'],
                ':stock_actual' => $prod['stock_actual'],
                ':stock_minimo' => $prod['stock_minimo'],
                ':unidad' => $prod['unidad_medida']
            ]
        );
        
        $contadorCreados++;
    }
    
    echo "<p>✅ <strong>$contadorCreados productos nuevos creados</strong></p>";
    if ($contadorExistentes > 0) {
        echo "<p>⚠️ <strong>$contadorExistentes productos ya existían</strong></p>";
    }
    
    // RESUMEN FINAL
    echo "<hr>";
    echo "<h3>📊 Resumen del Inventario</h3>";
    
    $stats = [
        'categorias' => $db->queryOne("SELECT COUNT(*) as total FROM categorias WHERE activo = 1")['total'],
        'proveedores' => $db->queryOne("SELECT COUNT(*) as total FROM proveedores WHERE activo = 1")['total'],
        'productos' => $db->queryOne("SELECT COUNT(*) as total FROM productos WHERE activo = 1")['total'],
        'valor_inventario' => $db->queryOne("SELECT SUM(stock_actual * precio_compra) as total FROM productos WHERE activo = 1")['total']
    ];
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; margin-top: 20px;'>";
    echo "<tr style='background: #667eea; color: white;'>";
    echo "<th>Métrica</th><th>Valor</th>";
    echo "</tr>";
    echo "<tr><td>Categorías Activas</td><td><strong>{$stats['categorias']}</strong></td></tr>";
    echo "<tr><td>Proveedores Activos</td><td><strong>{$stats['proveedores']}</strong></td></tr>";
    echo "<tr><td>Productos en Inventario</td><td><strong>{$stats['productos']}</strong></td></tr>";
    echo "<tr><td>Valor Total Inventario</td><td><strong>S/ " . number_format($stats['valor_inventario'], 2) . "</strong></td></tr>";
    echo "</table>";
    
    echo "<hr>";
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin-top: 20px;'>";
    echo "<h3 style='margin-top: 0;'>✅ ¡Inventario Poblado Exitosamente!</h3>";
    echo "<p><strong>Siguiente paso:</strong> Ve al módulo de inventario para ver todos los productos:</p>";
    echo "<p><a href='/UNIVERSIDAD/Integrador/7service/public/inventario' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>📦 Ver Inventario</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
    echo "<h3 style='color: #dc3545;'>❌ Error</h3>";
    echo "<p>Error al poblar el inventario: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
