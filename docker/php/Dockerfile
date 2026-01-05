FROM php:8.3-fpm

# Libs système
RUN apt-get update && apt-get install -y netcat-openbsd postgresql-client\
    git unzip libicu-dev libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install intl zip opcache pdo_mysql pdo_pgsql

# Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier l'entrypoint dans l'image
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# IMPORTANT : ne pas définir d'ENTRYPOINT ici
# On laisse docker-compose gérer l'entrypoint
