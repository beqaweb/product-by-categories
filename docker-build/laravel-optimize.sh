#!/usr/bin/env bash

cd /var/www/html

VENDOR_DIR_EXISTS=true
if [[ ! -d vendor ]]; then
    VENDOR_DIR_EXISTS=false
fi

composer install --optimize-autoloader --no-dev --no-interaction

if [[ "$VENDOR_DIR_EXISTS" = false ]]; then
    php artisan key:generate --no-interaction
fi

php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan migrate --seed --no-interaction --force

if [[ "$VENDOR_DIR_EXISTS" = false ]]; then
    php artisan passport:install --no-interaction
    php artisan passport:keys --no-interaction
    php artisan storage:link --no-interaction
fi

php artisan l5-swagger:generate --no-interaction