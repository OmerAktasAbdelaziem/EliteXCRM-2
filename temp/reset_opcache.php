<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache reset successfully\n";
} else {
    echo "OPcache not available\n";
}

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    echo "APC cache cleared\n";
}

echo "Done\n";
