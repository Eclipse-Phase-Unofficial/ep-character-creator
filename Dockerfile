FROM node:alpine as Javascript

WORKDIR /tmp

#Use npm to install Javascript vendor packages
COPY package.json package-lock.json ./
RUN npm install

COPY public ./public
COPY webpack.mix.js ./
COPY resources ./resources
#Compile the javascript/css for production
RUN npm run production

#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:fpm-alpine

EXPOSE 80
CMD ["s6-svscan", "/etc/s6"]

HEALTHCHECK --interval=1m --timeout=3s \
    CMD curl -f http://localhost/ || exit 1

RUN apk update && apk add nginx composer sqlite s6 --no-cache

COPY .docker/s6/ /etc/s6/
COPY .docker/nginx.conf /etc/nginx/

#The timezone must be set or else the pdf exporter, and anything else that uses date functions, will fail.
RUN echo '\ndate.timezone = "UTC"' >> /usr/local/etc/php/php.ini

#So the RUN commands are done in the proper context
WORKDIR /var/www/html/

#########
#Everything after this changes somewhat frequently
#########

#Use Composer to install PHP vendor packages
COPY composer.json composer.lock ./
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --no-dev --no-autoloader

#Set default mode to standalone
COPY standalone.env .env

#SQLite Database prep
ENV DB_DATABASE /var/www/html/database/database.sqlite
COPY database/database.sql ./database/
RUN touch $DB_DATABASE && \
    sed --in-place 's/\\n/ /g' database/database.sql && \
    sqlite3 --init database/database.sql $DB_DATABASE

#Actual App
COPY . ./

#Re-run composer because we now have a few more Classes (acutally build the autoloader)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --no-dev --optimize-autoloader

#Pull in the compiled javascript
COPY --from=Javascript /tmp/public ./public

#Set permissions so laravel logging and caching works
RUN chown -R www-data:www-data bootstrap/cache storage
