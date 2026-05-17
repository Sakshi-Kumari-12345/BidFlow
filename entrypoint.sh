#!/bin/bash

# Clear configuration cache
php artisan config:clear

# Force run database migrations
php artisan migrate --force

# Start Apache in foreground
apache2-foreground
