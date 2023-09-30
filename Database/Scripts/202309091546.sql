-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 07 sep. 2023 à 06:35
-- Version du serveur : 8.0.31
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `php-tickets`
--

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--
CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_admin_id` int NOT NULL,
  `creation_date` datetime NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group_description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_admin_id` (`group_admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `groups`
--
INSERT INTO `groups` (`id`, `group_admin_id`, `creation_date`, `group_name`, `group_description`) VALUES
(9, 20, '2023-07-27 16:57:21', 'dfshjjhk', 'lghlhjlhkl'),
(10, 20, '2023-07-27 19:02:08', 'bdfhvhlhbj:', 'vk,cfghnhgdn'),
(11, 20, '2023-07-27 19:02:20', 'xcvbnfg', 'gfchj,nfgh,f,'),
(12, 18, '2023-09-02 16:08:03', 'test group', 'group description');

-- --------------------------------------------------------

--
-- Structure de la table `group_members`
--
CREATE TABLE `group_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--
CREATE TABLE `invitations` (
  `id` int NOT NULL,
  `invitation_from_user_id` int NOT NULL,
  `invitation_to_user_id` int NOT NULL,
  `invitation_for_group_id` int NOT NULL,
  `invitation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `remembered_logins`
--
CREATE TABLE `remembered_logins` (
  `token_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`token_hash`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--
CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_admin_id` int NOT NULL,
  `creation_date` datetime NOT NULL,
  `ticket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ticket_description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--
INSERT INTO `tickets` (`id`, `ticket_admin_id`, `creation_date`, `ticket_name`, `ticket_description`) VALUES
(1, 20, '2023-07-27 21:22:11', 'ticket name', 'ticket description'),
(2, 20, '2023-07-27 21:22:23', 'ticket 2', 'description 02'),
(3, 20, '2023-07-27 21:26:04', 'Tiecket 03', 'Ticket 03');

-- --------------------------------------------------------

-- Structure de la table `users`
CREATE TABLE `users` (
 `id` int NOT NULL AUTO_INCREMENT,
 `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `password_reset_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `password_reset_expires_at` datetime DEFAULT NULL,
 `activation_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `is_active` tinyint(1) NOT NULL DEFAULT '0',
 `is_sys_admin` tinyint(1) NOT NULL DEFAULT '0',  -- New column
 `time_zone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
 UNIQUE KEY `activation_hash` (`activation_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `users`
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_hash`, `is_active`, `time_zone`) VALUES
(18, 'Rui', 'ruivo.rui@gmail.com', '$2y$10$qFrdXEbLYsyY4YgBR.2u6uTYOPUQCZEiKknio2T2ZmD1gZOR7VWR6', NULL, NULL, NULL, 1, 'Europe/Paris'),
(20, 'Rui', 'ruivo.rui83@outlook.com', '$2y$10$WvfojWDfbbBPzuGS5LS1WO3Gh2bHY7Lr6qfjNMu5.gRWBeOHRUWNK', NULL, NULL, NULL, 1, 'Europe/Paris');



