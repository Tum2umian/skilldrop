-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 08:45 AM
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
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 12, 'Profile Update', 'Profile updated: Name, Email, Phone, Location, Password changed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 15:34:11'),
(2, 12, 'Profile Update', 'Profile updated: Name, Email, Phone, Location', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 15:47:15'),
(3, 12, 'Profile Update', 'Profile updated: Name, Email, Phone, Location', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 16:43:32'),
(4, 12, 'profile_update', 'Location changed from \'Rakai\' to \'Jinja\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 16:47:17'),
(5, 13, 'unsuspend', 'Unsuspended user ID: 1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:01'),
(6, 13, 'unsuspend', 'Unsuspended user ID: 2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:07'),
(7, 13, 'unsuspend', 'Unsuspended user ID: 3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:07'),
(8, 13, 'unsuspend', 'Unsuspended user ID: 4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:09'),
(9, 13, 'unsuspend', 'Unsuspended user ID: 5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:11'),
(10, 13, 'unsuspend', 'Unsuspended user ID: 6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:12'),
(11, 13, 'unsuspend', 'Unsuspended user ID: 7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:13'),
(12, 13, 'unsuspend', 'Unsuspended user ID: 8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:14'),
(13, 13, 'unsuspend', 'Unsuspended user ID: 9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:17'),
(14, 13, 'unsuspend', 'Unsuspended user ID: 11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:19'),
(15, 13, 'unsuspend', 'Unsuspended user ID: 12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0', '2025-02-26 16:56:20'),
(16, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:10'),
(17, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:30'),
(18, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:31'),
(19, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:34'),
(20, 13, 'unsuspend', 'Unsuspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:36'),
(21, 13, 'unsuspend', 'Unsuspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:38'),
(22, 13, 'suspend', 'Suspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:42'),
(23, 13, 'unsuspend', 'Unsuspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:44'),
(24, 13, 'unsuspend', 'Unsuspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:45'),
(25, 13, 'suspend', 'Suspend user ID: 9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:45'),
(26, 13, 'suspend', 'Suspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:47'),
(27, 13, 'suspend', 'Suspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:48'),
(28, 13, 'suspend', 'Suspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:48'),
(29, 13, 'suspend', 'Suspend user ID: 9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:49'),
(30, 13, 'unsuspend', 'Unsuspend user ID: 9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:53'),
(31, 13, 'unsuspend', 'Unsuspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:55'),
(32, 13, 'unsuspend', 'Unsuspend user ID: 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:58'),
(33, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:01:58'),
(34, 13, 'suspend', 'Suspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:02:00'),
(35, 13, 'unsuspend', 'Unsuspend user ID: 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:02:02'),
(36, 13, 'suspend_user', 'Suspended user with ID 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:14:20'),
(37, 13, 'unsuspend_user', 'Unsuspended user with ID 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:14:26'),
(38, 13, 'approve_user', 'Approved user with ID 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:16:51'),
(39, 13, 'approve_user', 'Approved user with ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:25:59'),
(40, 13, 'suspend_user', 'Suspended user with ID 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-26 17:29:09'),
(41, 13, 'suspend_user', 'Suspended user with ID 9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-27 08:26:53'),
(42, 12, 'profile_update', 'Location changed from \'Jinja\' to \'Gulu\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0', '2025-02-27 08:47:40');

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
(13, 27, 0);

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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `service_name`, `description`, `created_at`, `updated_at`) VALUES
(5, 12, 'IT Admin', 'I manage servers at enterprise level :)', '2025-02-26 15:03:48', '2025-02-27 08:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `professional_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT 'SkillDrop',
  `site_email` varchar(255) NOT NULL DEFAULT 'admin@skilldrop.com',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_email`, `maintenance_mode`, `created_at`, `updated_at`) VALUES
(1, 'SkillDrop', 'admin@skilldrop.com', 0, '2025-02-26 13:21:03', '2025-02-26 13:21:03');

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
(26, 'Software Engineer'),
(27, 'Dev Ops');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
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

INSERT INTO `users` (`id`, `full_name`, `role`, `status`, `email`, `phone`, `password`, `location`, `profile_image`, `created_at`) VALUES
(1, 'Kevin Murungi Tumaini', '', 'active', 'kevintumaini90@gmail.com', '0743153188', '$2y$10$OdN1bQ.oXu7u9ytalrL3E.v7hjs5bUUy7Q7BT7X6LujW./nNo9UFu', 'Mbarara', NULL, '2024-11-08 23:13:57'),
(2, 'Mercy Kasembo', '', 'active', 'mercy@gmail.com', '0123456789', '$2y$10$KohkCb/CQHy6v/BoxlGTjO/jfOq3rQyzkGh3x4P0ixOS6U4KUtopK', 'Kampala', NULL, '2024-11-08 23:55:36'),
(3, 'Wilson Mwebaze', '', 'active', 'wilson@gmail.com', '0771234567', '$2y$10$2TNIKXp2myQ44R7ioP.b/OWwCzXwn9ufN0zEKS.OU9vW0FIUbeSt6', 'Kihumuro', NULL, '2024-11-09 00:52:13'),
(4, 'Tyra', '', 'active', 'tyra@gmail.com', '0123456789', '$2y$10$9ahagy4njDCj7iPTUi..mOsUhqlTONF6I4u88IEvL5JBqkopNPt4a', 'Entebbe', NULL, '2024-11-09 00:53:18'),
(5, 'Onina', '', 'active', 'onina@gmail.com', '01234', '$2y$10$u3HNjOCxHQZpL.aNJdQV6edn4QV/WaHHb28UWz4yzlR3tYU8FZsGm', 'Mbarara', NULL, '2024-11-09 10:07:37'),
(6, 'Daniel Astrav', '', 'active', 'daniel@gmail.com', '0123456789', '$2y$10$gjkGBX09mRZrIoPCcZtRlOWcWOFBEXX6NizNZ408nVPSxMnvzgAYm', 'Kampala', NULL, '2024-11-09 13:54:42'),
(7, 'Flavia', '', 'active', 'flavia@gmail.com', '0123456789', '$2y$10$BevMDkpRd1i5zBOxzmtJZuwlrauUWL9sgyCcbMnN/cMhms8XSqZ2W', 'Mbarara', NULL, '2024-11-09 14:33:44'),
(8, 'Jordan Arinda', '', 'active', 'jordan@gmail.com', '0123456789', '$2y$10$q21xDbh7Lxi2Jx4vBcr7geFwrTlMeRNUMyqjrs1lFIWVYHJttXzKy', 'Mbarara', NULL, '2024-11-10 13:16:18'),
(9, 'Sheeba Rain', '', 'suspended', 'sheeba@gmail.com', '0788790734', '$2y$10$SldYONYktE2hEHjNntrvUOwLXOdBslVrjeDSJm0B1cxjE62CQQL0q', 'Mbarara', NULL, '2024-11-25 10:44:24'),
(11, 'Muhairwe Enock', '', 'active', 'menock@gmail.com', '0782123456', '$2y$10$ICibeiVFAwwGjM6VNteDSebBp9Ls/gvuCh/zcy6UHZxg93ds/lDlO', 'Mbarara', NULL, '2024-12-05 11:46:31'),
(12, 'Tuwangye Brave Pro', 'worker', 'suspended', 'tbrave@gmail.com', '0782123456', '$2y$10$wjzVM8QqyMzD21jYCmusO.0bkSpAdrsSLy4IMpj0FcFFFPcFyItQ2', 'Gulu', 'uploads/profile_images/12_9795860448618232371.png', '2024-12-05 11:47:57'),
(13, 'Ainamaani Allan Mwesigye', 'admin', '', '2023bse151@std.must.ac.ug', '0700868939', '$2y$10$b7dIFwbrgspoies3qxklJ.cMDTiV/qvGtxUwg7LYfLIuEKx79cese', 'Lugazi', 'uploads/profile_images/13_alma.jpg', '2025-02-10 09:42:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_services_user` (`user_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_professional` (`professional_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `fk_professional` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
