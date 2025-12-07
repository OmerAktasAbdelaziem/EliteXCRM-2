#!/bin/bash
cd /home/u420350257/domains/bnc-ltd.co.uk/public_html/admin
mysql -h localhost -u u420350257_admincrm -p'GlowUp2024$' u420350257_admincrm << 'EOF'
SHOW TABLES LIKE '%role%';
EOF
