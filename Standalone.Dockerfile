#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:5.6-apache

EXPOSE 80

RUN apt update && apt install -y sqlite3

COPY docker/epcc/php.ini /usr/local/etc/php/
COPY src /var/www/html/
COPY docker/epcc/epcc_standalone.ini /var/www/html/php/config.ini

RUN mkdir /db/
RUN touch /db/FullDatabase.sqlite
RUN sed --in-place 's/\\n/ /g' /var/www/html/sql/init/FullDatabase.sql
RUN sqlite3 --init /var/www/html/sql/init/FullDatabase.sql /db/FullDatabase.sqlite
