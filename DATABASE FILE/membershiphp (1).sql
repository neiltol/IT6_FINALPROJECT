-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 10:28 AM
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
-- Database: `membershiphp`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckMemberStatus` (IN `member_id` INT)   BEGIN
    SELECT 
        m.id,
        m.fullname,
        m.membership_number,
        mt.type AS membership_type,
        m.expiry_date,
        CASE 
            WHEN m.expiry_date >= CURDATE() THEN 'Active'
            ELSE 'Expired'
        END AS status,
        DATEDIFF(m.expiry_date, CURDATE()) AS days_remaining
    FROM members m
    JOIN membership_types mt ON m.membership_type = mt.id
    WHERE m.id = member_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRenewalReport` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
    SELECT 
        r.id AS renewal_id,
        m.fullname,
        m.membership_number,
        mt.type AS membership_type,
        r.total_amount,
        r.renew_date,
        DATE_ADD(r.renew_date, INTERVAL 1 YEAR) AS new_expiry
    FROM renew r
    JOIN members m ON r.member_id = m.id
    JOIN membership_types mt ON m.membership_type = mt.id
    WHERE r.renew_date BETWEEN start_date AND end_date
    ORDER BY r.renew_date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRenewals` (IN `start_date` DATE, IN `end_date` DATE)   BEGIN
  SELECT r.id, m.fullname, r.total_amount, r.renew_date
  FROM renew r
  JOIN members m ON r.member_id = m.id
  WHERE r.renew_date BETWEEN start_date AND end_date;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CheckMembershipStatus` (`expiry` DATE) RETURNS VARCHAR(10) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
  IF expiry >= CURDATE() THEN
    RETURN 'Active';
  ELSE
    RETURN 'Expired';
  END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `membership_type` int(11) NOT NULL,
  `membership_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `fullname`, `dob`, `gender`, `contact_number`, `email`, `password`, `address`, `country`, `postcode`, `occupation`, `membership_type`, `membership_number`, `created_at`, `photo`, `expiry_date`, `role`, `is_active`, `last_login`) VALUES
(23, 'Ken Lee', '2009-03-18', 'Male', '12345688787', 'saging@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Vilusa, Davao City', 'Philippines', '8000', 'Garbage Collector', 1, 'CA-090017', '2025-09-19 04:07:52', 'garbage_1758862479.jpg', '2025-10-19', 'member', 1, '2025-10-06 13:51:23'),
(37, 'jay nacua', '2003-02-20', 'Male', '12345688787', 'jay@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Nabunturan', 'Philippines', '8000', 'Boxer', 1, 'CA-856329', '2025-10-06 14:55:36', 'shakur_1759763441.jpg', '2025-11-06', 'member', 1, '2025-10-14 02:37:05'),
(38, 'Marky Ting', '2002-12-14', 'Male', '09057015273', 'marky@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Ecoland, Davao City', 'Philippines', '8000', 'Business Owner', 1, 'CA-344456', '2025-10-06 15:12:57', '1759763577_68e3dc79cf9d1.webp', '2026-01-06', 'member', 1, '2025-10-06 15:14:34'),
(39, 'Kael Uy', '2025-10-06', 'Male', '09061430532', 'kael@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Nabunturan', 'Philippines', '8000', 'Garbage Collector', 1, 'CA-159479', '2025-10-06 15:20:37', '1759764037_68e3de457c617.jpg', '2025-11-06', 'member', 1, '2025-10-06 15:31:20'),
(41, 'ian alfred', '2002-11-20', 'Male', '09066015234', 'ian@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Bankerohan', 'Philippines', '8000', 'Artist', 1, 'CA-964337', '2025-10-07 04:01:54', '1759809714_68e490b2cc227.webp', '2026-10-07', 'member', 1, '2025-10-07 04:11:40'),
(42, 'michaael', '2012-02-07', 'Male', '123456789', 'michael@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Anahaw, Davao City', 'Philippines', '8000', 'kawatan', 1, 'CA-206786', '2025-10-08 03:38:12', '1759894692_68e5dca4a9a11.jpg', '2026-01-08', 'member', 1, '2025-10-08 03:39:31'),
(44, 'Princess Yesha', '2008-01-21', 'Female', '09028015947', 'PY@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Vilusa, Davao City', 'Philippines', '8000', 'Student', 1, 'CA-102058', '2025-10-09 04:14:45', '1759983285_68e736b5af0f7.jpg', '2025-11-09', 'member', 1, '2025-10-09 04:35:39'),
(46, 'Yiv Pua', '2004-01-21', 'Female', '09061430532', 'yivpua@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Anahaw, Davao City', 'Philippines', '8000', 'Analyst', 1, 'CA-917224', '2025-10-09 04:38:04', 'yiv_1759984872.jpg', '2025-11-09', 'member', 1, '2025-10-14 04:43:26'),
(49, 'doe', '2012-03-13', 'Male', '09066015943', 'doe@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', 'Ecoland, Davao City', 'Philippines', '8000', 'programmer', 10, 'CA-128498', '2025-10-14 04:47:41', '1760417261_68edd5ed65fa5.jpg', '2026-04-14', 'member', 1, '2025-10-14 04:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `membership_types`
--

CREATE TABLE `membership_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `membership_types`
--

INSERT INTO `membership_types` (`id`, `type`, `amount`) VALUES
(1, 'Basic', 8),
(2, 'Standard', 13),
(3, 'Gold', 19),
(4, 'Silver', 15),
(6, 'Bronze', 12),
(7, 'BB Upd', 6),
(10, 'Premium', 28);

-- --------------------------------------------------------

--
-- Table structure for table `renew`
--

CREATE TABLE `renew` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `renew_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `renew`
--

INSERT INTO `renew` (`id`, `member_id`, `total_amount`, `renew_date`) VALUES
(37, 23, 8.00, '2025-09-19'),
(41, 37, 8.00, '2025-10-06'),
(42, 38, 24.00, '2025-10-06'),
(43, 39, 8.00, '2025-10-06'),
(44, 41, 96.00, '2025-10-07'),
(45, 42, 24.00, '2025-10-08'),
(48, 44, 8.00, '2025-10-09'),
(49, 46, 8.00, '2025-10-09'),
(52, 49, 168.00, '2025-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `currency` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_name`, `logo`, `currency`) VALUES
(1, 'Core Motion Gym', 'uploads/gym-center-logo-logo-design-gym-center_1152818-25.jpg', '$');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `registration_date`, `updated_date`) VALUES
(1, 'admin@mail.com', '5e9d11a14ad1c8dd77e98ef9b53fd1ba', '2024-02-02 01:34:26', '2025-09-13 07:00:58'),
(2, 'kael@gmail.com', '83b9ca665003d370a364a2b64703d963', '2025-10-06 12:26:59', '2025-10-06 12:26:59'),
(3, 'yunlang@gmail.com', 'cca8dd8babd4c9996c8dfee788a49d18', '2025-10-06 12:29:09', '2025-10-06 14:48:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membership_type` (`membership_type`);

--
-- Indexes for table `membership_types`
--
ALTER TABLE `membership_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `renew`
--
ALTER TABLE `renew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `membership_types`
--
ALTER TABLE `membership_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `renew`
--
ALTER TABLE `renew`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`membership_type`) REFERENCES `membership_types` (`id`);

--
-- Constraints for table `renew`
--
ALTER TABLE `renew`
  ADD CONSTRAINT `renew_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
