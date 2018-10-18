#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:5.6-apache

EXPOSE 80

RUN apt update && apt install -y sqlite3
RUN a2enmod headers

COPY docker/epcc/httpd.conf /etc/apache2/conf-enabled/epcc.conf
COPY docker/epcc/php.ini /usr/local/etc/php/
COPY src /var/www/html/
COPY docker/epcc/epcc_standalone.ini /var/www/html/php/config.ini

#Database prep
RUN mkdir /db/
RUN touch /db/FullDatabase.sqlite
RUN sed --in-place 's/\\n/ /g' /var/www/html/sql/init/FullDatabase.sql
RUN sqlite3 --init /var/www/html/sql/init/FullDatabase.sql /db/FullDatabase.sqlite

#Clean out non user accessable folders
RUN rm -r /var/www/html/sql/
RUN rm -r /var/www/html/management/
