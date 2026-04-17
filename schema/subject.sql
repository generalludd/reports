-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 17, 2026 at 11:50 PM
-- Server version: 11.8.6-MariaDB-ubu2404-log
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject` varchar(55) NOT NULL,
  `gradeStart` int(1) NOT NULL,
  `gradeEnd` int(1) NOT NULL,
  `kSubject` int(2) NOT NULL COMMENT 'key and sort order for subjects'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='a list of subjects for each grade (range)';

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject`, `gradeStart`, `gradeEnd`, `kSubject`) VALUES
('Reading', 0, 0, 1),
('Writing', 0, 0, 2),
('Math', 0, 0, 3),
('Science', 0, 0, 4),
('Social Studies', 0, 0, 5),
('Reading', 1, 2, 6),
('Writing', 1, 2, 7),
('Math', 1, 2, 8),
('Science', 1, 2, 9),
('Social Studies', 1, 2, 10),
('Reading', 1, 2, 11),
('Writing', 1, 2, 12),
('Math', 1, 2, 13),
('Science', 1, 2, 14),
('Social Studies', 1, 2, 15),
('Reading', 3, 4, 16),
('Writing', 3, 4, 17),
('Math', 3, 4, 18),
('Science', 3, 4, 19),
('Social Studies', 3, 4, 20),
('Reading', 3, 4, 21),
('Writing', 3, 4, 22),
('Math', 3, 4, 23),
('Science', 3, 4, 24),
('Social Studies', 3, 4, 25),
('History', 5, 8, 26),
('Language Arts', 5, 8, 27),
('Literature', 5, 8, 28),
('History', 5, 8, 29),
('Language Arts', 5, 8, 30),
('Science', 5, 8, 31),
('Math', 5, 8, 32),
('Art', 0, 8, 33),
('Music', 0, 8, 34),
('Spanish', 0, 8, 35);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`kSubject`),
  ADD KEY `subject` (`subject`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `kSubject` int(2) NOT NULL AUTO_INCREMENT COMMENT 'key and sort order for subjects', AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
