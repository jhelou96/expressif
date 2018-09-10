-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 01 Juillet 2017 à 15:12
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
(1, 'Accueil', '[h]Expressif vient d''être installé ![/h]\r\nFélicitations, Expressif a été installé correctement. Expressif est encore en version [b]bêta[/b] ce qui signifie que l''application bien que fonctionnelle n''est pas encore tout à fait stable et peut comporter certains bugs. \r\n\r\nBien qu''ayant fait l''objet de nombreux tests avant d''être publié, le processus de validation avant le passage à la version stable est long et fastidieux. Ainsi, nous comptons sur vous pour nous faire remonter quelconque bug que vous viendriez à découvrir.\r\n\r\nExpressif a été conçu pour s''adapter à tout type de site web: Sites vitrines, communautaires, forums ou encore blogs. De nombreux outils sont mis à votre disposition par défaut afin de répondre à vos besoins.\r\n\r\nPour les utilisateurs expérimentés qui cherchent à aller plus loin, Expressif a été conçu de zéro et dispose de son propre framework basé sur un système de plugins et templates permettant ainsi aux développeurs de personnaliser à la fois l''interface et les fonctionnalités de l''application selon leurs envies. [b]Une documentation complète est mise à disposition sur le site officiel.[/b]\r\n\r\nNous vous remercions pour la confiance que vous avez accordé à notre application. N''hésitez pas à avoir recours au site officiel en cas de soucis.\r\n\r\nL''équipe d''Expressif.', 1),
(9, 'Rules', 'Utilisez cette page pour rédiger vos propres règles qui devront être acceptées par les utilisateurs afin de valider leur inscription.', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
