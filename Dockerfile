FROM serversideup/php:8.2-fpm-nginx

USER root

# Install PostgreSQL extension
RUN install-php-extensions pdo_pgsql pgsql

USER www-data

COPY --chown=www-data:www-data . /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && mkdir -p storage/framework/cache/data storage/framework/sessions \
       storage/framework/views storage/logs \
    && chmod -R 775 storage bootstrap/cache

COPY --chown=www-data:www-data docker-start.sh /usr/local/bin/start
USER root
RUN chmod +x /usr/local/bin/start

CMD ["/usr/local/bin/start"]
