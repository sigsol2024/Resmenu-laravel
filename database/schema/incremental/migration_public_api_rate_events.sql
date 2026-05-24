-- DB-backed rate limiting for public JSON APIs (submit-order, cancel-order, etc.)
-- Run once on deploy after pulling code.

CREATE TABLE IF NOT EXISTS public_api_rate_events (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  action VARCHAR(64) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_action_ip_time (action, ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
