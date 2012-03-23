-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 22, 2012 at 06:47 PM
-- Server version: 5.1.52
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `immigrants`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `speaker` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `timeslot` int(10) unsigned NOT NULL,
  `length` int(10) unsigned NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `required` int(10) unsigned DEFAULT NULL,
  `restricted` set('9','10','11','12') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(10) unsigned NOT NULL,
  `event1` int(10) unsigned DEFAULT NULL,
  `event2` int(10) unsigned DEFAULT NULL,
  `event3` int(10) unsigned DEFAULT NULL,
  `event4` int(10) unsigned DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event1` (`event1`),
  KEY `event2` (`event2`),
  KEY `event3` (`event3`),
  KEY `event4` (`event4`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `studentdata`
--

CREATE TABLE IF NOT EXISTS `studentdata` (
  `BCPStudID` int(6) unsigned NOT NULL,
  `StudLastName` varchar(32) NOT NULL,
  `StudFirstName` varchar(32) NOT NULL,
  `StudLastFirst` varchar(32) NOT NULL,
  `Grade` int(2) unsigned NOT NULL,
  `HomeroomTeacher` varchar(32) NOT NULL,
  `HomeroomRoom` varchar(8) NOT NULL,
  `StudentEmail` varchar(32) NOT NULL,
  PRIMARY KEY (`BCPStudID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`id`) REFERENCES `studentdata` (`BCPStudID`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event1`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `registrations_ibfk_3` FOREIGN KEY (`event2`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `registrations_ibfk_4` FOREIGN KEY (`event3`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `registrations_ibfk_5` FOREIGN KEY (`event4`) REFERENCES `events` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
