-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.25-0ubuntu0.18.04.2 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table test_mvc_lo.task
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `description` text CHARACTER SET latin1,
  `description_hash` tinytext,
  `status` enum('new','in_progress','done') CHARACTER SET latin1 DEFAULT 'new',
  `edit_by` int(10) unsigned DEFAULT NULL COMMENT 'edit by user_id',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  -- KEY `FK_task_users` (`user_id`),
  KEY `status` (`status`)
  -- CONSTRAINT `FK_task_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16;

-- Dumping data for table test_mvc_lo.task: ~4 rows (approximately)
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` (`id`, `user_id`, `description`, `description_hash`, `status`, `edit_by`, `created_at`, `updated_at`) VALUES
	(1, 1, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cd7d2c8c', 'new', 7, '2019-10-05 22:52:47', '2019-10-13 17:30:29'),
	(2, 2, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cd7d2c8c', 'new', NULL, '2019-10-05 22:52:48', '2019-10-13 17:30:30'),
	(3, 3, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cd7d2c8c', 'new', NULL, '2019-10-05 22:52:49', '2019-10-13 17:30:30'),
	(4, 4, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cd7d2c8c', 'done', NULL, '2019-10-05 22:52:49', '2019-10-13 17:30:31'),
	(5, 5, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cd7d2c8c', 'new', NULL, '2019-10-05 22:52:49', '2019-10-13 17:30:32');
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Dumping structure for table test_mvc_lo.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'login',
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf16;

-- Dumping data for table test_mvc_lo.users: ~6 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `name`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'aemail1@do.do', 'aName 1', '', '2019-10-05 22:51:03', '2019-10-07 17:29:30'),
	(2, 'bemail2@do.do', 'bName 2', '', '2019-10-05 22:51:05', '2019-10-07 17:29:51'),
	(3, 'cmail3@do.do', 'cName 3', '', '2019-10-05 22:51:04', '2019-10-07 17:29:41'),
	(4, 'demail4@do.do', 'dName 4', '', '2019-10-05 22:51:05', '2019-10-07 17:29:58'),
	(5, 'email5@do.do', 'eName 5', '', '2019-10-05 22:51:05', '2019-10-07 16:25:30'),
	(6, 'admin@adminovich.net', 'admin', '6597b4ab327881643c68cfe0ac073b4e55b1a8c5574acb07c8cd8de7da36f1a4', '2019-10-10 23:17:03', '2019-10-10 23:17:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40000 ALTER TABLE `task` DISABLE KEYS */;
ALTER TABLE `task` ADD CONSTRAINT `FK_task_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
