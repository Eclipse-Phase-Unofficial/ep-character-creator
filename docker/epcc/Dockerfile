FROM php:7.2-apache

RUN docker-php-ext-install pdo_mysql

COPY php.ini /usr/local/etc/php/
COPY epcc.ini /etc/epcc/config.ini
