#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Deployment finished!"
