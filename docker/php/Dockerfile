FROM php:8.2-fpm-alpine

# 필수 확장
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html

# (선택) 운영 시 OPcache 활성화
# RUN docker-php-ext-install opcache
