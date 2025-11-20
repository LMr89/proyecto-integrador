<?php
/**
 * Script para importar datos iniciales a la base de datos
 */

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=5060;dbname=taller_bicicletas;charset=utf8mb4',
        'root',
        'wbWpZlPGVj8rlEmvaRzGwHutwEvjbo1PrJzeqizFXASco6q1lxeipB1v9lvsb7gJ',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<h2>🔄 Importando datos iniciales...</h2>";
    
    // Leer el archivo SQL
    $sqlFile = __DIR__ . '/../info/datos_iniciales.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("No se encuentra el archivo: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Limpiar comentarios y dividir por declaraciones
    $sql = preg_replace('/--.*$/m', '', $sql); // Remover comentarios
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remover comentarios multilinea
    
    // Dividir por punto y coma pero ignorar los que están dentro de cadenas
    $statements = [];
    $current = '';
    $inString = false;
    $stringChar = '';
    
    for ($i = 0; $i < strlen($sql); $i++) {
        $char = $sql[$i];
        
        if (($char === '"' || $char === "'") && !$inString) {
            $inString = true;
            $stringChar = $char;
        } elseif ($char === $stringChar && $inString && $sql[$i-1] !== '\\') {
            $inString = false;
        }
        
        if ($char === ';' && !$inString) {
            $stmt = trim($current);
            if (!empty($stmt)) {
                $statements[] = $stmt;
            }
            $current = '';
        } else {
            $current .= $char;
        }
    }
    
    // Agregar la última declaración si existe
    $stmt = trim($current);
    if (!empty($stmt)) {
        $statements[] = $stmt;
    }
    
    echo "<p>Se encontraron " . count($statements) . " declaraciones SQL</p>";
    
    $pdo->beginTransaction();
    
    $executed = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Ignorar comandos SET
        if (stripos($statement, 'SET') === 0) continue;
        
        try {
            $pdo->exec($statement);
            $executed++;
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠️ Error en statement: " . substr($statement, 0, 50) . "... - " . $e->getMessage() . "</p>";
        }
    }
    
    $pdo->commit();
    
    echo "<h3 style='color: green;'>✅ Importación completada exitosamente</h3>";
    echo "<p>Se ejecutaron $executed declaraciones SQL</p>";
    
    // Verificar usuarios insertados
    $stmt = $pdo->query("SELECT id_usuario, nombre, correo, rol FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>👥 Usuarios creados:</h3>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background: #4CAF50; color: white;'><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>";
    foreach ($usuarios as $user) {
        echo "<tr>";
        echo "<td>{$user['id_usuario']}</td>";
        echo "<td>{$user['nombre']}</td>";
        echo "<td><strong>{$user['correo']}</strong></td>";
        echo "<td>{$user['rol']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='background: #e3f2fd; padding: 20px; margin: 20px 0; border-left: 4px solid #2196F3;'>";
    echo "<h3>🔐 Credenciales de acceso:</h3>";
    echo "<p><strong>Email:</strong> admin@sevenservice.com</p>";
    echo "<p><strong>Contraseña:</strong> admin123</p>";
    echo "<hr>";
    echo "<p><strong>Email:</strong> tecnico@sevenservice.com</p>";
    echo "<p><strong>Contraseña:</strong> admin123</p>";
    echo "</div>";
    
    echo "<p><a href='/' style='display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Ir al Login</a></p>";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
