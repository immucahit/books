FROM composer:2.5.1 as composer

FROM php:8.2-fpm

RUN apt-get clean
RUN apt-get update

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get install -y libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql sockets bcmath \
&& docker-php-ext-enable pdo pdo_pgsql pgsql sockets bcmath

ARG user
ARG uid

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user
