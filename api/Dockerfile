FROM composer:2.5.1 as composer

FROM php:8.2-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache linux-headers

RUN apk add libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql sockets bcmath \
&& docker-php-ext-enable pdo pdo_pgsql pgsql sockets bcmath
