#!/bin/bash

cd /var/www/staging.ippu.org || exit
git pull origin master >> /var/www/staging.ippu.org/storage/laravel.log 2>&1
composer install --optimize-autoloader --no-dev >> /var/www/staging.ippu.org/storage/laravel.log 2>&1
php artisan migrate --force >> /var/www/staging.ippu.org/storage/logs/laravel.log 2>&1
php artisan config:cache >> /var/www/staging.ippu.org/storage/logs/laravel.log 2>&1
# compile assets with npm
sudo npm install >> /var/www/staging.ippu.org/storage/logs/laravel.log 2>&1
# build assets
sudo npm run build >> /var/www/staging.ippu.org/storage/logs/laravel.log 2>&1
# log a  deployment success message
echo "Deployment successful at $(date)" >> /var/www/staging.ippu.org/storage/logs/laravel.log
