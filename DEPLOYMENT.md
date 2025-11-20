# Guía de Despliegue en Servidor

## Pre-requisitos

1. Servidor con Docker instalado (recomendado)
2. Acceso SSH al servidor
3. Contenedor de base de datos MySQL ya funcionando
4. Puerto 80 o 443 disponible (o usar proxy inverso)

## Despliegue con Docker

### Paso 1: Preparar el servidor

```bash
# Conectar al servidor
ssh usuario@tu-servidor.com

# Crear directorio para el proyecto
mkdir -p /var/www/7service
cd /var/www/7service
```

### Paso 2: Subir archivos al servidor

**Opción A: Git (Recomendado)**
```bash
git clone tu-repositorio.git .
```

**Opción B: SCP/SFTP**
```bash
# Desde tu máquina local
scp -r . usuario@tu-servidor.com:/var/www/7service/
```

### Paso 3: Configurar variables de entorno

```bash
# Copiar el archivo de ejemplo
cp .env.production .env

# Editar con tus credenciales reales
nano .env
```

**Configuración CRÍTICA en .env:**
```env
# Nombre del contenedor de tu base de datos
DB_HOST=nombre-contenedor-mysql

# O si usas IP:
# DB_HOST=172.17.0.2

DB_PORT=5060
DB_NAME=taller_bicicletas
DB_USER=root
DB_PASS=tu-password-real-aqui

APP_ENV=production
APP_DEBUG=false
APP_URL=http://tu-dominio.com
APP_BASE_PATH=

# GENERAR CLAVE SEGURA (32 caracteres aleatorios)
APP_KEY=abc123xyz789def456ghi012jkl345mn
```

### Paso 4: Conectar a la red de Docker de la BD

```bash
# Listar redes Docker existentes
docker network ls

# Conectar a la red donde está tu contenedor de BD
# O crear una red compartida
docker network create 7service-network

# Conectar el contenedor de BD a la red (si no está)
docker network connect 7service-network nombre-contenedor-bd
```

### Paso 5: Construir y ejecutar

**Opción A: Docker Compose**
```bash
# Editar docker-compose.yml con los datos reales
nano docker-compose.yml

# Levantar el servicio
docker-compose up -d

# Ver logs
docker-compose logs -f
```

**Opción B: Docker CLI**
```bash
# Construir imagen
docker build -t 7service-app .

# Ejecutar contenedor
docker run -d \
  --name 7service-app \
  -p 80:80 \
  --env-file .env \
  --network 7service-network \
  --restart unless-stopped \
  7service-app

# Ver logs
docker logs -f 7service-app
```

### Paso 6: Verificar funcionamiento

```bash
# Verificar que el contenedor está corriendo
docker ps

# Probar la aplicación
curl http://localhost

# O desde el navegador
# http://tu-dominio.com
```

## Configuración SSL/HTTPS (Producción)

### Opción 1: Usar Nginx como proxy inverso

```bash
# Instalar Nginx
apt-get update && apt-get install nginx certbot python3-certbot-nginx

# Configurar Nginx
nano /etc/nginx/sites-available/7service
```

```nginx
server {
    listen 80;
    server_name tu-dominio.com;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

```bash
# Activar configuración
ln -s /etc/nginx/sites-available/7service /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx

# Instalar certificado SSL
certbot --nginx -d tu-dominio.com
```

### Opción 2: Traefik como proxy inverso

Ver archivo `docker-compose.traefik.yml` de ejemplo.

## Actualización de la aplicación

```bash
# Detener contenedor
docker stop 7service-app

# Actualizar código (git pull o subir nuevos archivos)
git pull origin main

# Reconstruir imagen
docker build -t 7service-app .

# Eliminar contenedor viejo
docker rm 7service-app

# Crear nuevo contenedor
docker run -d \
  --name 7service-app \
  -p 80:80 \
  --env-file .env \
  --network 7service-network \
  --restart unless-stopped \
  7service-app
```

## Troubleshooting

### No se conecta a la base de datos

```bash
# Verificar que ambos contenedores están en la misma red
docker network inspect 7service-network

# Probar conexión desde el contenedor
docker exec -it 7service-app ping nombre-contenedor-bd

# Ver logs del contenedor
docker logs 7service-app
```

### Error 500 en la aplicación

```bash
# Verificar permisos de storage
docker exec -it 7service-app ls -la /var/www/html/storage

# Ver logs de PHP
docker exec -it 7service-app tail -f /var/www/html/storage/logs/php_errors.log

# Verificar configuración de Apache
docker exec -it 7service-app apachectl configtest
```

### Acceso denegado a archivos

```bash
# Arreglar permisos
docker exec -it 7service-app chown -R www-data:www-data /var/www/html
docker exec -it 7service-app chmod -R 755 /var/www/html/storage
```

## Seguridad en Producción

1. **Generar APP_KEY segura:**
   ```bash
   # Generar clave aleatoria de 32 caracteres
   openssl rand -base64 32
   ```

2. **Deshabilitar debug:**
   ```env
   APP_DEBUG=false
   ```

3. **Usar HTTPS siempre**

4. **Firewall:**
   ```bash
   ufw allow 80/tcp
   ufw allow 443/tcp
   ufw enable
   ```

5. **Backup de base de datos:**
   ```bash
   # Crear script de backup automático
   docker exec nombre-contenedor-bd mysqldump -u root -p taller_bicicletas > backup_$(date +%Y%m%d).sql
   ```

## Monitoreo

```bash
# Ver uso de recursos
docker stats 7service-app

# Ver logs en tiempo real
docker logs -f --tail 100 7service-app

# Reiniciar si es necesario
docker restart 7service-app
```
