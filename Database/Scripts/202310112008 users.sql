SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Table structure for `users`
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_reset_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activation_expiration_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_sys_admin` tinyint(1) NOT NULL DEFAULT '0',
  `time_zone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
  UNIQUE KEY `activation_hash` (`activation_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Déchargement des données de la table `users`
INSERT INTO `users` ( `name`, `email`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_hash`, '', `is_active`,`is_sys_admin`, `time_zone`) VALUES
( 'Rui', 'ruivo.rui@gmail.com', '$2y$10$qFrdXEbLYsyY4YgBR.2u6uTYOPUQCZEiKknio2T2ZmD1gZOR7VWR6', NULL, NULL, NULL, 1, 1, 'Europe/Paris'),
( 'Rui', 'ruivo.rui83@outlook.com', '$2y$10$WvfojWDfbbBPzuGS5LS1WO3Gh2bHY7Lr6qfjNMu5.gRWBeOHRUWNK', NULL, NULL, NULL, 1, 1, 'Europe/Paris');
