FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev curl zip libonig-dev libpng-dev libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions needed for Laravel + PostgreSQL + Cloudinary
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath tokenizer xml curl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Laravel dependencies
RUN php -d memory_limit=-1 /usr/bin/composer install --no-dev --optimize-autoloader

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]