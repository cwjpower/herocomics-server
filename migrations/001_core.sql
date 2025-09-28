-- 트래킹 테이블
CREATE TABLE IF NOT EXISTS schema_migrations (
  id INT NOT NULL AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL UNIQUE,
  checksum CHAR(40) NOT NULL,
  applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 헬스체크(이미 있으면 그대로)
CREATE TABLE IF NOT EXISTS hc_health (
  id INT NOT NULL AUTO_INCREMENT,
  checked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  note VARCHAR(255) NOT NULL DEFAULT 'ok',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 사용자
CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(100) NULL,
  status TINYINT NOT NULL DEFAULT 1,            -- 1:active, 0:disabled
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email),
  KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 작품(코믹)
CREATE TABLE IF NOT EXISTS comics (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(200) NOT NULL,
  synopsis TEXT NULL,
  author VARCHAR(100) NULL,
  status TINYINT NOT NULL DEFAULT 1,            -- 1:public, 0:hidden
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_comics_slug (slug),
  KEY idx_comics_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 챕터
CREATE TABLE IF NOT EXISTS chapters (
  id INT NOT NULL AUTO_INCREMENT,
  comic_id INT NOT NULL,
  number INT NOT NULL,
  title VARCHAR(200) NULL,
  published_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_chapters_comic_number (comic_id, number),
  KEY idx_chapters_published_at (published_at),
  CONSTRAINT fk_chapters_comic FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 페이지
CREATE TABLE IF NOT EXISTS pages (
  id INT NOT NULL AUTO_INCREMENT,
  chapter_id INT NOT NULL,
  page_no INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pages_chapter_page (chapter_id, page_no),
  KEY idx_pages_chapter (chapter_id),
  CONSTRAINT fk_pages_chapter FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 즐겨찾기
CREATE TABLE IF NOT EXISTS user_favorites (
  user_id INT NOT NULL,
  comic_id INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, comic_id),
  KEY idx_fav_comic (comic_id),
  CONSTRAINT fk_fav_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_fav_comic FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
