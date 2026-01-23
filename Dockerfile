FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# System deps
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libpng16-16 libjpeg62-turbo libfreetype6 \
    libzip4 libicu72 \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions (PREBUILT, NO COMPILATION)
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions \
    mongodb \
    redis \
    intl \
    mbstring \
    zip \
    gd \
    exif \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-cache \
 && php artisan key:generate --force \
 && php artisan optimize


RUN php artisan package:discover --ansi \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

EXPOSE 10000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=10000"]
