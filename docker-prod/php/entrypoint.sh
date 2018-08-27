#! /bin/bash

echo "UPDATE"
cd /var/www/html && bin/console cache:clear --env=prod

echo "STARTING"
docker-php-entrypoint
apache2-foreground -DAPP_ENV=prod
