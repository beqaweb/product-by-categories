#!/usr/bin/env bash

cd /var/www/html

chgrp -Rf www-data ./public ./storage
chmod -R g+s ./public ./storage
chmod -R 775 ./storage

exec php-fpm