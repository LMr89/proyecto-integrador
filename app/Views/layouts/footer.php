    </main>
    
    <?php if (isset($_SESSION['usuario_id'])): ?>
    <!-- Footer -->
    <footer class="bg-white mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Seven Service - Taller de Bicicletas. Todos los derechos reservados.
            </p>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Script para notificaciones -->
    <script>
        // Helper para mostrar notificaciones
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Helper para peticiones AJAX
        async function apiRequest(url, options = {}) {
            try {
                const response = await fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    ...options
                });
                
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error en petición:', error);
                return { success: false, message: 'Error de conexión' };
            }
        }
    </script>
</body>
</html>
