<?php
require __DIR__ . '/vendor/autoload.php';

echo "Spatie Role: " . (class_exists('Spatie\Permission\Models\Role') ? 'EXISTS' : 'NOT FOUND') . PHP_EOL;
echo "App Role: " . (class_exists('App\Models\Role') ? 'EXISTS' : 'NOT FOUND') . PHP_EOL;
echo "App Permission: " . (class_exists('App\Models\Permission') ? 'EXISTS' : 'NOT FOUND') . PHP_EOL;
