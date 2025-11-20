-- ====================================
-- SCRIPT DE DATOS INICIALES
-- Seven Service - Taller de Bicicletas
-- ====================================

-- Insertar usuario administrador
INSERT INTO usuarios (nombre, correo, contraseña_hash, rol, activo) VALUES
('Administrador Principal', 'admin@sevenservice.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Juan Técnico', 'tecnico@sevenservice.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tecnico', 1);

-- Nota: La contraseña para ambos usuarios es: admin123
-- Se genera con: password_hash('admin123', PASSWORD_BCRYPT)

-- Insertar categorías de ejemplo
INSERT INTO categorias (nombre, descripcion, activo) VALUES
('Llantas y Neumáticos', 'Llantas, neumáticos, cámaras y accesorios', 1),
('Frenos', 'Sistemas de frenos, pastillas, cables y líquidos', 1),
('Transmisión', 'Cadenas, piñones, platos, desviadores y cambios', 1),
('Suspensión', 'Horquillas, amortiguadores y componentes', 1),
('Componentes', 'Manubrios, potencias, tijas, sillines', 1),
('Iluminación', 'Luces delanteras, traseras y accesorios', 1),
('Herramientas', 'Herramientas para mantenimiento y reparación', 1),
('Accesorios', 'Portabidones, bolsos, guardabarros, espejos', 1),
('Lubricantes', 'Aceites, grasas y productos de limpieza', 1);

-- Insertar proveedores de ejemplo
INSERT INTO proveedores (nombre, contacto_nombre, telefono, email, direccion, ruc, activo) VALUES
('Shimano Perú', 'Roberto García', '987654321', 'ventas@shimano.pe', 'Av. Industrial 123, Lima', '20123456789', 1),
('Trek Distribuidora', 'María López', '965432109', 'info@trekdist.com', 'Jr. Comercio 456, Lima', '20234567890', 1);

-- Insertar productos de ejemplo
INSERT INTO productos (id_categoria, id_proveedor, sku, nombre, descripcion, precio_compra, precio_venta, stock_actual, stock_minimo, unidad_medida, activo) VALUES
(1, 1, 'LLT-001', 'Neumático MTB 29x2.2', 'Neumático de montaña 29 pulgadas', 45.00, 75.00, 15, 5, 'Unidad', 1),
(1, 1, 'CMR-001', 'Cámara 29"', 'Cámara para neumático 29 pulgadas', 8.00, 15.00, 30, 10, 'Unidad', 1),
(2, 1, 'FRN-001', 'Pastillas de Freno Shimano', 'Pastillas de freno hidráulico', 25.00, 45.00, 20, 8, 'Par', 1),
(3, 1, 'CDN-001', 'Cadena Shimano HG-71', 'Cadena 8 velocidades', 35.00, 60.00, 12, 5, 'Unidad', 1),
(5, 2, 'SLL-001', 'Sillín Deportivo', 'Sillín ergonómico para ruta', 55.00, 95.00, 8, 3, 'Unidad', 1),
(9, 1, 'LUB-001', 'Lubricante Cadena 100ml', 'Lubricante para cadena seco', 12.00, 22.00, 25, 10, 'Unidad', 1);

-- Mensaje de confirmación
SELECT '✅ Datos iniciales insertados correctamente' as mensaje;
SELECT '👤 Usuario Admin: admin@sevenservice.com | Contraseña: admin123' as credenciales;
SELECT '👤 Usuario Técnico: tecnico@sevenservice.com | Contraseña: admin123' as credenciales;
