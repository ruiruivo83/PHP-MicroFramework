SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `php-tickets`

-- Table: `groups`
CREATE TABLE `groups` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `group_admin_id` INT NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `group_name` VARCHAR(255) NOT NULL,
  `group_description` VARCHAR(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `groups_members`
CREATE TABLE `group_members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `group_id` INT DEFAULT NULL,
  FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `groups_invitations`
CREATE TABLE `group_invitations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invitation_from_user_id` INT NOT NULL,
  `invitation_to_user_id` INT NOT NULL,
  `invitation_for_group_id` INT NOT NULL,
  `invitation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`invitation_for_group_id`) REFERENCES `groups`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `users`
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `password_reset_hash` VARCHAR(64) UNIQUE DEFAULT NULL,
  `password_reset_expires_at` DATETIME DEFAULT NULL,
  `activation_hash` VARCHAR(64) UNIQUE DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '0',
  `is_sys_admin` TINYINT(1) NOT NULL DEFAULT '0',
  `time_zone` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Déchargement des données de la table `users`
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_hash`, `is_active`, `time_zone`) VALUES
(18, 'Rui', 'ruivo.rui@gmail.com', '$2y$10$qFrdXEbLYsyY4YgBR.2u6uTYOPUQCZEiKknio2T2ZmD1gZOR7VWR6', NULL, NULL, NULL, 1, 'Europe/Paris'),
(20, 'Rui', 'ruivo.rui83@outlook.com', '$2y$10$WvfojWDfbbBPzuGS5LS1WO3Gh2bHY7Lr6qfjNMu5.gRWBeOHRUWNK', NULL, NULL, NULL, 1, 'Europe/Paris');


-- Table: `users_remembered_logins`
CREATE TABLE `user_remembered_login` (
  `token_hash` VARCHAR(64) NOT NULL PRIMARY KEY,
  `user_id` INT NOT NULL,
  `expires_at` DATETIME NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `tickets`
CREATE TABLE `tickets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_admin_id` INT NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `ticket_name` VARCHAR(255) NOT NULL,
  `ticket_description` VARCHAR(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `courses`
CREATE TABLE `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `course_sections`
CREATE TABLE `course_sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `course_chapters`
CREATE TABLE `course_chapter` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `section_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`),
  FOREIGN KEY (`section_id`) REFERENCES `courses_sections`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `course_payments`
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `payment_from_user_id` INT NOT NULL,
  `payment_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_expiration_date` TIMESTAMP NOT NULL,
  `is_read` TINYINT(1) NULL DEFAULT 0,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `course_chapter_video_url`
CREATE TABLE `course_chapter_video_url` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `chapter_id` INT,
  `video_url` VARCHAR(255),
  FOREIGN KEY (`chapter_id`) REFERENCES `courses_chapters`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: `course_chapter_file_url`
CREATE TABLE `course_chapter_file_url` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `chapter_id` INT,
  `file_url` VARCHAR(255),
  FOREIGN KEY (`chapter_id`) REFERENCES `courses_chapters`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE messages (
    id INT NOT NULL AUTO_INCREMENT,    
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    send_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    open_date TIMESTAMP NULL DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Assuming 'users' table exists and 'id' is the primary key
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (to_user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
