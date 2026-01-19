FROM php:8.2-fpm-alpine

# 1. تثبيت الإضافات اللازمة للتعامل مع MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. تثبيت أداة Composer داخل السيرفر
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. تحديد مجلد العمل داخل السيرفر
WORKDIR /var/www

# 4. نسخ كل ملفات المشروع إلى السيرفر
COPY . .

# 5. تثبيت مكتبات Laravel البرمجية
RUN composer install --no-dev --optimize-autoloader

# 6. إعطاء صلاحيات الكتابة لمجلدات Laravel الضرورية
RUN chmod -R 777 storage bootstrap/cache

CMD php artisan migrate:fresh --force --no-interaction && php -S 0.0.0.0:$PORT index.php
