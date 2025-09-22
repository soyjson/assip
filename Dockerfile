# stage build: install composer deps
FROM php:7.4-fpm AS builder

# system deps
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy app files and install deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction --optimize-autoloader

# copy remaining files
COPY . .

# run artisan optimizations (optional)
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# stage final: runtime with nginx + php-fpm
FROM php:7.4-fpm

# install extensions required at runtime
RUN apt-get update && apt-get install -y libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# create www user
RUN useradd -G www-data,root -u 1000 -d /home/www www

WORKDIR /var/www/html

# copy from builder
COPY --from=builder /var/www/html /var/www/html

# set permissions
RUN chown -R www:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# install nginx
RUN apt-get update && apt-get install -y nginx

# nginx config (simple)
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# expose port
EXPOSE 80

# start supervisor to run php-fpm + nginx (simple entrypoint)
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
