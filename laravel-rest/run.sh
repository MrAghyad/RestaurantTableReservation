#!/bin/sh
composer update

php artisan cache:clear

chgrp -R www-data storage
chmod -R ug+w storage

cp .env.example .env
php artisan key:generate
echo yes | php artisan jwt:secret
php artisan migrate
php artisan db:seed --class=UserSeeder

exec apache2-foreground
