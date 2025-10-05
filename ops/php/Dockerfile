FROM php:8.2-fpm-alpine
RUN apk add --no-cache icu-dev libjpeg-turbo-dev libpng-dev freetype-dev libzip-dev oniguruma-dev $PHPIZE_DEPS  && docker-php-ext-configure gd --with-freetype --with-jpeg  && docker-php-ext-install -j$(nproc) gd mbstring exif zip intl mysqli pdo pdo_mysql opcache
COPY php.ini /usr/local/etc/php/conf.d/herocomics.ini
WORKDIR /var/www/html
