#!/usr/bin/env bash

cd /var/www/html

cp .env.example .env
chgrp -Rf www-data ./public ./storage
chmod -R g+s ./public ./storage
chmod -R 775 ./storage

crontab -l > cronjobs
echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" >> cronjobs
crontab cronjobs
rm cronjobs
service cron restart

exec php-fpm