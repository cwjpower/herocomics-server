-- Seed admin_users (plural) if schema is (username,password[,created_at])
-- Try to add columns only if they don't exist (MariaDB 10.3+)
ALTER TABLE admin_users
  ADD COLUMN IF NOT EXISTS username VARCHAR(64) UNIQUE,
  ADD COLUMN IF NOT EXISTS password VARCHAR(255),
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

-- Insert admin row if missing
INSERT INTO admin_users (username, password, created_at)
SELECT * FROM (SELECT 'admin' AS username, '$2y$10$Xh8b0FRM7e0fJ2b1tGv.Lu0Xr7KXgV5J7nUpS1mCBUc8pT8xWm2KO' AS password, NOW() AS created_at) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM admin_users WHERE username='admin');
