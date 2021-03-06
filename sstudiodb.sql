-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 10, 2018 at 07:59 PM
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
-- Table structure for table `admin_billing`
--

CREATE TABLE `admin_billing` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `plan` varchar(8) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `task_priority` char(3) NOT NULL COMMENT 'field that represents the priority of the task as determined by the user max is 100',
  `due_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date at which the task is expected to be completed',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_completed` timestamp NULL DEFAULT NULL COMMENT 'date the task was completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tss_package_subscription`
--

CREATE TABLE `tss_package_subscription` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'just a normal id for the table',
  `company_name` varchar(60) NOT NULL COMMENT 'now it''s just the name of the intended user of the admin dashboard',
  `company_email` varchar(320) NOT NULL COMMENT 'now it''s just the email of the intended user of the admin dashboard',
  `password` char(32) DEFAULT NULL,
  `contact_number` varchar(14) DEFAULT NULL COMMENT 'contact number filled on the modal form on the pricing page',
  `country` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `wallpaper` varchar(255) DEFAULT NULL,
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
-- Indexes for table `admin_billing`
--
ALTER TABLE `admin_billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `admin_billing`
--
ALTER TABLE `admin_billing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
-- Constraints for table `admin_billing`
--
ALTER TABLE `admin_billing`
  ADD CONSTRAINT `admin_billing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tss_package_subscription` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `admin_task`
--
ALTER TABLE `admin_task`
  ADD CONSTRAINT `admin_task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tss_package_subscription` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
