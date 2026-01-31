FROM php:8.2-apache

# Install dependencies for Laravel and PostgreSQL (Render)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    git \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Set Apache Document Root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# CRITICAL FIX: Ensure sensitive directories exist and are writable before Composer runs
RUN mkdir -p bootstrap/cache storage/framework/views storage/framework/cache storage/framework/sessions storage/logs \
    && chmod -R 777 bootstrap/cache storage

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set final ownership for runtime (Apache runs as www-data)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Render sets this dynamically, but 80 is container default)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
