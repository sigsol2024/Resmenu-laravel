-- Platform reference: site settings singleton (idempotent)
SET NAMES utf8mb4;

INSERT INTO site_settings (id, site_name, contact_sales_email, contact_support_email, contact_partners_email, contact_form_recipient, contact_hq_title, contact_hq_address)
SELECT 1, 'Resmenu', 'sales@resmenu.net', 'support@resmenu.net', 'partners@resmenu.net', 'info@resmenu.net', 'Laagos HQ', 'Ogombo Road, Citadel view Estate along Ogumbo Road Off Abraham Adesayan'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE id = 1);
