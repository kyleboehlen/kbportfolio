#!/usr/bin/env bash

sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 public/

touch storage/logs/laravel.log
php artisan config:cache
sudo chown webapp:ec2-user storage/logs/laravel.log
sudo chown -R webapp:ec2-user /var/app/current
sudo chmod -R 775 storage/
sudo chmod 777 storage/logs/laravel.log
