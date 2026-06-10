FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libpng-dev libxml2-dev libonig-dev \
    nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml zip gd opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# OPcache para producción (sin revalidar archivos = máximo rendimiento)
RUN echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nopcache.revalidate_freq=0\nopcache.validate_timestamps=0" \
    > /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN mkdir -p storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /app

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/app.conf

RUN chmod +x docker-start.sh

EXPOSE 8080

CMD ["sh", "docker-start.sh"]
