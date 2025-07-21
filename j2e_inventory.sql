-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 11:26 PM
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
-- Database: `j2e_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'testCategory', 'testCategory', '2025-07-20 22:33:17', '2025-07-20 22:33:17', 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_contact` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `invoice_number`, `invoice_date`, `customer_name`, `customer_contact`, `total_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(11, '2523423', '2025-07-20 00:00:00', '432432423', '4234234', 936.00, 'paid', '234324', 2, '2025-07-21 05:36:48', '2025-07-21 05:36:48'),
(12, '123', '2025-07-20 00:00:00', 'Client1', '09064418621', 4.00, 'pending', 'Test', 2, '2025-07-21 06:02:11', '2025-07-21 06:02:11'),
(13, 'Client2', '2025-07-20 00:00:00', 'Joseph Michael', '09123123987', 1299.00, 'pending', '', 2, '2025-07-21 06:05:49', '2025-07-21 06:05:49'),
(14, '345345', '2025-07-20 00:00:00', 'test1', 'test123', 246.00, 'paid', '', 2, '2025-07-21 06:11:02', '2025-07-21 06:11:02'),
(15, '456789', '2025-07-20 00:00:00', 'Client 1234', '1231546457', 1245.00, 'draft', '', 2, '2025-07-21 06:17:55', '2025-07-21 06:17:55'),
(16, '123456', '2025-07-20 00:00:00', 'Moises', '0922216200', 258.00, 'partial', '', 2, '2025-07-21 06:24:20', '2025-07-21 06:24:20'),
(17, '123123123', '2025-07-20 00:00:00', 'John', '1233123123', 14796.00, 'pending', '', 2, '2025-07-21 06:25:12', '2025-07-21 06:25:12'),
(18, '323535345', '2025-07-20 00:00:00', 'Mosies John', '32479328579324', 123.00, 'pending', '', 2, '2025-07-21 06:37:17', '2025-07-21 06:37:17'),
(19, '45345345', '2025-07-20 00:00:00', 'eetgsdfgert', '345345', 345.00, 'draft', '', 2, '2025-07-21 06:38:06', '2025-07-21 06:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_item_id`, `invoice_id`, `product_id`, `product_name`, `quantity`, `unit`, `unit_price`, `discount`, `subtotal`) VALUES
(1, 11, 2, 'Paraffin Wax Bath', 4, '234', 234.00, 0.00, 936.00),
(2, 12, 1, 'Hydrocollator Moist Heat Pack', 2, '23', 2.00, 0.00, 4.00),
(3, 13, 3, 'Therapeutic Ultrasound Machine', 1, '2', 1299.00, 0.00, 1299.00),
(4, 14, 31, 'testProd', 2, 'kilo', 123.00, 0.00, 246.00),
(5, 15, 1, 'Hydrocollator Moist Heat Pack', 5, 'pcs', 249.00, 0.00, 1245.00),
(6, 16, 1, 'Hydrocollator Moist Heat Pack', 2, 'pcs', 129.00, 0.00, 258.00),
(7, 17, 31, 'testProd', 12, 'pcs', 1233.00, 0.00, 14796.00),
(8, 18, 1, 'Hydrocollator Moist Heat Pack', 1, 'pcs', 123.00, 0.00, 123.00),
(9, 19, 1, 'Hydrocollator Moist Heat Pack', 1, 'pcs', 345.00, 0.00, 345.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT 1,
  `image_path` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `product_name`, `description`, `category_id`, `unit_id`, `status_id`, `image_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'MED001', 'Hydrocollator Moist Heat Pack', 'Standard size moist heat pack for thermotherapy', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(2, 'MED002', 'Paraffin Wax Bath', 'Therapeutic paraffin wax bath for hand therapy', NULL, 1, 30, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(3, 'MED003', 'Therapeutic Ultrasound Machine', '1 MHz/3 MHz dual frequency ultrasound unit', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(4, 'MED004', 'TENS Unit', 'Transcutaneous electrical nerve stimulation device', NULL, 1, 31, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(5, 'MED005', 'Nebulizer Machine', 'Compressor nebulizer for respiratory treatments', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(6, 'MED006', 'Pulse Oximeter', 'Fingertip pulse oximeter with SpO2 monitoring', NULL, 1, 32, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(7, 'MED007', 'Blood Pressure Monitor', 'Digital automatic blood pressure cuff', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(8, 'MED008', 'Stethoscope', 'Dual-head diagnostic stethoscope', NULL, 1, 33, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(9, 'MED009', 'Thermometer', 'Digital oral/axillary thermometer', NULL, 1, 34, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(10, 'MED010', 'First Aid Kit', '100-piece comprehensive first aid kit', NULL, 3, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(11, 'MED011', 'Medical Gloves (Box)', 'Latex-free examination gloves, 100 count', NULL, 2, 30, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(12, 'MED012', 'Surgical Mask (Box)', '3-ply disposable surgical masks, 50 count', NULL, 2, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(13, 'MED013', 'Alcohol Swabs (Box)', 'Isopropyl alcohol prep pads, 200 count', NULL, 2, 31, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(14, 'MED014', 'Gauze Pads (Box)', 'Sterile 4x4 gauze pads, 100 count', NULL, 2, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(15, 'MED015', 'Adhesive Bandages (Box)', 'Assorted size bandages, 100 count', NULL, 2, 32, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(16, 'MED016', 'Medical Tape (Roll)', 'Hypoallergenic surgical tape, 1\" width', NULL, 6, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(17, 'MED017', 'Cotton Balls (Bag)', 'Sterile cotton balls, 500g', NULL, 7, 33, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(18, 'MED018', 'Tongue Depressors (Box)', 'Wooden tongue depressors, 100 count', NULL, 2, 34, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(19, 'MED019', 'Disposable Syringes (Box)', '3ml luer-lock syringes, 100 count', NULL, 2, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(20, 'MED020', 'Sharps Container', '1-liter safety sharps disposal container', NULL, 8, 30, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(21, 'MED021', 'Walker', 'Standard aluminum walker with rubber tips', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(22, 'MED022', 'Wheelchair', 'Transport wheelchair with 18\" seat', NULL, 1, 31, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(23, 'MED023', 'Crutches', 'Adjustable underarm crutches, pair', NULL, 5, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(24, 'MED024', 'Cane', 'Adjustable aluminum walking cane', 1, 5, 32, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:39:06'),
(25, 'MED025', 'Hospital Bed', 'Manual crank hospital bed', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(26, 'MED026', 'Patient Lift', 'Hydraulic patient lift with sling', NULL, 1, 33, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(27, 'MED027', 'Exam Table', 'Adjustable height examination table', NULL, 1, 34, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(28, 'MED028', 'IV Pole', 'Stainless steel IV stand', NULL, 1, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(29, 'MED029', 'Medical Scale', 'Digital floor scale with handrails', NULL, 1, 30, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(30, 'MED030', 'Otoscope Set', 'Diagnostic otoscope with specula', NULL, 4, 29, NULL, 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(31, '1234', 'testProd', 'testProd', 1, 5, 29, 'uploads/products/687cfe4fc9a48.png', 2, '2025-07-20 22:33:51', '2025-07-20 22:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventory`
--

CREATE TABLE `product_inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL,
  `reorder_level` int(11) DEFAULT 0,
  `last_restock_date` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_inventory`
--

INSERT INTO `product_inventory` (`inventory_id`, `product_id`, `quantity`, `unit_price`, `reorder_level`, `last_restock_date`, `updated_at`) VALUES
(31, 31, 12, 90.00, 0, NULL, '2025-07-20 22:44:30'),
(32, 1, 15, 89.99, 5, '2025-06-15 10:00:00', '2025-07-20 22:44:11'),
(33, 2, 3, 249.99, 2, '2025-05-20 14:30:00', '2025-07-20 22:44:11'),
(34, 3, 7, 1299.00, 3, '2025-07-01 09:15:00', '2025-07-20 22:44:11'),
(35, 4, 12, 79.95, 8, '2025-06-28 11:45:00', '2025-07-20 22:44:11'),
(36, 5, 9, 89.50, 4, '2025-07-10 13:20:00', '2025-07-20 22:44:11'),
(37, 6, 22, 29.99, 10, '2025-07-12 08:00:00', '2025-07-20 22:44:11'),
(38, 7, 18, 59.99, 6, '2025-06-25 15:30:00', '2025-07-20 22:44:11'),
(39, 8, 5, 45.00, 3, '2025-05-15 10:45:00', '2025-07-20 22:44:11'),
(40, 9, 30, 12.99, 15, '2025-07-05 09:00:00', '2025-07-20 22:44:11'),
(41, 10, 8, 39.99, 3, '2025-06-18 14:00:00', '2025-07-20 22:44:11'),
(42, 11, 0, 8.99, 20, '2025-04-10 11:30:00', '2025-07-20 22:44:11'),
(43, 12, 25, 12.50, 10, '2025-07-08 10:15:00', '2025-07-20 22:44:11'),
(44, 13, 4, 6.99, 5, '2025-03-22 13:45:00', '2025-07-20 22:44:11'),
(45, 14, 17, 14.75, 8, '2025-06-30 16:20:00', '2025-07-20 22:44:11'),
(46, 15, 2, 9.99, 5, '2025-05-05 09:30:00', '2025-07-20 22:44:11'),
(47, 16, 11, 5.25, 6, '2025-07-02 10:00:00', '2025-07-20 22:44:11'),
(48, 17, 0, 7.50, 4, '2025-02-28 14:15:00', '2025-07-20 22:44:11'),
(49, 18, 14, 3.99, 10, '2025-07-01 11:30:00', '2025-07-20 22:44:11'),
(50, 19, 6, 12.25, 8, '2025-06-20 15:00:00', '2025-07-20 22:44:11'),
(51, 20, 9, 24.99, 3, '2025-07-05 09:45:00', '2025-07-20 22:44:11'),
(52, 21, 7, 69.99, 3, '2025-06-22 10:30:00', '2025-07-20 22:44:11'),
(53, 22, 0, 199.00, 2, '2025-05-18 14:00:00', '2025-07-20 22:44:11'),
(54, 23, 5, 59.95, 2, '2025-07-03 11:15:00', '2025-07-20 22:44:11'),
(55, 24, 1, 29.99, 2, '2025-04-15 09:00:00', '2025-07-20 22:44:11'),
(56, 25, 3, 899.00, 1, '2025-06-28 13:45:00', '2025-07-20 22:44:11'),
(57, 26, 0, 1299.00, 1, '2025-05-30 10:30:00', '2025-07-20 22:44:11'),
(58, 27, 4, 599.00, 1, '2025-07-07 14:15:00', '2025-07-20 22:44:11'),
(59, 28, 12, 49.99, 3, '2025-07-10 09:30:00', '2025-07-20 22:44:11'),
(60, 29, 1, 199.00, 1, '2025-04-20 11:00:00', '2025-07-20 22:44:11'),
(61, 30, 6, 149.00, 2, '2025-07-01 10:45:00', '2025-07-20 22:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `product_status`
--

CREATE TABLE `product_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_status`
--

INSERT INTO `product_status` (`status_id`, `status_name`, `description`) VALUES
(25, 'Active', 'Product is currently available for use/sale'),
(26, 'Inactive', 'Product is not currently available'),
(27, 'Discontinued', 'Product has been permanently discontinued'),
(28, 'Out of Stock', 'Temporarily unavailable due to stock depletion'),
(29, 'On Order', 'Product is currently being ordered from supplier'),
(30, 'Quarantined', 'Product temporarily held for quality inspection'),
(31, 'Expired', 'Product has passed its expiration date'),
(32, 'Damaged', 'Product is not usable due to damage'),
(33, 'Recalled', 'Product has been recalled by manufacturer'),
(34, 'Seasonal', 'Product only available during certain seasons');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'Owner', 'System owner with full access'),
(2, 'Admin', 'Administrator with management privileges'),
(3, 'Employee', 'Regular employee with limited access');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `unit_symbol` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `unit_symbol`) VALUES
(1, 'Piece', 'pc'),
(2, 'Box', 'box'),
(3, 'Pack', 'pk'),
(4, 'Set', 'set'),
(5, 'Pair', 'pr'),
(6, 'Roll', 'roll'),
(7, 'Bag', 'bag'),
(8, 'Liter', 'L'),
(9, 'Kilogram', 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 1,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role_id`, `status_id`, `last_login`, `created_at`) VALUES
(1, 'admin123', 'admin123@email.com', '$2y$10$X1OM1esFu7V7Ycs1Kaekw.HPrlPG4iaU2uRcJ700Kf59ja7s54MO2', 2, 1, '2025-07-21 06:56:59', '2025-07-20 13:16:31'),
(2, 'test1', 'test1@email.com', '$2y$10$F46qHtshkxdcCdyh6ty9Du/Q8oR9nPSraWd6FjvwjLHq1p87k3wX.', 3, 1, '2025-07-20 21:26:29', '2025-07-20 13:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

CREATE TABLE `user_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_status`
--

INSERT INTO `user_status` (`status_id`, `status_name`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Suspended');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_status`
--
ALTER TABLE `product_status`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `unit_name` (`unit_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_role` (`role_id`),
  ADD KEY `fk_user_status` (`status_id`);

--
-- Indexes for table `user_status`
--
ALTER TABLE `user_status`
  ADD PRIMARY KEY (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_inventory`
--
ALTER TABLE `product_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `product_status`
--
ALTER TABLE `product_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_status`
--
ALTER TABLE `user_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `product_status` (`status_id`),
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `product_inventory`
--
ALTER TABLE `product_inventory`
  ADD CONSTRAINT `product_inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `fk_user_status` FOREIGN KEY (`status_id`) REFERENCES `user_status` (`status_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
