-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: mysql1005.domainname.com
-- Generation Time: Apr 29, 2020 at 01:32 AM
-- Server version: 5.6.39
-- PHP Version: 7.2.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `DB`
--

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE IF NOT EXISTS `entries` (
  `username` varchar(48) COLLATE utf8_turkish_ci NOT NULL,
  `entry` longtext COLLATE utf8_turkish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(64) COLLATE utf8_turkish_ci NOT NULL,
  `title_id` bigint(20) DEFAULT NULL,
  `stars` tinyint(3) unsigned NOT NULL,
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=264 ;

--
-- Dumping data for table `entries`


--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `isRead` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_turkish_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `isDeletedByReceiver` tinyint(1) NOT NULL DEFAULT '0',
  `isDeletedBySender` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=44 ;

--
-- Dumping data for table `messages`

--
-- Table structure for table `titles`
--

CREATE TABLE IF NOT EXISTS `titles` (
  `title` varchar(64) COLLATE utf8_turkish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(64) COLLATE utf8_turkish_ci NOT NULL,
  `lastupdate` datetime DEFAULT NULL,
  `title_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `totalstar` bigint(20) unsigned NOT NULL,
  `totalentry` bigint(20) DEFAULT NULL,
  `category` varchar(32) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`title_id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `title_2` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=122 ;

--
-- Dumping data for table `titles`

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(32) COLLATE utf8_turkish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_turkish_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_turkish_ci NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `emailcheck` varchar(3) COLLATE utf8_turkish_ci NOT NULL,
  `status` varchar(12) COLLATE utf8_turkish_ci DEFAULT NULL,
  `banned` varchar(3) COLLATE utf8_turkish_ci DEFAULT NULL,
  `onlinestatus` bigint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=44 ;

--
-- Dumping data for table `users`
--



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
