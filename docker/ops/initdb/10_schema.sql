-- 기본 헬스체크 테이블
CREATE TABLE IF NOT EXISTS hc_health (
                                         id INT NOT NULL AUTO_INCREMENT,
                                         checked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                         note VARCHAR(255) NOT NULL DEFAULT 'ok',
    PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 필요한 스키마를 여기에 계속 추가하면 됨
-- 예) 사용자/도서 테이블(샘플)
/*
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS books (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  price INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_books_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/
-- users
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;

CREATE TABLE IF NOT EXISTS books (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  price INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_books_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;


INSERT INTO users (email, password_hash)
VALUES ('test@hero.co', '$2y$10$.6nGqF4l.q0H2TrRPGZlUeC7yAtKZPqE4MZ4k1Q6zZv6p2m0n0mJe')  -- bcrypt(dummy)
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO books (title, price) VALUES
('스파이더맨', 9900),
('아이언맨', 11000)
;

SELECT COUNT(*) AS users_cnt FROM users;
SELECT COUNT(*) AS books_cnt FROM books;
SELECT * FROM books ORDER BY id DESC LIMIT 5;
