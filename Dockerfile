#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:fpm-alpine

EXPOSE 80
CMD ["s6-svscan", "/etc/s6"]

HEALTHCHECK --interval=1m --timeout=3s \
    CMD curl -f http://localhost/ || exit 1

RUN apk update && apk add nginx npm composer sqlite s6 --no-cache

COPY .docker/s6/ /etc/s6/
COPY .docker/nginx.conf /etc/nginx/

#The timezone must be set or else the pdf exporter, and anything else that uses date functions, will fail.
RUN echo '\ndate.timezone = "UTC"' >> /usr/local/etc/php/php.ini

#########
#Everything after this changes somewhat frequently
#########

#So the RUN commands are done in the proper context
USER www-data:www-data
WORKDIR /var/www/html/

#Use Composer to install PHP vendor packages
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --no-autoloader

#Use npm to install Javascript vendor packages
COPY --chown=www-data:www-data package.json package-lock.json ./
RUN npm install

#Actual App
COPY --chown=www-data:www-data . ./

#SQLite Database prep
ENV DB_DATABASE /var/www/html/database/database.sqlite
RUN touch $DB_DATABASE && \
    sed --in-place 's/\\n/ /g' /var/www/html/database/database.sql && \
    sqlite3 --init /var/www/html/database/database.sql $DB_DATABASE

#Re-run composer because we now have a few more Classes, and compile the javascript/css for production
RUN composer install --no-interaction --no-dev --optimize-autoloader && npm run production

#Set default mode to standalone
RUN mv standalone.env .env

#Needed for nginx to run
USER root
