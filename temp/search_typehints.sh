#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
echo "=== Type hints in Controllers ==="
grep -r "Spatie\\\\Permission\\\\Models" app/Http/Controllers/ --include="*.php" || echo "None found in Controllers"
echo ""
echo "=== Type hints in Services ==="
grep -r "Spatie\\\\Permission\\\\Models" app/Http/Services/ --include="*.php" || echo "None found in Services"
echo ""
echo "=== Type hints in Repositories ==="
grep -r "Spatie\\\\Permission\\\\Models" app/Http/Repositories/ --include="*.php" || echo "None found in Repositories"
