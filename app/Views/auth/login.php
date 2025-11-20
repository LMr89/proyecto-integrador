<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo y Título -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                <i class="fas fa-bicycle text-4xl text-blue-600"></i>
            </div>
            <h2 class="mt-6 text-center text-4xl font-extrabold text-white">
                Seven Service
            </h2>
            <p class="mt-2 text-center text-sm text-blue-100">
                Sistema de Gestión de Taller de Bicicletas
            </p>
        </div>
        
        <!-- Formulario de Login -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <?php if (isset($_SESSION['error_login'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo htmlspecialchars($_SESSION['error_login']); unset($_SESSION['error_login']); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo url('process_login.php'); ?>" class="space-y-6">
                <!-- Campo de correo -->
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                    </label>
                    <div class="mt-1">
                        <input id="correo" name="correo" type="email" required
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="tu@email.com">
                    </div>
                </div>
                
                <!-- Campo de contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-lock mr-1"></i> Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="••••••••">
                    </div>
                </div>
                
                <!-- Botón de submit -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </button>
                </div>
                
                <!-- Info de demostración -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-700 font-semibold mb-2">
                        <i class="fas fa-info-circle"></i> Usuarios de prueba:
                    </p>
                    <ul class="text-xs text-blue-600 space-y-1">
                        <li><strong>Admin:</strong> admin@sevenservice.com / admin123</li>
                        <li><strong>Técnico:</strong> tecnico1@sevenservice.com / tecnico123</li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
