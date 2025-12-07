<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Permission model: " . config('permission.models.permission') . PHP_EOL;
echo "Role model: " . config('permission.models.role') . PHP_EOL;
