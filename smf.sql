-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 10 aug 2012 om 22:50
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Gegevens worden uitgevoerd voor tabel `smf_bugtracker_entries`
--

INSERT INTO `smf_bugtracker_entries` (`id`, `name`, `description`, `type`, `tracker`, `private`, `startedon`, `project`, `status`, `attention`, `progress`, `in_trash`) VALUES
(39, 'An open entry', 'Testing', 'issue', 1, 0, '2012-08-09 23:06:06', 1, 'new', 0, 0, 0),
(40, 'A closed entry', 'Posting a resolved/closed entry', 'feature', 1, 0, '2012-08-10 09:47:00', 1, 'done', 0, 0, 0),
(41, 'A rejected entry', 'Noes I''m rejected :(', 'issue', 1, 0, '2012-08-09 19:28:58', 1, 'reject', 0, 0, 0),
(42, 'Workin'' around', 'Just workin'' around.', 'feature', 1, 0, '2012-08-10 09:47:08', 1, 'wip', 1, 10, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `smf_bugtracker_notes`
--

CREATE TABLE IF NOT EXISTS `smf_bugtracker_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authorid` int(11) NOT NULL,
  `time_posted` int(11) NOT NULL,
  `entryid` int(11) NOT NULL,
  `note` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
(1, 'Testing', 'Test FXTracker in here', 2, 2, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
