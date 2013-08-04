-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 04 Août 2013 à 11:11
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `mots_a_la_pelle`
--

-- --------------------------------------------------------

--
-- Structure de la table `atelier`
--

CREATE TABLE IF NOT EXISTS `atelier` (
  `idAtelier` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `sujet` text NOT NULL,
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUtilisateur` int(11) NOT NULL,
  PRIMARY KEY (`idAtelier`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Structure de la table `cadavre`
--

CREATE TABLE IF NOT EXISTS `cadavre` (
  `idCadavre` int(10) NOT NULL AUTO_INCREMENT,
  `phraseFinale` text NOT NULL,
  `dateDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isEnCours` tinyint(1) NOT NULL DEFAULT '0',
  `idUtilisateur` int(10) NOT NULL,
  PRIMARY KEY (`idCadavre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `idNews` int(10) NOT NULL AUTO_INCREMENT,
  `titreN` varchar(255) NOT NULL DEFAULT '',
  `contenuN` text NOT NULL,
  `idUtilisateur` int(10) NOT NULL,
  PRIMARY KEY (`idNews`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `participantsatelier`
--

CREATE TABLE IF NOT EXISTS `participantsatelier` (
  `idAtelier` int(10) NOT NULL,
  `idUtilisateur` int(10) NOT NULL,
  PRIMARY KEY (`idAtelier`,`idUtilisateur`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `phrase`
--

CREATE TABLE IF NOT EXISTS `phrase` (
  `idPhrase` int(10) NOT NULL AUTO_INCREMENT,
  `phrase` varchar(255) NOT NULL DEFAULT '',
  `idCadavre` int(10) NOT NULL,
  `idUtilisateur` int(10) NOT NULL,
  PRIMARY KEY (`idPhrase`),
  KEY `idCadavre` (`idCadavre`),
  KEY `idCadavre_2` (`idCadavre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `retour`
--

CREATE TABLE IF NOT EXISTS `retour` (
  `idRetour` int(10) NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(1024) NOT NULL DEFAULT '',
  `isValide` tinyint(1) NOT NULL DEFAULT '0',
  `datePoste` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUtilisateur` int(10) NOT NULL,
  `idTexte` int(10) NOT NULL,
  PRIMARY KEY (`idRetour`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `texte`
--

CREATE TABLE IF NOT EXISTS `texte` (
  `idTexte` int(10) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL DEFAULT '',
  `contenu` text NOT NULL,
  `dateAjout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isPublic` tinyint(1) NOT NULL DEFAULT '0',
  `idUtilisateur` int(10) NOT NULL,
  `idTheme` int(10) NOT NULL,
  `idAtelier` int(10) DEFAULT NULL,
  `isBrouillon` tinyint(1) NOT NULL DEFAULT '1',
  `isValide` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idTexte`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idTheme` (`idTheme`),
  KEY `idAtelier` (`idAtelier`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `texte`
--

--
-- Structure de la table `textemelange`
--

CREATE TABLE IF NOT EXISTS `textemelange` (
  `idTexteMelange` int(10) NOT NULL AUTO_INCREMENT,
  `texteOriginal` text NOT NULL,
  `texteFinal` text NOT NULL,
  `dateDebut` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isEnCours` tinyint(1) NOT NULL DEFAULT '0',
  `idUtilisateur` int(10) NOT NULL,
  PRIMARY KEY (`idTexteMelange`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `idTheme` int(10) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`idTheme`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `theme`
--

INSERT INTO `theme` (`idTheme`, `intitule`) VALUES
(1, 'Histoire'),
(2, 'Poésie'),
(3, 'Philosophie'),
(4, 'Politique'),
(5, 'Humour');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `mail` varchar(255) NOT NULL DEFAULT '',
  `login` varchar(255) NOT NULL DEFAULT '',
  `mdp` varchar(255) NOT NULL DEFAULT '',
  `codeP` varchar(10) NOT NULL DEFAULT '',
  `adresse` varchar(255) NOT NULL DEFAULT '',
  `ville` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(20) NOT NULL DEFAULT '',
  `rang` int(2) NOT NULL DEFAULT '0',
  `dateContribution` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateAdherence` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `nom`, `prenom`, `mail`, `login`, `mdp`, `codeP`, `adresse`, `ville`, `tel`, `rang`, `dateContribution`, `dateAdherence`) VALUES
(1, 'DEMO', 'Démo', 'guillaume.sainthillier@gmail.com', 'demo', 'demo', '', '', '', '', 5, '2013-03-13 16:26:52', '0000-00-00 00:00:00'),

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `participantsatelier`
--
ALTER TABLE `participantsatelier`
  ADD CONSTRAINT `participantsatelier_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `participantsatelier_ibfk_2` FOREIGN KEY (`idAtelier`) REFERENCES `atelier` (`idAtelier`);

--
-- Contraintes pour la table `texte`
--
ALTER TABLE `texte`
  ADD CONSTRAINT `texte_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `texte_ibfk_2` FOREIGN KEY (`idTheme`) REFERENCES `theme` (`idTheme`),
  ADD CONSTRAINT `texte_ibfk_3` FOREIGN KEY (`idAtelier`) REFERENCES `atelier` (`idAtelier`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
