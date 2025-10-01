-- Admin user table: create or align minimal fields without breaking legacy
CREATE TABLE IF NOT EXISTS admin_user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- In case an older/different table exists, add columns if they are missing
ALTER TABLE admin_user
  ADD COLUMN IF NOT EXISTS username VARCHAR(64) UNIQUE,
  ADD COLUMN IF NOT EXISTS password VARCHAR(255) NOT NULL,
  ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Seed account: admin / ChangeMe123!
-- (bcrypt hash example below; change later in UI)
INSERT INTO admin_user (username, password)
SELECT * FROM (SELECT 'admin' AS username, '$2y$10$Xh8b0FRM7e0fJ2b1tGv.Lu0Xr7KXgV5J7nUpS1mCBUc8pT8xWm2KO' AS password) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM admin_user WHERE username='admin');
