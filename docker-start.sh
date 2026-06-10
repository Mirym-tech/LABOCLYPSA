#!/bin/sh
set -e

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Fijar permisos para php-fpm (www-data)
chown -R www-data:www-data /app/storage /app/bootstrap/cache

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/app.conf
