#!/usr/bin/env bash

cd /var/www/html

php artisan passport:install --quiet --no-interaction
php artisan passport:keys --quiet --no-interaction
php artisan storage:link --quiet --no-interaction

crontab -l > cronjobs
echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" >> cronjobs
crontab cronjobs
rm cronjobs