SET @bcrypt := '$2y$10$35ss/rgo07nI79WqlOIA7.bKQzvoO3YkE2.fkyoRqfZqp427OGxeq';

-- A) admin_user (email + bcrypt 사용)
CREATE TABLE IF NOT EXISTS admin_user(
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL DEFAULT 'Admin',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin_user(email,password_hash,name)
VALUES ('admin@herocomics.local', @bcrypt, 'Admin')
ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash), name=VALUES(name);

-- B) admin_users (환경별 컬럼명이 다를 수 있어 자동 감지)
-- 후보: password(=MD5 비교용), password_hash(=password_verify용), pwd(레거시)
SELECT COUNT(*) INTO @has_pwd
  FROM INFORMATION_SCHEMA.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE()
   AND TABLE_NAME = 'admin_users'
   AND COLUMN_NAME = 'password';

SELECT COUNT(*) INTO @has_ph
  FROM INFORMATION_SCHEMA.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE()
   AND TABLE_NAME = 'admin_users'
   AND COLUMN_NAME = 'password_hash';

SELECT COUNT(*) INTO @has_pwd_legacy
  FROM INFORMATION_SCHEMA.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE()
   AND TABLE_NAME = 'admin_users'
   AND COLUMN_NAME = 'pwd';

-- 테이블 없으면 생성(가벼운 기본형)
CREATE TABLE IF NOT EXISTS admin_users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(190) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- admin / admin@herocomics.local 둘 다 준비
-- 1) password 컬럼이 있으면: MD5('123456')로 저장 (SQL에서 직접 계산)
SET @sql := NULL;
IF @has_pwd = 1 THEN
  SET @sql := "INSERT INTO admin_users(username,password) VALUES ('admin', MD5('123456'))
               ON DUPLICATE KEY UPDATE password=MD5('123456')";
  PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
END IF;

-- 2) password_hash 컬럼이 있으면: bcrypt로 저장
IF @has_ph = 1 THEN
  SET @sql := CONCAT("INSERT INTO admin_users(username,password_hash) VALUES ('admin@herocomics.local','", @bcrypt, "')
                      ON DUPLICATE KEY UPDATE password_hash='", @bcrypt, "'");
  PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
END IF;

-- 3) 레거시 pwd 컬럼이 있으면: MD5 사용
IF @has_pwd_legacy = 1 THEN
  SET @sql := "INSERT INTO admin_users(username,pwd) VALUES ('admin', MD5('123456'))
               ON DUPLICATE KEY UPDATE pwd=MD5('123456')";
  PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
END IF;
