#!/bin/bash

# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
npm install
npm run build

# Generate app key if not set
php artisan key:generate --force

# Storage directory setup
mkdir -p storage/app/public
mkdir -p storage/framework/{sessions,views,cache}
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --class=OrchidAdminSeeder --force

# Publish assets
php artisan storage:link
php artisan orchid:publish --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear old cache
php artisan cache:clear
