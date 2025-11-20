# 🚀 Guía de Inicio Rápido - Seven Service

## Pasos para Ejecutar el Proyecto (5 minutos)

### 1️⃣ Verificar XAMPP

```bash
# Asegúrate de que Apache y MySQL estén corriendo
# Abre el Panel de Control de XAMPP y verifica:
✅ Apache - Running
✅ MySQL - Running (puerto 5060)
```

### 2️⃣ Importar la Base de Datos

**Opción A: Desde la línea de comandos**
```bash
cd c:\xampp\htdocs\UNIVERSIDAD\Integrador\7service
mysql -u root -p -P 5060 < info/dump-taller_bicicletas-202510272252.sql
```

**Opción B: Desde DBeaver**
1. Conectar a `localhost:5060`
2. Click derecho en la conexión → SQL Editor → Open SQL Script
3. Seleccionar `info/dump-taller_bicicletas-202510272252.sql`
4. Ejecutar (F5)

### 3️⃣ Insertar Datos Iniciales

```bash
mysql -u root -p -P 5060 taller_bicicletas < info/datos_iniciales.sql
```

Esto creará:
- ✅ Usuario Administrador
- ✅ Usuario Técnico
- ✅ Categorías de productos
- ✅ Proveedores de ejemplo
- ✅ Productos de ejemplo

### 4️⃣ Configurar Variables de Entorno

El archivo `.env` ya está creado, solo verifica la configuración:

```env
DB_HOST=localhost
DB_PORT=5060
DB_NAME=taller_bicicletas
DB_USER=root
DB_PASS=         # Agrega tu contraseña si tienes una
```

### 5️⃣ Acceder a la Aplicación

Abre tu navegador en:
```
http://localhost/UNIVERSIDAD/Integrador/7service/public
```

**Credenciales de prueba:**

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Admin | admin@sevenservice.com | admin123 |
| Técnico | tecnico@sevenservice.com | admin123 |

---

## ✅ Checklist de Verificación

Antes de empezar, asegúrate de:

- [ ] XAMPP instalado y corriendo
- [ ] PHP 8.2+ activo
- [ ] MySQL corriendo en puerto 5060
- [ ] Base de datos importada
- [ ] Datos iniciales insertados
- [ ] Archivo `.env` configurado
- [ ] Navegador moderno (Chrome, Firefox, Edge)

---

## 🐛 Solución de Problemas Comunes

### Error: "No se puede conectar a la base de datos"

**Solución:**
```php
// Verifica en .env:
DB_PORT=5060  // Debe ser 5060, no 3306
DB_NAME=taller_bicicletas  // Nombre correcto de la BD
```

### Error: "Call to undefined function password_hash()"

**Solución:**
```bash
# Verifica que estés usando PHP 8.2+
php --version

# Si es menor, actualiza PHP en XAMPP
```

### Error 404 en todas las rutas

**Solución:**
```bash
# Verifica que mod_rewrite esté activo en Apache
# En httpd.conf, busca y descomenta:
LoadModule rewrite_module modules/mod_rewrite.so

# Luego reinicia Apache
```

### Estilos no se cargan (Tailwind)

**Solución:**
```html
<!-- Verifica que tengas conexión a internet -->
<!-- Tailwind se carga desde CDN en header.php -->
<script src="https://cdn.tailwindcss.com"></script>
```

---

## 📖 Próximos Pasos

1. **Explora el Dashboard**
   - Ve estadísticas en tiempo real
   - Revisa órdenes pendientes
   - Verifica alertas de stock

2. **Crea tu Primer Cliente**
   - Menú → Clientes → Crear Nuevo
   - Completa el formulario
   - Guarda y verifica

3. **Crea una Orden de Servicio**
   - Menú → Órdenes → Nueva Orden
   - Selecciona cliente y bicicleta
   - Describe el problema
   - Asigna técnico

4. **Gestiona el Inventario**
   - Menú → Inventario
   - Agrega productos
   - Configura stock mínimo
   - Recibe alertas automáticas

5. **Personaliza el Sistema**
   - Lee `README.md` completo
   - Revisa la arquitectura MVC
   - Agrega tus propios módulos

---

## 🎓 Recursos de Aprendizaje

### Conceptos Clave
- **MVC**: [Ver Arquitectura](docs/ARQUITECTURA.md)
- **PHP PDO**: [Documentación oficial](https://www.php.net/manual/es/book.pdo.php)
- **Tailwind CSS**: [Guía oficial](https://tailwindcss.com/docs)

### Tutoriales Paso a Paso
1. [Cómo funciona el Router](docs/tutoriales/router.md)
2. [Crear un nuevo modelo](docs/tutoriales/crear-modelo.md)
3. [Agregar un controlador](docs/tutoriales/crear-controlador.md)
4. [Diseñar una vista](docs/tutoriales/crear-vista.md)

---

## 💡 Tips para Principiantes

### 1. Entiende el Flujo de Datos
```
URL → Router → Middleware → Controller → Model → Database
                                      ↓
                               View (HTML)
```

### 2. Siempre Usa Prepared Statements
```php
// ❌ MAL (vulnerable a SQL injection)
$query = "SELECT * FROM usuarios WHERE correo = '$correo'";

// ✅ BIEN (seguro)
$query = "SELECT * FROM usuarios WHERE correo = :correo";
$params = [':correo' => $correo];
```

### 3. Sanitiza los Datos de Usuario
```php
// Antes de mostrar en HTML
$nombre = htmlspecialchars($usuario['nombre']);

// O usa el helper del controlador
$data = $this->sanitize($_POST);
```

### 4. Usa el Debugger
```php
// En modo desarrollo (APP_DEBUG=true)
var_dump($variable);
die();

// O revisa los logs
tail -f storage/logs/database.log
```

---

## 📞 Ayuda y Soporte

- **Documentación Completa**: `README.md`
- **Arquitectura Detallada**: `docs/ARQUITECTURA.md`
- **API REST**: `docs/swagger/swagger.yaml`
- **Issues**: Reporta bugs en GitHub Issues

---

**¡Listo para empezar! 🎉**

Cualquier duda, revisa el README.md principal o los archivos en la carpeta `docs/`.
