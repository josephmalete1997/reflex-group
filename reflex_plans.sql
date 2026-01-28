-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 08:43 AM
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
-- Database: `reflex_plans`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(2, 'Administrator', 'admin@reflexgroupco.co.za', 'Admin@123', '2026-01-15 17:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `full_desc` text NOT NULL,
  `old_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `new_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bedrooms` int(11) NOT NULL DEFAULT 0,
  `bathrooms` int(11) NOT NULL DEFAULT 0,
  `garage` int(11) NOT NULL DEFAULT 0,
  `sqm` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stories` int(11) NOT NULL DEFAULT 1,
  `style` varchar(80) NOT NULL,
  `dimensions` varchar(80) NOT NULL,
  `floor_plan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `img`, `short_desc`, `full_desc`, `old_price`, `new_price`, `bedrooms`, `bathrooms`, `garage`, `sqm`, `stories`, `style`, `dimensions`, `floor_plan`, `created_at`) VALUES
('d23908', '4 Bedroom House Plan MLB 007.11S', 'uploads/plans/plan_15fa3471bdd8cf195688.jpg', '483m² • 4 bedrooms • 1 Level • 2-car garage • 27m x 26.4m', 'Test', 100.00, 200.00, 2, 3, 4, 200.00, 2, 'Modern', '27m x 26.4m', 'plans/pl1-floor.png', '2026-01-16 10:25:57'),
('mlb00711s', '4 Bedroom House Plan MLB 007.11S', 'plans/pl1.png', '483m² • 4 bedrooms • 1 Level • 2-car garage • 27m x 26.4m', 'This stunning single-level 4 bedroom house plan offers spacious living...', 12075.00, 9660.00, 4, 3, 2, 483.00, 1, 'Modern', '27m x 26.4m', 'plans/pl1-floor.png', '2026-01-15 15:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `id` int(11) NOT NULL,
  `plan_id` varchar(32) NOT NULL,
  `feature` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_features`
--

INSERT INTO `plan_features` (`id`, `plan_id`, `feature`) VALUES
(1, 'mlb00711s', 'Open-plan living and dining area'),
(2, 'mlb00711s', 'Modern fitted kitchen with island');

-- --------------------------------------------------------

--
-- Table structure for table `plan_gallery`
--

CREATE TABLE `plan_gallery` (
  `id` int(11) NOT NULL,
  `plan_id` varchar(32) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_plans_style` (`style`),
  ADD KEY `idx_plans_bedrooms` (`bedrooms`),
  ADD KEY `idx_plans_price` (`new_price`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `plan_gallery`
--
ALTER TABLE `plan_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan_features`
--
ALTER TABLE `plan_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan_gallery`
--
ALTER TABLE `plan_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `plan_features_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_gallery`
--
ALTER TABLE `plan_gallery`
  ADD CONSTRAINT `plan_gallery_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
