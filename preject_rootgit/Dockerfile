FROM php:8.2-apache
 
# Extensions PHP nécessaires pour MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql
 
# Activer mod_rewrite Apache
RUN a2enmod rewrite
 
# Copier tout le projet dans Apache
COPY . /var/www/html/
 
# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
 
EXPOSE 80
 
CMD ["apache2-foreground"]