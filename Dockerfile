# =========================================================
# STEP 1: Build Vue / Vite frontend
# =========================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# =========================================================
# STEP 2: Laravel PHP application
# =========================================================
FROM php:8.2-cli

WORKDIR /var/www/html

# ---------------------------------------------------------
# Install required PHP extensions
# ---------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------
# Install Composer
# ---------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ---------------------------------------------------------
# Copy Laravel composer files
# ---------------------------------------------------------
COPY composer.json composer.lock* ./


# ---------------------------------------------------------
# Install Laravel dependencies
# ---------------------------------------------------------
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# ---------------------------------------------------------
# Copy complete Laravel project
# ---------------------------------------------------------
COPY . .


# ---------------------------------------------------------
# Copy built Vue/Vite files
# ---------------------------------------------------------
COPY --from=frontend /app/public/build ./public/build


# ---------------------------------------------------------
# Laravel permissions
# ---------------------------------------------------------
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache


# ---------------------------------------------------------
# Laravel production environment
# ---------------------------------------------------------
ENV APP_ENV=production
ENV APP_DEBUG=false


# ---------------------------------------------------------
# Render port
# ---------------------------------------------------------
EXPOSE 10000


# ---------------------------------------------------------
# Start Laravel
# ---------------------------------------------------------
CMD php artisan serve \
    --host=0.0.0.0 \
    --port=${PORT:-10000}
