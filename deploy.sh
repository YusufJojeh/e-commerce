#!/bin/bash

# Generate application key if not exists
php artisan key:generate --force

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Publish Orchid assets
php artisan orchid:publish --force

# Run migrations
php artisan migrate --force

# Run database seeds for admin accounts
php artisan db:seed --class=OrchidAdminSeeder --force
echo "Admin accounts created through database seeder"
echo "Please check the seeder output for credentials"

# Optimize
php artisan optimize:clear
php artisan optimize

# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/vendor/orchid
