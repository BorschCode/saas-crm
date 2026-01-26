FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# --------------------
# System deps + Node 20 + Chromium
# --------------------
RUN apt-get update && apt-get install -y \
    curl ca-certificates gnupg \
    git unzip \
    libpng16-16 libjpeg62-turbo libfreetype6 \
    libzip4 libicu72 \
    chromium chromium-driver \
    fonts-liberation \
    libasound2 libatk-bridge2.0-0 libatk1.0-0 \
    libcups2 libdbus-1-3 libdrm2 libgbm1 \
    libnspr4 libnss3 libx11-xcb1 libxcomposite1 \
    libxdamage1 libxrandr2 xdg-utils \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# --------------------
# PHP extensions
# --------------------
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions \
    mongodb redis intl mbstring zip gd exif opcache

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
# Build frontend
# --------------------
RUN npm run build

# --------------------
# Runtime
# --------------------
EXPOSE 10000

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
