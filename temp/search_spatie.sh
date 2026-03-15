#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
grep -r "use Spatie" app/ --include="*.php" | grep "Models" | grep -v "App\\Models"
