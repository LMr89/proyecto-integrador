<?php
// Archivo temporal para verificar usuarios en la BD

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=5060;dbname=taller_bicicletas;charset=utf8mb4',
        'root',
        'wbWpZlPGVj8rlEmvaRzGwHutwEvjbo1PrJzeqizFXASco6q1lxeipB1v9lvsb7gJ'
    );
    
    echo "<h2>Conexión exitosa a la base de datos</h2>";
    
    // Verificar usuarios
    $stmt = $pdo->query("SELECT id_usuario, nombre, correo, rol, activo FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios en la BD (" . count($usuarios) . "):</h3>";
    
    if (count($usuarios) > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Activo</th></tr>";
        foreach ($usuarios as $user) {
            echo "<tr>";
            echo "<td>{$user['id_usuario']}</td>";
            echo "<td>{$user['nombre']}</td>";
            echo "<td>{$user['correo']}</td>";
            echo "<td>{$user['rol']}</td>";
            echo "<td>" . ($user['activo'] ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'><strong>⚠️ NO HAY USUARIOS EN LA BASE DE DATOS</strong></p>";
        echo "<p>Necesitas importar el archivo: <code>info/datos_iniciales.sql</code></p>";
    }
    
    // Verificar estructura de la tabla
    echo "<h3>Estructura de la tabla usuarios:</h3>";
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error: " . $e->getMessage() . "</h2>";
}
?>
