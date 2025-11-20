# ✅ Checklist de Pruebas - Seven Service

Usa esta lista para verificar que todo funciona correctamente.

---

## 🔧 Configuración Inicial

### 1. Verificar Requisitos del Sistema

```bash
# Verificar versión de PHP
php --version
# Debe ser >= 8.2

# Verificar que Apache está corriendo
# Abre http://localhost en tu navegador
# Deberías ver el dashboard de XAMPP
```

- [ ] PHP 8.2+ instalado
- [ ] Apache corriendo
- [ ] MySQL corriendo en puerto 5060
- [ ] mod_rewrite habilitado

### 2. Configurar Base de Datos

```bash
# Desde la terminal en la carpeta del proyecto
cd c:\xampp\htdocs\UNIVERSIDAD\Integrador\7service

# Importar estructura de BD
mysql -u root -p -P 5060 < info/dump-taller_bicicletas-202510272252.sql

# Importar datos iniciales
mysql -u root -p -P 5060 taller_bicicletas < info/datos_iniciales.sql
```

- [ ] Base de datos `taller_bicicletas` creada
- [ ] Todas las tablas importadas (18 tablas)
- [ ] Datos iniciales insertados
- [ ] Usuarios de prueba creados

### 3. Configurar Variables de Entorno

Edita el archivo `.env`:

```env
DB_HOST=localhost
DB_PORT=5060
DB_NAME=taller_bicicletas
DB_USER=root
DB_PASS=                    # ← Agrega tu contraseña aquí
```

- [ ] Archivo `.env` existe
- [ ] Credenciales de BD correctas
- [ ] `APP_DEBUG=true` para desarrollo

---

## 🧪 Pruebas Funcionales

### Test 1: Acceso a la Aplicación

1. Abre tu navegador
2. Ve a: `http://localhost/UNIVERSIDAD/Integrador/7service/public`

**Resultado Esperado:**
- ✅ Se muestra la pantalla de login
- ✅ Diseño Tailwind CSS carga correctamente
- ✅ No hay errores en la consola del navegador

- [ ] Pantalla de login visible
- [ ] Estilos se cargan correctamente
- [ ] No hay errores 404

### Test 2: Login de Usuario

**Credenciales Admin:**
- Email: `admin@sevenservice.com`
- Contraseña: `admin123`

**Pasos:**
1. Ingresa las credenciales
2. Click en "Iniciar Sesión"

**Resultado Esperado:**
- ✅ Redirige a `/dashboard`
- ✅ Se muestra el nombre del usuario en el navbar
- ✅ Se pueden ver las estadísticas

- [ ] Login exitoso con Admin
- [ ] Sesión se mantiene al navegar
- [ ] Botón "Salir" funciona

**Credenciales Técnico:**
- Email: `tecnico@sevenservice.com`
- Contraseña: `admin123`

- [ ] Login exitoso con Técnico
- [ ] Permisos diferentes al Admin

### Test 3: Dashboard

**Pasos:**
1. Navega a `/dashboard`

**Resultado Esperado:**
- ✅ Se muestran 4 tarjetas de estadísticas
- ✅ Sección de órdenes pendientes
- ✅ Sección de stock bajo
- ✅ Tabla de órdenes recientes

- [ ] Estadísticas visibles
- [ ] No hay errores de PHP
- [ ] Navegación funciona

### Test 4: Gestión de Clientes

#### 4.1 Listar Clientes

**Pasos:**
1. Click en "Clientes" en el navbar
2. Verifica la lista

**Resultado Esperado:**
- ✅ Se muestran los clientes de ejemplo
- ✅ Barra de búsqueda funciona
- ✅ Botón "Crear Nuevo" visible

- [ ] Lista de clientes carga
- [ ] Búsqueda funcional
- [ ] Paginación (si aplica)

#### 4.2 Crear Cliente

**Pasos:**
1. Click en "Crear Nuevo Cliente"
2. Completa el formulario:
   - Nombre: "Juan Pérez Test"
   - Teléfono: "987654321"
   - Email: "juan@test.com"
3. Click en "Guardar"

**Resultado Esperado:**
- ✅ Mensaje de éxito
- ✅ Redirige a la lista
- ✅ Cliente aparece en la lista

- [ ] Validación de campos funciona
- [ ] Cliente se guarda en BD
- [ ] Aparece en la lista

#### 4.3 Ver Detalle de Cliente

**Pasos:**
1. Click en un cliente
2. Verifica información

**Resultado Esperado:**
- ✅ Se muestran datos del cliente
- ✅ Historial de servicios
- ✅ Bicicletas registradas

- [ ] Detalle completo visible
- [ ] Botones de acción funcionan

#### 4.4 Editar Cliente

**Pasos:**
1. En detalle, click "Editar"
2. Modifica el teléfono
3. Guarda cambios

**Resultado Esperado:**
- ✅ Cambios se guardan
- ✅ Se actualiza la información

- [ ] Edición exitosa
- [ ] Validación funciona

### Test 5: Inventario

#### 5.1 Listar Productos

**Pasos:**
1. Click en "Inventario"

**Resultado Esperado:**
- ✅ Lista de productos con stock
- ✅ Categorías visibles
- ✅ SKU, precio y stock mostrados

- [ ] Productos se listan
- [ ] Información completa

#### 5.2 Buscar Producto

**Pasos:**
1. Usa el buscador
2. Escribe "cadena"

**Resultado Esperado:**
- ✅ Resultados filtrados
- ✅ Búsqueda por nombre y SKU

- [ ] Búsqueda funciona
- [ ] Resultados correctos

#### 5.3 Crear Producto

**Pasos:**
1. Click "Agregar Producto"
2. Completa:
   - SKU: "TEST-001"
   - Nombre: "Producto de Prueba"
   - Categoría: Selecciona una
   - Precio Venta: 50.00
   - Stock: 10
   - Stock Mínimo: 3
3. Guarda

**Resultado Esperado:**
- ✅ Producto creado
- ✅ Aparece en la lista
- ✅ SKU es único

- [ ] Creación exitosa
- [ ] Validación de SKU único

### Test 6: Órdenes de Servicio

#### 6.1 Crear Orden

**Pasos:**
1. Click en "Órdenes" → "Nueva Orden"
2. Paso 1: Selecciona un cliente
3. Paso 2: Selecciona una bicicleta
4. Paso 3: Describe el problema
5. Paso 4: Agrega productos (opcionales)
6. Guarda

**Resultado Esperado:**
- ✅ Orden creada con ID único
- ✅ Estado inicial "Pendiente"
- ✅ Si agregaste productos, stock se descuenta

- [ ] Orden creada exitosamente
- [ ] Stock actualizado automáticamente

#### 6.2 Ver Detalle de Orden

**Pasos:**
1. Click en una orden reciente

**Resultado Esperado:**
- ✅ Información completa
- ✅ Cliente y bicicleta
- ✅ Productos usados
- ✅ Costos calculados

- [ ] Detalle completo
- [ ] Cálculos correctos

#### 6.3 Cambiar Estado de Orden

**Pasos:**
1. En detalle, click "Cambiar Estado"
2. Selecciona "En Reparación"
3. Guarda

**Resultado Esperado:**
- ✅ Estado actualizado
- ✅ Se registra en historial

- [ ] Cambio de estado funciona
- [ ] Historial se registra

### Test 7: Reportes y Estadísticas

**Pasos:**
1. Navega a Dashboard
2. Selecciona rango de fechas
3. Observa cambios en estadísticas

**Resultado Esperado:**
- ✅ Filtros funcionan
- ✅ Estadísticas se actualizan
- ✅ Datos correctos

- [ ] Filtros de fecha funcionan
- [ ] Cálculos correctos

---

## 🔒 Pruebas de Seguridad

### Test 8: Autenticación

#### 8.1 Acceso sin Login

**Pasos:**
1. Cierra sesión
2. Intenta acceder a `/dashboard` directamente

**Resultado Esperado:**
- ✅ Redirige a `/login`
- ✅ No se puede acceder sin autenticación

- [ ] Rutas protegidas funcionan
- [ ] Redireccionamiento correcto

#### 8.2 Contraseñas

**Pasos:**
1. Intenta login con contraseña incorrecta

**Resultado Esperado:**
- ✅ Mensaje de error
- ✅ No revela información del usuario

- [ ] Validación de contraseña
- [ ] Mensaje genérico de error

### Test 9: Autorización por Roles

**Pasos:**
1. Login como Técnico
2. Intenta acceder a `/usuarios`

**Resultado Esperado:**
- ✅ Acceso denegado
- ✅ Mensaje 403 o redirección

- [ ] Roles funcionan correctamente
- [ ] Técnico no puede gestionar usuarios

### Test 10: SQL Injection

**Pasos:**
1. En búsqueda de clientes, escribe: `' OR '1'='1`

**Resultado Esperado:**
- ✅ No hay error
- ✅ Prepared statements previenen inyección

- [ ] Sistema protegido contra SQL Injection

### Test 11: XSS

**Pasos:**
1. Crea cliente con nombre: `<script>alert('XSS')</script>`

**Resultado Esperado:**
- ✅ Script no se ejecuta
- ✅ Se muestra como texto plano

- [ ] Sistema protegido contra XSS

---

## 📊 Pruebas de Rendimiento

### Test 12: Tiempo de Carga

**Herramienta:** Chrome DevTools (F12 → Network)

**Pasos:**
1. Abre DevTools
2. Carga `/dashboard`
3. Observa el tiempo

**Resultado Esperado:**
- ✅ Carga en < 2 segundos
- ✅ No hay recursos bloqueantes

- [ ] Dashboard carga rápido
- [ ] Recursos optimizados

### Test 13: Consultas a BD

**Pasos:**
1. Revisa `storage/logs/database.log`
2. Verifica las consultas

**Resultado Esperado:**
- ✅ Consultas optimizadas
- ✅ No hay N+1 queries

- [ ] Log de BD funciona
- [ ] Consultas eficientes

---

## 🐛 Pruebas de Manejo de Errores

### Test 14: Error de Conexión a BD

**Pasos:**
1. En `.env`, cambia `DB_PASS` a una incorrecta
2. Recarga la página

**Resultado Esperado:**
- ✅ Mensaje de error claro (en desarrollo)
- ✅ No se revelan credenciales

- [ ] Manejo de error de conexión

### Test 15: Archivo No Encontrado

**Pasos:**
1. Visita `/ruta-que-no-existe`

**Resultado Esperado:**
- ✅ Error 404
- ✅ Mensaje "Página no encontrada"

- [ ] 404 manejado correctamente

---

## 📱 Pruebas de Responsive

### Test 16: Diseño Móvil

**Pasos:**
1. Abre DevTools (F12)
2. Click en icono de dispositivo móvil
3. Navega por la aplicación

**Resultado Esperado:**
- ✅ Diseño se adapta
- ✅ Menú hamburguesa funciona
- ✅ Tablas scrolleables

- [ ] Responsive en móvil
- [ ] Navegación funcional

---

## 📝 Resumen de Pruebas

```
TOTAL DE TESTS: 16
TESTS PASADOS: ___ / 16
TESTS FALLADOS: ___ / 16
COBERTURA: ____%
```

### Estados

- ✅ **PASS**: Funciona correctamente
- ❌ **FAIL**: No funciona
- ⚠️ **WARN**: Funciona pero con problemas

---

## 🔧 Solución de Problemas Comunes

### Problema: "Database connection failed"

**Solución:**
```bash
# Verifica que MySQL esté corriendo
netstat -ano | findstr :5060

# Verifica credenciales en .env
DB_PORT=5060  # No 3306
```

### Problema: "404 Not Found en todas las rutas"

**Solución:**
```bash
# Verifica mod_rewrite en httpd.conf
LoadModule rewrite_module modules/mod_rewrite.so

# Reinicia Apache
```

### Problema: "Estilos no cargan"

**Solución:**
```html
<!-- Verifica conexión a internet (Tailwind CDN) -->
<!-- O descarga Tailwind localmente -->
```

---

## 📞 Reportar Issues

Si encuentras un bug:

1. Captura pantalla del error
2. Copia el mensaje de error completo
3. Describe los pasos para reproducirlo
4. Verifica los logs en `storage/logs/`
5. Crea un issue en GitHub

---

**¡Felicitaciones!** Si todos los tests pasan, tu sistema está listo para producción. 🎉
