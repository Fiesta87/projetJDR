-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 26 avr. 2018 à 23:14
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `attribut`
--

INSERT INTO `attribut` (`id`, `idFiche`, `idAttributParent`, `nom`, `valeur`) VALUES
(11, 4, 7, 'Force', 1),
(10, 4, 8, 'Volonté', 1),
(9, 4, 8, 'Intelligence', 1),
(8, 4, -1, 'Mental', 1),
(7, 4, -1, 'Physique', 1),
(12, 4, 7, 'Endurance', 1);

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
(8, 4);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fiche`
--

INSERT INTO `fiche` (`id`, `nom`, `description`, `idUser`) VALUES
(4, 'Ma super fiche', 'Une super fiche !', 8),
(5, 'Fiche Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a metus lacinia, gravida urna sit amet, dignissim dolor. Aenean iaculis felis eget massa dignissim fringilla. Sed interdum lorem odio, in fringilla ipsum vehicula ut. Ut sollicitudin scelerisque libero, sit amet laoreet velit ornare vestibulum. Fusce quis erat eget lorem vulputate mollis posuere in urna. Aliquam erat volutpat. Cras eget libero at purus venenatis luctus vitae non quam.', 8);

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `salt`) VALUES
(7, 'amesk', 'azerty@gmail.com', 'fe88defbf8fd208632e26c37055d2872de0f03575109dec5e0aaf4dc0fea06c55dffc7ebc12636957c897da9b601d267fd92d20d24ef93794dbec204ae7335a3', 0xa023fd6aeb71f7255bbea7810c98a295),
(8, 'Bob', 'bob@gmail.com', '1e68b7a95b5c174fe4f06329b2ae785f4b0bed10c8831b24547292d952f48467a6db8eb066c64b2feabbf8929bff2ae1d88202848370c069c431cf51812a721d', 0x469b88445d4ac69011998e49637adf12);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
