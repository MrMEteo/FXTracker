-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 02 aug 2012 om 13:57
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

--
-- Gegevens worden uitgevoerd voor tabel `smf_bugtracker_entries`
--

INSERT INTO `smf_bugtracker_entries` (`id`, `name`, `description`, `type`, `tracker`, `private`, `startedon`, `project`, `status`, `attention`, `progress`, `in_trash`) VALUES
(6, 'Add real permissions', 'The current permissions do not work, and will only evaluate TRUE if the user is logged in as administrator.', 'feature', 1, 0, '2012-07-17 13:52:54', 1, 'done', 0, 100, 0),
(7, 'Remove entries', 'Allow removal of entries. Also decrease the amount of issues/features in the project.', 'feature', 1, 0, '2012-07-22 23:19:07', 1, 'done', 0, 5, 0),
(31, 'Testing &lt;br/&gt;s', 'Just testing that crap<br /><br />in here...', 'issue', 1, 0, '2012-07-23 18:46:52', 1, 'done', 0, 0, 0),
(11, 'Info Center', 'Make an info center which shows total amount of entries, something like this:\r\n\r\n[quote]Total entries: xx\r\nSolved: xx\r\nWork In Progress: xx\r\nRejected: xx\r\nUnassigned: xx[/quote]', 'feature', 1, 0, '2012-07-17 13:53:29', 1, 'new', 0, 0, 0),
(12, 'Unread Replies/Posts box', 'Add a box like SimpleDesk does which allows you to see the items requiring attention. See http://simplemachines.org/community/index.php?action=unreadreplies for an example.', 'feature', 1, 0, '2012-07-17 13:53:46', 1, 'new', 0, 0, 0),
(13, 'Allow viewing of all entries of a category', 'Allow users to view all entries of a category at once. So allow them to view items in Unassigned at once, etc.', 'feature', 1, 0, '2012-07-22 23:40:08', 1, 'done', 0, 0, 0),
(14, 'Prettier URLs', 'Inspired by Marcus, do not use ;id= but use ;issue= or ;entry=. Also get rid of ;sa=projindex and give it a proper name.\r\n\r\nEdit: Thanks vbgamer45 for pointing out that it will cause issues with mod_security, this is getting priority then.\r\n\r\nEdit2: Done, set to 75% because the other 25% is testing if all URLs work now.', 'issue', 1, 0, '2012-07-23 10:38:29', 1, 'done', 0, 75, 0),
(22, 'Crash when adding new entry when not allowed to', 'Not a true crash, but SMF shows "Unable to load the ''main'' template" when adding a new entry, when you are not allowed to. Should be fixed.\r\n\r\nAlong with this, the New Entry button needs permissions added to it.', 'issue', 1, 0, '2012-07-22 22:42:06', 1, 'done', 0, 0, 0),
(30, 'Set &quot;Requires Attention&quot; to off when marking an entry as solved.', 'Modify the query so it sets the "Requires Attention" thing to off when marking stuff as resolved/done.', 'feature', 1, 0, '2012-08-02 12:31:13', 1, 'reject', 0, 0, 0),
(19, 'Fix the layout of the editing page', 'It looks horrible now.', 'issue', 1, 0, '2012-07-04 10:45:28', 1, 'done', 0, 0, 0),
(16, 'Comments', 'Allow people to comment on issues/features either with an inline system or with topics.', 'feature', 1, 0, '2012-07-17 13:54:11', 1, 'new', 0, 0, 0),
(18, 'Fix \\\\\\\\ when addign a new entry', 'When adding a new entry, and you add something like "doesn''t" it''ll get transformed in "doesn\\''t". It also does this when editing.', 'issue', 1, 0, '2012-07-04 09:59:16', 1, 'done', 0, 100, 0),
(20, 'Allow recalculating of the stats with a button.', 'Right now it is a pain to call the function.', 'feature', 1, 0, '2012-07-17 13:54:22', 1, 'new', 0, 0, 0),
(21, 'Change page title when editing/adding a new entry', 'Right now it shows the URL as page title, not what it''s supposed to show. Can be considered a bug?', 'feature', 1, 0, '2012-07-22 23:19:17', 1, 'done', 0, 0, 0),
(23, 'Enable/disable checkbox', 'Allow the user to enable/disable the mod.', 'feature', 1, 0, '2012-07-18 15:10:33', 6, 'new', 0, 0, 0),
(24, 'Allow the user to disable the mod', 'Add a checkbox to disable it for one topic', 'feature', 1, 0, '2012-07-18 15:11:26', 6, 'new', 0, 0, 0),
(25, 'The menu tag doesn''t work correctly', 'In regard to [url=http://map3cms.co.cc/demo/index.php?topic=2.0]this[/url] topic. The menu tag appears to be broken in some way; fixing needed.', 'issue', 1, 0, '2012-07-21 22:21:03', 2, 'new', 1, 0, 0),
(26, 'Optimize BBCode security', 'Use a preparsecode() while saving data to the database. Thanks to Arantor for pointing this one out :)', 'issue', 1, 0, '2012-07-22 21:40:14', 1, 'done', 0, 0, 0),
(33, 'Feature/issue count doesn''t change when changing type of entry', 'The issue/feature count of a project doesn''t change all the way. Needs to be checked and fixed while saving an edited entry :) ', 'feature', 1, 0, '2012-07-29 21:32:04', 1, 'new', 1, 0, 0);

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
(1, 'FXTracker', 'Any issues or feature requests found in FXTracker', 6, 11, 0),
(2, 'FancyPosts', 'Issues or feature requests found in FancyPosts', 1, 0, 0),
(3, 'Remove "Last Edit" Mod', 'Any issues or feature requests found in Remove "Last Edit" Mod', 0, 0, 0),
(4, 'Back To The Index', 'Any issues or feature requests found in Back To The Index', 0, 0, 0),
(6, 'Lock Recycled Topics', 'Any issues or feature requests found in Lock Recycled Topics', 0, 2, 0),
(5, 'Block E-mail Usernames', 'Any issues or feature requests found in Block E-mail Usernames', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
