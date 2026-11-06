#!/bin/sh
set -e

# Sesuaikan port Nginx jika environment PORT disediakan oleh platform
if [ -n "$PORT" ]; then
    sed -i "s/8000/$PORT/g" /etc/nginx/sites-available/default || true
    sed -i "s/8000/$PORT/g" /etc/nginx/conf.d/default.conf || true
fi

echo "==> Menyiapkan direktori storage & symlink..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

php artisan storage:link --force || true

echo "==> Mendaftarkan paket & aset Filament..."
php artisan package:discover --ansi || true
php artisan filament:upgrade || true

echo "==> Menjalankan migrasi basis data..."
php artisan migrate --force || true

echo "==> Mengoptimalkan cache konfigurasi & rute Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Menjalankan PHP-FPM..."
php-fpm -D

echo "==> Menjalankan Nginx..."
exec nginx -g "daemon off;"
