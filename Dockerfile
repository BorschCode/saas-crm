FROM php:8.4-fpm

WORKDIR /var/www/html

# System deps
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libexif-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PHP core extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    intl \
    mbstring \
    zip \
    gd \
    exif \
    pcntl \
    sockets \
    opcache

# ❗ MongoDB + Redis — ТІЛЬКИ PREBUILT
RUN apt-get update && apt-get install -y \
    php-mongodb \
    php-redis \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App
COPY . .

# Git safety
RUN git config --global --add safe.directory /var/www/html

# Laravel optimize
RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force \
    && php artisan optimize

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
