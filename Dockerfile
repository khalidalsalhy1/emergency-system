FROM php:8.2-fpm-alpine
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www
COPY . .
CMD php -S 0.0.0.0:$PORT index.php
