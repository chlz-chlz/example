FROM php:8.1-fpm-alpine

RUN apk update && apk upgrade

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN addgroup -g 1000 bff && adduser -u 1000 -G bff -s /bin/sh -D bff

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /srv/bff

USER bff
