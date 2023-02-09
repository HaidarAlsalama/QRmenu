-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2023 at 01:06 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hdr`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorys`
--

CREATE TABLE `categorys` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_users`
--

CREATE TABLE `facebook_users` (
  `id` int(11) NOT NULL,
  `facebook_id` varchar(128) NOT NULL,
  `name` varchar(512) NOT NULL,
  `e-mail` varchar(128) NOT NULL,
  `photo` text NOT NULL,
  `register_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` mediumtext NOT NULL,
  `details` mediumtext DEFAULT NULL,
  `price` mediumtext NOT NULL,
  `photo` mediumtext DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `just_perm_name`
--

CREATE TABLE `just_perm_name` (
  `id` int(11) NOT NULL,
  `code` varchar(512) NOT NULL,
  `name` varchar(512) NOT NULL,
  `need` varchar(512) DEFAULT NULL,
  `login` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `just_perm_name`
--

INSERT INTO `just_perm_name` (`id`, `code`, `name`, `need`, `login`) VALUES
(1, 'Show_All_Users', 'Show All Users', NULL, 'admin'),
(2, 'Control_Status_Account_User', 'Control Status Account User', 'Show All Users , Edit Information User', 'admin'),
(3, 'Control_Status_Account_Admin', 'Control Status Account Admin', 'Show All Users , Edit Information Admin', 'admin'),
(4, 'Edit_Permission_Admin', 'Edit Permission Admin', 'Show All Users', 'admin'),
(5, 'Edit_Permission_User', 'Edit Permission User', 'Show All Users', 'admin'),
(6, 'Create_A_Restaurant', 'Create A Restaurant', NULL, 'user'),
(7, 'Edit_Information_User', 'Edit Information User', 'Show All Users', 'admin'),
(8, 'Edit_Information_Admin', 'Edit Information Admin', 'Show All Users', 'admin'),
(9, 'Edit_Password_Admin', 'Edit Password Admin', 'Show All Users', 'admin'),
(10, 'Edit_Password_User', 'Edit Password User', 'Show All Users', 'admin'),
(11, 'Add_New_Admin', 'Add New Admin', NULL, 'admin'),
(12, 'Add_New_User', 'Add New User', NULL, 'admin'),
(13, 'Add_New_subordinate', 'Add New subordinate', NULL, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission` varchar(512) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `nameSpecial` varchar(512) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `location` text NOT NULL,
  `mobile` text NOT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `theme` varchar(128) NOT NULL DEFAULT 'D-NoPhoto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `device` text DEFAULT NULL,
  `os` varchar(512) DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `ip` text NOT NULL,
  `sid` varchar(512) NOT NULL,
  `last_seen` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `properties` text NOT NULL,
  `attribute` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `name` mediumtext NOT NULL,
  `username` varchar(512) NOT NULL,
  `e-mail` varchar(256) DEFAULT NULL,
  `mobile` varchar(512) DEFAULT NULL,
  `password` mediumtext NOT NULL,
  `language` mediumtext NOT NULL DEFAULT 'en',
  `parent_id` int(11) DEFAULT NULL,
  `who_added` int(11) NOT NULL,
  `address` mediumtext DEFAULT NULL,
  `register_date` mediumtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ncc` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verifies`
--

CREATE TABLE `verifies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `is_confirmed` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `facebook_users`
--
ALTER TABLE `facebook_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_id` (`facebook_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `just_perm_name`
--
ALTER TABLE `just_perm_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission` (`permission`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nameSpecial` (`nameSpecial`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `e-mail` (`e-mail`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `users_ibfk_2` (`who_added`);

--
-- Indexes for table `verifies`
--
ALTER TABLE `verifies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorys`
--
ALTER TABLE `categorys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_users`
--
ALTER TABLE `facebook_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `just_perm_name`
--
ALTER TABLE `just_perm_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifies`
--
ALTER TABLE `verifies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorys`
--
ALTER TABLE `categorys`
  ADD CONSTRAINT `categorys_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categorys` (`id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`who_added`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `verifies`
--
ALTER TABLE `verifies`
  ADD CONSTRAINT `verifies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
