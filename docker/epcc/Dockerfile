FROM php:5.6-apache

RUN docker-php-ext-install pdo_mysql
RUN a2enmod headers

COPY httpd.conf /etc/apache2/conf-enabled/epcc.conf
COPY php.ini /usr/local/etc/php/
COPY epcc.ini /etc/epcc/config.ini
