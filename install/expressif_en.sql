-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 01 Juillet 2017 à 15:26
-- Version du serveur :  5.7.9
-- Version de PHP :  5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `expressif`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles_articles`
--

DROP TABLE IF EXISTS `articles_articles`;
CREATE TABLE IF NOT EXISTS `articles_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAuthor` int(11) NOT NULL,
  `idCategory` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(200) NOT NULL DEFAULT 'no-thumbnail.jpg',
  `status` int(11) NOT NULL DEFAULT '1',
  `publicationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `articles_categories`
--

DROP TABLE IF EXISTS `articles_categories`;
CREATE TABLE IF NOT EXISTS `articles_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `thumbnail` varchar(1000) NOT NULL DEFAULT 'app/modules/articles/views/frontend/images/no-thumbnail.jpg',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_categories`
--

DROP TABLE IF EXISTS `forum_categories`;
CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_likes`
--

DROP TABLE IF EXISTS `forum_likes`;
CREATE TABLE IF NOT EXISTS `forum_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idMessage` int(11) NOT NULL,
  `liked` int(11) NOT NULL DEFAULT '0',
  `disliked` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_messages`
--

DROP TABLE IF EXISTS `forum_messages`;
CREATE TABLE IF NOT EXISTS `forum_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTopic` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `message` text NOT NULL,
  `helpedAuthor` int(11) NOT NULL DEFAULT '0',
  `publicationDate` datetime NOT NULL,
  `lastEditionDate` datetime DEFAULT NULL,
  `idEditor` int(11) DEFAULT '0',
  `msgInTopic` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_subcategories`
--

DROP TABLE IF EXISTS `forum_subcategories`;
CREATE TABLE IF NOT EXISTS `forum_subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategory` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSubCategory` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `postit` int(11) NOT NULL DEFAULT '0',
  `solved` int(11) NOT NULL DEFAULT '0',
  `locked` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `forum_topicviews`
--

DROP TABLE IF EXISTS `forum_topicviews`;
CREATE TABLE IF NOT EXISTS `forum_topicviews` (
  `idMember` int(11) NOT NULL,
  `idTopic` int(11) NOT NULL,
  `lastMsgSeen` int(11) NOT NULL,
  `participated` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `members_members`
--

DROP TABLE IF EXISTS `members_members`;
CREATE TABLE IF NOT EXISTS `members_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(50) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(200) NOT NULL DEFAULT 'app/modules/members/views/frontend/images/default-avatar.png',
  `signature` text,
  `registrationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastconnectionDate` datetime DEFAULT NULL,
  `lastQuery` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `members_validation`
--

DROP TABLE IF EXISTS `members_validation`;
CREATE TABLE IF NOT EXISTS `members_validation` (
  `idMember` int(11) NOT NULL,
  `token` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `messaging_messages`
--

DROP TABLE IF EXISTS `messaging_messages`;
CREATE TABLE IF NOT EXISTS `messaging_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idThread` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `content` text NOT NULL,
  `msgInThread` int(11) NOT NULL,
  `expeditionDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `messaging_threads`
--

DROP TABLE IF EXISTS `messaging_threads`;
CREATE TABLE IF NOT EXISTS `messaging_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAuthor` int(11) NOT NULL,
  `idParticipants` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `messaging_threadsfavorite`
--

DROP TABLE IF EXISTS `messaging_threadsfavorite`;
CREATE TABLE IF NOT EXISTS `messaging_threadsfavorite` (
  `idMember` int(11) NOT NULL,
  `idThread` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `messaging_threadsviews`
--

DROP TABLE IF EXISTS `messaging_threadsviews`;
CREATE TABLE IF NOT EXISTS `messaging_threadsviews` (
  `idMember` int(11) NOT NULL,
  `idThread` int(11) NOT NULL,
  `lastMsgViewed` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `pages_pages`
--

DROP TABLE IF EXISTS `pages_pages`;
CREATE TABLE IF NOT EXISTS `pages_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `isHomepage` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `pages_pages`
--

INSERT INTO `pages_pages` (`id`, `name`, `content`, `isHomepage`) VALUES
(1, 'Home', '[h]Expressif has been installed ![/h]\r\nCongratulations Expressif has been installed correctly. Expressif is still in [b]bêta[/b] version which means that although the application is working, it might not be fully stable and might present some bugs.\r\n\r\nAlthough the application ran through numerous tests, the validation process before the transition to the stable version is long and tedious. Therefore, we count on you to report any bug that you would come to discover.\r\n\r\nExpressif has been designed to adapt to any kind of website: static websites, communities, forums or even blogs. \r\nNumerous tools are at your disposal by default in order to meet your needs.\r\n\r\nFor experienced users who want to go the extra mile, Expressif has been built from scratch and has its own framework based on a system of plugins and templates allowing developpers to customize both the interface and functionnalities of the application according to their desires. \r\n[b]A complete documentation is available on the official website.[/b]\r\n\r\nWe want to take a moment and thank you for the trust you have put in our application. Feel free to visit us on our official website in case you have any questions.\r\n\r\nExpressif Team.', 1),
(9, 'Rules', 'Use this page to write and establish your own rules that will have to be accepted by the users in order to validate their registration.', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
