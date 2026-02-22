-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 04:53 PM
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
-- Database: `herana_pastry`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `email`) VALUES
(1, 'admin', 'admin123', NULL, NULL),
(2, 'admin@gmail.com', '123', NULL, NULL),
(3, NULL, '123456', 'heljie palapas', 'palapasgeraldine@gmail.com'),
(4, NULL, 'qwerty', 'heljie palapas', 'palapas@gmail.com'),
(5, NULL, 'zxcvbn', 'heljie herana', 'herana@gmail.com'),
(6, 'jos', '1234567', NULL, NULL),
(7, 'patotoya', 'asdfgh', NULL, NULL),
(8, 'patotoyas', '098765', NULL, NULL),
(9, 'jis', '/.,mnb', NULL, NULL),
(10, 'esme', '123456', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_products`
--

CREATE TABLE `archived_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `archived_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `items` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Processing','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`product_id`, `user_id`, `product_name`, `price`, `quantity`, `items`, `total`, `status`, `created_at`) VALUES
(42, 3, 'Salted Caramel', 120.00, 1, NULL, NULL, 'Delivered', '2026-02-19 14:01:50'),
(43, 6, 'choco', 490.00, 9, NULL, NULL, 'Delivered', '2026-02-20 14:49:42'),
(44, 6, 'choco', 490.00, 1, NULL, NULL, 'Delivered', '2026-02-22 15:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT 'Cake',
  `category2` varchar(50) DEFAULT 'Cupcake'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`, `category2`) VALUES
(24, 'chocolate', 490.00, '1771598932_699874543c3f7.webp', 'Pastries', 'Cupcake'),
(25, 'choco', 490.00, '1771601398_69987df681727.jpg', 'Pastries', 'Cupcake'),
(26, 'choco', 490.00, '1771774978_699b24029e71c.webp', 'Cakes', 'Cupcake');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `created_at`) VALUES
(2, 'jie', '', 'jie@gmail.com', '$2y$10$noCpmOiWNyDpIVwYMoY6e.AKnhrYpMlle2mFde4GOqXPNHP8mDhXC', '2026-02-19 14:00:41'),
(3, 'email', '', 'email@123', '$2y$10$3frW74MJ2lvN.XaI8YA8fujLF.YOO6U67jiuMaF3STbo.qQQACzFi', '2026-02-19 14:01:34'),
(4, 'jo', '', 'jo@gmail.com', '$2y$10$miQ1JlmjfYm38vSCr.yB2uspzPNMkS4LBhflax/ArCAHpu2lZQtju', '2026-02-19 14:11:47'),
(5, 'jis', '', 'jis@gmail.com', '$2y$10$dgkdRjI6FdqQnBLizzMVxuf4tttZdEVnmVm5KgLCHTZAeTPmQczjm', '2026-02-19 15:25:07'),
(6, 'esme', '', 'esme@gmail.com', '$2y$10$8gm5hJzWZF2VSKWL55NcG.kpSLOHq0DnypeJ.Tp2r65PqDjKUDD6.', '2026-02-20 14:49:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `archived_products`
--
ALTER TABLE `archived_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
