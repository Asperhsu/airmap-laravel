-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 服務器版本:                        5.7.20 - MySQL Community Server (GPL)
-- 服務器操作系統:                      Linux
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 導出  視圖 airmap.latest_records 結構
-- 創建臨時表以解決視圖依賴性錯誤
CREATE TABLE `latest_records` (
	`record_id` INT(10) UNSIGNED NOT NULL,
	`uuid` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`maker` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`lat` DOUBLE(8,5) NULL,
	`lng` DOUBLE(8,5) NULL,
	`group_id` INT(11) NOT NULL,
	`group_name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`pm25` INT(11) NULL,
	`humidity` DOUBLE(6,3) NULL,
	`temperature` DOUBLE(6,3) NULL,
	`published_at` TIMESTAMP NOT NULL,
	`geometry_id` INT(11) NULL
) ENGINE=MyISAM;

-- 導出  視圖 airmap.latest_records 結構
-- 移除臨時表並創建最終視圖結構
DROP TABLE IF EXISTS `latest_records`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `latest_records` AS select `airmap`.`records`.`id` AS `record_id`,`airmap`.`records`.`uuid` AS `uuid`,`airmap`.`records`.`name` AS `name`,`airmap`.`records`.`maker` AS `maker`,`airmap`.`records`.`lat` AS `lat`,`airmap`.`records`.`lng` AS `lng`,`airmap`.`records`.`group_id` AS `group_id`,`airmap`.`groups`.`name` AS `group_name`,`airmap`.`records`.`pm25` AS `pm25`,`airmap`.`records`.`humidity` AS `humidity`,`airmap`.`records`.`temperature` AS `temperature`,`airmap`.`records`.`published_at` AS `published_at`,`airmap`.`site_geometries`.`geometry_id` AS `geometry_id` from (((`airmap`.`records` join (select max(`airmap`.`records`.`id`) AS `id` from `airmap`.`records` group by `airmap`.`records`.`group_id`,`airmap`.`records`.`uuid`) `ids` on((`airmap`.`records`.`id` = `ids`.`id`))) join `airmap`.`groups` on((`airmap`.`records`.`group_id` = `airmap`.`groups`.`id`))) left join `airmap`.`site_geometries` on(((`airmap`.`records`.`uuid` = `airmap`.`site_geometries`.`uuid`) and (`airmap`.`records`.`group_id` = `airmap`.`site_geometries`.`group_id`))));

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
