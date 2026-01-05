FROM php:8.3-fpm

# Libs système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install intl zip opcache pdo_mysql pdo_pgsql

# Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
