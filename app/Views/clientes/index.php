<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Gestión de Clientes
            </h1>
            <p class="text-gray-600 mt-1">Administra la información de tus clientes</p>
        </div>
        <a href="<?php echo url('/clientes/nuevo'); ?>" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
            <i class="fas fa-plus mr-2"></i> Nuevo Cliente
        </a>
    </div>

    <!-- Barra de búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Buscar por nombre, teléfono o DNI..."
                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                </div>
            </div>
            <button onclick="buscarClientes()" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search mr-2"></i> Buscar
            </button>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total Clientes</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo count($clientes ?? []); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-bicycle text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Con Bicicletas</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?php 
                        $conBicicletas = 0;
                        foreach($clientes ?? [] as $cliente) {
                            if(isset($cliente['total_bicicletas']) && $cliente['total_bicicletas'] > 0) {
                                $conBicicletas++;
                            }
                        }
                        echo $conBicicletas;
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-tools text-2xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Con Servicios</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?php 
                        $conServicios = 0;
                        foreach($clientes ?? [] as $cliente) {
                            if(isset($cliente['total_ordenes']) && $cliente['total_ordenes'] > 0) {
                                $conServicios++;
                            }
                        }
                        echo $conServicios;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            DNI
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bicicletas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Servicios
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registro
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody id="clientesTable" class="bg-white divide-y divide-gray-200">
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p class="text-lg">No hay clientes registrados</p>
                                <p class="text-sm mt-2">Comienza agregando tu primer cliente</p>
                                <a href="<?php echo url('/clientes/crear'); ?>" 
                                   class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Agregar Cliente
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold">
                                                    <?php echo strtoupper(substr($cliente['nombre'], 0, 2)); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($cliente['nombre']); ?>
                                            </div>
                                            <?php if (!empty($cliente['correo'])): ?>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($cliente['correo']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <?php echo htmlspecialchars($cliente['telefono'] ?? $cliente['contacto_telefono'] ?? '-'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($cliente['dni'] ?? '-'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?php echo $cliente['total_bicicletas'] ?? 0; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <?php echo $cliente['total_ordenes'] ?? 0; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php 
                                    $fecha = new DateTime($cliente['fecha_registro']);
                                    echo $fecha->format('d/m/Y');
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="<?php echo url('/clientes/<?php echo $cliente['); ?>'id_cliente']; ?>" 
                                       class="text-blue-600 hover:text-blue-900" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo url('/clientes/<?php echo $cliente['); ?>'id_cliente']; ?>/editar" 
                                       class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="eliminarCliente(<?php echo $cliente['id_cliente']; ?>)" 
                                            class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Búsqueda de clientes
function buscarClientes() {
    const searchTerm = document.getElementById('searchInput').value;
    
    if (searchTerm.length < 2) {
        showNotification('Por favor ingresa al menos 2 caracteres', 'warning');
        return;
    }
    
    fetch(`<?php echo url('/api/clientes/buscar?term=${encodeURIComponent(searchTerm)}`, {'); ?>
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        mostrarResultados(data);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al buscar clientes', 'error');
    });
}

// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('input', function(e) {
    if (e.target.value.length === 0) {
        location.reload();
    }
});

// Enter para buscar
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarClientes();
    }
});

function mostrarResultados(clientes) {
    const tbody = document.getElementById('clientesTable');
    
    if (clientes.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-search text-4xl mb-3"></i>
                    <p class="text-lg">No se encontraron resultados</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = clientes.map(cliente => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">
                                ${cliente.nombre.substring(0, 2).toUpperCase()}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            ${cliente.nombre}
                        </div>
                        ${cliente.correo ? `<div class="text-sm text-gray-500">${cliente.correo}</div>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                    ${cliente.telefono}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${cliente.dni || '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${cliente.total_bicicletas || 0}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    ${cliente.total_ordenes || 0}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(cliente.fecha_registro).toLocaleDateString('es-PE')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <a href="<?php echo url('/clientes/${cliente.id_cliente}'); ?>" 
                   class="text-blue-600 hover:text-blue-900" title="Ver detalle">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="<?php echo url('/clientes/${cliente.id_cliente}/editar'); ?>" 
                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="eliminarCliente(${cliente.id_cliente})" 
                        class="text-red-600 hover:text-red-900" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function eliminarCliente(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    
    fetch(`<?php echo url('/clientes/${id}/eliminar`, {'); ?>
        method: 'POST',
        credentials: 'include',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cliente eliminado exitosamente', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Error al eliminar cliente', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al eliminar cliente', 'error');
    });
}
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
