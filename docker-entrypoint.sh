#!/bin/sh
set -e

# Buat file database SQLite jika belum ada
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 775 /var/www/html/database/database.sqlite

# Jalankan migrasi & seeder saat container startup
php artisan migrate:fresh --seed --force
php artisan storage:link --force
php artisan config:clear
php artisan view:clear

# Jalankan server Apache
exec apache2-foreground
