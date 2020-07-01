FROM php:5.6-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add libressl-dev \
    && pecl install xdebug-2.5.0 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer