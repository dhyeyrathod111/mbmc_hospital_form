-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 11, 2020 at 08:06 PM
-- Server version: 5.7.20-0ubuntu0.16.04.1-log
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mbmc`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `approvedYearlyData` (IN `name_table` VARCHAR(30), IN `year_current` INT(11), IN `dept_id` INT(11), IN `role_id` INT(11))  NO SQL
BEGIN
	SET @name_table = name_table;
    SET @current_year = year_current;
    SET @dept_id = dept_id;
    SET @role_id = role_id;
    
    IF (@dept_id = '3') THEN
    	SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @name_table," WHERE cutAppId IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE dept_id = ", concat("'", @dept_id, "'")," AND status = '1' GROUP BY app_id) AND role_id >= ", concat("'", @role_id,"'"),") AND is_deleted = '0' AND YEAR(created_at) = ", concat("'", @current_year,"'"));
    ELSE
    	SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @name_table," WHERE app_id IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE dept_id = ", concat("'", @dept_id, "'")," AND status = '1' GROUP BY app_id) AND role_id >= ", concat("'", @role_id,"'"),") AND is_deleted = '0' AND YEAR(created_at) = ", concat("'", @current_year,"'"));
    END IF;
    
    
    PREPARE statement_ FROM @site_code_;
    EXECUTE statement_;
    DEALLOCATE PREPARE statement_;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dailyrequest` (IN `tab_name` VARCHAR(50), IN `today_date` VARCHAR(30))  BEGIN
 
 SET @site_code = CONCAT("SELECT COUNT(*) totalRequest FROM ", tab_name, " WHERE date(created_at) = ", CONCAT("'", today_date, "'"));
 
 PREPARE statement_ FROM @site_code;
 EXECUTE statement_;
 DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `gettotalreqmonth` (IN `month_number` INT, IN `name_table` VARCHAR(30))  NO SQL
BEGIN 
	SET @month_number = month_number;
    SET @name_table = name_table;
    
    SET @site_code_ = concat("SELECT count(*) total_count FROM ", @name_table, " WHERE MONTH(created_at) = ", concat("'", @month_number, "'"), " AND is_deleted = '0'");
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getyearlydata` (IN `name_table` VARCHAR(30), IN `year` VARCHAR(20))  NO SQL
BEGIN
	SET @name_table = name_table;
    SET @year = year;
    
    SET @site_code_ = concat("SELECT count(*) total_count FROM ", @name_table, " WHERE is_deleted = '0' AND YEAR(created_at) = ", concat("'", @year,"'"), " AND file_closure_status = '1'");
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pendingapprovals` (IN `prevrole` INT(11), IN `deptid` INT(11), IN `roleid` INT(11), IN `today_date` VARCHAR(30), IN `name_table` VARCHAR(30))  NO SQL
BEGIN
	SET @prevrole = prevrole;
    SET @dept_id = deptid;
    SET @role_id = roleid;
    SET @table_name = name_table;
    
	IF (@prevrole = '0') THEN
    
    	IF (@dept_id = '3') THEN 
        	
        SET @site_code_ = concat("SELECT count(*) total_count FROM ",  @table_name," pa WHERE date(pa.created_at) <= ", concat("'", today_date, "'"), " AND is_deleted = '0' AND pa.cutAppId NOT IN (", "SELECT distinct app_id FROM application_remarks WHERE dept_id = ", concat("'",@dept_id,"'"), " AND status = '1'", ")");
        
        ELSE
        	
            SET @site_code_ = concat("SELECT count(*) total_count FROM ",  @table_name," pa WHERE date(pa.created_at) <= ", concat("'", today_date, "'"), " AND is_deleted = '0' AND pa.app_id NOT IN (", "SELECT distinct app_id FROM application_remarks WHERE dept_id = ", concat("'",@dept_id,"'"), " AND status = '1'", ")");
        END IF;
    	
    ELSE

        IF(@dept_id = '5') THEN

            SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @table_name," WHERE app_id IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' GROUP BY app_id) AND role_id = ", concat("'", @prevrole,"'")," AND dept_id = ", concat("'", @dept_id,"'"),") AND is_deleted = '0'");

        ELSE 

            SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @table_name," WHERE app_id IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' GROUP BY app_id) AND role_id = ", concat("'", @prevrole,"'")," AND dept_id = ", concat("'", @dept_id,"'"),") AND is_deleted = '0'");

        END IF;
    
    
    	
    END IF;
    
    PREPARE statement_ FROM @site_code_;
    EXECUTE statement_;
    DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalamountcollectedpwd` (IN `final_role_id` VARCHAR(30), IN `date_today` VARCHAR(30))  NO SQL
BEGIN
	SET @final_role_id = final_role_id;
    SET @date_today = date_today;
    SET @site_code_ = concat("SELECT SUM(t3.total_amount) ttl_amont FROM (SELECT t2.*, (SELECT SUM((total_ri_charges + security_deposit + total_gst)) total_amount FROM road_information_pwd WHERE pwd_app_id = t2.ids AND status = '1') total_amount FROM (SELECT t1.app_id, (SELECT id FROM pwd_applications WHERE app_id = t1.app_id) ids FROM (SELECT app_id, role_id FROM application_remarks WHERE id IN ( SELECT MAX(id) FROM application_remarks WHERE status = '1' GROUP BY app_id )) t1 WHERE t1.role_id IN ( ", @final_role_id, " )) t2 WHERE t2.ids IN (SELECT app_id FROM payment WHERE status = '2' AND date(created_at) = ", concat("'", @date_today, "'")," AND is_deleted = '0')) t3");
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalapprovedByroleinmonth` (IN `month_number` INT(11), IN `name_table` VARCHAR(30), IN `roleid` INT(11), IN `deptid` INT(11))  NO SQL
BEGIN
	SET @month_number = month_number;
    SET @name_table = name_table;
    SET @role_id = roleid;
    SET @dept_id = deptid;
    
    SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ",concat(@name_table)," WHERE app_id IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT max(id) FROM `application_remarks` WHERE status = '1' AND dept_id = ", concat("'", @dept_id,"'")," GROUP BY app_id) AND role_id >= ", concat("'", @role_id,"'"),") AND is_deleted = '0' AND MONTH(created_at) = ", concat("'", @month_number,"'"));
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalpendingamountpwd` (IN `final_role_id` INT(11))  NO SQL
BEGIN
 SET @final_role = final_role_id;
 SET @site_code_ = concat("SELECT SUM(t3.total_amount) ttl_amnt FROM (SELECT t2.*, (SELECT SUM((total_ri_charges + security_deposit + total_gst)) total_amount FROM road_information_pwd WHERE pwd_app_id = t2.ids AND status = '1') total_amount FROM (SELECT t1.app_id, (SELECT id FROM pwd_applications WHERE app_id = t1.app_id) ids FROM (SELECT app_id, role_id FROM application_remarks WHERE id IN ( SELECT MAX(id) FROM application_remarks GROUP BY app_id )) t1 WHERE t1.role_id IN ( ", @final_role, " )) t2 WHERE t2.ids NOT IN (SELECT app_id FROM payment WHERE status = '2' AND is_deleted = '0')) t3");
 	
 PREPARE statement_ FROM @site_code_;
 EXECUTE statement_;
 DEALLOCATE PREPARE statement_;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalunapprovedByroleinmonth` (IN `month_number` INT, IN `name_table` VARCHAR(255), IN `roleid` INT, IN `deptid` INT, IN `prevrole` INT)  NO SQL
BEGIN
	SET @month_number = month_number;
    SET @name_table = name_table;
    SET @role_id = roleid;
    SET @dept_id = deptid;
    SET @prevrole = prevrole;
    
    IF (@prevrole = '0') THEN
    
    SET @site_code_ = concat("SELECT count(*) total_count FROM ",concat(@name_table)," WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT max(id) FROM application_remarks WHERE status = '1' AND dept_id = ", concat("'", @dept_id,"'"), " GROUP BY app_id)" , ") AND MONTH(created_at) = ", concat("'", @month_number,"'"), " AND is_deleted = '0'");
    
    ELSE
    
    SET @site_code_ = concat("SELECT count(id) total_count FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE MONTH(created_at) = ", concat("'", @month_number, "'"), " AND status = '1' GROUP BY app_id) AND role_id = ", concat("'", @prevrole,"'"));
    
    END IF;
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `unapprovedYearlyData` (IN `prevrole` INT(11), IN `name_table` VARCHAR(30), IN `deptid` INT(11), IN `roleid` INT(11), IN `year` INT(11))  NO SQL
BEGIN
	SET @prevrole = prevrole;
    SET @name_table = name_table;
    SET @dept_id = deptid;
    SET @role_id = roleid;
    SET @year = year;
    
    if (@prevrole = '0') THEN
    	IF (@dept_id = '3') THEN
        SET @site_code_ = concat("SELECT count(*) total_count FROM ", @name_table," WHERE cutAppId NOT IN (SELECT app_id FROM application_remarks where id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' AND dept_id = ", concat("'", @dept_id,"'")," GROUP BY app_id)", ") AND YEAR(created_at) = ", concat("'", @year,"'")," AND is_deleted = '0'");
        ELSE
        SET @site_code_ = concat("SELECT count(app_id) total_count FROM ", @name_table," WHERE app_id NOT IN (SELECT app_id FROM application_remarks where id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' AND dept_id = ", concat("'", @dept_id,"'")," GROUP BY app_id)", ") AND YEAR(created_at) = ", concat("'", @year,"'")," AND is_deleted = '0'");
        END IF;
        
    ELSE
    	IF (@dept_id = '3') THEN
        SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @name_table," WHERE cutAppId IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' GROUP BY app_id) AND role_id = ", concat("'", @prevrole,"'")," AND dept_id = ", concat("'", @dept_id,"'"),") AND is_deleted = '0' AND YEAR(created_at) = ", concat("'", @year,"'"));
        ELSE
        SET @site_code_ = concat("SELECT COUNT(*) total_count FROM ", @name_table," WHERE app_id IN (SELECT app_id FROM application_remarks WHERE id IN (SELECT MAX(id) FROM application_remarks WHERE status = '1' GROUP BY app_id) AND role_id = ", concat("'", @prevrole,"'")," AND dept_id = ", concat("'", @dept_id,"'"),") AND is_deleted = '0' AND YEAR(created_at) = ", concat("'", @year,"'"));
        END IF;
    END IF;
    
    PREPARE statement_ FROM @site_code_;
 	EXECUTE statement_;
 	DEALLOCATE PREPARE statement_;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `advertisement_applications`
--

CREATE TABLE `advertisement_applications` (
  `adv_id` int(11) NOT NULL,
  `form_no` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `hoarding_location_address` varchar(255) NOT NULL,
  `type_of_adv` int(11) NOT NULL,
  `illuminate` int(11) NOT NULL,
  `hoarding_length` varchar(50) NOT NULL,
  `hoarding_breadth` varchar(50) NOT NULL,
  `height_of_road` varchar(50) NOT NULL,
  `serchena` varchar(50) NOT NULL,
  `type_of_request` int(11) NOT NULL,
  `comp_address1` varchar(255) NOT NULL,
  `comp_address2` varchar(255) NOT NULL,
  `pancard` varchar(100) NOT NULL,
  `aadhar_card` varchar(100) NOT NULL,
  `society_notice_status` int(11) NOT NULL,
  `society_notice` varchar(100) NOT NULL,
  `owner_hoarding_name` varchar(50) NOT NULL,
  `owner_hoarding_address` varchar(255) NOT NULL,
  `owner_noc_status` int(11) NOT NULL,
  `owner_noc` varchar(100) NOT NULL,
  `hoarding_location` varchar(50) NOT NULL,
  `adv_start_date` date NOT NULL,
  `no_adv_days` int(11) NOT NULL,
  `end_date` date NOT NULL,
  `rate` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `application_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `extra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `advertisement_applications`
--

INSERT INTO `advertisement_applications` (`adv_id`, `form_no`, `name`, `address`, `hoarding_location_address`, `type_of_adv`, `illuminate`, `hoarding_length`, `hoarding_breadth`, `height_of_road`, `serchena`, `type_of_request`, `comp_address1`, `comp_address2`, `pancard`, `aadhar_card`, `society_notice_status`, `society_notice`, `owner_hoarding_name`, `owner_hoarding_address`, `owner_noc_status`, `owner_noc`, `hoarding_location`, `adv_start_date`, `no_adv_days`, `end_date`, `rate`, `amount`, `application_date`, `status`, `is_deleted`, `extra`) VALUES
(1, 'MBMC-0000049', 'Ankit', 'Test Test Test Test Test Test Test Test ', 'Test Test Test Test Test Test Test ', 1, 1, '12', '12', '12', 'test', 1, '', '', '44b2defc34771ea54b6f9623b1bb2aba.jpg', 'e925477f8b8c7804aee49f0087eac79b.jpg', 1, '', 'dadasd', 'test', 1, '', 'test', '2020-07-18', 12, '2020-07-30', '12', '12', '2020-07-18 07:16:39', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `adv_type`
--

CREATE TABLE `adv_type` (
  `adv_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adv_type`
--

INSERT INTO `adv_type` (`adv_id`, `name`, `date_added`, `status`) VALUES
(1, 'Hoarding', '2020-05-07 00:00:00', 1),
(2, 'Tests', '2020-05-13 16:01:39', 1),
(3, 'Test', '2020-05-13 16:02:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `applications_details`
--

CREATE TABLE `applications_details` (
  `application_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `sub_dept_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `applications_details`
--

INSERT INTO `applications_details` (`application_id`, `dept_id`, `sub_dept_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 0, 1, 0, '2020-10-08 07:47:08', '2020-10-08 07:47:08'),
(3, 1, 0, 1, 0, '2020-10-13 11:50:13', '2020-10-13 11:50:13'),
(4, 1, 0, 1, 0, '2020-11-22 15:18:22', '2020-11-22 15:18:22'),
(5, 1, 0, 1, 0, '2020-11-15 15:26:15', '2020-11-15 15:26:15'),
(6, 1, 0, 1, 0, '2020-11-28 15:33:28', '2020-11-28 15:33:28'),
(7, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 1, 0, 1, 0, '2020-11-03 08:06:03', '2020-11-03 08:06:03'),
(10, 1, 0, 1, 0, '2020-11-21 08:31:21', '2020-11-21 08:31:21'),
(11, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 1, 0, 1, 0, '2020-11-21 07:10:21', '2020-11-21 07:10:21'),
(13, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 1, 0, 1, 0, '2020-11-17 10:08:17', '2020-11-17 10:08:17'),
(15, 1, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 5, 1, 1, 0, '2020-11-04 16:35:04', '2020-11-04 16:35:04'),
(17, 5, 1, 1, 0, '2020-11-22 17:09:26', '2020-11-22 17:09:26'),
(18, 5, 1, 1, 0, '2020-11-22 17:14:26', '2020-11-22 17:14:26'),
(19, 5, 1, 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(20, 5, 1, 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(21, 5, 0, 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(22, 5, 0, 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(23, 5, 0, 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(24, 5, 0, 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(25, 5, 0, 1, 0, '2020-11-23 08:15:18', '2020-11-23 08:15:18'),
(26, 5, 0, 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(27, 5, 0, 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(28, 5, 0, 1, 0, '2020-11-23 08:15:24', '2020-11-23 08:15:24'),
(29, 5, 0, 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(30, 5, 0, 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(31, 5, 0, 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(32, 5, 0, 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(33, 5, 0, 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(34, 5, 0, 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(35, 5, 0, 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(36, 5, 0, 1, 0, '2020-11-24 07:20:51', '2020-11-24 07:20:51'),
(37, 5, 0, 1, 0, '2020-11-24 07:23:30', '2020-11-24 07:23:30'),
(38, 5, 2, 1, 0, '0000-00-00 00:00:00', '2020-11-24 16:45:21'),
(39, 5, 2, 1, 0, '2020-11-14 13:22:14', '2020-11-14 13:22:14'),
(40, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 5, 2, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 5, 2, 1, 0, '2020-11-17 16:39:17', '2020-11-24 16:46:50'),
(48, 5, 2, 1, 0, '0000-00-00 00:00:00', '2020-11-24 17:00:15'),
(49, 5, 2, 1, 0, '0000-00-00 00:00:00', '2020-11-24 18:24:08'),
(50, 5, 2, 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(51, 5, 3, 1, 0, '2020-11-26 12:32:07', '2020-11-26 12:32:07'),
(52, 5, 2, 1, 0, '2020-11-26 17:24:45', '2020-11-26 17:24:45'),
(53, 5, 2, 1, 0, '2020-11-26 17:26:08', '2020-11-26 17:26:08'),
(54, 0, 0, 1, 0, '2020-11-27 15:41:16', '2020-11-27 15:41:16'),
(55, 0, 0, 1, 0, '2020-11-30 11:54:01', '2020-11-30 11:54:01'),
(56, 0, 0, 1, 0, '2020-11-30 16:37:11', '2020-11-30 16:37:11'),
(57, 0, 0, 1, 0, '2020-11-30 20:46:02', '2020-11-30 20:46:02'),
(58, 5, 0, 1, 0, '2020-12-02 10:51:54', '2020-12-02 10:51:54'),
(59, 5, 0, 1, 0, '2020-12-02 11:00:05', '2020-12-02 11:00:05'),
(60, 5, 0, 1, 0, '2020-12-02 11:00:21', '2020-12-02 11:00:21'),
(61, 5, 0, 1, 0, '2020-12-02 11:02:02', '2020-12-02 11:02:02'),
(62, 5, 0, 1, 0, '2020-12-02 11:05:03', '2020-12-02 11:05:03'),
(63, 5, 0, 1, 0, '2020-12-02 11:05:55', '2020-12-02 11:05:55'),
(64, 5, 0, 1, 0, '2020-12-02 11:06:20', '2020-12-02 11:06:20'),
(65, 5, 0, 1, 0, '2020-12-02 11:32:58', '2020-12-02 11:32:58'),
(66, 5, 0, 1, 0, '2020-12-02 11:35:26', '2020-12-02 11:35:26'),
(67, 5, 0, 1, 0, '2020-12-02 11:36:21', '2020-12-02 11:36:21'),
(68, 5, 0, 1, 0, '2020-12-02 12:41:58', '2020-12-02 12:41:58'),
(69, 5, 0, 1, 0, '2020-12-02 13:16:59', '2020-12-02 13:16:59'),
(70, 5, 0, 1, 0, '2020-12-02 18:16:03', '2020-12-02 18:16:03'),
(71, 5, 0, 1, 0, '2020-12-02 18:16:20', '2020-12-02 18:16:20'),
(72, 5, 0, 1, 0, '2020-12-02 18:18:01', '2020-12-02 18:18:01'),
(73, 5, 0, 1, 0, '2020-12-02 18:18:38', '2020-12-02 18:18:38'),
(74, 5, 0, 1, 0, '2020-12-02 18:18:52', '2020-12-02 18:18:52'),
(75, 5, 0, 1, 0, '2020-12-02 18:19:02', '2020-12-02 18:19:02'),
(76, 5, 0, 1, 0, '2020-12-02 18:23:02', '2020-12-02 18:23:02'),
(77, 5, 0, 1, 0, '2020-12-02 18:23:55', '2020-12-02 18:23:55'),
(78, 5, 0, 1, 0, '2020-12-02 18:24:12', '2020-12-02 18:24:12'),
(79, 5, 0, 1, 0, '2020-12-02 18:24:30', '2020-12-02 18:24:30'),
(80, 5, 0, 1, 0, '2020-12-02 18:24:36', '2020-12-02 18:24:36'),
(81, 5, 0, 1, 0, '2020-12-02 18:25:06', '2020-12-02 18:25:06'),
(82, 5, 0, 1, 0, '2020-12-02 18:28:08', '2020-12-02 18:28:08'),
(83, 5, 0, 1, 0, '2020-12-02 18:37:58', '2020-12-02 18:37:58'),
(84, 5, 0, 1, 0, '2020-12-02 18:42:39', '2020-12-02 18:42:39'),
(85, 5, 0, 1, 0, '2020-12-03 11:32:54', '2020-12-03 11:32:54'),
(86, 5, 0, 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(87, 5, 2, 1, 0, '2020-12-04 15:43:37', '2020-12-04 15:43:37'),
(88, 5, 2, 1, 0, '2020-12-04 15:44:02', '2020-12-04 15:44:02'),
(89, 5, 0, 1, 0, '2020-12-04 15:48:33', '2020-12-04 15:48:33'),
(90, 5, 2, 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(91, 5, 2, 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(92, 5, 2, 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(93, 5, 2, 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(94, 5, 2, 1, 0, '2020-12-04 16:19:03', '2020-12-04 16:19:03'),
(95, 5, 2, 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(96, 5, 2, 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(97, 5, 0, 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(98, 5, 2, 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(99, 5, 2, 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(100, 5, 0, 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(101, 5, 2, 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(102, 5, 0, 1, 0, '2020-12-05 13:22:49', '2020-12-05 13:22:49'),
(103, 5, 2, 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(104, 5, 3, 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(105, 5, 0, 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(106, 5, 0, 1, 0, '2020-12-07 15:21:54', '2020-12-07 15:21:54'),
(107, 5, 2, 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(108, 5, 0, 1, 0, '2020-12-07 17:41:42', '2020-12-07 17:41:42'),
(109, 5, 0, 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(110, 5, 0, 1, 0, '2020-12-07 18:49:45', '2020-12-07 18:49:45'),
(111, 5, 0, 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(112, 5, 2, 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(113, 5, 2, 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(114, 5, 2, 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(115, 5, 3, 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(116, 5, 2, 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(117, 5, 2, 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(118, 5, 2, 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(119, 5, 2, 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(120, 5, 3, 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(121, 5, 3, 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(122, 5, 3, 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(123, 5, 0, 1, 0, '2020-12-08 17:51:16', '2020-12-08 17:51:16'),
(124, 5, 0, 1, 0, '2020-12-08 18:13:15', '2020-12-08 18:13:15'),
(125, 5, 0, 1, 0, '2020-12-08 18:33:55', '2020-12-08 18:33:55'),
(126, 5, 0, 1, 0, '2020-12-09 15:06:58', '2020-12-09 15:06:58'),
(127, 5, 0, 1, 0, '2020-12-09 15:07:32', '2020-12-09 15:07:32'),
(128, 5, 0, 1, 0, '2020-12-09 16:26:10', '2020-12-09 16:26:10'),
(129, 6, 0, 1, 0, '2020-12-21 16:54:21', '2020-12-21 16:54:21'),
(130, 12, 0, 1, 0, '2020-12-19 17:27:19', '2020-12-19 17:27:19'),
(131, 12, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 12, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 12, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 12, 0, 1, 0, '2020-12-02 14:48:02', '2020-12-02 14:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `application_remarks`
--

CREATE TABLE `application_remarks` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `sub_dept_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `remarks` longtext NOT NULL,
  `commissionerApproval` int(11) NOT NULL COMMENT '0:Not Approval, 1:Approval',
  `status_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `application_remarks`
--

INSERT INTO `application_remarks` (`id`, `app_id`, `dept_id`, `sub_dept_id`, `user_id`, `role_id`, `remarks`, `commissionerApproval`, `status_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 0, 9, 3, 'ok', 0, 8, 1, 0, '2020-10-29 12:10:30', '2020-10-30 12:10:30'),
(2, 3, 1, 0, 9, 3, 'k', 0, 8, 1, 0, '2020-10-29 12:13:10', '2020-10-10 12:13:10'),
(3, 3, 1, 0, 9, 3, 'Test', 0, 8, 1, 0, '2020-10-30 13:05:45', '0000-00-00 00:00:00'),
(4, 3, 1, 0, 9, 3, 'Test clerk', 0, 8, 1, 0, '2020-10-30 13:13:22', '2020-10-22 13:13:22'),
(5, 3, 1, 0, 9, 3, 'New Test', 0, 8, 1, 0, '2020-11-02 06:48:15', '2020-11-15 06:48:15'),
(6, 3, 1, 0, 9, 3, 'Test New', 0, 8, 1, 0, '2020-11-02 06:52:23', '2020-11-23 06:52:23'),
(7, 8, 1, 0, 9, 3, 'length is not correct', 0, 6, 1, 0, '2020-11-02 15:51:09', '2020-11-09 15:51:09'),
(8, 9, 1, 0, 9, 3, 'approved and can be proceeded', 0, 8, 1, 0, '2020-11-10 08:10:01', '2020-11-01 08:10:01'),
(9, 9, 1, 0, 11, 8, 'approved by jea', 0, 7, 1, 0, '2020-11-10 08:18:32', '0000-00-00 00:00:00'),
(10, 9, 1, 0, 12, 17, 'approved by deputy eng', 0, 79, 1, 0, '2020-11-10 08:20:06', '2020-11-06 08:20:06'),
(11, 9, 1, 0, 13, 18, 'approved by se', 0, 38, 1, 0, '2020-11-10 08:21:35', '0000-00-00 00:00:00'),
(12, 10, 1, 0, 9, 3, 'approved', 0, 8, 1, 0, '2020-11-10 08:32:31', '0000-00-00 00:00:00'),
(13, 10, 1, 0, 11, 8, 'approved by je', 0, 7, 1, 0, '2020-11-10 08:33:24', '2020-11-24 08:33:24'),
(14, 10, 1, 0, 12, 17, 'approved', 0, 79, 1, 0, '2020-11-10 08:34:08', '2020-11-08 08:34:08'),
(15, 10, 1, 0, 13, 18, 'approved', 0, 38, 1, 0, '2020-11-10 08:34:54', '0000-00-00 00:00:00'),
(16, 9, 1, 0, 13, 18, 'test', 0, 38, 1, 0, '2020-11-10 08:52:42', '0000-00-00 00:00:00'),
(17, 9, 1, 0, 13, 18, 'test', 0, 38, 1, 0, '2020-11-10 08:56:13', '2020-11-13 08:56:13'),
(18, 9, 1, 0, 13, 18, 'adadasd', 0, 38, 1, 0, '2020-11-10 09:01:09', '2020-11-09 09:01:09'),
(19, 9, 1, 0, 13, 18, 'adadasd', 0, 38, 1, 0, '2020-11-10 09:01:49', '0000-00-00 00:00:00'),
(20, 9, 1, 0, 13, 18, 'adadasd', 0, 38, 1, 0, '2020-11-10 09:04:13', '2020-11-13 09:04:13'),
(21, 9, 1, 0, 13, 18, 'adadasd', 0, 38, 1, 0, '2020-11-10 09:06:04', '2020-11-04 09:06:04'),
(22, 9, 1, 0, 13, 18, 'adadasd', 0, 38, 1, 0, '2020-11-10 09:11:01', '2020-11-01 09:11:01'),
(23, 9, 1, 0, 13, 18, 'test', 0, 38, 1, 0, '2020-11-10 09:22:45', '0000-00-00 00:00:00'),
(24, 9, 1, 0, 13, 18, 'testing', 0, 38, 1, 0, '2020-11-10 09:23:54', '0000-00-00 00:00:00'),
(25, 9, 1, 0, 13, 18, 'sadasdasd', 0, 38, 1, 0, '2020-11-10 09:26:28', '2020-11-28 09:26:28'),
(26, 9, 1, 0, 13, 18, 'asdasdasd', 0, 38, 1, 0, '2020-11-10 09:46:43', '0000-00-00 00:00:00'),
(27, 12, 1, 0, 9, 3, 'Approved by the clerk test application', 0, 8, 1, 0, '2020-11-18 07:12:26', '2020-11-26 07:12:26'),
(28, 12, 1, 0, 11, 8, 'Approved by Junior Engineer test application', 0, 7, 1, 0, '2020-11-18 07:33:43', '0000-00-00 00:00:00'),
(29, 12, 1, 0, 12, 17, 'Approved by the Deputy Engineer test application', 0, 79, 1, 0, '2020-11-18 07:37:26', '2020-11-26 07:37:26'),
(30, 12, 1, 0, 13, 18, 'Approved by Executive Engineer test application', 0, 38, 1, 0, '2020-11-18 07:39:42', '0000-00-00 00:00:00'),
(31, 13, 1, 0, 9, 3, 'TEST APPLICATION APPROVED BY CLERK', 0, 8, 1, 0, '2020-11-18 09:42:40', '0000-00-00 00:00:00'),
(32, 13, 1, 0, 11, 8, 'test approved by jr. engineer', 0, 7, 1, 0, '2020-11-18 09:45:25', '2020-11-25 09:45:25'),
(33, 13, 1, 0, 12, 17, 'test application approved by deputy eng', 0, 79, 1, 0, '2020-11-18 09:46:30', '2020-11-30 09:46:30'),
(34, 13, 1, 0, 13, 18, 'test application approved by executive eng ', 0, 38, 1, 0, '2020-11-18 09:47:26', '2020-11-26 09:47:26'),
(35, 14, 1, 0, 9, 3, 'Approved  by clerk test application joint visit', 0, 8, 1, 0, '2020-11-18 10:09:50', '0000-00-00 00:00:00'),
(36, 14, 1, 0, 11, 8, 'approved by the je a joint visit', 0, 7, 1, 0, '2020-11-18 10:12:30', '2020-11-30 10:12:30'),
(37, 14, 1, 0, 12, 17, 'de joint visit', 0, 79, 1, 0, '2020-11-18 10:13:14', '2020-11-14 10:13:14'),
(38, 14, 1, 0, 13, 18, 'executive eng joint visit', 0, 38, 1, 0, '2020-11-18 10:14:40', '0000-00-00 00:00:00'),
(39, 15, 1, 0, 9, 3, 'approved by clerk extension', 0, 8, 1, 0, '2020-11-18 10:40:22', '2020-11-22 10:40:22'),
(40, 15, 1, 0, 11, 8, 'approved by je extension', 0, 7, 1, 0, '2020-11-18 10:41:22', '2020-11-22 10:41:22'),
(41, 15, 1, 0, 12, 17, 'approved by de extension', 0, 79, 1, 0, '2020-11-18 10:42:16', '2020-11-16 10:42:16'),
(42, 15, 1, 0, 13, 18, 'approved by se extension', 0, 38, 1, 0, '2020-11-18 10:42:52', '0000-00-00 00:00:00'),
(43, 21, 5, 1, 24, 3, 'sadsadsad', 0, 16, 1, 0, '2020-11-22 19:02:20', '2020-11-22 19:02:20'),
(44, 22, 5, 1, 24, 3, 'sadasdas', 0, 16, 1, 0, '2020-11-22 19:45:06', '2020-11-22 19:45:06'),
(45, 22, 5, 1, 25, 22, 'sasadsad', 0, 81, 1, 0, '2020-11-22 19:45:48', '2020-11-22 19:45:48'),
(46, 22, 5, 1, 26, 23, 'sdsadasds', 0, 83, 1, 0, '2020-11-22 20:21:02', '2020-11-22 20:21:02'),
(47, 22, 5, 1, 27, 24, 'sdasdsad', 0, 85, 1, 0, '2020-11-22 20:22:04', '2020-11-22 20:22:04'),
(48, 24, 5, 1, 24, 3, 'test add remark', 0, 16, 1, 0, '2020-11-22 20:52:44', '2020-11-22 20:52:44'),
(49, 24, 5, 1, 25, 22, 'qweqwesadasd', 0, 81, 1, 0, '2020-11-22 20:53:27', '2020-11-22 20:53:27'),
(50, 24, 5, 1, 26, 23, 'asdasdasd', 0, 83, 1, 0, '2020-11-22 21:20:35', '2020-11-22 21:20:35'),
(51, 24, 5, 1, 27, 24, 'ewqwq asdasdsa', 0, 85, 1, 0, '2020-11-22 21:21:13', '2020-11-22 21:21:13'),
(52, 23, 5, 1, 24, 3, 'group 1 officer please take charge test', 0, 11, 1, 0, '2020-11-23 03:55:31', '2020-11-23 03:55:31'),
(53, 23, 5, 1, 25, 22, 'approved by the health officer', 0, 81, 1, 0, '2020-11-23 04:09:40', '2020-11-23 04:09:40'),
(54, 23, 5, 1, 26, 23, 'approved by junior doctor test', 0, 83, 1, 0, '2020-11-23 04:10:25', '2020-11-23 04:10:25'),
(55, 23, 5, 1, 27, 24, 'approved by senior doctor test', 0, 85, 1, 0, '2020-11-23 04:11:12', '2020-11-23 04:11:12'),
(56, 23, 5, 1, 27, 24, 'approved by senior doctor test', 0, 85, 1, 0, '2020-11-23 04:11:17', '2020-11-23 04:11:17'),
(57, 34, 5, 1, 24, 3, 'approved and submitted to health offier ward 1', 0, 11, 1, 0, '2020-11-23 08:19:30', '2020-11-23 08:19:30'),
(58, 34, 5, 1, 25, 22, 'approved', 0, 81, 1, 0, '2020-11-23 08:22:37', '2020-11-23 08:22:37'),
(59, 34, 5, 1, 26, 23, 'approved by the junior doctor', 0, 83, 1, 0, '2020-11-23 08:30:45', '2020-11-23 08:30:45'),
(60, 34, 5, 1, 26, 23, 'approved by the junior doctor', 0, 83, 1, 0, '2020-11-23 08:30:53', '2020-11-23 08:30:53'),
(61, 34, 5, 1, 27, 24, 'approved', 0, 85, 1, 0, '2020-11-23 08:31:38', '2020-11-23 08:31:38'),
(62, 35, 5, 1, 24, 3, 'Approved', 0, 16, 1, 0, '2020-11-23 11:02:12', '2020-11-23 11:02:12'),
(63, 25, 5, 1, 24, 3, 'Pending', 0, 11, 1, 0, '2020-11-23 11:02:55', '2020-11-23 11:02:55'),
(64, 35, 5, 1, 25, 22, 'Approved', 0, 81, 1, 0, '2020-11-23 11:05:11', '2020-11-23 11:05:11'),
(69, 41, 5, 2, 24, 3, 'asdadadad', 0, 16, 1, 0, '2020-11-24 21:24:00', '0000-00-00 00:00:00'),
(70, 47, 5, 2, 24, 3, 'asdasdsad', 0, 16, 1, 0, '2020-11-25 12:43:22', '2020-11-22 12:43:22'),
(71, 51, 5, 1, 24, 3, 'qweqwsadsad sadasdqweqwe ', 0, 11, 1, 0, '2020-11-26 12:33:30', '2020-11-26 12:33:30'),
(72, 51, 5, 1, 25, 22, 'asdasdsadsad', 0, 81, 1, 0, '2020-11-26 12:43:25', '2020-11-26 12:43:25'),
(73, 51, 5, 1, 26, 23, 'asdadad', 0, 83, 1, 0, '2020-11-26 12:51:53', '2020-11-26 12:51:53'),
(74, 51, 5, 1, 27, 24, 'asdasdasdasd', 0, 85, 1, 0, '2020-11-26 12:52:19', '2020-11-26 12:52:19'),
(75, 53, 5, 1, 24, 3, 'Hello world', 0, 16, 1, 0, '2020-11-26 17:27:37', '2020-11-26 17:27:37'),
(76, 53, 5, 1, 25, 22, 'asdas asdasd', 0, 81, 1, 0, '2020-11-26 17:28:03', '2020-11-26 17:28:03'),
(77, 53, 5, 1, 26, 23, 'sadasdasd', 0, 83, 1, 0, '2020-11-26 17:32:21', '2020-11-26 17:32:21'),
(78, 53, 5, 1, 27, 24, 'asdasdasd', 0, 85, 1, 0, '2020-11-26 17:32:56', '2020-11-26 17:32:56'),
(79, 53, 5, 1, 27, 24, 'asdasdasd', 0, 85, 1, 0, '2020-11-26 17:34:31', '2020-11-26 17:34:31'),
(80, 3, 3, 3, 29, 3, 'Approved', 0, 28, 1, 0, '2020-11-27 16:40:39', '2020-11-27 16:40:39'),
(81, 3, 3, 3, 33, 15, 'asdasdasdsa', 0, 30, 1, 0, '2020-11-27 17:48:02', '2020-11-27 17:48:02'),
(82, 3, 3, 3, 34, 6, 'asdasdas', 1, 32, 1, 0, '2020-11-27 17:57:55', '2020-11-27 17:57:55'),
(83, 3, 3, 3, 35, 25, 'asdadasd', 1, 87, 1, 0, '2020-11-27 18:05:54', '2020-11-27 18:05:54'),
(84, 3, 3, 3, 36, 4, 'asdasdad', 1, 91, 1, 0, '2020-11-27 18:24:43', '2020-11-27 18:24:43'),
(85, 5, 3, 3, 29, 3, 'Approved by Clerk test', 0, 28, 1, 0, '2020-11-30 17:46:02', '2020-11-30 17:46:02'),
(86, 96, 5, 1, 24, 3, 'sadsadas asdsadasd', 0, 16, 1, 0, '2020-12-04 20:55:30', '2020-12-04 20:55:30'),
(87, 99, 5, 1, 24, 3, 'adasdasdsa', 0, 16, 1, 0, '2020-12-04 20:55:47', '2020-12-04 20:55:47'),
(88, 96, 5, 1, 25, 22, 'qwewq asdasd adasdas', 0, 81, 1, 0, '2020-12-04 20:57:05', '2020-12-04 20:57:05'),
(89, 99, 5, 1, 25, 22, 'dasd adasdas', 0, 81, 1, 0, '2020-12-04 20:58:28', '2020-12-04 20:58:28'),
(90, 99, 5, 1, 26, 23, 'asdasdad', 0, 83, 1, 0, '2020-12-04 21:00:58', '2020-12-04 21:00:58'),
(91, 99, 5, 1, 27, 24, 'asdasdad', 0, 85, 1, 0, '2020-12-04 21:01:24', '2020-12-04 21:01:24'),
(92, 100, 5, 1, 24, 3, 'Approved by the clerk and sent to the Medical Officer of Ward B ', 0, 16, 1, 0, '2020-12-05 11:00:36', '2020-12-05 11:00:36'),
(93, 100, 5, 1, 25, 22, 'approved by health officer', 0, 81, 1, 0, '2020-12-05 11:09:47', '2020-12-05 11:09:47'),
(94, 101, 5, 1, 24, 3, 'Approved by the Clerk and submitted to Ward B ', 0, 16, 1, 0, '2020-12-05 11:17:28', '2020-12-05 11:17:28'),
(95, 104, 5, 1, 24, 3, 'Sonam sharma application has been approved', 0, 16, 1, 0, '2020-12-05 18:00:31', '2020-12-05 18:00:31'),
(96, 104, 5, 1, 25, 22, 'asdasdasd', 0, 81, 1, 0, '2020-12-05 18:02:27', '2020-12-05 18:02:27'),
(97, 105, 5, 1, 24, 3, 'This application is verified by the clerk test', 0, 16, 1, 0, '2020-12-07 14:56:01', '2020-12-07 14:56:01'),
(99, 106, 5, 1, 24, 3, 'check ward', 0, 16, 1, 0, '2020-12-07 15:22:59', '2020-12-07 15:22:59'),
(100, 105, 5, 1, 25, 22, 'Approved by the Health Officer test ', 0, 81, 1, 0, '2020-12-07 15:47:41', '2020-12-07 15:47:41'),
(104, 105, 5, 1, 26, 23, 'Approved by the Junior Doctor Test ', 0, 83, 1, 0, '2020-12-07 16:19:39', '2020-12-07 16:19:39'),
(105, 105, 5, 1, 27, 24, 'Approved ', 0, 85, 1, 0, '2020-12-07 16:20:23', '2020-12-07 16:20:23'),
(107, 107, 5, 1, 24, 3, 'Approved by the Clerk test for clinic ', 0, 16, 1, 0, '2020-12-07 17:24:50', '2020-12-07 17:24:50'),
(108, 107, 5, 1, 25, 22, 'Approved by the Health Officer Clinic Test ', 0, 81, 1, 0, '2020-12-07 17:25:55', '2020-12-07 17:25:55'),
(109, 108, 5, 1, 24, 3, 'Hospital Approved by the clerk test ', 0, 16, 1, 0, '2020-12-07 17:42:42', '2020-12-07 17:42:42'),
(110, 108, 5, 1, 25, 22, 'Approved by the Health Officer Test ', 0, 81, 1, 0, '2020-12-07 17:43:50', '2020-12-07 17:43:50'),
(111, 84, 5, 1, 24, 3, 'sadasdas asdasdsad', 0, 16, 1, 0, '2020-12-07 17:45:09', '2020-12-07 17:45:09'),
(112, 84, 5, 1, 25, 22, 'sadasdasd', 0, 81, 1, 0, '2020-12-07 17:46:26', '2020-12-07 17:46:26'),
(113, 108, 5, 1, 26, 23, 'Approved by the Junior Officer test ', 0, 83, 1, 0, '2020-12-07 17:47:24', '2020-12-07 17:47:24'),
(114, 108, 5, 1, 27, 24, 'Approved by the Senior test ', 0, 85, 1, 0, '2020-12-07 17:48:11', '2020-12-07 17:48:11'),
(115, 109, 5, 1, 24, 3, 'asdasdas asdasdsad', 0, 16, 1, 0, '2020-12-07 17:58:21', '2020-12-07 17:58:21'),
(116, 109, 5, 1, 25, 22, 'asdasdad', 0, 81, 1, 0, '2020-12-07 17:59:14', '2020-12-07 17:59:14'),
(117, 109, 5, 1, 26, 23, 'asdasdasd', 0, 83, 1, 0, '2020-12-07 18:01:03', '2020-12-07 18:01:03'),
(118, 109, 5, 1, 27, 24, 'asdas dasdasdasd', 0, 85, 1, 0, '2020-12-07 18:01:44', '2020-12-07 18:01:44'),
(119, 109, 5, 1, 27, 24, 'sadasdasd', 0, 85, 1, 0, '2020-12-07 18:02:02', '2020-12-07 18:02:02'),
(120, 110, 5, 1, 24, 3, 'asdas asdasdas asdsad', 0, 16, 1, 0, '2020-12-07 18:50:19', '2020-12-07 18:50:19'),
(121, 110, 5, 1, 25, 22, 'asdsa asdasd', 0, 81, 1, 0, '2020-12-07 18:51:08', '2020-12-07 18:51:08'),
(122, 111, 5, 1, 24, 3, 'asdsa sadsad asdsad', 0, 16, 1, 0, '2020-12-07 19:31:43', '2020-12-07 19:31:43'),
(123, 111, 5, 1, 25, 22, 'Approved by ho', 0, 81, 1, 0, '2020-12-07 19:32:32', '2020-12-07 19:32:32'),
(124, 111, 5, 1, 26, 23, 'asdas asdsad', 0, 83, 1, 0, '2020-12-07 20:01:20', '2020-12-07 20:01:20'),
(125, 111, 5, 1, 27, 24, 'asdasdad', 0, 85, 1, 0, '2020-12-07 20:08:41', '2020-12-07 20:08:41'),
(126, 111, 5, 1, 27, 24, 'adasdaada', 0, 85, 1, 0, '2020-12-07 20:09:55', '2020-12-07 20:09:55'),
(127, 112, 5, 1, 24, 3, 'Hello world', 0, 16, 1, 0, '2020-12-07 20:40:17', '2020-12-07 20:40:17'),
(128, 112, 5, 1, 25, 22, 'Approved', 0, 81, 1, 0, '2020-12-07 20:41:10', '2020-12-07 20:41:10'),
(129, 112, 5, 1, 26, 23, 'sada sdasdasd', 0, 83, 1, 0, '2020-12-07 20:44:51', '2020-12-07 20:44:51'),
(130, 112, 5, 1, 26, 23, 'asdasdasdasdasdasdasdasd', 0, 83, 1, 0, '2020-12-07 20:57:17', '2020-12-07 20:57:17'),
(131, 112, 5, 1, 26, 23, 'asdasdsa', 0, 83, 1, 0, '2020-12-07 20:57:27', '2020-12-07 20:57:27'),
(132, 112, 5, 1, 27, 24, 'final approvel', 0, 85, 1, 0, '2020-12-07 20:57:59', '2020-12-07 20:57:59'),
(133, 113, 5, 1, 24, 3, 'sadsad sadasd sadsad', 0, 16, 1, 0, '2020-12-07 21:18:32', '2020-12-07 21:18:32'),
(134, 113, 5, 1, 25, 22, 'sdas adasdas', 0, 81, 1, 0, '2020-12-07 21:20:56', '2020-12-07 21:20:56'),
(135, 113, 5, 1, 26, 23, 'asdasda adadasd', 0, 83, 1, 0, '2020-12-07 21:35:16', '2020-12-07 21:35:16'),
(136, 113, 5, 1, 27, 24, 'adasdasdasd', 0, 85, 1, 0, '2020-12-07 21:35:46', '2020-12-07 21:35:46'),
(137, 115, 5, 1, 24, 3, 'Approved', 0, 16, 1, 0, '2020-12-08 09:58:18', '2020-12-08 09:58:18'),
(138, 115, 5, 1, 25, 22, 'adadasdasd', 0, 81, 1, 0, '2020-12-08 09:58:58', '2020-12-08 09:58:58'),
(139, 115, 5, 1, 26, 23, 'hgjgjgjgjgjhg', 0, 83, 1, 0, '2020-12-08 10:01:07', '2020-12-08 10:01:07'),
(140, 115, 5, 1, 27, 24, 'fhfh fhgfhg', 0, 85, 1, 0, '2020-12-08 10:01:30', '2020-12-08 10:01:30'),
(141, 113, 5, 1, 27, 24, 'adadasdasd', 0, 85, 1, 0, '2020-12-08 11:00:40', '2020-12-08 11:00:40'),
(142, 116, 5, 1, 24, 3, 'Approved by the clerk ', 0, 16, 1, 0, '2020-12-08 12:44:01', '2020-12-08 12:44:01'),
(143, 116, 5, 1, 25, 22, 'Approved ', 0, 81, 1, 0, '2020-12-08 12:45:03', '2020-12-08 12:45:03'),
(144, 114, 5, 1, 24, 3, 'adas asdasd', 0, 16, 1, 0, '2020-12-08 13:05:42', '2020-12-08 13:05:42'),
(145, 117, 5, 1, 24, 3, 'Approved test', 0, 16, 1, 0, '2020-12-08 13:10:30', '2020-12-08 13:10:30'),
(146, 117, 5, 1, 25, 22, 'Approvd test ', 0, 81, 1, 0, '2020-12-08 13:12:28', '2020-12-08 13:12:28'),
(147, 117, 5, 1, 26, 23, 'Approved by the Junior Doctor ', 0, 83, 1, 0, '2020-12-08 13:17:18', '2020-12-08 13:17:18'),
(148, 117, 5, 1, 27, 24, 'Approved by senior doctor ', 0, 85, 1, 0, '2020-12-08 13:18:05', '2020-12-08 13:18:05'),
(149, 118, 5, 1, 24, 3, 'Approved test ', 0, 16, 1, 0, '2020-12-08 13:25:35', '2020-12-08 13:25:35'),
(150, 118, 5, 1, 25, 22, 'Approved test for renewal', 0, 81, 1, 0, '2020-12-08 13:27:50', '2020-12-08 13:27:50'),
(151, 114, 5, 1, 25, 22, 'asdadasdasd', 0, 81, 1, 0, '2020-12-08 13:51:19', '2020-12-08 13:51:19'),
(152, 119, 5, 1, 24, 3, 'Approved by the clerk ', 0, 16, 1, 0, '2020-12-08 14:47:14', '2020-12-08 14:47:14'),
(153, 119, 5, 1, 25, 22, 'Approved by the medical officer ', 0, 81, 1, 0, '2020-12-08 14:48:09', '2020-12-08 14:48:09'),
(154, 119, 5, 1, 26, 23, 'Approved test ', 0, 83, 1, 0, '2020-12-08 15:15:54', '2020-12-08 15:15:54'),
(155, 119, 5, 1, 27, 24, 'Approved ', 0, 85, 1, 0, '2020-12-08 15:16:35', '2020-12-08 15:16:35'),
(156, 120, 5, 1, 24, 3, 'Approved for Lab ', 0, 16, 1, 0, '2020-12-08 15:51:16', '2020-12-08 15:51:16'),
(157, 120, 5, 1, 25, 22, 'DGHSFGHKJFHSLJK', 0, 81, 1, 0, '2020-12-08 15:52:12', '2020-12-08 15:52:12'),
(158, 121, 5, 1, 24, 3, 'bcbhfdbfdb', 0, 16, 1, 0, '2020-12-08 16:42:12', '2020-12-08 16:42:12'),
(159, 121, 5, 1, 25, 22, 'xvdgdsgsdb', 0, 81, 1, 0, '2020-12-08 16:42:48', '2020-12-08 16:42:48'),
(160, 122, 5, 1, 24, 3, 'GFJHGCKJS,SD', 0, 16, 1, 0, '2020-12-08 16:59:45', '2020-12-08 16:59:45'),
(161, 122, 5, 1, 25, 22, 'zvcxcvx', 0, 81, 1, 0, '2020-12-08 17:00:29', '2020-12-08 17:00:29'),
(162, 97, 5, 1, 24, 3, 'Accepted CL', 0, 16, 1, 0, '2020-12-08 17:14:47', '2020-12-08 17:14:47'),
(163, 123, 5, 1, 24, 3, 'WARD B ', 0, 16, 1, 0, '2020-12-08 17:54:40', '2020-12-08 17:54:40'),
(164, 123, 5, 1, 25, 22, 'SDSHDJHD', 0, 81, 1, 0, '2020-12-08 17:57:32', '2020-12-08 17:57:32'),
(165, 124, 5, 1, 24, 3, 'fdhgdfhrh', 0, 16, 1, 0, '2020-12-08 18:13:42', '2020-12-08 18:13:42'),
(166, 124, 5, 1, 25, 22, 'hgjmgfjkfkj', 0, 81, 1, 0, '2020-12-08 18:15:24', '2020-12-08 18:15:24'),
(167, 124, 5, 1, 26, 23, 'dgdfhd', 0, 83, 1, 0, '2020-12-08 18:23:32', '2020-12-08 18:23:32'),
(168, 124, 5, 1, 27, 24, 'xdfbgvdf', 0, 85, 1, 0, '2020-12-08 18:24:11', '2020-12-08 18:24:11'),
(169, 124, 5, 1, 27, 24, 'xdfbgvdf', 0, 85, 1, 0, '2020-12-08 18:24:31', '2020-12-08 18:24:31'),
(170, 115, 5, 1, 27, 24, 'asdasdasd asdsadsad', 0, 85, 1, 0, '2020-12-08 18:30:30', '2020-12-08 18:30:30'),
(171, 125, 5, 1, 24, 3, 'Approved ', 0, 16, 1, 0, '2020-12-08 18:36:56', '2020-12-08 18:36:56'),
(172, 125, 5, 1, 25, 22, 'sdfggfh', 0, 81, 1, 0, '2020-12-08 18:37:57', '2020-12-08 18:37:57'),
(173, 125, 5, 1, 26, 23, 'ukhjk', 0, 83, 1, 0, '2020-12-08 18:39:48', '2020-12-08 18:39:48'),
(174, 125, 5, 1, 27, 24, 'jukyikyi', 0, 85, 1, 0, '2020-12-08 18:40:04', '2020-12-08 18:40:04'),
(175, 125, 5, 1, 27, 24, 'jukyikyi', 0, 85, 1, 0, '2020-12-08 18:40:19', '2020-12-08 18:40:19'),
(191, 130, 12, 0, 43, 3, 'asdasdsad', 0, 97, 1, 0, '2020-12-11 19:09:22', '2020-12-22 19:09:22'),
(192, 130, 12, 0, 45, 10, 'asdasdadad', 0, 100, 1, 0, '2020-12-11 19:19:53', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `app_premssion`
--

CREATE TABLE `app_premssion` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `app_title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `app_routes`
--

CREATE TABLE `app_routes` (
  `id` bigint(20) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `grp_index` int(11) NOT NULL,
  `slug` varchar(192) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sub_slug` varchar(255) NOT NULL,
  `controller` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(255) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_routes`
--

INSERT INTO `app_routes` (`id`, `dept_id`, `grp_index`, `slug`, `sub_slug`, `controller`, `method`, `short_desc`, `created_at`, `updated_at`, `status`, `is_deleted`) VALUES
(1, 0, 0, 'login', '', 'adminController', 'login_view', 'login page', '2020-03-19 17:17:09', '0000-00-00 00:00:00', 1, 0),
(2, 0, 0, 'add', 'users', 'usersController', 'add', 'add', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(3, 0, 0, 'login_check', '', 'adminController', 'login_check', 'login logic', '2020-03-19 17:17:18', '0000-00-00 00:00:00', 1, 0),
(4, 0, 0, 'addusers', '', 'adminController', 'addUserDetails', 'registration logic', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(5, 0, 0, 'roles', '', 'rolesController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(6, 0, 0, 'getlist', 'role', 'rolesController', 'get_lists', 'roles table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(7, 0, 0, 'save', 'role', 'rolesController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(8, 0, 0, 'update', 'role', 'rolesController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(9, 0, 0, 'applications', '', 'ApplicationsController', 'index', 'applications data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(10, 1, 1, 'pwd', '', 'PwdController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(11, 1, 2, 'create', 'pwd', 'PwdController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(12, 0, 0, 'departments', '', 'departmentsController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(13, 0, 0, 'getlist', 'dept', 'departmentsController', 'get_lists', 'department table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(14, 0, 0, 'save', 'dept', 'departmentsController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(15, 0, 0, 'update', 'dept', 'departmentsController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(16, 1, 0, 'save', 'pwd', 'PwdController', 'save', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(17, 1, 0, 'getlist', 'pwd', 'PwdController', 'get_lists', 'table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(18, 0, 0, '403', 'error', 'MyerrorController', 'access_denied', 'access denied', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(19, 0, 0, 'logout', '', 'adminController', 'logout', 'logout page', '2020-03-19 17:17:09', '0000-00-00 00:00:00', 1, 0),
(20, 1, 0, 'addremarks', 'pwd', 'PwdController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(21, 0, 0, 'getStatusByDeptRole', 'status', 'StatusController', 'get_status_by_dept_role', 'status', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(22, 0, 0, 'users', '', 'usersController', 'index', 'users data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(23, 0, 0, 'getlist', 'users', 'usersController', 'get_lists', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(24, 0, 0, 'update', 'users', 'usersController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(25, 0, 0, 'edit', 'users', 'usersController', 'edit', 'edit', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(26, 0, 0, 'save', 'users', 'usersController', 'save', 'save', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(27, 0, 0, 'remarksbyid', 'remarks', 'RemarksController', 'get_app_remarks_by_id', 'app remarks', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(28, 0, 0, 'road', '', 'roadController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(29, 0, 0, 'getlist', 'road', 'roadController', 'get_lists', 'data table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(30, 0, 0, 'save', 'road', 'roadController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(31, 0, 0, 'update', 'road', 'roadController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(32, 1, 3, 'edit', 'pwd', 'PwdController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(33, 0, 0, 'status', '', 'StatusController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(34, 0, 0, 'getlist', 'status', 'StatusController', 'get_lists', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(35, 0, 0, 'update', 'status', 'StatusController', 'update', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(36, 0, 0, 'edit', 'status', 'StatusController', 'edit', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(37, 0, 0, 'create', 'status', 'StatusController', 'create', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(38, 0, 0, 'save', 'status', 'StatusController', 'save', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(39, 0, 0, 'create', 'road', 'roadController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(40, 0, 0, 'edit', 'road', 'roadController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(41, 0, 0, 'create', 'role', 'rolesController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(42, 0, 0, 'edit', 'role', 'rolesController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(43, 0, 0, 'create', 'dept', 'departmentsController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(44, 0, 0, 'edit', 'dept', 'departmentsController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(45, 6, 1, 'hall', '', 'hallController', 'index', 'hall data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(46, 6, 2, 'create', 'hall', 'hallController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(47, 6, 3, 'edit', 'hall', 'hallController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(48, 6, 0, 'update', 'hall', 'hallController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(49, 6, 0, 'save', 'hall', 'hallController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(50, 6, 0, 'getlist', 'hall', 'hallController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(51, 0, 0, 'create', 'sku', 'skuController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(52, 0, 0, 'edit', 'sku', 'skuController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(53, 0, 0, 'update', 'sku', 'skuController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(54, 0, 0, 'save', 'sku', 'skuController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(55, 0, 0, 'getlist', 'sku', 'skuController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(56, 0, 0, 'create', 'price', 'priceController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(57, 0, 0, 'edit', 'price', 'priceController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(58, 0, 0, 'update', 'price', 'priceController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(59, 0, 0, 'save', 'price', 'priceController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(60, 0, 0, 'getlist', 'price', 'priceController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(61, 0, 0, 'sku', '', 'skuController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(62, 0, 0, 'price', '', 'priceController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(63, 0, 0, 'getsku', 'price', 'priceController', 'sku_by_dept', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(64, 0, 0, 'getprice', 'price', 'priceController', 'price_by_sku', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(65, 6, 0, 'getbookedhall', 'hall', 'hallController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(66, 6, 0, 'addremarks', 'hall', 'hallController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(67, 0, 0, 'create', 'unit', 'unitController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(68, 0, 0, 'edit', 'unit', 'unitController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(69, 0, 0, 'update', 'unit', 'unitController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(70, 0, 0, 'save', 'unit', 'unitController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(71, 0, 0, 'getlist', 'unit', 'unitController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(72, 0, 0, 'unit', '', 'unitController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(73, 0, 0, 'getunit', 'unit', 'unitController', 'get_unit', 'unit details', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(74, 6, 0, 'checklist', 'hall', 'hallController', 'check_list', 'checklist', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(75, 3, 1, 'garden', '', 'TreeCuttingController', 'index', 'Tree Cutting Section', '2020-04-03 17:17:21', '0000-00-00 00:00:00', 1, 0),
(76, 3, 2, 'create', 'garden', 'TreeCuttingController', 'createComplaint', 'Tree Cutting Section', '2020-04-03 17:17:21', '0000-00-00 00:00:00', 1, 0),
(77, 3, 0, 'addTree', 'garden', 'TreeCuttingController', 'addTree', 'Tree add', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(78, 3, 0, 'addProcess', 'garden', 'TreeCuttingController', 'addProcess', 'Add process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(79, 3, 0, 'treeSubmit', 'garden', 'TreeCuttingController', 'treeSubmit', 'Add Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(80, 3, 0, 'deleteTree', 'garden', 'TreeCuttingController', 'deleteTree', 'Delete Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(81, 3, 0, 'deactivateTree', 'garden', 'TreeCuttingController', 'deactivateTree', 'Deactivate Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(82, 3, 0, 'treeEdit', 'garden', 'TreeCuttingController', 'treeEdit', 'Deactivate Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(83, 3, 0, 'processSubmit', 'garden', 'TreeCuttingController', 'processSubmit', 'Create Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(84, 3, 0, 'processDelete', 'garden', 'TreeCuttingController', 'processDelete', 'Delete Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(85, 3, 0, 'processdeactivate', 'garden', 'TreeCuttingController', 'processdeactivate', 'Deactivate Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(86, 3, 0, 'processEdits', 'garden', 'TreeCuttingController', 'processEdits', 'Edit Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(87, 3, 0, 'getTreeName', 'garden', 'TreeCuttingController', 'getTreeName', 'Tree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(88, 3, 0, 'getProcessName', 'garden', 'TreeCuttingController', 'getProcessName', 'Process Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(89, 3, 0, 'submitComplaint', 'garden', 'TreeCuttingController', 'submitComplaint', 'Submit Complaint', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(90, 3, 3, 'edits', 'garden', 'TreeCuttingController', 'editApps', 'Edit Complaint', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(91, 3, 0, 'gardenDelete', 'garden', 'TreeCuttingController', 'gardenDelete', 'Delete Garden', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(92, 3, 0, 'complainEdit', 'garden', 'TreeCuttingController', 'complainEdit', 'Complain Edit', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(93, 3, 4, 'complainDelete', 'garden', 'TreeCuttingController', 'complainDelete', 'Complain Delete', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(94, 3, 0, 'getGardenDataById', 'garden', 'TreeCuttingController', 'getGardenDataById', 'Garden Data', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(95, 3, 0, 'complainApprove', 'garden', 'TreeCuttingController', 'complainApprove', 'Approve Complain', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(96, 3, 0, 'remarksGet', 'garden', 'TreeCuttingController', 'remarksGet', 'Get Remarks', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(97, 6, 0, 'asset-save', 'hall', 'hallController', 'asset_save', 'asset save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(98, 10, 1, 'templic', '', 'TempLicController', 'index', 'Temp Lic Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(99, 10, 0, 'renewApp', 'templic', 'TempLicController', 'renewApp', 'Renew Application', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(100, 0, 0, 'addLicType', 'templic', 'TempLicController', 'addLicType', 'Add License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(101, 10, 0, 'licSubmit', 'templic', 'TempLicController', 'licSubmit', 'Add License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(102, 10, 4, 'deleteLic', 'templic', 'TempLicController', 'deleteLic', 'Delete License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(103, 10, 0, 'deactivateLic', 'templic', 'TempLicController', 'deactivateLic', 'Delete License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(104, 10, 0, 'licEdit', 'templic', 'TempLicController', 'licEdit', 'Edit License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(105, 10, 2, 'createLic', 'templic', 'TempLicController', 'createLic', 'Create License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(106, 10, 0, 'createApplication', 'templic', 'TempLicController', 'createApplication', 'Create License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(107, 10, 0, 'renewApplication', 'templic', 'TempLicController', 'renewApplication', 'Renew License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(108, 10, 0, 'deleteApplication', 'templic', 'TempLicController', 'deleteApplication', 'Renew License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(109, 10, 3, 'edit', 'templic', 'TempLicController', 'editApplication', 'edit License', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(110, 10, 0, 'editApplicationRenew', 'templic', 'TempLicController', 'editApplicationRenew', 'edit License', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(111, 3, 0, 'getAppStatus', 'garden', 'TreeCuttingController', 'getAppStatus', 'Approval Status', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(114, 5, 1, 'hospital', '', 'HospitalController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(115, 0, 0, 'imagedetails', 'image', 'imagedetailsController', 'getimagedetails', 'get image details', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(116, 5, 2, 'create', 'hospital', 'HospitalController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(117, 0, 0, 'get-designation', 'designation', 'designationController', 'get_designation', 'get_designation', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(118, 0, 0, 'get-qualification', 'qualification', 'qualificationController', 'get_qualification', 'qualification', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(119, 5, 0, 'save', 'hospital', 'hospitalController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(120, 5, 0, 'getlist', 'hospital', 'hospitalController', 'get_lists', 'hospital table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(121, 5, 3, 'edit', 'hospital', 'HospitalController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(122, 5, 0, 'addremarks', 'hospital', 'hospitalController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(123, 5, 0, 'staffDelete', 'hospital', 'hospitalController', 'staff_delete', 'staff delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(124, 5, 1, 'clinic', '', 'clinicController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(125, 5, 2, 'create', 'clinic', 'clinicController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(126, 5, 0, 'save', 'clinic', 'clinicController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(127, 5, 0, 'getlist', 'clinic', 'clinicController', 'get_lists', 'clinic table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(128, 5, 3, 'edit', 'clinic', 'clinicController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(129, 5, 0, 'addremarks', 'clinic', 'clinicController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(130, 5, 0, 'staffDelete', 'clinic', 'clinicController', 'staff_delete', 'staff delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(131, 5, 1, 'lab', '', 'labController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(132, 5, 2, 'create', 'lab', 'labController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(133, 5, 0, 'save', 'lab', 'labController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(134, 5, 0, 'getlist', 'lab', 'labController', 'get_lists', 'lab table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(135, 5, 3, 'edit', 'lab', 'labController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(136, 5, 0, 'addremarks', 'lab', 'labController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(137, 7, 1, 'tradefactlic', '', 'TradeFactLicController', 'index', 'Trade Factory Lic Section', '2020-04-15 17:17:21', '2020-04-15 17:17:21', 1, 0),
(138, 7, 2, 'create', 'tradefactlic', 'TradeFactLicController', 'create', 'create', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(139, 7, 0, 'createFactLic', 'tradefactlic', 'TradeFactLicController', 'createFactLic', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(140, 7, 3, 'edit', 'tradefactlic', 'TradeFactLicController', 'editLic', 'edit License', '2020-03-19 17:17:21', '2020-04-15 17:17:21', 1, 0),
(141, 7, 0, 'editFactLic', 'tradefactlic', 'TradeFactLicController', 'editFactLic', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(142, 7, 0, 'getData', 'tradefactlic', 'TradeFactLicController', 'getData', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(143, 7, 0, 'getRemarks', 'tradefactlic', 'TradeFactLicController', 'getRemarks', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(144, 7, 0, 'getAppStatus', 'tradefactlic', 'TradeFactLicController', 'getAppStatus', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(145, 7, 0, 'approveComplain', 'tradefactlic', 'TradeFactLicController', 'approveComplain', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(146, 3, 0, 'getData', 'garden', 'TreeCuttingController', 'getData', 'Get Data', '2020-04-04 17:17:21', '2020-04-04 17:17:21', 1, 0),
(148, 10, 0, 'getAppStatus', 'templic', 'TempLicController', 'getAppStatus', 'appstatus', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(149, 10, 0, 'approveComplain', 'templic', 'TempLicController', 'approveComplain', 'approval', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(150, 8, 2, 'create', 'godown', 'godownController', 'create', 'Godown Lic', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(151, 10, 0, 'getAppStatus', 'templic', 'TempLicController', 'getAppStatus', 'appstatus', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(152, 10, 0, 'approveComplain', 'templic', 'TempLicController', 'approveComplain', 'approval', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(153, 8, 0, 'addStorage', 'godown', 'godownController', 'addStorage', 'Godown Storage', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(154, 8, 0, 'getDataByLic', 'godown', 'godownController', 'getDataByLic', 'appData', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(155, 8, 3, 'edit', 'godown', 'godownController', 'editApp', 'edit storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(156, 8, 0, 'editStorage', 'godown', 'godownController', 'editStorage', 'edit storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(157, 8, 0, 'getData', 'godown', 'godownController', 'getData', 'Get storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(158, 8, 1, 'godown', '', 'godownController', 'index', 'Godown Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(159, 8, 4, 'delStorage', 'godown', 'godownController', 'delStorage', 'Delete storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(160, 8, 0, 'getAppStatus', 'godown', 'godownController', 'getAppStatus', 'status storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(161, 8, 0, 'approveComplain', 'godown', 'godownController', 'approveComplain', 'stat storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(162, 6, 0, 'create', 'hall-service', 'hallServiceController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(163, 6, 0, 'edit', 'hall-service', 'hallServiceController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(164, 6, 0, 'update', 'hall-service', 'hallServiceController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(165, 6, 0, 'save', 'hall-service', 'hallServiceController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(166, 6, 0, 'getlist', 'hall-service', 'hallServiceController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(167, 6, 0, 'hall-service', '', 'hallServiceController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(168, 6, 0, 'getsku', 'hallService', 'hallServiceController', 'sku_by_dept', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(169, 6, 0, 'getprice', 'hall-service', 'hallServiceController', 'price_by_sku', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(170, 0, 0, 'create', 'designation', 'designationController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(171, 0, 0, 'edit', 'designation', 'designationController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(172, 0, 0, 'update', 'designation', 'designationController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(173, 0, 0, 'save', 'designation', 'designationController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(174, 0, 0, 'getlist', 'designation', 'designationController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(175, 0, 0, 'designation', '', 'designationController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(178, 0, 0, 'create', 'qualification', 'qualificationController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(179, 0, 0, 'edit', 'qualification', 'qualificationController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(180, 0, 0, 'update', 'qualification', 'qualificationController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(181, 0, 0, 'save', 'qualification', 'qualificationController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(182, 0, 0, 'getlist', 'qualification', 'qualificationController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(183, 0, 0, 'qualification', '', 'qualificationController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(184, 9, 1, 'film', '', 'filmController', 'index', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(185, 9, 2, 'create', 'film', 'filmController', 'create', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(186, 9, 3, 'edit', 'film', 'filmController', 'edit', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(187, 9, 4, 'delete', 'film', 'filmController', 'delete', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(188, 9, 0, 'getData', 'film', 'filmController', 'getData', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(189, 9, 0, 'createFilmLic', 'film', 'filmController', 'createFilmLic', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(190, 9, 0, 'getRemarks', 'film', 'filmController', 'getRemarks', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(191, 9, 0, 'getAppStatus', 'film', 'filmController', 'getAppStatus', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(192, 9, 0, 'approveComplain', 'film', 'filmController', 'approveComplain', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(193, 9, 0, 'editFilmLic', 'film', 'filmController', 'editFilmLic', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(194, 7, 0, 'getLicData', 'tradefactlic', 'TradeFactLicController', 'getLicData', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(195, 10, 0, 'getData', 'templic', 'TempLicController', 'getData', 'approval', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(196, 10, 0, 'edits', 'templic', 'TempLicController', 'editLic', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(197, 11, 1, 'advertisement', '', 'AdvertisementController', 'index', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(198, 11, 2, 'create', 'advertisement', 'AdvertisementController', 'create', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(199, 11, 0, 'createApplications', 'advertisement', 'AdvertisementController', 'createApplications', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(200, 11, 0, 'getData', 'advertisement', 'AdvertisementController', 'getData', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(201, 11, 0, 'getAppStatus', 'advertisement', 'AdvertisementController', 'getAppStatus', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(202, 11, 0, 'approveComplain', 'advertisement', 'AdvertisementController', 'approveComplain', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(203, 11, 4, 'deleteApplication', 'advertisement', 'AdvertisementController', 'deleteApplication', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(204, 11, 3, 'edit', 'advertisement', 'AdvertisementController', 'editAdv', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(205, 11, 0, 'editApplications', 'advertisement', 'AdvertisementController', 'editApplications', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(206, 10, 0, 'getlictype', 'templic', 'TempLicController', 'getlictype', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(207, 10, 0, 'crLicType', 'templic', 'TempLicController', 'crLicType', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(208, 3, 0, 'editss', 'garden', 'TreeCuttingController', 'treeNameEdit', 'Edit Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(209, 3, 0, 'crTreeType', 'garden', 'TreeCuttingController', 'crTreeType', 'createTree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(210, 3, 0, 'treeNameSubmit', 'garden', 'TreeCuttingController', 'treeNameSubmit', 'createTree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(211, 3, 0, 'crProcessType', 'garden', 'TreeCuttingController', 'crProcessType', 'createProcess Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(212, 3, 0, 'edit', 'garden', 'TreeCuttingController', 'processNameEdit', 'Edit Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(213, 11, 0, 'adv_index', 'advertisement', 'AdvertisementController', 'adv_index', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(214, 11, 0, 'getadvtype', 'advertisement', 'AdvertisementController', 'getadvtype', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(215, 11, 0, 'deactivateAdv', 'advertisement', 'AdvertisementController', 'deactivateAdv', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(216, 11, 0, 'crAdvType', 'advertisement', 'AdvertisementController', 'crAdvType', 'Advertisement Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(217, 11, 0, 'edits', 'advertisement', 'AdvertisementController', 'advEdit', 'Edit Adv', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(218, 11, 0, 'advSubmit', 'advertisement', 'AdvertisementController', 'advSubmit', 'Edit Adv', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(219, 11, 0, 'advEditSubmit', 'advertisement', 'AdvertisementController', 'advEditSubmit', 'Edit Adv', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(220, 11, 0, 'illuminate_index', 'advertisement', 'AdvertisementController', 'illuminate_index', 'Illuminate', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(221, 11, 0, 'getilluminate', 'advertisement', 'AdvertisementController', 'getilluminate', 'Illuminate', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(222, 11, 0, 'deactivateill', 'advertisement', 'AdvertisementController', 'deactivateill', 'Illuminate', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(223, 11, 0, 'crIlluminate', 'advertisement', 'AdvertisementController', 'crIlluminate', 'Illuminate', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(224, 11, 0, 'illSubmit', 'advertisement', 'AdvertisementController', 'illSubmit', 'Illuminate', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(225, 11, 0, 'editss', 'advertisement', 'AdvertisementController', 'editIll', 'Edit Ill', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(226, 11, 0, 'illEditSubmit', 'advertisement', 'AdvertisementController', 'illEditSubmit', 'Edit Ill', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(227, 8, 0, 'remarksGet', 'godown', 'godownController', 'remarksGet', 'Remarks storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(228, 3, 0, 'getonlyTreeName', 'garden', 'TreeCuttingController', 'getonlyTreeName', 'get tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(229, 3, 0, 'getonlyProcessName', 'garden', 'TreeCuttingController', 'getonlyProcessName', 'get process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(230, 10, 0, 'getRemarks', 'templic', 'TempLicController', 'getRemarks', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(231, 7, 4, 'delete', 'tradefactlic', 'TradeFactLicController', 'delete', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(232, 0, 0, 'permissions', '', 'PermissionsController', 'index', 'index', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(233, 0, 0, 'getUserData', 'permissions', 'PermissionsController', 'getUserData', 'userdata', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(234, 0, 0, 'addPermission', 'permissions', 'PermissionsController', 'addPermission', 'submit permission', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(235, 1, 4, 'delete', 'pwd', 'PwdController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(236, 0, 0, 'reports', '', 'reportsController', 'index', 'index', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(237, 0, 0, 'getData', 'reports', 'reportsController', 'getData', 'get Data', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(238, 0, 0, 'payment', 'reports', 'reportsController', 'payment', 'payment', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(239, 13, 1, 'marriage', '', 'marriageController', 'index', 'marriage data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(240, 13, 2, 'create', 'marriage', 'marriageController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(241, 13, 3, 'edit', 'marriage', 'marriageController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(242, 13, 0, 'update', 'marriage', 'marriageController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(243, 13, 0, 'save', 'marriage', 'marriageController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(244, 13, 0, 'getlist', 'marriage', 'marriageController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(245, 13, 0, 'getbookedhall', 'marriage', 'marriageController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(246, 13, 0, 'addremarks', 'marriage', 'marriageController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 2, 0),
(247, 12, 1, 'mandap', '', 'mandapController', 'index', 'mandap data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(248, 12, 2, 'create', 'mandap', 'mandapController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(249, 12, 3, 'edit', 'mandap', 'mandapController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(250, 12, 0, 'update', 'mandap', 'mandapController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(251, 12, 0, 'save', 'mandap', 'mandapController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(252, 12, 0, 'getlist', 'mandap', 'mandapController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(253, 12, 0, 'getbookedhall', 'mandap', 'mandapController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(254, 12, 0, 'addremarks', 'mandap', 'mandapController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(255, 12, 0, 'checklist', 'mandap', 'mandapController', 'check_list', 'checklist', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(256, 12, 0, 'asset-save', 'mandap', 'mandapController', 'asset_save', 'asset save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(257, 12, 4, 'delete', 'mandap', 'mandapController', 'delete', 'delete\r\n', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(258, 0, 0, 'roles', 'dept', 'departmentsController', 'getRoles', 'get roles', '2020-08-25 17:17:21', '2020-08-25 17:17:21', 1, 0),
(259, 0, 0, 'rolestatus', '', 'RoleStatus', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(260, 0, 0, 'getData', 'rolestatus', 'RoleStatus', 'getData', 'get data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(261, 0, 0, 'submit', 'rolestatus', 'RoleStatus', 'submit', 'submit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(262, 0, 0, 'delete', 'rolestatus', 'RoleStatus', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(263, 0, 0, 'edit', 'rolestatus', 'RoleStatus', 'editApp', 'edit data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(265, 0, 0, 'create', 'rolestatus', 'RoleStatus', 'create', 'create roles', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(266, 0, 0, 'edit', 'garden_permission', 'Garden_permisson', 'edit_garden_permission', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(267, 0, 0, 'gardenpermission_edit_validation', '', 'Garden_permisson', 'gardenpermission_edit_validation', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(268, 0, 0, 'process_edit_permission', '', 'Garden_permisson', 'process_edit_permission', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(269, 0, 0, 'update_permission_status', '', 'Garden_permisson', 'update_permission_status', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(270, 0, 0, 'get_garden_permission_list', '', 'Garden_permisson', 'get_garden_permission_list', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(271, 0, 0, 'garden_permission_add_validation', '', 'Garden_permisson', 'garden_permission_add_validation', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(272, 0, 0, 'process_add_permission', '', 'Garden_permisson', 'process_add_permission', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(273, 0, 0, 'create', 'garden_permission', 'Garden_permisson', 'create_new_permission', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(274, 0, 0, 'garden_permission', '', 'Garden_permisson', 'index', 'garden permission master', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(275, 13, 1, 'marriage', '', 'marriageController', 'index', 'marriage data', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(276, 13, 2, 'create', 'marriage', 'marriageController', 'create', 'create', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(277, 13, 3, 'edit', 'marriage', 'marriageController', 'edit', 'edit', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(278, 13, 0, 'update', 'marriage', 'marriageController', 'update', 'update', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(279, 13, 0, 'save', 'marriage', 'marriageController', 'save', 'save', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(280, 13, 0, 'getlist', 'marriage', 'marriageController', 'get_list', 'get list', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(281, 13, 0, 'getbookedhall', 'marriage', 'marriageController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(282, 13, 0, 'addremarks', 'marriage', 'marriageController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '2020-03-19 17:17:21', 1, 0),
(283, 13, 0, 'update_status_remark', '', 'MarriageController', 'update_status_remark', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(284, 13, 0, 'add_remark_modal', '', 'MarriageController', 'add_remark_modal', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(285, 13, 0, 'get_marriage_application_remark', '', 'MarriageController', 'get_marriage_application_remark', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(286, 13, 0, 'update_marriage_application_status', '', 'MarriageController', 'update_marriage_application_status', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(287, 13, 0, 'edit_form_process', '', 'MarriageController', 'edit_form_process', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(288, 13, 0, 'marriage', 'edit', 'MarriageController', 'edit', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(289, 13, 0, 'marriage_datatable', '', 'MarriageController', 'get_marriage_data_table', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(290, 0, 0, 'edit_validate_contact', '', 'UsersController', 'edit_validate_contact', 'Test', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(291, 0, 0, 'user_email_validate_edit', '', 'UsersController', 'edit_validate_user_email', 'User email validation while edit user profil.', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(292, 0, 0, 'deleteRow', 'dept', 'departmentsController', 'deleteRow', 'delete permission row', '2020-08-27 17:17:21', '2020-08-27 17:17:21', 1, 0),
(293, 0, 0, 'validate_edit_dept', '', 'DepartmentsController', 'validate_edit_dept', 'validate edit dept.', '2020-08-24 00:39:10', '2020-08-24 00:39:10', 1, 0),
(294, 3, 2, 'getTreeNo', 'garden', 'TreeCuttingController', 'getTreeNo', 'Get New Tree No', '2020-09-02 11:28:21', '0000-00-00 00:00:00', 1, 0),
(295, 3, 2, 'getRefundableData', 'garden', 'TreeCuttingController', 'getRefundableData', 'Get Data For Refunds', '2020-09-05 12:24:21', '0000-00-00 00:00:00', 1, 0),
(296, 3, 2, 'approveRefund', 'garden', 'TreeCuttingController', 'approveRefund', 'Approve Refund', '2020-09-05 15:24:21', '0000-00-00 00:00:00', 1, 0),
(297, 3, 2, 'approveRefundCancel', 'garden', 'TreeCuttingController', 'approveRefundCancel', 'Approve Refund Cancel', '2020-09-07 11:12:21', '0000-00-00 00:00:00', 1, 0),
(298, 0, 0, 'register', '', 'adminController', 'register', 'register page', '2020-09-07 17:04:09', '2020-09-07 17:04:09', 1, 0),
(299, 0, 0, 'register_save', 'users', 'UsersController', 'register_save', 'register user', '2020-09-07 17:39:10', '2020-09-07 17:39:10', 1, 0),
(300, 0, 0, 'getRoleByDept', 'users', 'UsersController', 'getRoleByDept', 'Get Roles By Dept', '2020-09-10 17:39:10', '2020-09-10 17:39:10', 1, 0),
(301, 1, 4, 'pwduserlist', 'pwd', 'PwdController', 'pwduserlist', 'Pwd user list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(302, 1, 4, 'getUserApplicationList', 'pwd', 'PwdController', 'getUserApplicationList', 'get data user wise', '2020-03-30 17:17:21', '0000-00-00 00:00:00', 1, 0),
(304, 0, 0, 'defect_liab', '', 'DefectliabController', 'index', 'liab view', '2020-10-06 17:17:21', '0000-00-00 00:00:00', 1, 0),
(305, 0, 0, 'getlist', 'defect_liab', 'DefectliabController', 'getlist', 'liab view list', '2020-10-06 17:17:21', '0000-00-00 00:00:00', 1, 0),
(306, 0, 0, 'edit', 'defect_liab', 'DefectliabController', 'edit', 'edit laiblity', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(307, 0, 0, 'create', 'defect_liab', 'DefectliabController', 'create', 'liab new create', '2020-10-06 17:17:21', '0000-00-00 00:00:00', 1, 0),
(308, 0, 0, 'save', 'defect_liab', 'DefectliabController', 'save', 'liab save', '2020-10-06 17:17:21', '0000-00-00 00:00:00', 1, 0),
(309, 0, 0, 'deactivate', 'defect_liab', 'DefectliabController', 'deactivate', 'liab deactivate', '2020-10-06 17:17:21', '0000-00-00 00:00:00', 1, 0),
(310, 0, 0, 'company_details', '', 'CompanydetailsController', 'index', 'company details view', '2020-10-06 17:17:21', '2020-10-06 17:17:21', 1, 0),
(311, 0, 0, 'create', 'company_details', 'CompanydetailsController', 'create', 'company details new create', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(312, 0, 0, 'edit', 'company_details', 'CompanydetailsController', 'edit', 'edit company details', '2020-03-19 17:17:15', '2020-10-07 17:17:15', 1, 0),
(313, 0, 0, 'getData', 'company_details', 'CompanydetailsController', 'getData', 'company details get data', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(314, 0, 0, 'save', 'company_details', 'CompanydetailsController', 'save', 'company details save', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(315, 0, 0, 'deactivate', 'company_details', 'CompanydetailsController', 'deactivate', 'company details delete', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(316, 0, 0, 'addressList', 'company_details', 'CompanydetailsController', 'addressList', 'company details address list', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(317, 0, 0, 'deleteAddress', 'company_details', 'CompanydetailsController', 'deleteAddress', 'company details address delete', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(318, 0, 0, 'EditAddress', 'company_details', 'CompanydetailsController', 'EditAddress', 'company details edit address', '2020-10-06 17:17:21', '2020-10-07 17:17:21', 1, 0),
(319, 0, 0, 'pdf', '', 'pdfController', 'index', 'pdf controller', '2020-10-09 17:17:21', '2020-10-09 17:17:21', 1, 0),
(320, 0, 0, 'demand_note', 'pdf', 'pdfController', 'demand_note', 'demand note create', '2020-10-09 17:17:21', '2020-10-09 17:17:21', 1, 0),
(321, 0, 0, 'get_company_address', 'pwd', 'PwdController', 'getCompanyAddressByCompID', 'its post request for getting company address ', '2020-10-08 00:00:00', NULL, 1, 0),
(322, 0, 0, 'app_delete_by_user', 'pwd', 'PwdController', 'user_delete_app', 'post request for delete application', '2020-10-09 11:59:34', NULL, 1, 0),
(323, 0, 0, 'ward', '', 'WardController', 'index', 'ward index ppage data table', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(324, 0, 0, 'create', 'ward', 'WardController', 'create', 'Add new ward', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(325, 0, 0, 'edit/(:any)', 'ward', 'WardController', 'edit_ward_from', 'Ward form edit', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(326, 0, 0, 'get_roles', 'ward', 'WardController', 'get_roles_by_dept', 'ajax method for get role for ward create', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(327, 0, 0, 'create_ward_process', 'ward', 'WardController', 'create_ward_process', 'insert ward data to database opration', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(328, 0, 0, 'ward_datatable', 'ward', 'WardController', 'ward_datatable', 'ajax method for create ward data table', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(329, 0, 0, 'get_user_ward', 'userdata', 'UsersController', 'get_ward_by_dept_role', 'ajax method for for user create and get dynamic ward', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(330, 0, 0, 'update_ward_process', 'ward', 'WardController', 'update_ward_process', 'ward edit update process', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(331, 0, 0, 'delete', 'ward', 'WardController', 'delete_ward_process', 'soft delete ward ajax method', '2020-10-05 22:16:25', '2020-10-05 22:16:25', 1, 0),
(334, 0, 0, 'forgot_password', 'user_authentication', 'AdminController', 'forgot_password_form', 'forgot passwor email form which recive email id', '2020-10-14 10:44:31', NULL, 1, 0),
(335, 0, 0, 'forgot_password_process', 'user_authentication', 'AdminController', 'forgot_password_process', 'forgot password process and mail trigur', '2020-10-14 10:44:31', NULL, 1, 0),
(336, 0, 0, 'reset_password', 'user_authentication', 'AdminController', 'reset_password_form', 'reset password form after mail trigur', '2020-10-14 10:44:31', NULL, 1, 0),
(337, 0, 0, 'change_password_process', 'user_authentication', 'AdminController', 'change_password_process', 'users password will update', '2020-10-14 10:44:31', NULL, 1, 0),
(338, 0, 0, 'validate_keygen', 'user_authentication', 'AdminController', 'validate_keygen', 'users password will update', '2020-10-14 10:44:31', NULL, 1, 0),
(339, 0, 0, 'letters', '', 'LetterController', 'index', 'Generate Letters', '2020-10-16 11:17:21', '2020-10-16 11:17:21', 1, 0),
(340, 0, 0, 'permission_letter', 'letters', 'LetterController', 'permission_letter', 'permission letter', '2020-10-16 11:25:31', '2020-10-16 11:28:53', 1, 0),
(341, 0, 0, 'extension_letter', 'letters', 'LetterController', 'extension_letter', 'extension letter', '2020-10-16 15:25:31', '2020-10-16 15:28:53', 1, 0),
(342, 0, 0, 'jointvisit_letter', 'letters', 'LetterController', 'jointvisit_letter', 'length increase letter', '2020-10-16 15:25:31', '2020-10-16 15:28:53', 1, 0),
(343, 0, 0, 'file_close_process', 'pwd', 'PwdController', 'file_close_process', 'this method has benn regards to file closer.', '2020-11-03 11:48:42', '2020-11-03 11:48:42', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_routes_old`
--

CREATE TABLE `app_routes_old` (
  `id` bigint(20) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `grp_index` int(11) NOT NULL,
  `slug` varchar(192) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sub_slug` varchar(255) NOT NULL,
  `controller` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(255) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_routes_old`
--

INSERT INTO `app_routes_old` (`id`, `dept_id`, `grp_index`, `slug`, `sub_slug`, `controller`, `method`, `short_desc`, `created_at`, `updated_at`, `status`, `is_deleted`) VALUES
(1, 0, 0, 'login', '', 'adminController', 'login_view', 'login page', '2020-03-19 17:17:09', '0000-00-00 00:00:00', 1, 0),
(2, 0, 0, 'add', 'users', 'usersController', 'add', 'add', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(3, 0, 0, 'login_check', '', 'adminController', 'login_check', 'login logic', '2020-03-19 17:17:18', '0000-00-00 00:00:00', 1, 0),
(4, 0, 0, 'addusers', '', 'adminController', 'addUserDetails', 'registration logic', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(5, 0, 0, 'roles', '', 'rolesController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(6, 0, 0, 'getlist', 'role', 'rolesController', 'get_lists', 'roles table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(7, 0, 0, 'save', 'role', 'rolesController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(8, 0, 0, 'update', 'role', 'rolesController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(9, 0, 0, 'applications', '', 'ApplicationsController', 'index', 'applications data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(10, 1, 1, 'pwd', '', 'PwdController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(11, 1, 2, 'create', 'pwd', 'PwdController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(12, 0, 0, 'departments', '', 'departmentsController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(13, 0, 0, 'getlist', 'dept', 'departmentsController', 'get_lists', 'department table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(14, 0, 0, 'save', 'dept', 'departmentsController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(15, 0, 0, 'update', 'dept', 'departmentsController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(16, 1, 0, 'save', 'pwd', 'PwdController', 'save', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(17, 1, 0, 'getlist', 'pwd', 'PwdController', 'get_lists', 'table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(18, 0, 0, '403', 'error', 'MyerrorController', 'access_denied', 'access denied', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(19, 0, 0, 'logout', '', 'adminController', 'logout', 'logout page', '2020-03-19 17:17:09', '0000-00-00 00:00:00', 1, 0),
(20, 1, 0, 'addremarks', 'pwd', 'PwdController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(21, 0, 0, 'getStatusByDeptRole', 'status', 'StatusController', 'get_status_by_dept_role', 'status', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(22, 0, 0, 'users', '', 'usersController', 'index', 'users data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(23, 0, 0, 'getlist', 'users', 'usersController', 'get_lists', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(24, 0, 0, 'update', 'users', 'usersController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(25, 0, 3, 'edit', 'users', 'usersController', 'edit', 'edit', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(26, 0, 0, 'save', 'users', 'usersController', 'save', 'save', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(27, 0, 0, 'remarksbyid', 'remarks', 'RemarksController', 'get_app_remarks_by_id', 'app remarks', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(28, 0, 0, 'road', '', 'roadController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(29, 0, 0, 'getlist', 'road', 'roadController', 'get_lists', 'data table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(30, 0, 0, 'save', 'road', 'roadController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(31, 0, 0, 'update', 'road', 'roadController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(32, 1, 3, 'edit', 'pwd', 'PwdController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(33, 0, 0, 'status', '', 'StatusController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(34, 0, 0, 'getlist', 'status', 'StatusController', 'get_lists', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(35, 0, 0, 'update', 'status', 'StatusController', 'update', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(36, 0, 3, 'edit', 'status', 'StatusController', 'edit', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(37, 0, 2, 'create', 'status', 'StatusController', 'create', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(38, 0, 0, 'save', 'status', 'StatusController', 'save', 'status table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(39, 0, 2, 'create', 'road', 'roadController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(40, 0, 3, 'edit', 'road', 'roadController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(41, 0, 2, 'create', 'role', 'rolesController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(42, 0, 3, 'edit', 'role', 'rolesController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(43, 0, 2, 'create', 'dept', 'departmentsController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(44, 0, 3, 'edit', 'dept', 'departmentsController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(45, 6, 0, 'hall', '', 'hallController', 'index', 'hall data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(46, 6, 2, 'create', 'hall', 'hallController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(47, 6, 3, 'edit', 'hall', 'hallController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(48, 6, 0, 'update', 'hall', 'hallController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(49, 6, 0, 'save', 'hall', 'hallController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(50, 6, 0, 'getlist', 'hall', 'hallController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(51, 0, 2, 'create', 'sku', 'skuController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(52, 0, 3, 'edit', 'sku', 'skuController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(53, 0, 0, 'update', 'sku', 'skuController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(54, 0, 0, 'save', 'sku', 'skuController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(55, 0, 0, 'getlist', 'sku', 'skuController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(56, 0, 2, 'create', 'price', 'priceController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(57, 0, 3, 'edit', 'price', 'priceController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(58, 0, 0, 'update', 'price', 'priceController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(59, 0, 0, 'save', 'price', 'priceController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(60, 0, 0, 'getlist', 'price', 'priceController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(61, 0, 0, 'sku', '', 'skuController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(62, 0, 0, 'price', '', 'priceController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(63, 0, 0, 'getsku', 'price', 'priceController', 'sku_by_dept', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(64, 0, 0, 'getprice', 'price', 'priceController', 'price_by_sku', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(65, 6, 0, 'getbookedhall', 'hall', 'hallController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(66, 6, 0, 'addremarks', 'hall', 'hallController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(67, 0, 2, 'create', 'unit', 'unitController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(68, 0, 3, 'edit', 'unit', 'unitController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(69, 0, 0, 'update', 'unit', 'unitController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(70, 0, 0, 'save', 'unit', 'unitController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(71, 0, 0, 'getlist', 'unit', 'unitController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(72, 0, 0, 'unit', '', 'unitController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(73, 0, 0, 'getunit', 'unit', 'unitController', 'get_unit', 'unit details', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(74, 6, 0, 'checklist', 'hall', 'hallController', 'check_list', 'checklist', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(75, 3, 0, 'garden', '', 'TreeCuttingController', 'index', 'Tree Cutting Section', '2020-04-03 17:17:21', '0000-00-00 00:00:00', 1, 0),
(76, 3, 2, 'create', 'garden', 'TreeCuttingController', 'createComplaint', 'Tree Cutting Section', '2020-04-03 17:17:21', '0000-00-00 00:00:00', 1, 0),
(77, 3, 0, 'addTree', 'garden', 'TreeCuttingController', 'addTree', 'Tree add', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(78, 3, 0, 'addProcess', 'garden', 'TreeCuttingController', 'addProcess', 'Add process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(79, 3, 0, 'treeSubmit', 'garden', 'TreeCuttingController', 'treeSubmit', 'Add Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(80, 3, 0, 'deleteTree', 'garden', 'TreeCuttingController', 'deleteTree', 'Delete Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(81, 3, 0, 'deactivateTree', 'garden', 'TreeCuttingController', 'deactivateTree', 'Deactivate Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(82, 3, 0, 'treeEdit', 'garden', 'TreeCuttingController', 'treeEdit', 'Deactivate Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(83, 3, 0, 'processSubmit', 'garden', 'TreeCuttingController', 'processSubmit', 'Create Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(84, 3, 0, 'processDelete', 'garden', 'TreeCuttingController', 'processDelete', 'Delete Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(85, 3, 0, 'processdeactivate', 'garden', 'TreeCuttingController', 'processdeactivate', 'Deactivate Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(86, 3, 0, 'processEdits', 'garden', 'TreeCuttingController', 'processEdits', 'Edit Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(87, 3, 0, 'getTreeName', 'garden', 'TreeCuttingController', 'getTreeName', 'Tree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(88, 3, 0, 'getProcessName', 'garden', 'TreeCuttingController', 'getProcessName', 'Process Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(89, 3, 0, 'submitComplaint', 'garden', 'TreeCuttingController', 'submitComplaint', 'Submit Complaint', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(90, 3, 0, 'edits', 'garden', 'TreeCuttingController', 'editApps', 'Edit Complaint', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(91, 3, 0, 'gardenDelete', 'garden', 'TreeCuttingController', 'gardenDelete', 'Delete Garden', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(92, 3, 0, 'complainEdit', 'garden', 'TreeCuttingController', 'complainEdit', 'Complain Edit', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(93, 3, 4, 'complainDelete', 'garden', 'TreeCuttingController', 'complainDelete', 'Complain Delete', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(94, 3, 0, 'getGardenDataById', 'garden', 'TreeCuttingController', 'getGardenDataById', 'Garden Data', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(95, 3, 0, 'complainApprove', 'garden', 'TreeCuttingController', 'complainApprove', 'Approve Complain', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(96, 3, 0, 'remarksGet', 'garden', 'TreeCuttingController', 'remarksGet', 'Get Remarks', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(97, 6, 0, 'asset-save', 'hall', 'hallController', 'asset_save', 'asset save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(98, 13, 0, 'templic', '', 'TempLicController', 'index', 'Temp Lic Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(99, 13, 0, 'renewApp', 'templic', 'TempLicController', 'renewApp', 'Renew Application', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(100, 13, 0, 'addLicType', 'templic', 'TempLicController', 'addLicType', 'Add License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(101, 13, 0, 'licSubmit', 'templic', 'TempLicController', 'licSubmit', 'Add License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(102, 13, 4, 'deleteLic', 'templic', 'TempLicController', 'deleteLic', 'Delete License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(103, 13, 0, 'deactivateLic', 'templic', 'TempLicController', 'deactivateLic', 'Delete License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(104, 13, 0, 'licEdit', 'templic', 'TempLicController', 'licEdit', 'Edit License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(105, 13, 0, 'createLic', 'templic', 'TempLicController', 'createLic', 'Create License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(106, 13, 0, 'createApplication', 'templic', 'TempLicController', 'createApplication', 'Create License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(107, 13, 0, 'renewApplication', 'templic', 'TempLicController', 'renewApplication', 'Renew License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(108, 13, 0, 'deleteApplication', 'templic', 'TempLicController', 'deleteApplication', 'Renew License', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(109, 13, 3, 'edit', 'templic', 'TempLicController', 'editApplication', 'edit License', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(110, 13, 0, 'editApplicationRenew', 'templic', 'TempLicController', 'editApplicationRenew', 'edit License', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(111, 3, 0, 'getAppStatus', 'garden', 'TreeCuttingController', 'getAppStatus', 'Approval Status', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(112, 7, 0, 'tradefactlic', '', 'TradeFactLicController', 'index', 'Trade Factory Lic Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(113, 7, 2, 'create', 'tradefactlic', 'TradeFactLicController', 'create', 'Pwd create', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(114, 5, 0, 'hospital', '', 'HospitalController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(115, 0, 0, 'imagedetails', 'image', 'imagedetailsController', 'getimagedetails', 'get image details', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(116, 5, 2, 'create', 'hospital', 'HospitalController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(117, 0, 0, 'get-designation', 'designation', 'designationController', 'get_designation', 'get_designation', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(118, 0, 0, 'get-qualification', 'qualification', 'qualificationController', 'get_qualification', 'qualification', '2020-03-19 17:17:15', '0000-00-00 00:00:00', 1, 0),
(119, 5, 0, 'save', 'hospital', 'hospitalController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(120, 5, 0, 'getlist', 'hospital', 'hospitalController', 'get_lists', 'hospital table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(121, 5, 3, 'edit', 'hospital', 'HospitalController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(122, 5, 0, 'addremarks', 'hospital', 'hospitalController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(123, 5, 0, 'staffDelete', 'hospital', 'hospitalController', 'staff_delete', 'staff delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(124, 5, 0, 'clinic', '', 'clinicController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(125, 5, 2, 'create', 'clinic', 'clinicController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(126, 5, 0, 'save', 'clinic', 'clinicController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(127, 5, 0, 'getlist', 'clinic', 'clinicController', 'get_lists', 'clinic table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(128, 5, 3, 'edit', 'clinic', 'clinicController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(129, 5, 0, 'addremarks', 'clinic', 'clinicController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(130, 5, 0, 'staffDelete', 'clinic', 'clinicController', 'staff_delete', 'staff delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(131, 5, 0, 'lab', '', 'labController', 'index', 'index', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(132, 5, 2, 'create', 'lab', 'labController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(133, 5, 0, 'save', 'lab', 'labController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(134, 5, 0, 'getlist', 'lab', 'labController', 'get_lists', 'lab table', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(135, 5, 3, 'edit', 'lab', 'labController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(136, 5, 0, 'addremarks', 'lab', 'labController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(137, 7, 0, 'tradefactlic', '', 'TradeFactLicController', 'index', 'Trade Factory Lic Section', '2020-04-15 17:17:21', '2020-04-15 17:17:21', 1, 0),
(138, 7, 2, 'create', 'tradefactlic', 'TradeFactLicController', 'create', 'Pwd create', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(139, 7, 0, 'createFactLic', 'tradefactlic', 'TradeFactLicController', 'createFactLic', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(140, 7, 0, 'edits', 'tradefactlic', 'TradeFactLicController', 'editLic', 'edit License', '2020-03-19 17:17:21', '2020-04-15 17:17:21', 1, 0),
(141, 7, 0, 'editFactLic', 'tradefactlic', 'TradeFactLicController', 'editFactLic', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(142, 7, 0, 'getData', 'tradefactlic', 'TradeFactLicController', 'getData', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(143, 7, 0, 'getRemarks', 'tradefactlic', 'TradeFactLicController', 'getRemarks', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(144, 7, 0, 'getAppStatus', 'tradefactlic', 'TradeFactLicController', 'getAppStatus', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(145, 7, 0, 'approveComplain', 'tradefactlic', 'TradeFactLicController', 'approveComplain', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(146, 3, 0, 'getData', 'garden', 'TreeCuttingController', 'getData', 'Get Data', '2020-04-04 17:17:21', '2020-04-04 17:17:21', 1, 0),
(147, 8, 2, 'create', 'godown', 'godownController', 'create', 'Godown Lic', '2020-04-21 17:17:21', '2020-04-21 17:17:21', 1, 0),
(148, 13, 0, 'getAppStatus', 'templic', 'TempLicController', 'getAppStatus', 'appstatus', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(149, 13, 0, 'approveComplain', 'templic', 'TempLicController', 'approveComplain', 'approval', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(150, 8, 2, 'create', 'godown', 'godownController', 'create', 'Godown Lic', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 2, 0),
(151, 13, 0, 'getAppStatus', 'templic', 'TempLicController', 'getAppStatus', 'appstatus', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(152, 13, 0, 'approveComplain', 'templic', 'TempLicController', 'approveComplain', 'approval', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(153, 8, 0, 'addStorage', 'godown', 'godownController', 'addStorage', 'Godown Storage', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(154, 8, 0, 'getDataByLic', 'godown', 'godownController', 'getDataByLic', 'appData', '2020-04-21 17:17:21', '0000-00-00 00:00:00', 1, 0),
(155, 8, 3, 'edit', 'godown', 'godownController', 'editApp', 'edit storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(156, 8, 0, 'editStorage', 'godown', 'godownController', 'editStorage', 'edit storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(157, 8, 0, 'getData', 'godown', 'godownController', 'getData', 'Get storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(158, 8, 0, 'godown', '', 'godownController', 'index', 'Godown Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(159, 8, 4, 'delStorage', 'godown', 'godownController', 'delStorage', 'Delete storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(160, 8, 0, 'getAppStatus', 'godown', 'godownController', 'getAppStatus', 'status storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(161, 8, 0, 'approveComplain', 'godown', 'godownController', 'approveComplain', 'stat storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(162, 6, 2, 'create', 'hall-service', 'hallServiceController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(163, 6, 3, 'edit', 'hall-service', 'hallServiceController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(164, 6, 0, 'update', 'hall-service', 'hallServiceController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(165, 6, 0, 'save', 'hall-service', 'hallServiceController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(166, 6, 0, 'getlist', 'hall-service', 'hallServiceController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(167, 6, 0, 'hall-service', '', 'hallServiceController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(168, 6, 0, 'getsku', 'hallService', 'hallServiceController', 'sku_by_dept', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(169, 6, 0, 'getprice', 'hall-service', 'hallServiceController', 'price_by_sku', 'sku by dept', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(170, 0, 2, 'create', 'designation', 'designationController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(171, 0, 3, 'edit', 'designation', 'designationController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(172, 0, 0, 'update', 'designation', 'designationController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(173, 0, 0, 'save', 'designation', 'designationController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(174, 0, 0, 'getlist', 'designation', 'designationController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(175, 0, 0, 'designation', '', 'designationController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(178, 0, 2, 'create', 'qualification', 'qualificationController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(179, 0, 3, 'edit', 'qualification', 'qualificationController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(180, 0, 0, 'update', 'qualification', 'qualificationController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(181, 0, 0, 'save', 'qualification', 'qualificationController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(182, 0, 0, 'getlist', 'qualification', 'qualificationController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(183, 0, 0, 'qualification', '', 'qualificationController', 'index', 'view', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(184, 12, 0, 'mandap', '', 'mandapController', 'index', 'mandap data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(185, 12, 2, 'create', 'mandap', 'mandapController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(186, 12, 3, 'edit', 'mandap', 'mandapController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(187, 12, 0, 'update', 'mandap', 'mandapController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(188, 12, 0, 'save', 'mandap', 'mandapController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(189, 12, 0, 'getlist', 'mandap', 'mandapController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(190, 12, 0, 'getbookedhall', 'mandap', 'mandapController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(191, 12, 0, 'addremarks', 'mandap', 'mandapController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(192, 12, 0, 'checklist', 'mandap', 'mandapController', 'check_list', 'checklist', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(193, 12, 0, 'asset-save', 'mandap', 'mandapController', 'asset_save', 'asset save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(194, 10, 0, 'film', '', 'filmController', 'index', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(195, 10, 2, 'create', 'film', 'filmController', 'create', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(196, 10, 3, 'edit', 'film', 'filmController', 'edit', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(197, 10, 4, 'delete', 'film', 'filmController', 'delete', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(198, 10, 0, 'getData', 'film', 'filmController', 'getData', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(199, 10, 0, 'createFilmLic', 'film', 'filmController', 'createFilmLic', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(200, 10, 0, 'getRemarks', 'film', 'filmController', 'getRemarks', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(201, 10, 0, 'getAppStatus', 'film', 'filmController', 'getAppStatus', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(202, 10, 0, 'approveComplain', 'film', 'filmController', 'approveComplain', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(203, 10, 0, 'editFilmLic', 'film', 'filmController', 'editFilmLic', 'Film Section', '2020-04-15 17:17:21', '0000-00-00 00:00:00', 1, 0),
(204, 13, 0, 'getlictype', 'templic', 'TempLicController', 'getlictype', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(205, 13, 0, 'crLicType', 'templic', 'TempLicController', 'crLicType', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(206, 3, 0, 'editss', 'garden', 'TreeCuttingController', 'treeNameEdit', 'Edit Tree', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(207, 3, 0, 'crTreeType', 'garden', 'TreeCuttingController', 'crTreeType', 'createTree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(208, 3, 0, 'treeNameSubmit', 'garden', 'TreeCuttingController', 'treeNameSubmit', 'createTree Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(209, 3, 0, 'crProcessType', 'garden', 'TreeCuttingController', 'crProcessType', 'createProcess Name', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(210, 3, 3, 'edit', 'garden', 'TreeCuttingController', 'processNameEdit', 'Edit Process', '2020-04-04 17:17:21', '0000-00-00 00:00:00', 1, 0),
(225, 13, 0, 'getData', 'templic', 'TempLicController', 'getData', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(226, 8, 0, 'remarksGet', 'godown', 'godownController', 'remarksGet', 'Remarks storage', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(227, 13, 0, 'marriage', '', 'marriageController', 'index', 'marriage data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(228, 13, 2, 'create', 'marriage', 'marriageController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(229, 13, 3, 'edit', 'marriage', 'marriageController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(230, 13, 0, 'update', 'marriage', 'marriageController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(231, 13, 0, 'save', 'marriage', 'marriageController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(232, 13, 0, 'getlist', 'marriage', 'marriageController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(233, 13, 0, 'getbookedhall', 'marriage', 'marriageController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(234, 13, 0, 'addremarks', 'marriage', 'marriageController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(235, 13, 0, 'getRemarks', 'templic', 'TempLicController', 'getRemarks', 'editLic', '2020-03-19 17:17:21', '2020-04-21 17:17:21', 1, 0),
(236, 7, 4, 'delete', 'tradefactlic', 'TradeFactLicController', 'delete', 'Trade Fact Lic', '2020-04-21 17:17:21', '2020-04-15 17:17:21', 1, 0),
(237, 0, 0, 'permissions', '', 'PermissionsController', 'index', 'index', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(238, 0, 0, 'getUserData', 'permissions', 'PermissionsController', 'getUserData', 'userdata', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(239, 0, 0, 'addPermission', 'permissions', 'PermissionsController', 'addPermission', 'submit permission', '2020-06-02 17:17:21', '0000-00-00 00:00:00', 1, 0),
(240, 1, 4, 'delete', 'pwd', 'PwdController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(242, 6, 4, 'delete', 'hall', 'hallController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(243, 5, 4, 'delete', 'hospital', 'HospitalController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(244, 5, 4, 'delete', 'clinic', 'clinicController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(245, 5, 4, 'delete', 'lab', 'labController', 'delete', 'delete', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(246, 12, 4, 'delete', 'mandap', 'mandapController', 'delete', 'delete\r\n', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(247, 12, 0, 'mandap', '', 'mandapController', 'index', 'mandap data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(248, 12, 2, 'create', 'mandap', 'mandapController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(249, 12, 3, 'edit', 'mandap', 'mandapController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(250, 12, 0, 'update', 'mandap', 'mandapController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(251, 12, 0, 'save', 'mandap', 'mandapController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(252, 12, 0, 'getlist', 'mandap', 'mandapController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(253, 12, 0, 'getbookedhall', 'mandap', 'mandapController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(254, 12, 0, 'addremarks', 'mandap', 'mandapController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(255, 12, 0, 'checklist', 'mandap', 'mandapController', 'check_list', 'checklist', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(256, 12, 0, 'asset-save', 'mandap', 'mandapController', 'asset_save', 'asset save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(257, 12, 4, 'delete', 'mandap', 'mandapController', 'delete', 'delete\r\n', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(258, 13, 0, 'marriage', '', 'marriageController', 'index', 'marriage data', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(259, 13, 2, 'create', 'marriage', 'marriageController', 'create', 'create', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(260, 13, 3, 'edit', 'marriage', 'marriageController', 'edit', 'edit', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(261, 13, 0, 'update', 'marriage', 'marriageController', 'update', 'update', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(262, 13, 0, 'save', 'marriage', 'marriageController', 'save', 'save', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(263, 13, 0, 'getlist', 'marriage', 'marriageController', 'get_list', 'get list', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(264, 13, 0, 'getbookedhall', 'marriage', 'marriageController', 'get_booked_hall', 'get booked hall', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0),
(265, 13, 0, 'addremarks', 'marriage', 'marriageController', 'add_remarks', 'add remarks', '2020-03-19 17:17:21', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `app_status_level`
--

CREATE TABLE `app_status_level` (
  `status_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status_title` varchar(255) NOT NULL,
  `status_type` tinyint(1) NOT NULL,
  `is_rejected` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `status_approve` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_status_level`
--

INSERT INTO `app_status_level` (`status_id`, `dept_id`, `role_id`, `status_title`, `status_type`, `is_rejected`, `status`, `status_approve`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 'Approved by Senior Executive ', 1, 1, 1, 0, 1, '2020-10-19 06:53:19', '2020-10-19 06:53:19'),
(2, 1, 3, 'pending from clerk', 1, 0, 1, 0, 1, '2020-03-27 00:00:00', '2020-06-01 09:03:09'),
(3, 1, 3, 'Verified by clerk', 2, 0, 1, 0, 1, '2020-03-27 00:00:00', '2020-04-03 13:00:28'),
(4, 1, 8, 'Pending from Jr.engineer', 1, 0, 1, 0, 1, '2020-03-27 00:00:00', '2020-03-27 00:00:00'),
(5, 1, 8, 'Verified by Jr.Engineer', 2, 0, 2, 0, 1, '2020-03-27 00:00:00', '2020-06-01 08:47:46'),
(6, 1, 3, 'Rejected by clerk', 1, 1, 1, 0, 0, '2020-03-27 00:00:00', '2020-03-27 00:00:00'),
(7, 1, 8, 'Approved by Jr. Engineer', 2, 0, 1, 0, 0, '2020-03-27 00:00:00', '2020-03-27 00:00:00'),
(8, 1, 3, 'Approved by clerk', 1, 0, 1, 0, 0, '2020-03-27 00:00:00', '2020-03-27 00:00:00'),
(9, 1, 8, 'Rejected by Jr.Engineer', 1, 0, 1, 0, 0, '2020-03-27 00:00:00', '2020-03-27 00:00:00'),
(10, 2, 3, 'pending from clerk', 0, 0, 2, 0, 0, '2020-04-03 13:20:29', '2020-04-03 13:21:37'),
(11, 5, 3, 'Rejected by clerk', 1, 1, 1, 0, 0, '2020-04-04 09:45:35', '2020-04-04 09:51:33'),
(12, 6, 3, 'Pending by Clerk', 0, 0, 1, 0, 0, '2020-04-07 12:57:38', '2020-04-07 12:57:38'),
(13, 6, 3, 'Verified by clerk ', 0, 0, 1, 0, 0, '2020-04-07 12:58:15', '2020-04-07 12:58:15'),
(14, 6, 10, 'Pending By ward officer', 0, 0, 1, 0, 0, '2020-04-07 12:59:36', '2020-04-07 12:59:36'),
(15, 6, 10, 'Verified By ward officer', 0, 0, 1, 0, 0, '2020-04-07 13:00:07', '2020-04-07 13:00:07'),
(16, 5, 3, 'Approved by Clerk', 1, 0, 1, 0, 0, '2020-04-28 13:08:51', '2020-04-28 13:08:51'),
(17, 3, 8, 'Rejected by Suprentendent', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(18, 3, 8, 'Approved by Suprentendent', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(19, 6, 8, 'Approved', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(20, 6, 8, 'Rejected', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(21, 8, 8, 'Approved', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(22, 8, 8, 'Rejected', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(23, 9, 1, 'Approved', 1, 0, 1, 0, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(24, 7, 13, 'Approved', 1, 0, 1, 1, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(25, 7, 13, 'Reject', 1, 0, 1, 2, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(26, 11, 1, 'Approved', 1, 0, 1, 1, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(27, 11, 1, 'Reject', 1, 0, 1, 2, 0, '2020-03-08 00:00:00', '2020-03-08 00:00:00'),
(28, 3, 3, 'Approved By Clerk', 1, 0, 1, 0, 0, '2020-08-28 13:17:49', '2020-08-28 13:17:49'),
(29, 3, 3, 'Rejected By Clerk', 1, 0, 1, 0, 0, '2020-08-28 13:17:49', '2020-08-28 13:17:49'),
(30, 3, 15, 'Approved By Garden Suprentendent', 1, 0, 1, 0, 0, '2020-08-28 13:26:42', '2020-08-28 13:26:42'),
(31, 3, 15, 'Rejected By Garden Suprentendent', 1, 0, 1, 0, 0, '2020-08-28 13:26:42', '2020-08-28 13:26:42'),
(32, 3, 6, 'Approved By Dycomm', 1, 0, 1, 0, 0, '2020-09-04 11:48:12', '2020-09-04 11:48:12'),
(33, 3, 6, 'Rejected By Dycomm', 1, 0, 1, 0, 0, '2020-09-04 11:48:12', '2020-09-04 11:48:12'),
(34, 1, 19, 'approved by DE', 1, 0, 1, 0, 1, '2020-10-10 14:25:50', '2020-10-10 14:25:50'),
(35, 1, 19, 'rejected by DE', 1, 1, 1, 0, 1, '2020-10-10 14:25:50', '2020-10-10 14:25:50'),
(36, 1, 19, 'approved by DE', 1, 0, 1, 0, 1, '2020-10-10 14:26:01', '2020-10-10 14:26:01'),
(37, 1, 19, 'rejected by DE', 1, 1, 1, 0, 1, '2020-10-10 14:26:01', '2020-10-10 14:26:01'),
(38, 1, 18, 'approved by SE', 1, 0, 1, 0, 0, '2020-10-12 11:30:18', '2020-10-12 11:30:18'),
(39, 1, 18, 'Rejected by SE', 1, 1, 1, 0, 0, '2020-10-12 11:30:18', '2020-10-12 11:30:18'),
(40, 1, 20, 'approved by senior engineer', 1, 0, 1, 0, 1, '2020-10-12 11:42:52', '2020-10-12 11:42:52'),
(41, 1, 20, 'Rejected by senior engineer', 1, 1, 1, 0, 1, '2020-10-12 11:42:52', '2020-10-12 11:42:52'),
(42, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:20', '2020-10-12 15:37:20'),
(43, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:22', '2020-10-12 15:37:22'),
(44, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:23', '2020-10-12 15:37:23'),
(45, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:23', '2020-10-12 15:37:23'),
(46, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:23', '2020-10-12 15:37:23'),
(47, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:23', '2020-10-12 15:37:23'),
(48, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:24', '2020-10-12 15:37:24'),
(49, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:24', '2020-10-12 15:37:24'),
(50, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:24', '2020-10-12 15:37:24'),
(51, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 1, '2020-10-12 15:37:36', '2020-10-12 15:37:36'),
(52, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:37', '2020-10-12 15:37:37'),
(53, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:37', '2020-10-12 15:37:37'),
(54, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:37', '2020-10-12 15:37:37'),
(55, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:37', '2020-10-12 15:37:37'),
(56, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:37', '2020-10-12 15:37:37'),
(57, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:38', '2020-10-12 15:37:38'),
(58, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:38', '2020-10-12 15:37:38'),
(59, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:38', '2020-10-12 15:37:38'),
(60, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:38', '2020-10-12 15:37:38'),
(61, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:39', '2020-10-12 15:37:39'),
(62, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:39', '2020-10-12 15:37:39'),
(63, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:39', '2020-10-12 15:37:39'),
(64, 1, 21, 'Approved by Test PWD', 1, 0, 1, 0, 0, '2020-10-12 15:37:39', '2020-10-12 15:37:39'),
(65, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:02', '2020-10-12 15:42:02'),
(66, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:04', '2020-10-12 15:42:04'),
(67, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:04', '2020-10-12 15:42:04'),
(68, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:04', '2020-10-12 15:42:04'),
(69, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:04', '2020-10-12 15:42:04'),
(70, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:06', '2020-10-12 15:42:06'),
(71, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:07', '2020-10-12 15:42:07'),
(72, 1, 22, 'Approved by Test 2 PWD', 1, 0, 1, 0, 0, '2020-10-12 15:42:08', '2020-10-12 15:42:08'),
(73, 1, 23, 'Approved by 1234', 1, 0, 1, 0, 0, '2020-10-13 13:09:34', '2020-10-13 13:09:34'),
(74, 1, 23, 'Rejected by 1234', 1, 1, 1, 0, 0, '2020-10-13 13:10:37', '2020-10-13 13:10:37'),
(75, 0, 0, '*', 1, 0, 1, 0, 0, '2020-10-13 13:17:02', '2020-10-13 13:17:02'),
(76, 0, 0, '#', 1, 0, 1, 0, 0, '2020-10-13 13:19:09', '2020-10-13 13:19:09'),
(77, 1, 16, 'Approved by Senior Executive ', 1, 0, 1, 0, 0, '2020-10-19 07:02:20', '2020-10-19 07:02:20'),
(78, 1, 16, 'Rejected by Senior Executive ', 1, 1, 1, 0, 0, '2020-10-19 07:02:20', '2020-10-19 07:02:20'),
(79, 1, 17, 'Approved by Deputy Engineer', 1, 0, 1, 0, 0, '2020-10-19 07:09:16', '2020-10-19 07:09:16'),
(80, 1, 17, 'Rejected by Deputy Engineer', 1, 1, 1, 0, 0, '2020-10-19 07:09:16', '2020-10-19 07:09:16'),
(81, 5, 22, 'Approved by health officer', 1, 0, 1, 0, 0, '2020-11-22 18:55:34', '2020-11-22 18:55:34'),
(82, 5, 22, 'Rejected by health officer', 1, 1, 1, 0, 0, '2020-11-22 18:55:34', '2020-11-22 18:55:34'),
(83, 5, 23, 'Approved by junior doctor', 1, 0, 1, 0, 0, '2020-11-22 18:59:50', '2020-11-22 18:59:50'),
(84, 5, 23, 'Rejected by junior doctor', 1, 1, 1, 0, 0, '2020-11-22 18:59:50', '2020-11-22 18:59:50'),
(85, 5, 24, 'Approved by senior doctor', 1, 0, 1, 0, 0, '2020-11-22 19:00:35', '2020-11-22 19:00:35'),
(86, 5, 24, 'Rejected by senior doctor', 1, 1, 1, 0, 0, '2020-11-22 19:00:35', '2020-11-22 19:00:35'),
(87, 3, 25, 'Approved by additional commissioner', 1, 0, 1, 0, 0, '2020-11-27 18:04:12', '2020-11-27 18:04:12'),
(88, 3, 25, 'Rejected by additional commissioner', 1, 1, 1, 0, 0, '2020-11-27 18:04:12', '2020-11-27 18:04:12'),
(89, 3, 13, 'Approve by commissioner', 1, 0, 1, 2, 0, '2020-11-27 18:05:14', '2020-11-27 18:05:14'),
(90, 3, 13, 'Rejected by commissioner', 1, 1, 1, 2, 0, '2020-11-27 18:05:14', '2020-11-27 18:05:14'),
(91, 3, 4, 'Approve by commissioner', 1, 0, 1, 0, 0, '2020-11-27 18:20:41', '2020-11-27 18:20:41'),
(92, 3, 4, 'Rejected by commissioner', 1, 1, 1, 0, 0, '2020-11-27 18:20:41', '2020-11-27 18:20:41'),
(93, 15, 26, 'Approved ', 1, 0, 1, 0, 0, '2020-12-04 17:33:09', '2020-12-04 17:33:09'),
(94, 15, 26, 'Approved ', 0, 0, 1, 0, 0, '2020-12-04 17:34:30', '2020-12-04 17:34:30'),
(95, 15, 26, 'Approved by $*%##', 1, 0, 1, 0, 0, '2020-12-04 18:07:33', '2020-12-04 18:07:33'),
(96, 15, 26, '                                                                    ', 1, 0, 1, 0, 0, '2020-12-04 18:10:28', '2020-12-04 18:10:28'),
(97, 12, 3, 'Approved by the Clerk', 1, 0, 1, 0, 0, '2020-12-08 10:37:39', '2020-12-08 10:37:39'),
(98, 0, 0, 'Rejected by the Clerk', 1, 0, 1, 0, 0, '2020-12-08 10:37:51', '2020-12-08 10:37:51'),
(99, 12, 3, 'Approved y the Clerk', 1, 0, 1, 0, 0, '2020-12-10 17:40:11', '2020-12-10 17:40:11'),
(100, 12, 10, 'Approve by ward officer.', 1, 0, 1, 0, 0, '2020-12-11 19:18:27', '2020-12-11 19:18:27'),
(101, 12, 10, 'Rejected by ward officer.', 1, 1, 1, 0, 0, '2020-12-11 19:18:27', '2020-12-11 19:18:27');

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--

CREATE TABLE `auth_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `browser` varchar(255) NOT NULL,
  `browser_version` varchar(255) NOT NULL,
  `os` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_sessions`
--

INSERT INTO `auth_sessions` (`id`, `user_id`, `token`, `login_time`, `ip_address`, `browser`, `browser_version`, `os`, `created_at`) VALUES
(1, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlcmtAbWJtYy5pbiIsInVzZXJfbmFtZSI6InB3ZGNsZXJrIiwidXNlcl9tb2JpbGUiOiI3Nzg0NTYzMjExIiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOCRVZXRkNH', '2020-09-29 10:39:13', '192.168.225.231', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-18 15:48:34'),
(2, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidXNlckBtYm1jLmluIiwidXNlcl9uYW1lIjoidXNlciIsInVzZXJfbW9iaWxlIjoiODE2OTM5NjMwMCIsImRlcHRfaWQiOiIwIiwicGFzc3dvcmQiOiIkMmEkMDgkYWs1WFNtYUpNNndFSF', '2020-09-07 12:53:01', '49.33.151.171', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-18 15:15:56'),
(3, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlN1bWFuIiwidXNlcl9tb2JpbGUiOiIyMzQ1NjcxOTY4IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYSQwOCRvSS5XRG', '2020-10-13 14:57:13', '27.106.35.50', 'Chrome', '86.0.4240.80', 'Mac OS X', '2020-10-18 15:51:01'),
(4, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidXNlckBtYm1jLmluIiwidXNlcl9uYW1lIjoidXNlciIsInVzZXJfbW9iaWxlIjoiODE2OTM5NjMwMCIsImRlcHRfaWQiOiIwIiwicGFzc3dvcmQiOiIkMmEkMDgkYWs1WFNtYUpNNndFSF', '2020-09-07 12:53:01', '49.33.151.171', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-18 16:17:14'),
(5, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '27.106.35.50', 'Chrome', '86.0.4240.80', 'Mac OS X', '2020-10-18 17:27:14'),
(6, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '27.106.35.50', 'Safari', '604.1', 'iOS', '2020-10-18 17:35:00'),
(7, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlcmtAbWJtYy5pbiIsInVzZXJfbmFtZSI6InB3ZGNsZXJrIiwidXNlcl9tb2JpbGUiOiI3Nzg0NTYzMjExIiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOCRVZXRkNH', '2020-09-29 10:39:13', '49.32.31.44', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-18 19:41:15'),
(8, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlcmtAbWJtYy5pbiIsInVzZXJfbmFtZSI6InB3ZGNsZXJrIiwidXNlcl9tb2JpbGUiOiI3Nzg0NTYzMjExIiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOCRVZXRkNH', '2020-09-29 10:39:13', '49.32.31.44', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-18 20:02:26'),
(9, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 05:17:59'),
(10, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidXNlckBtYm1jLmluIiwidXNlcl9uYW1lIjoidXNlciIsInVzZXJfbW9iaWxlIjoiODE2OTM5NjMwMCIsImRlcHRfaWQiOiIwIiwicGFzc3dvcmQiOiIkMmEkMDgkYWs1WFNtYUpNNndFSF', '2020-09-07 12:53:01', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 05:31:53'),
(11, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Safari', '604.1', 'iOS', '2020-10-19 05:40:40'),
(12, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 05:42:04'),
(13, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidXNlckBtYm1jLmluIiwidXNlcl9uYW1lIjoidXNlciIsInVzZXJfbW9iaWxlIjoiODE2OTM5NjMwMCIsImRlcHRfaWQiOiIwIiwicGFzc3dvcmQiOiIkMmEkMDgkYWs1WFNtYUpNNndFSF', '2020-09-07 12:53:01', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 05:43:38'),
(14, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 05:59:44'),
(15, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:11:37'),
(16, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:12:23'),
(17, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:14:30'),
(18, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:14:48'),
(19, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE5Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:15:37'),
(20, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjIwIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:16:09'),
(21, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:17:22'),
(22, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:35:47'),
(23, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlcmtAbWJtYy5pbiIsInVzZXJfbmFtZSI6InB3ZGNsZXJrIiwidXNlcl9tb2JpbGUiOiI3Nzg0NTYzMjExIiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOCRVZXRkNH', '2020-09-29 10:39:13', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 07:45:41'),
(24, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 07:45:48'),
(25, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 07:51:32'),
(26, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 07:54:25'),
(27, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE5Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 07:56:50'),
(28, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE5Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 07:58:32'),
(29, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 08:00:09'),
(30, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:02:15'),
(31, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:02:17'),
(32, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:03:27'),
(33, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE5Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:04:18'),
(34, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:09:36'),
(35, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:10:16'),
(36, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 08:10:44'),
(37, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:11:56'),
(38, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:14:12'),
(39, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:14:51'),
(40, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:15:15'),
(41, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:16:03'),
(42, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:24:54'),
(43, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:26:44'),
(44, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:27:58'),
(45, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 08:30:09'),
(46, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:30:55'),
(47, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:31:07'),
(48, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:31:37'),
(49, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 08:32:19'),
(50, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:33:01'),
(51, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:34:22'),
(52, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:35:19'),
(53, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:35:55'),
(54, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 08:39:56'),
(55, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 08:42:28'),
(56, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjEiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoidGVzdEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJ0ZXN0IiwidXNlcl9tb2JpbGUiOiI5ODc1NjQxMjMyIiwiZGVwdF9pZCI6IjIyIiwicGFzc3dvcmQiOiIkMmEkMDgkd3VhMFpYd2M4YT', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 08:43:48'),
(57, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 08:57:15'),
(58, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:00:46'),
(59, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:03:06'),
(60, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:04:00'),
(61, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:05:14'),
(62, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:06:12'),
(63, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:07:16'),
(64, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:07:55'),
(65, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '84.0.4147.135', 'Windows 8.1', '2020-10-19 09:09:48'),
(66, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:14:39'),
(67, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:15:32'),
(68, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:16:22'),
(69, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:17:04'),
(70, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:17:18'),
(71, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:19:11'),
(72, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:23:32'),
(73, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:25:04'),
(74, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:26:10'),
(75, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:27:18'),
(76, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:28:08'),
(77, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:35:07'),
(78, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkY2xlYXJrQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoicHdkY2xlYXJrIiwidXNlcl9tb2JpbGUiOiI3NTM5NTE4NTI1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-12 10:55:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 8.1', '2020-10-19 09:49:32'),
(79, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 09:57:08'),
(80, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic3VtYW4ua2F0dGltYW5pQGFhcmF2c29mdHdhcmUuY29tIiwidXNlcl9uYW1lIjoiQWxpY2UiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-10-16 17:08:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:00:44'),
(81, 39, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM5Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJwd2RqdW5pb3JlbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiNzg5MzIxNjU0NSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:02:53', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:01:23'),
(82, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eWVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImRlcHV0eWVuZyIsInVzZXJfbW9iaWxlIjoiMTU5MzU3NDU2OCIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMm', '2020-10-12 11:04:31', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:03:05'),
(83, 42, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQyIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InB3ZHNlbmlvcmVuZyIsInVzZXJfbW9iaWxlIjoiNzUzOTUxODUyNyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-12 11:44:39', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:04:43'),
(84, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEiLCJyb2xlX2lkIjoiMCIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJhZGFuaXBvd2VyQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiQWRhbmkgUG93ZXIiLCJ1c2VyX21vYmlsZSI6Ijk4NzY1NDMyMTAiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJD', '2020-10-19 10:37:22', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:37:46'),
(85, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:42:07'),
(86, 10, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEwIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicmVsaWFuY2VAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJSZWxpYW5jZSBKaW8iLCJ1c2VyX21vYmlsZSI6IjM1Nzk1MTQ1NjMiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJD', '2020-10-19 10:44:11', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:44:39'),
(87, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIiLCJyb2xlX2lkIjoiMCIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJtYWhhbmFnYXJnYXNAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJNYWhhbmFnYXIgR2FzIiwidXNlcl9tb2JpbGUiOiIxMjM0NTY3ODkwIiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6Ii', '2020-10-19 10:38:45', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:45:08'),
(88, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:47:34'),
(89, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoicHdkamVAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJKdW5pb3IgRW5naW5lZXIiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJD', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:48:22'),
(90, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZGRlcHV0eUB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlcHV0eSBFbmdpbmVlciIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:48:39'),
(91, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InB3ZHNlbmlvcmVuZ0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNlbmlvciBFeGVjdXRpdmUiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTQiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:48:57'),
(92, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 10:59:13'),
(93, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-19 11:02:04'),
(94, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIiLCJyb2xlX2lkIjoiMCIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJpbmZvQG1haGFuYWdhcmdhcy5jb20iLCJ1c2VyX25hbWUiOiJNYWhhbmFnYXIgR2FzIiwidXNlcl9tb2JpbGUiOiIxMjM0NTY3ODkwIiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-19 10:38:45', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-20 06:23:43'),
(95, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 11:50:41'),
(96, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:23:36'),
(97, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:33:50'),
(98, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:46:46'),
(99, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:53:32'),
(100, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:53:50'),
(101, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:54:31'),
(102, 14, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE0Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoicGF0aWxhcnZpbmQxOS5hcEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJBcnZpbmQgUGF0aWwiLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzAiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-21 12:47:59', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:54:53'),
(103, 15, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE1Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoiY20xMzU2OEBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDaGV0YW4gTWhhdHJlIiwidXNlcl9tb2JpbGUiOiI0NTY5ODcxMjM2IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6IiQyYSQwOC', '2020-10-21 12:49:24', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:55:14'),
(104, 16, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE2Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2FjaGlucGF0aWwxMDM0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhY2hpbiBQYXRpbCIsInVzZXJfbW9iaWxlIjoiNDU2MzIxNDU2MyIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOi', '2020-10-21 12:50:21', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:55:36'),
(105, 17, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE3Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2FjaGlucGF3YXI3NzRAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiU2FjaGluIFBhd2FyIiwidXNlcl9tb2JpbGUiOiI0NTY5ODc0NTM1IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZCI6Ii', '2020-10-21 12:51:34', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:56:15'),
(106, 18, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE4Iiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoicHJhZnVsbHdhbmtoZWRlQGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlByYWZ1bGwgV2Fua2hlZGUiLCJ1c2VyX21vYmlsZSI6IjEyMzc4OTQ1NjMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3', '2020-10-21 12:52:20', '106.201.231.19', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-21 12:56:33'),
(107, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '171.50.232.242', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-21 13:54:11'),
(108, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '42.106.203.22', 'Chrome', '86.0.4240.75', 'Windows 10', '2020-10-22 12:42:35'),
(109, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '103.115.97.2', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-29 07:23:07'),
(110, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-29 09:56:16'),
(111, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '103.88.124.103', 'Chrome', '86.0.4240.111', 'Windows 7', '2020-10-29 11:34:55'),
(112, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '103.88.124.103', 'Chrome', '86.0.4240.111', 'Windows 7', '2020-10-29 11:34:58'),
(113, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-29 12:01:18'),
(114, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '103.88.124.103', 'Chrome', '86.0.4240.111', 'Windows 7', '2020-10-29 12:01:43'),
(115, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-30 13:05:09'),
(116, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-10-31 11:15:57'),
(117, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-11-02 06:47:55'),
(118, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '103.88.124.71', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-11-02 15:05:03'),
(119, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '103.88.124.71', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-11-02 15:47:06'),
(120, 19, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjE5Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibmlsZXNoLm1vcmU3QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Ik5pbGVzaCBNb3JlIiwidXNlcl9tb2JpbGUiOiI5MDIyNTEzMzQ0IiwiZGVwdF9pZCI6IjAiLCJwYXNzd29yZCI6IiQyYS', '2020-10-21 13:53:37', '103.88.124.71', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-11-02 15:52:35'),
(121, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 10', '2020-11-03 12:51:44'),
(122, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.111', 'Windows 8.1', '2020-11-03 13:03:45'),
(123, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 10', '2020-11-04 12:21:13'),
(124, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-07 05:45:58'),
(125, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-07 05:47:55'),
(126, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-07 05:48:34'),
(127, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-07 05:48:56'),
(128, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-07 05:48:57'),
(129, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.220.68.41', 'Chrome', '74.0.3729.157', 'Android', '2020-11-07 18:32:22'),
(130, 20, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIwIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY2hldG55YUBhYXJhdnNvZnR3YXJlLmNvbSIsInVzZXJfbmFtZSI6InRvbSIsInVzZXJfbW9iaWxlIjoiMTQ3ODUyMzY5MiIsImRlcHRfaWQiOiIwIiwicGFzc3dvcmQiOiIkMmEkMDgkMl', '2020-11-10 07:57:52', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 07:58:14'),
(131, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:07:18'),
(132, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:11:26'),
(133, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:12:19'),
(134, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:19:22');
INSERT INTO `auth_sessions` (`id`, `user_id`, `token`, `login_time`, `ip_address`, `browser`, `browser_version`, `os`, `created_at`) VALUES
(135, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:20:58'),
(136, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:23:55'),
(137, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:29:05'),
(138, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:31:49'),
(139, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:32:59'),
(140, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:33:45'),
(141, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:34:33'),
(142, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:39:34'),
(143, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 08:44:51'),
(144, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.193', 'Windows 10', '2020-11-10 08:50:37'),
(145, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 09:18:45'),
(146, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 09:27:14'),
(147, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '49.32.203.136', 'Chrome', '86.0.4240.183', 'Windows 8.1', '2020-11-10 09:39:21'),
(148, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-11-18 05:30:03'),
(149, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-11-18 05:55:51'),
(150, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-11-18 06:02:19'),
(151, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 06:43:47'),
(152, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:10:50'),
(153, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:18:49'),
(154, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:24:34'),
(155, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:32:52'),
(156, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:35:55'),
(157, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:39:08'),
(158, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:46:00'),
(159, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 07:53:28'),
(160, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 08:10:37'),
(161, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 08:19:04'),
(162, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 08:19:33'),
(163, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 08:20:00'),
(164, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 08:20:19'),
(165, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:37:26'),
(166, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:43:20'),
(167, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:44:51'),
(168, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:45:59'),
(169, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:46:58'),
(170, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:51:38'),
(171, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:54:24'),
(172, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:56:31'),
(173, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:57:54'),
(174, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:58:52'),
(175, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 09:59:31'),
(176, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:00:01'),
(177, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:08:51'),
(178, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:11:58'),
(179, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:12:54'),
(180, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:14:12'),
(181, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:16:27'),
(182, 11, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjExIiwicm9sZV9pZCI6IjgiLCJ3YXJkX2lkIjoiMSIsImVtYWlsX2lkIjoic2F0aXNodGFuZGVsc3Q4QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IlNhdGlzaCBUYW5kZWwiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTIiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIj', '2020-10-19 10:44:20', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:40:54'),
(183, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:41:42'),
(184, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:42:32'),
(185, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:44:08'),
(186, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:44:41'),
(187, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:44:43'),
(188, 13, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEzIiwicm9sZV9pZCI6IjE4Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImRlZXBhay5raGFtYml0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkRlZXBhayBLaGFtYml0IiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE0IiwiZGVwdF9pZCI6IjEiLCJwYXNzd29yZC', '2020-10-19 10:45:56', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:45:09'),
(189, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:45:57'),
(190, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:50:35'),
(191, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:51:02'),
(192, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-18 10:55:16'),
(193, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-11-18 12:04:22'),
(194, 12, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjEyIiwicm9sZV9pZCI6IjE3Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InlhM2pkdkBnbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJZYXRpbiBKYWRoYXYiLCJ1c2VyX21vYmlsZSI6Ijk4NzQ1NjMyMTMiLCJkZXB0X2lkIjoiMSIsInBhc3N3b3JkIjoiJDJhJDA4JD', '2020-10-19 10:45:19', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-11-18 12:20:50'),
(195, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-19 04:55:52'),
(196, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-19 04:56:27'),
(197, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-21 10:40:19'),
(198, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 16:27:39'),
(199, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 16:50:12'),
(200, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 17:21:22'),
(201, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 17:35:15'),
(202, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiZGh5ZXlyYXRob2QiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzIiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJD', '2020-11-22 18:06:21', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:06:32'),
(203, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:08:30'),
(204, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiZGh5ZXlyYXRob2QiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzIiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJD', '2020-11-22 18:06:21', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:12:47'),
(205, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:15:43'),
(206, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiZGh5ZXlyYXRob2QiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzIiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJD', '2020-11-22 18:06:21', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:36:15'),
(207, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:40:15'),
(208, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:50:29'),
(209, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:52:30'),
(210, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 18:53:26'),
(211, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:02:04'),
(212, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:02:43'),
(213, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-22 19:04:40'),
(214, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-22 19:07:33'),
(215, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-22 19:07:33'),
(216, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:13:13'),
(217, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiZGh5ZXlyYXRob2QiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzIiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJD', '2020-11-22 18:06:21', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:15:33'),
(218, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '171.51.234.13', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:18:02'),
(219, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-22 19:20:25'),
(220, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-22 19:22:35'),
(221, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '106.209.192.70', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:45:22'),
(222, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '106.209.192.70', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 19:47:06'),
(223, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '106.209.192.70', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:01:02'),
(224, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:12:33'),
(225, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:12:57'),
(226, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:18:58'),
(227, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:19:31'),
(228, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:20:06'),
(229, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:20:42'),
(230, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:21:50'),
(231, 28, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI4Iiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAZ21haWwuY29tIiwidXNlcl9uYW1lIjoiZGh5ZXlyYXRob2QiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzIiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJD', '2020-11-22 18:06:21', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:49:11'),
(232, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:52:14'),
(233, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:53:08'),
(234, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 20:54:46'),
(235, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 21:20:21'),
(236, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '223.189.51.100', 'Chrome', '86.0.4240.198', 'Windows 7', '2020-11-22 21:20:58'),
(237, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 03:53:59'),
(238, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 03:57:19'),
(239, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 03:58:45'),
(240, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:04:52'),
(241, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:06:45'),
(242, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:07:29'),
(243, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:08:28'),
(244, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:09:12'),
(245, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:09:58'),
(246, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:10:47'),
(247, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Safari', '604.1', 'iOS', '2020-11-23 04:12:29'),
(248, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:13:10'),
(249, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:21:03'),
(250, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '27.106.35.48', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 04:25:44'),
(251, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 04:59:23'),
(252, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '106.201.231.19', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-23 05:08:58'),
(253, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '106.201.231.19', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-23 06:14:35'),
(254, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:03:55'),
(255, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:16:26'),
(256, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:20:25'),
(257, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:22:55'),
(258, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:29:41'),
(259, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '120.63.147.19', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 08:31:15'),
(260, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 09:12:19'),
(261, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 09:13:25'),
(262, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 09:14:20'),
(263, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '103.88.124.103', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 10:54:05'),
(264, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '103.88.124.103', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 11:00:58'),
(265, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '103.88.124.103', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 11:03:59'),
(266, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '106.201.231.19', 'Chrome', '86.0.4240.198', 'Windows 10', '2020-11-23 11:26:37'),
(267, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '106.193.166.177', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-23 11:32:18'),
(268, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '::1', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 06:16:36');
INSERT INTO `auth_sessions` (`id`, `user_id`, `token`, `login_time`, `ip_address`, `browser`, `browser_version`, `os`, `created_at`) VALUES
(269, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '::1', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 06:16:56'),
(270, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '::1', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 06:18:26'),
(271, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 13:11:05'),
(272, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 13:16:36'),
(273, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 13:20:41'),
(274, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 13:22:38'),
(275, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-24 13:33:46'),
(276, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 14:29:43'),
(277, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 14:29:44'),
(278, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-24 15:07:51'),
(279, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 16:41:53'),
(280, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 16:48:38'),
(281, 9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjkiLCJyb2xlX2lkIjoiMyIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJwd2RjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJDbGVyayIsInVzZXJfbW9iaWxlIjoiOTg3NDU2MzIxMSIsImRlcHRfaWQiOiIxIiwicGFzc3dvcmQiOiIkMmEkMDgkc2tnWn', '2020-10-19 10:43:49', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 16:49:31'),
(282, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 16:53:15'),
(283, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:09:59'),
(284, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:11:22'),
(285, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:12:17'),
(286, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:13:15'),
(287, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:14:39'),
(288, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 18:15:38'),
(289, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-24 21:46:01'),
(290, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-25 10:11:02'),
(291, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-25 12:43:09'),
(292, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-25 12:52:34'),
(293, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-25 15:43:42'),
(294, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-25 19:18:54'),
(295, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:19:11'),
(296, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:32:23'),
(297, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:33:43'),
(298, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:35:14'),
(299, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:43:14'),
(300, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:43:39'),
(301, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:51:39'),
(302, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 12:52:07'),
(303, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:22:37'),
(304, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:26:21'),
(305, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:27:53'),
(306, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:29:00'),
(307, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:32:12'),
(308, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:32:41'),
(309, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-26 17:32:43'),
(310, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 12:01:24'),
(311, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 12:53:06'),
(312, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-27 13:19:07'),
(313, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 13:20:46'),
(314, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 13:21:11'),
(315, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 15:47:20'),
(316, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 15:56:02'),
(317, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:31:46'),
(318, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:37:57'),
(319, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:38:33'),
(320, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:40:08'),
(321, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:49:36'),
(322, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 16:59:10'),
(323, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:06:52'),
(324, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:08:22'),
(325, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-27 17:17:17'),
(326, 33, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjMzIiwicm9sZV9pZCI6IjE1Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImdyZHN1cGVyaW50QHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiZ2FyZGVuIHN1cGVyaW50ZW5kZW50IiwidXNlcl9tb2JpbGUiOiI3ODk0NTYzMjE1IiwiZGVwdF9pZCI6IjMiLCJwYX', '2020-11-27 17:39:52', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:43:14'),
(327, 33, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjMzIiwicm9sZV9pZCI6IjE1Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImdyZHN1cGVyaW50QHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiZ2FyZGVuIHN1cGVyaW50ZW5kZW50IiwidXNlcl9tb2JpbGUiOiI3ODk0NTYzMjE1IiwiZGVwdF9pZCI6IjMiLCJwYX', '2020-11-27 17:39:52', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:43:49'),
(328, 34, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM0Iiwicm9sZV9pZCI6IjYiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZHlwdGNvbW1AeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkeXB0IGNvbW1pc3Npb25lciIsInVzZXJfbW9iaWxlIjoiNzgxMjQ1NjU5OCIsImRlcHRfaWQiOiIzIiwicGFzc3dvcmQiOi', '2020-11-27 17:40:45', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:49:15'),
(329, 35, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM1Iiwicm9sZV9pZCI6IjI1Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFkZGNvbW1pQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiYWRkaXRpb25hbCBjb21taXNzaW9uZXIiLCJ1c2VyX21vYmlsZSI6Ijk4MzI0NTM2MTQiLCJkZXB0X2lkIjoiMyIsInBhc3', '2020-11-27 17:42:01', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 17:58:15'),
(330, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:01:01'),
(331, 35, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM1Iiwicm9sZV9pZCI6IjI1Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFkZGNvbW1pQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiYWRkaXRpb25hbCBjb21taXNzaW9uZXIiLCJ1c2VyX21vYmlsZSI6Ijk4MzI0NTM2MTQiLCJkZXB0X2lkIjoiMyIsInBhc3', '2020-11-27 17:42:01', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:05:29'),
(332, 36, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM2Iiwicm9sZV9pZCI6IjQiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY29tbWlzc2lvbmVyQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiY29tbWlzc2lvbmVyIiwidXNlcl9tb2JpbGUiOiI5ODMyNzg0NTY1IiwiZGVwdF9pZCI6IjMiLCJwYXNzd29yZCI6Ii', '2020-11-27 17:42:37', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:06:09'),
(333, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:07:30'),
(334, 36, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM2Iiwicm9sZV9pZCI6IjQiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY29tbWlzc2lvbmVyQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiY29tbWlzc2lvbmVyIiwidXNlcl9tb2JpbGUiOiI5ODMyNzg0NTY1IiwiZGVwdF9pZCI6IjMiLCJwYXNzd29yZCI6Ii', '2020-11-27 17:42:37', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:24:14'),
(335, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 18:25:10'),
(336, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 19:54:27'),
(337, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 19:58:39'),
(338, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-27 19:58:39'),
(339, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 10:30:02'),
(340, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-30 10:43:23'),
(341, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 10:49:52'),
(342, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-30 12:14:29'),
(343, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 14:52:56'),
(344, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 16:25:32'),
(345, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-30 16:35:50'),
(346, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-11-30 17:43:56'),
(347, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 18:15:18'),
(348, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 18:49:50'),
(349, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 19:53:38'),
(350, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 19:55:14'),
(351, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:28:13'),
(352, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:28:28'),
(353, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:39:52'),
(354, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:40:49'),
(355, 36, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM2Iiwicm9sZV9pZCI6IjQiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY29tbWlzc2lvbmVyQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiY29tbWlzc2lvbmVyIiwidXNlcl9tb2JpbGUiOiI5ODMyNzg0NTY1IiwiZGVwdF9pZCI6IjMiLCJwYXNzd29yZCI6Ii', '2020-11-27 17:42:37', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:42:38'),
(356, 36, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM2Iiwicm9sZV9pZCI6IjQiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY29tbWlzc2lvbmVyQHlvcG1haWwuY29tIiwidXNlcl9uYW1lIjoiY29tbWlzc2lvbmVyIiwidXNlcl9tb2JpbGUiOiI5ODMyNzg0NTY1IiwiZGVwdF9pZCI6IjMiLCJwYXNzd29yZCI6Ii', '2020-11-27 17:42:37', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:43:44'),
(357, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:44:12'),
(358, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:46:30'),
(359, 29, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI5Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZ2FyZGVuY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJHcmFkZW4gQ2xlcmsiLCJ1c2VyX21vYmlsZSI6IjEyMzQ1Njc4MDkiLCJkZXB0X2lkIjoiMyIsInBhc3N3b3JkIjoiJD', '2020-11-23 04:25:18', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-11-30 20:56:02'),
(360, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-12-01 16:00:59'),
(361, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-12-02 10:44:20'),
(362, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-12-02 17:03:21'),
(363, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.66', 'Windows 8.1', '2020-12-03 11:21:58'),
(364, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-12-03 12:26:01'),
(365, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.66', 'Windows 10', '2020-12-03 17:38:33'),
(366, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 10:33:58'),
(367, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 13:24:08'),
(368, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 15:43:56'),
(369, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 16:49:30'),
(370, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 16:52:37'),
(371, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 16:57:09'),
(372, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:01:21'),
(373, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:01:39'),
(374, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:02:05'),
(375, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:02:24'),
(376, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:02:35'),
(377, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 17:08:56'),
(378, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:12:44'),
(379, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:16:24'),
(380, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:17:48'),
(381, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:20:54'),
(382, 37, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM3Iiwicm9sZV9pZCI6IjI2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFiY0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImFiYyIsInVzZXJfbW9iaWxlIjoiMTIzNDU2Nzg5OCIsImRlcHRfaWQiOiIxNSIsInBhc3N3b3JkIjoiJDJhJDA4JE8zazdoYmJvTG', '2020-12-04 17:31:22', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:31:50'),
(383, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:32:21'),
(384, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 17:50:55'),
(385, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 17:55:31'),
(386, 37, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM3Iiwicm9sZV9pZCI6IjI2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFiY0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImFiYyIsInVzZXJfbW9iaWxlIjoiMTIzNDU2Nzg5OCIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkTzNrN2hiYm9MbW', '2020-12-04 17:31:22', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:56:47'),
(387, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 17:57:06'),
(388, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-04 18:24:48'),
(389, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:28:07'),
(390, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:45:52'),
(391, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:48:22'),
(392, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:56:01'),
(393, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:57:44'),
(394, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:58:11'),
(395, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 20:59:21'),
(396, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 21:00:46'),
(397, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 21:01:13'),
(398, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 21:04:00'),
(399, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 21:32:35'),
(400, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-04 22:48:09'),
(401, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 10:32:25'),
(402, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 10:44:08');
INSERT INTO `auth_sessions` (`id`, `user_id`, `token`, `login_time`, `ip_address`, `browser`, `browser_version`, `os`, `created_at`) VALUES
(403, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 10:48:42'),
(404, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 10:58:03'),
(405, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 11:01:02'),
(406, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 11:10:55'),
(407, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 11:14:19'),
(408, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 11:18:32'),
(409, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 11:59:27'),
(410, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:05:56'),
(411, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:06:25'),
(412, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 16:10:09'),
(413, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:12:33'),
(414, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:12:54'),
(415, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:46:07'),
(416, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:50:55'),
(417, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:53:55'),
(418, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 16:55:05'),
(419, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 17:05:48'),
(420, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 17:46:29'),
(421, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 17:48:46'),
(422, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 17:51:55'),
(423, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-05 17:57:56'),
(424, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 17:59:00'),
(425, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 18:01:48'),
(426, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 18:06:27'),
(427, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 19:20:11'),
(428, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 19:20:49'),
(429, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 19:48:40'),
(430, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 20:36:54'),
(431, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-05 20:42:04'),
(432, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 12:32:16'),
(433, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 12:32:33'),
(434, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 13:01:31'),
(435, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 13:03:56'),
(436, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 14:53:41'),
(437, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 14:53:49'),
(438, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 14:55:09'),
(439, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 14:56:34'),
(440, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 14:57:05'),
(441, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 14:57:31'),
(442, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:00:17'),
(443, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:01:52'),
(444, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:02:19'),
(445, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:02:49'),
(446, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:08:01'),
(447, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:10:08'),
(448, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:18:37'),
(449, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:21:36'),
(450, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:22:32'),
(451, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:23:11'),
(452, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:39:33'),
(453, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:40:45'),
(454, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:42:44'),
(455, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:44:49'),
(456, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:44:52'),
(457, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:47:20'),
(458, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 15:49:52'),
(459, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 15:51:29'),
(460, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:07:37'),
(461, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:08:14'),
(462, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:09:55'),
(463, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:10:13'),
(464, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:10:43'),
(465, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:11:07'),
(466, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 16:19:12'),
(467, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 16:20:07'),
(468, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 16:44:48'),
(469, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 16:47:22'),
(470, 37, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM3Iiwicm9sZV9pZCI6IjI2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFiY0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImFiYyIsInVzZXJfbW9iaWxlIjoiMTIzNDU2Nzg5OCIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkTzNrN2hiYm9MbW', '2020-12-04 17:31:22', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 16:48:15'),
(471, 37, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM3Iiwicm9sZV9pZCI6IjI2Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6ImFiY0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6ImFiYyIsInVzZXJfbW9iaWxlIjoiMTIzNDU2Nzg5OCIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkTzNrN2hiYm9MbW', '2020-12-04 17:31:22', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 16:49:20'),
(472, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:15:49'),
(473, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:17:51'),
(474, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:24:18'),
(475, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:25:15'),
(476, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:26:57'),
(477, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:27:21'),
(478, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:40:54'),
(479, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:42:53'),
(480, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:43:05'),
(481, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:44:14'),
(482, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:45:24'),
(483, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:46:17'),
(484, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:46:41'),
(485, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:46:59'),
(486, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:47:33'),
(487, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-07 17:47:53'),
(488, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:48:58'),
(489, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:49:28'),
(490, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:52:07'),
(491, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:52:27'),
(492, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:52:41'),
(493, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:55:35'),
(494, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:58:07'),
(495, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:58:41'),
(496, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 17:59:25'),
(497, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:00:52'),
(498, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:01:17'),
(499, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:48:22'),
(500, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:49:57'),
(501, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:50:30'),
(502, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 18:51:32'),
(503, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 19:21:01'),
(504, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 19:31:30'),
(505, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 19:31:59'),
(506, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 19:33:17'),
(507, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:01:06'),
(508, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:03:46'),
(509, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:30:25'),
(510, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:30:26'),
(511, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:40:01'),
(512, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:40:38'),
(513, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:41:32'),
(514, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:44:40'),
(515, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:47:13'),
(516, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 20:57:39'),
(517, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:13:28'),
(518, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:18:02'),
(519, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:18:53'),
(520, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:21:43'),
(521, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:35:03'),
(522, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-07 21:35:36'),
(523, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:52:09'),
(524, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:55:47'),
(525, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:56:22'),
(526, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:58:03'),
(527, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:58:33'),
(528, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:59:11'),
(529, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 09:59:25'),
(530, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 10:00:55'),
(531, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 10:01:19'),
(532, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 10:04:26'),
(533, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-08 10:29:03'),
(534, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-08 10:34:23'),
(535, 38, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjM4Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiY2xlcmttbmRhcEB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6IkhhcnJ5IiwidXNlcl9tb2JpbGUiOiI1NjU2NTY1NjU2IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcmQiOiIkMmEkMDgkZH', '2020-12-08 10:42:29', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-08 10:43:41'),
(536, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 10:51:13');
INSERT INTO `auth_sessions` (`id`, `user_id`, `token`, `login_time`, `ip_address`, `browser`, `browser_version`, `os`, `created_at`) VALUES
(537, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 12:37:02'),
(538, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 12:43:42'),
(539, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 12:44:24'),
(540, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 12:46:40'),
(541, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 12:49:50'),
(542, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 12:53:52'),
(543, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 12:59:02'),
(544, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:01:17'),
(545, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:02:22'),
(546, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:03:38'),
(547, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:05:56'),
(548, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:06:18'),
(549, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:07:59'),
(550, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:10:08'),
(551, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:10:53'),
(552, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:12:52'),
(553, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:13:14'),
(554, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:16:28'),
(555, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:17:41'),
(556, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:25:17'),
(557, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:25:56'),
(558, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 13:28:13'),
(559, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:32:07'),
(560, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:32:29'),
(561, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:50:45'),
(562, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 13:51:48'),
(563, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 14:47:37'),
(564, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 14:48:34'),
(565, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 15:08:55'),
(566, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 15:15:38'),
(567, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 15:16:20'),
(568, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 15:47:55'),
(569, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 15:51:33'),
(570, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 16:29:37'),
(571, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 16:36:20'),
(572, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 16:36:57'),
(573, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 16:42:29'),
(574, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 16:43:43'),
(575, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 17:00:18'),
(576, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 17:04:37'),
(577, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 17:07:36'),
(578, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 17:15:33'),
(579, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 17:24:08'),
(580, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 17:36:19'),
(581, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:10:01'),
(582, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:14:02'),
(583, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:14:41'),
(584, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:15:13'),
(585, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:15:35'),
(586, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.135', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-08 18:17:43'),
(587, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:23:19'),
(588, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:23:54'),
(589, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 18:29:57'),
(590, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:31:13'),
(591, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:35:39'),
(592, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:37:15'),
(593, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:38:04'),
(594, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:39:15'),
(595, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:39:36'),
(596, 27, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI3Iiwicm9sZV9pZCI6IjI0Iiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6InNlbmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6InNlbmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiOTg2NTMyNDUxMiIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:31:04', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:39:53'),
(597, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.102', 'Chrome', '86.0.4240.198', 'Windows 8.1', '2020-12-08 18:46:03'),
(598, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 19:10:19'),
(599, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-08 20:23:45'),
(600, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 10:09:57'),
(601, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 14:47:57'),
(602, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 16:01:49'),
(603, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 16:19:37'),
(604, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 16:26:29'),
(605, 25, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI1Iiwicm9sZV9pZCI6IjIyIiwid2FyZF9pZCI6IjMiLCJlbWFpbF9pZCI6Im1ibWNob0B5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im1ibWNobyIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MzIxNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOiIkMmEkMDgkazlPQz', '2020-11-22 17:29:55', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 16:42:33'),
(606, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 16:43:30'),
(607, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 17:57:49'),
(608, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 17:59:56'),
(609, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 18:13:53'),
(610, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 18:15:08'),
(611, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 18:20:16'),
(612, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 18:56:05'),
(613, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-09 18:57:37'),
(614, 24, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiaG9zcGl0YWxjbGVhcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJob3NwaXRhbGNsZWFyayIsInVzZXJfbW9iaWxlIjoiNzg5NjU0MTIzNSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcm', '2020-11-22 17:28:45', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 19:22:23'),
(615, 26, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjI2Iiwicm9sZV9pZCI6IjIzIiwid2FyZF9pZCI6IjAiLCJlbWFpbF9pZCI6Imp1bmlvcmRvY3RvckB5b3BtYWlsLmNvbSIsInVzZXJfbmFtZSI6Imp1bmlvcmRvY3RvciIsInVzZXJfbW9iaWxlIjoiNzg0NTEyMzI2NSIsImRlcHRfaWQiOiI1IiwicGFzc3dvcmQiOi', '2020-11-22 17:30:33', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-09 19:49:24'),
(616, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.187', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-10 10:17:38'),
(617, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 12:34:21'),
(618, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.135', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-10 16:39:01'),
(619, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:28:17'),
(620, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:29:43'),
(621, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibWFuZGFwY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVyayIsInVzZXJfbW9iaWxlIjoiNzg5ODQ1NjUxMiIsImRlcHRfaWQiOiIxMiIsInBhc3N3b3JkIjoiJD', '2020-12-10 17:31:34', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:31:45'),
(622, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:34:10'),
(623, 40, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQwIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoibWFuZGFwY2xlcmtAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVyayIsInVzZXJfbW9iaWxlIjoiNzg5ODQ1NjUxMiIsImRlcHRfaWQiOiIxMiIsInBhc3N3b3JkIjoiJD', '2020-12-10 17:31:34', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:35:52'),
(624, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.135', 'Chrome', '87.0.4280.88', 'Windows 10', '2020-12-10 17:36:34'),
(625, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-10 17:50:25'),
(626, 22, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIyIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoic21uMTAxMjk2QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6Im5pY2siLCJ1c2VyX21vYmlsZSI6Ijc4OTQ1NjEyMzgiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIjoiJDJhJDA4JHFEdDMuei', '2020-11-10 08:28:47', '192.168.1.102', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 10:31:35'),
(627, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 10:50:26'),
(628, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 11:08:23'),
(629, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 11:08:49'),
(630, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 12:26:55'),
(631, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 12:28:42'),
(632, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTEiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cxIiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE1IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:19:57', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 12:45:09'),
(633, 23, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIzIiwicm9sZV9pZCI6IjAiLCJ3YXJkX2lkIjoiMCIsImVtYWlsX2lkIjoiZGh5ZXlyYXRob2QxMTFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJkaHlleSByYXRob2QiLCJ1c2VyX21vYmlsZSI6IjEyMzY1NDc4OTUiLCJkZXB0X2lkIjoiMCIsInBhc3N3b3JkIj', '2020-11-18 05:55:03', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 12:45:54'),
(634, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTEiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cxIiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE1IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:19:57', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 14:48:46'),
(635, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjEwIiwid2FyZF9pZCI6IjExIiwiZW1haWxfaWQiOiJtYW5kYXB3YXJkb2ZmdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXB3YXJkb2ZmdzEiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzgiLCJkZXB0X2lkIjoiMTIiLCJwYX', '2020-12-11 12:21:43', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 16:28:12'),
(636, 44, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTIiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzJAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cyIiwidXNlcl9tb2JpbGUiOiI3ODkzMjE0NTY4IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:20:52', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 16:29:46'),
(637, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjEwIiwid2FyZF9pZCI6IjExIiwiZW1haWxfaWQiOiJtYW5kYXB3YXJkb2ZmdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXB3YXJkb2ZmdzEiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzgiLCJkZXB0X2lkIjoiMTIiLCJwYX', '2020-12-11 12:21:43', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 18:59:22'),
(638, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTEiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cxIiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE1IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:19:57', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 18:59:41'),
(639, 44, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ0Iiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTIiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzJAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cyIiwidXNlcl9tb2JpbGUiOiI3ODkzMjE0NTY4IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:20:52', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:02:43'),
(640, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTEiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cxIiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE1IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:19:57', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:03:25'),
(641, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjEwIiwid2FyZF9pZCI6IjExIiwiZW1haWxfaWQiOiJtYW5kYXB3YXJkb2ZmdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXB3YXJkb2ZmdzEiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzgiLCJkZXB0X2lkIjoiMTIiLCJwYX', '2020-12-11 12:21:43', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:09:42'),
(642, 6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjYiLCJyb2xlX2lkIjoiMSIsIndhcmRfaWQiOiIwIiwiZW1haWxfaWQiOiJ0ZXN0QGdtYWlsLmNvbSIsInVzZXJfbmFtZSI6InRlc3QiLCJ1c2VyX21vYmlsZSI6Ijk4NzU2NDEyMzIiLCJkZXB0X2lkIjoiMjIiLCJwYXNzd29yZCI6IiQyYSQwOCR3dWEwWlh3YzhhMk', '2020-09-16 07:24:09', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:16:25'),
(643, 45, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQ1Iiwicm9sZV9pZCI6IjEwIiwid2FyZF9pZCI6IjExIiwiZW1haWxfaWQiOiJtYW5kYXB3YXJkb2ZmdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXB3YXJkb2ZmdzEiLCJ1c2VyX21vYmlsZSI6Ijc4OTY1NDEyMzgiLCJkZXB0X2lkIjoiMTIiLCJwYX', '2020-12-11 12:21:43', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:19:32'),
(644, 43, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjQzIiwicm9sZV9pZCI6IjMiLCJ3YXJkX2lkIjoiMTEiLCJlbWFpbF9pZCI6Im1hbmRhcGNsZXJrdzFAeW9wbWFpbC5jb20iLCJ1c2VyX25hbWUiOiJtYW5kYXBjbGVya3cxIiwidXNlcl9tb2JpbGUiOiI5ODc0NTYzMjE1IiwiZGVwdF9pZCI6IjEyIiwicGFzc3dvcm', '2020-12-11 12:19:57', '192.168.1.63', 'Chrome', '87.0.4280.88', 'Windows 8.1', '2020-12-11 19:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_applications`
--

CREATE TABLE `clinic_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(255) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `applicant_qualification` varchar(255) NOT NULL,
  `clinic_name` text NOT NULL,
  `clinic_address` longtext NOT NULL,
  `clinic_telephone_no` varchar(255) NOT NULL,
  `ownership_agreement` int(11) NOT NULL,
  `tax_receipt` int(11) NOT NULL,
  `bio_medical_certificate` int(11) NOT NULL DEFAULT '0',
  `doc_certificate` int(11) NOT NULL,
  `aadhaar_card` int(11) NOT NULL DEFAULT '0',
  `user_image` int(11) NOT NULL DEFAULT '0',
  `bio_medical_valid_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_type` int(11) NOT NULL COMMENT '1 = new application , 2 = renewal application',
  `health_officer` int(11) NOT NULL DEFAULT '0',
  `cold_chain_facilities` text NOT NULL,
  `no_of_expiry_certificate` varchar(255) DEFAULT NULL,
  `date_of_expiry_certificate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clinic_applications`
--

INSERT INTO `clinic_applications` (`id`, `app_id`, `applicant_name`, `applicant_email_id`, `applicant_mobile_no`, `applicant_alternate_no`, `applicant_address`, `applicant_qualification`, `clinic_name`, `clinic_address`, `clinic_telephone_no`, `ownership_agreement`, `tax_receipt`, `bio_medical_certificate`, `doc_certificate`, `aadhaar_card`, `user_image`, `bio_medical_valid_date`, `status`, `is_deleted`, `created_at`, `updated_at`, `user_id`, `application_type`, `health_officer`, `cold_chain_facilities`, `no_of_expiry_certificate`, `date_of_expiry_certificate`) VALUES
(1, 103, 'Roshni Patel', 'roshnipatel@yopmail.com', '7896541235', '7896541235', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness', 'Eiusmod amet cumque', 'Roshni Patel', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness', '7896541235', 345, 358, 348, 357, 349, 0, '2020-12-10', 0, 0, '2020-12-05 17:55:44', '0000-00-00 00:00:00', 23, 1, 0, 'Accusantium molestia', NULL, NULL),
(2, 107, 'Tom ', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'New Address ', 'MBBS', 'New Mumbai ', 'New Address ', '02226231322', 371, 372, 374, 373, 375, 0, '0000-00-00', 81, 0, '2020-12-07 17:22:55', '2020-12-07 17:25:56', 22, 1, 3, '', NULL, NULL),
(3, 112, 'Hayfa Ware', 'cadaboz@yopmail.com', 'Veniam recu', 'Quam qui elit iusto', 'Quam voluptatem Eni', 'Ipsum in amet sed ', 'Kelly Harrison', 'Lorem qui quae unde ', '+1 (879) 457-6253', 396, 397, 399, 398, 400, 0, '2020-12-23', 85, 0, '2020-12-07 20:39:23', '2020-12-07 20:57:59', 23, 2, 3, 'Aliqua Ut dolore se', NULL, NULL),
(4, 113, 'testamount', 'testamount@yopmail.com', 'Sapiente et', 'Mollit culpa nisi en', 'Facere quisquam cum ', 'Iusto natus rerum do', 'testamount', 'Excepteur sunt harum', '+1 (857) 949-1468', 401, 402, 404, 403, 405, 459, '2020-12-29', 85, 0, '2020-12-07 21:17:24', '2020-12-08 11:00:40', 23, 2, 3, 'Et eligendi ullamco ', '123456', '2020-12-23'),
(5, 114, 'Hell bond hacker', 'hellbondhacker@yopmail.com', '+1 (121) 86', '+1 (121) 866-7891', 'Consequat Earum non', 'CEH', 'Hellbondhacker', 'Illum reprehenderit', '+1 (121) 866-7891', 406, 407, 409, 408, 410, 458, '2020-12-16', 81, 0, '2020-12-08 09:55:09', '2020-12-08 13:51:19', 23, 1, 3, 'Tenetur sed repellen', NULL, NULL),
(6, 116, 'Tom', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\njuhu', 'mbbs', 'Test Dispensary ', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\n\r\n', '1234567890', 416, 417, 419, 418, 420, 0, '0000-00-00', 81, 0, '2020-12-08 12:41:55', '2020-12-08 12:45:03', 22, 1, 3, '', NULL, NULL),
(7, 117, 'Harry', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\njuhu\r\n', 'MBBs', 'Harry', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\n\r\n', '12345678', 421, 422, 424, 423, 425, 0, '0000-00-00', 85, 0, '2020-12-08 12:49:08', '2020-12-08 13:18:05', 22, 1, 3, '', NULL, NULL),
(8, 118, 'Harry ', 'suman.katimani@aaravsoftware.com', '1234567890', '', 'bgdfhdghdfgudifuh;sdfojbh', 'MBBS', 'Dr. Harry ', 'dhfjgfkhgklhjljhklgj', '1234567890', 426, 427, 429, 428, 430, 0, '0000-00-00', 81, 0, '2020-12-08 13:23:03', '2020-12-08 13:27:50', 22, 2, 3, '', 'MH/THN/MBMC/19', '2020-12-08'),
(9, 119, 'Nick', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\n\r\njuhu ', 'MBBS', 'Harry ', 'Unit-906 9th Floor, DLH Park, SV Rd, Rani Sati Nagar, Sunder Nagar, Malad West, Mumbai, Maharashtra 400064 India.\r\n\r\n', '123456789', 431, 432, 434, 433, 435, 0, '0000-00-00', 85, 0, '2020-12-08 14:45:52', '2020-12-08 15:16:35', 22, 2, 3, '', 'MH/THN/2020/19', '2020-12-08');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_staff`
--

CREATE TABLE `clinic_staff` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `sr_number_clinic_Staff` int(11) DEFAULT NULL,
  `name_clinic_Staff` varchar(255) DEFAULT NULL,
  `designation_clinic_Staff` int(11) DEFAULT NULL,
  `qualification_clinic_Staff` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clinic_staff`
--

INSERT INTO `clinic_staff` (`id`, `app_id`, `sr_number_clinic_Staff`, `name_clinic_Staff`, `designation_clinic_Staff`, `qualification_clinic_Staff`, `created_at`) VALUES
(46, 1, 281, 'Odessa Fox', 3, '3', '2020-12-05 14:30:49'),
(47, 1, 470, 'Nerea Lewis', 4, '4', '2020-12-05 14:30:49'),
(48, 1, 505, 'Lani Munoz', 5, '3', '2020-12-05 14:30:49'),
(49, 1, 577, 'Alexandra Edwards', 5, '2', '2020-12-05 14:30:49'),
(50, 1, 941, 'Ursa Riley', 2, '2', '2020-12-05 14:30:49'),
(51, 2, 1, 'Bob', 6, '3', '2020-12-07 11:52:56'),
(57, 3, 611, 'Minerva Weiss', 2, '4', '2020-12-07 15:09:31'),
(58, 3, 443, 'Ava Francis', 1, '1', '2020-12-07 15:09:31'),
(59, 3, 612, 'Howard Wiley', 1, '2', '2020-12-07 15:09:31'),
(60, 3, 450, 'Ina Dawson', 5, '3', '2020-12-07 15:09:31'),
(61, 3, 136, 'Bevis Whitaker', 3, '1', '2020-12-07 15:09:31'),
(83, 6, 1, 'Tom', 1, '1', '2020-12-08 07:11:55'),
(84, 7, 1, 'Harry', 1, '1', '2020-12-08 07:19:08'),
(85, 8, 1, 'Harry', 1, '1', '2020-12-08 07:53:03'),
(86, 9, 1, 'Nick', 1, '1', '2020-12-08 09:15:52'),
(122, 5, 249, 'Kyle Haley', 4, '1', '2020-12-08 12:27:39'),
(123, 5, 45, 'Kristen Morris', 5, '1', '2020-12-08 12:27:39'),
(124, 5, 791, 'Ayanna Malone', 4, '3', '2020-12-08 12:27:39'),
(125, 5, 901, 'Keely Bates', 4, '1', '2020-12-08 12:27:39'),
(126, 5, 877, 'Penelope Nash', 5, '2', '2020-12-08 12:27:39'),
(127, 4, 289, 'Hop Vargas', 6, '1', '2020-12-08 12:39:48'),
(128, 4, 24, 'Tanner Cross', 2, '3', '2020-12-08 12:39:48'),
(129, 4, 427, 'Karleigh Gray', 4, '1', '2020-12-08 12:39:48'),
(130, 4, 15, 'Tatum Drake', 3, '2', '2020-12-08 12:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `closed_application_table`
--

CREATE TABLE `closed_application_table` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `remark_note` text NOT NULL,
  `refundable_amount` double NOT NULL,
  `refund_status` int(11) NOT NULL COMMENT '1 = paid , 2 = unpaid',
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `closed_application_table`
--

INSERT INTO `closed_application_table` (`id`, `app_id`, `dept_id`, `user_id`, `role_id`, `payment_status`, `remark_note`, `refundable_amount`, `refund_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, 1, 9, 3, 2, 'Close the application test application', 9360, 0, 1, '2020-11-18 09:20:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company_address`
--

CREATE TABLE `company_address` (
  `address_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 2: Deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_address`
--

INSERT INTO `company_address` (`address_id`, `company_id`, `company_address`, `date_added`, `status`) VALUES
(1, 1, 'Mahanagar Gas', '2020-10-19 11:52:57', 1),
(2, 2, 'Adani Power', '2020-10-19 11:53:14', 1),
(3, 3, 'Tata Power', '2020-10-19 11:53:31', 1),
(4, 4, 'MTNL', '2020-10-19 11:53:51', 1),
(5, 5, 'Idea', '2020-10-19 11:54:06', 1),
(6, 6, 'Airtel', '2020-10-19 11:54:22', 1),
(7, 7, 'Vodafone', '2020-10-19 11:54:45', 1),
(8, 8, 'Reliance Jio', '2020-10-19 11:55:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `company_details`
--

CREATE TABLE `company_details` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_details`
--

INSERT INTO `company_details` (`company_id`, `company_name`, `date_added`, `status`) VALUES
(1, 'Mahanagar Gas', '2020-10-19 11:52:57', 1),
(2, 'Adani Power', '2020-10-19 11:53:13', 1),
(3, 'Tata Power', '2020-10-19 11:53:31', 1),
(4, 'MTNL', '2020-10-19 11:53:51', 1),
(5, 'Idea', '2020-10-19 11:54:06', 1),
(6, 'Airtel', '2020-10-19 11:54:22', 1),
(7, 'Vodafone', '2020-10-19 11:54:45', 1),
(8, 'Reliance Jio', '2020-10-19 11:55:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `defect_laiblity`
--

CREATE TABLE `defect_laiblity` (
  `laib_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `laib_name` varchar(100) NOT NULL,
  `mul_factor` double NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_till` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:active, 2:not active',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `defect_laiblity`
--

INSERT INTO `defect_laiblity` (`laib_id`, `user_id`, `laib_name`, `mul_factor`, `date_from`, `date_till`, `status`, `date_added`) VALUES
(1, 28, 'Excavation during Fifth Year of Defect Liability Period', 1.4, '2020-10-18', '2021-10-18', 1, '2020-10-07 11:45:28'),
(2, 28, 'Excavation during Second Year of Defect Liability Period', 3, '2020-10-18', '2021-10-18', 1, '2020-10-07 11:46:12'),
(3, 0, 'Excavation during Third Year of Defect Liability Period', 2, '2020-10-18', '2021-10-18', 1, '2020-10-07 11:47:16'),
(4, 28, 'abc', 5, '2020-10-18', '2021-10-18', 2, '2020-10-14 15:57:55'),
(5, 28, 'Excavation during First Year of Defect Liability Period', 4, '2020-10-18', '2021-10-18', 1, '2020-10-19 11:58:29'),
(6, 28, 'Excavation during Forth Year of Defect Liability Period', 1.7, '2020-10-18', '2021-10-18', 1, '2020-10-19 11:59:10'),
(7, 28, 'Excavation beyond of Defect Liability Period', 1, '2020-10-18', '2021-10-18', 1, '2020-10-19 12:00:09');

--
-- Triggers `defect_laiblity`
--
DELIMITER $$
CREATE TRIGGER `logs` AFTER UPDATE ON `defect_laiblity` FOR EACH ROW BEGIN
UPDATE `defect_laiblity_logs` SET status = '2' WHERE 1;
INSERT INTO `defect_laiblity_logs`(`laib_id`, `laib_name`, `mul_factor`, `date_from`, `date_till`, `created_user`, `updated_user`, `created_date`, `updated_date`) VALUES (OLD.laib_id, OLD.laib_name, OLD.mul_factor, OLD.date_from, OLD.date_till, OLD.user_id, NEW.user_id, OLD.date_added, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `defect_laiblity_logs`
--

CREATE TABLE `defect_laiblity_logs` (
  `laib_log_id` int(11) NOT NULL,
  `laib_id` int(11) NOT NULL,
  `laib_name` varchar(100) NOT NULL,
  `mul_factor` double NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_till` date DEFAULT NULL,
  `created_user` int(11) NOT NULL,
  `updated_user` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Not Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `defect_laiblity_logs`
--

INSERT INTO `defect_laiblity_logs` (`laib_log_id`, `laib_id`, `laib_name`, `mul_factor`, `date_from`, `date_till`, `created_user`, `updated_user`, `created_date`, `updated_date`, `status`) VALUES
(1, 1, 'Excavation during First Year of Defect Liability Period', 2, '2020-10-06', '2020-10-31', 28, 28, '2020-10-07 11:45:28', '2020-10-07 12:40:07', 2),
(2, 1, 'Excavation during First Year of Defect Liability Period', 2.1, '2020-10-06', '2020-10-31', 28, 28, '2020-10-07 11:45:28', '2020-10-07 12:40:45', 2),
(3, 3, 'Excavation during Third Year of Defect Liability Period', 2, '2020-10-06', '2020-10-31', 0, 0, '2020-10-07 11:47:16', '2020-10-07 13:28:27', 2),
(4, 3, 'Excavation during Third Year of Defect Liability Period', 2, '2020-10-06', '2020-10-31', 0, 0, '2020-10-07 11:47:16', '2020-10-07 13:28:51', 2),
(5, 1, 'Excavation during First Year of Defect Liability Period', 2.2, '2020-10-06', '2020-10-31', 28, 28, '2020-10-07 11:45:28', '2020-10-13 13:43:31', 2),
(6, 1, 'Excavation during First Year of Defect Liability Period', 4, '2020-10-06', '2020-10-31', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:38:51', 2),
(7, 1, 'Excavation during First Year of Defect Liability Period', 4, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:39:33', 2),
(8, 1, 'Excavation during Second Year of Defect Liability Period', 3, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:40:24', 2),
(9, 1, 'Excavation during Third Year of Defect Liability Period', 3, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:41:04', 2),
(10, 1, 'Excavation during Forth Year of Defect Liability Period', 1.7, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:41:40', 2),
(11, 1, 'Excavation during Fifth Year of Defect Liability Period', 1.4, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:42:21', 2),
(12, 1, 'Excavation during Fifth Year of Defect Liability Period', 1.4, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 11:42:48', 2),
(13, 4, 'abc', 5, '2020-10-14', '2020-10-15', 28, 28, '2020-10-14 15:57:55', '2020-10-19 11:45:58', 2),
(14, 2, 'Excavation during Second Year of Defect Liability Period', 2, '2020-10-06', '2020-10-31', 0, 28, '2020-10-07 11:46:12', '2020-10-19 12:01:47', 2),
(15, 1, 'Excavation during Fifth Year of Defect Liability Period', 1.4, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:45:28', '2020-10-19 12:01:57', 2),
(16, 2, 'Excavation during Second Year of Defect Liability Period', 3, '2020-10-18', '2021-10-18', 28, 28, '2020-10-07 11:46:12', '2020-10-19 12:01:57', 2),
(17, 3, 'Excavation during Third Year of Defect Liability Period', 2, '2020-10-06', '2020-10-31', 0, 0, '2020-10-07 11:47:16', '2020-10-19 12:01:57', 2),
(18, 4, 'abc', 5, '2020-10-14', '2020-10-15', 28, 28, '2020-10-14 15:57:55', '2020-10-19 12:01:57', 2),
(19, 5, 'Excavation during First Year of Defect Liability Period', 4, '2020-10-18', '2021-10-18', 28, 28, '2020-10-19 11:58:29', '2020-10-19 12:01:57', 2),
(20, 6, 'Excavation during Forth Year of Defect Liability Period', 1.7, '2020-10-18', '2020-10-18', 28, 28, '2020-10-19 11:59:10', '2020-10-19 12:01:57', 2),
(21, 7, 'Excavation beyond of Defect Liability Period', 1, '2020-10-18', '2021-10-18', 28, 28, '2020-10-19 12:00:09', '2020-10-19 12:01:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department_table`
--

CREATE TABLE `department_table` (
  `dept_id` int(11) NOT NULL,
  `dept_title` varchar(255) NOT NULL,
  `department_mail_id` varchar(50) NOT NULL,
  `dept_desc` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department_table`
--

INSERT INTO `department_table` (`dept_id`, `dept_title`, `department_mail_id`, `dept_desc`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'PWD', 'pwd@mbmc.in', 'Public Works department', 1, 0, '2020-03-21 00:00:00', '2020-10-19 08:26:02'),
(2, 'Storage', '', 'Storage department', 2, 1, '2020-03-21 00:00:00', '2020-03-30 12:27:28'),
(3, 'Garden', 'garden@yopmail.com', 'Tree', 1, 0, '2020-03-23 14:02:44', '2020-11-27 16:37:16'),
(4, 'Tree Cutting', '', 'Tree', 2, 1, '2020-03-23 14:03:41', '2020-06-01 09:09:22'),
(5, 'Medical', 'Medical@yopmail.com', 'medical Health ', 1, 0, '2020-03-30 12:27:12', '2020-12-07 17:18:56'),
(6, 'Hall', '', 'hall', 1, 0, '2020-04-04 11:19:06', '2020-04-04 11:22:48'),
(7, 'trade', '', 'Trade Fact lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(8, 'godown', '', 'Godown Lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(9, 'film', '', 'Film Lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(10, 'templic', '', 'Temporary Lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(11, 'advertisement', '', 'advertisement', 1, 0, '2020-05-08 00:00:00', '2020-08-28 07:44:59'),
(12, 'Mandap', 'mandap@yopmail.com', 'Mandap', 1, 0, '2020-07-17 00:00:00', '2020-12-08 10:41:48'),
(13, 'Marriage', '', 'Marriage', 1, 0, '2020-07-17 00:00:00', '2020-07-17 00:00:00'),
(14, 'Head', '', 'Head Department', 1, 0, '2020-09-10 09:14:50', '2020-09-10 09:14:50'),
(15, 'Test_Medical', 'test@abc.com', 'medical health department', 1, 0, '2020-12-04 17:29:13', '2020-12-05 18:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `department_table_old`
--

CREATE TABLE `department_table_old` (
  `dept_id` int(11) NOT NULL,
  `dept_title` varchar(255) NOT NULL,
  `dept_desc` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department_table_old`
--

INSERT INTO `department_table_old` (`dept_id`, `dept_title`, `dept_desc`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'PWD', 'Public welfare department', 1, 0, '2020-03-21 00:00:00', '2020-03-30 12:32:21'),
(2, 'Storage', 'Storage department', 2, 0, '2020-03-21 00:00:00', '2020-05-04 15:28:48'),
(3, 'Garden', 'Tree', 1, 0, '2020-03-23 14:02:44', '2020-05-13 10:08:04'),
(4, 'Tree Cutting', 'Tree', 2, 0, '2020-03-23 14:03:41', '2020-04-03 07:57:31'),
(5, 'Medical', 'medical Health ', 1, 0, '2020-03-30 12:27:12', '2020-04-22 13:55:44'),
(6, 'Hall', 'hall', 1, 0, '2020-04-04 11:19:06', '2020-04-04 11:22:48'),
(7, 'tradeFactLic', 'Trade Fact lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(8, 'godown', 'Godown Lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(9, 'Mandap', '', 1, 0, '2020-05-04 07:24:28', '2020-05-04 07:24:28'),
(10, 'film', 'Film Lic', 1, 0, '2020-04-15 14:02:44', '2020-05-06 07:14:36'),
(13, 'tempLic', 'Temp Lic', 1, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(14, 'tradeFactLic', 'Trade Fact lic', 2, 0, '2020-04-15 14:02:44', '2020-04-15 14:02:44'),
(15, 'new dept', 'asc', 1, 0, '2020-05-21 13:48:37', '2020-05-21 13:48:37'),
(16, 'Marriage', 'Marriage ', 1, 0, '2020-06-10 09:19:49', '2020-06-10 09:19:49'),
(17, 'Advertisement', 'Advertisment', 1, 0, '2020-03-23 14:03:41', '2020-04-03 07:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_inspection_fees`
--

CREATE TABLE `deposit_inspection_fees` (
  `id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `inspection_fee` int(11) NOT NULL,
  `deposit` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = active dept fees. , 2 = old dept fees',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deposit_inspection_fees`
--

INSERT INTO `deposit_inspection_fees` (`id`, `dept_id`, `user_id`, `inspection_fee`, `deposit`, `from_date`, `to_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 6, 5000, 100, '2020-11-01', '2021-01-31', 1, '2020-11-27 11:09:39', '2020-11-27 11:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `designation_master`
--

CREATE TABLE `designation_master` (
  `design_id` int(11) NOT NULL,
  `design_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `designation_master`
--

INSERT INTO `designation_master` (`design_id`, `design_title`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Doctors', 1, 0, '2020-04-26 00:00:00', '2020-05-02 15:32:55'),
(2, 'Nurse', 1, 0, '2020-04-26 00:00:00', '2020-04-26 00:00:00'),
(3, 'Ward Boy', 2, 0, '2020-04-26 00:00:00', '2020-05-02 15:29:59'),
(4, 'Receptionist', 1, 0, '2020-04-26 00:00:00', '2020-04-26 00:00:00'),
(5, 'House', 1, 0, '2020-04-30 14:24:03', '2020-04-30 14:24:03'),
(6, 'test', 1, 0, '2020-05-02 15:30:08', '2020-05-02 15:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `filmdata`
--

CREATE TABLE `filmdata` (
  `film_id` int(11) NOT NULL,
  `form_no` varchar(25) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `reason_for_lic` varchar(255) NOT NULL,
  `place_of_shooting` varchar(50) NOT NULL,
  `period_from` date NOT NULL,
  `period_to` date NOT NULL,
  `police_noc` varchar(100) NOT NULL,
  `noc_path` varchar(50) NOT NULL,
  `aadhar` varchar(100) NOT NULL,
  `aadhar_path` varchar(50) NOT NULL,
  `pan` varchar(100) NOT NULL,
  `pan_path` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gardendata`
--

CREATE TABLE `gardendata` (
  `gardenId` int(11) NOT NULL,
  `complain_Id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL COMMENT 'permission id',
  `tree_no` int(11) NOT NULL,
  `tree_id` int(11) NOT NULL,
  `no_of_trees` int(11) NOT NULL,
  `conditionStatus` int(11) NOT NULL COMMENT '1:Hazardous,2:non hazardous',
  `reason_permission` varchar(500) NOT NULL,
  `orig_image` varchar(50) NOT NULL,
  `enc_image` varchar(100) NOT NULL,
  `image_path` varchar(100) NOT NULL,
  `image_size` varchar(25) NOT NULL,
  `refundable` int(11) NOT NULL DEFAULT '2' COMMENT '1:refundable, 2: Non Refundable',
  `refund_approval` int(11) NOT NULL COMMENT '0:Not Approve, 1:Approve',
  `refund_approved_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gardendata`
--

INSERT INTO `gardendata` (`gardenId`, `complain_Id`, `permission_id`, `tree_no`, `tree_id`, `no_of_trees`, `conditionStatus`, `reason_permission`, `orig_image`, `enc_image`, `image_path`, `image_size`, `refundable`, `refund_approval`, `refund_approved_by`, `status`, `is_deleted`, `date_added`, `updated_at`) VALUES
(1, 3, 1, 7083, 1, 0, 1, 'Necessitatibus quia ', '300_4.jpg', '7aa29ba0c99ef32c10f818dbcebd8d1e.jpg', 'http://192.168.1.59/mbmc/uploads/gardenImages/7aa29ba0c99ef32c10f818dbcebd8d1e.jpg', '63.73', 2, 0, 0, 1, 0, '2020-11-27 16:22:29', '2020-11-27 15:41:16'),
(2, 4, 0, 9919, 1, 3, 0, 'Maintanence', 'tree_1.png', '8c6dc3eddcf209fce9bc682dce4be216.png', 'http://192.168.1.59/mbmc/uploads/gardenImages/8c6dc3eddcf209fce9bc682dce4be216.png', '61.8', 2, 0, 0, 1, 0, '2020-11-30 11:54:01', '2020-11-30 11:54:01'),
(3, 5, 0, 6227, 1, 2, 0, 'Maintanence', '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '1e36ea7249fa859b15123e087d07cbcf.pdf', 'http://192.168.1.59/mbmc/uploads/gardenImages/1e36ea7249fa859b15123e087d07cbcf.pdf', '2.96', 2, 0, 0, 1, 0, '2020-11-30 16:37:11', '2020-11-30 16:37:11'),
(4, 6, 1, 3646, 1, 12, 1, 'Laborum in velit tem', '300_25.jpg', 'bc23478502e38615c7acfc6cf8fcb131.jpg', 'http://192.168.1.59/mbmc/uploads/gardenImages/bc23478502e38615c7acfc6cf8fcb131.jpg', '64.12', 2, 0, 0, 1, 0, '2020-11-30 20:53:55', '2020-11-30 20:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `garden_permission`
--

CREATE TABLE `garden_permission` (
  `garper_id` int(11) NOT NULL,
  `permission_type` varchar(100) NOT NULL,
  `is_blueprint` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:No BluePrint, 1: Blueprint',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `garden_permission`
--

INSERT INTO `garden_permission` (`garper_id`, `permission_type`, `is_blueprint`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tree Cutting', 1, 1, '2020-08-21 19:36:04', '2020-08-31 10:03:19'),
(2, 'Tree Transplantation', 1, 1, '2020-08-21 19:36:04', '2020-08-31 10:03:21'),
(3, 'Tree Trimming', 1, 1, '2020-08-21 19:36:04', '2020-08-31 10:02:46'),
(4, 'Tree Noc', 1, 1, '2020-08-21 19:36:04', '2020-08-22 01:22:14'),
(5, 'Other', 1, 1, '2020-11-30 05:20:39', '2020-11-30 15:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `godownapplication`
--

CREATE TABLE `godownapplication` (
  `godown_id` int(11) NOT NULL,
  `applicationDate` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `mobileNo` varchar(15) NOT NULL,
  `god_address1` varchar(255) NOT NULL,
  `god_address2` varchar(255) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `godown_area` varchar(100) NOT NULL,
  `type_of_godown` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `other_muncipal_lic` varchar(50) NOT NULL,
  `renewal_lic` int(11) NOT NULL,
  `old_lic_no` varchar(50) NOT NULL,
  `lic_no` varchar(50) NOT NULL,
  `explosive` int(11) NOT NULL,
  `pending_dues` varchar(50) NOT NULL,
  `disapprove_earlier` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `form_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hall_applications`
--

CREATE TABLE `hall_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(11) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `sku_price_id` int(11) NOT NULL,
  `booking_date` datetime NOT NULL,
  `reason` text NOT NULL,
  `amount` int(11) NOT NULL,
  `id_proof_id` int(11) NOT NULL,
  `address_proof_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `payment_status` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hall_assets`
--

CREATE TABLE `hall_assets` (
  `asset_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_unit_id` int(11) NOT NULL,
  `asset_unit_cost` varchar(255) NOT NULL,
  `penalty_charges` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hall_assets`
--

INSERT INTO `hall_assets` (`asset_id`, `sku_id`, `asset_name`, `asset_unit_id`, `asset_unit_cost`, `penalty_charges`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 2, 'Electricity', 3, '6', '0', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(2, 2, 'Dining Plate', 4, '20', '40', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(3, 2, 'Red Cover Chair', 4, '40', '80', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(4, 3, 'Electricity', 3, '6', '0', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(5, 3, 'Dining Plate', 4, '20', '40', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(6, 3, 'Red Cover Chair', 4, '40', '80', 1, 0, '2020-04-10 00:00:00', '2020-04-10 00:00:00'),
(7, 2, 'Spoons', 4, '10', '5', 1, 0, '2020-04-30 13:13:16', '2020-04-30 14:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `hall_checklist_details`
--

CREATE TABLE `hall_checklist_details` (
  `checklist_id` int(11) NOT NULL,
  `hall_app_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `consumed_unit` int(11) NOT NULL,
  `consumed_unit_cost` int(11) NOT NULL,
  `defected_unit` int(11) NOT NULL,
  `defected_unit_cost` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hall_type`
--

CREATE TABLE `hall_type` (
  `hall_id` int(11) NOT NULL,
  `hall_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hall_type`
--

INSERT INTO `hall_type` (`hall_id`, `hall_title`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Mbmc hall', 1, 0, '2020-04-04 00:00:00', '2020-04-04 00:00:00'),
(2, 'Abc hall', 1, 0, '2020-04-04 00:00:00', '2020-04-04 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_alien`
--

CREATE TABLE `hospital_alien` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `nationality` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_alien`
--

INSERT INTO `hospital_alien` (`id`, `app_id`, `name`, `age`, `qualification`, `nationality`) VALUES
(1, 1, 'Len Williamson', 5, 'Atque amet enim nih', 'Libero fugiat minus'),
(2, 1, 'Davis Owens', 69, 'Error officia culpa', 'Voluptatibus qui est'),
(3, 1, 'Alisa Strong', 69, 'Enim qui adipisci ne', 'Culpa earum aut moll'),
(4, 1, 'Xaviera Pate', 65, 'Qui quia esse recus', 'Sunt neque recusanda'),
(5, 1, 'Luke Chavez', 28, 'Distinctio Nemo aut', 'Minima repudiandae o'),
(58, 4, 'Alice Vaughn', 84, 'Modi minim culpa qu', 'Rerum reprehenderit'),
(59, 4, 'Louis Clay', 89, 'Doloremque sint et ', 'Ratione autem impedi'),
(60, 4, 'Breanna Olson', 71, 'Officiis non earum d', 'Iste sit et non ips'),
(61, 4, 'Cassidy Salinas', 85, 'Similique minus magn', 'Non veniam eum volu'),
(72, 5, 'Karly Benson', 39, 'Nulla aut molestiae ', 'Ut ut iste non qui l'),
(73, 5, 'Ariana King', 17, 'Qui quibusdam magni ', 'Minus aut in quam it'),
(74, 5, 'Cody Castillo', 31, 'Duis rerum in praese', 'Ex corrupti volupta'),
(75, 5, 'Elliott Cote', 55, 'Nobis exercitation c', 'Dolores eaque sed qu'),
(76, 5, 'Larissa Vang', 97, 'Ratione deserunt quo', 'Est non lorem facili'),
(77, 6, '', 0, '', ''),
(78, 7, 'Kirsten Pollard', 59, 'Fuga Natus mollitia', 'Voluptate consequatu'),
(80, 8, 'Knox Gomez', 28, 'Voluptatem consectet', 'Laborum non cillum a'),
(81, 9, '', 0, '', ''),
(86, 2, 'Hoyt Sherman', 32, 'In deleniti reiciend', 'Doloribus magnam dol'),
(87, 2, 'Ivor Short', 40, 'Modi cillum rerum se', 'Autem voluptatem sed'),
(88, 2, 'Kimberley Gregory', 93, 'Amet mollitia odit ', 'Aut praesentium ut o'),
(89, 2, 'Kareem Meyer', 3, 'Et inventore non con', 'Quos rerum quis illu'),
(90, 10, 'Isaiah Montgomery', 27, 'Officiis voluptatem', 'Tenetur incididunt f'),
(91, 3, 'Reed Sanchez', 8, 'Et accusantium liber', 'Necessitatibus disti'),
(92, 3, 'Jane Strickland', 41, 'Et nulla laudantium', 'Vero in do reiciendi'),
(93, 3, 'Kyra Sampson', 3, 'Qui exercitation lor', 'Dolor adipisci volup'),
(94, 3, 'Levi Carr', 70, 'Eos enim magnam ver', 'Molestiae sint omnis'),
(96, 12, 'Ella Wolfe', 78, 'Nisi labore architec', 'Minim est et archite'),
(102, 13, 'Jelani Hicks', 86, 'Sapiente velit exce', 'Quas dolore quam ips'),
(103, 13, 'Rama Chapman', 67, 'Animi esse numquam ', 'Tempora mollit velit'),
(104, 13, 'Armando Diaz', 74, 'Amet quos adipisci ', 'Quos ratione adipisc'),
(105, 13, 'Curran Lambert', 18, 'Dolor magni ex quis ', 'Nihil aliqua Aliqui'),
(106, 13, 'Melinda Boyle', 66, 'Sit accusamus tempo', 'Alias est adipisicin'),
(107, 14, '', 0, '', ''),
(112, 15, 'Iola Vinson', 89, 'Molestias veniam su', 'Dolor neque ab est '),
(113, 15, 'Jessamine Knowles', 15, 'Deleniti ut quo est', 'Sequi harum corporis'),
(114, 15, 'Melodie Miranda', 73, 'Accusamus minim amet', 'Soluta quo in accusa'),
(115, 15, 'Basia Newman', 28, 'Eum consectetur sin', 'Velit eiusmod tempo'),
(117, 11, 'Sonia Bowman', 43, 'Cillum id blanditiis', 'Consectetur in dolo'),
(120, 16, '', 0, '', ''),
(121, 17, '', 0, '', ''),
(122, 18, '', 0, '', ''),
(123, 19, 'Amena Torres', 13, 'Omnis voluptas fugia', 'Consequatur et est'),
(124, 20, 'Mira Lowe', 96, 'Perspiciatis invent', 'Vel eiusmod dignissi'),
(125, 21, '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_applications`
--

CREATE TABLE `hospital_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(255) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `applicant_nationality` varchar(11) NOT NULL,
  `technical_qualification` varchar(255) NOT NULL,
  `hospital_name` text NOT NULL,
  `hospital_address` longtext NOT NULL,
  `others` varchar(255) NOT NULL,
  `maternity_beds` varchar(255) NOT NULL,
  `patient_beds` text NOT NULL,
  `ownership_agreement` int(11) NOT NULL,
  `tax_receipt` int(11) NOT NULL,
  `doc_certificate` int(11) NOT NULL,
  `reg_certificate` int(11) NOT NULL,
  `staff_certificate` int(11) NOT NULL,
  `arrangement_for_checkup` text,
  `nursing_staff_deg_certificate` int(11) NOT NULL,
  `nursing_staff_reg_certificate` int(11) NOT NULL,
  `bio_des_certificate` int(11) NOT NULL,
  `society_noc` int(11) NOT NULL,
  `fire_noc` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `situation_registration` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `application_type` int(11) NOT NULL COMMENT '1 = new application , 2 = renewal application',
  `health_officer` int(11) NOT NULL DEFAULT '0',
  `detail_arrange_sanitary_employee` varchar(500) DEFAULT NULL,
  `detail_arrange_sanitary_patients` varchar(500) DEFAULT NULL,
  `storage_arrangements` varchar(500) DEFAULT NULL,
  `other_business_address` text,
  `accomodation` text,
  `proportion_of_qualified` varchar(255) DEFAULT NULL,
  `promise` int(11) NOT NULL DEFAULT '0',
  `no_of_expiry_certificate` varchar(255) DEFAULT NULL,
  `date_of_expiry_certificate` varchar(255) DEFAULT NULL,
  `unregisterd_medical` varchar(11) DEFAULT NULL,
  `file_closure_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_applications`
--

INSERT INTO `hospital_applications` (`id`, `app_id`, `applicant_name`, `applicant_email_id`, `applicant_mobile_no`, `applicant_alternate_no`, `applicant_address`, `applicant_nationality`, `technical_qualification`, `hospital_name`, `hospital_address`, `others`, `maternity_beds`, `patient_beds`, `ownership_agreement`, `tax_receipt`, `doc_certificate`, `reg_certificate`, `staff_certificate`, `arrangement_for_checkup`, `nursing_staff_deg_certificate`, `nursing_staff_reg_certificate`, `bio_des_certificate`, `society_noc`, `fire_noc`, `status`, `is_deleted`, `created_at`, `updated_at`, `situation_registration`, `user_id`, `application_type`, `health_officer`, `detail_arrange_sanitary_employee`, `detail_arrange_sanitary_patients`, `storage_arrangements`, `other_business_address`, `accomodation`, `proportion_of_qualified`, `promise`, `no_of_expiry_certificate`, `date_of_expiry_certificate`, `unregisterd_medical`, `file_closure_status`) VALUES
(1, 82, 'Anjolie James', 'hamiq@yopmail.com', '89', '54', 'Labore consequuntur ', 'Ex consequa', 'Provident ex deleni', 'Gage Cross', 'Sit non neque vitae ', 'Optio placeat illo', '145', '321', 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, '2020-12-02 18:28:08', '2020-12-02 18:37:58', 'Est dolore deserunt', 23, 1, 0, 'Perferendis perferen', 'Non cumque itaque ut', 'Quia saepe quibusdam', 'Consectetur sit si', 'Deserunt ullam qui i', NULL, 0, NULL, NULL, NULL, 0),
(2, 83, 'Maxine Rice', 'gizu@yopmail.com', '3216547895', '3216547895', 'Veritatis hic dolor ', 'Aliquid ill', 'Culpa esse autem te', 'Aut et molestiae qua', 'Aut et molestiae qua', 'Duis cillum consequa', '791', '411', 355, 356, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, '2020-12-02 18:37:58', '2020-12-02 18:37:58', 'Dolor id nisi culpa ', 23, 1, 0, 'Voluptates consequun', 'In et quis beatae ni', 'Totam exercitationem', 'Ducimus reiciendis ', 'Deleniti at eius ess', '', 0, NULL, NULL, NULL, 0),
(3, 84, 'Jeenal patel test', 'jeenalpatel@yopmail.com', '7896541235', '7896541235', 'Ullamco iure porro v', 'Atque sit a', 'Cupiditate nisi cons', 'Provident aliquip e', 'Provident aliquip e', 'Aut ipsam aliquid at', '341', '277', 369, 370, 0, 0, 0, 'asdasdas', 0, 0, 0, 0, 0, 81, 0, '2020-12-02 18:42:38', '2020-12-07 17:46:26', 'Quam iusto officia d', 23, 1, 3, 'Nostrum unde enim an', 'Sed odio sit sed vol', 'Quae impedit vitae ', 'Possimus rem conseq', 'Dolores cumque quod ', '0', 1, NULL, NULL, 'No', 0),
(4, 85, 'Ankita patel', 'ankitapatel@yopmail.com', '7896541234', '7896541234', 'Eiusmod culpa conseq', 'Minus ex in', 'Dolore exercitatione', 'Accusantium ad sed q', 'Accusantium ad sed q', 'Aut occaecat perspic', '996', '360', 257, 258, 259, 260, 261, 'Nesciunt alias anim', 0, 0, 0, 0, 262, 0, 0, '2020-12-03 11:32:54', '2020-12-02 18:37:58', 'Qui ullamco qui quae', 23, 1, 0, 'Quae explicabo Cons', 'Ratione optio labor', 'Minus aliquam nihil ', 'Eu ipsum voluptatem', 'Dolores sapiente deb', '0', 1, NULL, NULL, NULL, 0),
(5, 86, 'rashmi khan', 'rashmikhan@yopmail.com', '7896543215', '7896543215', 'Ex aliquam eveniet ', 'Numquam inv', 'Sit sint magna lab', 'Labore incididunt vo', 'Labore incididunt vo', 'Pariatur Ut omnis e', '293', '601', 263, 264, 265, 266, 267, 'Corporis libero lore', 268, 269, 270, 273, 272, 0, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49', 'Consequatur similiq', 23, 1, 0, 'Molestiae est commod', 'Quisquam elit aut l', 'Provident maiores d', 'Ea non qui dolore el', 'Dolores est voluptat', '0', 1, NULL, NULL, NULL, 0),
(6, 89, 'Nick', 'smn101296@gmail.com', '1234567890', '0987654321', 'Juhu', 'Indian', 'qualification ', 'ABC Nursing Home ', 'Mumbai', 'NA', '5', '5', 0, 0, 0, 0, 0, 'On the ground floor ', 0, 0, 0, 0, 0, 0, 0, '2020-12-04 15:48:33', '0000-00-00 00:00:00', 'Home branch mumbai ', 22, 2, 0, '3', '5', 'in the kitchen ', '', 'NA', '', 0, '', '', NULL, 0),
(7, 97, 'Kim Hanson', 'suman.kattimani@aaravsoftware.com', '83', '96', 'Qui suscipit reicien', 'Et ut in do', 'Ut autem ut est ad q', 'Aurelia Hill', 'Tenetur enim dolorem', 'Dolorem doloremque e', '337', '50', 304, 305, 306, 307, 308, 'Laborum est reprehe', 309, 310, 311, 312, 313, 16, 0, '2020-12-04 17:15:59', '2020-12-08 17:14:47', 'Pariatur Aute elit', 22, 1, 3, 'Sequi odio minim min', 'Cupiditate consequun', 'Qui fuga Ipsa reru', 'Veniam consectetur ', 'Facilis quia asperio', 'Eveniet maxime volu', 1, NULL, NULL, NULL, 0),
(8, 100, 'Gil Randall', 'suman.kattimani@aaravsoftware.com', '71', '8', 'Fugiat voluptas impe', 'Vitae inven', 'Voluptatem Ipsam mi', 'Minim error minim al', 'Minim error minim al', 'Suscipit excepturi d', '740', '396', 330, 331, 332, 333, 334, 'Qui eum aliquip faci', 335, 336, 337, 338, 339, 81, 0, '2020-12-05 10:43:10', '2020-12-05 11:09:47', 'Aliquip qui qui quia', 22, 1, 3, 'Ex ea illo id aspern', 'Nesciunt quia quaer', 'Ab perferendis aut a', 'Delectus eiusmod si', 'Ut voluptas id ex vo', 'Quia magna sunt id ', 1, NULL, NULL, NULL, 0),
(10, 105, 'Odysseus Ball', 'suman.kattimani@aaravsoftware.com', '97', '72', 'Esse voluptate vita', 'Magni aliqu', 'Asperiores et cillum', 'Sophia Hoover', 'Reiciendis non culpa', 'Molestiae culpa del', '988', '708', 359, 360, 361, 362, 363, 'Vero omnis cumque ex', 364, 365, 366, 367, 368, 85, 0, '2020-12-07 14:51:17', '2020-12-07 16:20:23', 'Est quia magni amet', 22, 1, 3, 'Molestiae in quae ei', 'Adipisci voluptatem ', 'Minus debitis itaque', 'Delectus totam dolo', 'Ut odio laudantium ', 'Incididunt iure et r', 1, NULL, NULL, 'Yes', 0),
(11, 106, 'Abdul Maldonado', 'suman.kattimani@aaravsoftware.com', '31', '55', 'Voluptates voluptas ', 'Qui cumque ', 'Explicabo In placea', 'Quibusdam possimus ', 'Quibusdam possimus ', 'Voluptates aut sed s', '372', '784', 0, 0, 0, 0, 0, 'Atque voluptate moll', 0, 0, 0, 0, 0, 16, 0, '2020-12-07 15:21:54', '2020-12-07 15:23:00', 'Voluptatem Molestia', 23, 1, 3, 'Fugit sequi consect', 'Ullamco quis laudant', 'Quaerat perferendis ', 'Natus ipsum distinct', 'Minus amet ut repel', 'Aut fugiat quo sit e', 1, NULL, NULL, 'No', 0),
(12, 108, 'Brittany Cobb', 'suman.kattimani@aaravsoftware.com', '38', '65', 'Reprehenderit ut am', 'Dolorem sun', 'Modi quo pariatur I', 'Kirsten Shelton', 'Eum consectetur qua', 'Qui nesciunt simili', '38', '102', 0, 0, 0, 0, 0, 'Incidunt placeat e', 0, 0, 0, 0, 0, 85, 0, '2020-12-07 17:41:42', '2020-12-07 17:48:11', 'Eos et tempore bla', 22, 1, 3, 'Qui ex sint non quis', 'Esse sunt qui id eu', 'Obcaecati omnis poss', '', 'Ut consequat Eiusmo', 'Earum expedita asper', 0, NULL, NULL, 'Yes', 0),
(13, 109, 'Hello world', 'helloworld@yopmail.com', '7896541235', '7896541235', 'Doloremque molestiae', 'Temporibus ', 'Consectetur molesti', 'Nisi exercitation ex', 'Nisi exercitation ex', 'Voluptatem molestiae', '989', '888', 376, 377, 378, 379, 380, 'Aut alias commodi nu', 381, 382, 383, 384, 385, 85, 0, '2020-12-07 17:56:52', '2020-12-07 18:02:02', 'Nam aliqua Autem al', 23, 1, 3, 'Labore sunt adipisic', 'Maxime quaerat minus', 'Asperiores unde inci', 'Consequatur deserun', 'Aut laudantium natu', 'Reprehenderit proid', 1, NULL, NULL, 'Yes', 0),
(14, 110, 'Kylan Adkins', 'lynyvo@yopmail.com', '7896541235', '7896541235', 'Aspernatur tempore ', 'Natus elit ', 'Suscipit dolorum vel', 'Libby Marsh', 'Ipsa quibusdam Nam ', 'Fugiat dolor veniam', '827', '734', 0, 0, 0, 0, 0, 'Sequi maiores eiusmo', 0, 0, 0, 0, 0, 81, 0, '2020-12-07 18:49:45', '2020-12-07 18:51:08', 'Architecto suscipit ', 23, 2, 3, 'Iure esse fugiat f', 'Animi iure nemo et ', 'Quas enim sed incidi', 'Exercitation sunt i', 'Dolore earum vel des', 'Magni est quia beat', 1, NULL, NULL, 'No', 0),
(15, 111, 'Data testing', 'testingtesting@yopmail.com', '7896541235', '7896541235', 'Autem vel totam quia', 'Ea non non ', 'Nihil ea omnis eos v', 'Suscipit debitis dui', 'Suscipit debitis dui', 'In aut placeat nisi', '588', '453', 386, 387, 388, 389, 390, 'Iusto sit amet occ', 391, 392, 393, 394, 395, 85, 0, '2020-12-07 19:25:29', '2020-12-07 20:09:55', 'Culpa laboris offic', 23, 2, 3, 'Sit corporis est om', 'Animi et culpa aut ', 'Quis molestias fuga', 'Alias non iste tempo', 'Ad voluptatem cupidi', 'Nobis nesciunt vel ', 1, '123456', '2020-6-13', 'Yes', 0),
(16, 123, 'Alice', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'juhu', 'indian', 'mbbs', 'aLICE CARE', 'Andheri west', 'NA', '3', '7', 0, 0, 0, 0, 0, 'IN THE OPD AT 1ST FLOOR ', 0, 0, 0, 0, 0, 81, 0, '2020-12-08 17:51:16', '2020-12-08 17:57:32', 'andheri west', 24, 2, 3, '1', '3', 'NA', '', 'na', '', 1, 'MH/THN/MBMC/2017/30 ', '2020-11-08', 'No', 0),
(17, 124, 'Harry ', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'juhu', 'indian', 'mbbs', 'Harry care ', 'andheri west ', 'fhfjfj', 'dfhfh', 'fghfdg', 0, 0, 0, 0, 0, 'bdfgnfgnf', 0, 0, 0, 0, 0, 85, 0, '2020-12-08 18:13:15', '2020-12-08 18:24:31', 'na', 22, 1, 3, 'fh', 'hf', 'ghfh', '', 'gfhfdhgdf', '', 0, NULL, NULL, 'No', 0),
(18, 125, 'Dhyey', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'juhu', 'indian', 'mbbs', 'Dhyey care', 'andheri west ', 'na', '0', '10', 0, 0, 0, 0, 0, 'na', 0, 0, 0, 0, 0, 85, 0, '2020-12-08 18:33:55', '2020-12-08 18:40:19', 'hgulkjlk', 22, 1, 3, '2', '2', '', '', 'fds', 'fg', 0, NULL, NULL, 'No', 0),
(19, 126, 'Raphael Fulton', 'kixufa@yopmail.com', '98', '43', 'Quam consectetur off', 'Ut ut sit o', 'Molestiae voluptatum', 'Kalia Newton', 'Non quos maxime repe', 'Voluptas minima a po', '250', '675', 0, 0, 0, 0, 0, 'Modi temporibus dele', 0, 0, 0, 0, 0, 0, 0, '2020-12-09 15:06:58', '0000-00-00 00:00:00', 'Cillum velit sunt eu', 22, 1, 0, 'Veritatis et repudia', 'Eum est ut fugiat ', 'Omnis voluptatem est', '', 'Magna aut iste dolor', 'Dolor eos recusandae', 0, NULL, NULL, 'Yes', 0),
(20, 127, 'Stephen Salinas', 'razuve@yopmail.com', '36', '43', 'Ratione non mollit i', 'Culpa id ni', 'Deserunt esse rerum ', 'Christopher Powers', 'Qui assumenda irure ', 'Ad amet consequatur', '808', '658', 0, 0, 0, 0, 0, 'Ad deserunt sint imp', 0, 0, 0, 0, 0, 0, 0, '2020-12-09 15:07:32', '0000-00-00 00:00:00', 'Irure ea eu eos exer', 22, 1, 0, 'Facere qui minima la', 'Ut consequatur rem ', 'Aliquam nostrum in q', 'Rem ad ullam in even', 'Ut quasi dolore corr', 'Corrupti quos quae ', 1, NULL, NULL, 'Yes', 0),
(21, 128, 'Kibo Drake', 'qyqoky@yopmail.com', '98', '75', 'Ea est dolorem minim', 'In consequa', 'Voluptate aliquip es', 'Logan Rush', 'Ut beatae dolorem cu', 'Amet voluptate id c', '229', '512', 0, 0, 0, 0, 0, 'Alias omnis commodi ', 0, 0, 0, 0, 0, 0, 0, '2020-12-09 16:26:10', '0000-00-00 00:00:00', 'Ipsam sapiente incid', 22, 1, 0, 'Dolores id minim ali', 'Debitis irure et off', 'Provide adequate storage for raw materials. Provide adequate space for food being prepared. Provide adequate space food awaiting service. Provide adequate storage for equipment, utensils, crockery and cutlery. Be efficient and effective in terms of movement of staff, equipment, materials and waste management system in place Food, Oil & Grease (F.O.G) Provide an area for checking in stock. Janitorial store for kitchen, with janitorial sink in place and chemical store.', 'Incidunt rerum veli', 'Dignissimos in porro', 'Ea officia id dolore', 1, NULL, NULL, 'Yes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_fee_charges`
--

CREATE TABLE `hospital_fee_charges` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `sr_no` int(11) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `charges` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_fee_charges`
--

INSERT INTO `hospital_fee_charges` (`id`, `app_id`, `sr_no`, `service`, `charges`) VALUES
(1, 1, 990, 'Est quo ut velit de', 57),
(2, 1, 663, 'Sunt quis accusamus', 3),
(3, 1, 399, 'Ab dicta illum volu', 69),
(4, 1, 169, 'Aliqua Aliquam dolo', 87),
(57, 4, 812, 'In voluptatem Aut a', 51),
(58, 4, 179, 'Assumenda non labore', 72),
(59, 4, 71, 'Cumque dolor sunt a', 12),
(60, 4, 980, 'Dolores voluptatem s', 69),
(69, 5, 926, 'A dignissimos perspi', 12),
(70, 5, 19, 'Mollit labore except', 77),
(71, 5, 634, 'Modi recusandae A s', 66),
(72, 5, 722, 'Praesentium omnis di', 63),
(73, 6, 0, '', 0),
(74, 7, 919, 'Aut sint ullamco mi', 12),
(76, 8, 277, 'Et tenetur qui assum', 55),
(77, 9, 0, '', 0),
(82, 2, 184, 'Nisi quae consectetu', 12),
(83, 2, 89, 'Laboris repudiandae ', 51),
(84, 2, 47, 'Eiusmod velit a fug', 58),
(85, 2, 196, 'Et voluptatibus nequ', 53),
(86, 10, 578, 'Ea ad repudiandae au', 38),
(87, 3, 927, 'Quia libero sit dol', 13),
(88, 3, 404, 'Doloremque quae et f', 73),
(89, 3, 317, 'Laudantium dolorum ', 2),
(90, 3, 911, 'Impedit doloribus d', 8),
(92, 12, 823, 'Ex nisi ut laudantiu', 65),
(97, 13, 317, 'Aut quidem est ea m', 55),
(98, 13, 389, 'Voluptatem neque imp', 69),
(99, 13, 544, 'Veniam sunt distinc', 5),
(100, 13, 357, 'Culpa est ipsa temp', 39),
(101, 14, 893, 'Laboris praesentium ', 79),
(103, 15, 161, 'Consequat Quisquam ', 45),
(105, 11, 318, 'Laborum eaque et eli', 17),
(108, 16, 1, 'OPD Charges ', 100),
(109, 17, 34, 'redgd', 3535353),
(110, 18, 1, 'dfgds', 100),
(111, 19, 187, 'Ducimus voluptatem ', 33),
(112, 20, 819, 'Non sed tempora ex s', 70),
(113, 21, 861, 'Ut quam nulla impedi', 42);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_florespace_for_bedrooms`
--

CREATE TABLE `hospital_florespace_for_bedrooms` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `floor_number` varchar(255) DEFAULT NULL,
  `total_bedrooms_on_flore` int(11) DEFAULT NULL,
  `total_number_of_beds` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_florespace_for_bedrooms`
--

INSERT INTO `hospital_florespace_for_bedrooms` (`id`, `app_id`, `floor_number`, `total_bedrooms_on_flore`, `total_number_of_beds`) VALUES
(1, 1, '505', 3, 821),
(2, 1, '852', 54, 523),
(3, 1, '507', 98, 871),
(4, 1, '101', 71, 117),
(65, 4, '558', 22, 739),
(66, 4, '315', 96, 220),
(67, 4, '299', 63, 484),
(68, 4, '516', 68, 721),
(69, 4, '642', 58, 14),
(80, 5, '274', 77, 585),
(81, 5, '520', 57, 641),
(82, 5, '693', 15, 233),
(83, 5, '731', 76, 884),
(84, 5, '804', 95, 875),
(85, 6, '1', 3, 6),
(86, 6, '2', 3, 6),
(87, 7, '234', 71, 629),
(89, 8, '705', 39, 413),
(90, 9, '', 0, 0),
(95, 2, '863', 19, 492),
(96, 2, '55', 47, 721),
(97, 2, '505', 61, 883),
(98, 2, '683', 76, 985),
(99, 10, '424', 3, 5),
(100, 3, '632', 81, 218),
(101, 3, '289', 93, 790),
(102, 3, '838', 47, 913),
(103, 3, '89', 4, 43),
(105, 12, '510', 89, 30),
(110, 13, '360', 81, 95),
(111, 13, '303', 37, 885),
(112, 13, '116', 59, 305),
(113, 13, '537', 19, 44),
(114, 14, '354', 92, 373),
(120, 15, '954', 36, 124),
(121, 15, '949', 92, 368),
(122, 15, '45', 41, 848),
(123, 15, '267', 58, 650),
(124, 15, '337', 28, 368),
(126, 11, '139', 19, 382),
(129, 16, '1', 3, 10),
(130, 17, '2', 2, 10),
(131, 18, '1', 2, 10),
(132, 19, '97', 97, 733),
(133, 20, '761', 100, 584),
(134, 21, '563', 75, 687);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_florespace_for_kitchen`
--

CREATE TABLE `hospital_florespace_for_kitchen` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `floor_name` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `user` int(11) DEFAULT NULL COMMENT '1 = patients , 2 = employee'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_florespace_for_kitchen`
--

INSERT INTO `hospital_florespace_for_kitchen` (`id`, `app_id`, `room_name`, `floor_name`, `area`, `user`) VALUES
(1, 1, 'Kristen Owens', 'Rina Cruz', '17', 2),
(2, 1, 'Ronan Woodward', 'Rina Bennett', '75', 1),
(3, 1, 'Zahir Gonzales', 'Clare Carson', '59', 2),
(4, 1, 'Leah Hurst', 'Cathleen Good', '97', 1),
(65, 4, 'Ori Kent', 'Ori Kent', '85', 2),
(66, 4, 'Forrest Combs', 'Forrest Combs', '63', 1),
(67, 4, 'Nayda Workman', 'Nayda Workman', '22', 1),
(68, 4, 'Calista Schultz', 'Calista Schultz', '54', 1),
(69, 4, 'Xavier Allison', 'Xavier Allison', '93', 2),
(80, 5, 'Yardley Keith', 'Yardley Keith', '16', 1),
(81, 5, 'Liberty Dennis', 'Liberty Dennis', '68', 2),
(82, 5, 'Brynn Bryan', 'Brynn Bryan', '99', 2),
(83, 5, 'Fuller Norton', 'Fuller Norton', '74', 2),
(84, 5, 'Faith Keller', 'Faith Keller', '26', 2),
(85, 6, 'kitchen', '1st floor ', '270', 2),
(86, 6, 'servant room', '1st floor ', '270', 2),
(87, 7, 'Reece Jimenez', 'Daniel Alvarez', '89', 2),
(89, 8, 'Kaye Todd', 'Kaye Todd', '28', 1),
(90, 9, '', '', '', 0),
(95, 2, 'Carl Mckinney', 'Carl Mckinney', '53', 2),
(96, 2, 'Cyrus Schneider', 'Cyrus Schneider', '38', 2),
(97, 2, 'Yoshi Lloyd', 'Yoshi Lloyd', '18', 1),
(98, 2, 'Beatrice Parsons', 'Beatrice Parsons', '75', 2),
(99, 10, 'Shelby Booker', 'Kadeem Mueller', '17', 1),
(100, 3, 'Regina Valentine', 'Regina Valentine', '30', 1),
(101, 3, 'Todd Mcclain', 'Todd Mcclain', '59', 1),
(102, 3, 'Clare Cote', 'Clare Cote', '85', 1),
(103, 3, 'Bertha Phelps', 'Bertha Phelps', '14', 1),
(105, 12, 'Suki Miranda', 'Gemma Terry', '49', 1),
(110, 13, 'Vladimir Maxwell', 'Vladimir Maxwell', '54', 2),
(111, 13, 'Raja Burke', 'Raja Burke', '1', 2),
(112, 13, 'Barry Noble', 'Barry Noble', '7', 2),
(113, 13, 'Malachi Walsh', 'Malachi Walsh', '35', 2),
(114, 14, 'Rhona Powell', 'Slade Edwards', '30', 1),
(121, 15, 'Jerry Church', 'Jerry Church', '96', 2),
(122, 15, 'Edward Bowman', 'Edward Bowman', '23', 1),
(123, 15, 'Christopher Medina', 'Christopher Medina', '77', 1),
(124, 15, 'Uriah Martinez', 'Uriah Martinez', '68', 2),
(125, 15, 'Rajah Munoz', 'Rajah Munoz', '36', 1),
(126, 15, 'Maryam Roy', 'Maryam Roy', '10', 2),
(128, 11, 'Yoko Rosales', 'Yoko Rosales', '31', 2),
(131, 16, 'KITCHEN', 'KITCHEN', '100', 2),
(132, 17, 'cvnvcn', 'vcnvn', '534643', 1),
(133, 18, 'a', '1', '100', 2),
(134, 19, 'Giselle Gregory', '', '', 2),
(135, 20, 'Vaughan Morrow', '', '', 2),
(136, 21, 'Maite Shannon', 'Maggie Bruce', '11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_inspection_form`
--

CREATE TABLE `hospital_inspection_form` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `sub_dept_id` int(11) NOT NULL,
  `doc_degree_certificate` int(11) NOT NULL,
  `doc_reg_mmc` int(11) NOT NULL,
  `bio_medical_valid_date` date NOT NULL,
  `mpcb_certificate_valid_date` date NOT NULL,
  `agreement_copy` int(11) NOT NULL,
  `tax_recipes` int(11) NOT NULL,
  `nursing_certificate` int(11) NOT NULL,
  `noc_from_society` int(11) NOT NULL,
  `noc_from_town_planning_mbmc` int(11) NOT NULL,
  `no_of_beds` int(11) NOT NULL,
  `no_of_toilets` int(11) NOT NULL,
  `noc_from_fire_dept` int(11) NOT NULL,
  `general_observation` int(11) NOT NULL,
  `labour_room_availability` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_inspection_form`
--

INSERT INTO `hospital_inspection_form` (`id`, `app_id`, `sub_dept_id`, `doc_degree_certificate`, `doc_reg_mmc`, `bio_medical_valid_date`, `mpcb_certificate_valid_date`, `agreement_copy`, `tax_recipes`, `nursing_certificate`, `noc_from_society`, `noc_from_town_planning_mbmc`, `no_of_beds`, `no_of_toilets`, `noc_from_fire_dept`, `general_observation`, `labour_room_availability`, `created_at`, `updated_at`, `created_by`, `approved_by`, `status`) VALUES
(9, 104, 3, 1, 1, '2020-12-10', '0000-00-00', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-05 12:32:02', NULL, 25, 0, 1),
(10, 105, 1, 1, 1, '2020-12-07', '2020-12-07', 1, 1, 1, 1, 1, 5, 3, 1, 0, 0, '2020-12-07 10:08:51', NULL, 25, 0, 1),
(11, 106, 1, 1, 0, '2020-12-15', '2020-12-29', 0, 1, 0, 0, 1, 12, 13, 0, 0, 1, '2020-12-07 10:12:28', NULL, 25, 0, 1),
(13, 107, 2, 1, 0, '2020-12-07', '0000-00-00', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-07 11:55:32', NULL, 25, 0, 1),
(14, 108, 1, 1, 1, '2020-12-07', '2020-12-07', 1, 1, 1, 0, 0, 5, 3, 0, 0, 0, '2020-12-07 12:13:29', NULL, 25, 0, 1),
(16, 84, 1, 0, 0, '2020-12-08', '2020-12-25', 1, 0, 1, 0, 0, 12, 2, 1, 0, 0, '2020-12-07 12:18:30', NULL, 25, 0, 1),
(17, 109, 1, 1, 0, '2020-12-14', '2020-12-31', 0, 1, 0, 0, 1, 100, 5, 0, 0, 1, '2020-12-07 12:29:01', NULL, 25, 0, 1),
(18, 110, 1, 1, 1, '2020-12-09', '2020-12-23', 0, 0, 1, 0, 0, 123, 5, 1, 0, 0, '2020-12-07 13:21:00', NULL, 25, 0, 1),
(19, 111, 1, 1, 0, '2020-12-08', '2020-12-23', 0, 1, 0, 0, 1, 45, 8, 0, 0, 1, '2020-12-07 14:02:53', NULL, 25, 0, 1),
(20, 112, 2, 1, 1, '2020-12-16', '0000-00-00', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-07 15:10:53', NULL, 25, 0, 1),
(22, 115, 3, 1, 1, '2020-12-17', '0000-00-00', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 04:28:46', NULL, 25, 0, 1),
(23, 116, 2, 1, 0, '2020-12-08', '0000-00-00', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 07:14:46', NULL, 25, 0, 1),
(26, 117, 2, 1, 0, '2020-12-09', '0000-00-00', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 07:41:17', NULL, 25, 0, 1),
(27, 118, 2, 1, 0, '2020-12-08', '0000-00-00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 07:57:06', NULL, 25, 0, 1),
(28, 114, 2, 0, 1, '2020-12-17', '0000-00-00', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 08:20:58', NULL, 25, 0, 1),
(29, 119, 2, 1, 0, '2020-12-08', '0000-00-00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 09:17:51', NULL, 25, 0, 1),
(30, 120, 3, 0, 0, '2020-12-08', '0000-00-00', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 10:24:08', NULL, 25, 0, 1),
(31, 121, 3, 1, 0, '2020-12-08', '0000-00-00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 11:12:41', NULL, 25, 0, 1),
(32, 122, 3, 1, 0, '2020-12-08', '0000-00-00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 11:30:44', NULL, 25, 0, 1),
(33, 113, 2, 1, 1, '2020-12-23', '0000-00-00', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2020-12-08 11:54:23', NULL, 25, 0, 1),
(34, 123, 1, 1, 0, '2020-12-08', '2020-12-08', 0, 0, 1, 0, 1, 10, 4, 1, 0, 0, '2020-12-08 12:27:19', NULL, 25, 0, 1),
(35, 124, 1, 1, 0, '2020-12-07', '2020-12-06', 0, 0, 0, 0, 0, 10, 3, 0, 0, 0, '2020-12-08 12:44:31', NULL, 25, 0, 1),
(36, 125, 1, 1, 0, '2020-04-01', '2020-04-01', 1, 0, 0, 1, 0, 10, 4, 0, 0, 0, '2020-12-08 13:07:38', NULL, 25, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_midwife`
--

CREATE TABLE `hospital_midwife` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_midwife`
--

INSERT INTO `hospital_midwife` (`id`, `app_id`, `name`, `age`, `qualification`) VALUES
(1, 1, 'Jamalia Howe', 48, 'Corrupti aut quae v'),
(2, 1, 'Yvonne Savage', 11, 'Libero amet ut duci'),
(3, 1, 'Roanna Oconnor', 96, 'Amet ut sint magnam'),
(4, 1, 'Bo Blevins', 30, 'Qui temporibus nihil'),
(65, 4, 'Jana Eaton', 44, 'Quam Nam facilis rem'),
(66, 4, 'India Potter', 77, 'Enim sed ullamco et '),
(67, 4, 'Kadeem Benson', 51, 'Ea vero qui expedita'),
(68, 4, 'Gary Gilbert', 89, 'Fugiat est vitae co'),
(69, 4, 'Shay Weeks', 94, 'Veniam voluptas est'),
(80, 5, 'Celeste Austin', 41, 'Adipisci ipsa ullam'),
(81, 5, 'Guinevere Blankenship', 43, 'Quaerat earum molest'),
(82, 5, 'Todd Atkinson', 84, 'In consequuntur magn'),
(83, 5, 'Jonah Gilbert', 46, 'Vitae omnis sit anim'),
(84, 5, 'Janna Velazquez', 62, 'Quos est odit ea dol'),
(85, 6, '', 0, ''),
(86, 7, 'Quin Yang', 82, 'Nihil repudiandae at'),
(88, 8, 'Beau Vincent', 69, 'Magna consectetur f'),
(89, 9, '', 0, ''),
(94, 2, 'Cassandra Williams', 43, 'Aliquam eligendi pro'),
(95, 2, 'Cecilia Welch', 42, 'Maxime eveniet ut c'),
(96, 2, 'Marvin Miranda', 32, 'Asperiores eligendi '),
(97, 2, 'Quincy Little', 48, 'Est est deleniti to'),
(98, 10, '', 0, ''),
(99, 3, 'Hop Vance', 1, 'Non et sit tempora '),
(100, 3, 'Zephr Harper', 85, 'Ut quod reprehenderi'),
(101, 3, 'Sawyer Melendez', 67, 'Neque aperiam alias '),
(102, 3, 'Dana Mcmahon', 24, 'Do dolores Nam cumqu'),
(104, 12, 'Kitra Clarke', 95, 'Qui ea aut ab magnam'),
(109, 13, 'Ori Foley', 83, 'Sapiente et sapiente'),
(110, 13, 'Tallulah Fisher', 39, 'Adipisicing et facil'),
(111, 13, 'Amena Fields', 62, 'Nulla incididunt par'),
(112, 13, 'Portia Chandler', 74, 'Officia occaecat asp'),
(113, 14, 'Guy Castro', 53, 'Eius libero recusand'),
(119, 15, 'Marny Puckett', 10, 'Neque consequatur d'),
(120, 15, 'Ifeoma Cooley', 91, 'In voluptatem Facil'),
(121, 15, 'Owen Manning', 92, 'Omnis est magni vero'),
(122, 15, 'Quamar Knox', 13, 'Odio aut aute ut com'),
(123, 15, 'Asher Henry', 44, 'Autem enim soluta vo'),
(125, 11, 'Yoko Rollins', 29, 'Nemo sit eos porro'),
(128, 16, '', 0, ''),
(129, 17, '', 0, ''),
(130, 18, '', 0, ''),
(131, 19, 'Lillith Stafford', 50, 'Eos ea labore volup'),
(132, 20, 'Melyssa Olson', 82, 'Culpa cupidatat hic'),
(133, 21, 'Hyacinth Castillo', 61, 'Et in consequatur ad');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_staff_details`
--

CREATE TABLE `hospital_staff_details` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `design_id` int(11) NOT NULL,
  `staff_accommodation` varchar(255) DEFAULT NULL,
  `qual_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_staff_details`
--

INSERT INTO `hospital_staff_details` (`id`, `app_id`, `staff_name`, `age`, `design_id`, `staff_accommodation`, `qual_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 'Arthur Hopper', '53', 5, NULL, 2, 1, 0, '2020-12-02 18:28:08', '0000-00-00 00:00:00'),
(2, 1, 'Nathan Barlow', '33', 2, NULL, 1, 1, 0, '2020-12-02 18:28:08', '0000-00-00 00:00:00'),
(3, 1, 'Berk Hall', '74', 6, NULL, 3, 1, 0, '2020-12-02 18:28:08', '0000-00-00 00:00:00'),
(4, 1, 'Galvin Kim', '25', 4, NULL, 4, 1, 0, '2020-12-02 18:28:08', '0000-00-00 00:00:00'),
(65, 4, 'Alexander Quinn', '45', 1, NULL, 4, 1, 0, '2020-12-03 15:14:37', '0000-00-00 00:00:00'),
(66, 4, 'Clio Sanford', '58', 2, NULL, 4, 1, 0, '2020-12-03 15:14:37', '0000-00-00 00:00:00'),
(67, 4, 'Ulric Suarez', '74', 6, NULL, 2, 1, 0, '2020-12-03 15:14:37', '0000-00-00 00:00:00'),
(68, 4, 'Barbara Soto', '18', 4, NULL, 2, 1, 0, '2020-12-03 15:14:37', '0000-00-00 00:00:00'),
(69, 4, 'Amy Lloyd', '37', 6, NULL, 3, 1, 0, '2020-12-03 15:14:37', '0000-00-00 00:00:00'),
(80, 5, 'Tamekah Ball', '42', 6, NULL, 2, 1, 0, '2020-12-03 15:48:24', '0000-00-00 00:00:00'),
(81, 5, 'Graham Patel', '93', 6, NULL, 3, 1, 0, '2020-12-03 15:48:24', '0000-00-00 00:00:00'),
(82, 5, 'Pamela Rivas', '39', 5, NULL, 1, 1, 0, '2020-12-03 15:48:24', '0000-00-00 00:00:00'),
(83, 5, 'Lacey Compton', '28', 1, NULL, 1, 1, 0, '2020-12-03 15:48:24', '0000-00-00 00:00:00'),
(84, 5, 'Kasper Bush', '84', 3, NULL, 3, 1, 0, '2020-12-03 15:48:24', '0000-00-00 00:00:00'),
(85, 6, 'Bob', '45', 1, NULL, 1, 1, 0, '2020-12-04 15:48:33', '0000-00-00 00:00:00'),
(86, 6, 'alice', '45', 4, NULL, 2, 1, 0, '2020-12-04 15:48:33', '0000-00-00 00:00:00'),
(87, 6, 'harry', '78', 3, NULL, 3, 1, 0, '2020-12-04 15:48:33', '0000-00-00 00:00:00'),
(88, 7, 'Allegra Deleon', '22', 4, NULL, 3, 1, 0, '2020-12-04 17:15:59', '0000-00-00 00:00:00'),
(90, 8, 'Halee Sharpe', '61', 3, NULL, 1, 1, 0, '2020-12-05 10:43:39', '0000-00-00 00:00:00'),
(91, 9, '', '', 0, NULL, 0, 1, 0, '2020-12-05 13:22:49', '0000-00-00 00:00:00'),
(96, 2, 'Marvin Chen', '46', 1, NULL, 3, 1, 0, '2020-12-05 19:59:31', '0000-00-00 00:00:00'),
(97, 2, 'Rosalyn Holloway', '74', 2, NULL, 3, 1, 0, '2020-12-05 19:59:31', '0000-00-00 00:00:00'),
(98, 2, 'Aaron Carpenter', '73', 2, NULL, 2, 1, 0, '2020-12-05 19:59:31', '0000-00-00 00:00:00'),
(99, 2, 'Fay Foley', '98', 1, NULL, 1, 1, 0, '2020-12-05 19:59:31', '0000-00-00 00:00:00'),
(100, 10, 'Justina Hancock', '9', 4, NULL, 2, 1, 0, '2020-12-07 14:51:18', '0000-00-00 00:00:00'),
(101, 3, 'Abra Montgomery', '95', 1, NULL, 3, 1, 0, '2020-12-07 15:19:12', '0000-00-00 00:00:00'),
(102, 3, 'Carlos Garcia', '20', 6, NULL, 1, 1, 0, '2020-12-07 15:19:12', '0000-00-00 00:00:00'),
(103, 3, 'Hasad Rivera', '78', 2, NULL, 4, 1, 0, '2020-12-07 15:19:12', '0000-00-00 00:00:00'),
(104, 3, 'Leslie Lawson', '27', 6, NULL, 3, 1, 0, '2020-12-07 15:19:12', '0000-00-00 00:00:00'),
(106, 12, 'Kylynn Hebert', '99', 1, NULL, 2, 1, 0, '2020-12-07 17:41:42', '0000-00-00 00:00:00'),
(111, 13, 'Micah Witt', '16', 1, NULL, 4, 1, 0, '2020-12-07 17:57:25', '0000-00-00 00:00:00'),
(112, 13, 'Paula Oliver', '85', 3, NULL, 3, 1, 0, '2020-12-07 17:57:25', '0000-00-00 00:00:00'),
(113, 13, 'Garrison Holmes', '41', 2, NULL, 1, 1, 0, '2020-12-07 17:57:25', '0000-00-00 00:00:00'),
(114, 13, 'Kimberley Hooper', '31', 1, NULL, 4, 1, 0, '2020-12-07 17:57:25', '0000-00-00 00:00:00'),
(115, 14, 'Mark Ramos', '56', 6, NULL, 3, 1, 0, '2020-12-07 18:49:45', '0000-00-00 00:00:00'),
(121, 15, 'Oprah Holden', '61', 5, NULL, 1, 1, 0, '2020-12-07 19:37:12', '0000-00-00 00:00:00'),
(122, 15, 'Rafael Flynn', '87', 4, NULL, 3, 1, 0, '2020-12-07 19:37:12', '0000-00-00 00:00:00'),
(123, 15, 'Rhea Prince', '76', 3, NULL, 4, 1, 0, '2020-12-07 19:37:12', '0000-00-00 00:00:00'),
(124, 15, 'Ruby Randolph', '78', 2, NULL, 3, 1, 0, '2020-12-07 19:37:12', '0000-00-00 00:00:00'),
(125, 15, 'Anjolie Wright', '5', 1, NULL, 4, 1, 0, '2020-12-07 19:37:12', '0000-00-00 00:00:00'),
(127, 11, 'Linus Alvarez', '25', 5, NULL, 3, 1, 0, '2020-12-08 17:16:27', '0000-00-00 00:00:00'),
(130, 16, 'Bob', '23', 1, NULL, 1, 1, 0, '2020-12-08 17:54:10', '0000-00-00 00:00:00'),
(131, 17, 'hghfg', '44', 1, NULL, 1, 1, 0, '2020-12-08 18:13:15', '0000-00-00 00:00:00'),
(132, 18, 'dfd', '34', 1, NULL, 1, 1, 0, '2020-12-08 18:33:55', '0000-00-00 00:00:00'),
(133, 19, 'Derek Kane', '85', 4, NULL, 3, 1, 0, '2020-12-09 15:06:58', '0000-00-00 00:00:00'),
(134, 20, 'Kenyon Serrano', '36', 2, NULL, 3, 1, 0, '2020-12-09 15:07:32', '0000-00-00 00:00:00'),
(135, 21, 'Leah Vinson', '41', 4, NULL, 4, 1, 0, '2020-12-09 16:26:10', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_supervision`
--

CREATE TABLE `hospital_supervision` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_supervision`
--

INSERT INTO `hospital_supervision` (`id`, `app_id`, `name`, `age`, `qualification`) VALUES
(1, 1, 'Gillian Jensen', 65, 'Inventore reprehende'),
(2, 1, 'Ariel Guerra', 46, 'Id nulla rerum aliqu'),
(3, 1, 'Jessamine Stark', 26, 'Sit veniam non vel'),
(4, 1, 'Gay Buckner', 21, 'Quo minus architecto'),
(65, 4, 'Jana Eaton', 44, 'Quam Nam facilis rem'),
(66, 4, 'India Potter', 77, 'Enim sed ullamco et '),
(67, 4, 'Kadeem Benson', 51, 'Ea vero qui expedita'),
(68, 4, 'Gary Gilbert', 89, 'Fugiat est vitae co'),
(69, 4, 'Shay Weeks', 94, 'Veniam voluptas est'),
(80, 5, 'Celeste Austin', 41, 'Adipisci ipsa ullam'),
(81, 5, 'Guinevere Blankenship', 43, 'Quaerat earum molest'),
(82, 5, 'Todd Atkinson', 84, 'In consequuntur magn'),
(83, 5, 'Jonah Gilbert', 46, 'Vitae omnis sit anim'),
(84, 5, 'Janna Velazquez', 62, 'Quos est odit ea dol'),
(85, 6, '', 0, ''),
(86, 7, '', 0, ''),
(88, 8, 'Beau Vincent', 69, 'Magna consectetur f'),
(89, 9, '', 0, ''),
(94, 2, 'Cassandra Williams', 43, 'Aliquam eligendi pro'),
(95, 2, 'Cecilia Welch', 42, 'Maxime eveniet ut c'),
(96, 2, 'Marvin Miranda', 32, 'Asperiores eligendi '),
(97, 2, 'Quincy Little', 48, 'Est est deleniti to'),
(98, 10, 'Blythe Ingram', 79, 'Dolor quibusdam ut d'),
(99, 3, 'Hop Vance', 1, 'Non et sit tempora '),
(100, 3, 'Zephr Harper', 85, 'Ut quod reprehenderi'),
(101, 3, 'Sawyer Melendez', 67, 'Neque aperiam alias '),
(102, 3, 'Dana Mcmahon', 24, 'Do dolores Nam cumqu'),
(104, 12, 'Quinlan Richmond', 90, 'Minima est delectus'),
(109, 13, 'Ori Foley', 83, 'Sapiente et sapiente'),
(110, 13, 'Tallulah Fisher', 39, 'Adipisicing et facil'),
(111, 13, 'Amena Fields', 62, 'Nulla incididunt par'),
(112, 13, 'Portia Chandler', 74, 'Officia occaecat asp'),
(113, 14, 'Clark Faulkner', 51, 'Obcaecati aut aspern'),
(119, 15, 'Marny Puckett', 10, 'Neque consequatur d'),
(120, 15, 'Ifeoma Cooley', 91, 'In voluptatem Facil'),
(121, 15, 'Owen Manning', 92, 'Omnis est magni vero'),
(122, 15, 'Quamar Knox', 13, 'Odio aut aute ut com'),
(123, 15, 'Asher Henry', 44, 'Autem enim soluta vo'),
(125, 11, 'Yoko Rollins', 29, 'Nemo sit eos porro'),
(128, 16, '', 0, ''),
(129, 17, '', 0, ''),
(130, 18, '', 0, ''),
(131, 19, 'Megan Mcintyre', 25, 'Impedit molestiae d'),
(132, 20, 'Omar Robertson', 86, 'Nihil alias laborios'),
(133, 21, 'Amal Dejesus', 50, 'In corrupti eos do');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_surgeon_information`
--

CREATE TABLE `hospital_surgeon_information` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `visiting` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hospital_surgeon_information`
--

INSERT INTO `hospital_surgeon_information` (`id`, `app_id`, `name`, `age`, `qualification`, `visiting`) VALUES
(1, 1, 'Abigail Newton', 88, 'Sunt eius nobis vol', 'Blanditiis quas eius'),
(2, 1, 'Philip Bray', 35, 'Vero enim explicabo', 'Veniam a numquam su'),
(3, 1, 'Hope Richard', 93, 'Eum incididunt volup', 'Veniam eveniet ad '),
(4, 1, 'Glenna Delacruz', 61, 'Tempora magna quia d', 'Velit deserunt ut re'),
(65, 4, 'Berk Farmer', 10, 'Est veniam aliquip ', 'Maiores aliquam ipsa'),
(66, 4, 'Kadeem Duran', 79, 'Dolor id blanditiis', 'Quisquam vero illo d'),
(67, 4, 'Gavin Horn', 81, 'Aut non dolor eius o', 'Quaerat rerum fugiat'),
(68, 4, 'Jaime Stuart', 71, 'In rerum animi quia', 'Illo sint ipsum vol'),
(69, 4, 'Mufutau Delacruz', 95, 'Laborum Autem disti', 'Mollitia magnam lore'),
(72, 5, 'Fuller Maynard', 87, 'Nulla porro tempore', 'Blanditiis ea earum '),
(73, 6, 'nick', 45, 'mbbs', ''),
(74, 7, 'Hakeem Garrison', 62, 'Quasi veritatis in v', 'Est doloremque sed '),
(76, 8, 'Clarke Snider', 14, 'Minim rerum ea dolor', 'Cillum fuga Nemo fu'),
(77, 9, '', 0, '', ''),
(82, 2, 'Mannix Woodard', 99, 'Rerum eos et ex quo', 'Ullamco consequatur'),
(83, 2, 'Kaden Harrison', 5, 'Molestias beatae eiu', 'Excepteur omnis tene'),
(84, 2, 'Risa Colon', 92, 'Fuga Assumenda quas', 'Dolor exercitation s'),
(85, 2, 'Cassidy Macias', 24, 'Rerum laborum provid', 'Eum minim fugiat lab'),
(86, 10, 'Stewart Oneill', 58, 'Totam beatae aute en', 'Commodo veritatis si'),
(87, 3, 'Akeem Phelps', 58, 'Eum dolore voluptate', 'In ad quo officia qu'),
(88, 3, 'Merrill Stokes', 63, 'Pariatur Saepe qui ', 'Dolorum quaerat quo '),
(89, 3, 'Dalton Jensen', 18, 'Qui nulla voluptas i', 'Minim eius atque sus'),
(90, 3, 'Hayes Simpson', 83, 'Consequatur similiqu', 'Voluptatem iure qua'),
(92, 12, 'Mary Roberson', 44, 'Esse aut voluptate ', 'Autem voluptatibus e'),
(97, 13, 'Ginger May', 13, 'Mollitia similique o', 'Consequat Impedit '),
(98, 13, 'Nelle Byrd', 94, 'Ratione laboris rati', 'Porro possimus quis'),
(99, 13, 'Abigail Carter', 46, 'Labore quod cupidata', 'Sapiente dolore expe'),
(100, 13, 'Kasper Acosta', 17, 'In nemo harum earum ', 'Nulla incididunt atq'),
(101, 14, 'Josephine Thomas', 86, 'Officia dolore excep', 'Consectetur ab quos '),
(107, 15, 'Imelda Roth', 50, 'Molestias dolorem al', 'Vel velit aliquam pe'),
(108, 15, 'Anika Solis', 12, 'Nostrum nesciunt el', 'Omnis fugit nostrud'),
(109, 15, 'Jaquelyn Blackwell', 82, 'Quia deserunt ipsam ', 'Ex quo rerum nulla e'),
(110, 15, 'Violet Lloyd', 77, 'Laborum Ea consequa', 'Saepe delectus pers'),
(111, 15, 'Kameko Moses', 69, 'Tempora perspiciatis', 'In mollitia rerum su'),
(113, 11, 'Jana Barlow', 48, 'Possimus non cumque', 'Beatae voluptatibus '),
(116, 16, 'bob', 23, 'mbbs', 'no'),
(117, 17, 'ghdfg', 45, 'fdhfg', 'fgds'),
(118, 18, '1f', 23, 'dfg', 'no'),
(119, 19, 'Flynn Payne', 70, 'Rerum maiores qui ul', 'Vel non ex libero ob'),
(120, 20, 'Davis Mcclain', 77, 'Est quae et quia nih', 'Autem accusantium be'),
(121, 21, 'Benedict Bray', 73, 'Reiciendis cum omnis', 'Sint tenetur magna ');

-- --------------------------------------------------------

--
-- Table structure for table `illuminate`
--

CREATE TABLE `illuminate` (
  `ill_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `illuminate`
--

INSERT INTO `illuminate` (`ill_id`, `name`, `date_added`, `status`) VALUES
(1, 'With Light', '2020-05-07 00:00:00', 1),
(2, 'Tests', '2020-05-14 08:56:57', 1),
(3, 'Test Est', '2020-05-14 08:57:24', 2);

-- --------------------------------------------------------

--
-- Table structure for table `image_details`
--

CREATE TABLE `image_details` (
  `image_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_enc_name` varchar(255) NOT NULL,
  `image_path` text NOT NULL,
  `image_size` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image_details`
--

INSERT INTO `image_details` (`image_id`, `image_name`, `image_enc_name`, `image_path`, `image_size`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Sample 1.pdf', 'c1a8237fb05e25f294a553f24786f8bf.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/c1a8237fb05e25f294a553f24786f8bf.pdf', '138.69', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Sample 1 - Copy.pdf', '02787f607e8bcfcae8e84286b15cdd97.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/02787f607e8bcfcae8e84286b15cdd97.pdf', '138.69', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '2019-07-10 Tata Power - Annual Plan Submission Ack 19-20.pdf', '0dbce220801b8f4a01903f42d1f791ce.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/0dbce220801b8f4a01903f42d1f791ce.pdf', '153.49', 1, 0, '2020-10-08 07:47:08', '2020-10-08 07:47:08'),
(4, '2020-10-15 Tata Power - Annual Plan Submission Ack 20-21.pdf', 'c2331cd6a45cb0181aad246918a69302.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/c2331cd6a45cb0181aad246918a69302.pdf', '286.37', 1, 0, '2020-10-08 07:47:08', '2020-10-08 07:47:08'),
(5, 'Sample 1.pdf', 'cb9a6893cf285ae37cee1d42ecb30ced.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/cb9a6893cf285ae37cee1d42ecb30ced.pdf', '80.06', 1, 0, '2020-10-13 11:50:13', '2020-10-13 11:50:13'),
(6, 'Sample 2.pdf', 'd98b1c08c9789f0d017fe71ed63d83a2.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/d98b1c08c9789f0d017fe71ed63d83a2.pdf', '79.25', 1, 0, '2020-10-13 11:50:13', '2020-10-13 11:50:13'),
(7, '10 mtr Sujata Ceramic Appln Ack.pdf', '077d047d5b8cb355c3599b9e51532fc4.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/077d047d5b8cb355c3599b9e51532fc4.pdf', '93.01', 1, 0, '2020-11-22 15:18:22', '2020-11-22 15:18:22'),
(8, '10 mtr Sujata Ceramic Appln Ack.pdf', 'fc2b003d03f041ab5a24e643ad7c9d24.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/fc2b003d03f041ab5a24e643ad7c9d24.pdf', '93.01', 1, 0, '2020-11-22 15:18:22', '2020-11-22 15:18:22'),
(9, '623 Mtr Demand Note.pdf', 'fb5a46e4a0be56d75cfdd0bd01457a60.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/fb5a46e4a0be56d75cfdd0bd01457a60.pdf', '528.12', 1, 0, '2020-11-15 15:26:15', '2020-11-15 15:26:15'),
(10, '623 Mtr Demand Note.pdf', '64cf6cbe778504b8b488f1d22b51ff50.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/64cf6cbe778504b8b488f1d22b51ff50.pdf', '528.12', 1, 0, '2020-11-15 15:26:15', '2020-11-15 15:26:15'),
(11, '465 mtr Receipt Swastik.pdf', '1ba5bd24eabb07d75c153b858455472e.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/1ba5bd24eabb07d75c153b858455472e.pdf', '99.71', 1, 0, '2020-11-28 15:33:28', '2020-11-28 15:33:28'),
(12, '465 mtr Receipt1 Swastik.pdf', 'a2a2ad77f11a6d749697965d4110a587.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/a2a2ad77f11a6d749697965d4110a587.pdf', '98.07', 1, 0, '2020-11-28 15:33:28', '2020-11-28 15:33:28'),
(13, '200 mtr Rohit Appln.pdf', '8f2223a0054ee649c1f279fa7df9d32f.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/8f2223a0054ee649c1f279fa7df9d32f.pdf', '232.51', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '200 mtr Rohit Appln.pdf', 'ccc22e10b5255e3b982a55bfdcf7e265.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/ccc22e10b5255e3b982a55bfdcf7e265.pdf', '232.51', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, '10 mtr Lodha Aqua Appln Ack.pdf', '00e07e8857e6c4f3bb4bb69e467b72b7.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/00e07e8857e6c4f3bb4bb69e467b72b7.pdf', '86.31', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '10 mtr Lodha Aqua Appln Ack.pdf', '95fbb727c6eb0c00d99671119c7dc3bc.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/95fbb727c6eb0c00d99671119c7dc3bc.pdf', '86.31', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '123.pdf', 'b54adb4c41b204983892a950ed6dc0cb.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/b54adb4c41b204983892a950ed6dc0cb.pdf', '25.86', 1, 0, '2020-11-03 08:06:03', '2020-11-03 08:06:03'),
(18, '123.pdf', 'edc31ff0438d31a4ba2f7ac125e964e4.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/edc31ff0438d31a4ba2f7ac125e964e4.pdf', '25.86', 1, 0, '2020-11-03 08:06:03', '2020-11-03 08:06:03'),
(19, '123.pdf', '7fb06940303a6ceb1eb0a14a19f6147b.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/7fb06940303a6ceb1eb0a14a19f6147b.pdf', '25.86', 1, 0, '2020-11-21 08:31:21', '2020-11-21 08:31:21'),
(20, '123.pdf', 'a1b64e9bd72886d0db509a39de5a5f27.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/a1b64e9bd72886d0db509a39de5a5f27.pdf', '25.86', 1, 0, '2020-11-21 08:31:21', '2020-11-21 08:31:21'),
(21, 'Anurag_Mishra_Mumbai_5.04_yrs.pdf', '4bbd8cb047493c45f63c69b0f68e5ef1.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/4bbd8cb047493c45f63c69b0f68e5ef1.pdf', '116.17', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '50495179_Mumbai_8.00_yrs.pdf', 'a243b098a87d4db2610d22c5610dec98.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/a243b098a87d4db2610d22c5610dec98.pdf', '208.45', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'ff2d84f5c010c2ffa96a81e79a7a9091.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/ff2d84f5c010c2ffa96a81e79a7a9091.pdf', '2.96', 1, 0, '2020-11-21 07:10:21', '2020-11-21 07:10:21'),
(24, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '47eb6b1dc4a1316e52afc8c3b86b081b.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/47eb6b1dc4a1316e52afc8c3b86b081b.pdf', '2.96', 1, 0, '2020-11-21 07:10:21', '2020-11-21 07:10:21'),
(25, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '1826c26bcd370092e170672e5d8f2396.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/1826c26bcd370092e170672e5d8f2396.pdf', '2.96', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '82c77380df277bd4da35c698c27a52f7.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/82c77380df277bd4da35c698c27a52f7.pdf', '2.96', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'c6ce5f3beee53b93b24a08573653db8a.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/c6ce5f3beee53b93b24a08573653db8a.pdf', '2.96', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '69b08df2792c3e3cff024afe59a0b73e.pdf', 'http://mbmc.aaravsoftware.in/uploads/pwd/69b08df2792c3e3cff024afe59a0b73e.pdf', '305.44', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Lighthouse.jpg', '970651c1a601ff0b76c963154753b303.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/970651c1a601ff0b76c963154753b303.jpg', '548.12', 1, 0, '2020-11-22 17:14:26', '2020-11-22 17:14:26'),
(30, 'Tulips.jpg', '5f16a4a2b9bcd0094c83d57900610dd7.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/5f16a4a2b9bcd0094c83d57900610dd7.jpg', '606.34', 1, 0, '2020-11-22 17:14:26', '2020-11-22 17:14:26'),
(31, 'Penguins.jpg', '8539e7e20025bb9ad3f03322cffebcee.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/8539e7e20025bb9ad3f03322cffebcee.jpg', '759.6', 1, 0, '2020-11-22 17:14:26', '2020-11-22 17:14:26'),
(32, 'Tulips.jpg', '1f5db793a4312f057b6b5b5a9554655f.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/1f5db793a4312f057b6b5b5a9554655f.jpg', '606.34', 1, 0, '2020-11-22 17:14:26', '2020-11-22 17:14:26'),
(33, 'Lighthouse.jpg', 'b76090fb52fcd6f2c99bae3b06626f35.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/b76090fb52fcd6f2c99bae3b06626f35.jpg', '548.12', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(34, 'Lighthouse.jpg', 'baa6f11b483758416283a91bd5ae91d2.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/baa6f11b483758416283a91bd5ae91d2.jpg', '548.12', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(35, 'Penguins.jpg', '2756540503eb1a471c3545d23676b981.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/2756540503eb1a471c3545d23676b981.jpg', '759.6', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(36, 'Tulips.jpg', 'b20b0804f80e98f64144fef42298c0ca.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/b20b0804f80e98f64144fef42298c0ca.jpg', '606.34', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(37, 'Tulips.jpg', '51a1996f7908e5c4952567475f9c1b9d.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/51a1996f7908e5c4952567475f9c1b9d.jpg', '606.34', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(38, 'Tulips.jpg', '515c706cf70d4d50cce5c3549c28847e.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/515c706cf70d4d50cce5c3549c28847e.jpg', '606.34', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(39, 'Lighthouse.jpg', '28967bcf29bef74a79701fad001bd34a.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/28967bcf29bef74a79701fad001bd34a.jpg', '548.12', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(40, 'Koala.jpg', '54a2b91ff6c754884e503a85e8efa4a8.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/54a2b91ff6c754884e503a85e8efa4a8.jpg', '762.53', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(41, 'Lighthouse.jpg', 'b497bfeaf4d0a9d9b6f65d9d1a34c2f0.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/b497bfeaf4d0a9d9b6f65d9d1a34c2f0.jpg', '548.12', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(42, 'Koala.jpg', '522d31b9f5a02ee8f7b876c7f4043925.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/522d31b9f5a02ee8f7b876c7f4043925.jpg', '762.53', 1, 0, '2020-11-22 18:08:13', '2020-11-22 18:08:13'),
(43, 'Koala.jpg', '8e0167e6c7349a4cf07445c43679eca7.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/8e0167e6c7349a4cf07445c43679eca7.jpg', '762.53', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(44, 'Penguins.jpg', 'fecc8a0c24928858eb7836f97a8d2a1b.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/fecc8a0c24928858eb7836f97a8d2a1b.jpg', '759.6', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(45, 'Tulips.jpg', 'c82610e6cfd75cb619d40a8487f92dc2.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/c82610e6cfd75cb619d40a8487f92dc2.jpg', '606.34', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(46, 'Tulips.jpg', '3e25f679a3b9add2e421dbbe1a134cc3.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/3e25f679a3b9add2e421dbbe1a134cc3.jpg', '606.34', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(47, 'Koala.jpg', '325c29f49bb8846e755910453fd62911.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/325c29f49bb8846e755910453fd62911.jpg', '762.53', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(48, 'Hydrangeas.jpg', 'ccb9447d9d62395318d5327a4ed3fcfb.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/ccb9447d9d62395318d5327a4ed3fcfb.jpg', '581.33', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(49, 'Lighthouse.jpg', 'cf29caa3d4a20dba27e31a1a5482fcd7.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/cf29caa3d4a20dba27e31a1a5482fcd7.jpg', '548.12', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(50, 'Penguins.jpg', '502b123665973db95803ca3c785f4923.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/502b123665973db95803ca3c785f4923.jpg', '759.6', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(51, 'Penguins.jpg', '96ac0acaca99fa8cb69b427755eda6c8.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/96ac0acaca99fa8cb69b427755eda6c8.jpg', '759.6', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(52, 'Tulips.jpg', '8de3f9b49e980fbc1d1c155eaa1966e1.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/8de3f9b49e980fbc1d1c155eaa1966e1.jpg', '606.34', 1, 0, '2020-11-22 18:14:42', '2020-11-22 18:14:42'),
(53, 'Koala.jpg', '5ea0838e6d1f5cdea767499abadbc983.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/5ea0838e6d1f5cdea767499abadbc983.jpg', '762.53', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(54, 'Penguins.jpg', '04d094ac0978d47285c0b87b89b13984.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/04d094ac0978d47285c0b87b89b13984.jpg', '759.6', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(55, 'Penguins.jpg', '6baca129810c342f5744f47994e095ee.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/6baca129810c342f5744f47994e095ee.jpg', '759.6', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(56, 'Penguins.jpg', '1f1c64062f043b77d031911bcbb471b1.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/1f1c64062f043b77d031911bcbb471b1.jpg', '759.6', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(57, 'Tulips.jpg', '75630628274f4f6608007d52dc5f6bd0.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/75630628274f4f6608007d52dc5f6bd0.jpg', '606.34', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(58, 'Jellyfish.jpg', '88b96423eaa9720004a473aea7fa790f.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/88b96423eaa9720004a473aea7fa790f.jpg', '757.52', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(59, 'Jellyfish.jpg', 'aee8f90cb4211b964dad3925c951b1b6.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/aee8f90cb4211b964dad3925c951b1b6.jpg', '757.52', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(60, 'Lighthouse.jpg', 'a0a7a190f7bad7480f49fcca86f0ad78.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/a0a7a190f7bad7480f49fcca86f0ad78.jpg', '548.12', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(61, 'Lighthouse.jpg', '45beaea0aa063b3992f1e7be539f869c.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/45beaea0aa063b3992f1e7be539f869c.jpg', '548.12', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(62, 'Chrysanthemum.jpg', '09341d2c00ac4ea0745ca8a83a0b5c75.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/09341d2c00ac4ea0745ca8a83a0b5c75.jpg', '858.78', 1, 0, '2020-11-22 18:39:51', '2020-11-22 18:39:51'),
(63, 'Lighthouse.jpg', 'd2ac95143baa0be3834eb15f380de5c9.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/d2ac95143baa0be3834eb15f380de5c9.jpg', '548.12', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(64, 'Tulips.jpg', '934546484555bc383fd177addf2ca1f1.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/934546484555bc383fd177addf2ca1f1.jpg', '606.34', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(65, 'Lighthouse.jpg', 'c2711854c8da05b8f4f35340eb714c7d.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/c2711854c8da05b8f4f35340eb714c7d.jpg', '548.12', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(66, 'Koala.jpg', '46bae87d39d3dd73852e61e617831a58.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/46bae87d39d3dd73852e61e617831a58.jpg', '762.53', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(67, 'Tulips.jpg', 'be867e74765a2ede206cae265299262d.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/be867e74765a2ede206cae265299262d.jpg', '606.34', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(68, 'Lighthouse.jpg', '264c55cb1cc1e6b06be23e78d826ee27.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/264c55cb1cc1e6b06be23e78d826ee27.jpg', '548.12', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(69, 'Desert.jpg', '591509439e549cefc1c5b9ce89e200f1.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/591509439e549cefc1c5b9ce89e200f1.jpg', '826.11', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(70, 'Hydrangeas.jpg', '0941a8728aa2601f3c9c1cac5b1dc4c1.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/0941a8728aa2601f3c9c1cac5b1dc4c1.jpg', '581.33', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(71, 'Hydrangeas.jpg', '0262c3836150e43d8a47e8ad289c4fda.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/0262c3836150e43d8a47e8ad289c4fda.jpg', '581.33', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(72, 'Lighthouse.jpg', '9fec45f61bc2fe92d76343da743a5d9e.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/9fec45f61bc2fe92d76343da743a5d9e.jpg', '548.12', 1, 0, '2020-11-22 19:17:44', '2020-11-22 19:17:44'),
(73, 'sample file.pdf', '6dd132f0b0146a4daa9b955d8114d2ac.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/6dd132f0b0146a4daa9b955d8114d2ac.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(74, 'sample file.pdf', 'f08645138db2032e6d3505194fde4b1a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f08645138db2032e6d3505194fde4b1a.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(75, 'sample file.pdf', '8e322ba712482c911f6190827cf62445.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/8e322ba712482c911f6190827cf62445.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(76, 'sample file.pdf', 'aad0d3920eaca183bd9a32ee4a9d2650.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/aad0d3920eaca183bd9a32ee4a9d2650.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(77, 'sample file.pdf', '7deaeb5e9c37c1dba8733802c1de80d7.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/7deaeb5e9c37c1dba8733802c1de80d7.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(78, 'sample file.pdf', '1f364b246255320e3f69ab62207c9630.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/1f364b246255320e3f69ab62207c9630.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(79, 'sample file.pdf', 'ad08acc2711e5a7a1776d54221a370fd.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ad08acc2711e5a7a1776d54221a370fd.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(80, 'sample file.pdf', '23ef74d8cc11c6f20a78a0b743457c50.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/23ef74d8cc11c6f20a78a0b743457c50.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(81, 'sample file.pdf', 'e8a83f1471b78f4da4b611b57d498713.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e8a83f1471b78f4da4b611b57d498713.pdf', '2.96', 1, 0, '2020-11-22 19:19:56', '2020-11-22 19:19:56'),
(82, 'Koala.jpg', 'b37c35169333e8e182d497ab46f5b8ef.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/b37c35169333e8e182d497ab46f5b8ef.jpg', '762.53', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(83, 'Penguins.jpg', 'fdf0d63547b833e81389f5cae1d54d95.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/fdf0d63547b833e81389f5cae1d54d95.jpg', '759.6', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(84, 'Lighthouse.jpg', 'b6156410aaf2c2a75ab36feab87e3682.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/b6156410aaf2c2a75ab36feab87e3682.jpg', '548.12', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(85, 'Lighthouse.jpg', '076e91aadde703f5ac4c33ed4974a50f.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/076e91aadde703f5ac4c33ed4974a50f.jpg', '548.12', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(86, 'Penguins.jpg', 'a360c2e175822d1749f8ac5eefc19d1c.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/a360c2e175822d1749f8ac5eefc19d1c.jpg', '759.6', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(87, 'Penguins.jpg', 'c0e61743632ce7fea2539155d5d7f64a.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/c0e61743632ce7fea2539155d5d7f64a.jpg', '759.6', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(88, 'Tulips.jpg', 'dddaf364eef260dcea1c24473cbbba0d.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/dddaf364eef260dcea1c24473cbbba0d.jpg', '606.34', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(89, 'Penguins.jpg', '16f72666a353007bbdf3c96a4a9c0bee.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/16f72666a353007bbdf3c96a4a9c0bee.jpg', '759.6', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(90, 'Tulips.jpg', 'f7f6fcf4c89f2b01144949c21062a047.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/f7f6fcf4c89f2b01144949c21062a047.jpg', '606.34', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(91, 'Penguins.jpg', '7b5141b4653a1af77cf13d3d5d2300f4.jpg', 'http://mbmc.aaravsoftware.in/uploads/hospital/7b5141b4653a1af77cf13d3d5d2300f4.jpg', '759.6', 1, 0, '2020-11-22 20:51:54', '2020-11-22 20:51:54'),
(92, 'sample file.pdf', '796354c512290e4fa1bf993370175f3a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/796354c512290e4fa1bf993370175f3a.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(93, 'sample file.pdf', '026d19fa9029d366ecde358863e616b7.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/026d19fa9029d366ecde358863e616b7.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(94, 'sample file.pdf', '934bc4e1b38ef95c28f41842b652a426.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/934bc4e1b38ef95c28f41842b652a426.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(95, 'sample file.pdf', '711245e60bb0b3f5358705dcee0e8046.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/711245e60bb0b3f5358705dcee0e8046.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(96, 'sample file.pdf', '74de662c66c722e524cc35b5e8c83c0c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/74de662c66c722e524cc35b5e8c83c0c.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(97, 'sample file.pdf', '402b2f314bf76f48b0718af83ea4ff7c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/402b2f314bf76f48b0718af83ea4ff7c.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(98, 'sample file.pdf', '4ff1e2e9031c2252b60c4b1b0a56fd52.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/4ff1e2e9031c2252b60c4b1b0a56fd52.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(99, 'sample file.pdf', '42385dc5dea97efec3be0021eedc2a7a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/42385dc5dea97efec3be0021eedc2a7a.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(100, 'sample file.pdf', '0c7872d70909062ca82ef90290fac08e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/0c7872d70909062ca82ef90290fac08e.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(101, 'sample file.pdf', '400f4f0f90403aa8b63255d2d36cd1c4.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/400f4f0f90403aa8b63255d2d36cd1c4.pdf', '2.96', 1, 0, '2020-11-23 08:15:17', '2020-11-23 08:15:17'),
(102, 'sample file.pdf', 'f898699ebfcbd0e8a1994037997bb5fc.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f898699ebfcbd0e8a1994037997bb5fc.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(103, 'sample file.pdf', '1c6fcdb293cd1f41fdfe00e7f23125f4.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/1c6fcdb293cd1f41fdfe00e7f23125f4.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(104, 'sample file.pdf', 'd65857c0b9bcc2e61065f2c2dba78f9e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/d65857c0b9bcc2e61065f2c2dba78f9e.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(105, 'sample file.pdf', '945507dd4949fbb07ed81b00c6ca1b62.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/945507dd4949fbb07ed81b00c6ca1b62.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(106, 'sample file.pdf', '7f27d8af426924541758b84e6db5be6c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/7f27d8af426924541758b84e6db5be6c.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(107, 'sample file.pdf', '26aa495b6191d6cfc9f8edbadc1ca7a9.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/26aa495b6191d6cfc9f8edbadc1ca7a9.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(108, 'sample file.pdf', 'aa46792a9a48141f7ad7e6dadd28a1b7.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/aa46792a9a48141f7ad7e6dadd28a1b7.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(109, 'sample file.pdf', '56db4c5cc9bc5e839391cc2889f3db6d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/56db4c5cc9bc5e839391cc2889f3db6d.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(110, 'sample file.pdf', '60a4e9385889a096bbd9ca7a0fc12d83.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/60a4e9385889a096bbd9ca7a0fc12d83.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(111, 'sample file.pdf', '2cc42eb67867627f215b72f197a765d1.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/2cc42eb67867627f215b72f197a765d1.pdf', '2.96', 1, 0, '2020-11-23 08:15:20', '2020-11-23 08:15:20'),
(112, 'sample file.pdf', 'dcafbc205f9cd4fdc8bc3a78ce6b4d4b.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/dcafbc205f9cd4fdc8bc3a78ce6b4d4b.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(113, 'sample file.pdf', '959704189a871afc46c0b17942506a7a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/959704189a871afc46c0b17942506a7a.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(114, 'sample file.pdf', '761773425744f9067f68a731bfe34a0e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/761773425744f9067f68a731bfe34a0e.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(115, 'sample file.pdf', '537401702b6c8d4b28ff9a321f95bec4.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/537401702b6c8d4b28ff9a321f95bec4.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(116, 'sample file.pdf', 'c3cd8437629e662cadf0314a33ff019e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c3cd8437629e662cadf0314a33ff019e.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(117, 'sample file.pdf', '67b6eb155d95a26a09b9d8ac2d111f4d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/67b6eb155d95a26a09b9d8ac2d111f4d.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(118, 'sample file.pdf', 'ff9210987438b584930754e0d6544bfe.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ff9210987438b584930754e0d6544bfe.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(119, 'sample file.pdf', 'ce176a76ce7a8c9d0207ffc970befc1f.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ce176a76ce7a8c9d0207ffc970befc1f.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(120, 'sample file.pdf', 'c6c7f08c25f7c6acab86aa7493c51897.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c6c7f08c25f7c6acab86aa7493c51897.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(121, 'sample file.pdf', '6dd6bcebf4fdfca6032166dbf17de7b0.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/6dd6bcebf4fdfca6032166dbf17de7b0.pdf', '2.96', 1, 0, '2020-11-23 08:15:22', '2020-11-23 08:15:22'),
(122, 'sample file.pdf', '0ac9746af26b960f85c0b02817a9670a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/0ac9746af26b960f85c0b02817a9670a.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(123, 'sample file.pdf', 'eb0f1819cede78bdc879b0a04d8aea27.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/eb0f1819cede78bdc879b0a04d8aea27.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(124, 'sample file.pdf', '2b2c8a3c5d88bf9bff3aa339c14c23b2.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/2b2c8a3c5d88bf9bff3aa339c14c23b2.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(125, 'sample file.pdf', '4611cbdaa09d109d8e7b87bdf4a1e581.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/4611cbdaa09d109d8e7b87bdf4a1e581.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(126, 'sample file.pdf', 'c0ffee3d72349ef75778baf1e9f136c7.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c0ffee3d72349ef75778baf1e9f136c7.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(127, 'sample file.pdf', '245a144fb0224f2ac2680e5e530be40c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/245a144fb0224f2ac2680e5e530be40c.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(128, 'sample file.pdf', 'f39efe44200b058677fd08d51418607f.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f39efe44200b058677fd08d51418607f.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(129, 'sample file.pdf', '183886d3040c576f0f2ebb93d7848b6d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/183886d3040c576f0f2ebb93d7848b6d.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(130, 'sample file.pdf', 'e2bf46606ecb5cca636a03f0ef34930f.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e2bf46606ecb5cca636a03f0ef34930f.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(131, 'sample file.pdf', '3b9908dad716b520fb942b17d9a869c5.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/3b9908dad716b520fb942b17d9a869c5.pdf', '2.96', 1, 0, '2020-11-23 08:15:23', '2020-11-23 08:15:23'),
(132, 'sample file.pdf', '4dd30c37dc691cb1956725aa176a9950.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/4dd30c37dc691cb1956725aa176a9950.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(133, 'sample file.pdf', 'a1a02e8b1ba74392e62311af0269c410.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/a1a02e8b1ba74392e62311af0269c410.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(134, 'sample file.pdf', 'b2b5891676b353a5ecc508f3d05eedd5.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/b2b5891676b353a5ecc508f3d05eedd5.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(135, 'sample file.pdf', 'ab47a14e9ac795e6836416c02a2c9b72.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ab47a14e9ac795e6836416c02a2c9b72.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(136, 'sample file.pdf', 'a6ac41cc0aea4c6b7d68b767edf2b30c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/a6ac41cc0aea4c6b7d68b767edf2b30c.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(137, 'sample file.pdf', '0d01cf10ebe76aab9391bcefe33ffac2.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/0d01cf10ebe76aab9391bcefe33ffac2.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(138, 'sample file.pdf', '36ec405746f8f1cc258e4037f04887ac.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/36ec405746f8f1cc258e4037f04887ac.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(139, 'sample file.pdf', '7210795d2cb8e8616682fd84bb7c3a35.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/7210795d2cb8e8616682fd84bb7c3a35.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(140, 'sample file.pdf', '2354a800cb90ba945283632e7c9a83cc.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/2354a800cb90ba945283632e7c9a83cc.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(141, 'sample file.pdf', '14bfd678da4d08ca0d0e909a5719f7ce.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/14bfd678da4d08ca0d0e909a5719f7ce.pdf', '2.96', 1, 0, '2020-11-23 08:15:26', '2020-11-23 08:15:26'),
(142, 'sample file.pdf', '94f8b1c1150c96b425953dcc6bcab71a.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/94f8b1c1150c96b425953dcc6bcab71a.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(143, 'sample file.pdf', '5334866372fa86392d6d23822a5c3004.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/5334866372fa86392d6d23822a5c3004.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(144, 'sample file.pdf', 'c3609428c7855bb14c5e78553e85f754.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c3609428c7855bb14c5e78553e85f754.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(145, 'sample file.pdf', 'df740e76ff8de351cc6003ec3b294ea3.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/df740e76ff8de351cc6003ec3b294ea3.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(146, 'sample file.pdf', 'cdd5dbbb81ce91ae8171cb5f0025f0bb.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/cdd5dbbb81ce91ae8171cb5f0025f0bb.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(147, 'sample file.pdf', 'cccf18cc24d5329f5ef70d13770c7d28.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/cccf18cc24d5329f5ef70d13770c7d28.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(148, 'sample file.pdf', '5391b62466a6b3a06a594b642b82ec20.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/5391b62466a6b3a06a594b642b82ec20.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(149, 'sample file.pdf', 'aa6ce8cd0567032cff63bf265582aa0d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/aa6ce8cd0567032cff63bf265582aa0d.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(150, 'sample file.pdf', 'ea95a44d7fcb0d909769fe0599338977.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ea95a44d7fcb0d909769fe0599338977.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(151, 'sample file.pdf', '9415292187efe40422eabbea8ad0ad9e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/9415292187efe40422eabbea8ad0ad9e.pdf', '2.96', 1, 0, '2020-11-23 08:15:28', '2020-11-23 08:15:28'),
(152, 'sample file.pdf', '626a7b4830f0c3740341d4931c0f36e0.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/626a7b4830f0c3740341d4931c0f36e0.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(153, 'sample file.pdf', 'c660dc56f8cd498757bf3cfda00ed580.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c660dc56f8cd498757bf3cfda00ed580.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(154, 'sample file.pdf', 'ed3a343bc7a6420555bc3eefd62b0743.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ed3a343bc7a6420555bc3eefd62b0743.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(155, 'sample file.pdf', '417e78d446d6643c3e4cac5366121c50.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/417e78d446d6643c3e4cac5366121c50.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(156, 'sample file.pdf', '80b9b4cb91e25b5640267ec48770ba66.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/80b9b4cb91e25b5640267ec48770ba66.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(157, 'sample file.pdf', 'ce5cdc3b277cf595ec6c8c11f348aaf3.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ce5cdc3b277cf595ec6c8c11f348aaf3.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(158, 'sample file.pdf', 'd97e164fa2a266125e8fae32f064ce4d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/d97e164fa2a266125e8fae32f064ce4d.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(159, 'sample file.pdf', '5c32c11860af95f070c5188803bb319c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/5c32c11860af95f070c5188803bb319c.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(160, 'sample file.pdf', 'd641bc8ccb098f01725826ab51cae0a2.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/d641bc8ccb098f01725826ab51cae0a2.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(161, 'sample file.pdf', '2a90df4f30a1415e41d45dcf8798db4b.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/2a90df4f30a1415e41d45dcf8798db4b.pdf', '2.96', 1, 0, '2020-11-23 08:15:30', '2020-11-23 08:15:30'),
(162, 'sample file.pdf', '919fdc770f6287efb08eddcc997b7f03.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/919fdc770f6287efb08eddcc997b7f03.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(163, 'sample file.pdf', '0138fbd08c9aad5fd6c8fe9ecd989744.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/0138fbd08c9aad5fd6c8fe9ecd989744.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(164, 'sample file.pdf', '5d0d7b02fe2decfd26aef7f1bf647fd9.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/5d0d7b02fe2decfd26aef7f1bf647fd9.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(165, 'sample file.pdf', 'ba8b3178d831b1012c3bbf2b89866e11.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ba8b3178d831b1012c3bbf2b89866e11.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(166, 'sample file.pdf', 'e034750cc56c243df7e59ebd5bc2aafe.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e034750cc56c243df7e59ebd5bc2aafe.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(167, 'sample file.pdf', '9bc80e775ab3870763ffe3db0334951d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/9bc80e775ab3870763ffe3db0334951d.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(168, 'sample file.pdf', '78e23fb01c0af9d4c7ab5fccb094d417.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/78e23fb01c0af9d4c7ab5fccb094d417.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(169, 'sample file.pdf', '01e32427e598b7e584625eb72866cdf0.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/01e32427e598b7e584625eb72866cdf0.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(170, 'sample file.pdf', 'f3d028aa1ee23b244fa967a1647181c5.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f3d028aa1ee23b244fa967a1647181c5.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(171, 'sample file.pdf', 'fcba24baba8e5f51574434e8a1b088c0.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/fcba24baba8e5f51574434e8a1b088c0.pdf', '2.96', 1, 0, '2020-11-23 08:15:32', '2020-11-23 08:15:32'),
(172, 'sample file.pdf', '4a2d239fa1e40b530c528f76ff527f9c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/4a2d239fa1e40b530c528f76ff527f9c.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(173, 'sample file.pdf', '66d07394b333a6de0beb0d510c3e84f9.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/66d07394b333a6de0beb0d510c3e84f9.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(174, 'sample file.pdf', 'ad2fcea296319b7f95497405961a73dc.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ad2fcea296319b7f95497405961a73dc.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(175, 'sample file.pdf', 'b355d002819f35f0084c3ae219aa7d7b.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/b355d002819f35f0084c3ae219aa7d7b.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(176, 'sample file.pdf', '5331fa2b535466a9f6b17a7f994f536c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/5331fa2b535466a9f6b17a7f994f536c.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(177, 'sample file.pdf', 'f2da7b9f60480900fb9c50ef1d7a8aab.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f2da7b9f60480900fb9c50ef1d7a8aab.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(178, 'sample file.pdf', 'c999f09b271dc0b34e844a0ed98dd636.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c999f09b271dc0b34e844a0ed98dd636.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(179, 'sample file.pdf', 'e53b9754bcbc20de1b18bfb973de1b83.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e53b9754bcbc20de1b18bfb973de1b83.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(180, 'sample file.pdf', 'c40fce1959ad2897d7eb4ae2c4e32c52.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c40fce1959ad2897d7eb4ae2c4e32c52.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(181, 'sample file.pdf', '288083a80dc5dbe9f9eb98c5b5f0de2c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/288083a80dc5dbe9f9eb98c5b5f0de2c.pdf', '2.96', 1, 0, '2020-11-23 08:15:40', '2020-11-23 08:15:40'),
(182, 'sample file.pdf', 'ea6507fe4bab5293ee278bd7ec0f378c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/ea6507fe4bab5293ee278bd7ec0f378c.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(183, 'sample file.pdf', '76d464efb51e529e3ffd0b1c37091c5e.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/76d464efb51e529e3ffd0b1c37091c5e.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(184, 'sample file.pdf', '638ca239d9d0091752d03368fd7f7eb8.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/638ca239d9d0091752d03368fd7f7eb8.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(185, 'sample file.pdf', 'e3e2465214e02c01dd0f3275e47884a3.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e3e2465214e02c01dd0f3275e47884a3.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(186, 'sample file.pdf', 'fdc19a6c102b1907f61440488569441f.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/fdc19a6c102b1907f61440488569441f.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(187, 'sample file.pdf', '73fea9bee3a3e5795d6a9263f011eb12.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/73fea9bee3a3e5795d6a9263f011eb12.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(188, 'sample file.pdf', '1ef5de36f4d031b5be33aed9af860ef1.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/1ef5de36f4d031b5be33aed9af860ef1.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(189, 'sample file.pdf', '03aab03957aaededf0e1d2198bfb7f72.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/03aab03957aaededf0e1d2198bfb7f72.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(190, 'sample file.pdf', '6ed848adeeaa3d7901d6e79a5fd5b0a3.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/6ed848adeeaa3d7901d6e79a5fd5b0a3.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(191, 'sample file.pdf', '10dabf4fccf2e5fbc3b4b35bdbca106b.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/10dabf4fccf2e5fbc3b4b35bdbca106b.pdf', '2.96', 1, 0, '2020-11-23 08:15:41', '2020-11-23 08:15:41'),
(192, 'Gem Marker.pdf', '54fe5be98ccd7adbe29ef66a47e83051.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/54fe5be98ccd7adbe29ef66a47e83051.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(193, 'Gem Marker.pdf', 'abb4a27c3b5b9d23fa3333e7624e3a8d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/abb4a27c3b5b9d23fa3333e7624e3a8d.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(194, 'Gem Marker.pdf', 'e323b4229bd8a7c21714bf8b97386aec.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/e323b4229bd8a7c21714bf8b97386aec.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(195, 'Gem Marker.pdf', '2fd64ec93d7a0ed009b394a41d89f26c.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/2fd64ec93d7a0ed009b394a41d89f26c.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(196, 'Gem Marker.pdf', 'de3a5b5f31bd8dd7dbd7fe8d1a3693eb.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/de3a5b5f31bd8dd7dbd7fe8d1a3693eb.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(197, 'Gem Marker.pdf', '8a2c771ef88a9354d0270132b4e500fe.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/8a2c771ef88a9354d0270132b4e500fe.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(198, 'Gem Marker.pdf', 'c278521a732373948a9bf7005e93c68b.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/c278521a732373948a9bf7005e93c68b.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(199, 'Gem Marker.pdf', 'f69b25926470630f928d8a38b0b46b6d.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/f69b25926470630f928d8a38b0b46b6d.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(200, 'Gem Marker.pdf', '452ebd9a514cab9f494184d3d3bc0e38.pdf', 'http://mbmc.aaravsoftware.in/uploads/hospital/452ebd9a514cab9f494184d3d3bc0e38.pdf', '594.87', 1, 0, '2020-11-23 10:59:45', '2020-11-23 10:59:45'),
(201, '300_1.jpg', 'e27010933fb3938bc31c52acc78213ff.jpg', 'http://localhost/mbmc_live/uploads/hospital/e27010933fb3938bc31c52acc78213ff.jpg', '83.47', 1, 0, '2020-11-24 07:20:51', '2020-11-24 07:20:51'),
(202, '300_2.jpg', '0ea1f6683c0b4ca324085ab9c30f2cd0.jpg', 'http://localhost/mbmc_live/uploads/hospital/0ea1f6683c0b4ca324085ab9c30f2cd0.jpg', '82.67', 1, 0, '2020-11-24 07:20:51', '2020-11-24 07:20:51'),
(203, '300_2.jpg', 'fecdfe77835a84222e8129225715f4d6.jpg', 'http://localhost/mbmc_live/uploads/hospital/fecdfe77835a84222e8129225715f4d6.jpg', '82.67', 1, 0, '2020-11-24 07:20:51', '2020-11-24 07:20:51'),
(204, '300_13.jpg', 'b8dc188285cb7d883c7d5a155c3c9dea.jpg', 'http://localhost/mbmc_live/uploads/hospital/b8dc188285cb7d883c7d5a155c3c9dea.jpg', '108.11', 1, 0, '2020-11-24 07:23:30', '2020-11-24 07:23:30'),
(205, '300_11.jpg', 'c245248d6aed62c3076b242e89036305.jpg', 'http://localhost/mbmc_live/uploads/hospital/c245248d6aed62c3076b242e89036305.jpg', '80.46', 1, 0, '2020-11-24 07:23:30', '2020-11-24 07:23:30'),
(206, '100_10.jpg', '5d5f7b115e7fa3764758fc94e3634081.jpg', 'http://localhost/mbmc_live/uploads/hospital/5d5f7b115e7fa3764758fc94e3634081.jpg', '12.44', 1, 0, '2020-11-24 07:23:30', '2020-11-24 07:23:30'),
(207, '300_1.jpg', 'e808fc8035378887c35d65abe91c87a7.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/e808fc8035378887c35d65abe91c87a7.jpg', '83.47', 1, 0, '2020-11-05 16:36:05', '2020-11-05 16:36:05'),
(208, '300_1.jpg', '55fe563dec734d7b04d2ef511a89f960.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/55fe563dec734d7b04d2ef511a89f960.jpg', '83.47', 1, 0, '2020-11-05 16:36:05', '2020-11-05 16:36:05'),
(209, '300_2.jpg', '16053ba2d482e6e1b4fd13af7ddd6118.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/16053ba2d482e6e1b4fd13af7ddd6118.jpg', '82.67', 1, 0, '2020-11-05 16:36:05', '2020-11-05 16:36:05'),
(210, '300_12.jpg', '9e9cb33b0431d94947ed42732328d1da.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/9e9cb33b0431d94947ed42732328d1da.jpg', '80.58', 1, 0, '2020-11-05 16:36:05', '2020-11-05 16:36:05'),
(211, '300_12.jpg', '0a18848909d09acc42a23b4c911cb0af.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/0a18848909d09acc42a23b4c911cb0af.jpg', '80.58', 1, 0, '2020-11-05 16:36:05', '2020-11-05 16:36:05'),
(212, '300_1.jpg', 'a80efda00db8536d45e3e800e1ed6bf2.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a80efda00db8536d45e3e800e1ed6bf2.jpg', '83.47', 1, 0, '2020-11-23 16:36:23', '2020-11-23 16:36:23'),
(213, '300_1.jpg', 'a9e9e3472937e0e02e02cd52de3e6ee6.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a9e9e3472937e0e02e02cd52de3e6ee6.jpg', '83.47', 1, 0, '2020-11-23 16:36:23', '2020-11-23 16:36:23'),
(214, '300_2.jpg', 'cb0a5e9843e592d1e0c320a90035bea8.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/cb0a5e9843e592d1e0c320a90035bea8.jpg', '82.67', 1, 0, '2020-11-23 16:36:23', '2020-11-23 16:36:23'),
(215, '300_12.jpg', '606e782e6ee14d6d253ca1a2daedc160.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/606e782e6ee14d6d253ca1a2daedc160.jpg', '80.58', 1, 0, '2020-11-23 16:36:23', '2020-11-23 16:36:23'),
(216, '300_12.jpg', '340defa74028c3c57d5cde7bd39c553e.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/340defa74028c3c57d5cde7bd39c553e.jpg', '80.58', 1, 0, '2020-11-23 16:36:23', '2020-11-23 16:36:23'),
(217, '300_1.jpg', 'f20a87ee1f30fc623d6f29c151cd883d.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/f20a87ee1f30fc623d6f29c151cd883d.jpg', '83.47', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, '300_1.jpg', '059a80cfa9ec0287bf004aca54e922ad.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/059a80cfa9ec0287bf004aca54e922ad.jpg', '83.47', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, '300_2.jpg', '631345062c663c24421b37244c8545da.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/631345062c663c24421b37244c8545da.jpg', '82.67', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, '300_12.jpg', '0e48c56bc72c09161fcdb7ee5c3bbef5.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/0e48c56bc72c09161fcdb7ee5c3bbef5.jpg', '80.58', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, '300_12.jpg', '13a18cfa283dc5744cd256c4059389a4.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/13a18cfa283dc5744cd256c4059389a4.jpg', '80.58', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, '300_1.jpg', '943d33ff75d6c3556aeaacd29e317280.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/943d33ff75d6c3556aeaacd29e317280.jpg', '83.47', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, '300_2.jpg', 'a7bad475208cfadd3db5cad161072d18.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a7bad475208cfadd3db5cad161072d18.jpg', '82.67', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, '300_12.jpg', '045b4db3befe9ca8ae3d46b7fd8456f8.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/045b4db3befe9ca8ae3d46b7fd8456f8.jpg', '80.58', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, '300_14.jpg', '4f56eca265d9d6e70f89137adf28ef95.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/4f56eca265d9d6e70f89137adf28ef95.jpg', '87.93', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, '300_22.jpg', '8ed97f0e23df9473446edbada2531476.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/8ed97f0e23df9473446edbada2531476.jpg', '92.53', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, '300_1.jpg', 'c282c38f0995bfd7c55ec226425c8da3.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c282c38f0995bfd7c55ec226425c8da3.jpg', '83.47', 1, 0, '2020-11-17 16:39:17', '2020-11-17 16:39:17'),
(228, '300_2.jpg', '9c3882325ae63b38eb2d18cfb5c9fdee.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/9c3882325ae63b38eb2d18cfb5c9fdee.jpg', '82.67', 1, 0, '2020-11-17 16:39:17', '2020-11-17 16:39:17'),
(229, '300_12.jpg', '6527df67d0b8074336aa1663e2f649f9.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/6527df67d0b8074336aa1663e2f649f9.jpg', '80.58', 1, 0, '2020-11-17 16:39:17', '2020-11-17 16:39:17'),
(230, '300_14.jpg', '1aaf27bd0cff961b4dbdfcfa67d9e423.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1aaf27bd0cff961b4dbdfcfa67d9e423.jpg', '87.93', 1, 0, '2020-11-17 16:39:17', '2020-11-17 16:39:17'),
(231, '300_22.jpg', '7c480eade6450a26a6b6f35dcde516d4.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/7c480eade6450a26a6b6f35dcde516d4.jpg', '92.53', 1, 0, '2020-11-17 16:39:17', '2020-11-17 16:39:17'),
(232, '300_1.jpg', '056029618dfbc5e53e1ec6dc5cfd0b1b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/056029618dfbc5e53e1ec6dc5cfd0b1b.jpg', '83.47', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, '300_3.jpg', 'ce1b262d3f3a7c859f8cfc8c87adc608.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/ce1b262d3f3a7c859f8cfc8c87adc608.jpg', '80.05', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, '300_10.jpg', '80e56f258a7a9119f0cb93d10138260d.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/80e56f258a7a9119f0cb93d10138260d.jpg', '75.07', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, '300_12.jpg', '48f3f73f289376067582469e2f53d766.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/48f3f73f289376067582469e2f53d766.jpg', '80.58', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, '300_4.jpg', '9bffad428a900eeaa8f1a2e344769ee3.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/9bffad428a900eeaa8f1a2e344769ee3.jpg', '63.73', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 'bg-1.jpg', 'b945ae083c5304fc6e9618ae23a91dc8.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/b945ae083c5304fc6e9618ae23a91dc8.jpg', '486.18', 1, 0, '2020-11-15 17:00:15', '2020-11-15 17:00:15'),
(238, 'bg-1.jpg', '96eae56bab023f53fed1a0ed4358a197.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/96eae56bab023f53fed1a0ed4358a197.jpg', '486.18', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `image_details` (`image_id`, `image_name`, `image_enc_name`, `image_path`, `image_size`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(239, 'demo5.jpg', 'ed4c00a8e1acbe3f26d4ee0a8d56cf23.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/ed4c00a8e1acbe3f26d4ee0a8d56cf23.jpg', '142.4', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 'demo17.jpg', 'de4c5d4857aee7ee892360dc78e79985.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/de4c5d4857aee7ee892360dc78e79985.jpg', '117.24', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 'demo18.jpg', 'c65b21967a62a26afe5be3f4e8d6e936.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c65b21967a62a26afe5be3f4e8d6e936.jpg', '135.23', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 'demo20.jpg', '750a2bb331e952867f6f94d6ac6e45a2.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/750a2bb331e952867f6f94d6ac6e45a2.jpg', '133.59', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 'demo17.jpg', '1dca396cb938b1a7fc2c02e90441cabe.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1dca396cb938b1a7fc2c02e90441cabe.jpg', '117.24', 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(244, 'demo18.jpg', '3091eefa8799ae4abf9c79ffc9a04f63.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/3091eefa8799ae4abf9c79ffc9a04f63.jpg', '135.23', 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(245, 'demo19.jpg', '4cd12629b77694a360e2c7acf225a8c1.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/4cd12629b77694a360e2c7acf225a8c1.jpg', '131.27', 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(246, 'demo21.jpg', 'b1779f0b6d5cec2a9f80498cb7c03f2a.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/b1779f0b6d5cec2a9f80498cb7c03f2a.jpg', '142.55', 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(247, 'demo22.jpg', '008215814b83d63f405941638eec4231.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/008215814b83d63f405941638eec4231.jpg', '120.96', 1, 0, '2020-11-01 11:50:01', '2020-11-01 11:50:01'),
(248, '300_1.jpg', '411533773a641da05c42847d78f88ad2.jpg', 'http://192.168.1.59/mbmc/uploads/lab/411533773a641da05c42847d78f88ad2.jpg', '83.47', 1, 0, '2020-11-26 12:32:07', '2020-11-26 12:32:07'),
(249, '300_2.jpg', 'e1255f76d96e3aa0cbaa6f9b3c64083f.jpg', 'http://192.168.1.59/mbmc/uploads/lab/e1255f76d96e3aa0cbaa6f9b3c64083f.jpg', '82.67', 1, 0, '2020-11-26 12:32:07', '2020-11-26 12:32:07'),
(250, '300_2.jpg', 'e710bc1b78628b65e7da1e3b8637cdae.jpg', 'http://192.168.1.59/mbmc/uploads/lab/e710bc1b78628b65e7da1e3b8637cdae.jpg', '82.67', 1, 0, '2020-11-26 12:32:07', '2020-11-26 12:32:07'),
(251, '300_7.jpg', '5929f6897e07f8d92f1c68008bdf6edc.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/5929f6897e07f8d92f1c68008bdf6edc.jpg', '75.22', 1, 0, '2020-11-26 17:24:45', '2020-11-26 17:24:45'),
(252, '300_11.jpg', '2e0dbbbfd202487203fad0600dabdc1f.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/2e0dbbbfd202487203fad0600dabdc1f.jpg', '80.46', 1, 0, '2020-11-26 17:24:45', '2020-11-26 17:24:45'),
(253, '300_13.jpg', 'd253cdb9c8b75694e811e18c844e62b6.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d253cdb9c8b75694e811e18c844e62b6.jpg', '108.11', 1, 0, '2020-11-26 17:24:45', '2020-11-26 17:24:45'),
(254, '300_1.jpg', '1a1d2796bc3ac87b0c49743c28925943.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1a1d2796bc3ac87b0c49743c28925943.jpg', '83.47', 1, 0, '2020-11-26 17:26:08', '2020-11-26 17:26:08'),
(255, '300_18.jpg', 'd41e33d625edfbac1797e555f22c9ab1.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d41e33d625edfbac1797e555f22c9ab1.jpg', '55.05', 1, 0, '2020-11-26 17:26:08', '2020-11-26 17:26:08'),
(256, '300_20.jpg', '3bb752b9b764b4204e48c28f35f64c4a.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/3bb752b9b764b4204e48c28f35f64c4a.jpg', '75.78', 1, 0, '2020-11-26 17:26:08', '2020-11-26 17:26:08'),
(257, '100_14.jpg', 'dd3ecf6f0e229b892ae3814ba6338ab3.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/dd3ecf6f0e229b892ae3814ba6338ab3.jpg', '16.85', 1, 0, '2020-12-03 11:32:54', '2020-12-03 11:32:54'),
(258, '300_2.jpg', '4c9d6d319bd1dd2d066fa93313027ec9.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/4c9d6d319bd1dd2d066fa93313027ec9.jpg', '82.67', 1, 0, '2020-12-03 11:32:54', '2020-12-03 11:32:54'),
(259, '300_3.jpg', '4bd9fc4897062fec694922f0c542b636.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/4bd9fc4897062fec694922f0c542b636.jpg', '80.05', 1, 0, '2020-12-03 11:32:54', '2020-12-03 11:32:54'),
(260, '100_14.jpg', '1f3b07537b5676a8e782f2c609b51146.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/1f3b07537b5676a8e782f2c609b51146.jpg', '16.85', 1, 0, '2020-12-03 15:13:50', '2020-12-03 15:13:50'),
(261, '100_6.jpg', 'bd0044215a493ba1ba12310fa71ce8d2.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/bd0044215a493ba1ba12310fa71ce8d2.jpg', '14.82', 1, 0, '2020-12-03 15:14:13', '2020-12-03 15:14:13'),
(262, '100_7.jpg', '8c479bcba022d8f14997c7f99bf38304.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/8c479bcba022d8f14997c7f99bf38304.jpg', '13.36', 1, 0, '2020-12-03 15:14:37', '2020-12-03 15:14:37'),
(263, '300_11.jpg', '0a075918d65c819faf787e0b46b305f2.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/0a075918d65c819faf787e0b46b305f2.jpg', '80.46', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(264, '300_14.jpg', 'b5add0e097fecb415fad26f8b16e9ae1.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/b5add0e097fecb415fad26f8b16e9ae1.jpg', '87.93', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(265, '300_3.jpg', '28f9ee22833bfd988fcbd0035787b99f.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/28f9ee22833bfd988fcbd0035787b99f.jpg', '80.05', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(266, '100_13.jpg', '774e77037e96f0ae4c30a943a506fbeb.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/774e77037e96f0ae4c30a943a506fbeb.jpg', '17.49', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(267, '300_6.jpg', '72e0e1ef462517f47ad552c38999938e.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/72e0e1ef462517f47ad552c38999938e.jpg', '86.33', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(268, '300_3.jpg', '22026f1fced6a4b59ec9fb0ed0f0b175.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/22026f1fced6a4b59ec9fb0ed0f0b175.jpg', '80.05', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(269, '300_23.jpg', '2373de83711a86bed93ab512c2188f5f.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/2373de83711a86bed93ab512c2188f5f.jpg', '56.1', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(270, '300_11.jpg', 'f82120bff412dc3c08111ef0f1b8484a.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/f82120bff412dc3c08111ef0f1b8484a.jpg', '80.46', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(271, '300_9.jpg', 'ea508e7cae5713ef171b2857b213f20e.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/ea508e7cae5713ef171b2857b213f20e.jpg', '66.84', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(272, '300_21.jpg', 'd70ef171b318eb7a0be6305d73f4d52b.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/d70ef171b318eb7a0be6305d73f4d52b.jpg', '83.2', 1, 0, '2020-12-03 15:42:49', '2020-12-03 15:42:49'),
(273, '100_10.jpg', 'b64bf31c846ef1b48efa75eb0c7ac3ee.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/b64bf31c846ef1b48efa75eb0c7ac3ee.jpg', '12.44', 1, 0, '2020-12-03 15:43:17', '2020-12-03 15:43:17'),
(274, '300_2.jpg', '249d14fddfb123a51ab31aaae81a8f1e.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/249d14fddfb123a51ab31aaae81a8f1e.jpg', '82.67', 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(275, '300_3.jpg', 'dc66e7c96a2eea3e44bf7d93e6c7bdb3.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/dc66e7c96a2eea3e44bf7d93e6c7bdb3.jpg', '80.05', 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(276, '300_14.jpg', '2f488d1768e6c7c31ef8b105c9551f8e.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/2f488d1768e6c7c31ef8b105c9551f8e.jpg', '87.93', 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(277, '300_5.jpg', '55cdc8286427574ad38fd4729769f65b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/55cdc8286427574ad38fd4729769f65b.jpg', '96.19', 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(278, '300_4.jpg', '4c95bb9a1688a9c11c27688de3856164.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/4c95bb9a1688a9c11c27688de3856164.jpg', '63.73', 1, 0, '2020-12-04 15:52:42', '2020-12-04 15:52:42'),
(279, '100_14.jpg', '7adf0342e4b26f4a2edd90a609b729d9.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/7adf0342e4b26f4a2edd90a609b729d9.jpg', '16.85', 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(280, '300_11.jpg', 'f6515e0b4c1e263108bfcc6845f0bc3b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/f6515e0b4c1e263108bfcc6845f0bc3b.jpg', '80.46', 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(281, '300_4.jpg', 'afa5b7498343afa53447b558dd63054b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/afa5b7498343afa53447b558dd63054b.jpg', '63.73', 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(282, '300_8.jpg', '24145e0226c59d8de0c7bc04dfe600e4.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/24145e0226c59d8de0c7bc04dfe600e4.jpg', '82.96', 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(283, '300_15.jpg', '0e27bebe690085587b3415173208099b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/0e27bebe690085587b3415173208099b.jpg', '84.15', 1, 0, '2020-12-04 15:55:40', '2020-12-04 15:55:40'),
(284, '100_14.jpg', 'c08e7475152a20b849eb5083b090770b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c08e7475152a20b849eb5083b090770b.jpg', '16.85', 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(285, '100_9.jpg', '95cdcc326afd77fd486b6665c0f42cc8.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/95cdcc326afd77fd486b6665c0f42cc8.jpg', '11.37', 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(286, '100_5.jpg', 'f85e91ddc12ca7cc21b7fea3195bf8e5.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/f85e91ddc12ca7cc21b7fea3195bf8e5.jpg', '16.68', 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(287, '100_6.jpg', 'bccc0cc2dc68a36ab25750b4e4940e88.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/bccc0cc2dc68a36ab25750b4e4940e88.jpg', '14.82', 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(288, '300_1.jpg', 'b99dda940c8ed1e705dc84ee6ffae6ba.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/b99dda940c8ed1e705dc84ee6ffae6ba.jpg', '83.47', 1, 0, '2020-12-04 15:58:21', '2020-12-04 15:58:21'),
(289, '100_14.jpg', '80d9705b73fa22140ebef113d012837c.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/80d9705b73fa22140ebef113d012837c.jpg', '16.85', 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(290, '100_2.jpg', '671c9d5a24c8d438a20661e245065e59.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/671c9d5a24c8d438a20661e245065e59.jpg', '12.75', 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(291, '300_4.jpg', '35ecf125994d0cf5dee7677cfe98e4d0.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/35ecf125994d0cf5dee7677cfe98e4d0.jpg', '63.73', 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(292, '300_6.jpg', 'a6ce9ed5446d4869f81b7ede82d23b4c.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a6ce9ed5446d4869f81b7ede82d23b4c.jpg', '86.33', 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(293, '300_11.jpg', 'a346d4438cf24f9cffa6ed696a15a327.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a346d4438cf24f9cffa6ed696a15a327.jpg', '80.46', 1, 0, '2020-12-04 16:00:35', '2020-12-04 16:00:35'),
(294, '100_13.jpg', '3a719f4b24c6d03ab5b7a9d1426e4b99.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/3a719f4b24c6d03ab5b7a9d1426e4b99.jpg', '17.49', 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(295, '300_12.jpg', '1de4db9b58d71e4f709346e3f4079fc5.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1de4db9b58d71e4f709346e3f4079fc5.jpg', '80.58', 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(296, '300_10.jpg', '636b0d450675dfc91ff5fa954422792a.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/636b0d450675dfc91ff5fa954422792a.jpg', '75.07', 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(297, '300_14.jpg', '34d3065e5fdc538bd3d47a94271ea7b9.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/34d3065e5fdc538bd3d47a94271ea7b9.jpg', '87.93', 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(298, '300_1.jpg', '5a520fb1ad7c6283e8e5bec07f06b011.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/5a520fb1ad7c6283e8e5bec07f06b011.jpg', '83.47', 1, 0, '2020-12-04 16:20:06', '2020-12-04 16:20:06'),
(299, '300_1.jpg', '4ed2c3c64c21d5049667783308642890.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/4ed2c3c64c21d5049667783308642890.jpg', '83.47', 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(300, '300_11.jpg', 'f4017730913b1fae6291ae34dae7f853.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/f4017730913b1fae6291ae34dae7f853.jpg', '80.46', 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(301, '300_13.jpg', '381a41fea2116f04a2c01b364e55907c.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/381a41fea2116f04a2c01b364e55907c.jpg', '108.11', 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(302, '300_13.jpg', 'c874a6e1b50961d363bc1bd7d39cc329.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c874a6e1b50961d363bc1bd7d39cc329.jpg', '108.11', 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(303, '300_4.jpg', 'd1b172dc2b8cdb3f059825d8d8e0e3e2.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d1b172dc2b8cdb3f059825d8d8e0e3e2.jpg', '63.73', 1, 0, '2020-12-04 16:31:03', '2020-12-04 16:31:03'),
(304, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'a33108c1ec7a58593d2cd03aaf9b3ba9.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/a33108c1ec7a58593d2cd03aaf9b3ba9.pdf', '2.96', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(305, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '653069e5eb7ff6bd445a7195546991d4.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/653069e5eb7ff6bd445a7195546991d4.pdf', '2.96', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(306, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', 'bffb08170465cd8206d8362621cf15f8.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/bffb08170465cd8206d8362621cf15f8.pdf', '305.44', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(307, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '737de7425302bc0f229b57ff6fd49b97.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/737de7425302bc0f229b57ff6fd49b97.pdf', '305.44', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(308, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '4c5e70acd19c22ba3e2c0e7e06514048.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/4c5e70acd19c22ba3e2c0e7e06514048.pdf', '2.96', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(309, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'a7f536d0f60d1701bb5b8dc28f2eb032.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/a7f536d0f60d1701bb5b8dc28f2eb032.pdf', '2.96', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(310, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'b2cd304563df2bc9f10aa2716e13b0fe.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/b2cd304563df2bc9f10aa2716e13b0fe.pdf', '2.96', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(311, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', 'ac140c51bf9c0bcaabe8048a62c14c72.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/ac140c51bf9c0bcaabe8048a62c14c72.pdf', '305.44', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(312, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '4ec68170ebfd1bbab8f9e04b81c2409b.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/4ec68170ebfd1bbab8f9e04b81c2409b.pdf', '305.44', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(313, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '1f3795055fc8e51ce2a4d178ffa042a2.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/1f3795055fc8e51ce2a4d178ffa042a2.pdf', '305.44', 1, 0, '2020-12-04 17:15:59', '2020-12-04 17:15:59'),
(314, '100_14.jpg', '89e7568bb2142652bed71d4871f84881.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/89e7568bb2142652bed71d4871f84881.jpg', '16.85', 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(315, '300_11.jpg', 'c1207846562c13d80c62d50e4568d103.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c1207846562c13d80c62d50e4568d103.jpg', '80.46', 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(316, '300_11.jpg', '33bf0bbb22418cc18ae925d53f4b69ea.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/33bf0bbb22418cc18ae925d53f4b69ea.jpg', '80.46', 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(317, '300_13.jpg', 'ca534fdb18512b470ab2832ce116e955.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/ca534fdb18512b470ab2832ce116e955.jpg', '108.11', 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(318, '300_4.jpg', '0abb023bb09e1d8aca52b8a9c4c66937.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/0abb023bb09e1d8aca52b8a9c4c66937.jpg', '63.73', 1, 0, '2020-12-04 19:29:40', '2020-12-04 19:29:40'),
(319, 'demo14.jpg', '33df5656a1b69eafcd22f072b11aae06.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/33df5656a1b69eafcd22f072b11aae06.jpg', '125.4', 1, 0, '2020-12-04 20:22:15', '2020-12-04 20:22:15'),
(320, 'demo18.jpg', '5d64b19cd30f8b8ae081639a45846942.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/5d64b19cd30f8b8ae081639a45846942.jpg', '135.23', 1, 0, '2020-12-04 20:24:04', '2020-12-04 20:24:04'),
(321, 'demo21.jpg', '3866e18794c4c6ef12277a0b40bba1e7.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/3866e18794c4c6ef12277a0b40bba1e7.jpg', '142.55', 1, 0, '2020-12-04 20:24:19', '2020-12-04 20:24:19'),
(322, 'demo17.jpg', '791782f5c2efacf761fb0f7b58353388.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/791782f5c2efacf761fb0f7b58353388.jpg', '117.24', 1, 0, '2020-12-04 20:24:19', '2020-12-04 20:24:19'),
(323, 'demo3.jpg', 'd153c00ecf74469c1dfe09391639d2a5.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d153c00ecf74469c1dfe09391639d2a5.jpg', '152.29', 1, 0, '2020-12-04 20:24:19', '2020-12-04 20:24:19'),
(324, 'demo22.jpg', 'bd87dcde6bcb33fa7831d56486b1cbf6.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/bd87dcde6bcb33fa7831d56486b1cbf6.jpg', '120.96', 1, 0, '2020-12-04 20:24:19', '2020-12-04 20:24:19'),
(325, 'demo1.jpg', '0a1fe3b24bf73e6e6c2e7d410c7e1d98.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/0a1fe3b24bf73e6e6c2e7d410c7e1d98.jpg', '154.14', 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(326, 'demo2.jpg', 'c44658a4bf6c257bbbddc8e6762d44c4.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c44658a4bf6c257bbbddc8e6762d44c4.jpg', '135.47', 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(327, 'demo3.jpg', 'abfd7a9cb35c47c4cff0e2ec5f7d9fa7.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/abfd7a9cb35c47c4cff0e2ec5f7d9fa7.jpg', '152.29', 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(328, 'demo4.jpg', 'b5eafdb5cd59cc0614f90d8321f56bc9.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/b5eafdb5cd59cc0614f90d8321f56bc9.jpg', '148.79', 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(329, 'demo5.jpg', '1a393e3f33d30b8f2f87c3f6bfec1312.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1a393e3f33d30b8f2f87c3f6bfec1312.jpg', '142.4', 1, 0, '2020-12-04 20:27:32', '2020-12-04 20:27:32'),
(330, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'ee53b13c7b922cfd75aabb7db5ad5fda.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/ee53b13c7b922cfd75aabb7db5ad5fda.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(331, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', 'd0f26f7cb729832af73a47c19eba844b.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/d0f26f7cb729832af73a47c19eba844b.pdf', '305.44', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(332, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'cc581c28d75cb278e3c0381c335bfca0.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/cc581c28d75cb278e3c0381c335bfca0.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(333, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '14e86025cd3d9a97910f8d16ae36191b.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/14e86025cd3d9a97910f8d16ae36191b.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(334, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '2fd253dcdf286b2268df46de49491602.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/2fd253dcdf286b2268df46de49491602.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(335, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'd588bfa21624f6185a3637d2ecf72b92.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/d588bfa21624f6185a3637d2ecf72b92.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(336, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'bed0c585b82e7a0f3de35720c155309e.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/bed0c585b82e7a0f3de35720c155309e.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(337, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'cdf8856e80a61b1bda46c42f0d51de67.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/cdf8856e80a61b1bda46c42f0d51de67.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(338, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '999830d6cfac1417c0a63e3c19ed99ad.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/999830d6cfac1417c0a63e3c19ed99ad.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(339, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '84ff207786103a4c00e5dff44246317e.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/84ff207786103a4c00e5dff44246317e.pdf', '2.96', 1, 0, '2020-12-05 10:43:10', '2020-12-05 10:43:10'),
(340, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '49a6a3d3f1d055dbb8ad2e0758084db5.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/49a6a3d3f1d055dbb8ad2e0758084db5.pdf', '2.96', 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(341, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '9da58860c494a594497cec2bfc95c6f4.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/9da58860c494a594497cec2bfc95c6f4.pdf', '2.96', 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(342, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '98fc179affb316dc8b2487ad908354f1.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/98fc179affb316dc8b2487ad908354f1.pdf', '2.96', 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(343, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '0569d9b8c8fe8634bde75f16bc7c385b.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/0569d9b8c8fe8634bde75f16bc7c385b.pdf', '305.44', 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(344, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', '872f2887ba0c8ee5b8c4769edaab2850.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/872f2887ba0c8ee5b8c4769edaab2850.pdf', '305.44', 1, 0, '2020-12-05 11:13:47', '2020-12-05 11:13:47'),
(345, 'demo1.jpg', '1830ef22db1d2bcb9b730207d9010c65.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1830ef22db1d2bcb9b730207d9010c65.jpg', '154.14', 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(346, 'demo2.jpg', 'd2744a1bcfccb9981c06b7b0977e0d51.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d2744a1bcfccb9981c06b7b0977e0d51.jpg', '135.47', 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(347, 'demo3.jpg', 'c6a53017363f45c6b357695cc59b718f.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c6a53017363f45c6b357695cc59b718f.jpg', '152.29', 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(348, 'demo4.jpg', '347716ff7c14500d8d2927c36fce3974.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/347716ff7c14500d8d2927c36fce3974.jpg', '148.79', 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(349, 'demo5.jpg', '52f130514421708dfe03c2b69b024855.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/52f130514421708dfe03c2b69b024855.jpg', '142.4', 1, 0, '2020-12-05 17:55:44', '2020-12-05 17:55:44'),
(350, 'demo22.jpg', 'de4da1222762580a2b487835a2340660.jpg', 'http://192.168.1.59/mbmc/uploads/lab/de4da1222762580a2b487835a2340660.jpg', '120.96', 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(351, 'demo21.jpg', 'dd3f0e062dd47352c7bbc8005eea48ec.jpg', 'http://192.168.1.59/mbmc/uploads/lab/dd3f0e062dd47352c7bbc8005eea48ec.jpg', '142.55', 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(352, 'demo20.jpg', 'c404c2d9d252023e5f5734e412c3a8e4.jpg', 'http://192.168.1.59/mbmc/uploads/lab/c404c2d9d252023e5f5734e412c3a8e4.jpg', '133.59', 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(353, 'demo19.jpg', 'f962a3c4e7d048b45fa72557f30f0e7e.jpg', 'http://192.168.1.59/mbmc/uploads/lab/f962a3c4e7d048b45fa72557f30f0e7e.jpg', '131.27', 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(354, 'demo18.jpg', 'a80808521d71923bacbe20d8f9717370.jpg', 'http://192.168.1.59/mbmc/uploads/lab/a80808521d71923bacbe20d8f9717370.jpg', '135.23', 1, 0, '2020-12-05 17:56:55', '2020-12-05 17:56:55'),
(355, 'demo18.jpg', 'b0b7d7c62902740e5ee61048145b8381.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/b0b7d7c62902740e5ee61048145b8381.jpg', '135.23', 1, 0, '2020-12-05 19:57:59', '2020-12-05 19:57:59'),
(356, 'demo16.jpg', '43ca85e403c5b985f13aa8aa46663d9b.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/43ca85e403c5b985f13aa8aa46663d9b.jpg', '134.72', 1, 0, '2020-12-05 19:59:31', '2020-12-05 19:59:31'),
(357, 'demo8.jpg', 'd3a7c56e969f705dbb5649a7fc7ad430.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d3a7c56e969f705dbb5649a7fc7ad430.jpg', '160.96', 1, 0, '2020-12-05 20:00:00', '2020-12-05 20:00:00'),
(358, 'Sanjay_Zamindar_Mumbai_City_7.04_yrs (1).pdf', '80a416f088de79ed1526084af914bf51.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/80a416f088de79ed1526084af914bf51.pdf', '59.11', 1, 0, '2020-12-05 20:00:49', '2020-12-05 20:00:49'),
(359, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'a167b203f6926273564422169199a297.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/a167b203f6926273564422169199a297.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(360, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '36905025b91caf1a85f857f4fb4090ee.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/36905025b91caf1a85f857f4fb4090ee.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(361, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '7c424dab432180a57739a8090eb735e8.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/7c424dab432180a57739a8090eb735e8.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(362, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '97a795d94ab77570bd303e6022214bb2.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/97a795d94ab77570bd303e6022214bb2.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(363, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '99dc644a14a8c038f5a52c2292f1d682.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/99dc644a14a8c038f5a52c2292f1d682.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(364, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', '64547d7ea5a465afd2cec31d16544cbe.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/64547d7ea5a465afd2cec31d16544cbe.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(365, 'd48dc3cc6014b5186ea723639a45b8b8.pdf', 'a2b2b15a0f81bd048b392dc82a4b73bd.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/a2b2b15a0f81bd048b392dc82a4b73bd.pdf', '305.44', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(366, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'b8ad2f7406f808e358f709b45ddf3f99.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/b8ad2f7406f808e358f709b45ddf3f99.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(367, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'a2055d0a51ce29293d976c19d516c947.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/a2055d0a51ce29293d976c19d516c947.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(368, '8ada65fd17e7bcd7dbb9a4f0a58af92f.pdf', 'b62216a778858107d5f0bc6d299536e1.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/b62216a778858107d5f0bc6d299536e1.pdf', '2.96', 1, 0, '2020-12-07 14:51:17', '2020-12-07 14:51:17'),
(369, 'Anurag_Mishra_Mumbai_5.04_yrs.pdf', 'f6c7262f83576d81f82fa1ccf6b8577f.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/f6c7262f83576d81f82fa1ccf6b8577f.pdf', '116.17', 1, 0, '2020-12-07 15:19:11', '2020-12-07 15:19:11'),
(370, 'Chandra_Prakash__Navi_Mumbai_3.07_yrs (1).pdf', '9e8f0bab6e258be19e58e32cfd8353c6.pdf', 'http://192.168.1.59/mbmc/uploads/hospital/9e8f0bab6e258be19e58e32cfd8353c6.pdf', '640', 1, 0, '2020-12-07 15:19:11', '2020-12-07 15:19:11'),
(371, 'sample.pdf', 'df7c5b81d08807714e391c5e3f925a3c.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/df7c5b81d08807714e391c5e3f925a3c.pdf', '2.96', 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(372, 'sample.pdf', 'defc5d83a44b3b1347a9b08efb4cd8c1.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/defc5d83a44b3b1347a9b08efb4cd8c1.pdf', '2.96', 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(373, 'sample.pdf', '76b05b56fa3054a66af5b604bdcb6a63.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/76b05b56fa3054a66af5b604bdcb6a63.pdf', '2.96', 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(374, 'sample.pdf', '3bbdb40ea89016caeda91c536971184d.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/3bbdb40ea89016caeda91c536971184d.pdf', '2.96', 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(375, 'sample.pdf', 'fdd32da7b2da778d61f9376be7ac52fc.pdf', 'http://192.168.1.59/mbmc/uploads/clinic/fdd32da7b2da778d61f9376be7ac52fc.pdf', '2.96', 1, 0, '2020-12-07 17:22:55', '2020-12-07 17:22:55'),
(376, '300_9.jpg', '3d0d8c16931a13614d1298f1add58ec9.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/3d0d8c16931a13614d1298f1add58ec9.jpg', '66.84', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(377, '300_9.jpg', '57ef880d6333b1ba3430641150a03f63.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/57ef880d6333b1ba3430641150a03f63.jpg', '66.84', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(378, '300_13.jpg', 'fc60124372108dc3e339625cf2a59f83.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/fc60124372108dc3e339625cf2a59f83.jpg', '108.11', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(379, '300_4.jpg', 'f2f8bab0e33a44acf494e31c124075c5.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/f2f8bab0e33a44acf494e31c124075c5.jpg', '63.73', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(380, '300_5.jpg', 'b0a47c94c1a5478993b1891c1d955693.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/b0a47c94c1a5478993b1891c1d955693.jpg', '96.19', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(381, '300_6.jpg', '30e49f5ad523a35704c1932aef428c78.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/30e49f5ad523a35704c1932aef428c78.jpg', '86.33', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(382, '300_15.jpg', 'f8bf95c7670e46912316a19f72afa118.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/f8bf95c7670e46912316a19f72afa118.jpg', '84.15', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(383, '300_15.jpg', '93b45d8a69dc3141114482d46dd778f2.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/93b45d8a69dc3141114482d46dd778f2.jpg', '84.15', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(384, '300_21.jpg', '3ee0a26cea35189c60c384fd49edf858.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/3ee0a26cea35189c60c384fd49edf858.jpg', '83.2', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(385, '300_21.jpg', '3789172f139561024f9f238eee3e35f2.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/3789172f139561024f9f238eee3e35f2.jpg', '83.2', 1, 0, '2020-12-07 17:56:52', '2020-12-07 17:56:52'),
(386, '300_11.jpg', 'd1da6dbf309508aa6e600d94936ab060.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/d1da6dbf309508aa6e600d94936ab060.jpg', '80.46', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(387, '300_13.jpg', '7adc44d5602aadc7495600ff4ef63a93.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/7adc44d5602aadc7495600ff4ef63a93.jpg', '108.11', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(388, '300_21.jpg', '252f8fee4b447fcf514015e323848b6f.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/252f8fee4b447fcf514015e323848b6f.jpg', '83.2', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(389, '300_22.jpg', '1ef0aeb08c0c20adceb8eade5f8b2ce3.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/1ef0aeb08c0c20adceb8eade5f8b2ce3.jpg', '92.53', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(390, '300_21.jpg', '6367548ba8c4b45b50d678622694f656.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/6367548ba8c4b45b50d678622694f656.jpg', '83.2', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(391, '300_19.jpg', 'e6bb3bd6b400330c9f40ad7466710b2c.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/e6bb3bd6b400330c9f40ad7466710b2c.jpg', '54.61', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(392, '300_18.jpg', 'faa3a60416f05431a38a1e345c1eccb5.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/faa3a60416f05431a38a1e345c1eccb5.jpg', '55.05', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(393, '300_18.jpg', '3a748f5c4a80826e2c2169822bfd887c.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/3a748f5c4a80826e2c2169822bfd887c.jpg', '55.05', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(394, '300_12.jpg', '9d09d1cb29147823696ef356a7e33e87.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/9d09d1cb29147823696ef356a7e33e87.jpg', '80.58', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(395, '300_12.jpg', 'dc98a33dec76f65ccbb8efa4ce2d22bc.jpg', 'http://192.168.1.59/mbmc/uploads/hospital/dc98a33dec76f65ccbb8efa4ce2d22bc.jpg', '80.58', 1, 0, '2020-12-07 19:25:29', '2020-12-07 19:25:29'),
(396, '100_14.jpg', '9a39fa9526a9f4ba9a7197a2137297d5.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/9a39fa9526a9f4ba9a7197a2137297d5.jpg', '16.85', 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(397, '300_14.jpg', 'b41f55c50cbd167f014ac441f5e53136.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/b41f55c50cbd167f014ac441f5e53136.jpg', '87.93', 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(398, '300_11.jpg', '6d4aba1afaf4feba0fcb8483d3566560.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/6d4aba1afaf4feba0fcb8483d3566560.jpg', '80.46', 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(399, '300_12.jpg', '826a5ad0979492deb03ddfe5178dacef.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/826a5ad0979492deb03ddfe5178dacef.jpg', '80.58', 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(400, '300_15.jpg', '41c07115ec8b7e0d669c142d74ad43fb.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/41c07115ec8b7e0d669c142d74ad43fb.jpg', '84.15', 1, 0, '2020-12-07 20:39:23', '2020-12-07 20:39:23'),
(401, '300_1.jpg', 'd5dabd6ab864d3c1977a4951d57188b7.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/d5dabd6ab864d3c1977a4951d57188b7.jpg', '83.47', 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(402, '300_12.jpg', '5db906eb49b12ac9c6eb2a6318b856b0.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/5db906eb49b12ac9c6eb2a6318b856b0.jpg', '80.58', 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(403, '300_3.jpg', 'aded4d602dd4e983642761989d9d6bf3.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/aded4d602dd4e983642761989d9d6bf3.jpg', '80.05', 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(404, '300_5.jpg', '89507384726b5ac4306260eb54bc186d.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/89507384726b5ac4306260eb54bc186d.jpg', '96.19', 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(405, '100_13.jpg', '3a97bee9cb15d037c9c52202c47aa022.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/3a97bee9cb15d037c9c52202c47aa022.jpg', '17.49', 1, 0, '2020-12-07 21:17:24', '2020-12-07 21:17:24'),
(406, '300_11.jpg', '74f0719351b065f95359c95712044ae6.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/74f0719351b065f95359c95712044ae6.jpg', '80.46', 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(407, '300_13.jpg', 'dad4f534a127dc138ca07b21db5bd9d0.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/dad4f534a127dc138ca07b21db5bd9d0.jpg', '108.11', 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(408, '300_22.jpg', '8978b55278a98dc66a52e805cd195ead.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/8978b55278a98dc66a52e805cd195ead.jpg', '92.53', 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(409, '300_10.jpg', 'f25930f0e935335ab3ea7c705ddc4a60.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/f25930f0e935335ab3ea7c705ddc4a60.jpg', '75.07', 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(410, '300_14.jpg', 'e72db4e302bedc2f3db713b44783571a.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/e72db4e302bedc2f3db713b44783571a.jpg', '87.93', 1, 0, '2020-12-08 09:55:09', '2020-12-08 09:55:09'),
(411, '100_14.jpg', '67c30e89d3d373b54b5233a49d801480.jpg', 'http://192.168.1.59/mbmc/uploads/lab/67c30e89d3d373b54b5233a49d801480.jpg', '16.85', 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(412, '300_13.jpg', 'c24706be9e2e3fba7d490dae6a49bb7e.jpg', 'http://192.168.1.59/mbmc/uploads/lab/c24706be9e2e3fba7d490dae6a49bb7e.jpg', '108.11', 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(413, '300_21.jpg', '0965e0ce9afa94c9265f0e2d866bb55c.jpg', 'http://192.168.1.59/mbmc/uploads/lab/0965e0ce9afa94c9265f0e2d866bb55c.jpg', '83.2', 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(414, '300_19.jpg', 'f23cddef2245246f8260dbe697dae5bd.jpg', 'http://192.168.1.59/mbmc/uploads/lab/f23cddef2245246f8260dbe697dae5bd.jpg', '54.61', 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(415, '300_24.jpg', '40beac3c5801cf95bc30b6bb1fb2708e.jpg', 'http://192.168.1.59/mbmc/uploads/lab/40beac3c5801cf95bc30b6bb1fb2708e.jpg', '51.89', 1, 0, '2020-12-08 09:57:46', '2020-12-08 09:57:46'),
(416, 'Parvana Blank copy.docx', 'dc1bada175feb1b6ed38cccd0d65725f.docx', 'http://192.168.1.59/mbmc/uploads/clinic/dc1bada175feb1b6ed38cccd0d65725f.docx', '483.72', 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(417, 'Parvana Blank copy.docx', '847b92cb345705e16534ff0bd1e8d4ed.docx', 'http://192.168.1.59/mbmc/uploads/clinic/847b92cb345705e16534ff0bd1e8d4ed.docx', '483.72', 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(418, 'Parvana Blank copy.docx', 'c4c8ed4e0ba2db0640d91f5433677a4c.docx', 'http://192.168.1.59/mbmc/uploads/clinic/c4c8ed4e0ba2db0640d91f5433677a4c.docx', '483.72', 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(419, 'Parvana Blank copy.docx', '5af16ae0b1d34995f151f0d58eb7c420.docx', 'http://192.168.1.59/mbmc/uploads/clinic/5af16ae0b1d34995f151f0d58eb7c420.docx', '483.72', 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(420, 'Parvana Blank copy.docx', '52f7b8c81017e1a65cda32318f904be9.docx', 'http://192.168.1.59/mbmc/uploads/clinic/52f7b8c81017e1a65cda32318f904be9.docx', '483.72', 1, 0, '2020-12-08 12:41:55', '2020-12-08 12:41:55'),
(421, 'Parvana Blank copy.docx', '6d7cd9d3510239f11d87b69f3611c5b8.docx', 'http://192.168.1.59/mbmc/uploads/clinic/6d7cd9d3510239f11d87b69f3611c5b8.docx', '483.72', 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(422, 'Parvana Blank copy.docx', '3a2a923be138079df40b2ef8e1db3b89.docx', 'http://192.168.1.59/mbmc/uploads/clinic/3a2a923be138079df40b2ef8e1db3b89.docx', '483.72', 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(423, 'Parvana Blank copy.docx', 'b962bdc17787b7c32a41ab62cdb52f89.docx', 'http://192.168.1.59/mbmc/uploads/clinic/b962bdc17787b7c32a41ab62cdb52f89.docx', '483.72', 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(424, 'Parvana Blank copy.docx', 'e57c14ead905d375ce0ddaa421d708ab.docx', 'http://192.168.1.59/mbmc/uploads/clinic/e57c14ead905d375ce0ddaa421d708ab.docx', '483.72', 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(425, 'Parvana Blank copy.docx', 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'http://192.168.1.59/mbmc/uploads/clinic/ac5fe23863fb6256c30cce06a1ef91fb.docx', '483.72', 1, 0, '2020-12-08 12:49:08', '2020-12-08 12:49:08'),
(426, 'Parvana Blank copy.docx', 'b4160309d7a68f72d38c3f59b9b0c936.docx', 'http://192.168.1.59/mbmc/uploads/clinic/b4160309d7a68f72d38c3f59b9b0c936.docx', '483.72', 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(427, 'Parvana Blank copy.docx', '16b4b42c12b914c0e49dc5b39a7c5ddf.docx', 'http://192.168.1.59/mbmc/uploads/clinic/16b4b42c12b914c0e49dc5b39a7c5ddf.docx', '483.72', 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(428, 'Parvana Blank copy.docx', '4391027e8828b43a569b04e5552f9505.docx', 'http://192.168.1.59/mbmc/uploads/clinic/4391027e8828b43a569b04e5552f9505.docx', '483.72', 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(429, 'Parvana Blank copy.docx', '2263acc93df4c7c27ac0eace74c096b6.docx', 'http://192.168.1.59/mbmc/uploads/clinic/2263acc93df4c7c27ac0eace74c096b6.docx', '483.72', 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(430, 'Parvana Blank copy.docx', '8023f0027103103deb58321e40cac02e.docx', 'http://192.168.1.59/mbmc/uploads/clinic/8023f0027103103deb58321e40cac02e.docx', '483.72', 1, 0, '2020-12-08 13:23:03', '2020-12-08 13:23:03'),
(431, 'Parvana Blank copy.docx', 'ef468341597b51b8914ea41fee4f2ef2.docx', 'http://192.168.1.59/mbmc/uploads/clinic/ef468341597b51b8914ea41fee4f2ef2.docx', '483.72', 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(432, 'Parvana Blank copy.docx', '8a80022f3781f9271554de99d72d6628.docx', 'http://192.168.1.59/mbmc/uploads/clinic/8a80022f3781f9271554de99d72d6628.docx', '483.72', 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(433, 'Parvana Blank copy.docx', 'ee482759584ce3f0fb880c18092d0e1f.docx', 'http://192.168.1.59/mbmc/uploads/clinic/ee482759584ce3f0fb880c18092d0e1f.docx', '483.72', 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(434, 'Parvana Blank copy.docx', '252e8da85ca3cfae458924d63b60520e.docx', 'http://192.168.1.59/mbmc/uploads/clinic/252e8da85ca3cfae458924d63b60520e.docx', '483.72', 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(435, 'Parvana Blank copy.docx', '2b51c998b8c46bba0f9124890e1ab963.docx', 'http://192.168.1.59/mbmc/uploads/clinic/2b51c998b8c46bba0f9124890e1ab963.docx', '483.72', 1, 0, '2020-12-08 14:45:52', '2020-12-08 14:45:52'),
(436, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'd3125c65a9e9931ed3f0da0c90e4d391.docx', 'http://192.168.1.59/mbmc/uploads/lab/d3125c65a9e9931ed3f0da0c90e4d391.docx', '483.72', 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(437, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '0eaf5f87e2c313d8ab5b23879397b820.docx', 'http://192.168.1.59/mbmc/uploads/lab/0eaf5f87e2c313d8ab5b23879397b820.docx', '483.72', 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(438, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '6f65716a316cdfe9bcc269acb7642aae.docx', 'http://192.168.1.59/mbmc/uploads/lab/6f65716a316cdfe9bcc269acb7642aae.docx', '483.72', 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(439, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'a38957c9f24565e264915ee0c0284382.docx', 'http://192.168.1.59/mbmc/uploads/lab/a38957c9f24565e264915ee0c0284382.docx', '483.72', 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(440, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'acc9e7dba32a731730ef5bcd06712b30.docx', 'http://192.168.1.59/mbmc/uploads/lab/acc9e7dba32a731730ef5bcd06712b30.docx', '483.72', 1, 0, '2020-12-08 15:46:48', '2020-12-08 15:46:48'),
(441, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '039245a8b205194d3c1a3f331bcf6fe1.docx', 'http://192.168.1.59/mbmc/uploads/lab/039245a8b205194d3c1a3f331bcf6fe1.docx', '483.72', 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(442, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'fe61e99a98b8c66ab8665a138f118d18.docx', 'http://192.168.1.59/mbmc/uploads/lab/fe61e99a98b8c66ab8665a138f118d18.docx', '483.72', 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(443, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '6662dfcabedf9a30d3ed123a2070b80e.docx', 'http://192.168.1.59/mbmc/uploads/lab/6662dfcabedf9a30d3ed123a2070b80e.docx', '483.72', 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(444, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'dcb653c986f45d2516b0aee37bd63166.docx', 'http://192.168.1.59/mbmc/uploads/lab/dcb653c986f45d2516b0aee37bd63166.docx', '483.72', 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(445, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'c3e7932755975d397ba0b75b95383372.docx', 'http://192.168.1.59/mbmc/uploads/lab/c3e7932755975d397ba0b75b95383372.docx', '483.72', 1, 0, '2020-12-08 16:41:46', '2020-12-08 16:41:46'),
(446, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'c822268790fdf2532fbaa8257be25f25.docx', 'http://192.168.1.59/mbmc/uploads/lab/c822268790fdf2532fbaa8257be25f25.docx', '483.72', 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(447, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', 'a8954cf544eb17fa3bd29b0ee6765a5e.docx', 'http://192.168.1.59/mbmc/uploads/lab/a8954cf544eb17fa3bd29b0ee6765a5e.docx', '483.72', 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(448, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '80754eedd3c6822648e39241f352cdb0.docx', 'http://192.168.1.59/mbmc/uploads/lab/80754eedd3c6822648e39241f352cdb0.docx', '483.72', 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(449, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '7a3934690984f927858040fb61c54a5e.docx', 'http://192.168.1.59/mbmc/uploads/lab/7a3934690984f927858040fb61c54a5e.docx', '483.72', 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(450, 'ac5fe23863fb6256c30cce06a1ef91fb.docx', '1ca4e2fb0bd2fea513642596cd5e9277.docx', 'http://192.168.1.59/mbmc/uploads/lab/1ca4e2fb0bd2fea513642596cd5e9277.docx', '483.72', 1, 0, '2020-12-08 16:59:09', '2020-12-08 16:59:09'),
(451, '300_12.jpg', '398bc886bc895ea1eb4c9fd0cd3478cd.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/398bc886bc895ea1eb4c9fd0cd3478cd.jpg', '80.58', 1, 0, '2020-12-08 17:41:54', '2020-12-08 17:41:54'),
(452, '100_14.jpg', 'a9ef44cd90450a5cf80c2394d536906e.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/a9ef44cd90450a5cf80c2394d536906e.jpg', '16.85', 1, 0, '2020-12-08 17:45:11', '2020-12-08 17:45:11'),
(453, '300_1.jpg', 'e937a81530385cfe371af1f08d8f9d71.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/e937a81530385cfe371af1f08d8f9d71.jpg', '83.47', 1, 0, '2020-12-08 17:45:40', '2020-12-08 17:45:40'),
(454, '300_2.jpg', '1e6a60b83d1163e6bc0282c5d43698ce.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/1e6a60b83d1163e6bc0282c5d43698ce.jpg', '82.67', 1, 0, '2020-12-08 17:46:52', '2020-12-08 17:46:52'),
(455, '100_14.jpg', '31a76e72e7071d9e3d1f5c99298eb10b.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/31a76e72e7071d9e3d1f5c99298eb10b.jpg', '16.85', 1, 0, '2020-12-08 17:52:02', '2020-12-08 17:52:02'),
(456, '300_4.jpg', 'ebcc9337ddb8f9ef2a660c2f7d2f36db.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/ebcc9337ddb8f9ef2a660c2f7d2f36db.jpg', '63.73', 1, 0, '2020-12-08 17:53:34', '2020-12-08 17:53:34'),
(457, '300_3.jpg', 'e02e75576b9e125ee53d00b48929023c.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/e02e75576b9e125ee53d00b48929023c.jpg', '80.05', 1, 0, '2020-12-08 17:53:44', '2020-12-08 17:53:44'),
(458, '300_16.jpg', '80837c152087be42af92a0d93b8ebfc3.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/80837c152087be42af92a0d93b8ebfc3.jpg', '68', 1, 0, '2020-12-08 17:57:39', '2020-12-08 17:57:39'),
(459, '300_12.jpg', 'c3cd12a9667dfba4cda94917ac250b1f.jpg', 'http://192.168.1.59/mbmc/uploads/clinic/c3cd12a9667dfba4cda94917ac250b1f.jpg', '80.58', 1, 0, '2020-12-08 18:09:48', '2020-12-08 18:09:48'),
(460, '300_12.jpg', 'dba7f2624fdd0ed0b0f15489094f81ed.jpg', 'http://192.168.1.59/mbmc/uploads/lab/dba7f2624fdd0ed0b0f15489094f81ed.jpg', '80.58', 1, 0, '2020-12-08 18:45:23', '2020-12-08 18:45:23'),
(461, '300_16.jpg', '5c4210ae47cd55ba9ed4590bbeb0ed08.jpg', 'http://192.168.1.59/mbmc/uploads/lab/5c4210ae47cd55ba9ed4590bbeb0ed08.jpg', '68', 1, 0, '2020-12-08 18:45:28', '2020-12-08 18:45:28'),
(462, '100_13.jpg', '6dfd59b9bf2f695b904c72c954888a90.jpg', 'http://192.168.1.59/mbmc/uploads/hall/6dfd59b9bf2f695b904c72c954888a90.jpg', '17.49', 1, 0, '2020-12-21 16:54:21', '2020-12-21 16:54:21'),
(463, '300_4.jpg', '98af9f0dce29921d0b3a9bc650a71ce1.jpg', 'http://192.168.1.59/mbmc/uploads/hall/98af9f0dce29921d0b3a9bc650a71ce1.jpg', '63.73', 1, 0, '2020-12-21 16:54:21', '2020-12-21 16:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `joint_visit_extentions`
--

CREATE TABLE `joint_visit_extentions` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `to_date` date DEFAULT NULL,
  `length` double DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '1 = joint visit 2 = extention',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL COMMENT '1 = Requested , 2 = Approved , 3 = Rejected',
  `description` text,
  `approved_by` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0:Not deleted, 1: deleted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `joint_visit_extentions`
--

INSERT INTO `joint_visit_extentions` (`id`, `app_id`, `date`, `to_date`, `length`, `type`, `created_at`, `updated_at`, `status`, `description`, `approved_by`, `is_deleted`) VALUES
(1, 13, '2020-11-19', NULL, 3, 1, '2020-11-18 09:58:15', '2020-11-18 10:02:38', 2, 'the flyover has been expanded by 5 meters ', 11, 0),
(2, 15, '2020-11-20', '2020-11-21', NULL, 2, '2020-11-18 10:53:33', '2020-11-18 12:04:56', 2, 'extension test', 12, 1),
(3, 15, '2020-11-21', '2020-11-23', NULL, 2, '2020-11-18 12:04:56', '2020-11-18 12:07:47', 1, 'sadasdas', 0, 1),
(4, 15, '2020-11-21', '2020-11-23', NULL, 2, '2020-11-18 12:07:47', '2020-11-18 12:08:34', 2, 'test 2', 12, 0),
(5, 15, '2020-11-23', NULL, 0, 1, '2020-11-18 12:10:27', '2020-11-18 12:12:05', 3, 'test application for close joint visit ', 12, 1),
(6, 15, '2020-11-23', NULL, 0, 1, '2020-11-18 12:13:06', NULL, 1, NULL, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lab_applications`
--

CREATE TABLE `lab_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(255) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `applicant_qualification` varchar(255) NOT NULL,
  `lab_name` text NOT NULL,
  `lab_address` longtext NOT NULL,
  `lab_telephone_no` varchar(255) NOT NULL,
  `ownership_agreement` int(11) NOT NULL,
  `tax_receipt` int(11) NOT NULL,
  `bio_medical_certificate` int(11) NOT NULL DEFAULT '0',
  `doc_certificate` int(11) NOT NULL,
  `aadhaar_card` int(11) NOT NULL DEFAULT '0',
  `user_image` int(11) NOT NULL DEFAULT '0',
  `bio_medical_valid_date` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_type` int(11) NOT NULL COMMENT '1 = new application , 2 = renewal application',
  `health_officer` int(11) NOT NULL DEFAULT '0',
  `cold_chain_facilities` text NOT NULL,
  `no_of_expiry_certificate` varchar(255) DEFAULT NULL,
  `date_of_expiry_certificate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_applications`
--

INSERT INTO `lab_applications` (`id`, `app_id`, `applicant_name`, `applicant_email_id`, `applicant_mobile_no`, `applicant_alternate_no`, `applicant_address`, `applicant_qualification`, `lab_name`, `lab_address`, `lab_telephone_no`, `ownership_agreement`, `tax_receipt`, `bio_medical_certificate`, `doc_certificate`, `aadhaar_card`, `user_image`, `bio_medical_valid_date`, `status`, `is_deleted`, `created_at`, `updated_at`, `user_id`, `application_type`, `health_officer`, `cold_chain_facilities`, `no_of_expiry_certificate`, `date_of_expiry_certificate`) VALUES
(1, 104, 'Sonam sharma', 'sonamsharma@yopmail.com', 'Magna labor', 'In corrupti laborum', 'or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example', 'Id qui eius rerum s', 'Sonam sharma', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, ', '+1 (934) 338-5266', 350, 351, 353, 352, 354, 0, '2020-12-15', 81, 0, '2020-12-05 17:56:55', '2020-12-05 18:02:27', 23, 1, 3, 'or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example', NULL, NULL),
(2, 115, 'Hell bond hacker', 'hellbondhacker@yopmail.com', '+1 (954) 30', '+1 (954) 306-1223', 'Laborum A incidunt', 'CEH', 'Hell bond hacker', 'Veritatis fugit eli', '+1 (954) 306-1223', 411, 412, 414, 413, 415, 461, '2020-12-03', 85, 0, '2020-12-08 09:57:46', '2020-12-08 18:30:30', 23, 2, 3, 'Incidunt amet non ', '123', '2020-12-23'),
(3, 120, 'mANESH k', 'suman.kattimani@aaravsoftware', '1234567890', '', 'You need not to develop your website in any regional language like Marathi. there are some ready made plug-ins & tools available for Multi-Language website. e.g. if you develop your website in WordPress then it suggest various plug-ins to set your website in multi-language.Juhu ', 'Mbbs ', 'Dr.Manesh ', 'You need not to develop your website in any regional language like Marathi. there are some ready made plug-ins & tools available for Multi-Language website. e.g. if you develop your website in WordPress then it suggest various plug-ins to set your website in multi-language.', '12345678', 436, 437, 439, 438, 440, 0, '0000-00-00', 81, 0, '2020-12-08 15:46:48', '2020-12-08 15:52:12', 22, 1, 3, '', NULL, NULL),
(4, 121, 'hgdhgd', 'suman.kattimani@aaravsoftware', 'dfhdfhdfh', 'dfhdfhd', 'sgsdhsdfdfhg', 'hdhf', 'sfgdfgd', 'dgdhgdhdfhdhdhdfh', 'dfghdfd', 441, 442, 444, 443, 445, 0, '0000-00-00', 81, 0, '2020-12-08 16:41:46', '2020-12-08 16:42:48', 22, 1, 3, '', NULL, NULL),
(5, 122, 'Dr. Tom', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'sddfhdhdfhdfhdfhdrhdfghdfhjdfbhdfhrhfghrf ', 'mbbs', 'Dr. Tom ', 'jhsjlksjvsaj', '1234567890', 446, 447, 449, 448, 450, 0, '0000-00-00', 81, 0, '2020-12-08 16:59:09', '2020-12-08 17:00:29', 22, 2, 3, '', 'MH/THM/MBMC/12', '2020-12-08');

-- --------------------------------------------------------

--
-- Table structure for table `lab_staff`
--

CREATE TABLE `lab_staff` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `sr_number_lab_Staff` int(11) DEFAULT NULL,
  `name_lab_Staff` varchar(255) DEFAULT NULL,
  `designation_lab_Staff` int(11) DEFAULT NULL,
  `qualification_lab_Staff` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lab_staff`
--

INSERT INTO `lab_staff` (`id`, `app_id`, `sr_number_lab_Staff`, `name_lab_Staff`, `designation_lab_Staff`, `qualification_lab_Staff`, `created_at`) VALUES
(57, 1, 83, 'Brynne England', 4, '2', '2020-12-08 05:06:39'),
(58, 1, 356, 'Whilemina Clemons', 2, '2', '2020-12-08 05:06:39'),
(59, 1, 657, 'Fuller Washington', 6, '1', '2020-12-08 05:06:39'),
(60, 3, 1, 'Manesh', 1, '1', '2020-12-08 10:16:49'),
(61, 4, 1, 'grg', 1, '1', '2020-12-08 11:11:46'),
(62, 5, 1, 'kjfgdf`', 1, '1', '2020-12-08 11:29:09'),
(68, 2, 551, 'Mikayla Moss', 4, '2', '2020-12-08 13:15:28'),
(69, 2, 551, 'Mikayla Moss', 3, '1', '2020-12-08 13:15:28'),
(70, 2, 551, 'Mikayla Moss', 4, '3', '2020-12-08 13:15:28'),
(71, 2, 551, 'Mikayla Moss', 3, '3', '2020-12-08 13:15:28'),
(72, 2, 551, 'Mikayla Moss', 5, '1', '2020-12-08 13:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `latter_generation`
--

CREATE TABLE `latter_generation` (
  `letter_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `latter_type_id` int(11) NOT NULL COMMENT 'latter type id is forgin key of latter table',
  `status` int(11) NOT NULL DEFAULT '1',
  `file_name` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `created_by` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `latter_generation`
--

INSERT INTO `latter_generation` (`letter_id`, `dept_id`, `app_id`, `latter_type_id`, `status`, `file_name`, `is_deleted`, `created_by`, `updated_by`) VALUES
(1, 1, 9, 1, 1, 0, 0, '2020-11-10 08:21:39', NULL),
(2, 1, 10, 1, 1, 0, 0, '2020-11-10 08:34:57', NULL),
(3, 1, 9, 1, 1, 0, 0, '2020-11-10 08:52:45', NULL),
(4, 1, 9, 1, 1, 0, 0, '2020-11-10 08:56:16', NULL),
(5, 1, 9, 1, 1, 0, 0, '2020-11-10 09:23:55', NULL),
(6, 1, 9, 2, 1, 0, 0, '2020-11-10 09:37:44', NULL),
(7, 1, 9, 1, 1, 0, 0, '2020-11-10 09:46:49', NULL),
(8, 1, 9, 2, 1, 0, 0, '2020-11-10 09:50:09', NULL),
(9, 1, 12, 1, 1, 0, 0, '2020-11-18 07:39:59', NULL),
(10, 1, 12, 2, 1, 0, 0, '2020-11-18 07:53:47', NULL),
(11, 1, 13, 1, 1, 0, 0, '2020-11-18 09:47:31', NULL),
(12, 1, 13, 2, 1, 0, 0, '2020-11-18 09:53:37', NULL),
(13, 1, 13, 5, 1, 0, 0, '2020-11-18 10:02:38', NULL),
(14, 1, 14, 1, 1, 0, 0, '2020-11-18 10:14:44', NULL),
(15, 1, 14, 2, 1, 0, 0, '2020-11-18 10:16:45', NULL),
(16, 1, 14, 2, 1, 0, 0, '2020-11-18 10:20:03', NULL),
(17, 1, 15, 1, 1, 0, 0, '2020-11-18 10:42:57', NULL),
(18, 1, 15, 2, 1, 0, 0, '2020-11-18 10:51:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `latter_table`
--

CREATE TABLE `latter_table` (
  `id` int(11) NOT NULL,
  `latter_name` varchar(100) NOT NULL,
  `latter_key` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `latter_table`
--

INSERT INTO `latter_table` (`id`, `latter_name`, `latter_key`, `status`, `created_at`, `updated_by`) VALUES
(1, 'Demand Note Letter\n', NULL, 1, '2020-10-12 11:29:34', '2020-10-12 11:30:35'),
(2, 'Permission Letter\n', 'permission_letter', 1, '2020-10-12 11:29:58', '2020-10-14 12:36:23'),
(5, 'Joint Visit Format Letter', 'joint_visit', 1, '2020-10-12 11:31:11', '2020-10-15 07:15:57'),
(6, 'Extension request Approval Letter', 'extension_approvel', 1, '2020-10-12 11:31:11', '2020-10-16 06:20:08');

-- --------------------------------------------------------

--
-- Table structure for table `licdates`
--

CREATE TABLE `licdates` (
  `date_id` int(11) NOT NULL,
  `lic_id` int(11) NOT NULL,
  `license_no` varchar(25) NOT NULL,
  `renewalDate` date NOT NULL,
  `expiryDate` date NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 2: deactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lic_data`
--

CREATE TABLE `lic_data` (
  `data_id` int(11) NOT NULL,
  `temp_lic_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT '1: expiry license, 2: aadhar, 3: pan',
  `file_name` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0: Active, 1: Deactive',
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lic_type`
--

CREATE TABLE `lic_type` (
  `lic_type_id` int(11) NOT NULL,
  `lic_name` varchar(25) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lic_type`
--

INSERT INTO `lic_type` (`lic_type_id`, `lic_name`, `is_deleted`, `status`, `date_added`) VALUES
(1, 'Show Maker', 0, 1, '2020-04-16 12:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `mandap_applications`
--

CREATE TABLE `mandap_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(11) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `booking_address` text NOT NULL,
  `reason` text NOT NULL,
  `booking_date` datetime NOT NULL,
  `mandap_size` varchar(11) NOT NULL,
  `id_proof_id` int(11) NOT NULL,
  `request_letter_id` int(11) NOT NULL,
  `police_noc_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `payment_status` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `fk_ward_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mandap_applications`
--

INSERT INTO `mandap_applications` (`id`, `app_id`, `applicant_name`, `applicant_email_id`, `applicant_mobile_no`, `applicant_alternate_no`, `applicant_address`, `booking_address`, `reason`, `booking_date`, `mandap_size`, `id_proof_id`, `request_letter_id`, `police_noc_id`, `transaction_id`, `payment_status`, `status`, `is_deleted`, `created_at`, `updated_at`, `fk_ward_id`) VALUES
(1, 130, 'Ankit Naik', 'suman.kattimani@aaravsoftware.com', '1234567890', '', 'juhu', 'juhu near aeskon temple', 'Test Event ', '2020-12-11 00:00:00', '5 ', 0, 0, 0, 0, 0, 100, 0, '2020-12-10 17:27:19', '2020-12-11 19:19:53', 11),
(2, 131, 'Iona Boyer', 'ququlagex@yopmail.com', '7896541235', '7896541235', 'Fuga Dolor nulla co', 'Incididunt enim ut s', 'Libero necessitatibu', '2020-12-23 00:00:00', 'Et culpa of', 0, 0, 0, 0, 0, 0, 0, '2020-12-10 17:28:51', '2020-12-11 18:48:15', 11),
(3, 133, 'Nelle Hudson', 'xuguq@yopmail.com', '7896541235', '7896541235', 'Est rerum culpa labo', 'Ducimus sed eius re', 'Reprehenderit tempor', '0000-00-00 00:00:00', 'Quia perspi', 0, 0, 0, 0, 0, 1, 0, '2020-12-11 14:46:53', '2020-12-11 14:46:53', 12),
(4, 134, 'Hollee Henderson', 'rafovus@yopmail.com', '7896541235', '7896541235', 'Ea non quis aspernat', 'Accusantium aute lab', 'Sequi distinctio De', '0000-00-00 00:00:00', 'In soluta c', 0, 0, 0, 0, 0, 1, 0, '2020-12-11 14:48:02', '2020-12-11 14:48:02', 11);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `pay_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL COMMENT 'This id is application auto increment id',
  `dept_id` int(11) NOT NULL,
  `remark_id` int(11) NOT NULL DEFAULT '0',
  `payment_selected` int(11) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `document_path` varchar(100) DEFAULT NULL,
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1 = Requested , 2 = Approved , 3 = Rejected',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`pay_id`, `app_id`, `dept_id`, `remark_id`, `payment_selected`, `amount`, `document_path`, `approved_by`, `is_deleted`, `status`, `created_at`, `updated_at`, `date_added`) VALUES
(1, 9, 1, 0, 2, '92554.4', '02fe0be6df189d4be4a9b2231df0eecb.pdf', 9, 0, 2, '2020-11-10 09:36:11', '2020-11-10 09:37:44', '2020-11-10 15:06:11'),
(2, 9, 1, 0, 2, '92554.4', '9e7d0e1de8a1fcee27d915907d701bb5.pdf', 13, 0, 2, '2020-11-10 09:36:14', '2020-11-10 09:50:09', '2020-11-10 15:06:14'),
(3, 12, 1, 0, 2, '136847.2', '6fb62a32deb8e0bed058194668f2135b.pdf', 9, 0, 2, '2020-11-18 07:51:06', '2020-11-18 07:53:49', '2020-11-18 13:21:06'),
(4, 12, 1, 0, 2, '136847.2', '2f0da9949b85c1f883c57a7adfdd2ab4.pdf', 0, 0, 1, '2020-11-18 07:51:15', NULL, '2020-11-18 13:21:15'),
(5, 13, 1, 0, 2, '5873834', '868bf93042a513b404e1292311473f7c.pdf', 9, 0, 2, '2020-11-18 09:50:20', '2020-11-18 09:53:37', '2020-11-18 15:20:20'),
(6, 14, 1, 0, 2, '2273628', 'dffec4461e5e0123ed4be8f0577ace96.pdf', 9, 0, 2, '2020-11-18 10:16:03', '2020-11-18 10:16:46', '2020-11-18 15:46:03'),
(7, 14, 1, 0, 2, '2273628', 'b5036081146d038d1d8dcce58b32c957.pdf', 9, 0, 2, '2020-11-18 10:16:07', '2020-11-18 10:20:03', '2020-11-18 15:46:07'),
(8, 15, 1, 0, 2, '37957.56', '6be651555212f3411ebec4016be27ea2.pdf', 9, 0, 2, '2020-11-18 10:43:42', '2020-11-18 10:51:21', '2020-11-18 16:13:42'),
(9, 22, 5, 0, 2, '2100', '5cd79b2ba93a95d571836819ccc11288.jpg', 0, 0, 2, '2020-11-22 20:15:00', '2020-11-22 20:19:47', '2020-11-23 01:45:00'),
(14, 24, 5, 0, 2, '101', '683d94cbdc91831406e300f885edaea4.jpg', 0, 0, 2, '2020-11-22 21:19:01', '2020-11-22 21:20:01', '2020-11-23 02:49:01'),
(15, 23, 5, 0, 2, '1100', 'c721d3e7dcbea0c952ea864b14b64576.pdf', 0, 0, 2, '2020-11-23 03:59:55', '2020-11-23 04:07:08', '2020-11-23 09:29:55'),
(16, 34, 5, 0, 2, '1100', '6b19b763c4f0255c50ac8d2875c6f35d.pdf', 0, 0, 2, '2020-11-23 08:27:10', '2020-11-23 08:29:14', '2020-11-23 13:57:10'),
(22, 47, 5, 0, 2, '5100', '32fde7dda6721956748e333ab527488e.jpg', 0, 0, 2, '2020-11-25 13:36:54', '2020-11-25 13:37:19', '2020-11-25 19:06:54'),
(23, 51, 5, 0, 2, '5100', 'b7948feea0c5e4b3e159b157943dfa0d.jpg', 0, 0, 2, '2020-11-26 07:05:58', '2020-11-26 07:21:19', '2020-11-26 12:35:58'),
(25, 53, 5, 0, 2, '2100', 'e6b0a92ef3312daedf812b97663dc7cd.jpg', 0, 0, 2, '2020-11-26 12:01:20', '2020-11-26 12:01:56', '2020-11-26 17:31:20'),
(26, 99, 5, 0, 2, '2100', '366ef39655ce3c97f2c9d0c629bfe411.jpg', 0, 0, 2, '2020-12-04 15:30:03', '2020-12-04 15:30:28', '2020-12-04 21:00:03'),
(27, 84, 5, 0, 2, '2200', 'f843902b7e1790cf4f823bf74822f4b2.jpg', 0, 0, 2, '2020-12-07 10:39:16', '2020-12-07 10:39:41', '2020-12-07 16:09:16'),
(28, 105, 5, 0, 2, '2200', 'cd693477c0940869570ca185e2175658.pdf', 0, 0, 2, '2020-12-07 10:43:36', '2020-12-07 10:48:33', '2020-12-07 16:13:36'),
(29, 108, 5, 0, 2, '2200', '96ae6e3e47d0ef416e8a2ca5681c337a.pdf', 0, 0, 2, '2020-12-07 12:15:43', '2020-12-07 12:16:35', '2020-12-07 17:45:43'),
(30, 109, 5, 0, 2, '10200', 'da54558c6ac264bf9bf4aa33aa1b1e25.jpg', 0, 0, 2, '2020-12-07 12:30:10', '2020-12-07 12:30:36', '2020-12-07 18:00:10'),
(31, 111, 5, 0, 2, '8200', 'a25c99e6766926ae184928dc20607fb1.jpg', 0, 0, 2, '2020-12-07 14:29:09', '2020-12-07 14:29:52', '2020-12-07 19:59:09'),
(32, 112, 5, 0, 2, '1600', 'e9ab4f9fcb36c4da665a4cbf4eb782c4.jpg', 0, 0, 2, '2020-12-07 15:13:58', '2020-12-07 15:14:26', '2020-12-07 20:43:58'),
(33, 113, 5, 0, 2, '850', '0ecd3312720a24db789105dda08e5c89.jpg', 0, 0, 2, '2020-12-07 16:03:32', '2020-12-07 16:04:36', '2020-12-07 21:33:32'),
(34, 115, 5, 0, 2, '1600', '042e995ae05f635586948e5c4bc324ba.jpg', 0, 0, 2, '2020-12-08 04:30:14', '2020-12-08 04:30:41', '2020-12-08 10:00:14'),
(35, 117, 5, 0, 2, '1200', 'ee97f625f374d3d771f174c1b888dac6.docx', 0, 0, 2, '2020-12-08 07:44:58', '2020-12-08 07:46:01', '2020-12-08 13:14:58'),
(36, 118, 5, 0, 0, '850', NULL, 0, 0, 1, '2020-12-08 08:14:45', NULL, '2020-12-08 13:44:45'),
(37, 114, 5, 0, 2, '1200', '9b9e01d3dc3465957b83a2c470e4ae5c.jpg', 0, 0, 2, '2020-12-08 08:23:44', '2020-12-08 08:25:45', '2020-12-08 13:53:44'),
(38, 119, 5, 0, 2, '850', 'f09c2e61667c33d7b8980878e4af40d5.docx', 0, 0, 2, '2020-12-08 09:20:02', '2020-12-08 09:44:55', '2020-12-08 14:50:02'),
(39, 119, 5, 0, 2, '850', 'f09c2e61667c33d7b8980878e4af40d5.docx', 0, 0, 2, '2020-12-08 09:24:30', '2020-12-08 09:44:55', '2020-12-08 14:54:30'),
(40, 120, 5, 0, 0, '1200', NULL, 0, 0, 1, '2020-12-08 11:09:11', NULL, '2020-12-08 16:39:11'),
(42, 121, 5, 0, 0, '1200', NULL, 0, 0, 1, '2020-12-08 11:20:00', NULL, '2020-12-08 16:50:00'),
(43, 122, 5, 0, 2, '850', '5d554fe7d9ff07d3a029136b88e1982d.docx', 0, 0, 2, '2020-12-08 11:35:41', '2020-12-08 11:37:52', '2020-12-08 17:05:41'),
(44, 123, 5, 0, 0, '2450', NULL, 0, 0, 1, '2020-12-08 12:30:23', NULL, '2020-12-08 18:00:23'),
(45, 124, 5, 0, 2, '3200', '87d273e8260fbef27cbe94215fda6a6b.docx', 0, 0, 2, '2020-12-08 12:50:28', '2020-12-08 12:52:34', '2020-12-08 18:20:28'),
(46, 125, 5, 0, 2, '3200', 'cc660ae463d1da12254ad39bdc70520d.docx', 0, 0, 2, '2020-12-08 13:08:36', '2020-12-08 13:09:27', '2020-12-08 18:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `payment_old`
--

CREATE TABLE `payment_old` (
  `pay_id` int(11) NOT NULL,
  `remark_id` int(11) NOT NULL,
  `payment_selected` int(11) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_old`
--

INSERT INTO `payment_old` (`pay_id`, `remark_id`, `payment_selected`, `amount`, `date_added`, `status`, `is_deleted`) VALUES
(3, 44, 1, '1022', '2020-07-04 17:47:21', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permission_access`
--

CREATE TABLE `permission_access` (
  `access_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `payable_status` int(11) NOT NULL COMMENT '1:payable,2:not payable',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Not Active',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sub_dept_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission_access`
--

INSERT INTO `permission_access` (`access_id`, `dept_id`, `role_id`, `payable_status`, `status`, `date_added`, `updated_at`, `sub_dept_id`) VALUES
(1, 11, 3, 2, 1, '2020-08-28 05:44:59', '2020-08-28 05:44:59', 0),
(2, 11, 15, 2, 1, '2020-08-28 05:44:59', '2020-08-28 05:44:59', 0),
(3, 11, 6, 1, 1, '2020-08-28 05:44:59', '2020-08-28 05:44:59', 0),
(4, 11, 4, 1, 1, '2020-08-28 05:44:59', '2020-08-28 05:44:59', 0),
(5, 3, 3, 2, 2, '2020-08-28 07:33:22', '2020-08-28 07:33:22', 0),
(6, 3, 15, 2, 2, '2020-08-28 07:33:22', '2020-08-28 07:33:22', 0),
(7, 3, 6, 1, 2, '2020-08-28 07:33:22', '2020-08-28 07:33:22', 0),
(8, 3, 4, 1, 2, '2020-08-28 07:33:22', '2020-08-28 07:33:22', 0),
(9, 22, 1, 2, 1, '2020-09-10 07:14:50', '2020-09-10 07:14:50', 0),
(10, 22, 2, 2, 1, '2020-09-10 07:14:50', '2020-09-10 07:14:50', 0),
(11, 1, 3, 2, 2, '2020-09-29 08:38:21', '2020-09-29 08:38:21', 0),
(12, 1, 8, 2, 2, '2020-09-29 08:38:21', '2020-09-29 08:38:21', 0),
(13, 1, 6, 2, 2, '2020-09-29 08:38:21', '2020-09-29 08:38:21', 0),
(14, 1, 18, 1, 2, '2020-09-29 08:38:21', '2020-09-29 08:38:21', 0),
(15, 1, 3, 2, 2, '2020-10-03 06:17:45', '2020-10-03 06:17:45', 0),
(16, 1, 8, 2, 2, '2020-10-03 06:17:45', '2020-10-03 06:17:45', 0),
(17, 1, 6, 2, 2, '2020-10-03 06:17:45', '2020-10-03 06:17:45', 0),
(18, 1, 18, 1, 2, '2020-10-03 06:17:45', '2020-10-03 06:17:45', 0),
(19, 1, 3, 2, 2, '2020-10-03 06:18:23', '2020-10-03 06:18:23', 0),
(20, 1, 8, 2, 2, '2020-10-03 06:18:23', '2020-10-03 06:18:23', 0),
(21, 1, 6, 2, 2, '2020-10-03 06:18:23', '2020-10-03 06:18:23', 0),
(22, 1, 18, 1, 2, '2020-10-03 06:18:23', '2020-10-03 06:18:23', 0),
(23, 1, 3, 2, 2, '2020-10-03 06:19:13', '2020-10-03 06:19:13', 0),
(24, 1, 8, 2, 2, '2020-10-03 06:19:14', '2020-10-03 06:19:14', 0),
(25, 1, 6, 2, 2, '2020-10-03 06:19:14', '2020-10-03 06:19:14', 0),
(26, 1, 18, 1, 2, '2020-10-03 06:19:14', '2020-10-03 06:19:14', 0),
(27, 23, 2, 2, 1, '2020-10-03 06:22:17', '2020-10-03 06:22:17', 0),
(28, 1, 3, 2, 2, '2020-10-10 12:25:02', '2020-10-10 12:25:02', 0),
(29, 1, 8, 2, 2, '2020-10-10 12:25:02', '2020-10-10 12:25:02', 0),
(30, 1, 19, 2, 2, '2020-10-10 12:25:02', '2020-10-10 12:25:02', 0),
(31, 1, 18, 1, 2, '2020-10-10 12:25:02', '2020-10-10 12:25:02', 0),
(32, 1, 3, 2, 2, '2020-10-12 09:18:43', '2020-10-12 09:18:43', 0),
(33, 1, 8, 2, 2, '2020-10-12 09:18:43', '2020-10-12 09:18:43', 0),
(34, 1, 19, 2, 2, '2020-10-12 09:18:43', '2020-10-12 09:18:43', 0),
(35, 1, 18, 1, 2, '2020-10-12 09:18:43', '2020-10-12 09:18:43', 0),
(36, 1, 3, 2, 2, '2020-10-12 09:43:11', '2020-10-12 09:43:11', 0),
(37, 1, 8, 2, 2, '2020-10-12 09:43:11', '2020-10-12 09:43:11', 0),
(38, 1, 19, 2, 2, '2020-10-12 09:43:11', '2020-10-12 09:43:11', 0),
(39, 1, 20, 1, 2, '2020-10-12 09:43:11', '2020-10-12 09:43:11', 0),
(40, 1, 3, 2, 2, '2020-10-12 09:43:58', '2020-10-12 09:43:58', 0),
(41, 1, 8, 2, 2, '2020-10-12 09:43:58', '2020-10-12 09:43:58', 0),
(42, 1, 19, 2, 2, '2020-10-12 09:43:58', '2020-10-12 09:43:58', 0),
(43, 1, 20, 1, 2, '2020-10-12 09:43:58', '2020-10-12 09:43:58', 0),
(44, 1, 3, 2, 2, '2020-10-12 10:28:21', '2020-10-12 10:28:21', 0),
(45, 1, 8, 2, 2, '2020-10-12 10:28:21', '2020-10-12 10:28:21', 0),
(46, 1, 19, 2, 2, '2020-10-12 10:28:21', '2020-10-12 10:28:21', 0),
(47, 1, 20, 1, 2, '2020-10-12 10:28:21', '2020-10-12 10:28:21', 0),
(48, 1, 3, 2, 2, '2020-10-19 08:00:57', '2020-10-19 08:00:57', 0),
(49, 1, 8, 2, 2, '2020-10-19 08:00:57', '2020-10-19 08:00:57', 0),
(50, 1, 17, 2, 2, '2020-10-19 08:00:57', '2020-10-19 08:00:57', 0),
(51, 1, 16, 1, 2, '2020-10-19 08:00:57', '2020-10-19 08:00:57', 0),
(52, 1, 3, 2, 1, '2020-10-19 08:26:02', '2020-10-19 08:26:02', 0),
(53, 1, 8, 2, 1, '2020-10-19 08:26:02', '2020-10-19 08:26:02', 0),
(54, 1, 17, 2, 1, '2020-10-19 08:26:02', '2020-10-19 08:26:02', 0),
(55, 1, 18, 1, 1, '2020-10-19 08:26:02', '2020-10-19 08:26:02', 0),
(57, 5, 3, 2, 2, '2020-11-22 17:27:43', '2020-11-22 17:27:43', 1),
(58, 5, 22, 2, 2, '2020-11-22 17:27:43', '2020-11-22 17:27:43', 1),
(59, 5, 23, 2, 2, '2020-11-22 17:27:43', '2020-11-22 17:27:43', 1),
(60, 5, 24, 1, 2, '2020-11-22 17:27:43', '2020-11-22 17:27:43', 1),
(61, 3, 3, 2, 2, '2020-11-27 11:07:02', '2020-11-27 11:07:02', 0),
(62, 3, 15, 2, 2, '2020-11-27 11:07:02', '2020-11-27 11:07:02', 0),
(63, 3, 6, 1, 2, '2020-11-27 11:07:02', '2020-11-27 11:07:02', 0),
(64, 3, 25, 1, 2, '2020-11-27 11:07:02', '2020-11-27 11:07:02', 0),
(65, 3, 3, 2, 1, '2020-11-27 11:07:16', '2020-11-27 11:07:16', 0),
(66, 3, 15, 2, 1, '2020-11-27 11:07:16', '2020-11-27 11:07:16', 0),
(67, 3, 6, 1, 1, '2020-11-27 11:07:16', '2020-11-27 11:07:16', 0),
(68, 3, 25, 1, 1, '2020-11-27 11:07:16', '2020-11-27 11:07:16', 0),
(69, 3, 4, 1, 1, '2020-11-27 11:07:16', '2020-11-27 11:07:16', 0),
(70, 15, 26, 2, 2, '2020-12-04 11:59:13', '2020-12-04 11:59:13', 0),
(71, 5, 3, 2, 1, '2020-12-04 12:25:09', '2020-12-04 12:25:09', 0),
(72, 5, 22, 2, 1, '2020-12-04 12:25:09', '2020-12-04 12:25:09', 0),
(73, 5, 23, 2, 1, '2020-12-04 12:25:09', '2020-12-04 12:25:09', 0),
(74, 5, 24, 1, 1, '2020-12-04 12:25:09', '2020-12-04 12:25:09', 0),
(76, 15, 26, 2, 1, '2020-12-05 13:05:47', '2020-12-05 13:05:47', 0),
(77, 12, 3, 2, 2, '2020-12-08 05:11:30', '2020-12-08 05:11:30', 0),
(78, 12, 3, 2, 1, '2020-12-08 05:11:48', '2020-12-08 05:11:48', 0),
(79, 12, 10, 2, 1, '2020-12-08 05:11:48', '2020-12-08 05:11:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `permission_type_pwd`
--

CREATE TABLE `permission_type_pwd` (
  `pt_id` int(11) NOT NULL,
  `permission_type` varchar(100) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Not Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission_type_pwd`
--

INSERT INTO `permission_type_pwd` (`pt_id`, `permission_type`, `date_added`, `status`) VALUES
(1, 'Faults', '2020-10-07 18:07:14', 1),
(2, 'Cable Shifting', '2020-10-07 18:07:14', 1),
(3, 'New Connection', '2020-10-07 18:07:14', 1),
(4, 'Optical Fibre', '2020-10-07 18:07:14', 1),
(5, 'New Cable Laying', '2020-10-07 18:07:14', 1),
(6, 'Gas pipeline connection', '2020-10-14 14:53:44', 1),
(7, 'any other', '2020-10-14 14:53:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `price_master`
--

CREATE TABLE `price_master` (
  `price_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `price_master`
--

INSERT INTO `price_master` (`price_id`, `dept_id`, `sku_id`, `unit_id`, `amount`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 5, 100000, 2, 0, '2020-04-10 09:08:34', '2020-05-13 09:57:36'),
(2, 6, 3, 2, 200000, 1, 0, '2020-04-10 09:08:52', '2020-05-13 10:03:22'),
(3, 6, 2, 5, 10000, 1, 0, '2020-04-10 09:25:14', '2020-05-13 09:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `property_type`
--

CREATE TABLE `property_type` (
  `prop_type_id` int(11) NOT NULL,
  `prop_type_name` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2: Deactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_type`
--

INSERT INTO `property_type` (`prop_type_id`, `prop_type_name`, `date_added`, `status`) VALUES
(1, 'Purchased', '2020-04-24 00:00:00', 1),
(2, 'Leave And License', '2020-04-24 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pwd_applications`
--

CREATE TABLE `pwd_applications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email_id` varchar(255) NOT NULL,
  `applicant_mobile_no` varchar(11) NOT NULL,
  `applicant_alternate_no` varchar(255) NOT NULL,
  `applicant_address` longtext NOT NULL,
  `company_name` int(11) NOT NULL COMMENT 'company name id',
  `company_address` int(11) NOT NULL COMMENT 'Address of company',
  `landline_no` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `name_company_head` varchar(50) NOT NULL,
  `company_head_number` varchar(15) NOT NULL,
  `company_head_designation` varchar(50) NOT NULL,
  `assistant_name` varchar(50) NOT NULL,
  `assistant_number` varchar(15) NOT NULL,
  `assistant_designation` varchar(50) NOT NULL,
  `letter_no` varchar(255) NOT NULL,
  `letter_date` varchar(255) NOT NULL,
  `road_name` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `work_start_date` date DEFAULT NULL,
  `work_end_date` date DEFAULT NULL,
  `days_of_work` int(11) NOT NULL,
  `request_letter_id` int(11) NOT NULL,
  `geo_location_map_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Deactive',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `clerk_approvel_date` datetime DEFAULT NULL,
  `permission_type` int(11) NOT NULL DEFAULT '0',
  `extention_requested` int(11) NOT NULL DEFAULT '0',
  `fk_ward_id` int(11) NOT NULL,
  `reference_no` int(11) NOT NULL,
  `file_closure_status` int(11) NOT NULL DEFAULT '0',
  `is_child_app` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pwd_applications`
--

INSERT INTO `pwd_applications` (`id`, `app_id`, `user_id`, `applicant_name`, `applicant_email_id`, `applicant_mobile_no`, `applicant_alternate_no`, `applicant_address`, `company_name`, `company_address`, `landline_no`, `contact_person`, `name_company_head`, `company_head_number`, `company_head_designation`, `assistant_name`, `assistant_number`, `assistant_designation`, `letter_no`, `letter_date`, `road_name`, `landmark`, `work_start_date`, `work_end_date`, `days_of_work`, `request_letter_id`, `geo_location_map_id`, `created_at`, `updated_at`, `status`, `is_deleted`, `clerk_approvel_date`, `permission_type`, `extention_requested`, `fk_ward_id`, `reference_no`, `file_closure_status`, `is_child_app`) VALUES
(1, 1, 19, 'Nilesh', 'nilesh.more7@gmail.com', '9022513344', '', 'Mumbai', 3, 3, '9022513344', 'Nilesh', 'Nilesh M', '9022513344', 'H O D', ' Nilesh', '9022513344', 'Engineer', '1', '10/21/2020', 'C S M Road', 'Abc Hotel', '2020-10-28', '2020-10-31', 3, 1, 2, '2020-10-21 19:36:50', '2020-10-21 14:06:50', 0, 0, NULL, 3, 0, 0, 0, 0, 0),
(2, 2, 19, 'Nilesh', 'nilesh.more7@gmail.com', '9022513344', '', '604, Runali Chsl, Near Chhota Mhasoba Maidan,', 3, 3, '9022513344', 'Nilesh More', 'N P More', '9022513344', 'H O D', 'Abc', '9022513344', 'Engg', 'TPCL-001', '10/29/2020', 'Silver Park Road', 'Balaji Hotel', '2020-11-04', '2020-11-19', 15, 3, 4, '2020-10-29 13:17:08', '2020-10-29 07:47:08', 0, 0, NULL, 1, 0, 0, 0, 0, 0),
(3, 3, 19, 'Vishal', 'vishal.vanmali1@gmai.com', '9096129160', '', 'Vasai', 8, 8, '9096129160', 'Vishal V', 'Reliance Jio', '9096129160', 'Hod ', 'Jitesh', '9096129160', 'Engineer', '123', '10/29/2020', 'Phatak Road', 'Police Station', '2020-10-30', '2020-11-13', 14, 5, 6, '2020-10-29 17:20:13', '2020-11-02 01:22:23', 8, 0, '2020-11-02 06:52:23', 2, 0, 0, 0, 0, 0),
(4, 4, 19, 'Nilesh M', 'nilesh.more7@gmail.com', '9022513344', '9022513344', '604, Runali Chsl, Near Chhota Mhasoba Maidan,', 3, 3, '9022513344', 'Nilesh More', 'Nilesh More', '9022513344', 'Manager', 'Nilesh P More', '9022513344', 'Engineer', '1234', '11/02/2020', 'Sonali Road', 'School', '2020-11-04', '2020-11-10', 6, 7, 8, '2020-11-02 20:48:22', '2020-11-02 15:19:33', 0, 0, NULL, 3, 0, 0, 0, 0, 0),
(5, 5, 19, 'More', 'nilesh.more7@gmail.com', '0902251334', '', '604, Runali Chsl, Near Chhota Mhasoba Maidan,', 7, 7, '9022513344', 'Nilesh More', 'More N', '0022513344', 'Hod', 'Nilesh More', '0902251344', 'Mgr', 'Tata Power Co. Ltd', '11/01/2020', 'Mari Gold Road', 'Mayor Bunglow', '2020-11-03', '2020-11-20', 17, 9, 10, '2020-11-02 20:56:15', '2020-11-02 15:26:15', 0, 0, NULL, 4, 0, 0, 0, 0, 0),
(6, 6, 19, 'Santosh', 'nilesh.more7@gmail.com', '0902251334', '09920099598', '604, Runali Chsl, Near Chhota Mhasoba Maidan,', 4, 4, '6233726328', 'Nilesh More', 'Ramesh', '0793378137', 'Md', 'Suresh', '8962367942', 'Executive', '12345', '10/16/2020', 'Golden Nest Road', 'Mall', '2020-11-02', '2020-11-24', 22, 11, 12, '2020-11-02 21:03:28', '2020-11-02 15:33:29', 0, 0, NULL, 5, 0, 0, 0, 0, 0),
(7, 7, 19, 'Narendra', 'nilesh.more7@gmail.com', '0902251334', '', '604, Runali Chsl, Near Chhota Mhasoba Maidan,', 1, 1, '7892442229', 'Nilesh More', 'Singh', '7878248723', 'Director', 'Raju', '7844245238', 'Worker', 'dfhk1312', '11/02/2020', 'Navghar Road', 'Hdfc Bank', '2020-11-02', '2020-11-27', 25, 13, 14, '2020-11-02 21:08:57', '2020-11-02 15:38:58', 0, 0, NULL, 6, 0, 0, 0, 0, 0),
(8, 8, 19, 'Raju', 'nilesh.more7@gmail.com', '7344734743', '', 'Thane', 5, 5, '3478478230', 'Anil', 'Gupta', '7845784578', 'E D', 'Lalu', '2489892378', 'Project Coordinator', '122', '11/02/2020', 'Uttan Road', 'Lake', '2020-11-11', '2020-11-20', 9, 15, 16, '2020-11-02 21:12:47', '2020-11-02 10:21:09', 6, 1, '2020-11-02 15:51:10', 2, 0, 0, 0, 0, 0),
(9, 9, 20, 'tom', 'chetnya@aaravsoftware.com', '7897456123', '', 'juhu', 1, 1, '1234567890', 'tom', 'bob', '7894561230', 'director', 'nick', '4567891230', 'assistant', 'pwd-009', '11/08/2020', 'sv road', 'mbmc', '2020-11-10', '2020-11-12', 2, 17, 18, '2020-11-10 13:36:03', '2020-11-10 09:48:40', 38, 0, '2020-11-10 08:10:01', 2, 0, 1, 0, 0, 0),
(10, 10, 22, 'tom', 'smn101296@gmail.com', '1234567890', '', 'juhu', 1, 1, '456789412', 'tom', 'tom', '7894561233', 'director', 'nick', '4569871236', 'assistant', 'pwd-10', '11/09/2020', 'link road', 'abc', '2020-11-10', '2020-11-13', 3, 19, 20, '2020-11-10 14:01:21', '2020-11-10 03:04:54', 38, 0, '2020-11-10 08:32:31', 1, 0, 1, 84375, 0, 0),
(11, 11, 23, 'Test app', 'testapplication@yopmail.com', '9876541238', '9876541235', 'Et cupidatat nisi cu', 7, 7, '9876541238', 'Caesar Cox', 'Faulkner Stevens Inc', '9876541238', 'Edwards and Best Inc', 'Laura Fox', '9876541238', 'Est soluta tempore ', 'Beck Cardenas Plc', '11/17/2020', 'Test app', 'Duis nisi commodi in', '2020-11-24', '2020-11-27', 4, 21, 22, '2020-11-18 11:29:55', '2020-11-18 05:59:55', 0, 0, NULL, 3, 0, 0, 0, 0, 0),
(12, 12, 22, 'Suman K  ', 'smn101296@gmail.com', '1234567890', '', 'juhu', 1, 1, '7894561230', 'Tom', 'Bob', '2314568796', 'Director', 'Tim', '4563217890', 'Assistant', 'pwd-012', '11/16/2020', 'S.V Road', 'DLH Park', '2020-11-18', '2020-11-20', 3, 23, 24, '2020-11-18 12:40:21', '2020-11-18 09:20:14', 38, 0, '2020-11-18 07:12:27', 1, 0, 1, 65439, 1, 0),
(13, 13, 22, 'Suman K ', 'smn101296@gmail.com', '1023456789', '', 'juhu', 3, 3, '4567891230', 'Tom', 'Bob', '4567891230', 'Test', 'nick', '4567891230', 'test jr', 'pwd-013', '11/17/2020', 'link road', 'dlh park', '2020-11-18', '2020-11-25', 8, 25, 26, '2020-11-18 15:10:56', '2020-11-18 04:17:26', 38, 0, '2020-11-18 09:42:40', 2, 0, 1, 35984, 0, 0),
(14, 14, 22, 'Suman K ', 'smn101296@gmail.com', '1023456789', '', 'juhu', 3, 3, '4567891230', 'Tom', 'Bob', '4567891230', 'Test', 'nick', '4567891230', 'test jr', 'pwd-013', '11/17/2020', 'link road', 'dlh park', '2020-11-18', '2020-11-19', 2, 25, 26, '2020-11-18 15:38:17', '2020-11-18 04:44:40', 38, 0, '2020-11-18 10:09:50', 2, 0, 1, 35984, 0, 1),
(15, 15, 22, 'Suman K ', 'smn101296@gmail.com', '1234567890', '', 'juhu', 3, 3, '7894561230', 'tom', 'tim', '4567891230', 'ceo', 'bob', '4563217890', 'associate', 'pwd-15', '11/10/2020', 'link road', 'dlh park', '2020-11-18', '2020-11-20', 3, 27, 28, '2020-11-18 15:53:48', '2020-11-18 12:08:34', 38, 0, '2020-11-18 10:40:22', 2, 0, 1, 91726, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `qualification_master`
--

CREATE TABLE `qualification_master` (
  `qual_id` int(11) NOT NULL,
  `qual_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qualification_master`
--

INSERT INTO `qualification_master` (`qual_id`, `qual_title`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'MBBSs', 1, 0, '2020-04-26 00:00:00', '2020-05-02 16:30:04'),
(2, 'MSC', 1, 0, '2020-04-26 00:00:00', '2020-04-26 00:00:00'),
(3, '12th', 1, 0, '2020-04-26 00:00:00', '2020-04-26 00:00:00'),
(4, 'MS', 1, 0, '2020-04-26 00:00:00', '2020-04-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `req_type`
--

CREATE TABLE `req_type` (
  `req_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `req_type`
--

INSERT INTO `req_type` (`req_id`, `name`, `date_added`, `status`) VALUES
(1, 'Individual', '2020-05-07 00:00:00', 1),
(2, 'Company', '2020-05-07 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `road_information_pwd`
--

CREATE TABLE `road_information_pwd` (
  `ri_id` int(11) NOT NULL,
  `pwd_app_id` int(11) NOT NULL COMMENT 'pwd_application table id',
  `road_type_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `surface_rate` double NOT NULL,
  `start_point` varchar(255) NOT NULL,
  `end_point` varchar(255) NOT NULL,
  `total_length` double NOT NULL,
  `defectlaib_id` int(11) NOT NULL,
  `mul_factor` double NOT NULL,
  `ri_chargers` double DEFAULT NULL,
  `supervision_charges` double DEFAULT NULL,
  `land_rant` double DEFAULT NULL,
  `total_ri_charges` double DEFAULT NULL,
  `security_deposit` double DEFAULT NULL COMMENT 'refundable amount',
  `sgst` double DEFAULT NULL,
  `cgst` double DEFAULT NULL,
  `total_gst` double DEFAULT NULL,
  `grand_total` double DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Deactive',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `road_information_pwd`
--

INSERT INTO `road_information_pwd` (`ri_id`, `pwd_app_id`, `road_type_id`, `role_id`, `surface_rate`, `start_point`, `end_point`, `total_length`, `defectlaib_id`, `mul_factor`, `ri_chargers`, `supervision_charges`, `land_rant`, `total_ri_charges`, `security_deposit`, `sgst`, `cgst`, `total_gst`, `grand_total`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 14, 0, 5500, 'abc', 'xyz', 125, 0, 0, 0, 0, 25000, 25000, 0, 2250, 2250, 4500, NULL, 1, '2020-10-21 19:36:50', NULL),
(2, 2, 1, 0, 9600, 'abc', 'xyz', 10, 0, 0, 0, 0, 2000, 2000, 0, 180, 180, 360, NULL, 1, '2020-10-29 13:17:08', NULL),
(5, 3, 7, 3, 5500, 'Shop No1 ', 'Shop No 2', 4, 3, 2, 44000, 6600, 800, 51400, 4400, 4626, 4626, 9252, NULL, 1, '2020-10-29 17:39:15', NULL),
(6, 3, 1, 3, 9600, 'Shop No5', 'Shop No110', 6, 3, 2, 115200, 17280, 1200, 133680, 11520, 12031.2, 12031.2, 24062.4, NULL, 1, '2020-10-29 17:39:15', NULL),
(8, 4, 3, 0, 11700, 'bldg 1', 'bldg 2', 90, 0, 0, 0, 0, 18000, 18000, 0, 1620, 1620, 3240, NULL, 1, '2020-11-02 20:49:34', NULL),
(9, 5, 2, 0, 2000, 'shop 1', 'rbk school', 700, 0, 0, 0, 0, 140000, 140000, 0, 12600, 12600, 25200, NULL, 1, '2020-11-02 20:56:15', NULL),
(10, 6, 8, 0, 130000, 'Golden Nest Signal', 'Maxus Mall', 500, 0, 0, 0, 0, 100000, 100000, 0, 9000, 9000, 18000, NULL, 1, '2020-11-02 21:03:28', NULL),
(11, 7, 10, 0, 780000, 'Gate 1', 'Gate 2', 200, 0, 0, 0, 0, 40000, 40000, 0, 3600, 3600, 7200, NULL, 1, '2020-11-02 21:08:57', NULL),
(13, 8, 11, 3, 57200, 'shop1', 'shop1', 10, 1, 1.4, 800800, 120120, 2000, 922920, 80080, 83062.8, 83062.8, 166125.6, NULL, 1, '2020-11-02 21:20:06', NULL),
(17, 10, 5, 3, 7900, 'a', 'b', -2, 5, 4, -63200, -9480, -400, -73080, -6320, -6577.2, -6577.2, -13154.4, NULL, 1, '2020-11-10 14:02:11', NULL),
(24, 9, 5, 18, 7900, 'a', 'b', 2, 5, 4, 63200, 9480, 400, 73080, 6320, 6577.2, 6577.2, 13154.4, NULL, 1, '2020-11-10 15:18:40', NULL),
(31, 11, 7, 3, 5500, 'Exercitationem obcae', 'Tenetur officia quod', 40, 1, 1.4, 308000, 46200, 8000, 362200, 30800, 32598, 32598, 65196, NULL, 1, '2020-11-18 11:33:27', NULL),
(32, 11, 4, 3, 2090, 'Totam non harum aut ', 'Nihil obcaecati quib', 31, 2, 3, 194370, 29155.5, 6200, 229725.5, 19437, 20675.295, 20675.295, 41350.59, NULL, 1, '2020-11-18 11:33:27', NULL),
(33, 11, 9, 3, 3300, 'Cupiditate ut do duc', 'Rerum et ut corrupti', 5, 3, 2, 33000, 4950, 1000, 38950, 3300, 3505.5, 3505.5, 7011, NULL, 1, '2020-11-18 11:33:27', NULL),
(35, 12, 3, 3, 11700, 'a', 'b', 2, 5, 4, 93600, 14040, 400, 108040, 9360, 9723.6, 9723.6, 19447.2, NULL, 1, '2020-11-18 12:41:31', NULL),
(38, 13, 8, 3, 130000, 'a', 'b', 4, 5, 4, 2080000, 312000, 800, 2392800, 208000, 215352, 215352, 430704, NULL, 1, '2020-11-18 15:12:02', NULL),
(39, 13, 8, 3, 130000, 'c', 'd', 5, 2, 3, 1950000, 292500, 1000, 2243500, 195000, 201915, 201915, 403830, NULL, 1, '2020-11-18 15:12:02', NULL),
(41, 14, 8, 3, 130000, 'c', 'd', 3, 5, 4, 1560000, 234000, 600, 1794600, 156000, 161514, 161514, 323028, NULL, 1, '2020-11-18 15:39:07', NULL),
(43, 15, 4, 3, 2090, 'a', 'b', 6, 3, 2, 25080, 3762, 1200, 30042, 2508, 2703.78, 2703.78, 5407.56, NULL, 1, '2020-11-18 16:09:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `road_type`
--

CREATE TABLE `road_type` (
  `road_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `road_title` varchar(255) NOT NULL,
  `rate` double NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_till` date DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `road_type`
--

INSERT INTO `road_type` (`road_id`, `user_id`, `road_title`, `rate`, `status`, `date_from`, `date_till`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 0, 'Asphalt Road', 9600, 1, '2020-10-18', '2021-10-19', 0, '2020-10-06 08:06:26', '2020-10-19 05:43:17'),
(2, 0, 'Horizontal Directional Drilling ', 2000, 1, '2020-10-18', '2021-10-19', 0, '2020-10-06 08:06:41', '2020-10-19 05:47:11'),
(3, 0, 'Concrete Road', 11700, 1, '2020-10-18', '2021-10-19', 0, '2020-10-06 08:06:54', '2020-10-19 05:49:29'),
(4, 0, 'Earthen Road', 2090, 1, '2020-10-18', '2021-10-19', 0, '2020-10-06 08:07:08', '2020-10-19 05:50:15'),
(5, 0, 'Mastic Asphalt', 7900, 1, '2020-10-18', '2021-10-19', 0, '2020-10-13 13:37:37', '2020-10-14 12:48:34'),
(6, 0, 'Grounting Road ', 3900, 1, '2020-10-18', '2021-10-19', 0, '2020-10-14 12:35:12', '2020-10-19 05:48:31'),
(7, 0, 'Concrete Footpath ', 5500, 1, '2020-10-18', '2021-10-19', 0, '2020-10-14 14:46:14', '2020-10-19 05:51:12'),
(8, 0, 'Flyover ', 130000, 1, '2020-10-18', '2021-10-19', 0, '2020-10-14 14:57:37', '2020-10-19 05:52:17'),
(9, 0, 'WBM Road', 3300, 1, '2020-10-18', '2021-10-19', 0, '2020-10-19 05:42:16', '2020-10-19 05:42:16'),
(10, 0, 'Subway', 780000, 1, '2020-10-18', '2021-10-19', 0, '2020-10-19 05:53:14', '2020-10-19 05:53:14'),
(11, 0, 'Culvert ', 57200, 1, '2020-10-18', '2021-10-19', 0, '2020-10-19 05:54:07', '2020-10-19 05:54:07'),
(12, 0, 'Bituminious Concrete (60/70 Grade)', 9600, 1, '2020-10-18', '2021-10-19', 0, '2020-10-19 06:01:56', '2020-10-19 06:01:56'),
(13, 0, 'Bituminious Concrete (30/40 Grade)', 9600, 1, '2020-10-18', '2021-10-18', 0, '2020-10-19 06:05:53', '2020-10-19 06:05:53'),
(14, 0, 'Paver Block 80 mm', 5500, 1, '2020-10-18', '2021-10-18', 0, '2020-10-19 06:06:56', '2020-10-19 06:06:56'),
(15, 0, 'Paver Block 100 mm', 5500, 1, '2020-10-18', '2021-10-18', 0, '2020-10-19 06:07:37', '2020-10-19 06:07:37'),
(16, 0, 'main road', 10330, 1, '2020-11-10', '2021-04-30', 0, '2020-11-10 09:15:01', '2020-11-10 09:15:01');

--
-- Triggers `road_type`
--
DELIMITER $$
CREATE TRIGGER `OldData` AFTER UPDATE ON `road_type` FOR EACH ROW BEGIN
UPDATE `road_type_logs` SET status = '2' WHERE 1;

INSERT INTO `road_type_logs`(`user_id`, `road_id`, `road_title`, `rate`, `date_from`, `date_till`, `status`, `date_added`) VALUES (OLD.user_id, OLD.road_id, OLD.road_title, OLD.rate, OLD.date_from, OLD.date_till, '1', NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `road_type_logs`
--

CREATE TABLE `road_type_logs` (
  `rt_logid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `road_id` int(11) NOT NULL,
  `road_title` varchar(50) NOT NULL,
  `rate` double NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_till` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 2: Not Active',
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `road_type_logs`
--

INSERT INTO `road_type_logs` (`rt_logid`, `user_id`, `road_id`, `road_title`, `rate`, `date_from`, `date_till`, `status`, `date_added`) VALUES
(1, 0, 4, 'Earth', 2090, '2020-10-06', '2020-10-31', 2, '2020-10-06 11:43:12'),
(2, 0, 4, 'Earth', 2090, '2020-10-05', '2020-10-31', 2, '2020-10-08 11:10:36'),
(3, 0, 5, 'Mastic Asphalt', 7900, '2020-01-01', '2020-12-31', 2, '2020-10-14 16:18:29'),
(4, 0, 5, 'Mastic Asphalt', 7900, '2020-01-01', '2020-12-31', 2, '2020-10-14 16:18:33'),
(5, 0, 1, 'Asphalt', 9600, '2020-10-06', '2020-10-31', 2, '2020-10-19 11:07:48'),
(6, 0, 1, 'Asphalt Road', 9600, '0000-00-00', '0000-00-00', 2, '2020-10-19 11:08:34'),
(7, 0, 1, 'Asphalt Road', 9600, '0000-00-00', '0000-00-00', 2, '2020-10-19 11:13:17'),
(8, 0, 2, 'HDD', 2000, '2020-10-06', '2020-10-31', 2, '2020-10-19 11:17:11'),
(9, 0, 6, 'road title ', 5, '2020-12-01', '2020-10-14', 2, '2020-10-19 11:18:31'),
(10, 0, 3, 'Concrete', 11700, '2020-10-06', '2020-10-31', 2, '2020-10-19 11:19:29'),
(11, 0, 4, 'Earth', 2090, '0000-00-00', '0000-00-00', 2, '2020-10-19 11:20:15'),
(12, 0, 7, ' Asphalt', 9300, '2020-12-31', '2020-10-14', 2, '2020-10-19 11:21:12'),
(13, 0, 8, '        Asphalt', 8900, '2021-01-01', '2021-03-31', 2, '2020-10-19 11:22:17'),
(14, 0, 1, 'Asphalt Road', 9600, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(15, 0, 2, 'Horizontal Directional Drilling ', 2000, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(16, 0, 3, 'Concrete Road', 11700, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(17, 0, 4, 'Earthen Road', 2090, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(18, 0, 5, 'Mastic Asphalt', 7900, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(19, 0, 6, 'Grounting Road ', 3900, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(20, 0, 7, 'Concrete Footpath ', 5500, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(21, 0, 8, 'Flyover ', 130000, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(22, 0, 9, 'WBM Road', 3300, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(23, 0, 10, 'Subway', 780000, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(24, 0, 11, 'Culvert ', 57200, '2020-01-01', '2020-12-31', 2, '2020-10-19 11:33:36'),
(25, 0, 12, 'Bituminious Concrete (60/70 Grade)', 9600, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:33:36'),
(26, 0, 1, 'Asphalt Road', 9600, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(27, 0, 2, 'Horizontal Directional Drilling ', 2000, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(28, 0, 3, 'Concrete Road', 11700, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(29, 0, 4, 'Earthen Road', 2090, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(30, 0, 5, 'Mastic Asphalt', 7900, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(31, 0, 6, 'Grounting Road ', 3900, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(32, 0, 7, 'Concrete Footpath ', 5500, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(33, 0, 8, 'Flyover ', 130000, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(34, 0, 9, 'WBM Road', 3300, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(35, 0, 10, 'Subway', 780000, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(36, 0, 11, 'Culvert ', 57200, '2020-10-19', '2021-10-19', 2, '2020-10-19 11:34:16'),
(37, 0, 12, 'Bituminious Concrete (60/70 Grade)', 9600, '2020-10-19', '2021-10-19', 1, '2020-10-19 11:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `roles_table`
--

CREATE TABLE `roles_table` (
  `role_id` int(11) NOT NULL,
  `role_title` varchar(255) NOT NULL,
  `is_superadmin` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles_table`
--

INSERT INTO `roles_table` (`role_id`, `role_title`, `is_superadmin`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Superadmin', 1, 1, 0, '2020-03-18 15:58:26', '2020-09-02 08:14:10'),
(2, 'Subadmin', 0, 1, 0, '2020-03-18 15:58:26', '2020-03-21 12:42:50'),
(3, 'Clerk', 0, 1, 0, '2020-03-18 15:58:26', '2020-10-19 06:47:21'),
(4, 'commissioner', 0, 1, 0, '2020-03-21 12:14:17', '2020-08-28 07:44:40'),
(5, 'commm', 0, 1, 0, '2020-03-21 12:18:25', '2020-03-21 12:46:30'),
(6, 'dy.comm', 0, 1, 0, '2020-03-21 12:24:13', '2020-04-23 13:09:31'),
(7, 'Engineer', 0, 1, 0, '2020-03-21 12:24:57', '2020-03-21 12:24:57'),
(8, 'Jr.Engineer', 0, 1, 0, '2020-03-21 12:25:48', '2020-03-21 12:25:48'),
(9, 'Technican admin', 0, 1, 0, '2020-04-04 10:47:43', '2020-04-04 10:47:43'),
(10, 'ward officer', 0, 1, 0, '2020-04-07 12:59:15', '2020-04-07 12:59:15'),
(11, 'Technical Support', 0, 1, 0, '2020-04-10 14:55:10', '2020-04-10 14:55:10'),
(12, 'Authors', 0, 1, 0, '2020-04-10 14:55:37', '2020-04-23 13:09:21'),
(14, 'Support', 0, 1, 0, '2020-04-10 14:58:00', '2020-04-10 14:58:00'),
(15, 'Garden Suprentendent', 0, 1, 0, '2020-08-28 07:43:52', '2020-08-28 07:43:52'),
(16, 'Senior Executive', 0, 2, 0, '2020-09-29 10:38:06', '2020-09-29 10:38:06'),
(17, 'Deputy Engineer', 0, 1, 0, '2020-10-10 14:24:28', '2020-10-19 06:46:50'),
(18, 'Executive Engineer', 0, 1, 0, '2020-10-12 11:42:00', '2020-10-21 12:21:03'),
(19, 'Test PWD ', 0, 2, 0, '2020-10-12 15:34:53', '2020-10-19 06:46:01'),
(20, 'Test 2 PWD ', 0, 2, 0, '2020-10-12 15:41:40', '2020-10-19 06:45:51'),
(21, '1234', 0, 2, 0, '2020-10-13 11:43:36', '2020-10-19 06:45:37'),
(22, 'health officer', 0, 1, 0, '2020-11-22 17:26:19', '2020-11-22 17:26:19'),
(23, 'junior doctor', 0, 1, 0, '2020-11-22 17:26:40', '2020-11-22 17:26:40'),
(24, 'senior doctor', 0, 1, 0, '2020-11-22 17:27:00', '2020-11-22 17:27:00'),
(25, 'Additional commissioner', 0, 1, 0, '2020-11-27 16:35:48', '2020-11-27 16:35:48'),
(26, 'Test Role Medical ', 0, 1, 0, '2020-12-04 17:27:58', '2020-12-04 17:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `service_charge_details`
--

CREATE TABLE `service_charge_details` (
  `app_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `penalty_charges` varchar(255) NOT NULL,
  `finat_charges` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sku_master`
--

CREATE TABLE `sku_master` (
  `sku_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `sku_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sku_master`
--

INSERT INTO `sku_master` (`sku_id`, `dept_id`, `sku_title`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 3, 'Tree Cutting', 1, 0, '2020-04-06 08:52:46', '2020-05-13 09:52:41'),
(2, 6, 'mbmc main', 1, 0, '2020-04-06 10:11:07', '2020-04-10 12:11:19'),
(3, 6, 'abc', 1, 0, '2020-04-06 10:11:59', '2020-04-06 10:11:59'),
(4, 1, 'concrete', 1, 0, '2020-04-06 12:57:11', '2020-04-06 12:57:11'),
(5, 6, 'Electricity', 1, 0, '2020-04-10 12:09:48', '2020-04-10 12:09:48'),
(6, 6, 'red cover chair', 1, 0, '2020-04-10 12:10:11', '2020-04-10 12:10:11'),
(7, 6, 'Dining Plate', 1, 0, '2020-04-10 12:10:40', '2020-04-10 12:10:40'),
(8, 9, 'abc', 1, 0, '2020-05-13 09:51:58', '2020-05-13 09:51:58'),
(9, 9, 'abc', 1, 0, '2020-05-13 09:52:10', '2020-05-13 09:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `sub_department_table`
--

CREATE TABLE `sub_department_table` (
  `sub_dept_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `dept_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `crteated_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_department_table`
--

INSERT INTO `sub_department_table` (`sub_dept_id`, `dept_id`, `dept_title`, `status`, `is_deleted`, `crteated_at`, `updated_at`) VALUES
(1, 5, 'Hospital', 1, 0, '2020-04-22 00:00:00', '2020-04-22 00:00:00'),
(2, 5, 'Clinic', 1, 0, '2020-04-22 00:00:00', '2020-04-22 00:00:00'),
(3, 5, 'Labs', 1, 0, '2020-04-22 00:00:00', '2020-04-22 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `temp_lic`
--

CREATE TABLE `temp_lic` (
  `lic_id` int(11) NOT NULL,
  `form_no` varchar(25) NOT NULL,
  `lic_type` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stall_address` varchar(255) NOT NULL,
  `aadharId` int(11) NOT NULL DEFAULT '0',
  `panId` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `udated_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:activate, 2: deactivate',
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_lic`
--

INSERT INTO `temp_lic` (`lic_id`, `form_no`, `lic_type`, `name`, `stall_address`, `aadharId`, `panId`, `created_date`, `udated_date`, `status`, `is_deleted`) VALUES
(1, 'MBMC-000003', 1, 'Ankit Naik', 'Test', 2, 3, '2020-04-18 00:00:00', '2020-04-20 12:15:26', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `trade_faclicapplication`
--

CREATE TABLE `trade_faclicapplication` (
  `tradefac_lic_id` int(11) NOT NULL,
  `form_no` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `shop_no` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `property_no` varchar(50) NOT NULL,
  `shop_name` varchar(50) NOT NULL,
  `type_of_business` varchar(100) NOT NULL,
  `new_renewal` int(11) NOT NULL,
  `existing_no` varchar(50) NOT NULL,
  `type_of_property` int(11) NOT NULL,
  `property_date` date NOT NULL,
  `aadhar_no` varchar(50) NOT NULL,
  `pan_no` varchar(50) NOT NULL,
  `date_no_obj` date NOT NULL,
  `date_food_lic` date NOT NULL,
  `date_property_tax` date NOT NULL,
  `date_establishment` date NOT NULL,
  `date_assurance` date NOT NULL,
  `application_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 2: Approved, 3: Delete',
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trade_faclicapplication`
--

INSERT INTO `trade_faclicapplication` (`tradefac_lic_id`, `form_no`, `name`, `shop_no`, `address`, `property_no`, `shop_name`, `type_of_business`, `new_renewal`, `existing_no`, `type_of_property`, `property_date`, `aadhar_no`, `pan_no`, `date_no_obj`, `date_food_lic`, `date_property_tax`, `date_establishment`, `date_assurance`, `application_date`, `status`, `is_deleted`) VALUES
(1, 'MBMC-000007', 'Test', '123', 'Test', '879', 'Test', 'tetet', 0, '12', 0, '2020-04-23', '444', '444', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '2020-04-22 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `treecuttingapplications`
--

CREATE TABLE `treecuttingapplications` (
  `cutAppId` int(11) NOT NULL,
  `formNo` varchar(50) NOT NULL,
  `applicantName` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `surveyNo` int(20) NOT NULL DEFAULT '0',
  `citySurveyNo` varchar(20) DEFAULT NULL,
  `wardNo` int(11) NOT NULL,
  `plotNo` int(11) NOT NULL DEFAULT '0',
  `noOfTrees` int(11) NOT NULL,
  `permission_type` int(11) NOT NULL,
  `blueprint` varchar(100) NOT NULL,
  `ownership_property_pdf` varchar(100) NOT NULL,
  `declarationGardenSuprintendent` int(11) NOT NULL DEFAULT '1' COMMENT '1:Agreed,2:Not Aggred',
  `added_by` int(11) NOT NULL,
  `applicantDate` varchar(30) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Accepted, 3:Rejected,4:Deleted',
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_closure_status` int(11) NOT NULL DEFAULT '1' COMMENT '0: open, 1: closed'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treecuttingapplications`
--

INSERT INTO `treecuttingapplications` (`cutAppId`, `formNo`, `applicantName`, `mobile`, `email`, `address`, `surveyNo`, `citySurveyNo`, `wardNo`, `plotNo`, `noOfTrees`, `permission_type`, `blueprint`, `ownership_property_pdf`, `declarationGardenSuprintendent`, `added_by`, `applicantDate`, `date_added`, `status`, `is_deleted`, `created_at`, `file_closure_status`) VALUES
(2, 'MBMC-0000063', 'Ankit Naik', '8879581492', 'ankit.naik@gmail.com', 'Test', 123, '123', 123, 123, 13, 0, 'e6523739bcb4caa51c2739499c6f755c.jpg', '0a4cdf88a3a5b03ebe52b1d0abff4bbf.pdf', 0, 26, '2020-09-10 12:10:19', '2020-09-10 12:10:19', 1, 0, '2020-09-10 12:10:19', 1),
(3, 'MBMC-0000054', 'shalini sinha', '7896541235', 'shalini@yopmail.com', '10th Floor,IJMIMA, Off Link Road,\r\nMalad West.Mumbai 400064', 10, '', 123456, 0, 0, 0, '4e0f4a076d4e5de6f33ca8b65b692bdb.pdf', 'b4a9ec7f3da450f5f4a11ff08c130bec.pdf', 0, 29, '2020-11-27 15:41:16', '2020-11-27 15:41:16', 1, 0, '2020-11-27 15:41:16', 1),
(4, 'MBMC-0000055', 'Daisy ', '1231231968', 'suman.kattimani@aaravsoftware.com', 'Daisy', 23456, '05102020', 340, 890, 0, 3, 'aa29a8b269fd053cd7034d34a5cd7791.pdf', '886adfddc92cfdacfb029d2b5435b50b.pdf', 1, 22, '2020-11-30 11:54:01', '2020-11-30 11:54:01', 1, 0, '2020-11-30 11:54:01', 1),
(5, 'MBMC-0000056', 'Daisy ', '1231231968', 'suman.kattimani@aaravsoftware.com', 'Juhu ', 1234, '6789', 123, 67, 0, 3, '46802caf4d1e3a90bb46f90a97ef30b1.pdf', '700f297c74a29034fb62d6eb2089af24.pdf', 1, 22, '2020-11-30 16:37:11', '2020-11-30 16:37:11', 1, 0, '2020-11-30 16:37:11', 1),
(6, 'MBMC-0000057', 'brandita', '7896541235', 'brandita@yopmail.com', 'Ea omnis commodo qui', 123, '123', 123, 123, 0, 5, '87d343bee060b3ccc145e5b88f3c1b24.jpg', '9c0c40a56424d9e133511bc25894fd56.pdf', 0, 29, '2020-11-30 20:46:02', '2020-11-30 20:46:02', 1, 0, '2020-11-30 20:46:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `treecuttingprocess`
--

CREATE TABLE `treecuttingprocess` (
  `processId` int(11) NOT NULL,
  `processName` varchar(50) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Not Active',
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treecuttingprocess`
--

INSERT INTO `treecuttingprocess` (`processId`, `processName`, `added_by`, `date_added`, `status`, `is_deleted`) VALUES
(1, 'Testing Process', 1, '2020-04-07 09:15:55', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `treenames`
--

CREATE TABLE `treenames` (
  `tree_id` int(11) NOT NULL,
  `treeName` varchar(50) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 2:Not Active',
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0: Not Deleted, 1: Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treenames`
--

INSERT INTO `treenames` (`tree_id`, `treeName`, `added_by`, `date_added`, `status`, `is_deleted`) VALUES
(1, 'Neem Tree', 1, '2020-04-06 08:11:44', 1, 0),
(2, 'Banyan Tree', 1, '2020-04-06 08:17:57', 1, 0),
(3, 'Tree', 1, '2020-04-06 08:18:50', 2, 0),
(4, 'Tree Test', 1, '2020-04-06 10:34:07', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `unit_master`
--

CREATE TABLE `unit_master` (
  `unit_id` int(11) NOT NULL,
  `unit_value` varchar(255) NOT NULL,
  `unit_label` varchar(255) NOT NULL,
  `unit_cost` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit_master`
--

INSERT INTO `unit_master` (`unit_id`, `unit_value`, `unit_label`, `unit_cost`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '1', 'Sq.ft', '', 1, 0, '2020-04-10 09:03:12', '2020-05-13 09:55:33'),
(2, '1', 'hall', '', 1, 0, '2020-04-10 09:07:11', '2020-04-10 09:07:11'),
(3, '1', 'Kwh', '', 1, 0, '2020-04-10 09:07:27', '2020-04-10 09:07:27'),
(4, '1', 'pieces', '', 1, 0, '2020-04-10 09:07:43', '2020-04-10 09:07:43'),
(5, '1', 'meter', '', 1, 0, '2020-04-10 09:07:56', '2020-04-10 09:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `user_name` varchar(225) NOT NULL,
  `user_mobile` varchar(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_visitor` int(11) NOT NULL,
  `is_user` int(11) NOT NULL COMMENT '1:user, 0:admin',
  `termsCond` int(11) NOT NULL COMMENT '0:No Terms, 1:Accepted',
  `user_keygen` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_superadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`user_id`, `role_id`, `ward_id`, `email_id`, `user_name`, `user_mobile`, `dept_id`, `password`, `is_visitor`, `is_user`, `termsCond`, `user_keygen`, `status`, `is_deleted`, `created_at`, `updated_at`, `is_superadmin`) VALUES
(1, 0, 0, 'info.mumbaielectricity@adani.com', 'Adani Power', '9876543210', 0, '$2a$08$6tMHvH0pGxr8WQOLn/TGYOnyMcVWg815o5KH1Z0JE6MW6Fwvd.mL.', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:37:22', '2020-10-19 10:37:22', 0),
(2, 0, 0, 'info@mahanagargas.com', 'Mahanagar Gas', '1234567890', 0, '$2a$08$M5ihVeIryD3xcp3vQ9ItFOsrgNmw0nfow7.mzGbO/JoF.1lMV/d0C', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:38:45', '2020-10-19 10:38:45', 0),
(3, 0, 0, 'tatapower@tatapower.com', 'Tata Power', '2356892365', 0, '$2a$08$ftpBlwi1yRkVCEcPhqxdUOoNIZor1TzZHDt4ROuFhG03HwFOMS.aC', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:39:54', '2020-10-19 10:39:54', 0),
(4, 0, 0, 'mtnl@yopmail.com', 'MTNL', '7845127845', 0, '$2a$08$AwzDMsQy2sTCQr38Jz6cGePBEy8uUVNS.XpLf0NdWy5hpDuLtCF3y', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:40:53', '2020-10-19 10:40:53', 0),
(5, 0, 0, 'idea@yopmail.com', 'Idea', '8956232365', 0, '$2a$08$DMoxf8BFVoBUlJK/8UAZ0uU.GFV.F/ywMV/7PFx1c6uxcQSpHY/KS', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:41:35', '2020-10-19 10:41:35', 0),
(6, 1, 0, 'test@gmail.com', 'test', '9875641232', 22, '$2a$08$wua0ZXwc8a2EpofUA1mppemfY9oWvAK7aggldv3ueXOis9RHEpBBi', 0, 0, 1, NULL, 1, 0, '2020-09-16 07:24:09', '2020-09-16 07:24:09', 0),
(7, 0, 0, 'corporate.secretarial@bharti.in', 'Airtel', '1238529632', 0, '$2a$08$KP7KuSzaFiF94j2D8g8kIe1W2qjReFi2R1sUalmlHa.2jbwu2rgzG', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:42:26', '2020-10-19 10:42:26', 0),
(8, 0, 0, 'vodafone@yopmail.com', 'Vodafone', '4567896541', 0, '$2a$08$gvZDn24NxekPxx5BhIPMZOJpXfPO6rH/TpjAPePT5WLeZxQSJfF7m', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:43:28', '2020-10-19 10:43:28', 0),
(9, 3, 0, 'pwdcleark@yopmail.com', 'Clerk', '9874563211', 1, '$2a$08$skgZzRGUHefIlXg2JhwwuOx/fGc5F89PaxO74GFgtBJaFi7FOHZ1.', 0, 0, 1, NULL, 1, 0, '2020-10-19 10:43:49', '2020-10-19 10:43:49', 0),
(10, 0, 0, 'reliance@yopmail.com', 'Reliance Jio', '3579514563', 0, '$2a$08$Dy/T5HeVPQXHiSW5dYnuQ.MnOhkQ868qgGyltiOL8Ck7BfvYq4bq6', 0, 1, 1, NULL, 1, 0, '2020-10-19 10:44:11', '2020-10-19 10:44:11', 0),
(11, 8, 1, 'satishtandelst8@gmail.com', 'Satish Tandel', '9874563212', 1, '$2a$08$3gyYNAjnawcVceltUPrzFe5y7CmgmtRu762.rNMmj8xTjneDVan02', 0, 0, 1, NULL, 1, 0, '2020-10-19 10:44:20', '2020-10-21 12:46:24', 0),
(12, 17, 0, 'ya3jdv@gmail.com', 'Yatin Jadhav', '9874563213', 1, '$2a$08$99O0HjgVeL5/.dIhQqCGN.keyMpjuJKkJBrIpWWdPtT.yKigAK/Mi', 0, 0, 1, NULL, 1, 0, '2020-10-19 10:45:19', '2020-10-21 12:33:37', 0),
(13, 18, 0, 'deepak.khambit@gmail.com', 'Deepak Khambit', '9874563214', 1, '$2a$08$DVTdRGLB/tLx0qkB/e8ebOajnUORHEqCP89L/AktZLYuTqdynf9dy', 0, 0, 1, NULL, 1, 0, '2020-10-19 10:45:56', '2020-10-21 12:22:43', 0),
(14, 8, 1, 'patilarvind19.ap@gmail.com', 'Arvind Patil', '7894561230', 1, '$2a$08$WOkOQdOlDtj0uGrX/WNRfOCi1F4.R7LEhfHQYpjUJSZbeKx6N6gj2', 0, 0, 1, NULL, 1, 0, '2020-10-21 12:47:59', '2020-10-21 12:47:59', 0),
(15, 8, 1, 'cm13568@gmail.com', 'Chetan Mhatre', '4569871236', 1, '$2a$08$1HgHS634pWontzxVqa4aDOr9Lib4zFg8TscnmkKYycz4Zfz8009I6', 0, 0, 1, NULL, 1, 0, '2020-10-21 12:49:24', '2020-10-21 12:49:24', 0),
(16, 8, 1, 'sachinpatil1034@gmail.com', 'Sachin Patil', '4563214563', 1, '$2a$08$j/dW8F1Oyua/q3Q1JUExROFR5eHCT3BL78UqT/qpOt.9Ocls/luG.', 0, 0, 1, NULL, 1, 0, '2020-10-21 12:50:21', '2020-10-21 12:50:21', 0),
(17, 8, 1, 'sachinpawar774@gmail.com', 'Sachin Pawar', '4569874535', 1, '$2a$08$g3Zo3HQ4cp3lm/7hZHPKku052tNcDtDUXk7bDioBgWoPu5Szey5ES', 0, 0, 1, NULL, 1, 0, '2020-10-21 12:51:34', '2020-10-21 12:51:34', 0),
(18, 8, 1, 'prafullwankhede@gmail.com', 'Prafull Wankhede', '1237894563', 1, '$2a$08$nIDvJ740rL96x2Mchh4Ev.68sCK7CGa7y2R1lXY61r0XRC8P9cLr.', 0, 0, 1, NULL, 1, 0, '2020-10-21 12:52:20', '2020-10-21 12:52:20', 0),
(19, 0, 0, 'nilesh.more7@gmail.com', 'Nilesh More', '9022513344', 0, '$2a$08$taDHES1zjGJJzKQxKWiSQORDCLt4onR3GaxsFDfr5hYs53jrB/h8a', 0, 1, 1, 'cWX9HZjVelobU7aOSR8f', 1, 0, '2020-10-21 13:53:37', '2020-10-21 13:53:37', 0),
(20, 0, 0, 'chetnya@aaravsoftware.com', 'tom', '1478523692', 0, '$2a$08$2WdGWk29gU7jgppfBcsweOpQwGcv.etx9ubR1T3QlQ8/FMwg8NlCO', 0, 1, 1, NULL, 1, 0, '2020-11-10 07:57:52', '2020-11-10 07:57:52', 0),
(21, 0, 0, 'sn101296@gmail.com', 'nick', '7894562312', 0, '$2a$08$0.1i72ULIMddSNEWifKBquXJTVvLCXbqYZGKSn4NPzUBoNYLfPHeu', 0, 1, 1, NULL, 1, 0, '2020-11-10 08:27:13', '2020-11-10 08:27:13', 0),
(22, 0, 0, 'smn101296@gmail.com', 'nick', '7894561238', 0, '$2a$08$qDt3.z.b9DxJOAmUGs.X/ueV1UWc6l93aAGSr.OUzEtgZQTUGzgEy', 0, 1, 1, NULL, 1, 0, '2020-11-10 08:28:47', '2020-11-10 08:28:47', 0),
(23, 0, 0, 'dhyeyrathod111@yopmail.com', 'dhyey rathod', '1236547895', 0, '$2a$08$XmQLvGZOTjGcRyAu00g3IuLk/MqWgnIEAuvE7IQDKuQ/J8vKKvoHi', 0, 1, 1, NULL, 1, 0, '2020-11-18 05:55:03', '2020-11-18 05:55:03', 0),
(24, 3, 0, 'hospitalcleark@yopmail.com', 'hospitalcleark', '7896541235', 5, '$2a$08$ohsqC3kG.Jx8ja612Up.M.lUApUsJcQqqk6tfPDQofa37XrXXqtcS', 0, 0, 1, NULL, 1, 0, '2020-11-22 17:28:45', '2020-11-22 17:28:45', 0),
(25, 22, 3, 'mbmcho@yopmail.com', 'mbmcho', '7896543215', 5, '$2a$08$k9OC4H0VNaZwmpVtWKmJF.wRhbd/EpHh5n9d4aVgkZPk0S1H1ER5S', 0, 0, 1, NULL, 1, 0, '2020-11-22 17:29:55', '2020-11-22 17:29:55', 0),
(26, 23, 0, 'juniordoctor@yopmail.com', 'juniordoctor', '7845123265', 5, '$2a$08$.HgDEXbU/Ze7m.qdxtGCMem0mUdpWffAtzOUQTmdmz8xZGlASQ6SO', 0, 0, 1, NULL, 1, 0, '2020-11-22 17:30:33', '2020-11-22 17:30:33', 0),
(27, 24, 0, 'seniordoctor@yopmail.com', 'seniordoctor', '9865324512', 5, '$2a$08$bcmF8T3Kq/ymLfrVIPpVpeLtcHS65bPMIMf5SDGzFB5Wf4GbGtTEW', 0, 0, 1, NULL, 1, 0, '2020-11-22 17:31:04', '2020-11-22 17:31:04', 0),
(28, 0, 0, 'dhyeyrathod111@gmail.com', 'dhyeyrathod', '7896541232', 0, '$2a$08$Vza5vcxpuWMhPeTSX1IPfe3qPOI.gwPLiFJnWfFk42E7ELFCZqqOq', 0, 1, 1, NULL, 1, 0, '2020-11-22 18:06:21', '2020-11-22 18:06:21', 0),
(29, 3, 0, 'gardenclerk@yopmail.com', 'Graden Clerk', '1234567809', 3, '$2a$08$JAcSs8CXsTexDChpIj6dHePyfrEMl23L.CHBo66aa3BLbvgjIoABC', 0, 0, 1, '', 1, 0, '2020-11-23 04:25:18', '2020-11-23 04:25:18', 0),
(33, 15, 0, 'grdsuperint@yopmail.com', 'garden superintendent', '7894563215', 3, '$2a$08$b5qHMpWUl7.MGlR/P9d2t.ys768UaX8HBnoKFFv0hDuf.cHg5RAI.', 0, 0, 1, NULL, 1, 0, '2020-11-27 17:39:52', '2020-11-27 17:39:52', 0),
(34, 6, 0, 'dyptcomm@yopmail.com', 'dypt commissioner', '7812456598', 3, '$2a$08$vMeLzn1feO/E9ojCBsAkOu.kz4D6nCG5jaIG.9PBHH3q607cLbHEm', 0, 0, 1, NULL, 1, 0, '2020-11-27 17:40:45', '2020-11-27 17:40:45', 0),
(35, 25, 0, 'addcommi@yopmail.com', 'additional commissioner', '9832453614', 3, '$2a$08$zjaHe.VzkIGhWNWk7F5sVeMs9BCxMoBWdWh5BjFv6vkQHoicwlAnC', 0, 0, 1, NULL, 1, 0, '2020-11-27 17:42:01', '2020-11-27 17:42:01', 0),
(36, 4, 0, 'commissioner@yopmail.com', 'commissioner', '9832784565', 3, '$2a$08$6YFIGWYIJKojQqNCoMZuw.eeKyMCt37gxx3EaD/.nyZmZ5hJHno/2', 0, 0, 1, NULL, 1, 0, '2020-11-27 17:42:37', '2020-11-27 17:42:37', 0),
(37, 26, 0, 'abc@yopmail.com', 'abc', '1234567898', 5, '$2a$08$O3k7hbboLmiEmpyMaDWuhOw92W1GIK41W8IBwp2vhCKI/K/uftDP2', 0, 0, 1, NULL, 2, 0, '2020-12-04 17:31:22', '2020-12-07 16:48:59', 0),
(43, 3, 11, 'mandapclerkw1@yopmail.com', 'mandapclerkw1', '9874563215', 12, '$2a$08$.E9zkqTLLuZqCH/LmEZBg.Qyy1U7Y6fh6Oy/utd3HYg1UO9X55Z.W', 0, 0, 1, NULL, 1, 0, '2020-12-11 12:19:57', '2020-12-11 12:19:57', 0),
(44, 3, 12, 'mandapclerkw2@yopmail.com', 'mandapclerkw2', '7893214568', 12, '$2a$08$lRDcYAfUIDeBmWwQ0Elm7u2JKoGCJy9vBkJBHPZ6BuYY9xphevcOK', 0, 0, 1, NULL, 1, 0, '2020-12-11 12:20:52', '2020-12-11 12:20:52', 0),
(45, 10, 11, 'mandapwardoffw1@yopmail.com', 'mandapwardoffw1', '7896541238', 12, '$2a$08$mbykT.2pnpWd0L1W0jG50eYshh2BAFyXgzp1NqnL5AjARZn.To2Y.', 0, 0, 1, NULL, 1, 0, '2020-12-11 12:21:43', '2020-12-11 12:21:43', 0),
(46, 10, 12, 'mandapwardoffw2@yopmail.com', 'mandapwardoffw2', '9514567538', 12, '$2a$08$pzCRaSFjJjDqWdPNOmK3BePfNIEiAi8Io5Fmkj8Swo2UleHB/668e', 0, 0, 1, NULL, 1, 0, '2020-12-11 12:22:34', '2020-12-11 12:22:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_dept_table`
--

CREATE TABLE `user_dept_table` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_dept_table`
--

INSERT INTO `user_dept_table` (`id`, `user_id`, `dept_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, '2020-03-30 00:00:00', '2020-03-30 00:00:00'),
(2, 2, 2, 1, 0, '2020-03-30 00:00:00', '2020-03-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `per_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `route_status` int(11) NOT NULL COMMENT '1: Active, 0: Inactive',
  `category_id` int(11) NOT NULL COMMENT '1:index, 2:create, 3:edit,4:delete',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active, 2: Inactive',
  `date_added` datetime NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`per_id`, `user_id`, `role_id`, `dept_id`, `route_id`, `route_status`, `category_id`, `status`, `date_added`, `is_deleted`) VALUES
(1, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:38:23', 1),
(2, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:38:23', 1),
(3, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:38:23', 1),
(4, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:38:35', 1),
(5, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:38:35', 1),
(6, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:38:36', 1),
(7, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:40:27', 1),
(8, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:40:27', 1),
(9, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:40:27', 1),
(10, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:41:02', 1),
(11, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:41:02', 1),
(12, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:41:02', 1),
(13, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:41:16', 1),
(14, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:41:16', 1),
(15, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:41:16', 1),
(16, 2, 8, 1, 10, 0, 1, 2, '2020-06-16 15:41:30', 1),
(17, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:41:30', 1),
(18, 2, 8, 1, 32, 0, 3, 2, '2020-06-16 15:41:30', 1),
(19, 2, 8, 1, 10, 1, 1, 2, '2020-06-16 15:43:01', 1),
(20, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:43:02', 1),
(21, 2, 8, 1, 32, 1, 3, 2, '2020-06-16 15:43:02', 1),
(22, 2, 8, 1, 10, 1, 1, 2, '2020-06-16 15:43:12', 1),
(23, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:43:12', 1),
(24, 2, 8, 1, 32, 1, 3, 2, '2020-06-16 15:43:12', 1),
(25, 2, 8, 1, 10, 1, 1, 2, '2020-06-16 15:43:22', 1),
(26, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:43:22', 1),
(27, 2, 8, 1, 32, 1, 3, 2, '2020-06-16 15:43:22', 1),
(28, 2, 8, 1, 10, 1, 1, 2, '2020-06-16 15:46:03', 1),
(29, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:46:03', 1),
(30, 2, 8, 1, 32, 1, 3, 2, '2020-06-16 15:46:04', 1),
(31, 2, 8, 1, 10, 1, 1, 2, '2020-06-16 15:48:27', 1),
(32, 2, 8, 1, 11, 0, 2, 2, '2020-06-16 15:48:27', 1),
(33, 2, 8, 1, 32, 1, 3, 2, '2020-06-16 15:48:27', 1),
(34, 2, 8, 1, 240, 0, 4, 2, '2020-06-16 15:48:27', 1),
(35, 2, 8, 1, 10, 1, 1, 1, '2020-06-23 14:56:37', 0),
(36, 2, 8, 1, 11, 1, 2, 1, '2020-06-23 14:56:38', 0),
(37, 2, 8, 1, 32, 1, 3, 1, '2020-06-23 14:56:39', 0),
(38, 2, 8, 1, 240, 0, 4, 1, '2020-06-23 14:56:39', 0),
(39, 0, 3, 11, 197, 1, 1, 2, '2020-07-18 08:03:22', 1),
(40, 0, 3, 11, 198, 1, 2, 2, '2020-07-18 08:03:22', 1),
(41, 0, 3, 11, 204, 1, 3, 2, '2020-07-18 08:03:22', 1),
(42, 0, 3, 11, 203, 1, 4, 2, '2020-07-18 08:03:22', 1),
(43, 0, 3, 11, 197, 1, 1, 2, '2020-07-18 08:03:37', 1),
(44, 0, 3, 11, 198, 1, 2, 2, '2020-07-18 08:03:37', 1),
(45, 0, 3, 11, 204, 1, 3, 2, '2020-07-18 08:03:38', 1),
(46, 0, 3, 11, 203, 1, 4, 2, '2020-07-18 08:03:38', 1),
(47, 0, 3, 11, 197, 1, 1, 2, '2020-07-18 08:06:12', 1),
(48, 0, 3, 11, 198, 1, 2, 2, '2020-07-18 08:06:12', 1),
(49, 0, 3, 11, 204, 1, 3, 2, '2020-07-18 08:06:12', 1),
(50, 0, 3, 11, 203, 1, 4, 2, '2020-07-18 08:06:12', 1),
(51, 0, 3, 11, 197, 1, 1, 2, '2020-07-18 08:07:20', 1),
(52, 0, 3, 11, 198, 1, 2, 2, '2020-07-18 08:07:20', 1),
(53, 0, 3, 11, 204, 1, 3, 2, '2020-07-18 08:07:20', 1),
(54, 0, 3, 11, 203, 1, 4, 2, '2020-07-18 08:07:21', 1),
(55, 0, 3, 11, 197, 1, 1, 1, '2020-07-18 08:07:43', 0),
(56, 0, 3, 11, 198, 1, 2, 1, '2020-07-18 08:07:43', 0),
(57, 0, 3, 11, 204, 1, 3, 1, '2020-07-18 08:07:43', 0),
(58, 0, 3, 11, 203, 1, 4, 1, '2020-07-18 08:07:44', 0),
(59, 0, 1, 11, 197, 1, 1, 1, '2020-07-18 08:09:25', 0),
(60, 0, 1, 11, 198, 1, 2, 1, '2020-07-18 08:09:25', 0),
(61, 0, 1, 11, 204, 1, 3, 1, '2020-07-18 08:09:25', 0),
(62, 0, 1, 11, 203, 1, 4, 1, '2020-07-18 08:09:25', 0),
(63, 0, 3, 3, 75, 1, 1, 2, '2020-10-05 11:18:20', 1),
(64, 0, 3, 3, 76, 1, 2, 2, '2020-10-05 11:18:20', 1),
(65, 0, 3, 3, 90, 1, 3, 2, '2020-10-05 11:18:20', 1),
(66, 0, 3, 3, 93, 1, 4, 2, '2020-10-05 11:18:20', 1),
(67, 0, 3, 3, 75, 1, 1, 1, '2020-10-05 11:18:56', 0),
(68, 0, 3, 3, 76, 0, 2, 1, '2020-10-05 11:18:57', 0),
(69, 0, 3, 3, 90, 1, 3, 1, '2020-10-05 11:18:57', 0),
(70, 0, 3, 3, 93, 1, 4, 1, '2020-10-05 11:18:57', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `ward_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `ward_title` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: Active,2: Not Active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` int(11) NOT NULL DEFAULT '0',
  `sub_dept_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`ward_id`, `dept_id`, `role_id`, `ward_title`, `status`, `created_at`, `updated_at`, `is_deleted`, `sub_dept_id`) VALUES
(1, 1, 8, 'ward one', 1, '2020-10-10 12:41:05', NULL, 0, 0),
(2, 1, 8, 'word two', 1, '2020-10-10 12:41:22', NULL, 0, 0),
(3, 5, 22, 'ward B', 1, '2020-11-22 23:04:03', '2020-12-07 09:29:58', 0, 0),
(4, 5, 22, 'ward A', 1, '2020-12-04 16:50:18', NULL, 0, 0),
(5, 5, 22, 'Ward C', 1, '2020-12-04 17:20:10', NULL, 0, 0),
(6, 5, 22, 'WARD 1995', 1, '2020-12-05 16:55:37', NULL, 0, 0),
(11, 12, 0, 'mandap ward 1', 1, '2020-12-11 12:11:41', NULL, 0, 0),
(12, 12, 0, 'mandap ward 2', 1, '2020-12-11 12:11:54', NULL, 0, 0),
(13, 12, 0, 'mandap ward 3', 1, '2020-12-11 12:12:10', NULL, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisement_applications`
--
ALTER TABLE `advertisement_applications`
  ADD PRIMARY KEY (`adv_id`);

--
-- Indexes for table `adv_type`
--
ALTER TABLE `adv_type`
  ADD PRIMARY KEY (`adv_id`);

--
-- Indexes for table `applications_details`
--
ALTER TABLE `applications_details`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `application_remarks`
--
ALTER TABLE `application_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_premssion`
--
ALTER TABLE `app_premssion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_routes`
--
ALTER TABLE `app_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `app_routes_old`
--
ALTER TABLE `app_routes_old`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `app_status_level`
--
ALTER TABLE `app_status_level`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinic_applications`
--
ALTER TABLE `clinic_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinic_staff`
--
ALTER TABLE `clinic_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `closed_application_table`
--
ALTER TABLE `closed_application_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_address`
--
ALTER TABLE `company_address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `company_details`
--
ALTER TABLE `company_details`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `defect_laiblity`
--
ALTER TABLE `defect_laiblity`
  ADD PRIMARY KEY (`laib_id`);

--
-- Indexes for table `defect_laiblity_logs`
--
ALTER TABLE `defect_laiblity_logs`
  ADD PRIMARY KEY (`laib_log_id`);

--
-- Indexes for table `department_table`
--
ALTER TABLE `department_table`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `department_table_old`
--
ALTER TABLE `department_table_old`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `deposit_inspection_fees`
--
ALTER TABLE `deposit_inspection_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation_master`
--
ALTER TABLE `designation_master`
  ADD PRIMARY KEY (`design_id`);

--
-- Indexes for table `filmdata`
--
ALTER TABLE `filmdata`
  ADD PRIMARY KEY (`film_id`);

--
-- Indexes for table `gardendata`
--
ALTER TABLE `gardendata`
  ADD PRIMARY KEY (`gardenId`);

--
-- Indexes for table `garden_permission`
--
ALTER TABLE `garden_permission`
  ADD PRIMARY KEY (`garper_id`);

--
-- Indexes for table `godownapplication`
--
ALTER TABLE `godownapplication`
  ADD PRIMARY KEY (`godown_id`);

--
-- Indexes for table `hall_applications`
--
ALTER TABLE `hall_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hall_assets`
--
ALTER TABLE `hall_assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `hall_checklist_details`
--
ALTER TABLE `hall_checklist_details`
  ADD PRIMARY KEY (`checklist_id`);

--
-- Indexes for table `hall_type`
--
ALTER TABLE `hall_type`
  ADD PRIMARY KEY (`hall_id`);

--
-- Indexes for table `hospital_alien`
--
ALTER TABLE `hospital_alien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_applications`
--
ALTER TABLE `hospital_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_fee_charges`
--
ALTER TABLE `hospital_fee_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_florespace_for_bedrooms`
--
ALTER TABLE `hospital_florespace_for_bedrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_florespace_for_kitchen`
--
ALTER TABLE `hospital_florespace_for_kitchen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_inspection_form`
--
ALTER TABLE `hospital_inspection_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_midwife`
--
ALTER TABLE `hospital_midwife`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_staff_details`
--
ALTER TABLE `hospital_staff_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_supervision`
--
ALTER TABLE `hospital_supervision`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_surgeon_information`
--
ALTER TABLE `hospital_surgeon_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `illuminate`
--
ALTER TABLE `illuminate`
  ADD PRIMARY KEY (`ill_id`);

--
-- Indexes for table `image_details`
--
ALTER TABLE `image_details`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `joint_visit_extentions`
--
ALTER TABLE `joint_visit_extentions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_applications`
--
ALTER TABLE `lab_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_staff`
--
ALTER TABLE `lab_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `latter_generation`
--
ALTER TABLE `latter_generation`
  ADD PRIMARY KEY (`letter_id`);

--
-- Indexes for table `licdates`
--
ALTER TABLE `licdates`
  ADD PRIMARY KEY (`date_id`);

--
-- Indexes for table `lic_data`
--
ALTER TABLE `lic_data`
  ADD PRIMARY KEY (`data_id`);

--
-- Indexes for table `lic_type`
--
ALTER TABLE `lic_type`
  ADD PRIMARY KEY (`lic_type_id`);

--
-- Indexes for table `mandap_applications`
--
ALTER TABLE `mandap_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `permission_access`
--
ALTER TABLE `permission_access`
  ADD PRIMARY KEY (`access_id`);

--
-- Indexes for table `permission_type_pwd`
--
ALTER TABLE `permission_type_pwd`
  ADD PRIMARY KEY (`pt_id`);

--
-- Indexes for table `price_master`
--
ALTER TABLE `price_master`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `property_type`
--
ALTER TABLE `property_type`
  ADD PRIMARY KEY (`prop_type_id`);

--
-- Indexes for table `pwd_applications`
--
ALTER TABLE `pwd_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qualification_master`
--
ALTER TABLE `qualification_master`
  ADD PRIMARY KEY (`qual_id`);

--
-- Indexes for table `req_type`
--
ALTER TABLE `req_type`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `road_information_pwd`
--
ALTER TABLE `road_information_pwd`
  ADD PRIMARY KEY (`ri_id`);

--
-- Indexes for table `road_type`
--
ALTER TABLE `road_type`
  ADD PRIMARY KEY (`road_id`);

--
-- Indexes for table `road_type_logs`
--
ALTER TABLE `road_type_logs`
  ADD PRIMARY KEY (`rt_logid`);

--
-- Indexes for table `roles_table`
--
ALTER TABLE `roles_table`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `service_charge_details`
--
ALTER TABLE `service_charge_details`
  ADD PRIMARY KEY (`app_id`);

--
-- Indexes for table `sku_master`
--
ALTER TABLE `sku_master`
  ADD PRIMARY KEY (`sku_id`);

--
-- Indexes for table `sub_department_table`
--
ALTER TABLE `sub_department_table`
  ADD PRIMARY KEY (`sub_dept_id`);

--
-- Indexes for table `temp_lic`
--
ALTER TABLE `temp_lic`
  ADD PRIMARY KEY (`lic_id`);

--
-- Indexes for table `trade_faclicapplication`
--
ALTER TABLE `trade_faclicapplication`
  ADD PRIMARY KEY (`tradefac_lic_id`);

--
-- Indexes for table `treecuttingapplications`
--
ALTER TABLE `treecuttingapplications`
  ADD PRIMARY KEY (`cutAppId`);

--
-- Indexes for table `treecuttingprocess`
--
ALTER TABLE `treecuttingprocess`
  ADD PRIMARY KEY (`processId`);

--
-- Indexes for table `treenames`
--
ALTER TABLE `treenames`
  ADD PRIMARY KEY (`tree_id`);

--
-- Indexes for table `unit_master`
--
ALTER TABLE `unit_master`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_dept_table`
--
ALTER TABLE `user_dept_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`per_id`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`ward_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adv_type`
--
ALTER TABLE `adv_type`
  MODIFY `adv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `applications_details`
--
ALTER TABLE `applications_details`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
--
-- AUTO_INCREMENT for table `application_remarks`
--
ALTER TABLE `application_remarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;
--
-- AUTO_INCREMENT for table `app_premssion`
--
ALTER TABLE `app_premssion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `app_status_level`
--
ALTER TABLE `app_status_level`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=645;
--
-- AUTO_INCREMENT for table `clinic_applications`
--
ALTER TABLE `clinic_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `clinic_staff`
--
ALTER TABLE `clinic_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT for table `closed_application_table`
--
ALTER TABLE `closed_application_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `company_address`
--
ALTER TABLE `company_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `company_details`
--
ALTER TABLE `company_details`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `defect_laiblity`
--
ALTER TABLE `defect_laiblity`
  MODIFY `laib_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `defect_laiblity_logs`
--
ALTER TABLE `defect_laiblity_logs`
  MODIFY `laib_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `department_table`
--
ALTER TABLE `department_table`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `deposit_inspection_fees`
--
ALTER TABLE `deposit_inspection_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `designation_master`
--
ALTER TABLE `designation_master`
  MODIFY `design_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `filmdata`
--
ALTER TABLE `filmdata`
  MODIFY `film_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gardendata`
--
ALTER TABLE `gardendata`
  MODIFY `gardenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `garden_permission`
--
ALTER TABLE `garden_permission`
  MODIFY `garper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `godownapplication`
--
ALTER TABLE `godownapplication`
  MODIFY `godown_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hall_applications`
--
ALTER TABLE `hall_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hall_assets`
--
ALTER TABLE `hall_assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `hall_checklist_details`
--
ALTER TABLE `hall_checklist_details`
  MODIFY `checklist_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hall_type`
--
ALTER TABLE `hall_type`
  MODIFY `hall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `hospital_alien`
--
ALTER TABLE `hospital_alien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
--
-- AUTO_INCREMENT for table `hospital_applications`
--
ALTER TABLE `hospital_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `hospital_fee_charges`
--
ALTER TABLE `hospital_fee_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `hospital_florespace_for_bedrooms`
--
ALTER TABLE `hospital_florespace_for_bedrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;
--
-- AUTO_INCREMENT for table `hospital_florespace_for_kitchen`
--
ALTER TABLE `hospital_florespace_for_kitchen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;
--
-- AUTO_INCREMENT for table `hospital_inspection_form`
--
ALTER TABLE `hospital_inspection_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `hospital_midwife`
--
ALTER TABLE `hospital_midwife`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT for table `hospital_staff_details`
--
ALTER TABLE `hospital_staff_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT for table `hospital_supervision`
--
ALTER TABLE `hospital_supervision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT for table `hospital_surgeon_information`
--
ALTER TABLE `hospital_surgeon_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `illuminate`
--
ALTER TABLE `illuminate`
  MODIFY `ill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `image_details`
--
ALTER TABLE `image_details`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=464;
--
-- AUTO_INCREMENT for table `joint_visit_extentions`
--
ALTER TABLE `joint_visit_extentions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `lab_applications`
--
ALTER TABLE `lab_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `lab_staff`
--
ALTER TABLE `lab_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `latter_generation`
--
ALTER TABLE `latter_generation`
  MODIFY `letter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `licdates`
--
ALTER TABLE `licdates`
  MODIFY `date_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lic_data`
--
ALTER TABLE `lic_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lic_type`
--
ALTER TABLE `lic_type`
  MODIFY `lic_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mandap_applications`
--
ALTER TABLE `mandap_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `permission_access`
--
ALTER TABLE `permission_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `permission_type_pwd`
--
ALTER TABLE `permission_type_pwd`
  MODIFY `pt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `price_master`
--
ALTER TABLE `price_master`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `property_type`
--
ALTER TABLE `property_type`
  MODIFY `prop_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pwd_applications`
--
ALTER TABLE `pwd_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `qualification_master`
--
ALTER TABLE `qualification_master`
  MODIFY `qual_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `req_type`
--
ALTER TABLE `req_type`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `road_information_pwd`
--
ALTER TABLE `road_information_pwd`
  MODIFY `ri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `road_type`
--
ALTER TABLE `road_type`
  MODIFY `road_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `road_type_logs`
--
ALTER TABLE `road_type_logs`
  MODIFY `rt_logid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `roles_table`
--
ALTER TABLE `roles_table`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `service_charge_details`
--
ALTER TABLE `service_charge_details`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sku_master`
--
ALTER TABLE `sku_master`
  MODIFY `sku_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `sub_department_table`
--
ALTER TABLE `sub_department_table`
  MODIFY `sub_dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `temp_lic`
--
ALTER TABLE `temp_lic`
  MODIFY `lic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `trade_faclicapplication`
--
ALTER TABLE `trade_faclicapplication`
  MODIFY `tradefac_lic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `treecuttingapplications`
--
ALTER TABLE `treecuttingapplications`
  MODIFY `cutAppId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `treecuttingprocess`
--
ALTER TABLE `treecuttingprocess`
  MODIFY `processId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `treenames`
--
ALTER TABLE `treenames`
  MODIFY `tree_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `unit_master`
--
ALTER TABLE `unit_master`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users_table`
--
ALTER TABLE `users_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `user_dept_table`
--
ALTER TABLE `user_dept_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `per_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `ward`
--
ALTER TABLE `ward`
  MODIFY `ward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
