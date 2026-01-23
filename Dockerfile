FROM php:8.4-fpm

WORKDIR /var/www/html

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    pkg-config \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    intl \
    mbstring \
    zip \
    gd \
    exif \
    pcntl \
    sockets

# MongoDB + Redis
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App source
COPY . .

# Git safety (REQUIRED)
RUN git config --global --add safe.directory /var/www/html

# Install deps & optimize
RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force \
    && php artisan optimize

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:${PORT}", "-t", "public"]
