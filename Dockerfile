FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl unzip \
    libicu-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libonig-dev libssl-dev \
    autoconf g++ make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl mbstring zip gd exif pcntl sockets opcache \
    && pecl install mongodb-1.20.1 redis-6.1.0 \
    && docker-php-ext-enable mongodb redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/pear

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
