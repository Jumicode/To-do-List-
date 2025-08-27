# Stage 1: Construir la aplicación
FROM php:8.2-fpm-alpine AS builder

WORKDIR /var/www/html

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    git \
    curl \
    nginx \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libjpeg-turbo-dev \
    jpeg-dev \
    bash \
    build-base

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer y las dependencias de Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Generar la clave de la aplicación y enlazar el storage
RUN php artisan key:generate
RUN php artisan storage:link

# Stage 2: Producción (imagen final)
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Copiar archivos de Nginx y la configuración
COPY --from=builder /etc/nginx /etc/nginx
COPY --from=builder /usr/lib/nginx /usr/lib/nginx
COPY --from=builder /var/www/html /var/www/html

# Exponer el puerto
EXPOSE 80

# Comando para iniciar Nginx y PHP-FPM
CMD ["/bin/bash", "-c", "nginx && php-fpm"]