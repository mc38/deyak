-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 29, 2017 at 08:03 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Dumping data for table `zzdev`
--

INSERT INTO `zzdev` (`id`, `parameter`, `value`) VALUES(1, 'DUE_DATE', '15');
INSERT INTO `zzdev` (`id`, `parameter`, `value`) VALUES(2, 'BACKUP_MAX', '50');
INSERT INTO `zzdev` (`id`, `parameter`, `value`) VALUES(3, 'UPLOAD_MAX', '100');
INSERT INTO `zzdev` (`id`, `parameter`, `value`) VALUES(4, 'SYSTEM', 'DEMO_POWER');

--
-- Dumping data for table `zzpage`
--

INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(1, 'Authority Manage', 'inner-data/user/data-auth_manage/index.php', 1, 1, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(2, 'User Manage', 'inner-data/user/data-user_manage/index.php', 1, 2, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(3, 'Authority Access', 'inner-data/user/data-auth_access/index.php', 1, 3, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(4, 'Import APDCL File', 'inner-data/im-ex_port/csv_import_data/index.php', 3, 4, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(5, 'Sub-division Registration', 'inner-data/data/settings/data-subdiv_reg/index.php', 2, 5, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(6, 'Meter Category', 'inner-data/data/settings/data-meter_cate/index.php', 2, 6, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(7, 'Consumer Category', 'inner-data/data/settings/data-consumer_category/index.php', 2, 7, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(37, 'Rejected Android DB', 'inner-data/im-ex_port/android_db_export_rejected/index.php', 6, 37, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(9, 'Breakup Report', 'inner-data/data/zz_migration/data-import_report_brkup/index.php', 3, 11, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(10, 'Authority Control', 'inner-data/user/data-user_control/index.php', 1, 10, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(11, 'List Report', 'inner-data/data/zz_migration/data-import_report/index.php', 3, 14, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(12, 'Agent Manage', 'inner-data/data/agent/data-agent_registration/index.php', 5, 12, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(13, 'Estimated Consumption', 'inner-data/data/settings/data-estimated_consumption/index.php', 2, 13, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(14, 'Migrated Data Process', 'inner-data/data/zz_migration/data-migrate_process/index.php', 3, 18, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(36, 'Consumer Category View', 'inner-data/data/settings/data-consumer_category_view/index.php', 2, 36, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(16, 'Android Database Process', 'inner-data/data/process/data-android_db_process/index.php', 4, 16, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(17, 'Export Blank Android DB', 'inner-data/im-ex_port/android_db_export_blank/index.php', 6, 17, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(18, 'Data Edit', 'inner-data/data/zz_migration/data-edit/index.php', 3, 15, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(19, 'Agent DTR assaign', 'inner-data/data/agent/data-agent_dtr_assign/index.php', 5, 19, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(20, 'Approve Uploaded Bill', 'inner-data/data/billing/data-bill_approve/index.php', 7, 20, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(34, 'Lock Billing', 'inner-data/data/process/data-lock_billing/index.php', 4, 34, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(33, 'Tariff Information Edit', 'inner-data/data/settings/data-consumer_category_edit/index.php', 2, 33, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(23, 'Bill Payment', 'inner-data/data/billing/data-bill_payment/index.php', 7, 23, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(24, 'Consumer Ledger Report', 'inner-data/data/report/data-consumer_ledger_report/index.php', 8, 24, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(25, 'QR Code List', 'inner-data/data/report/data-qrc_report/index.php', 8, 25, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(26, 'Data upload Report', 'inner-data/data/report/data-monthly_data_upload_report/index.php', 8, 26, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(27, 'Duplicate Bill Print', 'inner-data/data/billing/data-bill_duplicate_print/index.php', 7, 27, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(28, 'Agent Upload Break-up', 'inner-data/data/report/data-agent_data_report/index.php', 8, 28, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(29, 'Bill Picture Upload', 'inner-data/im-ex_port/data-bill_picture_upload/index.php', 7, 29, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(30, 'Picture report', 'inner-data/data/report/data-picture_report/index.php', 8, 30, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(31, 'Android DB Import', 'inner-data/im-ex_port/android_db_import/index.php', 6, 31, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(32, 'Holidays List', 'inner-data/data/settings/data-holiday_list/index.php', 2, 32, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(35, 'Power Factor Settings', 'inner-data/data/settings/data-consumer_powerfactor_edit/index.php', 2, 35, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(38, 'Backdoor password', 'inner-data/data/agent/data-agent_backdoorpass/index.php', 5, 38, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(39, 'Duplicate Multi Bill Print', 'inner-data/data/billing/data-bill_duplicate_print_multi/index.php', 7, 39, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(40, 'Bill Bulk Approve', 'inner-data/data/billing/data-bill_bulk_approve/index.php', 7, 40, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(41, 'Update Meterno', 'inner-data/data/consumer/data-update_meterno/index.php', 9, 41, 0);
INSERT INTO `zzpage` (`id`, `name`, `location`, `link`, `srl`, `status`) VALUES(42, 'Unbilled Android DB', 'inner-data/im-ex_port/android_db_export_unbilled/index.php', 6, 42, 0);

--
-- Dumping data for table `zzpagetag`
--

INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(1, 'user', 1, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(6, 'import/export', 6, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(2, 'settings', 2, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(3, 'data migration', 3, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(5, 'agent', 5, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(4, 'process', 4, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(7, 'billing', 7, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(8, 'report', 8, 0, 0);
INSERT INTO `zzpagetag` (`id`, `name`, `srl`, `core`, `status`) VALUES(9, 'consumer', 9, 0, 0);

--
-- Dumping data for table `zzuserdata`
--

INSERT INTO `zzuserdata` (`id`, `name`, `username`, `hashalgo`, `ushashvalue`, `salt`, `access`, `uactive`, `fname`, `lname`, `contact`, `sex`, `status`, `auth`, `byuser`) VALUES(0, 'dev', 'admin', 30, '35edbb9b', 'Buoeq', '', '', '', '', '', 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
