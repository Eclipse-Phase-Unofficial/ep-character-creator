#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:7.2-apache

EXPOSE 80

RUN a2enmod rewrite
RUN apt update && apt install -y sqlite3

COPY docker/epcc/php.ini /usr/local/etc/php/
COPY docker/epcc/apache_host.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/
COPY docker/epcc/epcc_standalone.ini /var/www/html/config.ini

#Database prep
RUN mkdir /db/ && \
    touch /db/FullDatabase.sqlite && \
    sed --in-place 's/\\n/ /g' /var/www/html/database/database.sql && \
    sqlite3 --init /var/www/html/database/database.sql /db/database.sqlite
