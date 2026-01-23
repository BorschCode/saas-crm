FROM php:8.4-fpm AS builder

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl unzip libicu-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libssl-dev autoconf g++ make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl mbstring zip gd exif pcntl sockets opcache \
    && pecl install mongodb-1.20.1 redis-6.1.0 \
    && docker-php-ext-enable mongodb redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .
RUN php artisan key:generate --force && php artisan optimize

# Runtime stage (smaller image)
FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libicu72 libzip4 libpng16-16 libjpeg62-turbo libfreetype6 \
    && rm -rf /var/lib/apt/lists/*

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /var/www/html /var/www/html

EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
