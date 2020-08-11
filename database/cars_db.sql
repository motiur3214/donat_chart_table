-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2020 at 08:25 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cars_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_car_details`
--

CREATE TABLE `tbl_car_details` (
  `id` int(11) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(6) NOT NULL,
  `producing_country` varchar(100) NOT NULL,
  `insertion_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_car_details`
--

INSERT INTO `tbl_car_details` (`id`, `manufacturer`, `model`, `year`, `producing_country`, `insertion_time`) VALUES
(2477, 'BMW', 'Isetta', 1955, 'Germany', '2020-08-11 17:51:54'),
(2478, 'Kia', 'Picanto', 2004, 'South Korea', '2020-08-11 17:51:55'),
(2479, 'Toyota', 'Primio', 2005, 'Japan', '2020-08-11 17:51:56'),
(2480, 'Nissan', 'X-trail', 2010, 'Japan', '2020-08-11 17:51:57'),
(2481, 'Hyunda', 'Sonata', 2006, 'South Korea', '2020-08-11 17:51:58'),
(2482, 'Toyota', 'Alien', 2004, 'Japan', '2020-08-11 17:51:59'),
(2483, 'Nissan', 'GTR', 2007, 'Japan', '2020-08-11 17:52:00'),
(2484, 'Honda', 'Cevic', 1980, 'Japan', '2020-08-11 17:52:01'),
(2485, 'BMW', 'Unspecified', 2019, 'Germany', '2020-08-11 17:52:02'),
(2486, 'BMW', 'X6 (G06)', 2019, 'Germany', '2020-08-11 17:52:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_car_details`
--
ALTER TABLE `tbl_car_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_car_details`
--
ALTER TABLE `tbl_car_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2487;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
