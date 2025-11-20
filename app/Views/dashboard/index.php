<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </h1>
        <p class="text-gray-600 mt-2">Bienvenido, <?= $usuario['nombre'] ?>. Aquí tienes un resumen de tu taller.</p>
    </div>
    
    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Órdenes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Total Órdenes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        <?= $estadisticas['total_ordenes'] ?? 0 ?>
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Pendientes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Pendientes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        <?= $estadisticas['pendientes'] ?? 0 ?>
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <!-- En Reparación -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">En Reparación</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        <?= $estadisticas['en_reparacion'] ?? 0 ?>
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-tools text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Ventas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Total Ventas</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        S/. <?= number_format($estadisticas['total_ventas'] ?? 0, 2) ?>
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grid de dos columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Órdenes Pendientes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Órdenes Pendientes
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($ordenesPendientes)): ?>
                    <p class="text-gray-500 text-center py-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i><br>
                        ¡No hay órdenes pendientes!
                    </p>
                <?php else: ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($ordenesPendientes as $orden): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">Orden #<?= $orden['id_orden'] ?></p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-user"></i> <?= htmlspecialchars($orden['cliente_nombre']) ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-bicycle"></i> <?= htmlspecialchars($orden['bicicleta_marca'] . ' ' . $orden['bicicleta_modelo']) ?>
                                    </p>
                                </div>
                                <a href="<?php echo url('/ordenes/' . $orden['id_orden']); ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Productos con Stock Bajo -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-box-open mr-2"></i> Stock Bajo
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($productosStockBajo)): ?>
                    <p class="text-gray-500 text-center py-4">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i><br>
                        Todo el inventario tiene stock suficiente
                    </p>
                <?php else: ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($productosStockBajo as $producto): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900"><?= htmlspecialchars($producto['nombre']) ?></p>
                                    <p class="text-xs text-gray-500">SKU: <?= htmlspecialchars($producto['sku']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-red-600">
                                        Stock: <?= $producto['stock_actual'] ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Mín: <?= $producto['stock_minimo'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Órdenes Recientes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">
                <i class="fas fa-history mr-2"></i> Órdenes Recientes
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Orden
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
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($ordenesRecientes as $orden): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #<?= $orden['id_orden'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?= htmlspecialchars($orden['cliente_nombre']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?= htmlspecialchars($orden['bicicleta_marca'] . ' ' . $orden['bicicleta_modelo']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $estadoClases = [
                                'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                'En Diagnostico' => 'bg-blue-100 text-blue-800',
                                'En Reparacion' => 'bg-purple-100 text-purple-800',
                                'Listo para Entrega' => 'bg-green-100 text-green-800',
                                'Entregado' => 'bg-gray-100 text-gray-800',
                                'Cancelado' => 'bg-red-100 text-red-800'
                            ];
                            $clase = $estadoClases[$orden['estado']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $clase ?>">
                                <?= $orden['estado'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            S/. <?= number_format($orden['costo_total'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo url('/ordenes/' . $orden['id_orden']); ?>" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
