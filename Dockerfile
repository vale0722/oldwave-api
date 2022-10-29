FROM php:8.1-apache-buster

RUN apt-get update \
    && apt-get install -y --no-install-recommends gettext libcurl4-openssl-dev \
    libpq-dev libxslt-dev \
    libxml2-dev libicu-dev libfreetype6-dev libjpeg62-turbo-dev libmemcached-dev \
    zlib1g-dev unixodbc-dev \
    locales libaio1 libcurl4 libgss3 libpq5 \
    libmemcached11 libmemcachedutil2 libxml2 libxslt1.1 unixodbc \
    libmcrypt-dev \
    unzip ghostscript locales apt-transport-https

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY ./ /var/www/html/
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN php artisan config:cache && \
    php artisan route:cache && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite
