CREATE DATABASE  IF NOT EXISTS `zwave` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `zwave`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: 192.168.2.7    Database: zwave
-- ------------------------------------------------------
-- Server version	5.5.44-0+deb8u1

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
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `message` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=950660 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor_avg120m`
--

DROP TABLE IF EXISTS `sensor_avg120m`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor_avg120m` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `sa_s_id` int(11) NOT NULL,
  `sa_value` float NOT NULL,
  `sa_datetime` datetime NOT NULL,
  PRIMARY KEY (`sa_id`),
  KEY `i_sensor_id` (`sa_s_id`),
  KEY `i_sensor_id_datetime` (`sa_datetime`,`sa_s_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3676 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor_avg24h`
--

DROP TABLE IF EXISTS `sensor_avg24h`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor_avg24h` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `sa_s_id` int(11) NOT NULL,
  `sa_value` float NOT NULL,
  `sa_datetime` date NOT NULL,
  PRIMARY KEY (`sa_id`),
  KEY `i_sensor_id` (`sa_s_id`),
  KEY `i_sensor_id_datetime` (`sa_s_id`,`sa_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor_avg30m`
--

DROP TABLE IF EXISTS `sensor_avg30m`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor_avg30m` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `sa_s_id` int(11) NOT NULL,
  `sa_value` float NOT NULL,
  `sa_datetime` datetime NOT NULL,
  PRIMARY KEY (`sa_id`),
  KEY `i_sensor_id` (`sa_s_id`),
  KEY `i_sensor_id_datetime` (`sa_s_id`,`sa_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=14468 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor_avg5m`
--

DROP TABLE IF EXISTS `sensor_avg5m`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor_avg5m` (
  `sa_id` int(11) NOT NULL AUTO_INCREMENT,
  `sa_s_id` int(11) NOT NULL,
  `sa_value` float NOT NULL,
  `sa_datetime` datetime NOT NULL,
  PRIMARY KEY (`sa_id`),
  KEY `i_sensor_id` (`sa_s_id`),
  KEY `i_sensor_id_datetime` (`sa_s_id`,`sa_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=85565 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor_data`
--

DROP TABLE IF EXISTS `sensor_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor_data` (
  `sd_id` int(11) NOT NULL AUTO_INCREMENT,
  `sd_s_id` int(11) NOT NULL,
  `sd_datetime` datetime NOT NULL,
  `sd_value` float NOT NULL,
  PRIMARY KEY (`sd_id`),
  KEY `i_sensor_id` (`sd_s_id`),
  KEY `i_date` (`sd_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=423086 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-15 19:55:13
