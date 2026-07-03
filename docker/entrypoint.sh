#!/bin/sh
set -e

# Render passes PORT as an env var (defaults to 10000 for Docker services).
# Patch Apache to listen on that port instead of 80.
APP_PORT=${PORT:-80}
sed -i "s/Listen 80/Listen $APP_PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$APP_PORT>/" /etc/apache2/sites-available/000-default.conf

# Ensure the sqlite database file exists and is writable by the web server
mkdir -p database
touch database/database.sqlite
chown -R www-data:www-data database
chmod -R 775 database

# Run migrations — safe to run on every boot, only applies new ones
php artisan migrate --force

# Cache config/routes/views now that real env vars are injected
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
