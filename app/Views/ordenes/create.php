<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/dashboard" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/ordenes" class="hover:text-blue-600">Órdenes</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-semibold">Nueva Orden</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                Nueva Orden de Servicio
            </h1>
            <p class="text-gray-600 mt-1">Registra un nuevo servicio de reparación</p>
        </div>
        <button type="button" onclick="llenarDatosAleatorios()" 
                class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all shadow-lg transform hover:scale-105">
            <i class="fas fa-dice mr-2"></i> Llenar con Datos Aleatorios
        </button>
    </div>

    <!-- Formulario -->
    <form id="ordenForm" method="POST" action="/UNIVERSIDAD/Integrador/7service/public/ordenes/nuevo">
        
        <!-- PASO 1: Información del Cliente -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">
                <span class="bg-blue-600 text-white w-8 h-8 rounded-full inline-flex items-center justify-center mr-2">1</span>
                Información del Cliente
            </h2>

            <!-- Selector: Cliente Existente o Nuevo -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Cliente
                </label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="tipo_cliente" value="existente" checked 
                               class="mr-2" onchange="toggleClienteForm('existente')">
                        <span class="text-sm">Cliente Existente</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="tipo_cliente" value="nuevo" 
                               class="mr-2" onchange="toggleClienteForm('nuevo')">
                        <span class="text-sm">Nuevo Cliente</span>
                    </label>
                </div>
            </div>

            <!-- Búsqueda de Cliente Existente -->
            <div id="clienteExistenteSection" class="mb-6">
                <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar Cliente <span class="text-red-500">*</span>
                </label>
                <select id="cliente_id" name="cliente_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccione un cliente...</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id_cliente'] ?>">
                            <?= htmlspecialchars($cliente['nombre']) ?> - 
                            <?= htmlspecialchars($cliente['contacto_telefono']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Formulario de Nuevo Cliente -->
            <div id="clienteNuevoSection" style="display: none;">
                <input type="hidden" name="cliente_id" value="nuevo">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="cliente_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="cliente_nombre" name="cliente_nombre"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Juan Pérez">
                    </div>
                    <div>
                        <label for="cliente_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="cliente_telefono" name="cliente_telefono"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="987654321" maxlength="9">
                    </div>
                    <div>
                        <label for="cliente_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" id="cliente_email" name="cliente_email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="cliente@example.com">
                    </div>
                    <div>
                        <label for="cliente_direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <input type="text" id="cliente_direccion" name="cliente_direccion"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Av. Principal 123">
                    </div>
                </div>
            </div>
        </div>

        <!-- PASO 2: Información de la Bicicleta -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">
                <span class="bg-green-600 text-white w-8 h-8 rounded-full inline-flex items-center justify-center mr-2">2</span>
                Información de la Bicicleta
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bicicleta_marca" class="block text-sm font-medium text-gray-700 mb-2">
                        Marca <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="bicicleta_marca" name="bicicleta_marca" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Trek, Giant, Specialized">
                </div>

                <div>
                    <label for="bicicleta_modelo" class="block text-sm font-medium text-gray-700 mb-2">
                        Modelo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="bicicleta_modelo" name="bicicleta_modelo" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Mountain Bike X500">
                </div>

                <div>
                    <label for="bicicleta_tipo" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Bicicleta
                    </label>
                    <select id="bicicleta_tipo" name="bicicleta_tipo"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione...</option>
                        <option value="Montaña">Montaña</option>
                        <option value="Ruta">Ruta</option>
                        <option value="Urbana">Urbana</option>
                        <option value="BMX">BMX</option>
                        <option value="Eléctrica">Eléctrica</option>
                        <option value="Híbrida">Híbrida</option>
                    </select>
                </div>

                <div>
                    <label for="bicicleta_color" class="block text-sm font-medium text-gray-700 mb-2">
                        Color
                    </label>
                    <input type="text" id="bicicleta_color" name="bicicleta_color"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: Rojo, Negro, Azul">
                </div>

                <div>
                    <label for="bicicleta_numero_serie" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Serie
                    </label>
                    <input type="text" id="bicicleta_numero_serie" name="bicicleta_numero_serie"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="ABC123456">
                </div>

                <div>
                    <label for="bicicleta_año" class="block text-sm font-medium text-gray-700 mb-2">
                        Año de Fabricación
                    </label>
                    <input type="number" id="bicicleta_año" name="bicicleta_año"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="2024" min="1990" max="2025">
                </div>

                <div class="md:col-span-2">
                    <label for="bicicleta_notas" class="block text-sm font-medium text-gray-700 mb-2">
                        Observaciones de la Bicicleta
                    </label>
                    <textarea id="bicicleta_notas" name="bicicleta_notas" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Ej: Bicicleta con desgaste en frenos, cadena oxidada..."></textarea>
                </div>
            </div>
        </div>

        <!-- PASO 3: Detalles del Servicio -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">
                <span class="bg-orange-600 text-white w-8 h-8 rounded-full inline-flex items-center justify-center mr-2">3</span>
                Detalles del Servicio
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="descripcion_problema" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción del Problema <span class="text-red-500">*</span>
                    </label>
                    <textarea id="descripcion_problema" name="descripcion_problema" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe el problema o servicio solicitado por el cliente..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="diagnostico_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                        Diagnóstico Inicial (Técnico)
                    </label>
                    <textarea id="diagnostico_inicial" name="diagnostico_inicial" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Tu evaluación técnica inicial del problema..."></textarea>
                </div>

                <div>
                    <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-2">
                        Prioridad
                    </label>
                    <select id="prioridad" name="prioridad"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="Baja">Baja</option>
                        <option value="Normal" selected>Normal</option>
                        <option value="Alta">Alta</option>
                        <option value="Urgente">Urgente</option>
                    </select>
                </div>

                <div>
                    <label for="fecha_estimada" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Estimada de Entrega
                    </label>
                    <input type="date" id="fecha_estimada" name="fecha_estimada"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           min="<?= date('Y-m-d') ?>">
                </div>

                <div class="md:col-span-2">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                        Observaciones Adicionales
                    </label>
                    <textarea id="observaciones" name="observaciones" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Cualquier información adicional relevante..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="requiere_aprobacion" value="1" class="mr-2">
                        <span class="text-sm font-medium text-gray-700">
                            Este servicio requiere aprobación del cliente antes de proceder
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-4">
            <a href="/UNIVERSIDAD/Integrador/7service/public/dashboard" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i> Cancelar
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                <i class="fas fa-save mr-2"></i> Crear Orden de Servicio
            </button>
        </div>
    </form>
</div>

<script>
// Toggle entre cliente existente y nuevo
function toggleClienteForm(tipo) {
    const existenteSection = document.getElementById('clienteExistenteSection');
    const nuevoSection = document.getElementById('clienteNuevoSection');
    const clienteIdSelect = document.getElementById('cliente_id');
    
    if (tipo === 'existente') {
        existenteSection.style.display = 'block';
        nuevoSection.style.display = 'none';
        clienteIdSelect.required = true;
        document.getElementById('cliente_nombre').required = false;
        document.getElementById('cliente_telefono').required = false;
    } else {
        existenteSection.style.display = 'none';
        nuevoSection.style.display = 'block';
        clienteIdSelect.required = false;
        document.getElementById('cliente_nombre').required = true;
        document.getElementById('cliente_telefono').required = true;
    }
}

// Validación de teléfono
document.getElementById('cliente_telefono')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Establecer fecha mínima de entrega
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_estimada');
    const hoy = new Date();
    hoy.setDate(hoy.getDate() + 1); // Mínimo mañana
    fechaInput.min = hoy.toISOString().split('T')[0];
});

// Función para llenar el formulario con datos aleatorios
function llenarDatosAleatorios() {
    // Datos de muestra
    const nombres = ['Carlos Rodríguez', 'María González', 'José Martínez', 'Ana López', 'Luis Fernández', 
                     'Carmen Sánchez', 'Miguel Torres', 'Laura Ramírez', 'Pedro Castro', 'Isabel Morales'];
    const apellidos = ['Gutiérrez', 'Rojas', 'Flores', 'Vega', 'Mendoza', 'Silva', 'Ortiz', 'Cruz'];
    
    const marcas = ['Trek', 'Giant', 'Specialized', 'Cannondale', 'Scott', 'Merida', 'Cube', 'Bianchi'];
    const modelos = ['X-Caliber', 'Talon', 'Rockhopper', 'Trail', 'Aspect', 'Big Nine', 'Attention', 'Kuma'];
    const tipos = ['Montaña', 'Ruta', 'Urbana', 'BMX', 'Eléctrica', 'Híbrida'];
    const colores = ['Negro', 'Rojo', 'Azul', 'Verde', 'Blanco', 'Gris', 'Amarillo', 'Naranja', 'Morado'];
    
    const problemas = [
        'Frenos delanteros no responden correctamente, necesitan ajuste o cambio de pastillas',
        'Cadena se sale constantemente, piñones desgastados',
        'Ruedas desalineadas, rayos flojos, necesita centrado',
        'Cambios no funcionan bien, cable de cambio desgastado',
        'Suspensión delantera sin rebote, necesita servicio completo',
        'Manubrio flojo, potencia necesita ajuste',
        'Pedales con ruidos extraños, rodamientos dañados',
        'Llanta trasera pinchada, necesita parche o cambio de cámara',
        'Frenos de disco hacen ruido, discos requieren limpieza o cambio',
        'Bicicleta completa necesita mantenimiento general y limpieza'
    ];
    
    const diagnosticos = [
        'Pastillas de freno desgastadas al 80%, recomendar cambio inmediato',
        'Cassette y cadena con desgaste severo, cambio necesario',
        'Requiere centrado de ruedas y tensión de rayos',
        'Cable oxidado, cambio completo del sistema de cambios',
        'Aceite de suspensión contaminado, servicio completo requerido',
        'Tornillos de potencia flojos, verificar toda la dirección',
        'Rodamientos con juego excesivo, cambio urgente',
        'Cámara con múltiples pinchazos, recomendar cambio completo',
        'Pastillas contaminadas con aceite, limpieza de discos necesaria',
        'Mantenimiento preventivo completo, todos los componentes en buen estado'
    ];
    
    // Seleccionar nuevo cliente
    document.querySelector('input[name="tipo_cliente"][value="nuevo"]').checked = true;
    toggleClienteForm('nuevo');
    
    // Llenar datos del cliente
    const nombreAleatorio = nombres[Math.floor(Math.random() * nombres.length)] + ' ' + 
                           apellidos[Math.floor(Math.random() * apellidos.length)];
    document.getElementById('cliente_nombre').value = nombreAleatorio;
    document.getElementById('cliente_telefono').value = '9' + Math.floor(10000000 + Math.random() * 90000000);
    document.getElementById('cliente_email').value = nombreAleatorio.toLowerCase().replace(/\s/g, '') + '@email.com';
    
    const calles = ['Av. Principal', 'Jr. Los Olivos', 'Calle Lima', 'Av. Arequipa', 'Jr. Independencia'];
    document.getElementById('cliente_direccion').value = calles[Math.floor(Math.random() * calles.length)] + ' ' + 
                                                         (Math.floor(Math.random() * 999) + 100);
    
    // Llenar datos de la bicicleta
    document.getElementById('bicicleta_marca').value = marcas[Math.floor(Math.random() * marcas.length)];
    document.getElementById('bicicleta_modelo').value = modelos[Math.floor(Math.random() * modelos.length)] + ' ' + 
                                                       (Math.floor(Math.random() * 500) + 100);
    document.getElementById('bicicleta_tipo').value = tipos[Math.floor(Math.random() * tipos.length)];
    document.getElementById('bicicleta_color').value = colores[Math.floor(Math.random() * colores.length)];
    document.getElementById('bicicleta_numero_serie').value = 'SN' + Math.random().toString(36).substring(2, 15).toUpperCase();
    document.getElementById('bicicleta_año').value = Math.floor(Math.random() * 7) + 2018; // 2018-2024
    
    const notasBici = ['Bicicleta con uso moderado', 'Bicicleta nueva, primer servicio', 'Uso intensivo diario', 
                       'Bicicleta de competencia', 'Uso recreativo fines de semana'];
    document.getElementById('bicicleta_notas').value = notasBici[Math.floor(Math.random() * notasBici.length)];
    
    // Llenar detalles del servicio
    const problemaIndex = Math.floor(Math.random() * problemas.length);
    document.getElementById('descripcion_problema').value = problemas[problemaIndex];
    document.getElementById('diagnostico_inicial').value = diagnosticos[problemaIndex];
    
    const prioridades = ['Baja', 'Normal', 'Alta', 'Urgente'];
    document.getElementById('prioridad').value = prioridades[Math.floor(Math.random() * prioridades.length)];
    
    // Fecha estimada (entre 1 y 7 días)
    const fechaEstimada = new Date();
    fechaEstimada.setDate(fechaEstimada.getDate() + Math.floor(Math.random() * 7) + 1);
    document.getElementById('fecha_estimada').value = fechaEstimada.toISOString().split('T')[0];
    
    const observaciones = [
        'Cliente habitual, ofrecer descuento de fidelidad',
        'Primera vez que trae su bicicleta, explicar procedimientos',
        'Servicio urgente, cliente necesita la bicicleta para el fin de semana',
        'Cliente solicita presupuesto antes de proceder con reparaciones',
        'Mantenimiento preventivo regular'
    ];
    document.getElementById('observaciones').value = observaciones[Math.floor(Math.random() * observaciones.length)];
    
    // Checkbox aleatorio
    document.querySelector('input[name="requiere_aprobacion"]').checked = Math.random() > 0.5;
    
    // Notificación visual
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-bounce';
    notification.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ¡Formulario llenado con datos aleatorios!';
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Validación del formulario
document.getElementById('ordenForm').addEventListener('submit', function(e) {
    const tipoCliente = document.querySelector('input[name="tipo_cliente"]:checked').value;
    
    if (tipoCliente === 'existente') {
        const clienteId = document.getElementById('cliente_id').value;
        if (!clienteId) {
            e.preventDefault();
            alert('Por favor selecciona un cliente');
            return false;
        }
    } else {
        const nombre = document.getElementById('cliente_nombre').value;
        const telefono = document.getElementById('cliente_telefono').value;
        
        if (!nombre || !telefono) {
            e.preventDefault();
            alert('Por favor completa los datos del cliente');
            return false;
        }
        
        if (telefono.length !== 9) {
            e.preventDefault();
            alert('El teléfono debe tener 9 dígitos');
            return false;
        }
    }
    
    return true;
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
