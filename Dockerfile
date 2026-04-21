FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Node.js for Vite
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the application
COPY . .

# Install PHP dependencies
# We use --no-scripts to avoid running artisan commands during build if extensions/env are not fully ready
RUN composer install --no-dev --optimize-autoloader --no-ansi --no-interaction

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Copy nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Prepare storage and cache
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Make start.sh executable
RUN chmod +x /var/www/start.sh

# Expose the port
EXPOSE 10000

# Use start.sh to run migrations and start services
CMD ["/var/www/start.sh"]