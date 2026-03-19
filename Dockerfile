FROM php:8.2-fpm

# Install system + nginx + pgsql
RUN apt-get update && apt-get install -y \
    nginx \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www

# Copy project
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose port
EXPOSE 10000

# Start both services
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]