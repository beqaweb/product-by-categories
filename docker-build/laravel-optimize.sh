#!/usr/bin/env bash

cd /var/www/html

composer install --optimize-autoloader --no-dev --no-interaction
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan migrate --seed --no-interaction --force
php artisan l5-swagger:generate --quiet --no-interaction