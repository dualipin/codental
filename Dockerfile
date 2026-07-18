# ========================================
# Stage 1 - PHP dependencies
# ========================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize


# ========================================
# Stage 2 - Frontend
# ========================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# ========================================
# Stage 3 - Production
# ========================================
FROM dunglas/frankenphp:php8.4

RUN install-php-extensions \
    pdo_mysql \
    bcmath \
    intl \
    gd \
    exif \
    zip \
    opcache

WORKDIR /app

COPY --from=vendor /app /app
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions storage/logs && \
    php artisan optimize && \
    php artisan migrate --force && \
    frankenphp php-server -r public/