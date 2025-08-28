# Use a PHP-FPM image as your base
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
# We add Caddy here instead of Nginx
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
    build-base

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy the project into the container
WORKDIR /var/www/html
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set file ownership for the web server user
RUN chown -R www-data:www-data /var/www/html

# Copy the Caddyfile to the correct location
COPY Caddyfile /etc/caddy/Caddyfile

# Expose the port
EXPOSE 80

# This command will start Caddy and PHP-FPM together
# 'caddy run' will keep Caddy running in the foreground
# 'php-fpm' is needed to run the PHP-FPM service
CMD ["/bin/bash", "-c", "caddy run --config /etc/caddy/Caddyfile & php-fpm"]