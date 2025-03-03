-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 08:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skilldrop`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Kevin Murungi Tumaini', 'kevintumaini90@gmail.com', 'I have failed to use this platform', '2024-11-09 01:03:31'),
(2, 'Daniel', 'daniel@gmail.com', 'I would like to know more about your company', '2024-11-09 13:52:35'),
(3, 'Flavia', 'flavia@gmail.com', 'Tell me more about you', '2024-11-09 14:32:01'),
(4, 'Jordan', 'jordan@gmail.com', 'I love your services', '2024-11-10 13:14:02'),
(5, 'Allan', 'allan@gmail.com', 'I need to understand your services more, please', '2024-11-11 08:16:14'),
(6, 'Sheeba', 'sheeba@gmail.com', 'Admin please beera serious', '2024-11-25 10:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `professionals`
--

CREATE TABLE `professionals` (
  `user_id` int(11) DEFAULT NULL,
  `skill_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professionals`
--

INSERT INTO `professionals` (`user_id`, `skill_id`, `rating`) VALUES
(2, 3, 0),
(2, 2, 0),
(3, 1, 0),
(3, 3, 0),
(3, 2, 0),
(3, 4, 0),
(4, 1, 0),
(4, 2, 0),
(4, 3, 0),
(4, 4, 0),
(5, 5, 0),
(5, 6, 0),
(5, 9, 0),
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(7, 3, 0),
(7, 13, 0),
(7, 14, 0),
(7, 15, 0),
(8, 16, 0),
(8, 17, 0),
(8, 9, 0),
(6, 5, 0),
(6, 10, 0),
(6, 11, 0),
(6, 12, 0),
(6, 18, 0),
(9, 19, 0),
(9, 20, 0),
(9, 21, 0),
(9, 22, 0),
(9, 23, 0),
(9, 6, 0),
(9, 24, 0),
(9, 25, 0),
(11, 26, 0),
(12, 9, 0),
(10, 26, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `professional_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `professional_id`, `user_id`, `review_text`, `rating`, `created_at`) VALUES
(3, 3, 2, 'His food is good', 5, '2024-11-09 01:49:27'),
(6, 4, 1, 'Not Serious', 2, '2024-11-09 12:32:09'),
(7, 4, 1, 'Bambi she has tried today', 5, '2024-11-09 12:33:00'),
(8, 4, 6, 'She\'s really good at her job', 5, '2024-11-09 13:58:03'),
(9, 3, 7, 'He knows these pipe things very well', 4, '2024-11-09 14:36:17'),
(10, 3, 8, 'I didn\'t like his food', 2, '2024-11-10 13:20:02'),
(11, 4, 6, 'She is fair', 2, '2024-11-11 08:18:42'),
(12, 3, 9, 'He is professional', 5, '2024-11-25 10:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`) VALUES
(1, 'Plumber'),
(2, 'Teacher'),
(3, 'Engineer'),
(4, 'Cook'),
(5, 'Programming'),
(6, 'Cooking'),
(7, 'Comedy'),
(8, 'Cobbler'),
(9, 'chef'),
(10, 'Teaching'),
(11, 'Eating'),
(12, 'Saving'),
(13, 'Developer'),
(14, 'Designer'),
(15, 'Makeup Artist'),
(16, 'Programmer'),
(17, 'Tutor'),
(18, 'SQL'),
(19, 'Catering'),
(20, 'Business'),
(21, 'Makeup'),
(22, 'Hairdressing'),
(23, 'Singing'),
(24, 'Receptionist'),
(25, 'Waitress'),
(26, 'Software Engineer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `profile_image` varchar(225) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `location`, `profile_image`, `created_at`) VALUES
(1, 'Kevin Murungi Tumaini', 'kevintumaini90@gmail.com', '0743153188', '$2y$10$OdN1bQ.oXu7u9ytalrL3E.v7hjs5bUUy7Q7BT7X6LujW./nNo9UFu', 'Mbarara', NULL, '2024-11-08 23:13:57'),
(2, 'Mercy Kasembo', 'mercy@gmail.com', '0123456789', '$2y$10$KohkCb/CQHy6v/BoxlGTjO/jfOq3rQyzkGh3x4P0ixOS6U4KUtopK', 'Kampala', NULL, '2024-11-08 23:55:36'),
(3, 'Wilson Mwebaze', 'wilson@gmail.com', '0771234567', '$2y$10$2TNIKXp2myQ44R7ioP.b/OWwCzXwn9ufN0zEKS.OU9vW0FIUbeSt6', 'Kihumuro', NULL, '2024-11-09 00:52:13'),
(4, 'Tyra', 'tyra@gmail.com', '0123456789', '$2y$10$9ahagy4njDCj7iPTUi..mOsUhqlTONF6I4u88IEvL5JBqkopNPt4a', 'Entebbe', NULL, '2024-11-09 00:53:18'),
(5, 'Onina', 'onina@gmail.com', '01234', '$2y$10$u3HNjOCxHQZpL.aNJdQV6edn4QV/WaHHb28UWz4yzlR3tYU8FZsGm', 'Mbarara', NULL, '2024-11-09 10:07:37'),
(6, 'Daniel Astrav', 'daniel@gmail.com', '0123456789', '$2y$10$gjkGBX09mRZrIoPCcZtRlOWcWOFBEXX6NizNZ408nVPSxMnvzgAYm', 'Kampala', NULL, '2024-11-09 13:54:42'),
(7, 'Flavia', 'flavia@gmail.com', '0123456789', '$2y$10$BevMDkpRd1i5zBOxzmtJZuwlrauUWL9sgyCcbMnN/cMhms8XSqZ2W', 'Mbarara', NULL, '2024-11-09 14:33:44'),
(8, 'Jordan Arinda', 'jordan@gmail.com', '0123456789', '$2y$10$q21xDbh7Lxi2Jx4vBcr7geFwrTlMeRNUMyqjrs1lFIWVYHJttXzKy', 'Mbarara', NULL, '2024-11-10 13:16:18'),
(9, 'Sheeba Rain', 'sheeba@gmail.com', '0788790734', '$2y$10$SldYONYktE2hEHjNntrvUOwLXOdBslVrjeDSJm0B1cxjE62CQQL0q', 'Mbarara', NULL, '2024-11-25 10:44:24'),
(10, 'Ainamaani Allan ', '2023bse151@std.must.ac.ug', '0700868939', '$2y$10$rYi6wy.DoSib5zn/DxCKzOtDgGas1kSdMaWPjHORSwb5beAHFo2Lm', 'Mbarara', 'uploads/profile_images/10_alma.jpg', '2024-12-05 11:44:42'),
(11, 'Muhairwe Enock', 'menock@gmail.com', '0782123456', '$2y$10$ICibeiVFAwwGjM6VNteDSebBp9Ls/gvuCh/zcy6UHZxg93ds/lDlO', 'Mbarara', NULL, '2024-12-05 11:46:31'),
(12, 'Tuwangye Brave', 'tbrave@gmail.com', '0782123456', '$2y$10$O/C5RU49CX6mJdzLm2BUXeBvvhqHFDD3Hj8y7o9eNKVwKKdnR3Bi2', 'Kakoba', 'uploads/profile_images/12_9795860448618232371.png', '2024-12-05 11:47:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professional_id` (`professional_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `professionals`
--
ALTER TABLE `professionals`
  ADD CONSTRAINT `professionals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `professionals_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;