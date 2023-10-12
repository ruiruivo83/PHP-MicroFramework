SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Déchargement des données de la table `tickets`
--
INSERT INTO `tickets` (`ticket_admin_id`, `creation_date`, `ticket_name`, `ticket_description`) VALUES
( 20, '2023-07-27 21:22:11', 'ticket name', 'ticket description'),
( 20, '2023-07-27 21:22:23', 'ticket 2', 'description 02'),
( 20, '2023-07-27 21:26:04', 'Tiecket 03', 'Ticket 03');
