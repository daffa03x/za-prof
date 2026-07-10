#!/bin/sh
set -e

cd /var/www/html

# Build nginx.conf from PORT env var (Railway sets this)
PORT="${PORT:-80}"

cat > /etc/nginx/nginx.conf << NGINXEOF
worker_processes auto;
pid /tmp/nginx.pid;
error_log /dev/stderr warn;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    access_log /dev/stdout;
    sendfile on;
    keepalive_timeout 65;
    client_max_body_size 20M;

    server {
        listen ${PORT};
        server_name _;
        root /var/www/html/public;
        index index.php;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_read_timeout 120;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
NGINXEOF

echo "nginx configured to listen on port ${PORT}"

# Setup .env
if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force
fi

php artisan package:discover --ansi

php artisan config:clear
php artisan config:cache || echo "warning: config:cache failed, continuing"
php artisan route:cache || echo "warning: route:cache failed, continuing"
php artisan view:cache || echo "warning: view:cache failed, continuing"

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link 2>/dev/null || true

chown -R www-data:www-data storage bootstrap/cache

exec "$@"