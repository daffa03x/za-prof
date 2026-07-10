#!/bin/sh
set -e

cd /var/www/html

sed "s/PORT_PLACEHOLDER/${PORT:-80}/" /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force
fi

php artisan package:discover --ansi

php artisan config:clear
php artisan config:cache || echo "warning: config:cache failed, continuing without cached config"
php artisan route:cache || echo "warning: route:cache failed, continuing without cached routes"
php artisan view:cache || echo "warning: view:cache failed, continuing without cached views"

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link 2>/dev/null || true

chown -R www-data:www-data storage bootstrap/cache

exec "$@"