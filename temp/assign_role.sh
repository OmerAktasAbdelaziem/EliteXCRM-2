#!/bin/bash
cd /home/u420350257/domains/bnc-ltd.co.uk/public_html/admin
mysql -h localhost -u u420350257_admincrm -p'GlowUp2024$' u420350257_admincrm << 'EOF'
INSERT INTO rl_model_has_roles (role_id, model_type, model_id, pipeline_id) 
VALUES (1, 'App\\Models\\User', 443493, 1)
ON DUPLICATE KEY UPDATE role_id = role_id;
SELECT * FROM rl_model_has_roles WHERE model_id = 443493;
EOF
