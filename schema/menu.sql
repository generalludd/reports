-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 17, 2026 at 11:51 PM
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
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `kMenu` int(11) NOT NULL,
  `category` varchar(35) NOT NULL DEFAULT '',
  `label` varchar(35) NOT NULL,
  `value` varchar(65) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='list of menu items by class for use in the UI';

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`kMenu`, `category`, `label`, `value`) VALUES
(1, 'dbRole', 'Administrator', '1'),
(2, 'dbRole', 'Editor', '2'),
(3, 'dbRole', 'Classroom aide', '3'),
(4, 'grade', '1', '1'),
(5, 'grade', '2', '2'),
(6, 'grade', '3', '3'),
(7, 'grade', '4', '4'),
(8, 'grade', '5', '5'),
(9, 'grade', '6', '6'),
(10, 'grade', '7', '7'),
(11, 'grade', '8', '8'),
(12, 'grade', 'K', '0'),
(13, 'userStatus', 'Active', '1'),
(14, 'userStatus', 'Inactive', '0'),
(15, 'attend-length-type', 'Early Dismissal', 'early-dismissal'),
(16, 'attend-length-type', 'Late Arrival', 'late-arrival'),
(17, 'attend-length-type', 'Early Dismissal', 'early-dismissal'),
(18, 'attend-length-type', 'Late Arrival', 'late-arrival'),
(19, 'attend-subtype', 'Illness', '1'),
(20, 'attend-subtype', 'Religious Holiday', '2'),
(21, 'attend-subtype', 'Shadowing', '3'),
(22, 'attend-subtype', 'Suspension', '4'),
(23, 'attend-subtype', 'Travel', '6'),
(24, 'attend-subtype', 'Unexcused', '5'),
(25, 'attendance', 'Absent', '1'),
(26, 'attendance', 'Appointment', '2'),
(27, 'attendance', 'Present', '3'),
(28, 'attendance', 'Tardy', '4'),
(29, 'classroom', 'Art', '1'),
(30, 'classroom', 'Bayou', '2'),
(31, 'classroom', 'City', '3'),
(32, 'classroom', 'Fen', '4'),
(33, 'classroom', 'Forest', '5'),
(34, 'classroom', 'Gym', '6'),
(35, 'classroom', 'Learning Support', '7'),
(36, 'classroom', 'Marsh', '8'),
(37, 'classroom', 'Mesa', '9'),
(38, 'classroom', 'Mississippi', '10'),
(39, 'classroom', 'Music', '11'),
(40, 'classroom', 'Prairie', '12'),
(41, 'classroom', 'Science', '13'),
(42, 'classroom', 'Spanish', '14'),
(43, 'classroom', 'Tundra', '15'),
(44, 'course_preference', 'Exempt', '1'),
(45, 'course_preference', 'Pass/Fail', '2'),
(46, 'gender', 'He/Him/His', 'M'),
(47, 'gender', 'She/Her/Hers', 'F'),
(48, 'gender', 'They/Them/Theirs', 'O'),
(49, 'grade_footnote', '-', '0'),
(50, 'grade_footnote', 'Completed', '5'),
(51, 'grade_footnote', 'Incomplete', '4'),
(52, 'grade_footnote', 'Late', '1'),
(53, 'grade_footnote', 'Not Turned In', '2'),
(54, 'grade_footnote', 'Redo', '3'),
(55, 'grade_footnote', 'Redo Possible', '7'),
(56, 'grade_footnote', 'Redone/Corrected', '6'),
(57, 'grade_status', '-', '0'),
(58, 'grade_status', 'Absent', '1'),
(59, 'grade_status', 'Drop', '3'),
(60, 'grade_status', 'Excused', '3'),
(61, 'grade_status', 'Incomplete', '4'),
(62, 'grade_status', 'Redo', '5'),
(63, 'points_type', 'Extra Credit', 'extra_credit'),
(64, 'points_type', 'Make-Up', 'make_up'),
(65, 'report_category', 'Inappropriate Behavior', '1'),
(66, 'report_category', 'Late to Class', '2'),
(67, 'report_category', 'Missing Homework', '3'),
(68, 'report_category', 'Missing Planner', '4'),
(69, 'report_category', 'Unprepared for Class', '5'),
(70, 'report_contact_method', 'By Email', '1'),
(71, 'report_contact_method', 'By Phone', '2'),
(72, 'report_contact_method', 'In Person', '3'),
(73, 'report_rank', '0', '0'),
(74, 'report_rank', 'Important', '1'),
(75, 'report_rank', 'Urgent', '2'),
(76, 'report_status', 'important', 'important'),
(77, 'report_status', 'read', 'read'),
(78, 'report_status', 'unread', 'unread'),
(79, 'student_sort', 'First Name, Last Name', 'first_last'),
(80, 'student_sort', 'Last Name, First Name', 'last_first'),
(81, 'userStatus', 'Inactive', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`kMenu`),
  ADD KEY `class` (`category`),
  ADD KEY `label` (`label`),
  ADD KEY `value` (`value`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `kMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
