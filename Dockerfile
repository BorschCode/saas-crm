FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# --------------------
# System deps + Node 20 (КРИТИЧНО)
# --------------------
RUN apt-get update && apt-get install -y \
    curl ca-certificates gnupg \
    git unzip \
    libpng16-16 libjpeg62-turbo libfreetype6 \
    libzip4 libicu72 \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v \
    && rm -rf /var/lib/apt/lists/*

# --------------------
# PHP extensions
# --------------------
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

# --------------------
# Composer
# --------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --------------------
# App source
# --------------------
COPY . .

RUN git config --global --add safe.directory /var/www/html

# --------------------
# Install deps
# --------------------
RUN npm ci \
 && composer install --no-dev --optimize-autoloader --no-interaction --no-cache

# --------------------
# BUILD FRONTEND (Node 20 → OK)
# --------------------
RUN npm run build

EXPOSE 10000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
