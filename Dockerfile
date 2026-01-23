# Stage 1: Build extensions
FROM php:8.4-cli-alpine AS extensions

RUN apk add --no-cache autoconf g++ make linux-headers openssl-dev \
    && pecl install mongodb-1.20.1 redis-6.1.0 \
    && docker-php-ext-enable mongodb redis

# Stage 2: Runtime
FROM php:8.4-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git curl \
    icu libzip libpng libjpeg-turbo freetype oniguruma \
    icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl mbstring zip gd exif pcntl sockets opcache \
    && apk del icu-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev

# Копіюємо pre-compiled extensions зі stage 1
COPY --from=extensions /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=extensions /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

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
