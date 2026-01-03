FROM wordpress:php8.2-fpm

# Install additional PHP extensions if needed
RUN docker-php-ext-install opcache

# WordPress Core is included in the base image at /usr/src/wordpress
# On first run, it copies to /var/www/html if empty

# Custom PHP settings
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
