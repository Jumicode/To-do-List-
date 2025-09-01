# Usa la imagen base de PHP-FPM.
FROM php:8.2-fpm-alpine

# Instala Caddy y las dependencias de PHP.
RUN apk add --no-cache \
    caddy \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libjpeg-turbo-dev \
    jpeg-dev \
    git \
    curl \
    bash \
    build-base \
    # Instala la librería cliente de PostgreSQL
    libpq-dev

# Configura e instala las extensiones de PHP.

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Copia el proyecto al contenedor.
WORKDIR /var/www/html
COPY . .

# Copia el binario de Composer y las dependencias de Laravel.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader

# Asigna los permisos correctos.
RUN chown -R www-data:www-data /var/www/html

# Copia el Caddyfile al contenedor.
COPY Caddyfile /etc/caddy/Caddyfile

# Expone el puerto 80, aunque Caddy usará la variable de entorno PORT.
EXPOSE 80

# Comando para iniciar Caddy y PHP-FPM.
CMD ["/bin/bash", "-c", "caddy run --config /etc/caddy/Caddyfile & php-fpm"]