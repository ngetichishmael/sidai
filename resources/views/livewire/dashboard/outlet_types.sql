-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2023 at 01:58 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sidai`
--

-- --------------------------------------------------------

--
-- Table structure for table `outlet_types`
--

CREATE TABLE `outlet_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_code` varchar(255) DEFAULT NULL,
  `business_code` varchar(255) DEFAULT NULL,
  `outlet_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outlet_types`
--

INSERT INTO `outlet_types` (`id`, `outlet_code`, `business_code`, `outlet_name`, `created_at`, `updated_at`) VALUES
(1, 'i8P0ibLjzBszXwUXtp1P', 'Qx4FstqLJfHwf3WA', 'Wholesaler', '2023-03-28 19:41:38', '2023-03-28 19:41:38'),
(2, 'WIUpJgWqTq0pXsJt934L', 'Qx4FstqLJfHwf3WA', 'Retailer', '2023-03-28 19:41:44', '2023-03-28 19:41:44'),
(3, 'd4O0j8aups5xjfZVjXKX', 'Qx4FstqLJfHwf3WA', 'Distributor', '2023-04-14 06:56:36', '2023-04-14 06:56:36'),
(4, '7Fi5hjXOcE8CVykqvLUb', 'Qx4FstqLJfHwf3WA', 'Fred test LLC', '2023-05-17 12:21:31', '2023-05-17 12:21:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `outlet_types`
--
ALTER TABLE `outlet_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `outlet_types`
--
ALTER TABLE `outlet_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
