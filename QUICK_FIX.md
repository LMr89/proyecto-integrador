# Fix Rápido para Error 404 en Servidor

## El Problema
Cuando inicias sesión en `https://seven-service.fernandoqdev.com/`, después del login te redirige a:
```
https://seven-service.fernandoqdev.com/UNIVERSIDAD/Integrador/7service/public/process_login.php
```
Y obtienes un **404**.

## La Solución (3 pasos)

### Paso 1: Verificar que tengas el archivo `.env` en el servidor

Conéctate al servidor y verifica:
```bash
ssh tu-usuario@seven-service.fernandoqdev.com
cd /ruta/donde/esta/tu/proyecto
ls -la .env
```

### Paso 2: Editar el archivo `.env` en el servidor

```bash
nano .env
```

Asegúrate de que la línea `APP_BASE_PATH` esté **VACÍA**:
```env
APP_BASE_PATH=
```

**NO** debe tener esto:
```env
APP_BASE_PATH=/UNIVERSIDAD/Integrador/7service/public  ❌ MAL
```

### Paso 3: Reiniciar el contenedor (si usas Docker)

```bash
docker restart 7service-app
# o si usas docker-compose:
docker-compose restart
```

Si NO usas Docker, reinicia Apache/PHP-FPM:
```bash
sudo systemctl restart apache2
# o
sudo systemctl restart php8.3-fpm
```

## Verificación

Después de estos pasos:
1. Ve a `https://seven-service.fernandoqdev.com/`
2. Inicia sesión
3. Deberías ser redirigido a `https://seven-service.fernandoqdev.com/dashboard` ✅

## Si sigue sin funcionar

### Opción A: Verificar que los archivos se actualizaron

```bash
# Ver las funciones helper en config.php
cat config/config.php | grep -A 5 "function url"

# Deberías ver:
# function url($path = '') {
#     $basePath = env('APP_BASE_PATH', '');
#     ...
# }
```

### Opción B: Verificar logs

```bash
# Si usas Docker:
docker logs 7service-app

# Si no usas Docker:
tail -f /var/log/apache2/error.log
# o
tail -f /var/www/html/storage/logs/php_errors.log
```

### Opción C: Forzar actualización de código

Si desplegaste antes de mis correcciones:

```bash
# Hacer backup
cp -r /ruta/proyecto /ruta/proyecto.backup

# Subir código actualizado desde tu máquina local
# Desde tu PC local:
scp -r . usuario@servidor:/ruta/proyecto/

# O si usas git:
cd /ruta/proyecto
git pull origin main

# Reiniciar
docker restart 7service-app
```

## Archivo .env Completo de Ejemplo para Servidor

```env
# CONFIGURACIÓN DE BASE DE DATOS
DB_HOST=nombre-contenedor-mysql
DB_PORT=3306
DB_NAME=taller_bicicletas
DB_USER=root
DB_PASS=tu-password-real

# CONFIGURACIÓN DE LA APLICACIÓN
APP_NAME="Seven Service - Taller de Bicicletas"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seven-service.fernandoqdev.com
APP_BASE_PATH=

# SEGURIDAD
APP_KEY=tu-clave-segura-de-32-caracteres-aqui
SESSION_LIFETIME=7200
```

## Notas Importantes

⚠️ **APP_BASE_PATH debe estar VACÍO en producción/Docker**
⚠️ No incluir `/UNIVERSIDAD/Integrador/7service/public` en ninguna parte del `.env` del servidor
⚠️ Después de cambiar `.env`, siempre reiniciar el servicio
