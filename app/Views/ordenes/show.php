<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/dashboard" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/ordenes" class="hover:text-blue-600">Órdenes</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-semibold">Orden #<?= str_pad($orden['id_orden'], 5, '0', STR_PAD_LEFT) ?></li>
        </ol>
    </nav>

    <!-- Header con Código de Seguimiento -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                Orden #<?= str_pad($orden['id_orden'], 5, '0', STR_PAD_LEFT) ?>
            </h1>
            <p class="text-gray-600 mt-1">
                Cliente: <?= htmlspecialchars($orden['cliente_nombre']) ?>
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg shadow-lg">
                <p class="text-xs font-medium mb-1">Código de Seguimiento</p>
                <p class="text-xl font-mono font-bold tracking-wider"><?= htmlspecialchars($orden['codigo_seguimiento']) ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Panel de Estado Actual -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-traffic-light text-blue-600 mr-2"></i>
                    Estado de la Orden
                </h2>

                <?php
                $estadoColors = [
                    'Pendiente' => 'yellow',
                    'En Diagnostico' => 'blue',
                    'Esperando Aprobacion' => 'orange',
                    'En Reparacion' => 'purple',
                    'Listo para Entrega' => 'green',
                    'Entregado' => 'gray',
                    'Cancelado' => 'red'
                ];
                $color = $estadoColors[$orden['estado']] ?? 'gray';
                ?>

                <div class="bg-<?= $color ?>-50 border-l-4 border-<?= $color ?>-500 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-circle text-<?= $color ?>-500 mr-3 animate-pulse"></i>
                        <div>
                            <p class="text-sm text-<?= $color ?>-600 font-medium">Estado Actual</p>
                            <p class="text-2xl font-bold text-<?= $color ?>-700"><?= htmlspecialchars($orden['estado']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Formulario para Cambiar Estado -->
                <form id="cambiarEstadoForm" method="POST" action="/UNIVERSIDAD/Integrador/7service/public/ordenes/<?= $orden['id_orden'] ?>/cambiar-estado" class="space-y-4">
                    <div>
                        <label for="nuevo_estado" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exchange-alt mr-1"></i> Cambiar a:
                        </label>
                        <select id="nuevo_estado" name="estado" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Seleccionar nuevo estado --</option>
                            <option value="Pendiente" <?= $orden['estado'] === 'Pendiente' ? 'disabled' : '' ?>>Pendiente</option>
                            <option value="En Diagnostico" <?= $orden['estado'] === 'En Diagnostico' ? 'disabled' : '' ?>>En Diagnóstico</option>
                            <option value="Esperando Aprobacion" <?= $orden['estado'] === 'Esperando Aprobacion' ? 'disabled' : '' ?>>Esperando Aprobación</option>
                            <option value="En Reparacion" <?= $orden['estado'] === 'En Reparacion' ? 'disabled' : '' ?>>En Reparación</option>
                            <option value="Listo para Entrega" <?= $orden['estado'] === 'Listo para Entrega' ? 'disabled' : '' ?>>Listo para Entrega</option>
                            <option value="Entregado" <?= $orden['estado'] === 'Entregado' ? 'disabled' : '' ?>>Entregado</option>
                            <option value="Cancelado" <?= $orden['estado'] === 'Cancelado' ? 'disabled' : '' ?>>Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment mr-1"></i> Comentario (Opcional)
                        </label>
                        <textarea id="comentario" name="comentario" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Ej: Se encontró que la cadena estaba desgastada, se procedió a cambiarla..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Actualizar Estado
                    </button>
                </form>
            </div>

            <!-- Información del Problema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                    Problema Reportado
                </h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($orden['descripcion_problema'])) ?></p>
                </div>
            </div>

            <!-- Diagnóstico Técnico -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-stethoscope text-blue-600 mr-2"></i>
                    Diagnóstico Técnico
                </h2>
                <?php if (!empty($orden['diagnostico_tecnico'])): ?>
                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($orden['diagnostico_tecnico'])) ?></p>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Sin diagnóstico aún</p>
                <?php endif; ?>
            </div>

            <!-- Historial de Estados -->
            <?php if (!empty($historialEstados)): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-history text-green-600 mr-2"></i>
                        Historial de Cambios
                    </h2>
                    <div class="space-y-4">
                        <?php foreach ($historialEstados as $cambio): ?>
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-semibold text-gray-800">
                                            <?= htmlspecialchars($cambio['estado_anterior'] ?: 'Inicio') ?> 
                                            <i class="fas fa-arrow-right text-gray-400 mx-2"></i> 
                                            <?= htmlspecialchars($cambio['estado_nuevo']) ?>
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            <?= date('d/m/Y H:i', strtotime($cambio['fecha_cambio'])) ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Por: <strong><?= htmlspecialchars($cambio['usuario_nombre']) ?></strong>
                                    </p>
                                    <?php if (!empty($cambio['comentario'])): ?>
                                        <p class="text-sm text-gray-700 mt-2 italic border-l-2 border-gray-300 pl-3">
                                            "<?= htmlspecialchars($cambio['comentario']) ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna Lateral -->
        <div class="space-y-6">
            
            <!-- Información de la Bicicleta -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bicycle text-green-600 mr-2"></i>
                    Bicicleta
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Marca</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['marca']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Modelo</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['modelo']) ?></p>
                    </div>
                    <?php if (!empty($orden['bicicleta_tipo'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Tipo</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['bicicleta_tipo']) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($orden['color'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Color</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['color']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user text-purple-600 mr-2"></i>
                    Cliente
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nombre</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['cliente_nombre']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Teléfono</p>
                        <p class="font-semibold text-gray-800">
                            <a href="tel:<?= htmlspecialchars($orden['contacto_telefono']) ?>" class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($orden['contacto_telefono']) ?>
                            </a>
                        </p>
                    </div>
                    <?php if (!empty($orden['contacto_email'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-800 break-all">
                                <a href="mailto:<?= htmlspecialchars($orden['contacto_email']) ?>" class="text-blue-600 hover:underline">
                                    <?= htmlspecialchars($orden['contacto_email']) ?>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fechas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar text-orange-600 mr-2"></i>
                    Fechas
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Creación</p>
                        <p class="font-semibold text-gray-800">
                            <?= date('d/m/Y H:i', strtotime($orden['fecha_creacion'])) ?>
                        </p>
                    </div>
                    <?php if (!empty($orden['fecha_estimada_entrega'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Estimada de Entrega</p>
                            <p class="font-semibold text-blue-600">
                                <?= date('d/m/Y', strtotime($orden['fecha_estimada_entrega'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($orden['fecha_finalizacion'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Finalización</p>
                            <p class="font-semibold text-green-600">
                                <?= date('d/m/Y H:i', strtotime($orden['fecha_finalizacion'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Prioridad -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-flag text-red-600 mr-2"></i>
                    Prioridad
                </h3>
                <?php
                $prioridadColors = [
                    'Baja' => 'green',
                    'Normal' => 'blue',
                    'Alta' => 'orange',
                    'Urgente' => 'red'
                ];
                $prioColor = $prioridadColors[$orden['prioridad']] ?? 'gray';
                ?>
                <span class="inline-block bg-<?= $prioColor ?>-100 text-<?= $prioColor ?>-800 px-4 py-2 rounded-full font-semibold">
                    <?= htmlspecialchars($orden['prioridad']) ?>
                </span>
            </div>

            <!-- Asignación -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user-cog text-blue-600 mr-2"></i>
                    Asignación
                </h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-600">Creado por</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['creador_nombre']) ?></p>
                    </div>
                    <?php if (!empty($orden['asignado_nombre'])): ?>
                        <div>
                            <p class="text-sm text-gray-600">Asignado a</p>
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['asignado_nombre']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Link Público -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-md p-6 text-white">
                <h3 class="text-lg font-bold mb-3 flex items-center">
                    <i class="fas fa-link mr-2"></i>
                    Link Público
                </h3>
                <p class="text-sm mb-3 opacity-90">Comparte este enlace con el cliente:</p>
                <div class="bg-white bg-opacity-20 p-3 rounded text-sm break-all mb-3">
                    <?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/UNIVERSIDAD/Integrador/7service/public/seguimiento/<?= htmlspecialchars($orden['codigo_seguimiento']) ?>
                </div>
                <button onclick="copiarLink('<?= htmlspecialchars($orden['codigo_seguimiento']) ?>')" 
                        class="w-full bg-white text-blue-600 py-2 rounded font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-copy mr-2"></i> Copiar Link
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Copiar link al portapapeles
function copiarLink(codigo) {
    const baseUrl = '<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/UNIVERSIDAD/Integrador/7service/public/seguimiento/';
    const link = baseUrl + codigo;
    
    navigator.clipboard.writeText(link).then(() => {
        // Mostrar notificación
        const notif = document.createElement('div');
        notif.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-bounce';
        notif.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ¡Link copiado al portapapeles!';
        document.body.appendChild(notif);
        
        setTimeout(() => {
            notif.remove();
        }, 3000);
    });
}

// Confirmación al cambiar estado
document.getElementById('cambiarEstadoForm').addEventListener('submit', function(e) {
    const nuevoEstado = document.getElementById('nuevo_estado').value;
    if (!nuevoEstado) {
        e.preventDefault();
        alert('Por favor selecciona un nuevo estado');
        return false;
    }
    
    const estadosImportantes = ['Entregado', 'Cancelado'];
    if (estadosImportantes.includes(nuevoEstado)) {
        if (!confirm(`¿Estás seguro de cambiar el estado a "${nuevoEstado}"?`)) {
            e.preventDefault();
            return false;
        }
    }
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
