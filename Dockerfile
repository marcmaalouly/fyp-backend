FROM php:7.4.3-fpm
FROM composer:2.0
WORKDIR /app
RUN apk add  libpng libpng-dev && docker-php-ext-install pdo_mysql gd
COPY composer.json ./
COPY composer.lock ./
COPY . .
RUN composer install
ENV DB_HOST = db
CMD php artisan serve --host=0.0.0.0
