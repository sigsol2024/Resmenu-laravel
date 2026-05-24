-- Staging-only auth users for smoke tests (password: password)
-- bcrypt hash below matches Laravel's default testing hash for "password"

SET NAMES utf8mb4;

SET @mania_rid = (SELECT id FROM restaurants WHERE slug = 'mania-house' LIMIT 1);

INSERT INTO admins (username, email, password_hash)
SELECT 'staging-admin', 'staging-admin@resmenu.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username = 'staging-admin');

INSERT INTO managers (username, email, password_hash, restaurant_id)
SELECT 'staging-manager', 'staging-manager@resmenu.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', @mania_rid
FROM DUAL
WHERE @mania_rid IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM managers WHERE username = 'staging-manager');
