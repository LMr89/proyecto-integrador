FROM php:8.3-apache

WORKDIR /var/www/html

# copiamos todo el proyecto
COPY . /var/www/html

# si tu index.php está en public, puedes:
# 1) mover el contenido de public/ a la raíz
#    o
# 2) apuntar el DocumentRoot a /var/www/html/public
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/apache2.conf

# extensiones que necesites (ejemplo: MySQL)
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 80
