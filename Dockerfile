FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# System deps
RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

# PHP extensions
RUN docker-php-ext-install \
    intl \
    mbstring \
    zip \
    gd \
    pcntl \
    sockets

# MongoDB + Redis
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force \
    && php artisan optimize

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:${PORT}", "-t", "public"]
