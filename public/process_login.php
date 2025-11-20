<?php
// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/');
}

// Obtener datos del formulario
$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

// Validar que no estén vacíos
if (empty($correo) || empty($password)) {
    $_SESSION['error_login'] = 'Por favor completa todos los campos';
    redirect('/');
}

try {
    // Conectar a la base de datos
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Buscar usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ? AND activo = 1");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        $_SESSION['error_login'] = 'Credenciales inválidas';
        redirect('/');
    }

    // Verificar contraseña
    if (!password_verify($password, $usuario['contraseña_hash'])) {
        $_SESSION['error_login'] = 'Credenciales inválidas';
        redirect('/');
    }
    
    // Login exitoso - crear sesión
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    $_SESSION['usuario_correo'] = $usuario['correo'];
    $_SESSION['usuario_rol'] = $usuario['rol'];
    
    // Actualizar última sesión
    $stmt = $pdo->prepare("UPDATE usuarios SET ultima_sesion = NOW() WHERE id_usuario = ?");
    $stmt->execute([$usuario['id_usuario']]);

    // Redirigir al dashboard
    redirect('/dashboard');

} catch (PDOException $e) {
    $_SESSION['error_login'] = 'Error de conexión a la base de datos';
    redirect('/');
}
?>
