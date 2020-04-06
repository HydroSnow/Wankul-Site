-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  ven. 08 nov. 2019 à 08:52
-- Version du serveur :  10.4.8-MariaDB
-- Version de PHP :  7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wankul`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `fromage` int(11) DEFAULT NULL,
  `utilisateur` int(11) DEFAULT NULL,
  `note` int(11) NOT NULL,
  `texte` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `fromage`, `utilisateur`, `note`, `texte`) VALUES
(1, 14, 3, 5, 'Je ne m\'appelle pas Chantal, mais j\'aime le cantal !'),
(2, 46, 2, 2, 'Du fromage frais ? Très peu pour moi.');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `id_utilisateur`, `date`) VALUES
(1, 1, '2019-11-05 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `detailcommande`
--

CREATE TABLE `detailcommande` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_fromage` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `detailcommande`
--

INSERT INTO `detailcommande` (`id`, `id_commande`, `id_fromage`, `quantite`) VALUES
(1, 1, 8, 1);

-- --------------------------------------------------------

--
-- Structure de la table `fromage`
--

CREATE TABLE `fromage` (
  `id` int(11) NOT NULL,
  `nom` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `origine` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `lait` int(11) NOT NULL,
  `img` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prix` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `fromage`
--

INSERT INTO `fromage` (`id`, `nom`, `origine`, `type`, `lait`, `img`, `prix`) VALUES
(2, 'Brie de Meaux', 'Bassin parisien', 1, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Wikicheese_-_Brie_de_Meaux_-_20150515_-_023.jpg/800px-Wikicheese_-_Brie_de_Meaux_-_20150515_-_023.jpg', 16.86),
(3, 'Brie de Melun', 'Bassin parisien', 1, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Wikicheese_-_Brie_de_Melun_-_20150515_-_015.jpg/800px-Wikicheese_-_Brie_de_Melun_-_20150515_-_015.jpg', 10.41),
(4, 'Camembert', 'Normandie', 1, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Camembert_de_Normandie_%28AOP%29_02.jpg/800px-Camembert_de_Normandie_%28AOP%29_02.jpg', 1.48),
(5, 'Chaource', 'Champagne', 1, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Chaource_%28fromage%29_01.jpg/800px-Chaource_%28fromage%29_01.jpg', 76.17),
(6, 'Neufchâtel', 'Normandie', 1, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/C%C5%93ur_de_Neufch%C3%A2tel_01.jpg/800px-C%C5%93ur_de_Neufch%C3%A2tel_01.jpg', 76.39),
(7, 'Livarot', 'Normandie', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Livarot_%28fromage%29_02.jpg/800px-Livarot_%28fromage%29_02.jpg', 53.46),
(8, 'Epoisses', 'Bourgogne', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/%C3%89poisses_Gaugry_03.jpg/800px-%C3%89poisses_Gaugry_03.jpg', 38.11),
(9, 'Maroilles', 'Nord-Pas-de-Calais', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/WikiCheese_-_Maroilles_-_20150619_-_001.jpg/800px-WikiCheese_-_Maroilles_-_20150619_-_001.jpg', 30.2),
(10, 'Munster', 'Alsace', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Munster_01.jpg/800px-Munster_01.jpg', 36.66),
(11, 'Pont l’Evêque', 'Normandie', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Pont-l%27%C3%89v%C3%AAque_03.jpg/800px-Pont-l%27%C3%89v%C3%AAque_03.jpg', 92.7),
(12, 'Mont d\'Or', 'Franche Comté', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Vacherin_du_haut_Doubs.jpg/800px-Vacherin_du_haut_Doubs.jpg', 53.5),
(13, 'Langres', 'Champagne', 2, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Langres_%C3%A0_la_coupe_02.jpg/800px-Langres_%C3%A0_la_coupe_02.jpg', 89.43),
(14, 'Cantal', 'Auvergne ou Limousin', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Cantal_02.jpg/800px-Cantal_02.jpg', 86.65),
(15, 'Laguiole', 'Auvergne, Languedoc ou Midi-Pyrénées', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Laguiole_%28fromage%29_01.jpg/800px-Laguiole_%28fromage%29_01.jpg', 64.95),
(16, 'Saint-Nectaire', 'Auvergne', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Saint-nectaire_01.jpg/800px-Saint-nectaire_01.jpg', 64.8),
(17, 'Salers', 'Cantal', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Salers_%28fromage%29_01.jpg/800px-Salers_%28fromage%29_01.jpg', 29.15),
(18, 'Reblochon', 'Rhône-Alpes', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Reblochon_11.jpg/800px-Reblochon_11.jpg', 51.37),
(19, 'Morbier', 'Franche-Comté', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Morbier_%28fromage%29_01.jpg/800px-Morbier_%28fromage%29_01.jpg', 69.37),
(20, 'Tome des Bauges', 'Savoie', 3, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/WikiCheese_-_Tome_des_Bauges_-_20150619_-_001.jpg/800px-WikiCheese_-_Tome_des_Bauges_-_20150619_-_001.jpg', 92.74),
(21, 'Ossau-Iraty', 'Pays Basque', 3, 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Ossau-iraty_01.jpg/800px-Ossau-iraty_01.jpg', 55.61),
(22, 'Beaufort', 'Savoie', 4, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Beaufort_%28fromage%29_01.jpg/800px-Beaufort_%28fromage%29_01.jpg', 99.81),
(23, 'Comté', 'Franche-Comté', 4, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Comt%C3%A9_02.jpg/800px-Comt%C3%A9_02.jpg', 32.22),
(24, 'Abondance', 'Savoie', 4, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Abondance_%28fromage%29_01.jpg/800px-Abondance_%28fromage%29_01.jpg', 61.66),
(25, 'Gruyère', 'Franche-Comté', 4, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Cheese-gruy%C3%A8re-IGP.jpg/800px-Cheese-gruy%C3%A8re-IGP.jpg', 11.66),
(26, 'Bleu d’Auvergne', 'Auvergne', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Bleu_d%27Auvergne_01.jpg/800px-Bleu_d%27Auvergne_01.jpg', 73.33),
(27, 'Bleu des Causses', 'Aveyron', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f7/WikiCheese_-_Bleu_des_Causses_-_20150619_-_001.jpg/800px-WikiCheese_-_Bleu_des_Causses_-_20150619_-_001.jpg', 31.64),
(28, 'Bleu de Gex', 'Franche-Comté', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/WikiCheese_-_Bleu_de_Gex_01.jpg/800px-WikiCheese_-_Bleu_de_Gex_01.jpg', 38.23),
(29, 'Bleu du Vercors-Sassenage', 'Dauphiné Vercors', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/WikiCheese_-_Bleu_du_Vercors_02.jpg/800px-WikiCheese_-_Bleu_du_Vercors_02.jpg', 96.22),
(30, 'Fourme d’Ambert', 'Auvergne', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Fourme_d%27Ambert_02.jpg/800px-Fourme_d%27Ambert_02.jpg', 66.42),
(31, 'Fourme de Montbrison', 'Auvergne', 5, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/80/Wikicheese_-_Fourme_de_Montbrison_-_20151024_-_012.jpg/800px-Wikicheese_-_Fourme_de_Montbrison_-_20151024_-_012.jpg', 43.43),
(32, 'Roquefort', 'Aveyron', 5, 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Wikicheese_-_Roquefort_-_20150417_-_003.jpg/800px-Wikicheese_-_Roquefort_-_20150417_-_003.jpg', 17.9),
(33, 'Chabichou du Poitou', 'Poitou-Charentes', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Chabichou_du_Poitou_01.jpg/800px-Chabichou_du_Poitou_01.jpg', 59.22),
(34, 'Charolais', 'Bourgogne', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Fromage_charolais.jpg/1024px-Fromage_charolais.jpg', 42.39),
(35, 'Chevrotin', 'Savoie', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Chevrotin_de_Savoie.jpg/800px-Chevrotin_de_Savoie.jpg', 34.29),
(36, 'Crottin de Chavignol', 'Berry', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Crottin_02.jpg/800px-Crottin_02.jpg', 44.29),
(37, 'Pélardon ', 'Languedoc-Roussillon', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8b/P%C3%A9lardon_01.jpg/800px-P%C3%A9lardon_01.jpg', 18.59),
(38, 'Picodon', 'Drôme et Ardèche', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Wikicheese_-_Picodon_-_20150417_-_006.jpg/800px-Wikicheese_-_Picodon_-_20150417_-_006.jpg', 60.05),
(39, 'Pouligny Saint-Pierre', 'Berry', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Pouligny-saint-pierre_%28fromage%29_02.jpg/800px-Pouligny-saint-pierre_%28fromage%29_02.jpg', 44.51),
(40, 'Rocamadour', 'Quercy', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Rocamadour_%28fromage%29_01.jpg/800px-Rocamadour_%28fromage%29_01.jpg', 42.39),
(41, 'Sainte-Maure de Touraine', 'Touraine', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/Sainte-Maure_de_touraine_08.jpg/800px-Sainte-Maure_de_touraine_08.jpg', 78.41),
(42, 'Selles sur Cher', 'Centre', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Selles-sur-cher_01.jpg/800px-Selles-sur-cher_01.jpg', 64.9),
(43, 'Valençay', 'Centre', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Valen%C3%A7ay_04.jpg/800px-Valen%C3%A7ay_04.jpg', 89.25),
(44, 'Mâconnais', 'Bourgogne', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Wikicheese_-_M%C3%A2connais_-_20150417_-_003.jpg/800px-Wikicheese_-_M%C3%A2connais_-_20150417_-_003.jpg', 51.54),
(45, 'Banon', 'Provence-Alpes-Côte d\'Azur', 6, 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Banon_01.jpg/800px-Banon_01.jpg', 89.96),
(46, 'Brocciu', 'Corse', 7, 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Wikicheese_-_Brocciu_Frescu_-_20150417_-_002.jpg/800px-Wikicheese_-_Brocciu_Frescu_-_20150417_-_002.jpg', 95.18);

-- --------------------------------------------------------

--
-- Structure de la table `lait`
--

CREATE TABLE `lait` (
  `id` int(11) NOT NULL,
  `nom` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `lait`
--

INSERT INTO `lait` (`id`, `nom`) VALUES
(1, 'Lait de vache'),
(2, 'Lait de chèvre'),
(3, 'Lait de brebis');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nom` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `nom`) VALUES
(1, 'Administrateur'),
(2, 'Fournisseur'),
(3, 'Client');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `nom` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id`, `nom`) VALUES
(1, 'Pâtes molles à croûte fleurie'),
(2, 'Pâtes molles à croûte lavée'),
(3, 'Pâtes pressées non cuites'),
(4, 'Pâtes pressées cuites'),
(5, 'Pâtes persillées'),
(6, 'Chèvres'),
(7, 'Fromages frais');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `mdp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cb` int(16) DEFAULT NULL,
  `moisExpi` int(2) DEFAULT NULL,
  `anneeExpi` int(2) DEFAULT NULL,
  `cryptogramme` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `role`, `mdp`, `cb`, `moisExpi`, `anneeExpi`, `cryptogramme`) VALUES
(1, 'titos', 1, '$argon2id$v=19$m=65536,t=4,p=1$N00wVmYwWUUzV2lpU3lzWA$GuHv4CE9bWaEfQrNgbsuXD9dbJYJIG/8w5MEHTwaagw', NULL, NULL, NULL, NULL),
(2, 'trupin', 1, '$argon2id$v=19$m=65536,t=4,p=1$dy42QTZueC5iZno1ck9Bbg$NRBjnV5TVUGWrTPq/Dry7IbbLcZJXBGrOb5SnSevVLw', NULL, NULL, NULL, NULL),
(3, 'dark_kirito_69', 3, '$argon2id$v=19$m=65536,t=4,p=1$NGhIMnRMZDZrLjF1ZzdDYg$e3cNlr4d14lrTsHk/tIXXySjtxnGyo4nXAvIdq4RP7I', NULL, NULL, NULL, NULL),
(4, 'lactalis', 2, '$argon2id$v=19$m=65536,t=4,p=1$RVJOckRLWmNUWnBiLnFteQ$B7U7MTpvS3T2JfHPo6tkXr36p+L0HpASNrN5Zn0Ge4E', NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fromage` (`fromage`) USING BTREE,
  ADD KEY `utilisateur` (`utilisateur`) USING BTREE;

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur_fkey` (`id_utilisateur`);

--
-- Index pour la table `detailcommande`
--
ALTER TABLE `detailcommande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id_fromage_fkey` (`id_fromage`),
  ADD KEY `detail_id_command_fkey` (`id_commande`);

--
-- Index pour la table `fromage`
--
ALTER TABLE `fromage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `lait` (`lait`) USING BTREE;

--
-- Index pour la table `lait`
--
ALTER TABLE `lait`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`) USING BTREE,
  ADD KEY `role` (`role`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `detailcommande`
--
ALTER TABLE `detailcommande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fromage`
--
ALTER TABLE `fromage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `lait`
--
ALTER TABLE `lait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fromage_avis` FOREIGN KEY (`fromage`) REFERENCES `fromage` (`id`),
  ADD CONSTRAINT `utilisateur_avis` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `id_utilisateur_fkey` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `detailcommande`
--
ALTER TABLE `detailcommande`
  ADD CONSTRAINT `detail_id_command_fkey` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `detail_id_fromage_fkey` FOREIGN KEY (`id_fromage`) REFERENCES `fromage` (`id`);

--
-- Contraintes pour la table `fromage`
--
ALTER TABLE `fromage`
  ADD CONSTRAINT `lait_fromage` FOREIGN KEY (`lait`) REFERENCES `lait` (`id`),
  ADD CONSTRAINT `type_fromage` FOREIGN KEY (`type`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `type_utilisateur` FOREIGN KEY (`role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
