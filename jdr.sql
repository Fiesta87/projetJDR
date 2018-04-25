-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 25 avr. 2018 à 15:32
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `jdr`
--

-- --------------------------------------------------------

--
-- Structure de la table `attribut`
--

DROP TABLE IF EXISTS `attribut`;
CREATE TABLE IF NOT EXISTS `attribut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFiche` int(11) NOT NULL,
  `idAttributParent` int(11) NOT NULL,
  `nom` varchar(200) NOT NULL,
  `valeur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `attribut`
--

INSERT INTO `attribut` (`id`, `idFiche`, `idAttributParent`, `nom`, `valeur`) VALUES
(1, 1, -1, 'Physique', 1),
(2, 1, -1, 'Mental', 1),
(3, 1, 1, 'Force', 1),
(4, 1, 1, 'Vigueur', 1),
(5, 1, 2, 'Intelligence', 1),
(6, 1, 2, 'Perception', 1);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `idUser` int(11) NOT NULL,
  `idFiche` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`idUser`, `idFiche`) VALUES
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `fiche`
--

DROP TABLE IF EXISTS `fiche`;
CREATE TABLE IF NOT EXISTS `fiche` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `metadata`
--

DROP TABLE IF EXISTS `metadata`;
CREATE TABLE IF NOT EXISTS `metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `valeur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `metadata`
--

INSERT INTO `metadata` (`id`, `nom`, `valeur`) VALUES
(1, 'page', 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` binary(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `salt`) VALUES
(7, 'amesk', 'azerty@gmail.com', 'fe88defbf8fd208632e26c37055d2872de0f03575109dec5e0aaf4dc0fea06c55dffc7ebc12636957c897da9b601d267fd92d20d24ef93794dbec204ae7335a3', 0xa023fd6aeb71f7255bbea7810c98a295);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
