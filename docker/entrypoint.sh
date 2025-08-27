#!/bin/bash

# Este script se ejecuta al inicio del contenedor.

# Copia .env.example a .env si no existe
if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi

# Genera la clave de la aplicación si no existe
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating a new one..."
    php /var/www/html/artisan key:generate
fi

# Link del storage para Laravel
php /var/www/html/artisan storage:link

# Ejecuta el comando principal del contenedor
exec "$@"