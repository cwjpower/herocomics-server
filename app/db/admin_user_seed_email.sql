-- Seed admin_user table that uses (email, password_hash, name)
INSERT INTO admin_user (email, password_hash, name, created_at)
VALUES ('admin@local', '$2y$10$Xh8b0FRM7e0fJ2b1tGv.Lu0Xr7KXgV5J7nUpS1mCBUc8pT8xWm2KO', 'Admin', CURRENT_TIMESTAMP)
ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash), name=VALUES(name);
