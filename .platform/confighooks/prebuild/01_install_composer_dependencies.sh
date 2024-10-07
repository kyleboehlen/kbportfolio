#!/usr/bin/env bash

# Update Composer binary.

php -d memory_limit=-1 /usr/bin/composer.phar install --no-dev --no-interaction --prefer-dist --optimize-autoloader