FROM php:8.2-fpm-alpine

RUN apk upgrade --update && apk add --no-cache \
    libxml2-dev \
    libintl \
    icu-dev \
    mariadb-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    linux-headers \ 
    $PHPIZE_DEPS \
 && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" intl pdo_mysql xml soap gd \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apk del $PHPIZE_DEPS \
 && rm -rf /tmp/* /var/cache/apk/*
