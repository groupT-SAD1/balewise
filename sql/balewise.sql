-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2026 at 06:39 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `balewise`
--

-- --------------------------------------------------------

--
-- Table structure for table `bales`
--

CREATE TABLE `bales` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `weight_kg` decimal(8,2) NOT NULL,
  `asking_price` decimal(14,2) NOT NULL,
  `sample_a` int(11) NOT NULL DEFAULT 0,
  `sample_b` int(11) NOT NULL DEFAULT 0,
  `sample_c` int(11) NOT NULL DEFAULT 0,
  `sample_r` int(11) NOT NULL DEFAULT 0,
  `base_value` decimal(14,2) NOT NULL,
  `est_low` decimal(14,2) NOT NULL,
  `est_high` decimal(14,2) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `actual_value` decimal(14,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `opened_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bales`
--

INSERT INTO `bales` (`id`, `supplier_id`, `weight_kg`, `asking_price`, `sample_a`, `sample_b`, `sample_c`, `sample_r`, `base_value`, `est_low`, `est_high`, `photo_path`, `actual_value`, `created_at`, `opened_at`) VALUES
(2, 2, 45.00, 380000.00, 6, 3, 1, 0, 432000.00, 345600.00, 432000.00, NULL, 362880.00, '2026-11-15 08:08:32', '2026-11-15 08:08:32'),
(3, 2, 50.00, 400000.00, 5, 4, 1, 0, 455000.00, 364000.00, 455000.00, NULL, 395850.00, '2026-11-15 08:08:32', '2026-11-15 08:08:32'),
(4, 2, 48.00, 390000.00, 6, 2, 2, 0, 441600.00, 353280.00, 441600.00, NULL, 375360.00, '2026-11-15 08:08:32', '2026-11-15 08:08:32'),
(5, 3, 40.00, 300000.00, 3, 4, 3, 0, 292000.00, 233600.00, 292000.00, NULL, 297840.00, '2026-11-15 08:08:32', '2026-11-15 08:08:32'),
(6, 3, 42.00, 310000.00, 4, 4, 2, 0, 344400.00, 275520.00, 344400.00, NULL, 337512.00, '2026-11-15 08:08:32', '2026-11-15 08:08:32'),
(7, 2, 45.00, 380000.00, 6, 3, 1, 0, 432000.00, 339148.80, 398131.20, NULL, NULL, '2026-11-15 08:10:00', NULL),
(8, 3, 45.00, 380000.00, 6, 3, 1, 0, 432000.00, 397440.00, 466560.00, NULL, NULL, '2026-11-15 08:11:00', NULL),
(9, 2, 45.00, 380000.00, 6, 3, 1, 0, 432000.00, 339148.80, 398131.20, 'ea7c5c1fe3b367ff.jpg', 380000.00, '2026-11-15 08:23:07', '2026-11-15 08:23:40'),
(10, 2, 45.00, 400000.00, 6, 3, 1, 0, 432000.00, 341761.64, 401198.44, NULL, 500000.00, '2026-11-15 08:46:53', '2026-11-15 08:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `grade_prices`
--

CREATE TABLE `grade_prices` (
  `grade` char(1) NOT NULL,
  `price_per_kg` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_prices`
--

INSERT INTO `grade_prices` (`grade`, `price_per_kg`) VALUES
('A', 12000.00),
('B', 7000.00),
('C', 3000.00),
('R', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `created_at`) VALUES
(2, 'Kampala Imports', '2026-11-15 08:08:32'),
(3, 'Mombasa Wholesale', '2026-11-15 08:08:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bales`
--
ALTER TABLE `bales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `grade_prices`
--
ALTER TABLE `grade_prices`
  ADD PRIMARY KEY (`grade`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bales`
--
ALTER TABLE `bales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bales`
--
ALTER TABLE `bales`
  ADD CONSTRAINT `bales_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
