#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
echo "=== Searching for morphTo/morphMany/belongsTo with Spatie string ==="
grep -rn "belongsTo.*['\"]Spatie" app/ --include="*.php"
grep -rn "morphTo.*['\"]Spatie" app/ --include="*.php"
grep -rn "morphMany.*['\"]Spatie" app/ --include="*.php"
echo "=== Searching for hasMany/hasOne with Spatie string ==="
grep -rn "hasMany.*['\"]Spatie" app/ --include="*.php"
grep -rn "hasOne.*['\"]Spatie" app/ --include="*.php"
