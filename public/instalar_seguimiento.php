<?php
/**
 * Script para agregar columna codigo_seguimiento a ordenes_servicio
 */

require_once __DIR__ . '/../config/config.php';

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

echo "<h2>🔧 Agregando sistema de códigos de seguimiento</h2>";
echo "<hr>";

try {
    // Verificar si la columna ya existe
    $columnas = $db->query("SHOW COLUMNS FROM ordenes_servicio LIKE 'codigo_seguimiento'");
    
    if (!empty($columnas)) {
        echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
        echo "<h3>⚠️ La columna ya existe</h3>";
        echo "<p>La columna <code>codigo_seguimiento</code> ya está presente en la tabla.</p>";
        echo "</div>";
    } else {
        // Agregar columna
        echo "<p>➕ Agregando columna <code>codigo_seguimiento</code>...</p>";
        $db->execute("ALTER TABLE ordenes_servicio 
                      ADD COLUMN codigo_seguimiento VARCHAR(10) UNIQUE 
                      AFTER id_orden");
        
        echo "<p>✅ Columna agregada exitosamente</p>";
        
        // Crear índice
        echo "<p>🔍 Creando índice...</p>";
        $db->execute("CREATE INDEX idx_codigo_seguimiento ON ordenes_servicio(codigo_seguimiento)");
        echo "<p>✅ Índice creado</p>";
    }
    
    // Generar códigos para órdenes existentes
    echo "<hr>";
    echo "<h3>📝 Generando códigos para órdenes existentes</h3>";
    
    $ordenesSinCodigo = $db->query("SELECT id_orden FROM ordenes_servicio WHERE codigo_seguimiento IS NULL");
    
    if (empty($ordenesSinCodigo)) {
        echo "<p>ℹ️ No hay órdenes sin código de seguimiento</p>";
    } else {
        $contador = 0;
        foreach ($ordenesSinCodigo as $orden) {
            // Generar código único
            do {
                $codigo = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
                $existe = $db->queryOne("SELECT id_orden FROM ordenes_servicio WHERE codigo_seguimiento = :codigo", 
                                       [':codigo' => $codigo]);
            } while ($existe);
            
            // Actualizar orden
            $db->execute("UPDATE ordenes_servicio SET codigo_seguimiento = :codigo WHERE id_orden = :id",
                        [':codigo' => $codigo, ':id' => $orden['id_orden']]);
            
            echo "<p>✅ Orden #{$orden['id_orden']} → Código: <strong>{$codigo}</strong></p>";
            $contador++;
        }
        
        echo "<p style='background: #d4edda; padding: 10px; border-radius: 4px;'>";
        echo "✅ <strong>{$contador} códigos generados</strong>";
        echo "</p>";
    }
    
    // Mostrar estructura actualizada
    echo "<hr>";
    echo "<h3>📊 Estructura actualizada</h3>";
    
    $columnas = $db->query("DESCRIBE ordenes_servicio");
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; margin-top: 20px;'>";
    echo "<tr style='background: #667eea; color: white;'>";
    echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Extra</th>";
    echo "</tr>";
    
    foreach ($columnas as $col) {
        $esNuevo = $col['Field'] === 'codigo_seguimiento';
        $estilo = $esNuevo ? "background: #d4edda; font-weight: bold;" : "";
        
        echo "<tr style='{$estilo}'>";
        echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Extra']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<hr>";
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin-top: 20px;'>";
    echo "<h3 style='margin-top: 0;'>✅ ¡Sistema de Seguimiento Instalado!</h3>";
    echo "<p>Ahora cada orden tiene un código único para seguimiento público.</p>";
    echo "<p><strong>Siguiente paso:</strong> Crear el portal de seguimiento público</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
    echo "<h3 style='color: #dc3545;'>❌ Error</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
