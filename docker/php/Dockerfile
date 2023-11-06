FROM php:8.2-fpm
WORKDIR /var/www/textmagic

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql
