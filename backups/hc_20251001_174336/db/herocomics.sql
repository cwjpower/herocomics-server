/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: herocomics
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_user`
--

DROP TABLE IF EXISTS `admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user`
--

LOCK TABLES `admin_user` WRITE;
/*!40000 ALTER TABLE `admin_user` DISABLE KEYS */;
INSERT INTO `admin_user` VALUES
(1,'admin@example.com','$2y$10$E.m5LfEr.bc.x7Zr4xKl4.y70CEuh8Lc.JgqdTQ3E0rpBSxRiJAme','Admin','2025-09-30 04:16:25'),
(3,'admin@herocomics.local','$2y$10$35ss/rgo07nI79WqlOIA7.bKQzvoO3YkE2.fkyoRqfZqp427OGxeq','Admin','2025-09-30 23:15:05'),
(4,'admin','$2y$10$ar9I3cpfiTSadWuEebbeaeMBGNMgwEMjMsg.7yU2WE5P5zEYbS5LC','admin','2025-09-30 23:15:05'),
(5,'owner@herocomics.local','$2y$10$BFEzCeLLDknXT7jOcgTgdOeE9NFSJEqtB3PFxRbfj8MZHmrl9EknS','owner@herocomics.local','2025-09-30 23:15:05'),
(8,'your@email.com','$2y$10$PASTE_HASH_HERE','Admin','2025-10-01 00:43:57');
/*!40000 ALTER TABLE `admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
(1,'admin','$2y$10$48X/VRuvAsfnScbHewkw/O8JD/QLcs.dko8egLatpnWJ58jA9f9km','2025-10-01 07:55:57'),
(4,'owner@herocomics.local','$2y$10$qlxMVPtC3EmcYcWx98Qpp.7VC5VPPreo15s6YvLr65PPmBNJ5WYi2','2025-09-30 06:37:44');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `link_url` varchar(500) DEFAULT '',
  `image_path` varchar(300) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES
(1,'?ㅽ뙆?대뜑 留?,'','/web/admin/uploads/banners/banner_20250930_080004_d2319f.png',1,1,'2025-09-30 08:00:04');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comic`
--

DROP TABLE IF EXISTS `comic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `author` varchar(120) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `cover_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comic`
--

LOCK TABLES `comic` WRITE;
/*!40000 ALTER TABLE `comic` DISABLE KEYS */;
INSERT INTO `comic` VALUES
(1,'?ㅽ뙆?대뜑留?,'議곗슦吏?,0,'uploads/covers/979dd95200f28267.png','2025-09-30 04:27:22','2025-09-30 04:27:22'),
(2,'?꾩씠?몃㎤','議곗슦吏?,1,'/web/admin/uploads/covers/cover_20250930_081726_138198.png','2025-09-30 08:17:26','2025-09-30 23:10:54'),
(3,'?덊띁留?,'議곗슦吏?,1,'/web/admin/uploads/covers/cover_20250930_081942_b244a0.png','2025-09-30 08:19:42','2025-09-30 23:10:54'),
(4,'?곗쭊留?,'議곗슦吏?,1,'/web/admin/uploads/covers/cover_20250930_083200_bc78c3.jpg','2025-09-30 08:32:00','2025-09-30 23:10:54');
/*!40000 ALTER TABLE `comic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `episode`
--

DROP TABLE IF EXISTS `episode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `episode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comic_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `no` int(11) NOT NULL DEFAULT 1,
  `is_published` tinyint(4) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_episode_comic` (`comic_id`),
  CONSTRAINT `fk_episode_comic` FOREIGN KEY (`comic_id`) REFERENCES `comic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episode`
--

LOCK TABLES `episode` WRITE;
/*!40000 ALTER TABLE `episode` DISABLE KEYS */;
INSERT INTO `episode` VALUES
(1,2,'?꾩씠?⑤㎤',1,1,'2025-09-30 17:48:00','2025-09-30 23:10:54','2025-09-30 23:10:54'),
(2,4,'?곗쭊留?,1,1,NULL,'2025-09-30 23:10:54','2025-09-30 23:10:54'),
(3,4,'?곗쭊留?,2,1,'2025-09-30 19:22:00','2025-09-30 23:10:54','2025-09-30 23:10:54');
/*!40000 ALTER TABLE `episode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `episodes`
--

DROP TABLE IF EXISTS `episodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `series_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `ep_no` int(11) DEFAULT 1,
  `content_dir` varchar(300) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ep_series` (`series_id`),
  KEY `idx_ep_no` (`ep_no`),
  CONSTRAINT `fk_ep_series` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodes`
--

LOCK TABLES `episodes` WRITE;
/*!40000 ALTER TABLE `episodes` DISABLE KEYS */;
INSERT INTO `episodes` VALUES
(1,1,'?꾩씠?⑤㎤',1,'',1,'2025-09-30 17:48:00','2025-09-30 08:50:03'),
(2,3,'?곗쭊留?,1,'/web/content/series/S3/E2',1,NULL,'2025-09-30 09:19:39'),
(3,3,'?곗쭊留?,2,'/web/content/series/S3/E3',1,'2025-09-30 19:22:00','2025-09-30 10:19:17'),
(4,1,'?꾩씠?몃㎤',2,'/web/content/series/S1/E4',1,'2025-10-01 08:47:00','2025-09-30 23:46:27'),
(5,4,'?ㅽ듃留??쒖옉',1,'/web/content/series/S4/E5',1,'2025-10-01 12:18:00','2025-10-01 03:17:59');
/*!40000 ALTER TABLE `episodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) NOT NULL,
  `page_no` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_order` (`episode_id`,`page_no`),
  CONSTRAINT `fk_page_episode` FOREIGN KEY (`episode_id`) REFERENCES `episode` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES
(1,2,1,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 160405.png',1915,964,'2025-09-30 23:10:54'),
(2,2,2,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 161408.png',1905,932,'2025-09-30 23:10:54'),
(3,2,3,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 171222.png',1895,923,'2025-09-30 23:10:54'),
(4,2,4,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 171456.png',1526,356,'2025-09-30 23:10:54'),
(5,2,5,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-19 080732.png',1898,994,'2025-09-30 23:10:54'),
(6,2,6,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-20 062555.png',478,54,'2025-09-30 23:10:54'),
(7,2,7,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-23 220025.png',2536,1379,'2025-09-30 23:10:54'),
(8,2,8,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-24 150641.png',388,170,'2025-09-30 23:10:54'),
(9,2,9,'/web/content/series/S3/E2/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-24 175017.png',2513,1363,'2025-09-30 23:10:54'),
(10,3,1,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 160405.png',1915,964,'2025-09-30 23:10:54'),
(11,3,2,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 161408.png',1905,932,'2025-09-30 23:10:54'),
(12,3,3,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 171222.png',1895,923,'2025-09-30 23:10:54'),
(13,3,4,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-16 171456.png',1526,356,'2025-09-30 23:10:54'),
(14,3,5,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-19 080732.png',1898,994,'2025-09-30 23:10:54'),
(15,3,6,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-20 062555.png',478,54,'2025-09-30 23:10:54'),
(16,3,7,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-23 220025.png',2536,1379,'2025-09-30 23:10:54'),
(17,3,8,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-24 150641.png',388,170,'2025-09-30 23:10:54'),
(18,3,9,'/web/content/series/S3/E3/占쏙옙占쌕?옙 占쏙옙占쏙옙占?2025-08-24 175017.png',2513,1363,'2025-09-30 23:10:54');
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `author` varchar(120) DEFAULT '',
  `description` text DEFAULT NULL,
  `cover_path` varchar(300) DEFAULT '',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_series_title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series`
--

LOCK TABLES `series` WRITE;
/*!40000 ALTER TABLE `series` DISABLE KEYS */;
INSERT INTO `series` VALUES
(1,'?꾩씠?몃㎤','議곗슦吏?,'?꾩씠?몃㎤ 醫뗭븘??,'/web/admin/uploads/covers/cover_20250930_081726_138198.png',1,'2025-09-30 08:17:26'),
(2,'?덊띁留?,'議곗슦吏?,'?덈뀞?섏꽭??,'/web/admin/uploads/covers/cover_20250930_081942_b244a0.png',1,'2025-09-30 08:19:42'),
(3,'?곗쭊留?,'議곗슦吏?,'硫뗭쭊 ?꾩씠!!','/web/admin/uploads/covers/cover_20250930_083200_bc78c3.jpg',1,'2025-09-30 08:32:00'),
(4,'?ㅽ듃留?,'議곗슦吏?,'媛쒕??멸컙','/web/admin/uploads/covers/cover_20251001_031723_257675.jpg',1,'2025-10-01 03:17:02');
/*!40000 ALTER TABLE `series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series_comic_map`
--

DROP TABLE IF EXISTS `series_comic_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `series_comic_map` (
  `series_id` int(11) NOT NULL,
  `comic_id` int(11) NOT NULL,
  PRIMARY KEY (`series_id`),
  UNIQUE KEY `uq_sc` (`comic_id`),
  CONSTRAINT `fk_sc_comic` FOREIGN KEY (`comic_id`) REFERENCES `comic` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sc_series` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series_comic_map`
--

LOCK TABLES `series_comic_map` WRITE;
/*!40000 ALTER TABLE `series_comic_map` DISABLE KEYS */;
INSERT INTO `series_comic_map` VALUES
(1,2),
(2,3),
(3,4);
/*!40000 ALTER TABLE `series_comic_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'herocomics'
--

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
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-01  8:43:36
