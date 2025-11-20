# Correcciones para Docker - Rutas Dinámicas

## Problema Original

El proyecto tenía rutas hardcodeadas a `/UNIVERSIDAD/Integrador/7service/public/` que funcionaban solo en tu entorno local, pero NO funcionaban en Docker donde la aplicación se sirve directamente desde la raíz (`/`).

## Solución Implementada

### 1. Funciones Helper Creadas en `config/config.php`

```php
// Genera URL relativa considerando APP_BASE_PATH
function url($path = '') {
    $basePath = env('APP_BASE_PATH', '');
    $path = '/' . ltrim($path, '/');
    return $basePath . $path;
}

// Genera URL completa con dominio
function full_url($path = '') {
    return rtrim(env('APP_URL', 'http://localhost'), '/') . url($path);
}

// Redirección con soporte de base path
function redirect($path = '', $statusCode = 302) {
    header('Location: ' . url($path), true, $statusCode);
    exit;
}
```

### 2. Variable de Entorno `APP_BASE_PATH`

**Entorno Local (.env):**
```env
APP_BASE_PATH=/UNIVERSIDAD/Integrador/7service/public
```

**Docker (.env o docker-compose.yml):**
```env
APP_BASE_PATH=
```

### 3. Archivos Modificados

#### Configuración:
- ✅ `config/config.php` - Agregadas funciones helper
- ✅ `.env.example` - Agregada variable APP_BASE_PATH
- ✅ `.env.production` - Configurada para Docker
- ✅ `docker-compose.yml` - Variable de entorno agregada

#### Core:
- ✅ `public/index.php` - Usa `env('APP_BASE_PATH')` en lugar de ruta hardcodeada
- ✅ `public/process_login.php` - Usa función `redirect()`
- ✅ `public/login_simple.php` - Usa función `redirect()`

#### Middleware:
- ✅ `app/Middleware/AuthMiddleware.php` - Usa función `redirect()`

#### Controllers:
- ✅ `app/Controllers/AuthController.php` - Usa funciones `redirect()` y `url()`
- ✅ `app/Controllers/ClienteController.php` - Usa función `redirect()`
- ✅ `app/Controllers/OrdenController.php` - Usa función `redirect()`
- ✅ `app/Controllers/InventarioController.php` - Usa función `redirect()`
- ✅ `app/Controllers/SeguimientoController.php` - Usa función `redirect()`

#### Views:
- ✅ `app/Views/layouts/header.php` - Todos los enlaces usan `url()`
- ✅ `app/Views/auth/login.php` - Formulario usa `url()`
- ✅ Todas las demás vistas en `app/Views/**/*.php` - Enlaces actualizados

## Cómo Usar

### Desarrollo Local:
```bash
# Tu .env
APP_BASE_PATH=/UNIVERSIDAD/Integrador/7service/public
```

Las URLs generarán:
- `url('/dashboard')` → `/UNIVERSIDAD/Integrador/7service/public/dashboard`
- `url('/api/clientes')` → `/UNIVERSIDAD/Integrador/7service/public/api/clientes`

### Producción Docker:
```bash
# .env en Docker
APP_BASE_PATH=
```

Las URLs generarán:
- `url('/dashboard')` → `/dashboard`
- `url('/api/clientes')` → `/api/clientes`

## Ejemplos de Uso

### En Controladores (PHP):
```php
// Redireccionar
redirect('/dashboard');
redirect('/clientes/nuevo');

// Generar URL para JSON
$this->json([
    'redirect' => url('/dashboard')
]);
```

### En Vistas (HTML/PHP):
```php
<!-- Enlaces -->
<a href="<?php echo url('/clientes'); ?>">Clientes</a>
<a href="<?php echo url('/ordenes/nuevo'); ?>">Nueva Orden</a>

<!-- Formularios -->
<form action="<?php echo url('/process_login.php'); ?>" method="POST">
```

## Verificación

Para verificar que todo funciona:

1. **En local:**
   - Acceder a `http://localhost/UNIVERSIDAD/Integrador/7service/public/`
   - Login debe funcionar
   - Navegación debe funcionar

2. **En Docker:**
   ```bash
   docker-compose up -d
   # Acceder a http://localhost:8080 o tu dominio
   # Login debe funcionar
   # Navegación debe funcionar
   ```

## Notas Importantes

- ✅ **NO** más rutas hardcodeadas `/UNIVERSIDAD/Integrador/7service/public/`
- ✅ Siempre usar `url()` para enlaces
- ✅ Siempre usar `redirect()` para redirecciones
- ✅ Funciona en local Y en Docker sin cambios de código
- ✅ Solo cambiar la variable `APP_BASE_PATH` en `.env`

## Troubleshooting

### Error 404 después de login:
- Verificar que `APP_BASE_PATH` esté correctamente configurado en `.env`
- En Docker debe estar vacío: `APP_BASE_PATH=`
- En local debe tener la ruta completa: `APP_BASE_PATH=/UNIVERSIDAD/Integrador/7service/public`

### Los enlaces no funcionan:
- Verificar que uses `<?php echo url('/ruta'); ?>` en lugar de hardcodear
- Verificar que el archivo `.env` exista y esté cargado

### Redirecciones infinitas:
- Verificar que `APP_BASE_PATH` NO tenga espacios ni comillas extras
- Verificar que comience con `/` si no está vacío
