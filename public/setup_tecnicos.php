<?php
/**
 * Script para crear usuarios técnicos
 * Ejecutar una sola vez
 */

// Cargar configuración
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

// Password para todos los técnicos
$password = 'tecnico123';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$tecnicos = [
    [
        'nombre' => 'Juan Técnico',
        'correo' => 'tecnico1@sevenservice.com',
        'rol' => 'tecnico'
    ],
    [
        'nombre' => 'María Técnico',
        'correo' => 'tecnico2@sevenservice.com',
        'rol' => 'tecnico'
    ],
    [
        'nombre' => 'Pedro Técnico',
        'correo' => 'tecnico3@sevenservice.com',
        'rol' => 'tecnico'
    ]
];

echo "<h2>Creando usuarios técnicos...</h2>";

foreach ($tecnicos as $tecnico) {
    // Verificar si ya existe
    $existe = $db->queryOne(
        "SELECT id_usuario FROM usuarios WHERE correo = :correo",
        [':correo' => $tecnico['correo']]
    );
    
    if ($existe) {
        echo "<p>❌ {$tecnico['correo']} ya existe</p>";
        continue;
    }
    
    // Insertar técnico
    $query = "INSERT INTO usuarios (nombre, correo, contraseña_hash, rol, activo) 
              VALUES (:nombre, :correo, :password, :rol, 1)";
    
    $db->execute($query, [
        ':nombre' => $tecnico['nombre'],
        ':correo' => $tecnico['correo'],
        ':password' => $hashedPassword,
        ':rol' => $tecnico['rol']
    ]);
    
    echo "<p>✅ {$tecnico['correo']} creado exitosamente</p>";
}

echo "<hr>";
echo "<h3>Credenciales de acceso:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Nombre</th><th>Correo</th><th>Contraseña</th><th>Rol</th></tr>";

foreach ($tecnicos as $tecnico) {
    echo "<tr>";
    echo "<td>{$tecnico['nombre']}</td>";
    echo "<td>{$tecnico['correo']}</td>";
    echo "<td>tecnico123</td>";
    echo "<td>{$tecnico['rol']}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<br><p><a href='<?php echo url('/'); ?>login'>Ir al Login</a></p>";
?>
