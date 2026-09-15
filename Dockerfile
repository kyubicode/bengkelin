FROM php:8.3-apache

# Install dependencies, PHP extensions, serta Node.js & NPM
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    sqlite3 \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip intl opcache

# Konfigurasi VirtualHost Apache
RUN a2enmod rewrite
RUN printf '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Build frontend assets & publish asset Filament
RUN npm install && npm run build
RUN touch /var/www/html/database/database.sqlite \
    && php artisan migrate --force \
    && php artisan filament:assets \
    && php artisan storage:link \
    && php artisan view:clear \
    && php artisan config:clear

# Hak akses folder storage, cache, database, & public
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/public
