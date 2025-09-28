-- 재실행 안전 예시
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login_at DATETIME NULL, ALGORITHM=INPLACE, LOCK=NONE;
CREATE INDEX IF NOT EXISTS idx_users_last_login ON users(last_login_at);
