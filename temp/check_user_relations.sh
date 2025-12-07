#!/bin/bash
cd domains/elitexcrm.com/public_html/new_crm
echo "=== Checking relationships in User model ==="
grep -A5 "function.*role" app/Models/User.php | head -30
