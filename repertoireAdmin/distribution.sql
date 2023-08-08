-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 02 août 2023 à 06:36
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `distribution`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `accesOrdi` tinyint(1) NOT NULL,
  `accesTelephone` tinyint(1) NOT NULL,
  `accesAjout` tinyint(1) NOT NULL,
  `accesSalarie` tinyint(1) NOT NULL,
  `accesTablette` tinyint(1) NOT NULL,
  `accesImprimante` tinyint(1) NOT NULL,
  `accesDemande` tinyint(1) NOT NULL,
  `accesLogiciel` tinyint(1) NOT NULL,
  `accesInsert` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `utilisateur`, `mdp`, `accesOrdi`, `accesTelephone`, `accesAjout`, `accesSalarie`, `accesTablette`, `accesImprimante`, `accesDemande`, `accesLogiciel`, `accesInsert`) VALUES
(1, 'matteo', '150be5b860e60a7fc7c7d9b9815e93d1', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(7, 'test', '098f6bcd4621d373cade4e832627b4f6', 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ville` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id`, `ville`) VALUES
(1, 'Les Sables');

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `date_resolu` date DEFAULT NULL,
  `resolu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id`, `nom`, `prenom`, `type`, `commentaire`, `date_resolu`, `resolu`) VALUES
(1, 'Docuware', 'Emmanuel', 'materiel', 'zeze', '2023-07-31', 1),
(2, 'br', 'mz', 'materiel', 'test\r\n', '2023-07-24', 1),
(3, 'br', 'mz', 'materiel', 'test\r\n', '2023-07-31', 1),
(4, 'STOCK', 'zer', 'materiel', 'zer', NULL, 0),
(5, 'e', 'e', 'tablette', 'e', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `imprimante`
--

DROP TABLE IF EXISTS `imprimante`;
CREATE TABLE IF NOT EXISTS `imprimante` (
  `numero_serie` varchar(200) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `modele` varchar(255) NOT NULL,
  `id_salarie` int NOT NULL,
  PRIMARY KEY (`numero_serie`),
  KEY `id_salarie` (`id_salarie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `imprimante`
--

INSERT INTO `imprimante` (`numero_serie`, `marque`, `ip`, `modele`, `id_salarie`) VALUES
('ezae', 'aez', 'aez', 'azea', 8);

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `licence`
--

INSERT INTO `licence` (`id`, `nom`) VALUES
(1, 'Basic'),
(2, 'Premium'),
(3, 'PROPLUS');

-- --------------------------------------------------------

--
-- Structure de la table `logiciel`
--

DROP TABLE IF EXISTS `logiciel`;
CREATE TABLE IF NOT EXISTS `logiciel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `logiciel`
--

INSERT INTO `logiciel` (`id`, `nom`) VALUES
(1, 'Docuware');

-- --------------------------------------------------------

--
-- Structure de la table `ordinateur`
--

DROP TABLE IF EXISTS `ordinateur`;
CREATE TABLE IF NOT EXISTS `ordinateur` (
  `numero_serie` varchar(200) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code_compta` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `status` int NOT NULL,
  `id_salarie` int NOT NULL,
  PRIMARY KEY (`numero_serie`),
  KEY `status` (`status`),
  KEY `fk_ordinateur_salarie` (`id_salarie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ordinateur`
--

INSERT INTO `ordinateur` (`numero_serie`, `nom`, `code_compta`, `designation`, `marque`, `status`, `id_salarie`) VALUES
('rez', 'zr', 'zer', 'zer', 'zer', 0, 1),
('test', 'tes', 'PO2023-002', 'tes', 'tes', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `salarie`
--

DROP TABLE IF EXISTS `salarie`;
CREATE TABLE IF NOT EXISTS `salarie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `id_agence` int NOT NULL,
  `id_licence` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_agence` (`id_agence`),
  KEY `id_licence` (`id_licence`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `salarie`
--

INSERT INTO `salarie` (`id`, `nom`, `prenom`, `id_agence`, `id_licence`) VALUES
(1, 'test', 'test', 1, 1),
(8, 'STOCK', 'LS', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `salarie_logiciel`
--

DROP TABLE IF EXISTS `salarie_logiciel`;
CREATE TABLE IF NOT EXISTS `salarie_logiciel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_salarie` int NOT NULL,
  `id_logiciel` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_salarie` (`id_salarie`),
  KEY `id_logiciel` (`id_logiciel`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tablette`
--

DROP TABLE IF EXISTS `tablette`;
CREATE TABLE IF NOT EXISTS `tablette` (
  `numero_serie` varchar(200) NOT NULL,
  `type` varchar(255) NOT NULL,
  `code_compta` varchar(255) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `id_salarie` int NOT NULL,
  PRIMARY KEY (`numero_serie`),
  KEY `id_salarie` (`id_salarie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tablette`
--

INSERT INTO `tablette` (`numero_serie`, `type`, `code_compta`, `marque`, `id_salarie`) VALUES
('1', 'A16Se', 'TA2023-001', 'OPPO', 1);

-- --------------------------------------------------------

--
-- Structure de la table `telephone`
--

DROP TABLE IF EXISTS `telephone`;
CREATE TABLE IF NOT EXISTS `telephone` (
  `numero_serie` varchar(200) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `code_compta` varchar(255) NOT NULL,
  `ntelephone` int NOT NULL,
  `ncarte` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `id_salarie` int NOT NULL,
  PRIMARY KEY (`numero_serie`),
  KEY `id_salarie` (`id_salarie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `telephone`
--

INSERT INTO `telephone` (`numero_serie`, `marque`, `code_compta`, `ntelephone`, `ncarte`, `type`, `id_salarie`) VALUES
('tst', 'tes', 'TEL2023-001', 162, 'test', 'teste', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `imprimante`
--
ALTER TABLE `imprimante`
  ADD CONSTRAINT `fk_imprimante_salarie` FOREIGN KEY (`id_salarie`) REFERENCES `salarie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  ADD CONSTRAINT `fk_ordinateur_salarie` FOREIGN KEY (`id_salarie`) REFERENCES `salarie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `salarie`
--
ALTER TABLE `salarie`
  ADD CONSTRAINT `fk_salarie_agence` FOREIGN KEY (`id_agence`) REFERENCES `agence` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_salarie_licence` FOREIGN KEY (`id_licence`) REFERENCES `licence` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `salarie_logiciel`
--
ALTER TABLE `salarie_logiciel`
  ADD CONSTRAINT `fk_salarie_logiciel_logiciel` FOREIGN KEY (`id_logiciel`) REFERENCES `logiciel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_salarie_logiciel_salarie` FOREIGN KEY (`id_salarie`) REFERENCES `salarie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tablette`
--
ALTER TABLE `tablette`
  ADD CONSTRAINT `fk_tablette_salarie` FOREIGN KEY (`id_salarie`) REFERENCES `salarie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `telephone`
--
ALTER TABLE `telephone`
  ADD CONSTRAINT `fk_telephone_salarie` FOREIGN KEY (`id_salarie`) REFERENCES `salarie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
