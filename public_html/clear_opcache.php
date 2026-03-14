<?php
// Reset opcache aggressively
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "Opcache reset: SUCCESS<br>";
} else {
    echo "Opcache reset: NOT AVAILABLE<br>";
}

if (function_exists('opcache_invalidate')) {
    // Invalidate specific files
    $files = [
        __DIR__ . '/app/Models/Role.php',
        __DIR__ . '/app/Models/Permission.php',
        __DIR__ . '/app/Models/User.php',
        __DIR__ . '/app/Traits/HasRolesWithPipeline.php',
        __DIR__ . '/config/permission.php',
        __DIR__ . '/vendor/spatie/laravel-permission/src/Models/Role.php',
        __DIR__ . '/vendor/spatie/laravel-permission/src/Models/Permission.php',
    ];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            opcache_invalidate($file, true);
            echo "Invalidated: $file<br>";
        }
    }
}

if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
    echo "APCu cleared<br>";
}

echo "<br><strong>Opcache cleared! Delete this file now for security.</strong>";
