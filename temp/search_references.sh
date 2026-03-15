#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
echo "=== Searching in Providers ==="
find app/Providers/ -name "*.php" -exec grep -l "Permission" {} \;
echo ""
echo "=== Searching in Middleware ==="
find app/Http/Middleware/ -name "*.php" -exec grep -l "Permission\|Role" {} \;
echo ""
echo "=== Checking bootstrap/cache ==="
ls -la bootstrap/cache/
