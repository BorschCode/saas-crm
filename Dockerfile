# Stage 1: Build MongoDB extension (кешується окремо)
FROM php:8.4-cli-bookworm AS mongodb-builder

RUN apt-get update && apt-get install -y \
    libssl-dev pkg-config autoconf g++ make \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install mongodb-1.20.1 \
    && docker-php-ext-enable mongodb

# Stage 2: Build Redis extension (швидко)
FROM php:8.4-cli-bookworm AS redis-builder

RUN pecl install redis-6.1.0 \
    && docker-php-ext-enable redis

# Stage 3: Runtime application
FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# System dependencies (без build tools)
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libicu72 libzip4 libpng16-16 libjpeg62-turbo \
    libfreetype6 libonig5 libssl3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Build dependencies для PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl mbstring zip gd exif opcache \
    && apt-get purge -y --auto-remove \
        libicu-dev libzip-dev libpng-dev libjpeg-dev \
        libfreetype6-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Copy pre-built extensions
COPY --from=mongodb-builder /usr/local/lib/php/extensions/no-debug-non-zts-20230831/mongodb.so \
     /usr/local/lib/php/extensions/no-debug-non-zts-20230831/
COPY --from=mongodb-builder /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini \
     /usr/local/etc/php/conf.d/

COPY --from=redis-builder /usr/local/lib/php/extensions/no-debug-non-zts-20230831/redis.so \
     /usr/local/lib/php/extensions/no-debug-non-zts-20230831/
COPY --from=redis-builder /usr/local/etc/php/conf.d/docker-php-ext-redis.ini \
     /usr/local/etc/php/conf.d/

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-cache

COPY . .

RUN php artisan package:discover --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
