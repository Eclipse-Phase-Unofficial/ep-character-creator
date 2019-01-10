#This is meant to run a full version of the creator in standalone mode.
#No external databases or volume mounts required
FROM php:7.2-apache

EXPOSE 80

HEALTHCHECK --interval=5m --timeout=3s \
    CMD curl -f http://localhost/ || exit 1

#Enable mod rewrite (required for Laravel), and setup apache for Laravel
RUN a2enmod rewrite && \
    sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf && \
    sed -i -e '/DocumentRoot/a\\n<Directory \/var\/www\/html\/>\nAllowOverride All\n<\/Directory>' /etc/apache2/sites-enabled/000-default.conf

#Install Mysql PDO extension
#RUN docker-php-ext-install pdo_mysql

#Install sqlite, unzip (required for composer), and gnupg (needed for node installer)
RUN apt update && apt install -y sqlite3 unzip gnupg

#Install Composer
RUN curl --silent https://raw.githubusercontent.com/composer/getcomposer.org/d3e09029468023aa4e9dcd165e9b6f43df0a9999/web/installer | php -- --install-dir=/usr/bin/ --filename=composer --quiet

#Install Node
RUN curl -sL https://raw.githubusercontent.com/nodesource/distributions/0a7ddca803e0f8a4908bbd8142ba863aca2e1274/deb/setup_10.x | bash - && \
    apt install -y nodejs

#The timezone must be set or else the pdf exporter, and anything else that uses date functions, will fail.
RUN echo '\ndate.timezone = "UTC"' >> /usr/local/etc/php/php.ini

#########
#Everything after this changes somewhat frequently
#########

#So the RUN commands are done in the proper context
USER www-data:www-data

#Actual App
COPY --chown=www-data:www-data . /var/www/html/

#SQLite Database prep
ENV DB_DATABASE /var/www/html/database/database.sqlite
RUN rm $DB_DATABASE; touch $DB_DATABASE && \
    sed --in-place 's/\\n/ /g' /var/www/html/database/database.sql && \
    sqlite3 --init /var/www/html/database/database.sql $DB_DATABASE

#Use Composer to install PHP vendor packages, and compile the application for deployment
RUN export COMPOSER_HOME=/tmp && composer install --no-interaction --no-dev --optimize-autoloader

#Use npm to install Javascript vendor packages, and compile the application for deployment
RUN export HOME=/tmp && npm install && npm run prod

#Set default mode to standalone
RUN mv standalone.env .env

#Needed for apache to run
USER root
