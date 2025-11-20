<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="<?php echo url('/dashboard'); ?>" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-semibold">Órdenes de Servicio</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                Órdenes de Servicio
            </h1>
            <p class="text-gray-600 mt-1">Gestión de reparaciones y mantenimientos</p>
        </div>
        <a href="<?php echo url('/ordenes/nuevo'); ?>" 
           class="mt-4 md:mt-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Nueva Orden
        </a>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <?php
        $totalOrdenes = count($ordenes);
        $pendientes = count(array_filter($ordenes, fn($o) => $o['estado'] === 'Pendiente'));
        $enProceso = count(array_filter($ordenes, fn($o) => in_array($o['estado'], ['En Diagnostico', 'En Reparacion'])));
        $completadas = count(array_filter($ordenes, fn($o) => $o['estado'] === 'Entregado'));
        ?>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Órdenes</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $totalOrdenes ?></p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pendientes</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2"><?= $pendientes ?></p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">En Proceso</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2"><?= $enProceso ?></p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-tools text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Completadas</p>
                    <p class="text-3xl font-bold text-green-600 mt-2"><?= $completadas ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="<?php echo url('/ordenes'); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i> Estado
                </label>
                <select id="estado" name="estado" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="Pendiente" <?= ($filters['estado'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="En Diagnostico" <?= ($filters['estado'] ?? '') === 'En Diagnostico' ? 'selected' : '' ?>>En Diagnóstico</option>
                    <option value="Esperando Aprobacion" <?= ($filters['estado'] ?? '') === 'Esperando Aprobacion' ? 'selected' : '' ?>>Esperando Aprobación</option>
                    <option value="En Reparacion" <?= ($filters['estado'] ?? '') === 'En Reparacion' ? 'selected' : '' ?>>En Reparación</option>
                    <option value="Listo para Entrega" <?= ($filters['estado'] ?? '') === 'Listo para Entrega' ? 'selected' : '' ?>>Listo para Entrega</option>
                    <option value="Entregado" <?= ($filters['estado'] ?? '') === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                    <option value="Cancelado" <?= ($filters['estado'] ?? '') === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>

            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-1"></i> Fecha Desde
                </label>
                <input type="date" id="fecha_desde" name="fecha_desde" 
                       value="<?= htmlspecialchars($filters['fecha_desde'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-1"></i> Fecha Hasta
                </label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" 
                       value="<?= htmlspecialchars($filters['fecha_hasta'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>
            </div>
        </form>

        <?php if (!empty($filters['estado']) || !empty($filters['fecha_desde']) || !empty($filters['fecha_hasta'])): ?>
            <div class="mt-4">
                <a href="<?php echo url('/ordenes'); ?>" 
                   class="text-sm text-blue-600 hover:text-blue-700">
                    <i class="fas fa-times-circle mr-1"></i> Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabla de Órdenes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if (empty($ordenes)): ?>
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay órdenes registradas</h3>
                <p class="text-gray-600 mb-6">Comienza creando tu primera orden de servicio</p>
                <a href="<?php echo url('/ordenes/nuevo'); ?>" 
                   class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Nueva Orden
                </a>
            </div>
        <?php else: ?>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Técnico
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($ordenes as $orden): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">
                                        #<?= str_pad($orden['id_orden'], 5, '0', STR_PAD_LEFT) ?>
                                    </div>
                                    <div class="text-xs text-gray-500 font-mono">
                                        <?= htmlspecialchars($orden['codigo_seguimiento']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($orden['cliente_nombre']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-phone text-xs mr-1"></i>
                                        <?= htmlspecialchars($orden['cliente_telefono']) ?>
                                    </div>
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
                                    $prioBadge = $prioridadBadges[$orden['prioridad']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $prioBadge ?>">
                                        <?= htmlspecialchars($orden['prioridad']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($orden['fecha_creacion'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars($orden['tecnico_asignado'] ?? 'Sin asignar') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?php echo url('/ordenes/' . $orden['id_orden']); ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo url('/seguimiento/' . htmlspecialchars($orden['codigo_seguimiento'])); ?>" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-900" title="Ver seguimiento público">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
