<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-tools text-blue-600 mr-2"></i>
            Panel de Técnico
        </h1>
        <p class="text-gray-600 mt-2">Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
    </div>

    <!-- Acciones Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- Registrar Nueva Orden -->
        <a href="<?php echo url('/ordenes/nuevo'); ?>" 
           class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl shadow-lg p-8 transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Nueva Orden
                    </h3>
                    <p class="text-blue-100">Registrar servicio de reparación</p>
                </div>
                <div class="text-5xl opacity-20">
                    <i class="fas fa-plus-circle"></i>
                </div>
            </div>
        </a>

        <!-- Ver Inventario -->
        <a href="<?php echo url('/inventario'); ?>" 
           class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl shadow-lg p-8 transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">
                        <i class="fas fa-boxes mr-3"></i>
                        Inventario
                    </h3>
                    <p class="text-green-100">Consultar productos disponibles</p>
                </div>
                <div class="text-5xl opacity-20">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Órdenes Hoy -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Órdenes Hoy</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['ordenes_hoy'] ?? 0 ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Órdenes Pendientes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pendientes</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['ordenes_pendientes'] ?? 0 ?></p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- En Proceso -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">En Proceso</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['ordenes_proceso'] ?? 0 ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-wrench text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Completadas Hoy -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completadas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['ordenes_completadas_hoy'] ?? 0 ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Órdenes Activas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list-ul text-blue-600 mr-2"></i>
                    Mis Órdenes Activas
                </h2>
                <a href="<?php echo url('/ordenes'); ?>" 
                   class="text-sm text-blue-600 hover:text-blue-700">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <?php if (!empty($ordenes_activas)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                # Orden
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bicicleta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioridad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Creación
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($ordenes_activas as $orden): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">
                                        #<?= str_pad($orden['id_orden'], 5, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <?php if (isset($orden['codigo_seguimiento'])): ?>
                                        <div class="text-xs text-gray-500 font-mono">
                                            <?= htmlspecialchars($orden['codigo_seguimiento']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($orden['cliente_nombre']) ?>
                                    </div>
                                    <?php if (isset($orden['cliente_telefono'])): ?>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-phone text-xs mr-1"></i>
                                            <?= htmlspecialchars($orden['cliente_telefono']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars($orden['bicicleta_marca']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($orden['bicicleta_modelo']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $estadoBadges = [
                                        'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'En Diagnostico' => 'bg-blue-100 text-blue-800',
                                        'Esperando Aprobacion' => 'bg-orange-100 text-orange-800',
                                        'En Reparacion' => 'bg-purple-100 text-purple-800',
                                        'Listo para Entrega' => 'bg-green-100 text-green-800',
                                        'Entregado' => 'bg-gray-100 text-gray-800',
                                        'Cancelado' => 'bg-red-100 text-red-800'
                                    ];
                                    $badgeClass = $estadoBadges[$orden['estado']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>">
                                        <?= htmlspecialchars($orden['estado']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $prioridadBadges = [
                                        'Baja' => 'bg-green-100 text-green-800',
                                        'Normal' => 'bg-blue-100 text-blue-800',
                                        'Alta' => 'bg-orange-100 text-orange-800',
                                        'Urgente' => 'bg-red-100 text-red-800'
                                    ];
                                    $prioBadge = $prioridadBadges[$orden['prioridad'] ?? 'Normal'] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $prioBadge ?>">
                                        <?= htmlspecialchars($orden['prioridad'] ?? 'Normal') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($orden['fecha_creacion'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?php echo url('/ordenes/' . $orden['id_orden']); ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (isset($orden['codigo_seguimiento'])): ?>
                                        <a href="<?php echo url('/seguimiento/' . htmlspecialchars($orden['codigo_seguimiento'])); ?>" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-900" title="Ver seguimiento público">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No tienes órdenes activas</h3>
                <p class="text-gray-600 mb-6">Las órdenes que te asignen aparecerán aquí</p>
                <a href="<?php echo url('/ordenes/nuevo'); ?>" 
                   class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Nueva Orden
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
