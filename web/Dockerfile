FROM composer:2.5.1 as composer

FROM php:8.2-fpm

RUN apt-get clean
RUN apt-get update

RUN apt-get install -y \
        libzip-dev \
        zip

RUN docker-php-ext-install zip \
&& docker-php-ext-enable zip

ARG user
ARG uid

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user
