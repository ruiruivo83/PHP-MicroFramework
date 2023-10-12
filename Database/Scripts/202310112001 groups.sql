SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;

-- Table structure for `groups`
CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_admin_id` int NOT NULL,
  `creation_date` datetime NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group_description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_admin_id` (`group_admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
