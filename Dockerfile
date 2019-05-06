FROM php:7.3-fpm
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libmcrypt-dev \
    libzip-dev \
    git \
    cron \
    acl \
    zip \
    zlib1g-dev \
    libmagickwand-dev \
    libmagickcore-dev \
    unzip \
    libpng-dev \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
    intl \
    pcntl \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    opcache \
    && pecl install \
    mcrypt-1.0.2 \
    imagick \
    && docker-php-ext-enable \
    imagick \
    mcrypt
COPY --from=composer:1.7.3 /usr/bin/composer /usr/bin/composer
ENTRYPOINT ["./docker-build/laravel-init.sh"]