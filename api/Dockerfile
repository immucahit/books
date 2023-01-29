FROM php:8.2-fpm-alpine

RUN apk add --no-cache linux-headers

RUN apk add libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql sockets bcmath \
&& docker-php-ext-enable pdo pdo_pgsql pgsql sockets bcmath