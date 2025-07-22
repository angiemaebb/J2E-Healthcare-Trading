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
(1, 'Equipment', 'Physical therapy and rehabilitation equipment', '2025-07-20 22:33:17', '2025-07-20 22:33:17', 2),
(2, 'Supplies', 'Consumable supplies for therapy treatments', '2025-07-20 22:33:17', '2025-07-20 22:33:17', 2),
(3, 'testCategory', 'testCategory', '2025-07-20 22:33:17', '2025-07-20 22:33:17', 2);
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
-- Supplies
(1, '0100', 'Paraffin Wax', 'Form: Pellet Form\nMelting point: 180-185°F\nAdding Fragrance Oil: 160°F (slowly stir for 2 mins)\nPour to vessel: 140-150°F', NULL, 9, 29, 'images/items/Paraffin Wax.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(2, '0101', 'Ultrasound Gel', 'DESCRIPTION: Odourless water-based viscous gel.\nEFFICIENCY: > 90% from 0.5 MHz\nDENSITY: 1.0 g/ml\nVISCOSITY: >800,000 mPa.s (Brookfield LVT)\nACOUSTIC IMPEDANCE: 1.62 (106 Rayls)', NULL, 8, 29, 'images/items/Ultrasound Gel.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),

-- Equipment
(3, '0200', 'Cold Pack', 'Small\nCervical\nSpinal', NULL, 1, 29, 'images/items/Cold Pack.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(4, '0201', 'Cylinder Peg Board', 'Size 6*7*7* inches\nOccupational Therapy Tool, insert the sticks into the proper positions, train patients coordination ability of eyes and hands', NULL, 4, 29, 'images/items/Cylinder Peg Board.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(5, '0202', 'Electric Lift', 'Size(cm): 125*81*171~220\nWeight: 66.0kg\nHeight range: 148 - 220cm\nSupply: internal DC 24V\nexternal AC 220 50HZ\nUsed for the patient with hemiplegia or post-surgery to move', NULL, 1, 29, 'images/items/Electric Lift.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(6, '0203', 'Electronic Hand Dynamometer', 'Hand Grip Strength Meter High quality', NULL, 1, 29, 'images/items/Electronic Hand Dynamometer.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(7, '0204', 'Exercise Tubing Thera Tube', '5 feet/\ncolor\nYellow\nRed\nGreen\nBlue\nBlack', NULL, 1, 29, 'images/items/Exercise Tubing Thera Tube.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(8, '0205', 'Floor Mats', '(FOAM OR RUBBERIZED)\nSize: 1MX1MX22 mm', NULL, 1, 29, 'images/items/Floor Mats.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(9, '0206', 'Gait Training Device', 'Model no. XY-K-G2\nLifting range column: 0-300 mm\n220V 50/60Hz Overall dimensions: 126*115*213cm\nHeight range: 0~30cm - load capacity:200 Kg\nArmrest height: 0～33cm\nUnweighting force: 0~900N\nDisplay load weight indicator Emergency UPS\nUses electric control to lift\nEquipped with handle switch', NULL, 1, 29, 'images/items/Gait Training Device.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(10, '0207', 'Hydrocollator Moist Tank', '304 stainless steel\n220-240V 50/60HZ\n14 hot moist pack capacity.\nConstant temperature: 0~99℃ adjustable\nTwo modes:\nAutomatic (preset initially and it will stop automatically)\nManual modes\nTank: 70L 14 hot packs: 6 standard, 2 oversized, 6 neck.', NULL, 1, 29, 'images/items/Hydrocollator Moist Tank.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(11, '0208', 'Hydrocollator Packheater', 'With 6 pcs. hot moist pack included\nPackheater 6-8 hot moist pack capacity\nThe Hydrocollator packheater is used for heating moist packs.\nThe enamelled heater is delivered including the base grill.\nThe water temperature is accurately maintained by a thermostat, pre-selected by you.\nTemperature range (50 - 95°C ) 6-8 hotpacks capacity Table Top unit Enamelled Interior\nThermostatic Control Contains 29 litres Temperature adjustable from 50 - 95°C', NULL, 4, 29, 'images/items/Hydrocollator Packheater.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(12, '0209', 'Infrared Lamp', '275W Floor Stand Infrared IR Temperature Light Therapy Heat Lamp Pain Relief\nMaterial: ABS\nColor: White\nVoltage: 110v240v\nFrequency: 50-60Hz\nPower: 275W', NULL, 1, 29, 'images/items/Infrared Lamp.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(13, '0210', 'Multi Resistance Hand Web', 'latex sheets inside hand training ring and use holes to perform stretching, squeezing, and pinching exercise\nSheets are designed to be less challenging\nkit consists of hand training ring, three color coded latex sheets and colored instruction manual', NULL, 4, 29, 'images/items/Multi Resistance Hand Web.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(14, '0211', 'Neurodevelopmental Training Ball', 'SMALL55 cm\nMEDIUM 65 cm\nLARGE 75 cm', NULL, 1, 29, 'images/items/Neurodevelopmental Training Ball.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(15, '0212', 'Operating Stool', 'Size: 39-45*45~58cm Cylindrical shape\nLifting range: 45-58cm\nWeight: 5-7.0kg\nIt is a kind of moving stool used for therapist to do manipulation', NULL, 1, 29, 'images/items/Operating Stool.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(16, '0213', 'Paraffin Wax Bath', '6 lbs Paraffin wax included\nCover made of plastic.\nAluminum metal inside.\nControl knob for adjustment. Led light indicator.\n6-10 pounds capacity\n220 V/ 50-60 Hz.\nincludes: 6 lbs. paraffin wax', NULL, 1, 29, 'images/items/Paraffin Wax Bath.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(17, '0214', 'Parallel Bars', '6 ft stainless steel handrails with bumper ends.\nHeight and width of bars are adjustable.\nSteel bars for support\nFoldable or Floor Mounted\nHeight Range: 26" - 39", Width Range\nadjusts out 6" on each side\nCapacity is 400 pounds -Height controlled by plungers for easy adjustment', NULL, 1, 29, 'images/items/Parallel Bars.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(18, '0215', 'Pediatric Gait Treadmill', 'Power 2.5HP/DC\nDisplay window\ntime, distance, speed, heart rate, calory, inclination\nInput voltage 220V, 50/60HZ\nFunction: Used for walking training\nApplication: Rehabilitation center, hospital, clinic\nLoading capacity 100Kg', NULL, 1, 29, 'images/items/Pediatric Gait Treadmill.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(19, '0216', 'Pediatric Standing Frame', 'Used for standing dysfunction children under 10 years old to stand training.\nSize: 98*70*90-122cm\nAdjustment range of knee(cm): back and forth 0-15; right and left 15-38.\nAdjustment range of table(cm): back and forth 16-36; right and left 67-87\nRotation angle of cushion: 90°\nLoading: 60Kg', NULL, 1, 29, 'images/items/Pediatric Standing Frame.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(20, '0217', 'Pediatric Walker', 'Aluminum KY966L', NULL, 1, 29, 'images/items/Pediatric Walker.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(21, '0218', 'Pedometer', 'Multi-functional Step Counter Portable Pedometer Fitness Tracker for Tracking Steps / Walking Distance / Calories Fitness Monitor for Man Woman\nPocket / Clip\nCalorie Counter\nWhite', NULL, 1, 29, 'images/items/Pedometer.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(22, '0219', 'Plinth', 'Solid hardwood legs\nH-brace construction\nLegs have bolts for fast easy assembly\nSeamless, rounded corner top\nChoice of upholstery colors, BLUE OR CREAM\nSpecification:\n2" of firm foam padding (5 cm)\n400 lbs. Load - capacity under normal use (181 kg) SIZE: 72X30X31', NULL, 1, 29, 'images/items/Plinth.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(23, '0220', 'Postural Training Mirror', 'Dimensions 145cm high x 76cm wide x 67cm deep', NULL, 1, 29, 'images/items/Postural Training Mirror.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(24, '0221', 'Pulley System', 'Dimension: 63*20*180\nDistance range: 0~1150mm\nWeight of bob-weight and number: 2Kg 5pcs\nLoading capacity of rope: 720N\nLoading capacity of handle: 480N', NULL, 1, 29, 'images/items/Pulley System.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(25, '0222', 'Pulley', 'Size: 43*3*116cm\nWeight: 1Kg\nRated loading: 10kg\nUsed for shoulder joints range of motion exercise, joints traction, power exercise', NULL, 1, 29, 'images/items/Pulley.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(26, '0223', 'Rehab Bolster', 'Length 36"\nElevation 10"', NULL, 1, 29, 'images/items/Rehab Bolster.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(27, '0224', 'Rehab Mat', 'Gray Color\nCompact foam\nFully Leatherette Cover\nFolding\nSize: L183cm x W61cm x H2.5cm', NULL, 1, 29, 'images/items/Rehab Mat.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(28, '0225', 'Rehab Wedge', 'Size: Length 20", x 28" X\nElevation 10"\nBonded Foam\nFully Leatherette\nCover - Color Gray', NULL, 1, 29, 'images/items/Rehab Wedge.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(29, '0226', 'Rocker Board', 'Non-slip surface on bottom prevents board from sliding during use.\nBalance coordination training for patients with hemiplegia, cerebral palsy etc.\nSize: 70-90*50-70*5-9cm\nLoading: 150Kg\nWeight: 4-9Kg', NULL, 1, 29, 'images/items/Rocker Board.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(30, '0227', 'Thera Band', '1 yard Theraband Yellow\n1 yard Theraband Red\n1 yard Theraband Green\n1 yard Theraband Blue\n1 yard Theraband Black\n1 yard Theraband Silver\n1 yard Theraband Gold\n1 pair Theraband Handle', NULL, 4, 29, 'images/items/Thera Band.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(31, '0228', 'Therapeutic Ultrasound Machine', 'MAIN FEATURES\nLarge 7″ colour touch screen\nQUICK protocols\nBody Parts navigation\nPreset protocols and therapeutic encyclopaedia\nPatient database\nPortable and battery-operated*\nTrolley*\nSaving operator''s time and effort\nwith HandsFree Sono®\nErgonomic heads with visual accessory identification / visual patient contact indicator\nHeated multi-frequency applicators: 1 and 3 MHz available\nSimultaneous treatment with 1 and 3 MHz', NULL, 1, 29, 'images/items/Therapeutic Ultrasound Machine.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(32, '0229', 'Training Ladder', 'Size: 337*83*134-155cm\nWeight: 120kg\nArmrest: 0-20cm\nLoading: 150Kg', NULL, 1, 29, 'images/items/Training Ladder.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(33, '0230', 'Vinyl Dumbell Set', '1 lb to 10 Lbs\nGood for arm and upper body exercises.\nEasy to clean vinyl coating.\nUsed for muscle toning and aerobics exercise.', NULL, 4, 29, 'images/items/Vinyl Dumbbell Set.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(34, '0231', 'Wedge Board', 'Size: 12x24x24 in', NULL, 1, 29, 'images/items/Wedge Board.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(35, '0232', 'Wobble Board', 'Balance coordination training for patients Non-slip surface\nSize: Cylindrical shape\n14-16 inches width\nLoading: 150Kg\nWeight: 4-9Kg', NULL, 1, 29, 'images/items/Wobble Board.png', 1, '2025-07-20 22:27:37', '2025-07-20 22:27:37'),
(36, '1234', 'testProd', 'testProd', 1, 5, 29, 'uploads/products/687cfe4fc9a48.png', 2, '2025-07-20 22:33:51', '2025-07-20 22:44:30');


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
-- Supplies
(1, 1, 50, 89.99, 10, '2025-07-15 10:00:00', '2025-07-21 10:00:00'),  -- Paraffin Wax
(2, 2, 25, 24.99, 5, '2025-07-18 14:00:00', '2025-07-21 10:00:00'),   -- Ultrasound Gel

-- Equipment
(3, 3, 15, 45.00, 5, '2025-07-12 11:45:00', '2025-07-21 10:00:00'),   -- Cold Pack
(4, 4, 10, 79.95, 3, '2025-07-05 13:20:00', '2025-07-21 10:00:00'),    -- Cylinder Peg Board
(5, 5, 3, 599.00, 1, '2025-06-28 15:30:00', '2025-07-21 10:00:00'),    -- Electric Lift
(6, 6, 20, 29.99, 5, '2025-07-15 08:00:00', '2025-07-21 10:00:00'),    -- Electronic Hand Dynamometer
(7, 7, 12, 59.99, 4, '2025-07-10 10:45:00', '2025-07-21 10:00:00'),   -- Exercise Tubing Thera Tube
(8, 8, 8, 45.00, 3, '2025-07-01 09:15:00', '2025-07-21 10:00:00'),     -- Floor Mats
(9, 9, 2, 1299.00, 1, '2025-06-25 16:20:00', '2025-07-21 10:00:00'),   -- Gait Training Device
(10, 10, 5, 199.00, 2, '2025-07-08 14:30:00', '2025-07-21 10:00:00'),  -- Hydrocollator Moist Tank
(11, 11, 4, 89.50, 2, '2025-07-03 11:00:00', '2025-07-21 10:00:00'),   -- Hydrocollator Packheater
(12, 12, 15, 12.50, 5, '2025-07-12 10:15:00', '2025-07-21 10:00:00'),  -- Infrared Lamp
(13, 13, 8, 6.99, 3, '2025-07-05 13:45:00', '2025-07-21 10:00:00'),    -- Multi Resistance Hand Web
(14, 14, 10, 14.75, 4, '2025-07-10 16:20:00', '2025-07-21 10:00:00'),  -- Neurodevelopmental Training Ball
(15, 15, 6, 9.99, 2, '2025-07-01 09:30:00', '2025-07-21 10:00:00'),    -- Operating Stool
(16, 16, 8, 129.00, 3, '2025-07-15 10:00:00', '2025-07-21 10:00:00'),  -- Paraffin Wax Bath
(17, 17, 4, 249.99, 2, '2025-06-28 14:15:00', '2025-07-21 10:00:00'),  -- Parallel Bars
(18, 18, 6, 39.99, 2, '2025-07-05 11:30:00', '2025-07-21 10:00:00'),   -- Pediatric Gait Treadmill
(19, 19, 3, 12.25, 2, '2025-07-08 15:00:00', '2025-07-21 10:00:00'),   -- Pediatric Standing Frame
(20, 20, 5, 24.99, 2, '2025-07-10 09:45:00', '2025-07-21 10:00:00'),   -- Pediatric Walker
(21, 21, 10, 69.99, 3, '2025-07-12 10:30:00', '2025-07-21 10:00:00'),  -- Pedometer
(22, 22, 2, 199.00, 1, '2025-06-30 14:00:00', '2025-07-21 10:00:00'),  -- Plinth
(23, 23, 3, 59.95, 2, '2025-07-03 11:15:00', '2025-07-21 10:00:00'),   -- Postural Training Mirror
(24, 24, 5, 29.99, 2, '2025-07-01 09:00:00', '2025-07-21 10:00:00'),    -- Pulley System
(25, 25, 2, 899.00, 1, '2025-06-28 13:45:00', '2025-07-21 10:00:00'),   -- Pulley
(26, 26, 1, 1299.00, 1, '2025-06-30 10:30:00', '2025-07-21 10:00:00'),  -- Rehab Bolster
(27, 27, 3, 599.00, 1, '2025-07-07 14:15:00', '2025-07-21 10:00:00'),   -- Rehab Mat
(28, 28, 10, 49.99, 3, '2025-07-10 09:30:00', '2025-07-21 10:00:00'),   -- Rehab Wedge
(29, 29, 2, 199.00, 1, '2025-06-20 11:00:00', '2025-07-21 10:00:00'),   -- Rocker Board
(30, 30, 5, 149.00, 2, '2025-07-01 10:45:00', '2025-07-21 10:00:00'),   -- Thera Band
(31, 31, 3, 1299.00, 1, '2025-07-15 10:00:00', '2025-07-21 10:00:00'), -- Therapeutic Ultrasound Machine
(32, 32, 4, 199.00, 2, '2025-07-05 11:00:00', '2025-07-21 10:00:00'),   -- Training Ladder
(33, 33, 8, 24.99, 3, '2025-07-10 09:00:00', '2025-07-21 10:00:00'),   -- Vinyl Dumbbell Set
(34, 34, 6, 59.95, 2, '2025-07-12 10:00:00', '2025-07-21 10:00:00'),    -- Wedge Board
(35, 35, 5, 149.00, 2, '2025-07-08 14:00:00', '2025-07-21 10:00:00'),   -- Wobble Board
(36, 36, 10, 123.00, 3, '2025-07-20 22:33:51', '2025-07-20 22:44:30'); -- testProd
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
  `image_path` varchar(255) DEFAULT NULL,
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

INSERT INTO `users` (`user_id`, `image_path`, `username`, `email`, `password_hash`, `role_id`, `status_id`, `last_login`, `created_at`) VALUES
(1, 'images/users/admin123.png','admin123', 'admin123@email.com', '$2y$10$X1OM1esFu7V7Ycs1Kaekw.HPrlPG4iaU2uRcJ700Kf59ja7s54MO2', 2, 1, '2025-07-21 06:56:59', '2025-07-20 13:16:31'),
(2, 'images/users/test1.png', 'test1', 'test1@email.com', '$2y$10$F46qHtshkxdcCdyh6ty9Du/Q8oR9nPSraWd6FjvwjLHq1p87k3wX.', 3, 1, '2025-07-20 21:26:29', '2025-07-20 13:26:17');

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
