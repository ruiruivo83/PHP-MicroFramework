SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE chapter_video_url (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chapter_id INT,
    video_url VARCHAR(255),
    FOREIGN KEY (chapter_id) REFERENCES chapters(id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
