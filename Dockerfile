# Dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev \
    build-essential pkg-config curl \
    && rm -rf /var/lib/apt/lists/*

# Configure GD for images
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath tokenizer xml curl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Laravel dependencies
RUN php -d memory_limit=-1 /usr/bin/composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 9000

CMD ["php-fpm"]