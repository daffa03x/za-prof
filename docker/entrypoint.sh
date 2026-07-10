#!/bin/sh
set -e

cd /var/www/html

PORT="${PORT:-80}"

echo "[entrypoint] Starting with PORT=${PORT}"

# Generate nginx.conf at runtime with the correct PORT
cat > /etc/nginx/nginx.conf << NGINXEOF
worker_processes auto;
pid /tmp/nginx.pid;
error_log /dev/stderr warn;

events {
    worker_connections 1024;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '\$remote_addr - \$remote_user [\$time_local] "\$request" '
                    '\$status \$body_bytes_sent "\$http_referer" '
                    '"\$http_user_agent"';

    access_log /proc/1/fd/1 main;
    error_log  /proc/1/fd/2 warn;

    sendfile           on;
    tcp_nopush         on;
    tcp_nodelay        on;
    keepalive_timeout  65;
    types_hash_max_size 2048;
    client_max_body_size 20M;

    server {
        listen ${PORT} default_server;
        server_name _;
        root /var/www/html/public;
        index index.php index.html;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";
        add_header X-XSS-Protection "1; mode=block";

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

echo "[entrypoint] Verifying nginx config..."
/usr/sbin/nginx -t 2>&1
echo "[entrypoint] nginx config OK"

# Laravel setup
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "[entrypoint] Created .env from .env.example"
    fi
fi

if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force
    echo "[entrypoint] APP_KEY generated"
fi

php artisan package:discover --ansi 2>&1 || true
php artisan config:clear 2>&1 || true
php artisan config:cache 2>&1 || echo "[entrypoint] warning: config:cache skipped"
php artisan route:cache 2>&1  || echo "[entrypoint] warning: route:cache skipped"
php artisan view:cache 2>&1   || echo "[entrypoint] warning: view:cache skipped"

if [ "${RUN_MIGRATIONS}" = "true" ]; then
    php artisan migrate --force 2>&1
fi

php artisan storage:link 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache

# Start PHP-FPM in background (daemonize)
echo "[entrypoint] Starting PHP-FPM..."
/usr/local/sbin/php-fpm --daemonize
echo "[entrypoint] PHP-FPM started"

# Start nginx in foreground as PID 1
echo "[entrypoint] Starting nginx on port ${PORT}..."
exec /usr/sbin/nginx -g "daemon off;"