############################
# 1️⃣ Frontend build stage
############################
FROM node:20-alpine AS frontend

WORKDIR /app

# Передаємо Vite env
ARG APP_NAME
ENV VITE_APP_NAME="${APP_NAME}"

# Копіюємо тільки frontend-залежності
COPY package.json package-lock.json ./

RUN npm ci

# Копіюємо frontend код
COPY resources ./resources
COPY vite.config.* .
COPY tailwind.config.* .
COPY postcss.config.* .
COPY public ./public

# Будуємо assets
RUN npm run build


############################
# 2️⃣ PHP runtime stage
############################
FROM php:8.4-cli-bookworm

WORKDIR /var/www/html

# --------------------
# System dependencies
# --------------------
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libpng16-16 libjpeg62-turbo libfreetype6 \
    libzip4 libicu72 \
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
# App source (backend)
# --------------------
COPY . .

# Копіюємо зібрані frontend assets
COPY --from=frontend /app/public/build ./public/build

RUN git config --global --add safe.directory /var/www/html \
 && composer install --no-dev --optimize-autoloader --no-interaction --no-cache \
 && php artisan optimize

# --------------------
# Runtime
# --------------------
EXPOSE 10000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
