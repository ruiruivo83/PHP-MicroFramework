SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;


-- Table structure for `invitations`
CREATE TABLE `invitations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invitation_from_user_id` int NOT NULL,
  `invitation_to_user_id` int NOT NULL,
  `invitation_for_group_id` int NOT NULL,
  `invitation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

