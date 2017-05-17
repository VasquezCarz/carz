-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Generation Time: May 13, 2017 at 07:34 PM
-- Server version: 5.0.83
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `carz`
--

-- --------------------------------------------------------

--
-- Table structure for table `crz_boite`
--

CREATE TABLE IF NOT EXISTS `crz_boite` (
  `id_boite` int(10) unsigned NOT NULL auto_increment,
  `lib_boite` varchar(64) collate utf8_unicode_ci NOT NULL,
  `auto` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id_boite`),
  UNIQUE KEY `lib_boite` (`lib_boite`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `crz_boite`
--

INSERT INTO `crz_boite` (`id_boite`, `lib_boite`, `auto`) VALUES
(1, 'manuelle', 0),
(2, 'Multitronic', 1),
(3, 'Tiptronic', 1),
(4, 'S-tronic', 1),
(5, 'R-tronic', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crz_code`
--

CREATE TABLE IF NOT EXISTS `crz_code` (
  `id_code` int(10) unsigned NOT NULL auto_increment,
  `lib_code` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_code`),
  UNIQUE KEY `lib_nom_code` (`lib_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Dumping data for table `crz_code`
--

INSERT INTO `crz_code` (`id_code`, `lib_code`) VALUES
(1, '8X'),
(2, '8Z'),
(3, '8L'),
(4, '8P'),
(5, '8V'),
(6, 'B5'),
(7, 'B6'),
(8, 'B7'),
(9, 'B8'),
(10, '8T'),
(11, 'C4'),
(12, 'C5'),
(13, 'C6'),
(14, 'C7'),
(15, '4G'),
(16, 'D2'),
(17, 'D3'),
(18, 'D4'),
(19, '8N'),
(20, '8J'),
(21, '8S'),
(22, 'E30'),
(23, 'E36'),
(24, 'E46'),
(25, 'E90'),
(26, 'E92'),
(27, 'P1'),
(28, 'F80'),
(29, 'F82'),
(30, 'B3'),
(31, '42'),
(32, '4S');

-- --------------------------------------------------------

--
-- Table structure for table `crz_finition`
--

CREATE TABLE IF NOT EXISTS `crz_finition` (
  `id_finition` int(10) unsigned NOT NULL auto_increment,
  `lib_finition` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_finition`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `crz_finition`
--

INSERT INTO `crz_finition` (`id_finition`, `lib_finition`) VALUES
(1, 'S line');

-- --------------------------------------------------------

--
-- Table structure for table `crz_groupe`
--

CREATE TABLE IF NOT EXISTS `crz_groupe` (
  `id_groupe` int(10) unsigned NOT NULL auto_increment,
  `lib_groupe` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_groupe`),
  UNIQUE KEY `lib_groupe` (`lib_groupe`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `crz_groupe`
--

INSERT INTO `crz_groupe` (`id_groupe`, `lib_groupe`) VALUES
(1, 'Groupe de test');

-- --------------------------------------------------------

--
-- Table structure for table `crz_groupe_utilisateur`
--

CREATE TABLE IF NOT EXISTS `crz_groupe_utilisateur` (
  `fk_groupe` int(10) unsigned NOT NULL,
  `fk_utilisateur` int(10) unsigned NOT NULL,
  `admin_groupe` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fk_groupe`,`fk_utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_groupe_utilisateur`
--

INSERT INTO `crz_groupe_utilisateur` (`fk_groupe`, `fk_utilisateur`, `admin_groupe`) VALUES
(1, 1, 1),
(1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `crz_groupe_voiture`
--

CREATE TABLE IF NOT EXISTS `crz_groupe_voiture` (
  `fk_groupe` int(10) unsigned NOT NULL,
  `fk_voiture` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_groupe`,`fk_voiture`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_groupe_voiture`
--

INSERT INTO `crz_groupe_voiture` (`fk_groupe`, `fk_voiture`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `crz_marque`
--

CREATE TABLE IF NOT EXISTS `crz_marque` (
  `id_marque` int(10) unsigned NOT NULL auto_increment,
  `lib_marque` varchar(64) collate utf8_unicode_ci NOT NULL,
  `fk_pays` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_marque`),
  UNIQUE KEY `lib_marque` (`lib_marque`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `crz_marque`
--

INSERT INTO `crz_marque` (`id_marque`, `lib_marque`, `fk_pays`) VALUES
(1, 'Audi', 1),
(2, 'BMW', 1),
(3, 'Mercedes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crz_modele`
--

CREATE TABLE IF NOT EXISTS `crz_modele` (
  `id_modele` int(10) unsigned NOT NULL auto_increment,
  `lib_modele` varchar(64) collate utf8_unicode_ci NOT NULL,
  `fk_marque` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_modele`),
  UNIQUE KEY `lib_modele` (`lib_modele`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `crz_modele`
--

INSERT INTO `crz_modele` (`id_modele`, `lib_modele`, `fk_marque`) VALUES
(1, 'A1', 1),
(2, 'S1', 1),
(3, 'A2', 1),
(4, 'S2', 1),
(5, 'RS2', 1),
(6, 'A3', 1),
(7, 'S3', 1),
(8, 'RS3', 1),
(9, 'A4', 1),
(10, 'S4', 1),
(11, 'RS4', 1),
(12, 'A5', 1),
(13, 'S5', 1),
(14, 'RS5', 1),
(15, 'A6', 1),
(16, 'S6', 1),
(17, 'RS6', 1),
(18, 'S6 Plus', 1),
(19, 'A7', 1),
(20, 'S7', 1),
(21, 'RS7', 1),
(22, 'A8', 1),
(23, 'S8', 1),
(24, 'TT', 1),
(25, 'TT S', 1),
(26, 'TT RS', 1),
(27, 'Série 1', 2),
(28, 'Série 2', 2),
(29, 'Série 3', 2),
(30, 'Série 4', 2),
(31, 'M3', 2),
(32, 'M4', 2),
(33, 'M5', 2),
(34, 'Coupe', 1),
(36, 'A4 allroad', 1),
(37, 'R8', 1),
(38, '100', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crz_modele_code`
--

CREATE TABLE IF NOT EXISTS `crz_modele_code` (
  `fk_modele` int(10) unsigned NOT NULL,
  `fk_code` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_modele`,`fk_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_modele_code`
--

INSERT INTO `crz_modele_code` (`fk_modele`, `fk_code`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 30),
(5, 27),
(6, 3),
(6, 4),
(6, 5),
(7, 3),
(7, 4),
(7, 5),
(8, 4),
(8, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(10, 6),
(10, 7),
(10, 8),
(10, 9),
(11, 6),
(11, 8),
(11, 9),
(12, 10),
(13, 10),
(14, 10),
(15, 11),
(15, 12),
(15, 13),
(15, 14),
(16, 11),
(16, 12),
(16, 13),
(16, 14),
(17, 12),
(17, 13),
(17, 14),
(18, 11),
(19, 15),
(20, 15),
(21, 15),
(22, 16),
(22, 17),
(22, 18),
(23, 16),
(23, 17),
(23, 18),
(24, 19),
(24, 20),
(24, 21),
(25, 20),
(25, 21),
(26, 20),
(26, 21),
(30, 29),
(31, 22),
(31, 23),
(31, 24),
(31, 25),
(31, 26),
(31, 28),
(32, 29),
(34, 30),
(36, 9),
(37, 31),
(37, 32),
(38, 11);

-- --------------------------------------------------------

--
-- Table structure for table `crz_modele_code_motorisation_boite`
--

CREATE TABLE IF NOT EXISTS `crz_modele_code_motorisation_boite` (
  `fk_modele` int(10) unsigned NOT NULL,
  `fk_code` int(10) unsigned NOT NULL,
  `fk_motorisation` int(10) unsigned NOT NULL,
  `fk_boite` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_modele`,`fk_code`,`fk_motorisation`,`fk_boite`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_modele_code_motorisation_boite`
--

INSERT INTO `crz_modele_code_motorisation_boite` (`fk_modele`, `fk_code`, `fk_motorisation`, `fk_boite`) VALUES
(2, 1, 4, 1),
(5, 27, 25, 1),
(6, 3, 23, 1),
(6, 5, 7, 4),
(6, 5, 13, 4),
(7, 3, 3, 1),
(7, 4, 4, 1),
(7, 4, 4, 4),
(7, 5, 4, 1),
(7, 5, 4, 4),
(8, 4, 14, 4),
(8, 5, 14, 4),
(9, 7, 27, 1),
(9, 9, 11, 1),
(10, 8, 24, 1),
(10, 8, 24, 3),
(10, 9, 5, 4),
(11, 8, 1, 1),
(11, 9, 1, 4),
(12, 10, 2, 1),
(12, 10, 18, 2),
(13, 10, 1, 1),
(13, 10, 1, 3),
(13, 10, 5, 4),
(14, 10, 1, 4),
(15, 13, 1, 3),
(15, 13, 7, 3),
(15, 13, 17, 3),
(15, 14, 16, 3),
(15, 14, 17, 3),
(16, 13, 6, 3),
(16, 14, 19, 4),
(17, 13, 20, 3),
(17, 14, 19, 3),
(19, 15, 17, 4),
(21, 15, 19, 3),
(23, 17, 6, 3),
(23, 18, 19, 3),
(24, 19, 3, 1),
(25, 20, 4, 1),
(25, 20, 4, 4),
(25, 21, 4, 1),
(25, 21, 4, 4),
(26, 20, 14, 1),
(26, 20, 14, 4),
(26, 21, 14, 4),
(34, 30, 9, 1),
(34, 30, 10, 1),
(34, 30, 26, 1),
(36, 9, 11, 1),
(37, 31, 1, 1),
(37, 31, 1, 5),
(37, 31, 6, 1),
(37, 31, 6, 5),
(37, 32, 6, 4),
(38, 11, 28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `crz_modele_code_puissance`
--

CREATE TABLE IF NOT EXISTS `crz_modele_code_puissance` (
  `fk_modele` int(10) unsigned NOT NULL,
  `fk_code` int(10) unsigned NOT NULL,
  `fk_puissance` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_modele`,`fk_code`,`fk_puissance`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_modele_code_puissance`
--

INSERT INTO `crz_modele_code_puissance` (`fk_modele`, `fk_code`, `fk_puissance`) VALUES
(0, 0, 25),
(2, 1, 34),
(5, 27, 32),
(6, 3, 15),
(6, 3, 19),
(6, 3, 25),
(6, 3, 37),
(6, 5, 12),
(7, 3, 4),
(7, 4, 6),
(7, 5, 7),
(8, 4, 33),
(8, 5, 17),
(9, 7, 39),
(9, 9, 15),
(10, 8, 26),
(10, 9, 5),
(11, 8, 9),
(11, 9, 2),
(12, 10, 3),
(12, 10, 20),
(13, 10, 1),
(13, 10, 5),
(14, 10, 2),
(15, 13, 13),
(15, 13, 28),
(15, 14, 18),
(16, 13, 21),
(16, 14, 22),
(17, 13, 23),
(17, 14, 24),
(19, 15, 36),
(21, 15, 24),
(23, 17, 8),
(23, 18, 27),
(24, 19, 4),
(25, 20, 10),
(25, 21, 11),
(26, 20, 33),
(26, 21, 35),
(29, 0, 25),
(34, 30, 19),
(34, 30, 37),
(36, 9, 16),
(37, 31, 9),
(37, 31, 29),
(37, 32, 30),
(37, 32, 31),
(38, 11, 40);

-- --------------------------------------------------------

--
-- Table structure for table `crz_modele_finition`
--

CREATE TABLE IF NOT EXISTS `crz_modele_finition` (
  `fk_modele` int(10) unsigned NOT NULL,
  `fk_finition` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_modele`,`fk_finition`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_modele_finition`
--


-- --------------------------------------------------------

--
-- Table structure for table `crz_motorisation`
--

CREATE TABLE IF NOT EXISTS `crz_motorisation` (
  `id_motorisation` int(10) unsigned NOT NULL auto_increment,
  `lib_motorisation` varchar(64) collate utf8_unicode_ci NOT NULL,
  `energie` enum('essence','diesel','hybride','electrique') collate utf8_unicode_ci NOT NULL default 'essence',
  `cylindree` smallint(4) unsigned default NULL,
  `nb_cylindres` tinyint(2) unsigned default NULL,
  `nb_soupapes` tinyint(2) unsigned default NULL,
  `suralimentation` enum('atmo','turbo','compresseur') collate utf8_unicode_ci default NULL,
  `injection` enum('direct','indirect','carbu') collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id_motorisation`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `crz_motorisation`
--

INSERT INTO `crz_motorisation` (`id_motorisation`, `lib_motorisation`, `energie`, `cylindree`, `nb_cylindres`, `nb_soupapes`, `suralimentation`, `injection`) VALUES
(1, 'V8 4.2 FSI', 'essence', 4163, 8, 32, 'atmo', 'direct'),
(2, '1.8 TFSI', 'essence', 1798, 4, 16, 'turbo', 'direct'),
(3, '1.8 T', 'essence', 1781, 4, 20, 'turbo', 'indirect'),
(4, '2.0 TFSI', 'essence', 1984, 4, 16, 'turbo', 'direct'),
(5, 'V6 3.0 TFSI', 'essence', 2995, 6, 24, 'compresseur', 'direct'),
(6, 'V10 5.2 FSI', 'essence', 5204, 10, 40, 'atmo', 'direct'),
(9, '2.3 20V', 'essence', 2309, 5, 20, 'atmo', 'indirect'),
(18, 'V6 2.7 TDI', 'diesel', 2698, 6, 24, 'turbo', 'direct'),
(11, '2.0 TDI', 'diesel', 1968, 4, 16, 'turbo', 'direct'),
(17, 'V6 3.0 TDI', 'diesel', 2967, 6, 24, 'turbo', 'direct'),
(13, '1.4 TFSI e-tron', 'hybride', 1395, 4, 16, 'turbo', 'direct'),
(14, '2.5 TFSI', 'essence', 2480, 5, 20, 'turbo', 'direct'),
(19, 'V8 4.0 TFSI', 'essence', 3993, 8, 32, 'turbo', 'direct'),
(20, 'V10 5.0 TFSI', 'essence', 4991, 10, 40, 'turbo', 'direct'),
(24, 'V8 4.2', 'essence', 4163, 8, 40, 'atmo', 'indirect'),
(23, '1.9 TDI', 'diesel', 1896, 4, 8, 'turbo', 'direct'),
(25, '2.2 20VT', 'essence', 2226, 5, 20, 'turbo', 'indirect'),
(26, '2.3 10V', 'essence', 2309, 5, 10, 'atmo', 'indirect'),
(27, 'V6 2.5 TDI', 'diesel', 2496, 6, 24, 'turbo', 'direct'),
(28, 'V6 2.8', 'essence', 2771, 6, 12, 'atmo', 'indirect');

-- --------------------------------------------------------

--
-- Table structure for table `crz_pays`
--

CREATE TABLE IF NOT EXISTS `crz_pays` (
  `id_pays` int(10) unsigned NOT NULL auto_increment,
  `lib_pays` varchar(64) collate utf8_unicode_ci NOT NULL,
  `code_pays` char(2) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_pays`),
  UNIQUE KEY `lib_pays` (`lib_pays`),
  UNIQUE KEY `code_pays` (`code_pays`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `crz_pays`
--

INSERT INTO `crz_pays` (`id_pays`, `lib_pays`, `code_pays`) VALUES
(1, 'Allemagne', 'de'),
(2, 'France', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `crz_pseudo`
--

CREATE TABLE IF NOT EXISTS `crz_pseudo` (
  `id_pseudo` int(10) unsigned NOT NULL auto_increment,
  `lib_pseudo` varchar(64) collate utf8_unicode_ci NOT NULL,
  `site` enum('AudiPassion','FaceBook') collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_pseudo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `crz_pseudo`
--

INSERT INTO `crz_pseudo` (`id_pseudo`, `lib_pseudo`, `site`) VALUES
(1, 'Admin', 'Carz'),
(2, 'User', 'Carz');

-- --------------------------------------------------------

--
-- Table structure for table `crz_puissance`
--

CREATE TABLE IF NOT EXISTS `crz_puissance` (
  `id_puissance` int(10) unsigned NOT NULL auto_increment,
  `puissance` smallint(4) unsigned NOT NULL,
  `regime_puissance` smallint(5) unsigned NOT NULL,
  `couple` smallint(4) unsigned NOT NULL,
  `regime_couple` smallint(5) unsigned NOT NULL,
  `fk_motorisation` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_puissance`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

--
-- Dumping data for table `crz_puissance`
--

INSERT INTO `crz_puissance` (`id_puissance`, `puissance`, `regime_puissance`, `couple`, `regime_couple`, `fk_motorisation`) VALUES
(1, 354, 6800, 440, 3500, 1),
(2, 450, 8250, 430, 4000, 1),
(3, 170, 4800, 250, 1500, 2),
(4, 225, 5900, 280, 2200, 3),
(5, 333, 5500, 440, 2900, 5),
(6, 265, 6000, 350, 2500, 4),
(7, 300, 5500, 380, 1800, 4),
(8, 450, 7000, 540, 3500, 6),
(9, 420, 7800, 430, 5500, 1),
(10, 272, 6000, 350, 2500, 4),
(11, 310, 5800, 380, 1800, 4),
(12, 204, 5000, 350, 2200, 13),
(13, 225, 4000, 450, 1400, 17),
(14, 177, 6000, 255, 4500, 10),
(15, 143, 4250, 320, 1750, 11),
(16, 177, 4200, 380, 1750, 11),
(17, 367, 5550, 465, 1625, 14),
(18, 313, 3900, 650, 1450, 17),
(19, 170, 6000, 220, 4500, 9),
(20, 190, 3250, 400, 1400, 18),
(21, 435, 6800, 540, 3000, 6),
(22, 420, 5500, 550, 1400, 19),
(23, 580, 6250, 650, 1500, 20),
(24, 560, 5700, 700, 1750, 19),
(25, 100, 4000, 240, 1900, 23),
(26, 344, 7000, 410, 3500, 24),
(27, 520, 5800, 650, 1700, 19),
(28, 350, 6600, 440, 3500, 1),
(29, 525, 8000, 530, 6500, 6),
(30, 540, 8250, 540, 6500, 6),
(31, 610, 8250, 560, 6500, 6),
(32, 315, 6500, 410, 3000, 25),
(33, 340, 5400, 450, 1600, 14),
(34, 231, 6000, 370, 1600, 4),
(35, 400, 5800, 480, 1700, 14),
(36, 245, 3800, 500, 1500, 17),
(37, 136, 5700, 190, 4500, 26),
(39, 180, 4000, 370, 1500, 27),
(40, 174, 5500, 250, 3000, 28);

-- --------------------------------------------------------

--
-- Table structure for table `crz_utilisateur`
--

CREATE TABLE IF NOT EXISTS `crz_utilisateur` (
  `id_utilisateur` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(64) collate utf8_unicode_ci NOT NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `nom` varchar(64) collate utf8_unicode_ci NOT NULL,
  `prenom` varchar(64) collate utf8_unicode_ci NOT NULL,
  `mail` varchar(255) collate utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) unsigned NOT NULL default '0',
  `date_expiration` datetime default NULL,
  `hash_activation` varchar(32) collate utf8_unicode_ci default NULL,
  `hash_reset` varchar(32) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id_utilisateur`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=45 ;

--
-- Dumping data for table `crz_utilisateur`
--

INSERT INTO `crz_utilisateur` (`id_utilisateur`, `login`, `password`, `nom`, `prenom`, `mail`, `admin`, `date_expiration`, `hash_activation`, `hash_reset`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Nom', 'Prénom', 'admin@yopmail.com', 1, NULL, NULL, NULL),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'Nom', 'Prénom', 'user@yopmail.com', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `crz_utilisateur_pseudo`
--

CREATE TABLE IF NOT EXISTS `crz_utilisateur_pseudo` (
  `fk_utilisateur` int(10) unsigned NOT NULL,
  `fk_pseudo` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`fk_utilisateur`,`fk_pseudo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `crz_utilisateur_pseudo`
--

INSERT INTO `crz_utilisateur_pseudo` (`fk_utilisateur`, `fk_pseudo`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `crz_voiture`
--

CREATE TABLE IF NOT EXISTS `crz_voiture` (
  `id_voiture` int(10) unsigned NOT NULL auto_increment,
  `lib_voiture` varchar(64) collate utf8_unicode_ci NOT NULL,
  `fk_utilisateur` int(10) unsigned NOT NULL,
  `fk_modele` int(10) unsigned NOT NULL,
  `fk_code` int(10) unsigned NOT NULL,
  `fk_finition` int(10) unsigned default NULL,
  `fk_boite` int(10) unsigned NOT NULL,
  `fk_puissance` int(10) unsigned NOT NULL,
  `puissance_reelle` smallint(4) unsigned default NULL,
  `annee` year(4) default NULL,
  PRIMARY KEY  (`id_voiture`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `crz_voiture`
--

INSERT INTO `crz_voiture` (`id_voiture`, `lib_voiture`, `fk_utilisateur`, `fk_modele`, `fk_code`, `fk_finition`, `fk_boite`, `fk_puissance`, `puissance_reelle`, `annee`) VALUES
(1, 'Audi S5 Coupe V8 4.2 FSI 354 quattro', 1, 13, 10, NULL, 1, 1, NULL, 2008),
(2, 'Audi TT Coupe 1.8 T 225 quattro', 2, 24, 19, NULL, 1, 4, NULL, 2002);
