-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 14 mai 2025 à 08:56
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boutique_en_ligne`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom`) VALUES
(1, 'Parfums'),
(2, 'Coffrets');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `date_commande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_attente','validee','annulee') DEFAULT 'en_attente',
  PRIMARY KEY (`id_commande`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_utilisateur`, `date_commande`, `statut`) VALUES
(1, 3, '2025-05-13 12:33:11', ''),
(2, 3, '2025-05-13 12:33:24', ''),
(3, 3, '2025-05-13 12:40:29', ''),
(4, 3, '2025-05-13 12:42:55', ''),
(5, 3, '2025-05-13 13:59:12', '');

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

DROP TABLE IF EXISTS `detail_commande`;
CREATE TABLE IF NOT EXISTS `detail_commande` (
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_commande`,`id_produit`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
(2, 1, 1, 49.99),
(2, 3, 1, 59.99),
(3, 6, 1, 34.99),
(4, 5, 1, 29.99),
(5, 30, 1, 129.99);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_panier`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_sous_categorie` int DEFAULT NULL,
  `id_categorie` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_produit`),
  KEY `id_sous_categorie` (`id_sous_categorie`),
  KEY `fk_produit_categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom`, `description`, `prix`, `image`, `stock`, `date_ajout`, `id_sous_categorie`, `id_categorie`) VALUES
(1, 'Eau de Toilette Homme', 'Un parfum frais et boisé pour hommes.', 49.99, 'eau_de_toilette_homme.jpg', 50, '2025-04-30 09:33:35', 1, 1),
(2, 'Parfum Intense Homme', 'Un parfum intense et sophistiqué pour hommes.', 69.99, 'parfum_intense_homme.jpg', 30, '2025-04-30 09:33:35', 1, 1),
(3, 'Eau de Parfum Femme', 'Un parfum floral et élégant pour femmes.', 59.99, 'eau_de_parfum_femme.jpg', 40, '2025-04-30 09:33:35', 2, 1),
(4, 'Parfum Luxe Femme', 'Un parfum de luxe avec des notes fruitées.', 89.99, 'parfum_luxe_femme.jpg', 20, '2025-04-30 09:33:35', 2, 1),
(5, 'Eau de Cologne Enfant', 'Un parfum doux et léger pour enfants.', 29.99, 'eau_de_cologne_enfant.jpg', 60, '2025-04-30 09:33:35', 3, 1),
(6, 'Parfum Fruité Enfant', 'Un parfum fruité et amusant pour enfants.', 34.99, 'parfum_fruite_enfant.jpg', 50, '2025-04-30 09:33:35', 3, 1),
(7, 'Coffret Cadeau Homme', 'Un coffret contenant un parfum et un gel douche.', 79.99, 'coffret_cadeau_homme.jpg', 25, '2025-04-30 09:33:35', 4, 1),
(8, 'Coffret Luxe Homme', 'Un coffret de luxe avec parfum et accessoires.', 119.99, 'coffret_luxe_homme.jpg', 15, '2025-04-30 09:33:35', 4, 1),
(9, 'Coffret Cadeau Femme', 'Un coffret contenant un parfum et une crème.', 89.99, 'coffret_cadeau_femme.jpg', 20, '2025-04-30 09:33:35', 5, 1),
(10, 'Coffret Luxe Femme', 'Un coffret de luxe avec parfum et accessoires.', 129.99, 'coffret_luxe_femme.jpg', 10, '2025-04-30 09:33:35', 5, 1),
(11, 'Coffret Parfum Enfant', 'Un coffret contenant un parfum et un jouet.', 49.99, 'coffret_parfum_enfant.jpg', 30, '2025-04-30 09:33:35', 6, 1),
(12, 'Coffret Cadeau Enfant', 'Un coffret amusant avec parfum et accessoires.', 59.99, 'coffret_cadeau_enfant.jpg', 25, '2025-04-30 09:33:35', 6, 1),
(26, 'Dior Sauvage', 'Un parfum iconique avec des notes fraîches et boisées.', 89.99, 'dior_sauvage.jpg', 50, '2025-05-13 15:43:07', 1, 1),
(27, 'Bleu de Chanel', 'Un parfum élégant et intemporel pour hommes.', 99.99, 'bleu_de_chanel.jpg', 40, '2025-05-13 15:43:07', 1, 1),
(28, 'Armani Code', 'Un parfum sophistiqué avec des notes orientales.', 79.99, 'armani_code.jpg', 30, '2025-05-13 15:43:07', 1, 1),
(29, 'Paco Rabanne Invictus', 'Un parfum énergisant et audacieux.', 84.99, 'paco_rabanne_invictus.jpg', 60, '2025-05-13 15:43:07', 1, 1),
(30, 'Chanel N°5', 'Un parfum classique et intemporel pour femmes.', 129.99, 'chanel_no5.jpg', 20, '2025-05-13 15:43:07', 2, 1),
(31, 'Lancôme La Vie Est Belle', 'Un parfum floral et gourmand.', 99.99, 'lancome_la_vie_est_belle.jpg', 35, '2025-05-13 15:43:07', 2, 1),
(32, 'Yves Saint Laurent Black Opium', 'Un parfum audacieux et sensuel.', 89.99, 'ysl_black_opium.jpg', 25, '2025-05-13 15:43:07', 2, 1),
(33, 'Dior J\'adore', 'Un parfum floral et raffiné.', 119.99, 'dior_jadore.jpg', 30, '2025-05-13 15:43:07', 2, 1),
(34, 'Disney Frozen Eau de Toilette', 'Un parfum doux et amusant pour enfants.', 24.99, 'disney_frozen.jpg', 50, '2025-05-13 15:43:07', 3, 1),
(35, 'Spiderman Eau de Cologne', 'Un parfum frais pour les petits héros.', 19.99, 'spiderman_cologne.jpg', 40, '2025-05-13 15:43:07', 3, 1),
(36, 'Coffret Dior Homme', 'Un coffret élégant avec un parfum et un gel douche.', 149.99, 'coffret_dior_homme.jpg', 15, '2025-05-13 15:43:07', 4, 1),
(37, 'Coffret Bleu de Chanel', 'Un coffret de luxe avec un parfum et un déodorant.', 169.99, 'coffret_bleu_de_chanel.jpg', 10, '2025-05-13 15:43:07', 4, 1),
(38, 'Coffret Chanel N°5', 'Un coffret exclusif avec un parfum et une crème.', 199.99, 'coffret_chanel_no5.jpg', 8, '2025-05-13 15:43:07', 5, 1),
(39, 'Coffret Lancôme La Vie Est Belle', 'Un coffret avec un parfum et une lotion.', 149.99, 'coffret_lancome_la_vie_est_belle.jpg', 12, '2025-05-13 15:43:07', 5, 1),
(40, 'Coffret Disney Princess', 'Un coffret amusant avec un parfum et des accessoires.', 39.99, 'coffret_disney_princess.jpg', 20, '2025-05-13 15:43:07', 6, 1),
(41, 'Coffret Avengers', 'Un coffret avec un parfum et un jouet.', 44.99, 'coffret_avengers.jpg', 25, '2025-05-13 15:43:07', 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `sous_categorie`
--

DROP TABLE IF EXISTS `sous_categorie`;
CREATE TABLE IF NOT EXISTS `sous_categorie` (
  `id_sous_categorie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `id_categorie` int DEFAULT NULL,
  PRIMARY KEY (`id_sous_categorie`),
  KEY `id_categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sous_categorie`
--

INSERT INTO `sous_categorie` (`id_sous_categorie`, `nom`, `id_categorie`) VALUES
(1, 'Hommes', 1),
(2, 'Femmes', 1),
(3, 'Enfants', 1),
(4, 'Coffrets Hommes', 2),
(5, 'Coffrets Femmes', 2),
(6, 'Coffrets Enfants', 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(60) DEFAULT NULL,
  `prenom` varchar(60) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `adresse`, `role`) VALUES
(3, 'HEUREUX', 'AXEL', 'axel-heureux@laplateforme.io', '$2y$10$vyq6rr7NsL9E.TBSVMjweOT5pfi8Yg9GrPA5InZrBv/FKHaE3yUbO', 'CHEMIN DE LA CROIX MARTIGUES 13800', 'admin'),
(4, 'MOGROVEJO', 'Justin', 'justin-morgrovejo@laplateforme.io', '$2y$10$Lvk9PzPMsC0WFUMW/QPELelr8bnmfJ5HypUKhqJoGUh1.QMDgAvs6', 'ISTRES 13800', 'client'),
(6, 'SORRENTINO', 'GIANNI', 'gianni.sorrentino@laplateforme.io', '$2y$10$wVdztMfNp/AZbusuDsrLPOR9NKBPm6HbaUuiU0i249EYQeGAYVhF6', 'boulevard des soucis', 'client');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL;

--
-- Contraintes pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `detail_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE,
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_sous_categorie`) REFERENCES `sous_categorie` (`id_sous_categorie`) ON DELETE SET NULL;

--
-- Contraintes pour la table `sous_categorie`
--
ALTER TABLE `sous_categorie`
  ADD CONSTRAINT `sous_categorie_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
