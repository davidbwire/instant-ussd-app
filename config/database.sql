-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 15, 2017 at 12:27 PM
-- Server version: 5.7.18
-- PHP Version: 7.0.20-2~ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `instant_ussd`
--

-- --------------------------------------------------------

--
-- Table structure for table `iussd_skippable_ussd_menu`
--

CREATE TABLE `iussd_skippable_ussd_menu` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(100) NOT NULL,
  `menu_id` varchar(100) NOT NULL,
  `is_skippable` tinyint(1) DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `iussd_ussd_loop`
--

CREATE TABLE `iussd_ussd_loop` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `loopset_name` varchar(100) NOT NULL,
  `loops_required` tinyint(3) UNSIGNED NOT NULL,
  `loops_done_so_far` tinyint(3) UNSIGNED DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='loops executed in a certain run on a certain loopset';


-- --------------------------------------------------------

--
-- Table structure for table `iussd_ussd_menus_served`
--

CREATE TABLE `iussd_ussd_menus_served` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `menu_id` varchar(100) NOT NULL,
  `is_loop_end` tinyint(1) DEFAULT '0',
  `loopset_name` varchar(100) DEFAULT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='- Lists the menus served to a specific user; in a specific order\n- is_loop_end && loopset_name help in decrementing loops_done_so_far incase a user decides  to go back';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iussd_skippable_ussd_menu`
--
ALTER TABLE `iussd_skippable_ussd_menu`
  ADD PRIMARY KEY (`reference_id`,`menu_id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_skippable_ussd_menu_bus_company1_idx` (`reference_id`);

--
-- Indexes for table `iussd_ussd_loop`
--
ALTER TABLE `iussd_ussd_loop`
  ADD PRIMARY KEY (`session_id`,`loopset_name`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `iussd_ussd_menus_served`
--
ALTER TABLE `iussd_ussd_menus_served`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iussd_skippable_ussd_menu`
--
ALTER TABLE `iussd_skippable_ussd_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `iussd_ussd_loop`
--
ALTER TABLE `iussd_ussd_loop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `iussd_ussd_menus_served`
--
ALTER TABLE `iussd_ussd_menus_served`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
