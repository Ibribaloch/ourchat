-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 09:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `se-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `message_type` varchar(20) DEFAULT 'text',
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `from_id`, `to_id`, `message`, `opened`, `created_at`, `image`, `message_type`, `deleted`) VALUES
(1, 2, 1, 'hi\n', 1, '2025-12-23 23:34:36', NULL, 'text', 0),
(2, 1, 2, 'hi', 1, '2025-12-23 23:34:50', NULL, 'text', 0),
(3, 1, 2, 'hi\n', 1, '2025-12-23 23:54:29', NULL, 'text', 0),
(4, 1, 2, '', 1, '2025-12-24 00:17:55', 'chat_694aeae3f19977.56076035.pptx', 'text', 0),
(5, 1, 2, '', 1, '2025-12-24 00:19:58', 'chat_694aeb5ea7d182.99181356.png', 'text', 0),
(6, 2, 1, '', 1, '2025-12-24 00:22:37', 'chat_694aebfd78d281.47805056.png', 'text', 0),
(7, 1, 2, 'ok', 1, '2025-12-24 00:23:37', NULL, 'text', 0),
(8, 2, 1, '', 1, '2025-12-24 00:24:05', 'chat_694aec550bb204.05885168.png', 'text', 0),
(9, 1, 2, '', 1, '2025-12-24 00:27:15', 'chat_694aed136ddf71.31232589.png', 'text', 0),
(10, 1, 2, 'hi', 1, '2025-12-24 00:37:32', NULL, 'text', 0),
(11, 2, 1, 'hi', 1, '2025-12-24 00:37:50', NULL, 'text', 0),
(12, 1, 2, 'hi', 1, '2025-12-24 00:39:27', NULL, 'text', 0),
(13, 2, 1, 'hi', 1, '2025-12-24 00:39:43', NULL, 'text', 0),
(14, 2, 1, 'hi', 1, '2025-12-24 00:49:02', NULL, 'text', 0),
(15, 2, 1, 'hi', 1, '2025-12-24 00:49:12', NULL, 'text', 0),
(16, 2, 1, 'hi', 1, '2025-12-24 00:49:22', NULL, 'text', 0),
(17, 2, 1, 'hi', 1, '2025-12-24 00:49:42', NULL, 'text', 0),
(18, 1, 2, 'hi', 1, '2025-12-24 00:52:24', NULL, 'text', 0),
(19, 1, 2, 'hi', 1, '2025-12-24 00:52:38', NULL, 'text', 0),
(20, 1, 2, 'hi', 1, '2025-12-24 00:52:48', NULL, 'text', 0),
(21, 2, 1, 'hi', 1, '2025-12-24 00:55:57', NULL, 'text', 0),
(22, 2, 1, 'hi', 1, '2025-12-24 00:56:09', NULL, 'text', 0),
(23, 1, 2, 'hh', 1, '2025-12-24 01:10:51', NULL, 'text', 0),
(24, 2, 1, 'hh', 1, '2025-12-24 01:11:04', NULL, 'text', 0),
(25, 1, 2, 'hi', 1, '2025-12-24 01:11:39', NULL, 'text', 0),
(26, 1, 2, 'hi', 1, '2025-12-24 01:11:51', NULL, 'text', 0),
(27, 1, 2, '', 1, '2025-12-24 01:12:19', 'chat_694af7a36072c4.41911629.png', 'text', 0),
(28, 1, 2, 'hh', 1, '2025-12-24 01:12:37', NULL, 'text', 0),
(29, 1, 2, 'hh', 1, '2025-12-24 01:12:52', NULL, 'text', 0),
(30, 1, 2, 'hh', 1, '2025-12-24 01:15:32', NULL, 'text', 0),
(31, 1, 2, 'hh', 1, '2025-12-24 01:16:39', NULL, 'text', 0),
(32, 2, 1, 'hh', 0, '2025-12-24 01:16:47', NULL, 'text', 0);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`conversation_id`, `user_1`, `user_2`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `p_p` varchar(255) DEFAULT 'user-default.png',
  `last_seen` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `p_p`, `last_seen`) VALUES
(1, 'Ibrahim Baloch', 'ibribaloch123@gmail.com', '$2y$10$CzcnkyZuYIfABnq79Urz.e27OSBFla.igVj2oGslvyGr7cd6ep2LC', 'ibribaloch123@gmail.com.png', '2025-12-24 01:20:48'),
(2, 'waina obaid', 'wania', '$2y$10$HW4Y7LG7MjZ.rE5qCmPHOOw0cNDV4fHnQbSRfpq3n6kOhjXceKbBG', 'user-default.png', '2025-12-24 01:21:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
