FROM php:8.3-apache

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache para URLs amigables
RUN a2enmod rewrite

# Configurar DocumentRoot para apuntar a /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar AllowOverride para que funcione .htaccess
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto con permisos correctos
COPY --chown=www-data:www-data . /var/www/html

# Crear directorio de logs y asignar permisos
RUN mkdir -p /var/www/html/storage/logs && \
    chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage

# Exponer el puerto 80
EXPOSE 80
