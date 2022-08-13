FROM php:8.1-apache-buster

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY ./ /var/www/html/
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite
