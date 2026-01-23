FROM php:8.4-cli

WORKDIR /var/www/html

# System deps (CRITICAL for mongodb)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    pkg-config \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libssl-dev \
    libsasl2-dev \
    libmongoc-dev \
    libbson-dev \
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

# MongoDB + Redis (NO PECL COMPILATION)
RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install mongodb-1.20.1 \
    && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App
COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-dev --optimize-autoloader \
    && php artisan key:generate --force \
    && php artisan optimize

ENV PORT=10000
EXPOSE 10000

CMD php -S 0.0.0.0:${PORT} -t public
