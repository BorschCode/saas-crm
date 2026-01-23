FROM php:8.3-fpm

WORKDIR /var/www/html

# System deps
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    intl \
    mbstring \
    zip \
    gd \
    pcntl \
    sockets

# MongoDB + Redis (stable on Debian)
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# Install deps & optimize
RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force \
    && php artisan optimize

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:${PORT}", "-t", "public"]
