FROM php:8.2-fpm

# Sistem bağımlılıklarını yükleyin
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Composer'ı yükleyin
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarla
WORKDIR /var/www

# Proje dosyalarını kopyala
COPY . /var/www

# İzinleri ayarla
RUN chown -R www-data:www-data /var/www

# Artisan için yazılabilir klasörleri ayarla
RUN chmod -R 777 storage bootstrap/cache
