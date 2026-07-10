#!/bin/sh

cd /var/www/html

PORT="${PORT:-80}"

echo "[entrypoint] === Starting container with PORT=${PORT} ==="

# Generate /etc/nginx/nginx.conf at runtime using PORT env var
# NOTE: $ signs for nginx vars must be escaped with \
cat > /etc/nginx/nginx.conf << NGINXEOF
user root;
worker_processes 1;
pid /tmp/nginx.pid;
error_log /dev/stderr warn;

events {
    worker_connections 512;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log off;

    sendfile        on;
    keepalive_timeout 65;
    client_max_body_size 20M;

    server {
        listen ${PORT} default_server;
        server_name _;
        root /var/www/html/public;
        index index.php index.html;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        charset utf-8;

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_read_timeout 120;
            include        fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
NGINXEOF

echo "[entrypoint] nginx.conf written for port ${PORT}"

# Validate nginx config - if this fails, print the config and exit
if ! /usr/sbin/nginx -t 2>&1; then
    echo "[entrypoint] ERROR: nginx config test failed. Config dump:"
    cat /etc/nginx/nginx.conf
    exit 1
fi
echo "[entrypoint] nginx config validated OK"

# ---------- Laravel bootstrap ----------
if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
    echo "[entrypoint] Copied .env.example to .env"
fi

if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force && echo "[entrypoint] APP_KEY generated"
fi

php artisan package:discover --ansi 2>&1 || true

php artisan config:clear  2>&1 || true
php artisan config:cache  2>&1 || echo "[entrypoint] config:cache skipped"
php artisan route:cache   2>&1 || echo "[entrypoint] route:cache skipped"
php artisan view:cache    2>&1 || echo "[entrypoint] view:cache skipped"

if [ "${RUN_MIGRATIONS}" = "true" ]; then
    php artisan migrate --force 2>&1 || echo "[entrypoint] migrate failed"
fi

php artisan storage:link 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# ---------- Start PHP-FPM ----------
echo "[entrypoint] Starting PHP-FPM in background..."
/usr/local/sbin/php-fpm -D 2>&1
echo "[entrypoint] PHP-FPM daemonized"

# ---------- Start nginx in foreground (becomes PID 1) ----------
echo "[entrypoint] Starting nginx on port ${PORT}..."
exec /usr/sbin/nginx -g "daemon off;"