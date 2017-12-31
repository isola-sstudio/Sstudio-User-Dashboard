-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2017 at 05:59 AM
-- Server version: 5.7.18-1
-- PHP Version: 7.0.20-2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thestart_upstudio`
--
CREATE DATABASE IF NOT EXISTS `thestart_upstudio` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `thestart_upstudio`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_task`
--

CREATE TABLE `admin_task` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `task_name` varchar(200) NOT NULL,
  `task_description` text,
  `status` char(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tss_package_subscription`
--

CREATE TABLE `tss_package_subscription` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'just a normal id for the table',
  `company_name` varchar(60) NOT NULL COMMENT 'now it''s just the name of the intended user of the admin dashboard',
  `company_email` varchar(320) NOT NULL COMMENT 'now it''s just the email of the intended user of the admin dashboard',
  `password` char(32) NOT NULL,
  `contact_number` varchar(14) NOT NULL COMMENT 'contact number filled on the modal form on the pricing page',
  `project_description` text COMMENT 'project description filled on the modal form on the pricing page',
  `stripe_customer_id` varchar(255) DEFAULT NULL COMMENT 'stripe customer id created by at stripe''s checkout api',
  `subscription_plan` varchar(8) DEFAULT NULL COMMENT 'the plan chosen on the pricing page, basic, standard or custom',
  `subscription_status` char(1) NOT NULL DEFAULT '0' COMMENT 'subscription status as in active or inactive based on expired subscription or unexpired, pay now or pay later',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time when the entry was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_task`
--
ALTER TABLE `admin_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tss_package_subscription`
--
ALTER TABLE `tss_package_subscription`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_task`
--
ALTER TABLE `admin_task`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tss_package_subscription`
--
ALTER TABLE `tss_package_subscription`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'just a normal id for the table';
--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_task`
--
ALTER TABLE `admin_task`
  ADD CONSTRAINT `admin_task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tss_package_subscription` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
