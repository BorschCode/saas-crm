FROM php:8.4-fpm

WORKDIR /var/www/html

# --------------------
# System dependencies
# --------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    pkg-config \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libexif-dev \
    && rm -rf /var/lib/apt/lists/*

# --------------------
# PHP core extensions
# --------------------
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

# --------------------
# PECL extensions (CRITICAL)
# --------------------
RUN pecl install mongodb redis \
 && docker-php-ext-enable mongodb redis

# --------------------
# Composer
# --------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --------------------
# App source
# --------------------
COPY . .

# Git safety (required by Composer)
RUN git config --global --add safe.directory /var/www/html

# --------------------
# Laravel optimize
# --------------------
RUN composer install --no-dev --optimize-autoloader \
 && php artisan key:generate --force \
 && php artisan optimize

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
