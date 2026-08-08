# ---------- 1) Build frontend assets with Vite ----------
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY . .
RUN npm run build


# ---------- 2) Install PHP dependencies ----------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize --no-dev


# ---------- 3) Production image ----------
FROM php:8.2-apache

WORKDIR /var/www/html

# System packages + PostgreSQL PHP extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Laravel needs Apache rewrite rules
RUN a2enmod rewrite

# Make Apache serve Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Application
COPY --from=vendor /app /var/www/html

# Compiled Vite assets
COPY --from=frontend /app/public/build /var/www/html/public/build

# Permissions required by Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Render normally provides PORT. Apache is changed at startup to listen on it.
ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "sed -ri \"s/^Listen .*/Listen ${PORT}/\" /etc/apache2/ports.conf && sed -ri \"s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
