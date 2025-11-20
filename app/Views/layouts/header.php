<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Seven Service' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js para interactividad -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Iconos (Heroicons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <!-- Navbar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-blue-600">
                                <i class="fas fa-bicycle"></i> Seven Service
                            </span>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="<?php echo url('/dashboard'); ?>"
                               class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="<?php echo url('/ordenes'); ?>"
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Órdenes
                            </a>
                            <a href="<?php echo url('/clientes'); ?>"
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Clientes
                            </a>
                            <a href="<?php echo url('/inventario'); ?>"
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Inventario
                            </a>
                            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                            <a href="<?php echo url('/usuarios'); ?>"
                               class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Usuarios
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="text-sm text-gray-700 mr-4">
                                <i class="fas fa-user"></i> <?= $_SESSION['usuario_nombre'] ?? 'Usuario' ?>
                                <span class="text-xs text-gray-500">(<?= $_SESSION['usuario_rol'] ?? '' ?>)</span>
                            </span>
                        </div>
                        <a href="<?php echo url('/logout'); ?>"
                           class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Salir
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    
    <!-- Notificaciones -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="notification-success" class="fixed top-20 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg max-w-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <div class="flex-1">
                    <p class="font-semibold">¡Éxito!</p>
                    <p class="text-sm"><?= htmlspecialchars($_SESSION['success']) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const notification = document.getElementById('notification-success');
                if (notification) {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 4000);
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div id="notification-error" class="fixed top-20 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                <div class="flex-1">
                    <p class="font-semibold">Error</p>
                    <p class="text-sm"><?= htmlspecialchars($_SESSION['error']) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const notification = document.getElementById('notification-error');
                if (notification) {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 4000);
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <!-- Contenido Principal -->
    <main class="<?= isset($_SESSION['usuario_id']) ? 'py-6' : '' ?>">
