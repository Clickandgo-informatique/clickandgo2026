FROM php:8.3-fpm

# -------------------------------------------------------
# 1. Dépendances système
# -------------------------------------------------------
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    make \
    bash \
    netcat-openbsd \
    postgresql-client \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install intl zip opcache pdo pdo_pgsql

# -------------------------------------------------------
# 2. Extension Redis
# -------------------------------------------------------
RUN pecl install redis \
    && docker-php-ext-enable redis

# -------------------------------------------------------
# 3. Composer
# -------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -------------------------------------------------------
# 4. Symfony CLI (méthode stable officielle)
# -------------------------------------------------------
RUN curl -1sLf \
  'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' \
  | bash \
  && apt-get install -y symfony-cli

# -------------------------------------------------------
# 5. Entrypoint
# -------------------------------------------------------
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# -------------------------------------------------------
# 6. Dossier de travail
# -------------------------------------------------------
WORKDIR /var/www/html

# IMPORTANT : pas d'ENTRYPOINT ici
