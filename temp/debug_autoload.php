<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Loading autoloader...\n";
require __DIR__ . '/vendor/autoload.php';

echo "Step 2: Checking if Spatie Role class exists...\n";
if (class_exists('Spatie\Permission\Models\Role', false)) {
    echo "  - Class already loaded\n";
} else {
    echo "  - Class not yet loaded, trying to load...\n";
    try {
        $test = new ReflectionClass('Spatie\Permission\Models\Role');
        echo "  - SUCCESS: Class loaded via ReflectionClass\n";
    } catch (Exception $e) {
        echo "  - ERROR: " . $e->getMessage() . "\n";
    }
}

echo "Step 3: Checking if class_exists triggers autoload...\n";
if (class_exists('Spatie\Permission\Models\Role')) {
    echo "  - SUCCESS: Spatie Role class exists\n";
} else {
    echo "  - FAILED: Spatie Role class does not exist\n";
}

echo "Step 4: Trying to load App\Models\Role...\n";
try {
    $appRole = new ReflectionClass('App\Models\Role');
    echo "  - SUCCESS: App Role class loaded\n";
} catch (Exception $e) {
    echo "  - ERROR: " . $e->getMessage() . "\n";
    echo "  - File: " . $e->getFile() . "\n";
    echo "  - Line: " . $e->getLine() . "\n";
}
