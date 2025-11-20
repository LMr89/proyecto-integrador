<?php
session_start();

// Habilitar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug de Login</h2>";

echo "<h3>1. Verificar sesión PHP:</h3>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . " (1=disabled, 2=active)\n";
echo "Session Data: ";
print_r($_SESSION);
echo "</pre>";

echo "<h3>2. Verificar conexión a BD:</h3>";
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=5060;dbname=taller_bicicletas;charset=utf8mb4',
        'root',
        'wbWpZlPGVj8rlEmvaRzGwHutwEvjbo1PrJzeqizFXASco6q1lxeipB1v9lvsb7gJ',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Conexión exitosa<br>";
    
    // Verificar usuarios
    $stmt = $pdo->query("SELECT id_usuario, nombre, correo, rol, activo FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Usuarios encontrados: " . count($usuarios) . "</p>";
    
    foreach ($usuarios as $user) {
        echo "- {$user['nombre']} ({$user['correo']}) - Rol: {$user['rol']} - Activo: " . ($user['activo'] ? 'Sí' : 'No') . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "<h3>3. Test de Login Manual:</h3>";
echo '<form method="POST" action="">
    <label>Email: <input type="email" name="test_email" value="admin@sevenservice.com"></label><br>
    <label>Password: <input type="text" name="test_password" value="admin123"></label><br>
    <button type="submit" name="test_login">Probar Login</button>
</form>';

if (isset($_POST['test_login'])) {
    $email = $_POST['test_email'];
    $password = $_POST['test_password'];
    
    echo "<div style='background: #f0f0f0; padding: 15px; margin: 10px 0;'>";
    echo "<h4>Resultado del Test:</h4>";
    
    try {
        // Buscar usuario
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            echo "✅ Usuario encontrado: {$usuario['nombre']}<br>";
            echo "Email: {$usuario['correo']}<br>";
            echo "Rol: {$usuario['rol']}<br>";
            echo "Activo: " . ($usuario['activo'] ? 'Sí' : 'No') . "<br>";
            echo "Hash almacenado: " . substr($usuario['contraseña_hash'], 0, 20) . "...<br>";
            
            // Verificar contraseña
            $passwordOk = password_verify($password, $usuario['contraseña_hash']);
            
            if ($passwordOk) {
                echo "<p style='color: green; font-weight: bold;'>✅ CONTRASEÑA CORRECTA</p>";
                
                // Crear sesión
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_correo'] = $usuario['correo'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                echo "<p>Sesión creada:</p>";
                echo "<pre>";
                print_r($_SESSION);
                echo "</pre>";
                
                echo "<p><a href='<?php echo url('/'); ?>dashboard' style='display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir al Dashboard</a></p>";
                
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ CONTRASEÑA INCORRECTA</p>";
                
                // Test adicional
                echo "<h5>Debug adicional:</h5>";
                $test_hash = password_hash($password, PASSWORD_BCRYPT);
                echo "Nuevo hash generado: " . substr($test_hash, 0, 20) . "...<br>";
                $verify_test = password_verify($password, $test_hash);
                echo "Verificación del nuevo hash: " . ($verify_test ? '✅ OK' : '❌ FAIL') . "<br>";
            }
        } else {
            echo "<p style='color: red;'>❌ Usuario no encontrado con ese email</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
}

echo "<h3>4. Verificar POST desde JavaScript:</h3>";
echo "<p>Método de petición actual: " . $_SERVER['REQUEST_METHOD'] . "</p>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Datos POST recibidos:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
?>
