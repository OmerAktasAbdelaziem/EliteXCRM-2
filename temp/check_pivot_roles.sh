#!/bin/bash
cd /home/u420350257/domains/bnc-ltd.co.uk/public_html/admin
mysql -h localhost -u u420350257_admincrm -p'GlowUp2024$' u420350257_admincrm << 'EOF'
SELECT * FROM rl_model_has_roles WHERE model_id = 443493;
SELECT * FROM rl_model_has_permissions WHERE model_id = 443493;
EOF
