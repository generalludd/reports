-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Apr 18, 2026 at 12:06 AM
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
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `kAssignment` int(5) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `assignment` varchar(35) NOT NULL,
  `kCategory` int(11) NOT NULL,
  `points` int(3) DEFAULT NULL COMMENT 'Null-value assignments are counted as make-up points for student grades',
  `points_type` varchar(25) DEFAULT NULL,
  `date` date NOT NULL,
  `subject` varchar(25) DEFAULT NULL,
  `term` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `gradeStart` int(1) NOT NULL,
  `gradeEnd` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_category`
--

CREATE TABLE `assignment_category` (
  `kCategory` int(11) NOT NULL,
  `kTeach` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `gradeStart` int(11) NOT NULL,
  `gradeEnd` int(11) NOT NULL,
  `term` enum('Mid-Year','Year-End') NOT NULL DEFAULT 'Mid-Year',
  `year` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='provide grade weights to categories';

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

CREATE TABLE `backup` (
  `kBackup` int(6) NOT NULL,
  `kNarrative` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL DEFAULT 0,
  `kTeach` int(5) NOT NULL DEFAULT 0,
  `stuGrade` int(11) NOT NULL,
  `narrText` text DEFAULT NULL COMMENT 'actual text of the report',
  `narrSubject` varchar(50) NOT NULL DEFAULT '' COMMENT 'social, academic, emotional, etc',
  `narrTerm` varchar(50) NOT NULL DEFAULT '' COMMENT 'term when the report is submitted',
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recModifier` text NOT NULL,
  `narrGrade` varchar(20) DEFAULT NULL,
  `narrYear` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `benchmark`
--

CREATE TABLE `benchmark` (
  `kBenchmark` int(4) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) DEFAULT NULL,
  `subject` varchar(25) NOT NULL,
  `category` varchar(55) NOT NULL,
  `weight` int(2) DEFAULT NULL COMMENT 'used for sorting categories',
  `benchmark` text NOT NULL,
  `recModifier` varchar(4) NOT NULL COMMENT 'who last modified the record',
  `recModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `benchmark_legend`
--

CREATE TABLE `benchmark_legend` (
  `kLegend` int(5) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) NOT NULL,
  `title` varchar(60) NOT NULL,
  `legend` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Legend for a given chart ';

-- --------------------------------------------------------

--
-- Table structure for table `chart_legend`
--

CREATE TABLE `chart_legend` (
  `kLegend` int(5) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) NOT NULL,
  `legend` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Legend for a given chart ';

-- --------------------------------------------------------

--
-- Table structure for table `concept`
--

CREATE TABLE `concept` (
  `kConcept` int(4) NOT NULL,
  `kTeach` int(5) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) DEFAULT NULL,
  `category` varchar(55) NOT NULL,
  `weight` int(2) NOT NULL COMMENT 'used for sorting categories',
  `concept` text NOT NULL,
  `phrase1` text DEFAULT NULL,
  `phrase2` text DEFAULT NULL,
  `phrase3` text DEFAULT NULL,
  `phrase4` text DEFAULT NULL,
  `phrase5` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='construction of ranked phrases to insert into reports';

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `kConfig` int(11) NOT NULL,
  `config_group` varchar(255) NOT NULL DEFAULT '' COMMENT 'Group to which this variable is associated',
  `config_key` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT '',
  `config_description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='global settings that apply all logged-in users';

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `kFeedback` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `submitDate` datetime NOT NULL,
  `subject` varchar(255) NOT NULL COMMENT 'the url associated with the particular feedback comment',
  `feedback` text NOT NULL,
  `rank` int(1) NOT NULL COMMENT 'ranking of the feedback in order of urgency',
  `activity` text NOT NULL COMMENT 'activity such as the last query and script',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=incomplete,1=complete',
  `comment` text NOT NULL COMMENT 'Administrator feedback',
  `commentDate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='feedback system for tracking needs';

-- --------------------------------------------------------

--
-- Table structure for table `global_subject`
--

CREATE TABLE `global_subject` (
  `grade_start` int(2) NOT NULL,
  `grade_end` int(2) NOT NULL,
  `context` varchar(30) NOT NULL DEFAULT 'grades',
  `subjects` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `kGrade` int(11) NOT NULL,
  `kStudent` int(5) NOT NULL,
  `kAssignment` int(5) NOT NULL,
  `points` float NOT NULL DEFAULT 0,
  `status` varchar(4) DEFAULT NULL COMMENT 'Abs for absent, Exc for excused',
  `footnote` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_preference`
--

CREATE TABLE `grade_preference` (
  `id` int(11) NOT NULL,
  `kStudent` int(11) UNSIGNED NOT NULL,
  `subject` varchar(25) NOT NULL DEFAULT '',
  `school_year` int(11) NOT NULL,
  `term` varchar(25) NOT NULL DEFAULT '',
  `pass_fail` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_scale`
--

CREATE TABLE `grade_scale` (
  `kTeach` int(4) NOT NULL,
  `gradeClass` enum('A-F','Pass/Fail','O/S/N') NOT NULL,
  `gradeID` varchar(5) NOT NULL,
  `gradeCutoff` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE `help` (
  `kHelp` int(4) NOT NULL,
  `helpTopic` varchar(55) NOT NULL,
  `helpSubTopic` varchar(55) NOT NULL,
  `helpText` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='provides help text that can be summoned based on query';

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

-- --------------------------------------------------------

--
-- Table structure for table `narrative`
--

CREATE TABLE `narrative` (
  `kNarrative` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL DEFAULT 0,
  `kTeach` int(5) NOT NULL DEFAULT 0,
  `stuGrade` varchar(2) NOT NULL DEFAULT '',
  `narrText` text DEFAULT NULL COMMENT 'actual text of the report',
  `narrSubject` varchar(50) NOT NULL DEFAULT '' COMMENT 'social, academic, emotional, etc',
  `narrTerm` varchar(50) NOT NULL DEFAULT '' COMMENT 'term when the report is submitted',
  `narrYear` int(4) NOT NULL,
  `narrGrade` varchar(20) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recModifier` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `narrative_edit`
--

CREATE TABLE `narrative_edit` (
  `kNarrative` int(5) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `kStudent` int(4) NOT NULL,
  `editorID` int(4) NOT NULL,
  `narrText` text NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preference`
--

CREATE TABLE `preference` (
  `kPreference` int(11) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `type` varchar(25) NOT NULL,
  `value` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='setting preferences for teachers such as how to view/edit re';

-- --------------------------------------------------------

--
-- Table structure for table `preference_type`
--

CREATE TABLE `preference_type` (
  `type` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'human-readable name of the preference',
  `description` text NOT NULL,
  `options` varchar(40) NOT NULL,
  `format` varchar(26) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `rec_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rec_modifier` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `query_tracking`
--

CREATE TABLE `query_tracking` (
  `kQuery` int(11) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `query` text NOT NULL,
  `script` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Attempt at feedback query tracking';

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `kStudent` int(4) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `stuFirst` varchar(25) NOT NULL,
  `stuLast` varchar(45) NOT NULL,
  `baseGrade` int(1) NOT NULL DEFAULT 0 COMMENT 'the first grade entered in the system, relies on baseYear to calculate current grade',
  `baseYear` int(4) NOT NULL COMMENT 'the year the baseGrade was entered in the system. Used to calculate stuGrade',
  `stuGroup` enum('A','B') DEFAULT NULL COMMENT '"A" or "B" for middleschool groups',
  `humanitiesTeacher` int(5) DEFAULT NULL COMMENT 'kTeach fk for Humanities sections',
  `stuNickname` varchar(15) NOT NULL,
  `stuGender` varchar(1) NOT NULL,
  `stuDOB` date NOT NULL,
  `stuEmail` varchar(255) DEFAULT NULL,
  `stuEmailPermission` tinyint(1) DEFAULT NULL COMMENT 'Received parental permission',
  `stuEmailPassword` varchar(255) DEFAULT NULL COMMENT 'current password',
  `isEnrolled` int(1) NOT NULL DEFAULT 0 COMMENT 'is student currently enrolled=1',
  `isGraduate` int(1) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recModifier` varchar(55) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Student database';

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `kAttendance` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL,
  `attendDate` date NOT NULL,
  `attendType` varchar(20) NOT NULL,
  `attendOverride` tinyint(1) DEFAULT NULL,
  `attendSubtype` varchar(25) DEFAULT NULL,
  `attendLength` varchar(8) DEFAULT NULL COMMENT 'half day or full day?',
  `attendNote` varchar(100) DEFAULT NULL,
  `recModifier` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='1:N table for students and attendance';

-- --------------------------------------------------------

--
-- Table structure for table `student_benchmark`
--

CREATE TABLE `student_benchmark` (
  `kStudentBenchmark` int(11) NOT NULL,
  `kStudent` int(4) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `kBenchmark` int(4) NOT NULL,
  `comment` text NOT NULL,
  `grade` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='rating student benchmark progress';

-- --------------------------------------------------------

--
-- Table structure for table `student_concept`
--

CREATE TABLE `student_concept` (
  `kStudentConcept` int(11) NOT NULL,
  `kStudent` int(4) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `kConcept` int(4) NOT NULL,
  `comment` text NOT NULL,
  `grade` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='rating student conceptual progress';

-- --------------------------------------------------------

--
-- Table structure for table `student_report`
--

CREATE TABLE `student_report` (
  `kReport` int(11) NOT NULL,
  `kStudent` int(11) NOT NULL,
  `kAdvisor` int(11) NOT NULL,
  `kTeach` int(11) NOT NULL COMMENT 'Teacher or staff member making the report',
  `is_read` tinyint(1) DEFAULT NULL COMMENT 'has the report been read yet?',
  `rank` int(11) DEFAULT NULL COMMENT 'numeric representation of a rank such as important, urgent, etc... defined in menu.report_rank',
  `category` varchar(255) DEFAULT NULL,
  `assignment_status` tinyint(1) DEFAULT NULL,
  `assignment` varchar(255) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `parent_contact` varchar(255) DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `contact_method` varchar(255) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp(),
  `recModifier` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='On-line equivalent of orange slips';

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

-- --------------------------------------------------------

--
-- Table structure for table `subject_sort`
--

CREATE TABLE `subject_sort` (
  `kReport` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL,
  `narrTerm` varchar(10) NOT NULL,
  `narrYear` int(4) NOT NULL,
  `context` enum('NARRATIVES','GRADES') DEFAULT NULL,
  `reportSort` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `kSupport` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL,
  `year` int(4) DEFAULT NULL COMMENT 'Year of the need entry',
  `meeting` int(1) DEFAULT NULL COMMENT 'has had the required fall meeting',
  `testDate` date DEFAULT NULL,
  `outsideSupport` text DEFAULT NULL,
  `specialNeed` text NOT NULL,
  `modification` text DEFAULT NULL,
  `hasIEP` tinyint(1) DEFAULT 0,
  `hasSPPS` tinyint(1) DEFAULT 0,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recModifier` varchar(55) DEFAULT NULL COMMENT 'modifier username'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='student learning support information ';

-- --------------------------------------------------------

--
-- Table structure for table `support_file`
--

CREATE TABLE `support_file` (
  `kFile` int(11) NOT NULL,
  `kSupport` int(11) NOT NULL COMMENT 'fk support',
  `kStudent` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_display_name` varchar(255) NOT NULL DEFAULT '',
  `file_type` varchar(30) NOT NULL DEFAULT '',
  `file_size` int(11) NOT NULL,
  `file_description` varchar(255) DEFAULT NULL,
  `file_path` text NOT NULL,
  `recModifier` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='related to support--student support';

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
(1, 1, 'administrator', 'email@example.com', 'Database', 'Administrator', '696d29e0940a4957748fe3fc9efd22a3', '', 0, 0, NULL, 1, 1, '', '2026-04-18 00:04:53', 'administrator'),
(13, 13, 'sjohnson', 'sjohnson@school.edu', 'Sarah', 'Johnson', '3714faf5c6953aad726265f1e94e8bb5', '10', NULL, NULL, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(2, 2, 'mchen', 'mchen@school.edu', 'Michael', 'Chen', '3714faf5c6953aad726265f1e94e8bb5', '12', 1, 2, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(3, 3, 'erodriguez', 'erodriguez@school.edu', 'Emily', 'Rodriguez', '3714faf5c6953aad726265f1e94e8bb5', '5', 1, 2, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(4, 4, 'dthompson', 'dthompson@school.edu', 'David', 'Thompson', '3714faf5c6953aad726265f1e94e8bb5', '15', 3, 4, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(5, 5, 'lmartinez', 'lmartinez@school.edu', 'Lisa', 'Martinez', '3714faf5c6953aad726265f1e94e8bb5', '2', 3, 4, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(6, 6, 'jwilson', 'jwilson@school.edu', 'James', 'Wilson', '3714faf5c6953aad726265f1e94e8bb5', '8', 5, 8, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(7, 7, 'panderson', 'panderson@school.edu', 'Patricia', 'Anderson', '3714faf5c6953aad726265f1e94e8bb5', '9', 5, 8, 1, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(8, 8, 'rgarcia', 'rgarcia@school.edu', 'Robert', 'Garcia', '3714faf5c6953aad726265f1e94e8bb5', '13', 5, 8, NULL, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(9, 9, 'jlee', 'jlee@school.edu', 'Jennifer', 'Lee', '3714faf5c6953aad726265f1e94e8bb5', '3', 5, 8, NULL, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(10, 10, 'tbrown', 'tbrown@school.edu', 'Thomas', 'Brown', '3714faf5c6953aad726265f1e94e8bb5', '1', NULL, 8, NULL, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(11, 11, 'kwhite', 'kwhite@school.edu', 'Karen', 'White', '3714faf5c6953aad726265f1e94e8bb5', '11', NULL, 8, NULL, 2, 1, NULL, '2026-04-18 00:05:26', '1000'),
(12, 12, 'crivera', 'crivera@school.edu', 'Carlos', 'Rivera', '3714faf5c6953aad726265f1e94e8bb5', '14', NULL, 8, NULL, 2, 1, NULL, '2026-04-18 00:05:26', '1000');

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
(13, 1, 'Reading', 0, 0),
(13, 2, 'Writing', 0, 0),
(13, 3, 'Math', 0, 0),
(13, 4, 'Science', 0, 0),
(13, 5, 'Social Studies', 0, 0),
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

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `kTemplate` int(6) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `template` text NOT NULL,
  `subject` varchar(85) NOT NULL DEFAULT '',
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` int(11) NOT NULL,
  `gradeEnd` int(11) NOT NULL,
  `type` varchar(25) DEFAULT '' COMMENT 'Excellent, Satisfactory, Needs Improvement, Pass, Pass with Honors',
  `isActive` int(11) NOT NULL,
  `recModifier` int(11) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `kTeach` int(4) NOT NULL,
  `username` varchar(55) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `action` enum('login','logout') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='database for tracking user logins/logouts';

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`kTeach`, `username`, `time`, `action`) VALUES
(1000, 'administrator', '2026-04-17 22:59:10', 'login'),
(1000, 'administrator', '2026-04-18 00:04:14', 'login');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('9ne27juh64pn0lg3eksvj3cvchq1a75j', '172.18.0.5', 1776470021, '__ci_last_regenerate|i:1776470021;username|s:13:\"administrator\";dbRole|s:1:\"1\";userID|s:4:\"1000\";'),
('iihtp4ktra8fsvhahlc1hvlhpeifmttv', '172.18.0.5', 1776470422, '__ci_last_regenerate|i:1776470021;username|s:13:\"administrator\";dbRole|s:1:\"1\";userID|s:4:\"1000\";'),
('cgodvoer9bih5adl9l4j7nl35vbedif5', '172.18.0.5', 1776470662, '__ci_last_regenerate|i:1776470646;username|s:13:\"administrator\";dbRole|s:1:\"1\";userID|s:4:\"1000\";');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`kAssignment`);

--
-- Indexes for table `assignment_category`
--
ALTER TABLE `assignment_category`
  ADD PRIMARY KEY (`kCategory`),
  ADD UNIQUE KEY `category` (`kTeach`,`category`,`gradeEnd`,`gradeStart`,`term`,`year`);

--
-- Indexes for table `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`kBackup`),
  ADD KEY `kNarrative` (`kNarrative`);
ALTER TABLE `backup` ADD FULLTEXT KEY `repSocial` (`narrText`,`narrSubject`);
ALTER TABLE `backup` ADD FULLTEXT KEY `narrType` (`narrSubject`);

--
-- Indexes for table `benchmark`
--
ALTER TABLE `benchmark`
  ADD PRIMARY KEY (`kBenchmark`);

--
-- Indexes for table `benchmark_legend`
--
ALTER TABLE `benchmark_legend`
  ADD PRIMARY KEY (`kLegend`);

--
-- Indexes for table `chart_legend`
--
ALTER TABLE `chart_legend`
  ADD PRIMARY KEY (`kLegend`);

--
-- Indexes for table `concept`
--
ALTER TABLE `concept`
  ADD PRIMARY KEY (`kConcept`);
ALTER TABLE `concept` ADD FULLTEXT KEY `benchmark` (`concept`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`kConfig`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`kFeedback`);

--
-- Indexes for table `global_subject`
--
ALTER TABLE `global_subject`
  ADD UNIQUE KEY `context_id` (`grade_start`,`grade_end`,`context`(1));

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`kGrade`);

--
-- Indexes for table `grade_preference`
--
ALTER TABLE `grade_preference`
  ADD PRIMARY KEY (`kStudent`,`subject`,`school_year`,`term`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `grade_scale`
--
ALTER TABLE `grade_scale`
  ADD KEY `kTeach` (`kTeach`),
  ADD KEY `gradeClass` (`gradeClass`),
  ADD KEY `gradeID` (`gradeID`),
  ADD KEY `gradeCutoff` (`gradeCutoff`);

--
-- Indexes for table `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`kHelp`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`kMenu`),
  ADD KEY `class` (`category`),
  ADD KEY `label` (`label`),
  ADD KEY `value` (`value`);

--
-- Indexes for table `narrative`
--
ALTER TABLE `narrative`
  ADD PRIMARY KEY (`kNarrative`);
ALTER TABLE `narrative` ADD FULLTEXT KEY `repSocial` (`narrText`,`narrSubject`);
ALTER TABLE `narrative` ADD FULLTEXT KEY `narrText` (`narrText`);
ALTER TABLE `narrative` ADD FULLTEXT KEY `narrType` (`narrSubject`);

--
-- Indexes for table `narrative_edit`
--
ALTER TABLE `narrative_edit`
  ADD PRIMARY KEY (`kNarrative`),
  ADD KEY `kNarrative` (`kNarrative`,`kTeach`,`kStudent`),
  ADD KEY `editorID` (`editorID`);
ALTER TABLE `narrative_edit` ADD FULLTEXT KEY `narrText` (`narrText`);

--
-- Indexes for table `preference`
--
ALTER TABLE `preference`
  ADD PRIMARY KEY (`kPreference`),
  ADD KEY `kTeach` (`kTeach`);

--
-- Indexes for table `preference_type`
--
ALTER TABLE `preference_type`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `query_tracking`
--
ALTER TABLE `query_tracking`
  ADD PRIMARY KEY (`kQuery`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`kStudent`),
  ADD KEY `stuNickname` (`stuNickname`);
ALTER TABLE `student` ADD FULLTEXT KEY `stuFirst` (`stuFirst`);
ALTER TABLE `student` ADD FULLTEXT KEY `stuLast` (`stuLast`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`kAttendance`),
  ADD KEY `kStudent` (`kStudent`),
  ADD KEY `attendDate` (`attendDate`),
  ADD KEY `attendType` (`attendType`);

--
-- Indexes for table `student_benchmark`
--
ALTER TABLE `student_benchmark`
  ADD PRIMARY KEY (`kStudentBenchmark`),
  ADD KEY `kStudent` (`kStudent`);

--
-- Indexes for table `student_concept`
--
ALTER TABLE `student_concept`
  ADD PRIMARY KEY (`kStudentConcept`),
  ADD KEY `kStudent` (`kStudent`);

--
-- Indexes for table `student_report`
--
ALTER TABLE `student_report`
  ADD PRIMARY KEY (`kReport`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`kSubject`),
  ADD KEY `subject` (`subject`);

--
-- Indexes for table `subject_sort`
--
ALTER TABLE `subject_sort`
  ADD PRIMARY KEY (`kReport`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`kSupport`),
  ADD KEY `kStudent` (`kStudent`);
ALTER TABLE `support` ADD FULLTEXT KEY `modification` (`modification`);

--
-- Indexes for table `support_file`
--
ALTER TABLE `support_file`
  ADD PRIMARY KEY (`kFile`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`kTeach`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  ADD PRIMARY KEY (`kSubject`),
  ADD KEY `kTeach` (`kTeach`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`kTemplate`);
ALTER TABLE `template` ADD FULLTEXT KEY `template` (`template`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD KEY `kTeach` (`username`),
  ADD KEY `logTime` (`time`),
  ADD KEY `logAction` (`action`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_activity_idx` (`timestamp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `kAssignment` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignment_category`
--
ALTER TABLE `assignment_category`
  MODIFY `kCategory` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backup`
--
ALTER TABLE `backup`
  MODIFY `kBackup` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `benchmark`
--
ALTER TABLE `benchmark`
  MODIFY `kBenchmark` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `benchmark_legend`
--
ALTER TABLE `benchmark_legend`
  MODIFY `kLegend` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_legend`
--
ALTER TABLE `chart_legend`
  MODIFY `kLegend` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concept`
--
ALTER TABLE `concept`
  MODIFY `kConcept` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `kConfig` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `kFeedback` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `kGrade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade_preference`
--
ALTER TABLE `grade_preference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help`
--
ALTER TABLE `help`
  MODIFY `kHelp` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `kMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `narrative`
--
ALTER TABLE `narrative`
  MODIFY `kNarrative` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preference`
--
ALTER TABLE `preference`
  MODIFY `kPreference` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `query_tracking`
--
ALTER TABLE `query_tracking`
  MODIFY `kQuery` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `kStudent` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `kAttendance` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_benchmark`
--
ALTER TABLE `student_benchmark`
  MODIFY `kStudentBenchmark` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_concept`
--
ALTER TABLE `student_concept`
  MODIFY `kStudentConcept` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_report`
--
ALTER TABLE `student_report`
  MODIFY `kReport` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `kSubject` int(2) NOT NULL AUTO_INCREMENT COMMENT 'key and sort order for subjects', AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `subject_sort`
--
ALTER TABLE `subject_sort`
  MODIFY `kReport` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `kSupport` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_file`
--
ALTER TABLE `support_file`
  MODIFY `kFile` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `kTeach` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  MODIFY `kSubject` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `kTemplate` int(6) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
