FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libpng-dev libxml2-dev libonig-dev \
    nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml zip gd opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# OPcache para producción
RUN printf "opcache.enable=1\n\
opcache.memory_consumption=256\n\
opcache.interned_strings_buffer=16\n\
opcache.max_accelerated_files=20000\n\
opcache.validate_timestamps=0\n\
opcache.revalidate_freq=0\n\
opcache.save_comments=1\n\
opcache.fast_shutdown=1\n" \
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
COPY docker/php-fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

# Forzar cache en archivo para que persista entre peticiones
ENV CACHE_STORE=file

RUN chmod +x docker-start.sh

EXPOSE 8080

CMD ["sh", "docker-start.sh"]
