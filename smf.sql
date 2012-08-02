-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 02 aug 2012 om 14:02
-- Serverversie: 5.5.24-log
-- PHP-versie: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `smf`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `smf_bugtracker_entries`
--

CREATE TABLE IF NOT EXISTS `smf_bugtracker_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `description` longtext NOT NULL,
  `type` tinytext NOT NULL,
  `tracker` int(11) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `startedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `project` int(11) NOT NULL,
  `status` mediumtext NOT NULL,
  `attention` tinyint(1) NOT NULL,
  `progress` int(11) NOT NULL,
  `in_trash` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `smf_bugtracker_projects`
--

CREATE TABLE IF NOT EXISTS `smf_bugtracker_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `description` longtext NOT NULL,
  `issuenum` int(11) NOT NULL,
  `featurenum` int(11) NOT NULL,
  `lastnum` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `smf_bugtracker_projects`
--

INSERT INTO `smf_bugtracker_projects` (`id`, `name`, `description`, `issuenum`, `featurenum`, `lastnum`) VALUES
(1, 'Testing', 'Test FXTracker in here', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
