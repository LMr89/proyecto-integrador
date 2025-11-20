<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-boxes text-green-600 mr-2"></i>
                Inventario de Productos
            </h1>
            <p class="text-gray-600 mt-1">Consulta de productos y stock disponible</p>
        </div>
        <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="/UNIVERSIDAD/Integrador/7service/public/inventario/nuevo" 
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors shadow-md">
                <i class="fas fa-plus mr-2"></i> Nuevo Producto
            </a>
        <?php endif; ?>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Productos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Productos</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['total_productos'] ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-box text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Stock Bajo -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Stock Bajo</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1"><?= $stats['stock_bajo'] ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Sin Stock -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Sin Stock</p>
                    <p class="text-3xl font-bold text-red-600 mt-1"><?= $stats['sin_stock'] ?></p>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Valor Inventario -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Valor Total</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        S/ <?= number_format($stats['valor_inventario'], 2) ?>
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <!-- Búsqueda -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i> Buscar
                </label>
                <input type="text" 
                       name="search" 
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                       placeholder="Nombre o SKU..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Categoría -->
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1"></i> Categoría
                </label>
                <select name="categoria" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>" 
                                <?= ($filters['categoria'] ?? '') == $cat['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Stock Bajo -->
            <div class="flex items-center">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="stock_bajo" 
                           value="1" 
                           <?= ($filters['stock_bajo'] ?? '') === '1' ? 'checked' : '' ?>
                           class="mr-2">
                    <span class="text-sm font-medium text-gray-700">Solo stock bajo</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex gap-2">
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
                <a href="/UNIVERSIDAD/Integrador/7service/public/inventario" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo mr-2"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Productos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if (!empty($productos)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKU
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio Venta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($productos as $producto): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-box-open text-green-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($producto['nombre']) ?>
                                            </div>
                                            <?php if (!empty($producto['descripcion'])): ?>
                                                <div class="text-sm text-gray-500">
                                                    <?= htmlspecialchars(substr($producto['descripcion'], 0, 50)) ?>...
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-900">
                                        <?= htmlspecialchars($producto['sku'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        <?= htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $stock = (int)$producto['stock_actual'];
                                    $minimo = (int)$producto['stock_minimo'];
                                    
                                    if ($stock === 0) {
                                        $badgeClass = 'bg-red-100 text-red-800';
                                        $icon = 'times-circle';
                                    } elseif ($stock <= $minimo) {
                                        $badgeClass = 'bg-orange-100 text-orange-800';
                                        $icon = 'exclamation-triangle';
                                    } else {
                                        $badgeClass = 'bg-green-100 text-green-800';
                                        $icon = 'check-circle';
                                    }
                                    ?>
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $badgeClass ?>">
                                            <i class="fas fa-<?= $icon ?> mr-1"></i>
                                            <?= $stock ?> / <?= $minimo ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?= htmlspecialchars($producto['unidad_medida'] ?? 'unidad') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        S/ <?= number_format($producto['precio_venta'], 2) ?>
                                    </div>
                                    <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                                        <div class="text-xs text-gray-500">
                                            Compra: S/ <?= number_format($producto['precio_compra'], 2) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($producto['activo']): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-ban mr-1"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="/UNIVERSIDAD/Integrador/7service/public/inventario/<?= $producto['id_producto'] ?>/editar" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="eliminarProducto(<?= $producto['id_producto'] ?>)" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron productos</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function eliminarProducto(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        return;
    }
    
    fetch(`/UNIVERSIDAD/Integrador/7service/public/inventario/${id}/eliminar`, {
        method: 'POST',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al eliminar el producto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el producto');
    });
}
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
