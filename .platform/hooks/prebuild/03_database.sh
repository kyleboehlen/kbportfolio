#!/usr/bin/env bash

# Cache config/routes

php artisan migrate --force

php artisan db:seed --force