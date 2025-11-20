<?php 
// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// La sesión ya se inicia en config.php, no necesitamos session_start() aquí

// Si ya está autenticado, redirigir
if (isset($_SESSION['usuario_id'])) {
    header('Location: /UNIVERSIDAD/Integrador/7service/public/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Seven Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center shadow-lg mb-4">
                <i class="fas fa-bicycle text-4xl text-blue-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800">Seven Service</h2>
            <p class="text-gray-600 mt-2">Taller de Bicicletas</p>
        </div>

        <?php if (isset($_SESSION['error_login'])): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                <p class="ml-3 text-sm text-red-700"><?php echo $_SESSION['error_login']; unset($_SESSION['error_login']); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                <p class="ml-3 text-sm text-green-700"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="process_login.php">
            <div class="mb-6">
                <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                </label>
                <input type="email" 
                       id="correo" 
                       name="correo" 
                       required 
                       value="admin@sevenservice.com"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1"></i> Contraseña
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       value="admin123"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
            </button>
        </form>

        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-xs text-blue-700 font-semibold mb-2">
                <i class="fas fa-info-circle"></i> Usuarios de prueba:
            </p>
            <ul class="text-xs text-blue-600 space-y-1">
                <li><strong>Admin:</strong> admin@sevenservice.com / admin123</li>
                <li><strong>Técnico:</strong> tecnico@sevenservice.com / admin123</li>
            </ul>
        </div>
    </div>
</body>
</html>
