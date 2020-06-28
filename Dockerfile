FROM php:5.6-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add libressl-dev
