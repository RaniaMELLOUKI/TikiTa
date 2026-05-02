FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html/

# Installer les dependances PHPUnit
RUN cd /var/www/html && composer install --no-interaction --no-progress 2>/dev/null || true

# Nettoyage des fichiers DevOps
RUN rm -f /var/www/html/Dockerfile \
    /var/www/html/Jenkinsfile \
    /var/www/html/docker-compose.yml \
    /var/www/html/.gitignore && \
    rm -rf /var/www/html/k8s /var/www/html/terraform

RUN echo "DirectoryIndex index.php index.html" \
    > /etc/apache2/conf-available/priority.conf \
    && a2enconf priority

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]