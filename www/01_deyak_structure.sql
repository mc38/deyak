-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 29, 2017 at 08:01 PM
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
-- Database: `deyak_apdcl`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_dtr`
--

CREATE TABLE IF NOT EXISTS `agent_dtr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subdiv` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `dtr` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `agent_info`
--

CREATE TABLE IF NOT EXISTS `agent_info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subdiv` text NOT NULL,
  `agent_pin` text NOT NULL,
  `imei` text NOT NULL,
  `datetime` int(11) NOT NULL,
  `name` text NOT NULL,
  `contact` text NOT NULL,
  `sex` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `backdoor` int(11) NOT NULL,
  `backdoor_pass` text NOT NULL,
  `backdoor_datetime` int(11) NOT NULL,
  `link` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_amount`
--

CREATE TABLE IF NOT EXISTS `bill_amount` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mydate` int(11) NOT NULL,
  `conid` bigint(20) NOT NULL,
  `datetime` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `pa` text NOT NULL,
  `asr` text NOT NULL,
  `cs` text NOT NULL,
  `cd` text NOT NULL,
  `nba` text NOT NULL,
  `i` text NOT NULL,
  `nbai` text NOT NULL,
  `cs_pa` text NOT NULL,
  `payment` text NOT NULL,
  `adjustment` text NOT NULL,
  `payid` bigint(20) NOT NULL,
  `mdid` bigint(20) NOT NULL,
  `due_datetime` int(11) NOT NULL,
  `credit` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE IF NOT EXISTS `bill_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mydate` int(11) NOT NULL,
  `subdiv_id` text NOT NULL,
  `conid` bigint(20) NOT NULL,
  `readid` bigint(20) NOT NULL,
  `baid` bigint(20) NOT NULL,
  `status` int(11) NOT NULL,
  `done` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_payment`
--

CREATE TABLE IF NOT EXISTS `bill_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dataid` text NOT NULL,
  `conid` int(11) NOT NULL,
  `conname` text NOT NULL,
  `amount` text NOT NULL,
  `insert_from` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `reference` text NOT NULL,
  `datetime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_reading`
--

CREATE TABLE IF NOT EXISTS `bill_reading` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mydate` int(11) NOT NULL,
  `conid` int(11) NOT NULL,
  `pre_meterstatus` int(11) NOT NULL,
  `prev_read_date` int(11) NOT NULL,
  `prev_read` int(11) NOT NULL,
  `meterno` text NOT NULL,
  `meterstatus` int(11) NOT NULL,
  `post_read_date` int(11) NOT NULL,
  `post_read` int(11) NOT NULL,
  `unit_consumed` int(11) NOT NULL,
  `power_factor` int(11) NOT NULL,
  `unit_pf` text NOT NULL,
  `m_factor` int(11) NOT NULL,
  `unit_billed` int(11) NOT NULL,
  `avarage_unit` text NOT NULL,
  `mdid` bigint(20) NOT NULL,
  `update_datetime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `consumer_details`
--

CREATE TABLE IF NOT EXISTS `consumer_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subdiv_id` text NOT NULL,
  `cid` text NOT NULL,
  `oldcid` text NOT NULL,
  `oldcno` text NOT NULL,
  `dtrno` text NOT NULL,
  `consumer_name` text NOT NULL,
  `consumer_address` text NOT NULL,
  `phase` int(11) NOT NULL,
  `cload` text NOT NULL,
  `load_unit` text NOT NULL,
  `category` int(11) NOT NULL,
  `mfactor` text NOT NULL,
  `meterno` text NOT NULL,
  `meter_cate` int(11) NOT NULL,
  `account_id` bigint(11) NOT NULL,
  `survey_id` bigint(20) NOT NULL,
  `gps_lati` text NOT NULL,
  `gps_longi` text NOT NULL,
  `gps_alti` text NOT NULL,
  `mobileno` text NOT NULL,
  `qrcode_pic` text NOT NULL,
  `survey` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `consumer_survey`
--

CREATE TABLE IF NOT EXISTS `consumer_survey` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `conid` bigint(20) NOT NULL,
  `survey_datetime` int(11) NOT NULL,
  `update_datetime` int(11) NOT NULL,
  `agent` int(11) NOT NULL,
  `meterslno` text NOT NULL,
  `metertype` int(11) NOT NULL,
  `consumertype` int(11) NOT NULL,
  `gps_lati` text NOT NULL,
  `gps_longi` text NOT NULL,
  `gps_alti` text NOT NULL,
  `nwsignal` int(11) NOT NULL,
  `meterheight` int(11) NOT NULL,
  `mobileno` text NOT NULL,
  `mdid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `in_data_queue`
--

CREATE TABLE IF NOT EXISTS `in_data_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` int(11) NOT NULL,
  `subdivision_id` text NOT NULL,
  `dtr_no` text NOT NULL,
  `old_consumer_no` text NOT NULL,
  `consumer_no` text NOT NULL,
  `consumer_name` text NOT NULL,
  `consumer_address` text NOT NULL,
  `meter_no` text NOT NULL,
  `connected_load` text NOT NULL,
  `multiplying_factor` text NOT NULL,
  `consumer_category_code` text NOT NULL,
  `meter_type` int(11) NOT NULL,
  `previous_reading` int(11) NOT NULL,
  `previous_bill_date` text NOT NULL,
  `previous_bill_datetime` int(11) NOT NULL,
  `principle_arrear` text NOT NULL,
  `arrear_surcharge` text NOT NULL,
  `adjustment` text NOT NULL,
  `avg_unit` text NOT NULL,
  `due_date` text NOT NULL,
  `due_datetime` int(11) NOT NULL,
  `pre_meterstatus` int(11) NOT NULL,
  `cs_pa` text NOT NULL,
  `status` int(11) NOT NULL,
  `importtype` int(11) NOT NULL,
  `byuser` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_data`
--

CREATE TABLE IF NOT EXISTS `m_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `c_bid` bigint(20) NOT NULL,
  `c_subdiv_id` int(11) NOT NULL,
  `in_aid` bigint(20) NOT NULL,
  `c_mydate` int(11) NOT NULL,
  `c_done` int(11) NOT NULL,
  `c_down_status` int(11) NOT NULL,
  `c_down_datetime` int(11) NOT NULL,
  `c_down_agent` int(11) NOT NULL,
  `c_import_status` int(11) NOT NULL,
  `c_import_datetime` int(11) NOT NULL,
  `c_import_user` int(11) NOT NULL,
  `c_pass_status` int(11) NOT NULL,
  `c_pass_datetime` int(11) NOT NULL,
  `c_pass_user` int(11) NOT NULL,
  `out_equation_category` int(11) NOT NULL,
  `c_ocr` int(11) NOT NULL,
  `c_survey` int(11) NOT NULL,
  `out_subdivision` text NOT NULL,
  `out_dtrno` text NOT NULL,
  `out_cid` text NOT NULL,
  `out_oldcid` text NOT NULL,
  `out_qrcode` text NOT NULL,
  `out_gps_lati` text NOT NULL,
  `out_gps_longi` text NOT NULL,
  `out_gps_alti` text NOT NULL,
  `out_consumer_name` text NOT NULL,
  `out_consumer_address` text NOT NULL,
  `out_consumer_category` text NOT NULL,
  `out_connection_type` int(11) NOT NULL,
  `out_mfactor` int(11) NOT NULL,
  `out_connection_load` text NOT NULL,
  `out_meter_no` text NOT NULL,
  `out_reserve_unit` text NOT NULL,
  `out_premeter_read_date` text NOT NULL,
  `out_premeter_read` text NOT NULL,
  `out_slab` text NOT NULL,
  `out_meter_rent` text NOT NULL,
  `out_principal_arrear` text NOT NULL,
  `out_arrear_surcharge` text NOT NULL,
  `out_current_surcharge` text NOT NULL,
  `out_adjustment` text NOT NULL,
  `out_rate_eduty` text NOT NULL,
  `out_rate_surcharge` text NOT NULL,
  `out_rate_fppa` text NOT NULL,
  `out_multibill` int(11) NOT NULL,
  `out_prevbillduedate` int(11) NOT NULL,
  `out_premeterstatus` int(11) NOT NULL,
  `out_cs_pa` text NOT NULL,
  `out_blnk_3` int(11) NOT NULL,
  `out_blnk_4` int(11) NOT NULL,
  `out_blnk_5` int(11) NOT NULL,
  `out_blnk_6` int(11) NOT NULL,
  `out_blnk_7` int(11) NOT NULL,
  `out_blnk_8` int(11) NOT NULL,
  `out_blnk_9` int(11) NOT NULL,
  `in_billno` text NOT NULL,
  `in_status` text NOT NULL,
  `in_reading_date` text NOT NULL,
  `in_postmeter_read` text NOT NULL,
  `in_meterpic` text NOT NULL,
  `in_meterpic_binary` text NOT NULL,
  `in_unit_consumed` text NOT NULL,
  `in_unit_billed` text NOT NULL,
  `in_consumption_day` text NOT NULL,
  `in_due_date` text NOT NULL,
  `in_energy_brkup` text NOT NULL,
  `in_energy_amount` text NOT NULL,
  `in_subsidy` text NOT NULL,
  `in_total_energy_charge` text NOT NULL,
  `in_fixed_charge` text NOT NULL,
  `in_meter_rent` text NOT NULL,
  `in_electricity_duty` text NOT NULL,
  `in_fppa_charge` text NOT NULL,
  `in_current_demand` text NOT NULL,
  `in_total_arrear` text NOT NULL,
  `in_net_bill_amount` text NOT NULL,
  `in_net_bill_amount_after_duedate` text NOT NULL,
  `in_gps_verification` int(11) NOT NULL,
  `in_ocr_analysis` int(11) NOT NULL,
  `in_pf` int(11) NOT NULL,
  `in_current_surcharge` text NOT NULL,
  `in_unit_pf` int(11) NOT NULL,
  `in_apdcl_billno` text NOT NULL,
  `in_curr_reading` text NOT NULL,
  `in_blnk_5` int(11) NOT NULL,
  `in_blnk_6` int(11) NOT NULL,
  `in_blnk_7` int(11) NOT NULL,
  `in_blnk_8` int(11) NOT NULL,
  `in_blnk_9` int(11) NOT NULL,
  `in_survey_gps_lati` text NOT NULL,
  `in_survey_gps_longi` text NOT NULL,
  `in_survey_gps_alti` text NOT NULL,
  `in_survey_meterheight` int(11) NOT NULL,
  `in_survey_mobno` text NOT NULL,
  `in_survey_meterslno` text NOT NULL,
  `in_survey_metertype` int(11) NOT NULL,
  `in_survey_consumertype` int(11) NOT NULL,
  `in_survey_nwsignal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_data_reject`
--

CREATE TABLE IF NOT EXISTS `m_data_reject` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datetime` int(11) NOT NULL,
  `c_mid` bigint(20) NOT NULL,
  `c_bid` bigint(20) NOT NULL,
  `c_subdiv_id` int(11) NOT NULL,
  `in_aid` bigint(20) NOT NULL,
  `c_mydate` int(11) NOT NULL,
  `c_import_status` int(11) NOT NULL,
  `c_import_datetime` int(11) NOT NULL,
  `c_import_user` int(11) NOT NULL,
  `c_pass_status` int(11) NOT NULL,
  `c_pass_datetime` int(11) NOT NULL,
  `c_pass_user` int(11) NOT NULL,
  `out_equation_category` int(11) NOT NULL,
  `c_ocr` int(11) NOT NULL,
  `c_survey` int(11) NOT NULL,
  `out_subdivision` text NOT NULL,
  `out_dtrno` text NOT NULL,
  `out_cid` text NOT NULL,
  `out_oldcid` text NOT NULL,
  `out_qrcode` text NOT NULL,
  `out_gps_lati` text NOT NULL,
  `out_gps_longi` text NOT NULL,
  `out_gps_alti` text NOT NULL,
  `out_consumer_name` text NOT NULL,
  `out_consumer_address` text NOT NULL,
  `out_consumer_category` text NOT NULL,
  `out_connection_type` int(11) NOT NULL,
  `out_mfactor` int(11) NOT NULL,
  `out_connection_load` text NOT NULL,
  `out_meter_no` text NOT NULL,
  `out_reserve_unit` int(11) NOT NULL,
  `out_premeter_read_date` text NOT NULL,
  `out_premeter_read` text NOT NULL,
  `out_slab` text NOT NULL,
  `out_meter_rent` text NOT NULL,
  `out_principal_arrear` text NOT NULL,
  `out_arrear_surcharge` text NOT NULL,
  `out_current_surcharge` text NOT NULL,
  `out_adjustment` text NOT NULL,
  `out_rate_eduty` text NOT NULL,
  `out_rate_surcharge` text NOT NULL,
  `out_rate_fppa` text NOT NULL,
  `out_multibill` int(11) NOT NULL,
  `in_billno` text NOT NULL,
  `in_status` text NOT NULL,
  `in_reading_date` text NOT NULL,
  `in_postmeter_read` text NOT NULL,
  `in_meterpic` text NOT NULL,
  `in_meterpic_binary` text NOT NULL,
  `in_unit_consumed` text NOT NULL,
  `in_unit_billed` text NOT NULL,
  `in_consumption_day` text NOT NULL,
  `in_due_date` text NOT NULL,
  `in_energy_brkup` text NOT NULL,
  `in_energy_amount` text NOT NULL,
  `in_subsidy` text NOT NULL,
  `in_total_energy_charge` text NOT NULL,
  `in_fixed_charge` text NOT NULL,
  `in_electricity_duty` text NOT NULL,
  `in_fppa_charge` text NOT NULL,
  `in_current_demand` text NOT NULL,
  `in_total_arrear` text NOT NULL,
  `in_net_bill_amount` text NOT NULL,
  `in_net_bill_amount_after_duedate` text NOT NULL,
  `in_gps_verification` int(11) NOT NULL,
  `in_ocr_analysis` int(11) NOT NULL,
  `in_pf` int(11) NOT NULL,
  `in_survey_gps_lati` text NOT NULL,
  `in_survey_gps_longi` text NOT NULL,
  `in_survey_gps_alti` text NOT NULL,
  `in_survey_meterheight` int(11) NOT NULL,
  `in_survey_mobno` text NOT NULL,
  `in_survey_meterslno` text NOT NULL,
  `in_survey_metertype` int(11) NOT NULL,
  `in_survey_consumertype` int(11) NOT NULL,
  `in_survey_nwsignal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m_data_update`
--

CREATE TABLE IF NOT EXISTS `m_data_update` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `datetime` int(11) NOT NULL,
  `mdid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `mydate` int(11) NOT NULL,
  `conid` int(11) NOT NULL,
  `pa` text NOT NULL,
  `asr` text NOT NULL,
  `adjustment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_consumer_cate`
--

CREATE TABLE IF NOT EXISTS `settings_consumer_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `slab` text NOT NULL,
  `tariff_id` text NOT NULL,
  `electricity_duty` text NOT NULL,
  `surcharge` text NOT NULL,
  `fppa` text NOT NULL,
  `pfslab` text NOT NULL,
  `link` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_estimated_consumption`
--

CREATE TABLE IF NOT EXISTS `settings_estimated_consumption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate` int(11) NOT NULL,
  `consump` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_holidays`
--

CREATE TABLE IF NOT EXISTS `settings_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `datetime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_meter_cate`
--

CREATE TABLE IF NOT EXISTS `settings_meter_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `rent` float NOT NULL,
  `phase` int(11) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_subdiv_data`
--

CREATE TABLE IF NOT EXISTS `settings_subdiv_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` text NOT NULL,
  `name` text NOT NULL,
  `detail` text NOT NULL,
  `link` int(11) NOT NULL,
  `accessurl` text NOT NULL,
  `ftp_img_server` text NOT NULL,
  `ftp_img_user` text NOT NULL,
  `ftp_img_pass` text NOT NULL,
  `data_1` text NOT NULL,
  `data_2` text NOT NULL,
  `data_3` text NOT NULL,
  `data_4` text NOT NULL,
  `data_5` text NOT NULL,
  `data_6` text NOT NULL,
  `data_7` text NOT NULL,
  `data_8` text NOT NULL,
  `data_9` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp_in_data_queue`
--

CREATE TABLE IF NOT EXISTS `temp_in_data_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  `subdivision_id` text NOT NULL,
  `dtr_no` text NOT NULL,
  `old_consumer_no` text NOT NULL,
  `consumer_no` text NOT NULL,
  `consumer_name` text NOT NULL,
  `consumer_address` text NOT NULL,
  `meter_no` text NOT NULL,
  `connected_load` text NOT NULL,
  `multiplying_factor` text NOT NULL,
  `consumer_category_code` text NOT NULL,
  `meter_type` int(11) NOT NULL,
  `previous_reading` int(11) NOT NULL,
  `previous_bill_date` text NOT NULL,
  `previous_bill_datetime` int(11) NOT NULL,
  `principle_arrear` text NOT NULL,
  `arrear_surcharge` text NOT NULL,
  `adjustment` text NOT NULL,
  `avg_unit` text NOT NULL,
  `due_date` text NOT NULL,
  `due_datetime` int(11) NOT NULL,
  `pre_meterstatus` int(11) NOT NULL,
  `cs_pa` text NOT NULL,
  `status` int(11) NOT NULL,
  `importtype` int(11) NOT NULL,
  `byuser` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzauth`
--

CREATE TABLE IF NOT EXISTS `zzauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `access` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzdev`
--

CREATE TABLE IF NOT EXISTS `zzdev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzpage`
--

CREATE TABLE IF NOT EXISTS `zzpage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `link` int(11) NOT NULL,
  `srl` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzpagetag`
--

CREATE TABLE IF NOT EXISTS `zzpagetag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `srl` int(11) NOT NULL,
  `core` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzuserdata`
--

CREATE TABLE IF NOT EXISTS `zzuserdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `hashalgo` int(11) NOT NULL,
  `ushashvalue` text NOT NULL,
  `salt` text NOT NULL,
  `access` text NOT NULL,
  `uactive` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `contact` text NOT NULL,
  `sex` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `auth` int(11) NOT NULL,
  `byuser` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

--
-- Table structure for table `zzuser_subdiv`
--

CREATE TABLE IF NOT EXISTS `zzuser_subdiv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zzzlogbook`
--

CREATE TABLE IF NOT EXISTS `zzzlogbook` (
  `datetime` int(11) NOT NULL,
  `byuser` int(11) NOT NULL,
  `tblname` text NOT NULL,
  `tblid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `prevdata` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
