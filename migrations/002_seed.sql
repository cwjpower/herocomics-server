SET NAMES utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;

INSERT IGNORE INTO users (email, password_hash, display_name)
VALUES
  ('test@hero.co', '$2y$10$.6nGqF4l.q0H2TrRPGZlUeC7yAtKZPqE4MZ4k1Q6zZv6p2m0n0mJe', '테스터');

INSERT IGNORE INTO comics (title, slug, synopsis, author)
VALUES
  ('히어로의 서막', 'hero-begins', '첫 연재물', 'Alice');

INSERT IGNORE INTO chapters (comic_id, number, title)
SELECT c.id, 1, '1화' FROM comics c WHERE c.slug='hero-begins';

INSERT IGNORE INTO pages (chapter_id, page_no, image_path)
SELECT ch.id, 1, '/images/hero/1/1.jpg'
FROM chapters ch
JOIN comics c ON c.id=ch.comic_id AND c.slug='hero-begins'
WHERE ch.number=1;
