#!/usr/bin/env bash

# Sync static assets to S3

php artisan assets:sync-static

# php artisan assets:purge-deleted