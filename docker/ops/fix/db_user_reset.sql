-- 기존 hero 계정 싹 정리
DROP USER IF EXISTS 'hero'@'localhost';
DROP USER IF EXISTS 'hero'@'127.0.0.1';
DROP USER IF EXISTS 'hero'@'172.18.0.1';
DROP USER IF EXISTS 'hero'@'172.18.%';
DROP USER IF EXISTS 'hero'@'%';

-- 단 하나의 계정만 남김(어디서 오든 동일 비번)
CREATE USER 'hero'@'%' IDENTIFIED BY 'heropass';
GRANT ALL PRIVILEGES ON `herocomics`.* TO 'hero'@'%';
FLUSH PRIVILEGES;
