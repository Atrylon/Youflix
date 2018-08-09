-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 08 août 2018 à 21:01
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
-- Base de données :  `youflix`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:simple_array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `birthday`, `roles`) VALUES
(4, 'laura@gmail.com', '$2y$13$8YNr5XXqk3z7fxDL1rteMegNFhw3GL7QJZDuDPMh6A.5uyX7.zd7S', 'laura', 'menu', '1985-04-27 00:00:00', 'ROLE_USER'),
(5, 'berenger.desgardin@gmail.com', '$2y$13$Y9zL1gJD/ZuENhc47cJtSutg/ehGeiwjrG3FSxOWdH9/DyP4v4Bvi', 'Bérenger', 'Desgardin', '1993-01-10 00:00:00', 'ROLE_ADMIN, ROLE_USER'),
(20, 'test@test.com', '$2y$13$JhwqzG4KUYNrekmNqTkaXOGumnYTLxNTBk/1NRwqGPf1o2Sr3INOO', NULL, NULL, '1898-01-01 00:00:00', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CC7DA2CA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `user_id`, `title`, `url`, `description`) VALUES
(3, 4, 'Top Vine FR !', 'https://www.youtube.com/watch?v=xFPvfrutFAY', 'MEILLEURS VINES & INSTAGRAM FRANÇAIS - Compilation n°5'),
(6, 4, 'Wiiiiiiiiii', 'https://www.youtube.com/watch?v=U_Zz-q1wn-I', 'Un escargot'),
(7, 5, 'Bande Annonce Aquaman', 'https://www.youtube.com/watch?v=150q5Qn4uNA', 'AQUAMAN Bande Annonce VF'),
(12, 5, 'Découverte K-Pop', 'https://www.youtube.com/watch?v=ZAPyikoKd30', 'Je fais découvrir la K-Pop à Tev !'),
(13, 5, 'WoW BFA trailer', 'https://www.youtube.com/watch?v=jSJr3dXZfcg', 'Tensions between the Alliance and Horde have erupted, and a new age of war has begun. For more info on the game, or to opt in to the beta, head to http://worldofwarcraft.com.'),
(23, 5, 'Old soldier', 'https://www.youtube.com/watch?v=aW_h0qf9vpA', 'War has a way of wearing down the most weathered soldiers. For legendary Horde warrior Varok Saurfang, this one could very well be his last.'),
(24, 5, 'Old soldier', 'https://www.youtube.com/watch?v=aW_h0qf9vpA', 'War has a way of wearing down the most weathered soldiers. For legendary Horde warrior Varok Saurfang, this one could very well be his last.'),
(25, 5, 'Old soldier', 'https://www.youtube.com/watch?v=aW_h0qf9vpA', 'War has a way of wearing'),
(26, 5, 'World of Warcraft #BFA : Patch 8.0, Geass de retour !', 'https://www.youtube.com/watch?v=3GLJaTz0wPA', '2eme soirée Soirée World of Warcraft avec comme thèmes : - le scenario partie 1 & 2 Alliance ! Je suis avec Geass, joueur World of Warcraft chez Wait for it -- Watch live at https://www.twitch.tv/w_lapin'),
(27, 20, 'Des nouvelles montures !!!', 'https://www.youtube.com/watch?v=Y-ZMDLx8j4I', 'Lots of new mounts in the Battle for Azeroth. Here\'s a preview of the 58 we know of so far. If you have any suggestions, requests, or just general feedback let me know in the comments or with a message. I try my best to answer as many as I can.'),
(32, 20, 'BFA Vol\'dun', 'https://www.youtube.com/watch?v=J9-m0H6F7Dw', 'Autrefois recouverte par une jungle verdoyante et ancien joyau de l’empire troll, la région de Vol’dun n’est aujourd’hui plus qu’une étendue aride où les exilés de Zuldazar finissent leurs jours.');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
