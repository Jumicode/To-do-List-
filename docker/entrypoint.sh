#!/bin/bash

# Este script se ejecuta al inicio del contenedor.

# Copia .env.example a .env si no existe. Esto es una capa de seguridad en caso de que el .env no se inyecte correctamente.
if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi



# Vincula el directorio de storage de Laravel
php /var/www/html/artisan storage:link

# Ejecuta el comando principal del contenedor (Nginx y PHP-FPM)
exec "$@"