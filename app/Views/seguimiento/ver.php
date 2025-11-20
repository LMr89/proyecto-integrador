<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden #<?= str_pad($orden['id_orden'], 5, '0', STR_PAD_LEFT) ?> - Seven Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Timeline personalizado */
        .timeline-step {
            position: relative;
            padding-left: 3rem;
            padding-bottom: 2.5rem;
        }
        .timeline-step:last-child {
            padding-bottom: 0;
        }
        .timeline-step::before {
            content: '';
            position: absolute;
            left: 0.875rem;
            top: 2.5rem;
            bottom: -0.5rem;
            width: 2px;
            background: #e5e7eb;
        }
        .timeline-step:last-child::before {
            display: none;
        }
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-center;
            font-size: 1.1rem;
            z-index: 10;
        }
        .timeline-icon.completed {
            background: #10b981;
            color: white;
            box-shadow: 0 0 0 4px #d1fae5;
        }
        .timeline-icon.current {
            background: #3b82f6;
            color: white;
            box-shadow: 0 0 0 4px #dbeafe;
            animation: pulse 2s infinite;
        }
        .timeline-icon.pending {
            background: #f3f4f6;
            color: #9ca3af;
            border: 2px solid #e5e7eb;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 4px #dbeafe; }
            50% { box-shadow: 0 0 0 8px #dbeafe; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    
    <!-- Header Público -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-wrench text-3xl text-blue-600"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Seven Service</h1>
                        <p class="text-sm text-gray-600">Seguimiento de Orden</p>
                    </div>
                </div>
                <a href="<?php echo url('/seguimiento'); ?>" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <i class="fas fa-search mr-1"></i> Nueva Consulta
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Código de Seguimiento -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl shadow-lg">
                <p class="text-sm font-medium mb-1">Código de Seguimiento</p>
                <p class="text-3xl font-mono font-bold tracking-widest"><?= htmlspecialchars($orden['codigo_seguimiento']) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda: Timeline de Estado -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-tasks text-blue-600 mr-3"></i>
                        Estado de la Reparación
                    </h2>

                    <?php
                    $estados = [
                        'Pendiente' => ['icon' => 'fa-clipboard-check', 'title' => 'Recibido', 'desc' => 'Tu bicicleta ha sido recibida en el taller'],
                        'En Diagnostico' => ['icon' => 'fa-search', 'title' => 'En Diagnóstico', 'desc' => 'Nuestros técnicos están evaluando el problema'],
                        'Esperando Aprobacion' => ['icon' => 'fa-clock', 'title' => 'Esperando Aprobación', 'desc' => 'Esperando tu confirmación para proceder'],
                        'En Reparacion' => ['icon' => 'fa-tools', 'title' => 'En Reparación', 'desc' => 'Estamos trabajando en tu bicicleta'],
                        'Listo para Entrega' => ['icon' => 'fa-check-circle', 'title' => 'Listo para Entrega', 'desc' => 'Tu bicicleta está lista para ser recogida'],
                        'Entregado' => ['icon' => 'fa-handshake', 'title' => 'Entregado', 'desc' => 'Servicio completado y entregado'],
                        'Cancelado' => ['icon' => 'fa-times-circle', 'title' => 'Cancelado', 'desc' => 'Esta orden fue cancelada']
                    ];

                    $estadoActual = $orden['estado'];
                    $ordenEstados = array_keys($estados);
                    $posicionActual = array_search($estadoActual, $ordenEstados);
                    
                    // Si está cancelado, solo mostrar ese estado
                    if ($estadoActual === 'Cancelado') {
                        $estadosMostrar = ['Cancelado'];
                    } else {
                        // Remover "Cancelado" del flujo normal
                        $estadosMostrar = array_diff($ordenEstados, ['Cancelado']);
                    }
                    ?>

                    <div class="mt-8">
                        <?php foreach ($estadosMostrar as $index => $estado): ?>
                            <?php
                            $pos = array_search($estado, $ordenEstados);
                            $estaCompleto = $pos < $posicionActual || $estado === $estadoActual;
                            $esActual = $estado === $estadoActual;
                            $esPendiente = $pos > $posicionActual;
                            
                            $iconClass = $estaCompleto ? 'completed' : ($esActual ? 'current' : 'pending');
                            ?>
                            
                            <div class="timeline-step">
                                <div class="timeline-icon <?= $iconClass ?>">
                                    <i class="fas <?= $estados[$estado]['icon'] ?>"></i>
                                </div>
                                <div class="pl-2">
                                    <h3 class="text-lg font-bold <?= $esActual ? 'text-blue-600' : ($estaCompleto ? 'text-gray-800' : 'text-gray-400') ?>">
                                        <?= $estados[$estado]['title'] ?>
                                        <?php if ($esActual): ?>
                                            <span class="ml-2 text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-full">ACTUAL</span>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm mt-1">
                                        <?= $estados[$estado]['desc'] ?>
                                    </p>
                                    <?php if ($esActual && !empty($orden['diagnostico_tecnico'])): ?>
                                        <div class="mt-3 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                            <p class="text-sm text-gray-700">
                                                <i class="fas fa-comment-medical text-blue-600 mr-2"></i>
                                                <strong>Diagnóstico:</strong> <?= nl2br(htmlspecialchars($orden['diagnostico_tecnico'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Historial de Cambios -->
                    <?php if (!empty($historial)): ?>
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                <i class="fas fa-history text-gray-600 mr-2"></i>
                                Historial de Cambios
                            </h3>
                            <div class="space-y-3">
                                <?php foreach ($historial as $cambio): ?>
                                    <div class="flex items-start space-x-3 text-sm">
                                        <div class="flex-shrink-0 w-36 text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($cambio['fecha_cambio'])) ?>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-gray-700">
                                                <?= htmlspecialchars($cambio['estado_anterior'] ?: 'Inicio') ?>
                                            </span>
                                            <i class="fas fa-arrow-right text-gray-400 mx-2"></i>
                                            <span class="text-blue-600 font-medium">
                                                <?= htmlspecialchars($cambio['estado_nuevo']) ?>
                                            </span>
                                            <?php if (!empty($cambio['comentario'])): ?>
                                                <p class="text-gray-600 mt-1 italic">
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
            </div>

            <!-- Columna Derecha: Información de la Orden -->
            <div class="space-y-6">
                
                <!-- Información de la Bicicleta -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bicycle text-green-600 mr-2"></i>
                        Tu Bicicleta
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Marca y Modelo</p>
                            <p class="font-semibold text-gray-800">
                                <?= htmlspecialchars($orden['bicicleta_marca']) ?> 
                                <?= htmlspecialchars($orden['bicicleta_modelo']) ?>
                            </p>
                        </div>
                        <?php if (!empty($orden['bicicleta_tipo'])): ?>
                            <div>
                                <p class="text-sm text-gray-600">Tipo</p>
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['bicicleta_tipo']) ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($orden['bicicleta_color'])): ?>
                            <div>
                                <p class="text-sm text-gray-600">Color</p>
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($orden['bicicleta_color']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fechas Importantes -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                        Fechas
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Ingreso</p>
                            <p class="font-semibold text-gray-800">
                                <?= date('d/m/Y', strtotime($orden['fecha_creacion'])) ?>
                            </p>
                        </div>
                        <?php if (!empty($orden['fecha_estimada_entrega'])): ?>
                            <div>
                                <p class="text-sm text-gray-600">Fecha Estimada</p>
                                <p class="font-semibold text-blue-600">
                                    <i class="far fa-calendar-check mr-1"></i>
                                    <?= date('d/m/Y', strtotime($orden['fecha_estimada_entrega'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($orden['fecha_finalizacion'])): ?>
                            <div>
                                <p class="text-sm text-gray-600">Fecha de Entrega</p>
                                <p class="font-semibold text-green-600">
                                    <?= date('d/m/Y H:i', strtotime($orden['fecha_finalizacion'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Técnico Asignado -->
                <?php if (!empty($orden['tecnico_asignado'])): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-cog text-orange-600 mr-2"></i>
                            Técnico Asignado
                        </h3>
                        <p class="font-semibold text-gray-800">
                            <?= htmlspecialchars($orden['tecnico_asignado']) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Costo Estimado -->
                <?php if ($orden['costo_total'] > 0): ?>
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-2 flex items-center">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Costo Total
                        </h3>
                        <p class="text-4xl font-bold">
                            S/ <?= number_format($orden['costo_total'], 2) ?>
                        </p>
                        <?php if ($orden['estado'] !== 'Entregado'): ?>
                            <p class="text-sm mt-2 opacity-90">
                                <i class="fas fa-info-circle mr-1"></i>
                                Este monto puede variar según el diagnóstico final
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Requiere Aprobación -->
                <?php if ($orden['requiere_aprobacion'] && !$orden['aprobado_por_cliente']): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div>
                                <p class="font-semibold text-yellow-800">Aprobación Pendiente</p>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Nos comunicaremos contigo para confirmar el presupuesto antes de proceder con la reparación.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Contacto -->
                <div class="bg-gray-800 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-phone text-blue-400 mr-2"></i>
                        ¿Necesitas Ayuda?
                    </h3>
                    <div class="space-y-3 text-sm">
                        <a href="tel:+51987654321" class="flex items-center hover:text-blue-400 transition">
                            <i class="fas fa-phone-alt w-6"></i>
                            987 654 321
                        </a>
                        <a href="mailto:contacto@sevenservice.com" class="flex items-center hover:text-blue-400 transition">
                            <i class="fas fa-envelope w-6"></i>
                            contacto@sevenservice.com
                        </a>
                        <a href="https://wa.me/51987654321" target="_blank" class="flex items-center hover:text-green-400 transition">
                            <i class="fab fa-whatsapp w-6"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-20 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-300">© <?= date('Y') ?> Seven Service - Taller de Bicicletas</p>
            <p class="text-sm text-gray-400 mt-2">Guarda este código para futuras consultas</p>
        </div>
    </footer>

    <!-- Auto-refresh cada 30 segundos -->
    <script>
        // Actualizar automáticamente cada 30 segundos
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>

</body>
</html>
