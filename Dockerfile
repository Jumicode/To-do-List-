# Usa la imagen de PHP-FPM como base
FROM php:8.2-fpm-alpine

# Instala el entorno de ejecución de Nginx y las dependencias de PHP
RUN apk add --no-cache \
    nginx \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libjpeg-turbo-dev \
    jpeg-dev

# Instala otras herramientas del sistema
RUN apk add --no-cache \
    git \
    curl \
    bash \
    build-base

# Configura e instala las extensiones de PHP
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copiar el proyecto al contenedor
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader

# Copiar la configuración de Nginx
COPY docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

# Copiar el script de inicio y darle permisos de ejecución
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer el puerto
EXPOSE 80

# Usar el script como punto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Comando para iniciar Nginx y PHP-FPM
CMD ["/bin/bash", "-c", "nginx -g 'daemon off;' & php-fpm"]