# ==========================================
# TAHAP 1: Build Aset Frontend (Vite / Tailwind)
# ==========================================
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ==========================================
# TAHAP 2: Server Produksi PHP-FPM + Nginx
# ==========================================
FROM php:8.2-fpm-bookworm

# Install paket sistem pendukung & Nginx
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    curl \
    git \
    unzip \
    ca-certificates \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi & pasang ekstensi PHP yang dibutuhkan Laravel & Filament
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# Salin Composer resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set direktori kerja aplikasi
WORKDIR /var/www/html

# Salin file dependensi PHP terlebih dahulu agar layer cache optimal
COPY composer.json composer.lock ./

# Install dependensi PHP tanpa paket development
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Salin seluruh kode proyek ke dalam kontainer
COPY . .

# Salin aset frontend hasil compile dari Tahap 1
COPY --from=node-builder /app/public/build ./public/build

# Jalankan dump-autoload akhir
RUN composer dump-autoload --optimize --no-dev

# Konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Salin entrypoint script & atur hak akses
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Atur kepemilikan folder storage dan cache ke user web
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Port default yang diekspos (Koyeb default port: 8000)
EXPOSE 8000

# Jalankan entrypoint saat kontainer dimulai
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
