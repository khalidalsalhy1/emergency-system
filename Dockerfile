FROM php:8.2-fpm-alpine

# تثبيت الإضافات اللازمة و Composer
RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY . .

# تثبيت مكتبات المشروع داخل السيرفر
RUN composer install --no-dev --optimize-autoloader

CMD php -S 0.0.0.0:$PORT index.php
CMD php artisan migrate --force ; php -S 0.0.0.0:$PORT index.php
