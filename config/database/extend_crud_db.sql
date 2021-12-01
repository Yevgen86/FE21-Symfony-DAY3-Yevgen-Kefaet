-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Dez 2021 um 20:31
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `extend_crud_db`
--
CREATE DATABASE IF NOT EXISTS `extend_crud_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `extend_crud_db`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONEN DER TABELLE `doctrine_migration_versions`:
--

--
-- TRUNCATE Tabelle vor dem Einfügen `doctrine_migration_versions`
--

TRUNCATE TABLE `doctrine_migration_versions`;
--
-- Daten für Tabelle `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211201101631', '2021-12-01 11:16:44', 96);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fk_status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONEN DER TABELLE `item`:
--   `fk_status_id`
--       `status` -> `id`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `item`
--

TRUNCATE TABLE `item`;
--
-- Daten für Tabelle `item`
--

INSERT INTO `item` (`id`, `name`, `type`, `description`, `price`, `image`, `fk_status_id`) VALUES
(1, 'FirstItem', 'NewType', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 759, 'asparagus-2258018-640-61a7c91898709.jpg', 2),
(2, 'SecondItem', 'NewType', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 456, 'salad-1672505-640-61a7c8fea7c8f.jpg', 2),
(3, 'ThirdItem', 'NewType', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 333, 'ice-cream-3611698-640-61a7c77837e82.jpg', 2),
(4, 'TEST_Create_UPDATED', NULL, 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 111, 'cake-1850011-640-61a7c716944e4.jpg', 2),
(7, 'TEST_ITEM', NULL, 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 753, 'teacup-2325722-640-61a7c5bddfc97.jpg', 2),
(8, 'LAST TEST Before push', NULL, 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Alias reiciendis, tempora optio quidem dolore dolor suscipit magni odit modi facere amet tenetur. Placeat obcaecati itaque possimus, error eaque qui laboriosam.', 951, 'coffee-983955-640-61a7c415cf6dc.jpg', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONEN DER TABELLE `status`:
--

--
-- TRUNCATE Tabelle vor dem Einfügen `status`
--

TRUNCATE TABLE `status`;
--
-- Daten für Tabelle `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Done'),
(2, 'Not done');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indizes für die Tabelle `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1F1B251EAAED72D` (`fk_status_id`);

--
-- Indizes für die Tabelle `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251EAAED72D` FOREIGN KEY (`fk_status_id`) REFERENCES `status` (`id`);


--
-- Metadaten
--
USE `phpmyadmin`;

--
-- Metadaten für Tabelle doctrine_migration_versions
--

--
-- TRUNCATE Tabelle vor dem Einfügen `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadaten für Tabelle item
--

--
-- TRUNCATE Tabelle vor dem Einfügen `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadaten für Tabelle status
--

--
-- TRUNCATE Tabelle vor dem Einfügen `pma__column_info`
--

TRUNCATE TABLE `pma__column_info`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__table_uiprefs`
--

TRUNCATE TABLE `pma__table_uiprefs`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__tracking`
--

TRUNCATE TABLE `pma__tracking`;
--
-- Metadaten für Datenbank extend_crud_db
--

--
-- TRUNCATE Tabelle vor dem Einfügen `pma__bookmark`
--

TRUNCATE TABLE `pma__bookmark`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__relation`
--

TRUNCATE TABLE `pma__relation`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__savedsearches`
--

TRUNCATE TABLE `pma__savedsearches`;
--
-- TRUNCATE Tabelle vor dem Einfügen `pma__central_columns`
--

TRUNCATE TABLE `pma__central_columns`;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
