-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 17, 2026 at 11:55 PM
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
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `kTeach` int(4) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(55) NOT NULL,
  `teachFirst` varchar(255) NOT NULL,
  `teachLast` varchar(255) NOT NULL,
  `pwd` varchar(32) NOT NULL DEFAULT '3714faf5c6953aad726265f1e94e8bb5',
  `teachClass` varchar(25) DEFAULT NULL,
  `gradeStart` tinyint(1) DEFAULT 0,
  `gradeEnd` tinyint(1) DEFAULT 0,
  `isAdvisor` tinyint(1) DEFAULT NULL,
  `dbRole` int(1) NOT NULL DEFAULT 2 COMMENT '1=admin,2=user',
  `status` tinyint(1) NOT NULL,
  `resetHash` varchar(32) DEFAULT NULL COMMENT 'A hash for resetting a password',
  `recModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `recModifier` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Teacher Database';

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`kTeach`, `user_id`, `username`, `email`, `teachFirst`, `teachLast`, `pwd`, `teachClass`, `gradeStart`, `gradeEnd`, `isAdvisor`, `dbRole`, `status`, `resetHash`, `recModified`, `recModifier`) VALUES
(1000, 1, 'administrator', 'email@example.com', 'Database', 'Administrator', '696d29e0940a4957748fe3fc9efd22a3', '', 0, 0, NULL, 1, 1, '', '2026-04-17 22:57:28', 'administrator'),
(1, NULL, 'sjohnson', 'sjohnson@school.edu', 'Sarah', 'Johnson', '3714faf5c6953aad726265f1e94e8bb5', '10', NULL, NULL, 1, 2, 1, NULL, '2026-04-17 23:53:04', '1000'),
(2, NULL, 'mchen', 'mchen@school.edu', 'Michael', 'Chen', '3714faf5c6953aad726265f1e94e8bb5', '12', 1, 2, 1, 2, 1, NULL, '2026-04-17 23:52:36', '1000'),
(3, NULL, 'erodriguez', 'erodriguez@school.edu', 'Emily', 'Rodriguez', '3714faf5c6953aad726265f1e94e8bb5', '5', 1, 2, 1, 2, 1, NULL, '2026-04-17 23:54:06', '1000'),
(4, NULL, 'dthompson', 'dthompson@school.edu', 'David', 'Thompson', '3714faf5c6953aad726265f1e94e8bb5', '15', 3, 4, 1, 2, 1, NULL, '2026-04-17 23:53:36', '1000'),
(5, NULL, 'lmartinez', 'lmartinez@school.edu', 'Lisa', 'Martinez', '3714faf5c6953aad726265f1e94e8bb5', '2', 3, 4, 1, 2, 1, NULL, '2026-04-17 23:53:27', '1000'),
(6, NULL, 'jwilson', 'jwilson@school.edu', 'James', 'Wilson', '3714faf5c6953aad726265f1e94e8bb5', '8', 5, 8, 1, 2, 1, NULL, '2026-04-17 23:54:32', '1000'),
(7, NULL, 'panderson', 'panderson@school.edu', 'Patricia', 'Anderson', '3714faf5c6953aad726265f1e94e8bb5', '9', 5, 8, 1, 2, 1, NULL, '2026-04-17 23:49:28', '1000'),
(8, NULL, 'rgarcia', 'rgarcia@school.edu', 'Robert', 'Garcia', '3714faf5c6953aad726265f1e94e8bb5', '13', 5, 8, NULL, 2, 1, NULL, '2026-04-17 23:52:54', '1000'),
(9, NULL, 'jlee', 'jlee@school.edu', 'Jennifer', 'Lee', '3714faf5c6953aad726265f1e94e8bb5', '3', 5, 8, NULL, 2, 1, NULL, '2026-04-17 23:53:17', '1000'),
(10, NULL, 'tbrown', 'tbrown@school.edu', 'Thomas', 'Brown', '3714faf5c6953aad726265f1e94e8bb5', '1', NULL, 8, NULL, 2, 1, NULL, '2026-04-17 23:51:56', '1000'),
(11, NULL, 'kwhite', 'kwhite@school.edu', 'Karen', 'White', '3714faf5c6953aad726265f1e94e8bb5', '11', NULL, 8, NULL, 2, 1, NULL, '2026-04-17 23:54:23', '1000'),
(12, NULL, 'crivera', 'crivera@school.edu', 'Carlos', 'Rivera', '3714faf5c6953aad726265f1e94e8bb5', '14', NULL, 8, NULL, 2, 1, NULL, '2026-04-17 23:53:49', '1000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`kTeach`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `kTeach` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
