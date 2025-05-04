#!/usr/bin/env bash

echo "Checking if Composer is installed..."
if ! command -v composer &> /dev/null; then
    echo "Composer not found! Installing..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

echo "Running Composer Install..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Ensure `vendor/` exists before running artisan commands
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Error: vendor directory is missing. Composer install failed!"
    exit 1
fi

echo "Generating application key..."
php /var/www/html/artisan key:generate --show

echo "Caching configuration..."
php /var/www/html/artisan config:cache

echo "Caching routes..."
php /var/www/html/artisan route:cache

echo "Running migrations..."
php /var/www/html/artisan migrate --force

echo "Setup complete!"
