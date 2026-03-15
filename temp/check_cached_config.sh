#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
echo "=== Checking cached config ==="
php -r "
\$config = require 'bootstrap/cache/config.php';
echo 'Permission Role Model: ' . (\$config['permission']['models']['role'] ?? 'NOT SET') . PHP_EOL;
echo 'Permission Permission Model: ' . (\$config['permission']['models']['permission'] ?? 'NOT SET') . PHP_EOL;
"
