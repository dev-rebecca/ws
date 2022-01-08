-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 08, 2022 at 08:57 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wildlife-watcher`
--

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `animal_id` int(255) NOT NULL,
  `species_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `image_id` int(255) DEFAULT NULL,
  `nickname` varchar(30) NOT NULL,
  `first_seen_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` varchar(500) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `favourite` tinyint(1) DEFAULT NULL,
  `maturity` varchar(15) NOT NULL,
  `first_seen_long` varchar(400) DEFAULT NULL,
  `first_seen_lat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`animal_id`, `species_id`, `user_id`, `image_id`, `nickname`, `first_seen_date`, `notes`, `gender`, `favourite`, `maturity`, `first_seen_long`, `first_seen_lat`) VALUES
(220, 26, 91, 163, 'Momo', '2021-11-10 14:45:41', 'He so hungry', 'sdfsdf', NULL, 'Baby', '153.021072', '-27.470125'),
(221, 26, 91, 162, 'Momo Mum', '2021-11-01 14:46:24', 'She feed him all the things', 'Female', NULL, 'Adult', '153.021073', '-27.470125'),
(222, 29, 91, 161, 'Trilby', '2021-09-21 19:55:03', 'Trilby is my favourite thing to ever exist', 'Unknown', NULL, 'Adult', '153.075465', '-27.555016'),
(223, 25, 91, 164, 'Frilly', '2021-08-18 19:59:03', 'I\'ve never seen one of these in the wild until now', 'Unknown', NULL, 'Unknown', '153.021022\r\n', '-27.470123\r\n'),
(224, 30, 91, 166, 'Tortie', '2021-11-08 20:15:10', 'Seen at the creek at the start of the path - what a cutie', 'Unknown', NULL, 'Unknown', '153.021022', '-27.470123\r\n'),
(225, 27, 91, 168, 'Unamed', '2021-08-11 23:57:34', 'Lots of guppies found in the creek', 'Various', NULL, 'Various', '153.021092', '-27.470122'),
(226, 31, 91, 169, 'Greeny', '2021-11-10 00:00:08', 'He is green and he is a frog', 'Male', NULL, 'Adult', '153.021022', '-27.470123'),
(227, 63, 91, 171, 'Unamed', '2021-11-04 00:05:27', 'Pretty butterfly in backyard', 'Unknown', NULL, 'Unknown', '153.021030', '-27.47012598'),
(228, 64, 91, 172, 'Froggy', '2021-11-06 00:10:28', 'A tawny in the tree outside my house', 'Unknown', NULL, 'Adult', '153.021034', '-27.47012567'),
(229, 64, 91, 173, 'Swoopy', '2021-11-01 00:12:23', 'Super cute tawny with a bubba', 'Male', NULL, 'Adult', '153.021076', '-27.47012571'),
(230, 64, 91, 174, 'Branchy', '2021-10-20 00:13:03', 'Seen at the park, looking very suspiciously like a tree branch', 'Unknown', NULL, 'Adult', '153.021066', '-27.47012588'),
(231, 65, 91, 175, 'Ed', '2021-06-15 00:16:15', 'I can\'t believe that I have seen this echidna', 'sss', NULL, 'Adult', '153.02107261', '-27.47012598'),
(232, 66, 91, 176, 'Chip', '2021-11-11 00:19:14', 'She was in the tree outside near the balcony, very cute', 'Female', NULL, 'Adult', '153.021074', '-27.4701221'),
(233, 66, 91, 177, 'Unamed Possum', '2021-11-14 00:20:29', 'Havin\' a chomp', 'Unknown', NULL, 'Medium', '153.021072', '-27.470125'),
(234, 67, 91, 178, 'Mum', '2021-11-14 00:23:15', 'Seen outside in the tree, very cute', 'Female', NULL, 'Adult', '153.021073', '-27.470125'),
(235, 68, 91, 180, 'Chonker', '2021-11-14 00:25:24', 'An absolute chonker seen at the campsite', 'Female', NULL, 'Adult', '153.021074', '-27.470166'),
(236, 69, 91, 181, 'Snakey', '2021-11-14 00:27:13', 'He is harmless yet spooky', 'Unknown', NULL, 'Unknown', '153.021072', '-27.470125');

-- --------------------------------------------------------

--
-- Table structure for table `animal_type`
--

CREATE TABLE `animal_type` (
  `animal_type_id` int(255) NOT NULL,
  `type_name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `animal_type`
--

INSERT INTO `animal_type` (`animal_type_id`, `type_name`) VALUES
(17, 'Reptiles'),
(18, 'Invertebrates'),
(19, 'Fish'),
(20, 'Amphibians'),
(21, 'Birds'),
(22, 'Mammals');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`image_id`, `image`) VALUES
(134, '1636778753.jpg'),
(154, '1636794933.jpeg'),
(161, '1636797283.jpeg'),
(162, '1636797435.jpg'),
(163, '1636797451.jpeg'),
(164, '1636797497.jpeg'),
(165, '1636798315.jpeg'),
(166, '1636798483.jpeg'),
(167, '1636811749.jpeg'),
(168, '1636811785.jpeg'),
(169, '1636811963.jpg'),
(170, '1636812095.jpeg'),
(171, '1636812189.jpeg'),
(172, '1636812556.jpeg'),
(173, '1636812636.jpeg'),
(174, '1636812748.jpeg'),
(175, '1636812913.jpeg'),
(176, '1636813081.jpeg'),
(177, '1636813173.jpeg'),
(178, '1636813306.jpeg'),
(179, '1636813472.jpeg'),
(180, '1636813498.jpeg'),
(181, '1636813594.jpeg'),
(182, '1636863768.png'),
(183, '1636863818.png'),
(184, '1636864025.png');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(750) NOT NULL,
  `animal_id` int(255) NOT NULL,
  `title` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `date`, `text`, `animal_id`, `title`) VALUES
(16, '2021-10-12 05:13:16', 'I saw Momo sitting in the tree again today', 220, 'Birb in tree'),
(17, '2021-09-07 05:13:16', 'I saw this tawny again last night, he was very cool', 230, 'Wow'),
(18, '2021-07-06 05:13:16', 'I saw Trilby today, he was near the footpath eating some leaves', 222, 'Yay'),
(19, '2021-10-22 05:13:16', 'We saw him again today, in the tree on the far right. He was sleeping', 222, 'In the tree'),
(20, '2021-08-01 05:13:16', 'Once again I have been bless by the chonk', 235, 'Chonky'),
(21, '2021-11-14 05:14:28', 'He got really close to us today! ', 222, 'We are blessed');

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE `species` (
  `species_id` int(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Pending',
  `user_id` int(255) NOT NULL,
  `animal_type_id` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`species_id`, `name`, `status`, `user_id`, `animal_type_id`, `date`) VALUES
(25, 'Frill Neck Lizard', 'Approved', 91, 17, '2021-09-23 02:20:15'),
(26, 'Magpie', 'Approved', 91, 21, '2021-09-23 02:20:15'),
(27, 'Guppy', 'Approved', 91, 19, '2021-09-23 02:20:15'),
(29, 'Koala', 'Approved', 91, 22, '2021-09-23 02:20:15'),
(30, 'Turtle', 'Approved', 91, 17, '2021-09-23 02:20:15'),
(31, 'Green Tree Frog', 'Approved', 91, 20, '2021-09-23 02:29:50'),
(63, 'Butterfly', 'Pending', 91, 18, '2021-11-13 14:03:18'),
(64, 'Tawny Frogmouth', 'Pending', 91, 21, '2021-11-13 14:09:30'),
(65, 'Echidna', 'Pending', 91, 22, '2021-11-13 14:14:10'),
(66, 'Ring Tail Possum', 'Pending', 91, 22, '2021-11-13 14:18:09'),
(67, 'Brush Tail Possum', 'Pending', 91, 22, '2021-11-13 14:20:40'),
(68, 'Wallaby', 'Pending', 91, 22, '2021-11-13 14:23:39'),
(69, 'Green Tree Snake', 'Pending', 91, 17, '2021-11-13 14:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `pass`, `image`, `role`) VALUES
(91, 'Bobby', 'Smith', 'bob@gmail.com', 'Password!1', NULL, 'admin'),
(135, 'Toby', 'Moby', 'toby@gmail.com', 'Password!1', NULL, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `user_log_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `action` varchar(30) NOT NULL,
  `user_resp_code` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `species_id` (`species_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `animal_type`
--
ALTER TABLE `animal_type`
  ADD PRIMARY KEY (`animal_type_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `logs_ibfk_1` (`animal_id`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`species_id`),
  ADD KEY `animal_type_id` (`animal_type_id`),
  ADD KEY `species_ibfk_2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`user_log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `animal_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `animal_type`
--
ALTER TABLE `animal_type`
  MODIFY `animal_type_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `species`
--
ALTER TABLE `species`
  MODIFY `species_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `user_log_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `animals_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `animals_ibfk_3` FOREIGN KEY (`species_id`) REFERENCES `species` (`species_id`),
  ADD CONSTRAINT `animals_ibfk_4` FOREIGN KEY (`image_id`) REFERENCES `images` (`image_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animals` (`animal_id`) ON DELETE CASCADE;

--
-- Constraints for table `species`
--
ALTER TABLE `species`
  ADD CONSTRAINT `species_ibfk_1` FOREIGN KEY (`animal_type_id`) REFERENCES `animal_type` (`animal_type_id`),
  ADD CONSTRAINT `species_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
