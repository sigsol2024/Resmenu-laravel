-- Platform reference: subscription plans (idempotent by slug)
SET NAMES utf8mb4;

INSERT INTO subscription_plans (name, slug, description, monthly_price, annual_price, yearly_discount_percent, max_categories, max_menu_items, max_qr_styles, max_templates, features, is_active, display_order)
SELECT 'Basic', 'basic', 'Perfect for small restaurants just getting started with digital menus.', 8000.00, 62400.00, 35.00, 20, 150, 5, 5, '{"priority_support":false,"custom_domain":false,"analytics_advanced":false,"food_ordering":false,"table_reservations":false}', 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM subscription_plans WHERE slug = 'basic');

INSERT INTO subscription_plans (name, slug, description, monthly_price, annual_price, yearly_discount_percent, max_categories, max_menu_items, max_qr_styles, max_templates, features, is_active, display_order)
SELECT 'Professional', 'professional', 'Ideal for growing restaurants with multiple menu categories.', 15500.00, 120900.00, 35.00, 50, 300, 7, 7, '{"priority_support":true,"custom_domain":false,"analytics_advanced":true,"food_ordering":true,"table_reservations":true}', 1, 2
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM subscription_plans WHERE slug = 'professional');

INSERT INTO subscription_plans (name, slug, description, monthly_price, annual_price, yearly_discount_percent, max_categories, max_menu_items, max_qr_styles, max_templates, features, is_active, display_order)
SELECT 'Enterprise', 'enterprise', 'Full-featured solution for large restaurants and chains.', 25700.00, 200460.00, 35.00, -1, -1, -1, -1, '{"priority_support": true, "custom_domain": true, "analytics_advanced": true, "food_ordering": true, "table_reservations": true}', 1, 3
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM subscription_plans WHERE slug = 'enterprise');
