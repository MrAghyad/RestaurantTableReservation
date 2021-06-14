#!/bin/sh
php artisan cache:clear

composer update

chmod g+w storage/logs

cp .env.example .env
php artisan key:generate
echo yes | php artisan jwt:secret
php artisan migrate
php artisan db:seed --class=UserSeeder

exec apache2-foreground
