#!/bin/bash

#Build PHP Documentation
apigen generate -s src -d Documentation/api

# Check for syntax errrors in all php documents
for i in `find ./src -iname '*.php'`;
do php -l $i;
done;

#Check php assumptions
#composer require --dev rskuipers/php-assumptions
#apt-get install php-mbstring
# ./vendor/bin/phpa ../src/ > problems.txt

#Check for copy and paste
#composer require --dev sebastian/phpcpd
# ./vendor/bin/phpcpd --fuzzy src
