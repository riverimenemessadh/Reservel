#!/bin/bash

# Wait for database to be ready
echo "Waiting for database..."
sleep 5

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Start Apache
echo "Starting Apache..."
apache2-foreground