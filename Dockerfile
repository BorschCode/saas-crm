FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# Встановлюємо лише системні залежності
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libicu-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libonig-dev libssl-dev \
    pkg-config \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP core extensions (без pcntl/sockets якщо не потрібні)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    mbstring \
    zip \
    gd \
    exif \
    opcache

# MongoDB і Redis БЕЗ компіляції — використаємо готові .so файли
RUN mkdir -p /tmp/extensions && cd /tmp/extensions \
    && curl -L https://github.com/mongodb/mongo-php-driver/releases/download/1.20.1/mongodb-1.20.1.tgz -o mongodb.tgz \
    && tar -xzf mongodb.tgz \
    && cd mongodb-1.20.1 \
    && phpize && ./configure && make -j$(nproc) && make install \
    && docker-php-ext-enable mongodb \
    && cd /tmp && rm -rf /tmp/extensions

# Redis (швидко компілюється)
RUN pecl install redis-6.1.0 \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

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
