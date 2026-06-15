FROM php:8.3-apache

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    curl git zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache: enable rewrite, point document root at Laravel public/
RUN a2enmod rewrite headers
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf && \
    printf '<Directory /var/www/html/public>\n\tAllowOverride All\n\tOptions -Indexes\n</Directory>\n' \
        >> /etc/apache2/apache2.conf

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source and install dependencies
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm ci && npm run build && rm -rf node_modules

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
