<?php
/**
 * Script simple para insertar usuarios iniciales
 */

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=5060;dbname=taller_bicicletas;charset=utf8mb4',
        'root',
        'wbWpZlPGVj8rlEmvaRzGwHutwEvjbo1PrJzeqizFXASco6q1lxeipB1v9lvsb7gJ',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ]
    );
    
    echo "<h2>🔄 Creando usuarios iniciales...</h2>";
    
    // Verificar si ya existen usuarios
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['total'] > 0) {
        echo "<p style='color: orange;'>⚠️ Ya existen {$result['total']} usuario(s) en la base de datos</p>";
        
        // Mostrar usuarios existentes
        $stmt = $pdo->query("SELECT id_usuario, nombre, correo, rol FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>👥 Usuarios existentes:</h3>";
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr style='background: #2196F3; color: white;'><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>";
        foreach ($usuarios as $user) {
            echo "<tr>";
            echo "<td>{$user['id_usuario']}</td>";
            echo "<td>{$user['nombre']}</td>";
            echo "<td><strong>{$user['correo']}</strong></td>";
            echo "<td>{$user['rol']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p><strong>Intenta iniciar sesión con estos usuarios usando la contraseña: admin123</strong></p>";
        
    } else {
        // Insertar usuarios nuevos
        $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
        
        $pdo->beginTransaction();
        
        // Usuario Admin
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña_hash, rol, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Administrador Principal', 'admin@sevenservice.com', $password_hash, 'admin', 1]);
        echo "<p>✅ Usuario Admin creado</p>";
        
        // Usuario Técnico
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña_hash, rol, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Juan Técnico', 'tecnico@sevenservice.com', $password_hash, 'tecnico', 1]);
        echo "<p>✅ Usuario Técnico creado</p>";
        
        $pdo->commit();
        
        echo "<h3 style='color: green;'>✅ Usuarios creados exitosamente</h3>";
    }
    
    echo "<div style='background: #e3f2fd; padding: 20px; margin: 20px 0; border-left: 4px solid #2196F3;'>";
    echo "<h3>🔐 Credenciales de acceso:</h3>";
    echo "<p><strong>Email:</strong> admin@sevenservice.com</p>";
    echo "<p><strong>Contraseña:</strong> admin123</p>";
    echo "<hr>";
    echo "<p><strong>Email Alternativo:</strong> tecnico@sevenservice.com</p>";
    echo "<p><strong>Contraseña:</strong> admin123</p>";
    echo "</div>";
    
    // Verificar el hash de la contraseña
    echo "<h3>🔍 Verificación de contraseña:</h3>";
    $test_hash = password_hash('admin123', PASSWORD_BCRYPT);
    $verify = password_verify('admin123', $test_hash);
    echo "<p>Test de hash: " . ($verify ? "✅ Funciona correctamente" : "❌ Error en hash") . "</p>";
    
    // Probar login
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute(['admin@sevenservice.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $passwordOk = password_verify('admin123', $user['contraseña_hash']);
        echo "<p>Verificación de login: " . ($passwordOk ? "✅ La contraseña es correcta" : "❌ La contraseña NO coincide") . "</p>";
        
        if (!$passwordOk) {
            echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";
            echo "<p><strong>⚠️ Detectamos un problema con el hash de contraseña</strong></p>";
            echo "<p>Vamos a actualizar la contraseña...</p>";
            
            // Actualizar con nuevo hash
            $new_hash = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE usuarios SET contraseña_hash = ? WHERE correo = ?");
            $stmt->execute([$new_hash, 'admin@sevenservice.com']);
            
            // Actualizar técnico también
            $stmt = $pdo->prepare("UPDATE usuarios SET contraseña_hash = ? WHERE correo = ?");
            $stmt->execute([$new_hash, 'tecnico@sevenservice.com']);
            
            echo "<p>✅ Contraseñas actualizadas correctamente</p>";
            echo "</div>";
        }
    }
    
    echo "<p style='margin-top: 30px;'><a href='/' style='display: inline-block; background: #4CAF50; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px;'>🚀 Ir al Login</a></p>";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
