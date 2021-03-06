-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 28 avr. 2018 à 01:57
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `attribut`
--

INSERT INTO `attribut` (`id`, `idFiche`, `idAttributParent`, `nom`, `valeur`) VALUES
(11, 4, 7, 'Force', 1),
(10, 4, 8, 'Volonté', 1),
(9, 4, 8, 'Intelligence', 1),
(8, 4, -1, 'Mental', 1),
(7, 4, -1, 'Physique', 1),
(12, 4, 7, 'Endurance', 1),
(13, 6, -1, 'Implants Moteur', 1),
(14, 6, -1, 'Implants psychique', 1),
(15, 6, 13, 'Servo-moteur de jambe', 1),
(16, 6, 13, 'Biceps artificiels', 1),
(17, 6, 14, 'Réseau neuronal', 1),
(18, 6, 14, 'Antenne de communication', 1),
(19, 6, -1, 'Implant auxiliaire', 1),
(20, 6, 19, 'Œil Bionique', 1),
(21, 6, 19, 'Acuité auditive', 1),
(22, 7, -1, 'work in progress', 1),
(23, 9, -1, 'Chapeau', 1),
(24, 9, 23, 'Couleur', 1),
(25, 9, 23, 'Taille', 1),
(26, 9, -1, 'Costume', 1),
(27, 9, -1, 'Chaussures', 1),
(28, 9, 27, 'Lacets', 1),
(29, 9, 26, 'Cravate', 1);

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
(8, 4),
(7, 6),
(8, 6);

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fiche`
--

INSERT INTO `fiche` (`id`, `nom`, `description`, `idUser`) VALUES
(4, 'Ma super fiche', 'Une super fiche !', 8),
(5, 'Fiche Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a metus lacinia, gravida urna sit amet, dignissim dolor. Aenean iaculis felis eget massa dignissim fringilla. Sed interdum lorem odio, in fringilla ipsum vehicula ut. Ut sollicitudin scelerisque libero, sit amet laoreet velit ornare vestibulum. Fusce quis erat eget lorem vulputate mollis posuere in urna. Aliquam erat volutpat. Cras eget libero at purus venenatis luctus vitae non quam.', 8),
(6, 'Cyborg', 'Une bonne base de départ pour un cyborg !', 7),
(7, 'Fiche de Test', 'je sais pas quoi écrire comme description...', 8),
(9, 'Chapelier Fou', 'Fou mais gentil !', 9),
(10, 'Chat du Cheshire', 'Il est invisible !', 9);

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `salt`) VALUES
(7, 'amesk', 'azerty@gmail.com', 'fe88defbf8fd208632e26c37055d2872de0f03575109dec5e0aaf4dc0fea06c55dffc7ebc12636957c897da9b601d267fd92d20d24ef93794dbec204ae7335a3', 0xa023fd6aeb71f7255bbea7810c98a295),
(8, 'Bob', 'bob@gmail.com', '1e68b7a95b5c174fe4f06329b2ae785f4b0bed10c8831b24547292d952f48467a6db8eb066c64b2feabbf8929bff2ae1d88202848370c069c431cf51812a721d', 0x469b88445d4ac69011998e49637adf12),
(9, 'Alice', 'alice@gmail.com', '5c13389c27be758368f3e194a14cb9e18d16b1da1921d9bbf92cf71ed960f10baab7dc26e85ffa9f8e3752c7e395f73b013bc0ac7f16e4dcc7e79f86b3c7a1fa', 0x14dc6cae8eff3eec46698b268f6fa3af);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
