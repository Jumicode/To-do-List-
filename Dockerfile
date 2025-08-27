# Usa la imagen de PHP-FPM como base
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    nginx \
    git \
    curl \
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

# Copiar el proyecto al contenedor
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Copiar la configuración de Nginx y el script de inicio
COPY docker/nginx/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer el puerto
EXPOSE 80

# Usar el script como punto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Comando para iniciar Nginx y PHP-FPM
CMD ["/bin/bash", "-c", "nginx -g 'daemon off;' & php-fpm"]