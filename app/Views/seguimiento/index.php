<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Orden - Seven Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <p class="text-sm text-gray-600">Taller de Reparación de Bicicletas</p>
                    </div>
                </div>
                <a href="/UNIVERSIDAD/Integrador/7service/public/login" 
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <i class="fas fa-sign-in-alt mr-1"></i> Acceso Técnicos
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                <i class="fas fa-search text-5xl text-blue-600"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4">
                Seguimiento de tu Orden
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Ingresa el código de seguimiento que te proporcionamos para ver el estado actual de tu bicicleta
            </p>
        </div>

        <!-- Formulario de Búsqueda -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12">
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-700"><?= htmlspecialchars($_SESSION['error']) ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-700"><?= htmlspecialchars($_SESSION['success']) ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form method="POST" action="/UNIVERSIDAD/Integrador/7service/public/seguimiento/buscar" class="space-y-6">
                    <div>
                        <label for="codigo" class="block text-lg font-semibold text-gray-700 mb-3">
                            <i class="fas fa-barcode mr-2 text-blue-600"></i>
                            Código de Seguimiento
                        </label>
                        <input 
                            type="text" 
                            id="codigo" 
                            name="codigo" 
                            required
                            maxlength="8"
                            placeholder="Ej: ABC12345"
                            class="w-full px-6 py-4 text-2xl text-center font-mono uppercase border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition-all tracking-widest"
                            style="letter-spacing: 0.3em;"
                            oninput="this.value = this.value.toUpperCase()"
                        >
                        <p class="mt-2 text-sm text-gray-500 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            El código está en el ticket que recibiste al dejar tu bicicleta
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg text-lg font-semibold">
                        <i class="fas fa-search mr-2"></i>
                        Consultar Estado
                    </button>
                </form>
            </div>

            <!-- Información Adicional -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <i class="fas fa-clock text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800 mb-2">Consulta 24/7</h3>
                    <p class="text-sm text-gray-600">Disponible en cualquier momento</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <i class="fas fa-shield-alt text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800 mb-2">Seguro</h3>
                    <p class="text-sm text-gray-600">Tu código es único y privado</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <i class="fas fa-bell text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800 mb-2">Actualizado</h3>
                    <p class="text-sm text-gray-600">Información en tiempo real</p>
                </div>
            </div>

            <!-- FAQs -->
            <div class="mt-12 bg-white rounded-xl shadow-md p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                    Preguntas Frecuentes
                </h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-chevron-right text-blue-600 mr-2 text-sm"></i>
                            ¿Dónde encuentro mi código de seguimiento?
                        </h4>
                        <p class="text-gray-600 pl-6">
                            Tu código está en el ticket/comprobante que recibiste al dejar tu bicicleta. Si lo perdiste, contáctanos con tu teléfono o email.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-chevron-right text-blue-600 mr-2 text-sm"></i>
                            ¿Con qué frecuencia se actualiza el estado?
                        </h4>
                        <p class="text-gray-600 pl-6">
                            Nuestros técnicos actualizan el estado en tiempo real. Recibirás notificaciones cuando haya cambios importantes.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-chevron-right text-blue-600 mr-2 text-sm"></i>
                            ¿Puedo consultar desde mi celular?
                        </h4>
                        <p class="text-gray-600 pl-6">
                            Sí, este portal está optimizado para funcionar en cualquier dispositivo: computadora, tablet o smartphone.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-20 py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-4">
                <i class="fas fa-wrench text-2xl text-blue-400"></i>
            </div>
            <p class="text-gray-300">© <?= date('Y') ?> Seven Service - Taller de Bicicletas</p>
            <div class="mt-4 space-x-6">
                <a href="tel:+51987654321" class="text-gray-300 hover:text-white">
                    <i class="fas fa-phone mr-2"></i>987 654 321
                </a>
                <a href="mailto:contacto@sevenservice.com" class="text-gray-300 hover:text-white">
                    <i class="fas fa-envelope mr-2"></i>contacto@sevenservice.com
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
