-- MySQL dump 10.13  Distrib 5.6.15, for Win64 (x86_64)
--
-- Host: localhost    Database: sandbox
-- ------------------------------------------------------
-- Server version	5.6.15-log

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
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document` (
  `global_document_id` varchar(13) NOT NULL DEFAULT '',
  `domain_id` int(10) unsigned NOT NULL,
  `document_id` mediumint(8) unsigned NOT NULL,
  `document_class` varchar(32) DEFAULT NULL,
  `document_name` varchar(32) DEFAULT NULL,
  `branch` varchar(2) DEFAULT '1',
  `priority` set('9','8','7','6','5','4','3','2','1','low','medium-low','medium','medium-hight','high','hidden_bottom','fixed_bottom','footer','content_post','content','content_pre','header','fixed_top','hidden_top') DEFAULT '5,medium,content',
  `acl_resource_id` varchar(32) DEFAULT 'document',
  `published` datetime DEFAULT NULL,
  `lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`global_document_id`),
  UNIQUE KEY `sub_primary` (`domain_id`,`document_id`),
  UNIQUE KEY `document_name` (`domain_id`,`document_name`),
  KEY `published` (`published`),
  KEY `lastupdated` (`lastupdated`),
  CONSTRAINT `document_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domain`
--

DROP TABLE IF EXISTS `domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domain` (
  `domain_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domain`
--

LOCK TABLES `domain` WRITE;
/*!40000 ALTER TABLE `domain` DISABLE KEYS */;
/*!40000 ALTER TABLE `domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sandbox`
--

DROP TABLE IF EXISTS `sandbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sandbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` char(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fullName` (`fullname`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sandbox`
--

LOCK TABLES `sandbox` WRITE;
/*!40000 ALTER TABLE `sandbox` DISABLE KEYS */;
INSERT INTO `sandbox` VALUES (1,'foo');
/*!40000 ALTER TABLE `sandbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `global_section_id` varchar(255) NOT NULL DEFAULT '',
  `global_document_id` varchar(255) DEFAULT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `document_id` mediumint(8) unsigned NOT NULL,
  `section_class` varchar(32) NOT NULL,
  `section_name` varchar(32) NOT NULL,
  `section_rev` smallint(4) unsigned DEFAULT '1',
  `branch_set` set('64','63','62','61','60','59','58','57','56','55','54','53','52','51','50','49','48','47','46','45','44','43','42','41','40','39','38','37','36','35','34','33','32','31','30','29','28','27','26','25','24','23','22','21','20','19','18','17','16','15','14','13','12','11','10','9','8','7','6','5','4','3','2','1') DEFAULT NULL,
  `release_tag` enum('master','origin','next','outdated') DEFAULT NULL,
  `content` text,
  `section_properties` text,
  `editor_id` int(10) unsigned NOT NULL,
  `capture_to` varchar(32) DEFAULT NULL,
  `kvs_resource_id` varchar(64) DEFAULT NULL,
  `kvs_resource_class` varchar(32) DEFAULT NULL,
  `acl_resource_id` varchar(32) DEFAULT NULL,
  `status` set('removed','outdated','modified','req_remove','req_edit','req_publish','scheduled','incubation','published') DEFAULT NULL,
  `priority` set('9','8','7','6','5','4','3','2','1','low','medium-low','medium','medium-hight','high','hidden_bottom','fixed_bottom','footer','content_post','content','content_pre','header','fixed_top','hidden_top') DEFAULT '5,medium,content',
  `lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `template_name` varchar(255) DEFAULT NULL,
  `section_to_string` text,
  `section_note` text,
  PRIMARY KEY (`global_section_id`),
  UNIQUE KEY `domain_document` (`domain_id`,`document_id`),
  UNIQUE KEY `domain_document_section` (`domain_id`,`document_id`,`section_name`,`section_rev`),
  KEY `document_branch` (`global_document_id`,`branch_set`,`priority`),
  KEY `document_release` (`release_tag`,`global_document_id`),
  KEY `document_domain_release` (`domain_id`,`release_tag`,`global_document_id`),
  KEY `editor_id` (`editor_id`,`lastupdated`),
  KEY `section_class` (`section_class`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`global_document_id`) REFERENCES `document` (`global_document_id`) ON UPDATE CASCADE,
  CONSTRAINT `section_ibfk_2` FOREIGN KEY (`domain_id`, `document_id`) REFERENCES `document` (`domain_id`, `document_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-08 16:28:42
