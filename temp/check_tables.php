<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Database: " . DB::connection()->getDatabaseName() . PHP_EOL;
echo "\nRole-related tables:\n";
$tables = DB::select("SHOW TABLES LIKE '%role%'");
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "  - $tableName\n";
}
