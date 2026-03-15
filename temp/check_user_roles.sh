#!/bin/bash
cd /home/u420350257/domains/bnc-ltd.co.uk/public_html/admin
mysql -h localhost -u u420350257_admincrm -p'GlowUp2024$' u420350257_admincrm << 'EOF'
SELECT id, first_name, last_name, email, role_id, pipeline_id FROM users LIMIT 5;
SELECT * FROM roles;
SELECT * FROM rl_roles;
EOF
