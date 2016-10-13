-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2013 at 06:49 PM
-- Server version: 5.5.25a-log
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblclients`
--

CREATE TABLE IF NOT EXISTS `tblclients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `hash` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE IF NOT EXISTS `tbldepartments` (
  `deptid` int(10) NOT NULL AUTO_INCREMENT,
  `deptname` varchar(200) DEFAULT NULL,
  `depthidden` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tblfields`
--

CREATE TABLE IF NOT EXISTS `tblfields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `deptid` int(10) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `uniqid` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;


-- --------------------------------------------------------

--
-- Table structure for table `tblpriorities`
--

CREATE TABLE IF NOT EXISTS `tblpriorities` (
  `priority` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblpriorities`
--

INSERT INTO `tblpriorities` (`priority`) VALUES
('Low'),
('Medium'),
('High');

-- --------------------------------------------------------

--
-- Table structure for table `tblsettings`
--

CREATE TABLE IF NOT EXISTS `tblsettings` (
  `setting` varchar(200) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblsettings`
--

INSERT INTO `tblsettings` (`setting`, `value`) VALUES
('companyname', 'Company Name'),
('template', 'default'),
('mailprotocol', 'mail'),
('smtp_host', ''),
('smtp_port', ''),
('smtp_timeout', '30'),
('smtp_user', ''),
('smtp_pass', ''),
('charset', 'utf-8'),
('email', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblstaffs`
--

CREATE TABLE IF NOT EXISTS `tblstaffs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `admin` int(10) DEFAULT '0',
  `hash` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tblstaffs`
--

INSERT INTO `tblstaffs` (`id`, `firstname`, `lastname`, `email`, `password`, `admin`, `hash`) VALUES
(1, 'Admin', 'Admin', 'admin@admin.com', '08gv6gemSKxv09EImCsPLO94Nje6ZVAlFnuc13kohFwvC0kh/ESFEn1lEwL9bbSbrlym/UrO6wM9p0CGnjJHzg==', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tblstatus`
--

CREATE TABLE IF NOT EXISTS `tblstatus` (
  `status` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblstatus`
--

INSERT INTO `tblstatus` (`status`) VALUES
('answered'),
('closed'),
('open'),
('customer reply'),
('in progress');

-- --------------------------------------------------------

--
-- Table structure for table `tblticketreplies`
--

CREATE TABLE IF NOT EXISTS `tblticketreplies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticketid` int(10) NOT NULL,
  `body` text NOT NULL,
  `replier` varchar(200) DEFAULT NULL,
  `replierid` int(10) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbltickets`
--
CREATE TABLE IF NOT EXISTS `tbltickets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` varchar(200) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `clientid` int(10) NOT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `additional` text,
  `attachment` varchar(200) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
