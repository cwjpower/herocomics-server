/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: herocomics
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comic_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_chapters_comic_number` (`comic_id`,`number`),
  KEY `idx_chapters_published_at` (`published_at`),
  CONSTRAINT `fk_chapters_comic` FOREIGN KEY (`comic_id`) REFERENCES `comics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapters`
--

LOCK TABLES `chapters` WRITE;
/*!40000 ALTER TABLE `chapters` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `chapters` VALUES
(1,1,1,'1화',NULL,'2025-09-28 03:13:31');
/*!40000 ALTER TABLE `chapters` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `comics`
--

DROP TABLE IF EXISTS `comics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `synopsis` text DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_comics_slug` (`slug`),
  KEY `idx_comics_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comics`
--

LOCK TABLES `comics` WRITE;
/*!40000 ALTER TABLE `comics` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `comics` VALUES
(1,'히어로의 서막','hero-begins','첫 연재물','Alice',1,'2025-09-28 03:13:31','2025-09-28 03:34:13');
/*!40000 ALTER TABLE `comics` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `hc_health`
--

DROP TABLE IF EXISTS `hc_health`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `hc_health` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hc_health`
--

LOCK TABLES `hc_health` WRITE;
/*!40000 ALTER TABLE `hc_health` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `hc_health` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `page_no` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pages_chapter_page` (`chapter_id`,`page_no`),
  KEY `idx_pages_chapter` (`chapter_id`),
  CONSTRAINT `fk_pages_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pages` VALUES
(1,1,1,'/images/hero/1/1.jpg','2025-09-28 03:13:31');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `schema_migrations`
--

DROP TABLE IF EXISTS `schema_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `schema_migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `checksum` char(40) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `schema_migrations` VALUES
(1,'001_core.sql','2a39e6941bf780200415465ce957a35661457981','2025-09-28 03:07:15'),
(2,'002_seed.sql','41c813ea8209f64a79d0c94c0a563d967e63aeb3','2025-09-28 03:11:30'),
(3,'003_indexes.sql','9d99b9c86aceadb3f548544e75ddf7c4e1bbe1fc','2025-09-28 03:07:15'),
(5,'004_patch_users.sql','cb491d40123969fea152775ef5366a2fa8b7c4fc','2025-09-28 03:13:31'),
(6,'005_seed_fix.sql','b2cde841fdf2411cc25e74abd88dea39919c0eb3','2025-09-28 03:13:31');
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_favorites` (
  `user_id` int(11) NOT NULL,
  `comic_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`comic_id`),
  KEY `idx_fav_comic` (`comic_id`),
  CONSTRAINT `fk_fav_comic` FOREIGN KEY (`comic_id`) REFERENCES `comics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_favorites`
--

LOCK TABLES `user_favorites` WRITE;
/*!40000 ALTER TABLE `user_favorites` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `user_favorites` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_last_login` (`last_login_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'test@hero.co','$2y$10$.6nGqF4l.q0H2TrRPGZlUeC7yAtKZPqE4MZ4k1Q6zZv6p2m0n0mJe',NULL,1,'2025-09-28 02:07:13','2025-09-28 03:13:31',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping routines for database 'herocomics'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-09-28  5:47:42
