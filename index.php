<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. توجيه طلبات AdminLTE (مثل plugins و dist) الموجودة داخل assets في الجذر
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// 2. تصحيح مسارات تشغيل Laravel لتجنب خطأ Fatal Error
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
