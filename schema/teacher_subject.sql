-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 17, 2026 at 11:56 PM
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
-- Table structure for table `teacher_subject`
--

CREATE TABLE `teacher_subject` (
  `kTeach` int(4) DEFAULT NULL,
  `kSubject` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `gradeStart` int(11) NOT NULL,
  `gradeEnd` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='N:N table for teachers and subjects';

--
-- Dumping data for table `teacher_subject`
--

INSERT INTO `teacher_subject` (`kTeach`, `kSubject`, `subject`, `gradeStart`, `gradeEnd`) VALUES
(1, 1, 'Reading', 0, 0),
(1, 2, 'Writing', 0, 0),
(1, 3, 'Math', 0, 0),
(1, 4, 'Science', 0, 0),
(1, 5, 'Social Studies', 0, 0),
(2, 6, 'Reading', 1, 2),
(2, 7, 'Writing', 1, 2),
(2, 8, 'Math', 1, 2),
(2, 9, 'Science', 1, 2),
(2, 10, 'Social Studies', 1, 2),
(3, 11, 'Reading', 1, 2),
(3, 12, 'Writing', 1, 2),
(3, 13, 'Math', 1, 2),
(3, 14, 'Science', 1, 2),
(3, 15, 'Social Studies', 1, 2),
(4, 16, 'Reading', 3, 4),
(4, 17, 'Writing', 3, 4),
(4, 18, 'Math', 3, 4),
(4, 19, 'Science', 3, 4),
(4, 20, 'Social Studies', 3, 4),
(5, 21, 'Reading', 3, 4),
(5, 22, 'Writing', 3, 4),
(5, 23, 'Math', 3, 4),
(5, 24, 'Science', 3, 4),
(5, 25, 'Social Studies', 3, 4),
(6, 26, 'History', 5, 8),
(6, 27, 'Language Arts', 5, 8),
(6, 28, 'Literature', 5, 8),
(7, 29, 'History', 5, 8),
(7, 30, 'Language Arts', 5, 8),
(7, 37, 'Literature', 5, 8),
(8, 32, 'Science', 5, 8),
(9, 33, 'Math', 5, 8),
(10, 34, 'Art', 0, 8),
(11, 35, 'Music', 0, 8),
(12, 36, 'Spanish', 0, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  ADD PRIMARY KEY (`kSubject`),
  ADD KEY `kTeach` (`kTeach`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  MODIFY `kSubject` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
