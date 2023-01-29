FROM composer:2.5.1 as composer

FROM php:8.2-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
