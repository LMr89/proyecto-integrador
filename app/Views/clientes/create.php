<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/dashboard" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="/UNIVERSIDAD/Integrador/7service/public/clientes" class="hover:text-blue-600">Clientes</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-semibold">Nuevo Cliente</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-plus text-blue-600 mr-2"></i>
            Nuevo Cliente
        </h1>
        <p class="text-gray-600 mt-1">Registra un nuevo cliente en el sistema</p>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form id="clienteForm" method="POST" action="/UNIVERSIDAD/Integrador/7service/public/clientes/nuevo">
            
            <!-- Sección: Información Personal -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Nombre Completo -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: Juan Pérez García">
                        <p class="mt-1 text-sm text-gray-500">Nombre completo del cliente</p>
                    </div>
                </div>
            </div>

            <!-- Sección: Información de Contacto -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b">
                    <i class="fas fa-phone text-green-600 mr-2"></i>
                    Información de Contacto
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teléfono -->
                    <div>
                        <label for="contacto_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="contacto_telefono" 
                               name="contacto_telefono" 
                               required
                               pattern="[0-9]{9}"
                               maxlength="9"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="987654321">
                        <p class="mt-1 text-sm text-gray-500">9 dígitos (celular)</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="contacto_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="contacto_email" 
                               name="contacto_email" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="cliente@example.com">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="contacto_direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="contacto_direccion" 
                                  name="contacto_direccion" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Av. Principal 123, San Isidro, Lima"></textarea>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="/UNIVERSIDAD/Integrador/7service/public/clientes" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                    <i class="fas fa-save mr-2"></i> Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validación en tiempo real
document.getElementById('contacto_telefono').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Validación antes de enviar
document.getElementById('clienteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const nombre = formData.get('nombre');
    const telefono = formData.get('contacto_telefono');
    
    // Validaciones
    if (nombre.trim().length < 3) {
        alert('El nombre debe tener al menos 3 caracteres');
        return;
    }
    
    if (telefono && telefono.length !== 9) {
        alert('El teléfono debe tener 9 dígitos');
        return;
    }
    
    // Enviar formulario
    this.submit();
});
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
