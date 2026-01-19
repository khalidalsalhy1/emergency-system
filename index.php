<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// السماح بالوصول المباشر للملفات داخل مجلد assets الموجود في الجذر
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// في حال لم يكن طلباً لملف، قم بتشغيل Laravel كالمعتاد
require_once __DIR__.'/public/index.php';
