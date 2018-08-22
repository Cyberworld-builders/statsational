-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: homestead
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auction_item`
--

DROP TABLE IF EXISTS `auction_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auction_item` (
  `item_id` int(10) unsigned DEFAULT NULL,
  `auction_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `auction_item_item_id_foreign` (`item_id`),
  KEY `auction_item_auction_id_foreign` (`auction_id`),
  CONSTRAINT `auction_item_auction_id_foreign` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auction_item_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction_item`
--

LOCK TABLES `auction_item` WRITE;
/*!40000 ALTER TABLE `auction_item` DISABLE KEYS */;
INSERT INTO `auction_item` VALUES (9,5,NULL,NULL),(10,5,NULL,NULL),(11,5,NULL,NULL),(12,5,NULL,NULL),(13,5,NULL,NULL),(14,6,NULL,NULL),(15,7,NULL,NULL),(16,8,NULL,NULL),(17,8,NULL,NULL),(18,8,NULL,NULL),(19,8,NULL,NULL),(20,8,NULL,NULL),(21,8,NULL,NULL),(22,8,NULL,NULL),(23,9,NULL,NULL),(24,10,NULL,NULL),(25,10,NULL,NULL),(26,10,NULL,NULL),(27,10,NULL,NULL),(28,10,NULL,NULL),(29,10,NULL,NULL);
/*!40000 ALTER TABLE `auction_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auction_user`
--

DROP TABLE IF EXISTS `auction_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auction_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auction_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auctions_users_auction_id_foreign` (`auction_id`),
  KEY `auctions_users_user_id_foreign` (`user_id`),
  CONSTRAINT `auctions_users_auction_id_foreign` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `auctions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction_user`
--

LOCK TABLES `auction_user` WRITE;
/*!40000 ALTER TABLE `auction_user` DISABLE KEYS */;
INSERT INTO `auction_user` VALUES (1,5,4),(2,11,1);
/*!40000 ALTER TABLE `auction_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auctions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auctions_user_id_foreign` (`user_id`),
  CONSTRAINT `auctions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auctions`
--

LOCK TABLES `auctions` WRITE;
/*!40000 ALTER TABLE `auctions` DISABLE KEYS */;
INSERT INTO `auctions` VALUES (5,'2018-05-18 05:09:46','2018-05-18 13:41:39',1,'fffffff','2018-05-18 05:09:41',0,'[{\"id\":9,\"name\":\"dfdfdf\",\"created_at\":\"2018-05-18 05:24:31\",\"updated_at\":\"2018-05-18 05:24:31\",\"pivot\":{\"auction_id\":5,\"item_id\":9}},{\"id\":10,\"name\":\"ttttt\",\"created_at\":\"2018-05-18 05:27:09\",\"updated_at\":\"2018-05-18 05:27:09\",\"pivot\":{\"auction_id\":5,\"item_id\":10}},{\"id\":11,\"name\":\"333333\",\"created_at\":\"2018-05-18 05:29:31\",\"updated_at\":\"2018-05-18 05:29:31\",\"pivot\":{\"auction_id\":5,\"item_id\":11}},{\"id\":9,\"name\":\"dfdfdf\",\"created_at\":\"2018-05-18 05:24:31\",\"updated_at\":\"2018-05-18 05:24:31\",\"pivot\":{\"auction_id\":5,\"item_id\":9}},{\"id\":10,\"name\":\"ttttt\",\"created_at\":\"2018-05-18 05:27:09\",\"updated_at\":\"2018-05-18 05:27:09\",\"pivot\":{\"auction_id\":5,\"item_id\":10}},{\"id\":11,\"name\":\"333333\",\"created_at\":\"2018-05-18 05:29:31\",\"updated_at\":\"2018-05-18 05:29:31\",\"pivot\":{\"auction_id\":5,\"item_id\":11}},{\"id\":12,\"name\":\"i8iiuoioio\",\"created_at\":\"2018-05-18 05:45:31\",\"updated_at\":\"2018-05-18 05:45:31\",\"pivot\":{\"auction_id\":5,\"item_id\":12}},{\"id\":9,\"name\":\"dfdfdf\",\"created_at\":\"2018-05-18 05:24:31\",\"updated_at\":\"2018-05-18 05:24:31\",\"pivot\":{\"auction_id\":5,\"item_id\":9}},{\"id\":10,\"name\":\"ttttt\",\"created_at\":\"2018-05-18 05:27:09\",\"updated_at\":\"2018-05-18 05:27:09\",\"pivot\":{\"auction_id\":5,\"item_id\":10}},{\"id\":11,\"name\":\"333333\",\"created_at\":\"2018-05-18 05:29:31\",\"updated_at\":\"2018-05-18 05:29:31\",\"pivot\":{\"auction_id\":5,\"item_id\":11}},{\"id\":12,\"name\":\"i8iiuoioio\",\"created_at\":\"2018-05-18 05:45:31\",\"updated_at\":\"2018-05-18 05:45:31\",\"pivot\":{\"auction_id\":5,\"item_id\":12}},{\"id\":13,\"name\":\"vbvg\",\"created_at\":\"2018-05-18 13:41:39\",\"updated_at\":\"2018-05-18 13:41:39\",\"pivot\":{\"auction_id\":5,\"item_id\":13}}]'),(6,'2018-05-18 16:12:10','2018-05-18 16:12:17',1,'dfdsdsdsd','2018-05-18 04:12:05',0,'[{\"id\":14,\"name\":\"hhhhh\",\"created_at\":\"2018-05-18 16:12:17\",\"updated_at\":\"2018-05-18 16:12:17\",\"pivot\":{\"auction_id\":6,\"item_id\":14}}]'),(7,'2018-05-18 16:19:17','2018-05-18 16:19:35',1,'eeee','2018-05-18 04:19:14',0,'[{\"id\":15,\"name\":\"ttt\",\"created_at\":\"2018-05-18 16:19:35\",\"updated_at\":\"2018-05-18 16:19:35\",\"pivot\":{\"auction_id\":7,\"item_id\":15}}]'),(8,'2018-05-18 16:23:48','2018-05-18 16:28:12',1,'fffff','2018-05-18 04:23:45',0,'[]'),(9,'2018-05-18 16:30:19','2018-05-18 16:30:19',1,'jjj','2018-05-18 04:30:16',0,'[]'),(10,'2018-05-18 16:31:12','2018-06-06 21:30:48',1,'rtrtrt','2018-05-18 04:31:09',0,'[{\"id\":24,\"name\":\"kkkkkkk\",\"created_at\":\"2018-05-18 16:31:18\",\"updated_at\":\"2018-05-18 16:31:18\",\"pivot\":{\"auction_id\":10,\"item_id\":24}},{\"id\":25,\"name\":\"ghghgh\",\"created_at\":\"2018-05-18 16:31:34\",\"updated_at\":\"2018-05-18 16:31:34\",\"pivot\":{\"auction_id\":10,\"item_id\":25}},{\"id\":26,\"name\":\"eeeee\",\"created_at\":\"2018-05-18 16:31:39\",\"updated_at\":\"2018-05-18 16:31:39\",\"pivot\":{\"auction_id\":10,\"item_id\":26}},{\"id\":27,\"name\":\"f3223434\",\"created_at\":\"2018-05-18 16:31:44\",\"updated_at\":\"2018-05-18 16:31:44\",\"pivot\":{\"auction_id\":10,\"item_id\":27}},{\"id\":28,\"name\":\"yyy\",\"created_at\":\"2018-06-06 21:28:55\",\"updated_at\":\"2018-06-06 21:28:55\",\"pivot\":{\"auction_id\":10,\"item_id\":28}},{\"id\":29,\"name\":\"yuyuy\",\"created_at\":\"2018-06-06 21:30:48\",\"updated_at\":\"2018-06-06 21:30:48\",\"pivot\":{\"auction_id\":10,\"item_id\":29}}]'),(11,'2018-05-26 06:58:51','2018-05-26 06:58:51',4,'message test 2','2018-05-26 06:58:43',0,'[]');
/*!40000 ALTER TABLE `auctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bids` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(13,4) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `auction_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bids_user_id_foreign` (`user_id`),
  KEY `bids_item_id_foreign` (`item_id`),
  KEY `bids_auction_id_foreign` (`auction_id`),
  CONSTRAINT `bids_auction_id_foreign` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bids_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bids`
--

LOCK TABLES `bids` WRITE;
/*!40000 ALTER TABLE `bids` DISABLE KEYS */;
INSERT INTO `bids` VALUES (1,'2018-05-18 14:22:21','2018-05-18 14:22:21',3.0000,1,9,5),(2,'2018-05-18 15:24:43','2018-05-18 15:24:43',3.0000,1,9,5),(3,'2018-05-18 15:32:26','2018-05-18 15:32:26',3.0000,1,9,5),(4,'2018-05-18 15:32:26','2018-05-18 15:32:26',3.0000,1,9,5),(5,'2018-05-18 15:32:26','2018-05-18 15:32:26',3.0000,1,9,5),(6,'2018-05-18 15:32:26','2018-05-18 15:32:26',3.0000,1,9,5),(7,'2018-05-18 15:32:26','2018-05-18 15:32:26',3.0000,1,9,5),(8,'2018-05-18 15:32:29','2018-05-18 15:32:29',3.0000,1,9,5),(9,'2018-05-18 15:32:29','2018-05-18 15:32:29',3.0000,1,9,5),(10,'2018-05-18 15:33:58','2018-05-18 15:33:58',3.0000,1,9,5),(11,'2018-05-18 15:35:32','2018-05-18 15:35:32',3.0000,1,9,5),(12,'2018-05-18 15:36:31','2018-05-18 15:36:31',5.0000,1,9,5),(13,'2018-05-18 15:36:57','2018-05-18 15:36:57',4.0000,1,9,5),(14,'2018-05-18 15:45:44','2018-05-18 15:45:44',13.0000,1,9,5),(15,'2018-05-18 16:08:46','2018-05-18 16:08:46',14.0000,1,9,5),(16,'2018-05-18 16:08:48','2018-05-18 16:08:48',15.0000,1,9,5),(17,'2018-05-18 16:08:53','2018-05-18 16:08:53',20.0000,1,9,5),(18,'2018-05-18 16:10:54','2018-05-18 16:10:54',21.0000,1,9,5),(19,'2018-05-18 16:10:56','2018-05-18 16:10:56',22.0000,1,9,5),(20,'2018-05-18 16:10:59','2018-05-18 16:10:59',29.0000,1,9,5),(21,'2018-05-18 16:11:02','2018-05-18 16:11:02',30.0000,1,9,5),(22,'2018-05-18 16:11:02','2018-05-18 16:11:02',30.0000,1,9,5),(23,'2018-05-18 16:11:02','2018-05-18 16:11:02',30.0000,1,9,5),(24,'2018-05-18 16:11:24','2018-05-18 16:11:24',31.0000,1,9,5),(25,'2018-05-18 16:22:40','2018-05-18 16:22:40',1.0000,1,15,7),(26,'2018-05-18 16:24:52','2018-05-18 16:24:52',1.0000,1,16,8),(27,'2018-05-18 16:24:55','2018-05-18 16:24:55',2.0000,1,16,8),(28,'2018-05-18 16:24:58','2018-05-18 16:24:58',7.0000,1,16,8),(29,'2018-05-18 16:25:05','2018-05-18 16:25:05',8.0000,1,16,8),(30,'2018-05-18 16:25:55','2018-05-18 16:25:55',20.0000,1,16,8),(31,'2018-05-18 16:26:00','2018-05-18 16:26:00',21.0000,1,16,8),(32,'2018-05-18 16:31:24','2018-05-18 16:31:24',1.0000,1,24,10),(33,'2018-05-18 16:31:28','2018-05-18 16:31:28',2.0000,1,24,10),(34,'2018-05-18 16:31:45','2018-05-18 16:31:45',3.0000,1,24,10),(35,'2018-05-18 16:31:49','2018-05-18 16:31:49',9.0000,1,24,10),(36,'2018-05-18 16:31:56','2018-05-18 16:31:56',10.0000,1,24,10),(37,'2018-05-18 16:32:00','2018-05-18 16:32:00',15.0000,1,24,10),(38,'2018-05-18 16:32:02','2018-05-18 16:32:02',20.0000,1,24,10),(39,'2018-05-18 16:32:04','2018-05-18 16:32:04',21.0000,1,24,10),(40,'2018-05-18 17:30:16','2018-05-18 17:30:16',22.0000,1,24,10),(41,'2018-05-18 17:30:21','2018-05-18 17:30:21',23.0000,1,24,10),(42,'2018-05-18 17:30:24','2018-05-18 17:30:24',29.0000,1,24,10),(43,'2018-05-25 18:30:21','2018-05-25 18:30:21',1.0000,1,14,6),(44,'2018-05-25 18:30:24','2018-05-25 18:30:24',2.0000,1,14,6),(45,'2018-05-25 18:30:26','2018-05-25 18:30:26',6.0000,1,14,6),(46,'2018-05-25 18:30:31','2018-05-25 18:30:31',7.0000,1,14,6),(47,'2018-06-06 15:40:27','2018-06-06 15:40:27',32.0000,1,9,5),(48,'2018-06-06 15:43:44','2018-06-06 15:43:44',41.0000,1,9,5),(49,'2018-06-06 15:43:58','2018-06-06 15:43:58',42.0000,1,9,5),(50,'2018-06-06 16:10:43','2018-06-06 16:10:43',43.0000,1,9,5),(51,'2018-06-06 16:10:49','2018-06-06 16:10:49',44.0000,1,9,5),(52,'2018-06-06 20:01:20','2018-06-06 20:01:20',47.0000,1,9,5),(53,'2018-06-06 20:01:24','2018-06-06 20:01:24',48.0000,1,9,5),(54,'2018-06-06 20:10:20','2018-06-06 20:10:20',4.0000,1,15,7),(55,'2018-06-06 21:27:34','2018-06-06 21:27:34',30.0000,1,24,10),(56,'2018-06-10 22:19:21','2018-06-10 22:19:21',49.0000,1,9,5),(57,'2018-06-10 22:19:40','2018-06-10 22:19:40',54.0000,1,9,5),(58,'2018-06-10 22:27:41','2018-06-10 22:27:41',55.0000,1,9,5),(59,'2018-06-10 22:28:38','2018-06-10 22:28:38',56.0000,1,9,5),(60,'2018-06-10 22:29:08','2018-06-10 22:29:08',57.0000,1,9,5),(61,'2018-06-10 22:29:55','2018-06-10 22:29:55',58.0000,1,9,5),(62,'2018-06-10 22:30:19','2018-06-10 22:30:19',59.0000,1,9,5),(63,'2018-06-10 22:30:46','2018-06-10 22:30:46',60.0000,1,9,5),(64,'2018-06-10 22:31:11','2018-06-10 22:31:11',61.0000,1,9,5),(65,'2018-06-10 22:32:10','2018-06-10 22:32:10',62.0000,1,9,5),(66,'2018-06-10 22:32:46','2018-06-10 22:32:46',63.0000,1,9,5),(67,'2018-06-10 22:32:48','2018-06-10 22:32:48',63.0000,1,9,5),(68,'2018-06-10 22:33:45','2018-06-10 22:33:45',64.0000,1,9,5),(69,'2018-06-10 22:34:05','2018-06-10 22:34:05',65.0000,1,9,5),(70,'2018-06-10 22:34:40','2018-06-10 22:34:40',66.0000,1,9,5),(71,'2018-06-10 22:38:18','2018-06-10 22:38:18',67.0000,1,9,5),(72,'2018-06-10 22:38:21','2018-06-10 22:38:21',67.0000,1,9,5),(73,'2018-06-10 22:39:31','2018-06-10 22:39:31',68.0000,1,9,5),(74,'2018-06-10 22:40:11','2018-06-10 22:40:11',69.0000,1,9,5),(75,'2018-06-10 22:40:25','2018-06-10 22:40:25',69.0000,1,9,5),(76,'2018-06-10 22:40:27','2018-06-10 22:40:27',69.0000,1,9,5),(77,'2018-06-10 22:40:32','2018-06-10 22:40:32',70.0000,1,9,5),(78,'2018-06-10 22:41:38','2018-06-10 22:41:38',71.0000,1,9,5),(79,'2018-06-10 22:41:42','2018-06-10 22:41:42',72.0000,1,9,5),(80,'2018-06-10 22:42:51','2018-06-10 22:42:51',73.0000,1,9,5),(81,'2018-06-10 22:42:53','2018-06-10 22:42:53',74.0000,1,9,5),(82,'2018-06-10 22:42:55','2018-06-10 22:42:55',75.0000,1,9,5),(83,'2018-06-10 22:43:01','2018-06-10 22:43:01',80.0000,1,9,5);
/*!40000 ALTER TABLE `bids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (9,'dfdfdf','2018-05-18 05:24:31','2018-05-18 05:24:31'),(10,'ttttt','2018-05-18 05:27:09','2018-05-18 05:27:09'),(11,'333333','2018-05-18 05:29:31','2018-05-18 05:29:31'),(12,'i8iiuoioio','2018-05-18 05:45:31','2018-05-18 05:45:31'),(13,'vbvg','2018-05-18 13:41:39','2018-05-18 13:41:39'),(14,'hhhhh','2018-05-18 16:12:17','2018-05-18 16:12:17'),(15,'ttt','2018-05-18 16:19:35','2018-05-18 16:19:35'),(16,'uuuu','2018-05-18 16:23:54','2018-05-18 16:23:54'),(17,'dsddsdsd','2018-05-18 16:26:09','2018-05-18 16:26:09'),(18,'sZXZx','2018-05-18 16:26:21','2018-05-18 16:26:21'),(19,'dfsdsds','2018-05-18 16:26:30','2018-05-18 16:26:30'),(20,'jjj','2018-05-18 16:27:54','2018-05-18 16:27:54'),(21,'gggg','2018-05-18 16:28:12','2018-05-18 16:28:12'),(22,'ffff','2018-05-18 16:29:37','2018-05-18 16:29:37'),(23,'uiuiui','2018-05-18 16:30:25','2018-05-18 16:30:25'),(24,'kkkkkkk','2018-05-18 16:31:18','2018-05-18 16:31:18'),(25,'ghghgh','2018-05-18 16:31:34','2018-05-18 16:31:34'),(26,'eeeee','2018-05-18 16:31:39','2018-05-18 16:31:39'),(27,'f3223434','2018-05-18 16:31:44','2018-05-18 16:31:44'),(28,'yyy','2018-06-06 21:28:55','2018-06-06 21:28:55'),(29,'yuyuy','2018-06-06 21:30:48','2018-06-06 21:30:48');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `auction_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,'sdsds','2018-05-25 19:00:23','2018-05-25 19:00:23',6),(2,1,'dsds','2018-05-25 19:00:32','2018-05-25 19:00:32',6),(3,1,'fdfdf','2018-05-25 19:08:44','2018-05-25 19:08:44',6),(4,1,'dfdfdf','2018-05-25 19:13:57','2018-05-25 19:13:57',7),(5,1,'adfadf','2018-05-25 19:14:14','2018-05-25 19:14:14',6),(6,1,'dfdf','2018-05-25 19:15:10','2018-05-25 19:15:10',6),(7,1,'fffff','2018-05-25 19:16:24','2018-05-25 19:16:24',6),(8,1,'dsds','2018-05-25 22:56:06','2018-05-25 22:56:06',6),(9,1,'ererer','2018-05-25 22:56:37','2018-05-25 22:56:37',7),(10,1,'dfdsd','2018-05-25 22:57:53','2018-05-25 22:57:53',7),(11,1,'ddsdfd','2018-05-26 03:52:29','2018-05-26 03:52:29',5),(12,1,'ddd','2018-05-26 04:02:06','2018-05-26 04:02:06',5),(13,1,'hghhh','2018-05-26 04:03:37','2018-05-26 04:03:37',5),(14,1,'ddd','2018-05-26 04:05:51','2018-05-26 04:05:51',5),(15,1,'ggg','2018-05-26 04:06:45','2018-05-26 04:06:45',5),(16,1,'rrr','2018-05-26 04:11:30','2018-05-26 04:11:30',5),(17,1,'dd','2018-05-26 04:24:55','2018-05-26 04:24:55',5),(18,1,'sdsd','2018-05-26 04:28:21','2018-05-26 04:28:21',5),(19,1,'fdfdfd','2018-05-26 04:28:32','2018-05-26 04:28:32',5),(20,1,'DFZDFA','2018-05-26 04:30:23','2018-05-26 04:30:23',5),(21,1,'fffff','2018-05-26 04:30:25','2018-05-26 04:30:25',5),(22,1,'asdfadf','2018-05-26 04:30:28','2018-05-26 04:30:28',5),(23,1,'sedfdf','2018-05-26 04:31:21','2018-05-26 04:31:21',5),(24,1,'dfdf','2018-05-26 04:34:53','2018-05-26 04:34:53',5),(25,1,'fgsfgf','2018-05-26 05:12:44','2018-05-26 05:12:44',5),(26,1,'hghgh','2018-05-26 05:14:27','2018-05-26 05:14:27',5),(27,1,'sdsds','2018-05-26 05:15:52','2018-05-26 05:15:52',5),(28,1,'sdsds','2018-05-26 05:16:21','2018-05-26 05:16:21',5),(29,1,'asa','2018-05-26 05:16:49','2018-05-26 05:16:49',5),(30,1,'ghgh','2018-05-26 05:18:45','2018-05-26 05:18:45',5),(31,1,'dfdf','2018-05-26 05:20:50','2018-05-26 05:20:50',5),(32,1,'dsdsd','2018-05-26 05:21:24','2018-05-26 05:21:24',5),(33,1,'dfdf','2018-05-26 05:22:19','2018-05-26 05:22:19',5),(34,1,'fdddddd','2018-05-26 05:22:25','2018-05-26 05:22:25',5),(35,1,'eeeeeee','2018-05-26 05:22:46','2018-05-26 05:22:46',5),(36,1,'ffffff','2018-05-26 05:23:04','2018-05-26 05:23:04',5),(37,1,'ssss','2018-05-26 05:25:11','2018-05-26 05:25:11',5),(38,1,'dddd','2018-05-26 05:25:51','2018-05-26 05:25:51',5),(39,1,'ggggg','2018-05-26 05:27:52','2018-05-26 05:27:52',5),(40,1,'ffff','2018-05-26 05:29:35','2018-05-26 05:29:35',5),(41,1,'hhh','2018-05-26 05:30:26','2018-05-26 05:30:26',5),(42,1,'fddd','2018-05-26 05:31:58','2018-05-26 05:31:58',5),(43,1,'cccc','2018-05-26 05:32:19','2018-05-26 05:32:19',5),(44,1,'ssssss','2018-05-26 05:32:25','2018-05-26 05:32:25',5),(45,1,'sdSADSAD','2018-05-26 05:32:42','2018-05-26 05:32:42',7),(46,1,'LLLLL','2018-05-26 05:32:53','2018-05-26 05:32:53',7),(47,1,'fff','2018-05-26 05:32:56','2018-05-26 05:32:56',7),(48,1,'oooo9oo','2018-05-26 05:33:08','2018-05-26 05:33:08',5),(49,1,'jjjjjjj','2018-05-26 05:33:18','2018-05-26 05:33:18',6),(50,1,'yuyuyuyuyuyuyu','2018-05-26 05:33:29','2018-05-26 05:33:29',5),(51,1,'test1','2018-05-26 05:33:45','2018-05-26 05:33:45',5),(52,1,'test2','2018-05-26 05:33:55','2018-05-26 05:33:55',6),(53,1,'test3','2018-05-26 05:34:06','2018-05-26 05:34:06',7),(54,1,'dddddd','2018-05-26 05:35:22','2018-05-26 05:35:22',7),(55,1,'fgfgfg','2018-05-26 05:45:20','2018-05-26 05:45:20',5),(56,1,'dfdfdf','2018-05-26 05:45:29','2018-05-26 05:45:29',5),(57,1,'fffff','2018-05-26 05:45:32','2018-05-26 05:45:32',5),(58,1,'fffff','2018-05-26 05:45:34','2018-05-26 05:45:34',5),(59,1,'fffff','2018-05-26 05:45:36','2018-05-26 05:45:36',5),(60,1,'ffff','2018-05-26 05:45:38','2018-05-26 05:45:38',5),(61,1,'erere','2018-05-26 05:45:42','2018-05-26 05:45:42',5),(62,1,'qewasdfzd','2018-05-26 05:45:46','2018-05-26 05:45:46',5),(63,1,'drtrt','2018-05-26 05:46:39','2018-05-26 05:46:39',5),(64,1,'dddd','2018-05-26 05:47:19','2018-05-26 05:47:19',5),(65,1,'ttt','2018-05-26 05:53:58','2018-05-26 05:53:58',5),(66,1,'yyyyy','2018-05-26 05:55:03','2018-05-26 05:55:03',5),(67,1,'ttttt','2018-05-26 05:55:42','2018-05-26 05:55:42',5),(68,1,'dddddd','2018-05-26 05:56:20','2018-05-26 05:56:20',6),(69,1,'uuuu','2018-05-26 05:59:53','2018-05-26 05:59:53',6),(70,1,'dddd','2018-05-26 06:31:26','2018-05-26 06:31:26',6),(71,1,'ttttt','2018-05-26 06:34:56','2018-05-26 06:34:56',6),(72,1,'ttttttytyty','2018-05-26 06:35:02','2018-05-26 06:35:02',6),(73,1,'ddddsdsdsdsd','2018-05-26 06:38:23','2018-05-26 06:38:23',6),(74,1,'fgfgfgfgfgfg','2018-05-26 06:45:12','2018-05-26 06:45:12',6),(75,1,'ccccccc','2018-05-26 06:45:34','2018-05-26 06:45:34',6),(76,1,'xssss','2018-05-26 06:48:19','2018-05-26 06:48:19',6),(77,1,'qwqwqwqwq','2018-05-26 06:48:27','2018-05-26 06:48:27',6),(78,1,'dfdf','2018-05-26 06:50:01','2018-05-26 06:50:01',6),(79,1,'fgfg','2018-05-26 06:50:58','2018-05-26 06:50:58',6),(80,1,'ggggggg','2018-05-26 06:51:04','2018-05-26 06:51:04',6),(81,1,'gggfgfgfgfgfg','2018-05-26 06:52:20','2018-05-26 06:52:20',6),(82,1,'rrrrr','2018-05-26 06:52:46','2018-05-26 06:52:46',6),(83,1,'cdwds','2018-05-26 06:53:09','2018-05-26 06:53:09',6),(84,1,'dfdfdfdf','2018-05-26 06:54:47','2018-05-26 06:54:47',6),(85,1,'fffff','2018-05-26 06:54:50','2018-05-26 06:54:50',6),(86,1,'fjd;aklfj;adfk aas df;kajdf a af aeoir[qer qmrq;me;vz  v[viu[pcovi\' a\'dmka\'fm\'e; qq\'fejq\'oiua\'voa','2018-05-26 06:55:04','2018-05-26 06:55:04',6),(87,4,'ffadfadfdffffffff','2018-05-26 06:56:27','2018-05-26 06:56:27',5),(88,1,'kkk;lkj;lkj;l','2018-05-26 06:56:35','2018-05-26 06:56:35',6),(89,4,'fdfdfdfdfdf','2018-05-26 06:56:47','2018-05-26 06:56:47',5),(90,1,'dfdfdfdf','2018-05-26 06:56:52','2018-05-26 06:56:52',6),(91,1,'dfdfdfdf','2018-05-26 06:57:35','2018-05-26 06:57:35',5),(92,4,'dfdawwwwww','2018-05-26 06:57:52','2018-05-26 06:57:52',5),(93,4,'test','2018-05-26 06:58:57','2018-05-26 06:58:57',11),(94,1,'test','2018-05-26 06:59:20','2018-05-26 06:59:20',11),(95,4,'cool','2018-05-26 06:59:30','2018-05-26 06:59:30',11),(96,4,'word','2018-05-26 06:59:41','2018-05-26 06:59:41',11),(97,1,'and all','2018-05-26 06:59:49','2018-05-26 06:59:49',11),(98,1,'balls','2018-05-26 07:01:58','2018-05-26 07:01:58',11),(99,4,'you fucking rock!','2018-05-26 07:02:05','2018-05-26 07:02:05',11),(100,1,'i know, right','2018-05-26 07:02:10','2018-05-26 07:02:10',11),(101,1,'that\'s phenomenal, i love it','2018-05-26 07:08:31','2018-05-26 07:08:31',11),(102,1,'ffffff','2018-06-06 21:12:33','2018-06-06 21:12:33',7),(103,1,'jjjjj','2018-06-06 21:13:35','2018-06-06 21:13:35',7),(104,1,'cd','2018-06-10 22:10:00','2018-06-10 22:10:00',5),(105,1,'dfdfdf','2018-06-10 23:16:50','2018-06-10 23:16:50',5),(106,1,'ggg','2018-06-10 23:17:29','2018-06-10 23:17:29',5);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2018_04_27_132722_create_auctions_table',1),(4,'2018_05_04_213051_update_user_add_auction_relationship',1),(5,'2018_05_04_213326_update_auction_add_user_relationship',1),(6,'2018_05_05_052828_create_auctions_users_pivot',1),(7,'2018_05_07_161823_update_auctions_users_change_name',1),(8,'2018_05_11_194723_create_messages_table',2),(9,'2018_05_08_045150_create_items_table',3),(10,'2018_05_08_053818_update_items_table',3),(11,'2018_05_17_181546_add_n_name_to_items_table',4),(12,'2018_05_17_195934_add_current_item_to_auctions_table',5),(13,'2018_05_18_040949_create_bids_table',5),(14,'2018_05_18_042255_add_queue_field_to_auctions_table',5),(15,'2018_05_18_043121_remove_item_id_from_auctions_table',6),(16,'2018_05_18_045117_remove_auction_id_from_items_table',7),(17,'2018_05_18_045811_create_auction_item_table',8),(18,'2018_05_18_055626_add_user_auction_item_ids_to_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'jay','jaylong255@gmail.com','$2y$10$vOyGq4WsWMDWx0N4qirn4O2WSxKqiF5pixp/z8XPS2IPOqNIob5we','qhDxcYMVjyYuYxW1QuygOZCYBfVr7eyL7A5XIBeMK2UTvBLOgpDygmtKyOkU','2018-05-11 19:55:00','2018-05-11 19:55:00'),(2,'jay2','test@mail.com','$2y$10$rkug2kYuKIbISKqr/Adrde7FMQTR.8nSf3de.z91U.YhBQLT0O0Ki',NULL,'2018-05-11 20:06:49','2018-05-11 20:06:49'),(3,'test3','test3@test.com','$2y$10$aRAjxi8WqJyYsffx8bmG4edxsqSYWryOsmxfj4QmSvemAbRu3fZk.',NULL,'2018-05-11 21:06:59','2018-05-11 21:06:59'),(4,'jaytest2','test222@test.com','$2y$10$NTVyVeVKg5JUPyzOaJszMuAzVYQnv6653uVfvQd4O9uztFnf3btXO',NULL,'2018-05-26 06:56:11','2018-05-26 06:56:11');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-11  0:12:27
