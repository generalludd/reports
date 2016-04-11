/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table assignment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assignment`;

CREATE TABLE `assignment` (
  `kAssignment` int(5) NOT NULL AUTO_INCREMENT,
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
  `gradeEnd` int(1) NOT NULL,
  PRIMARY KEY (`kAssignment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table assignment_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assignment_category`;

CREATE TABLE `assignment_category` (
  `kCategory` int(11) NOT NULL AUTO_INCREMENT,
  `kTeach` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `gradeStart` int(11) NOT NULL,
  `gradeEnd` int(11) NOT NULL,
  `term` enum('Mid-Year','Year-End') NOT NULL DEFAULT 'Mid-Year',
  `year` int(11) NOT NULL,
  PRIMARY KEY (`kCategory`),
  UNIQUE KEY `category` (`kTeach`,`category`,`gradeEnd`,`gradeStart`,`term`,`year`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='provide grade weights to categories';



# Dump of table backup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `backup`;

CREATE TABLE `backup` (
  `kBackup` int(6) NOT NULL AUTO_INCREMENT,
  `kNarrative` int(5) NOT NULL,
  `kStudent` int(5) NOT NULL DEFAULT '0',
  `kTeach` int(5) NOT NULL DEFAULT '0',
  `stuGrade` int(11) NOT NULL,
  `narrText` text COMMENT 'actual text of the report',
  `narrSubject` varchar(50) NOT NULL DEFAULT '' COMMENT 'social, academic, emotional, etc',
  `narrTerm` varchar(50) NOT NULL DEFAULT '' COMMENT 'term when the report is submitted',
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recModifier` text NOT NULL,
  `narrGrade` varchar(20) DEFAULT NULL,
  `narrYear` int(4) NOT NULL,
  PRIMARY KEY (`kBackup`),
  KEY `kNarrative` (`kNarrative`),
  FULLTEXT KEY `repSocial` (`narrText`,`narrSubject`),
  FULLTEXT KEY `narrType` (`narrSubject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table benchmark
# ------------------------------------------------------------

DROP TABLE IF EXISTS `benchmark`;

CREATE TABLE `benchmark` (
  `kBenchmark` int(4) NOT NULL AUTO_INCREMENT,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) DEFAULT NULL,
  `subject` varchar(25) NOT NULL,
  `category` varchar(55) NOT NULL,
  `weight` int(2) DEFAULT NULL COMMENT 'used for sorting categories',
  `benchmark` text NOT NULL,
  `recModifier` varchar(4) NOT NULL COMMENT 'who last modified the record',
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kBenchmark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table benchmark_legend
# ------------------------------------------------------------

DROP TABLE IF EXISTS `benchmark_legend`;

CREATE TABLE `benchmark_legend` (
  `kLegend` int(5) NOT NULL AUTO_INCREMENT,
  `kTeach` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) NOT NULL,
  `title` varchar(60) NOT NULL,
  `legend` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kLegend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Legend for a given chart ';



# Dump of table chart_legend
# ------------------------------------------------------------

DROP TABLE IF EXISTS `chart_legend`;

CREATE TABLE `chart_legend` (
  `kLegend` int(5) NOT NULL AUTO_INCREMENT,
  `kTeach` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) NOT NULL,
  `legend` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kLegend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Legend for a given chart ';



# Dump of table concept
# ------------------------------------------------------------

DROP TABLE IF EXISTS `concept`;

CREATE TABLE `concept` (
  `kConcept` int(4) NOT NULL AUTO_INCREMENT,
  `kTeach` int(5) NOT NULL,
  `term` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `subject` varchar(25) NOT NULL,
  `gradeStart` varchar(1) NOT NULL,
  `gradeEnd` varchar(1) DEFAULT NULL,
  `category` varchar(55) NOT NULL,
  `weight` int(2) NOT NULL COMMENT 'used for sorting categories',
  `concept` text NOT NULL,
  `phrase1` text,
  `phrase2` text,
  `phrase3` text,
  `phrase4` text,
  `phrase5` text,
  PRIMARY KEY (`kConcept`),
  FULLTEXT KEY `benchmark` (`concept`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='construction of ranked phrases to insert into reports';



# Dump of table config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `kConfig` int(11) NOT NULL AUTO_INCREMENT,
  `config_group` varchar(255) NOT NULL DEFAULT '' COMMENT 'Group to which this variable is associated',
  `config_key` varchar(255) NOT NULL DEFAULT '',
  `config_value` varchar(255) NOT NULL DEFAULT '',
  `config_description` text NOT NULL,
  PRIMARY KEY (`kConfig`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='global settings that apply all logged-in users';



# Dump of table feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback` (
  `kFeedback` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `submitDate` datetime NOT NULL,
  `subject` varchar(255) NOT NULL COMMENT 'the url associated with the particular feedback comment',
  `feedback` text NOT NULL,
  `rank` int(1) NOT NULL COMMENT 'ranking of the feedback in order of urgency',
  `activity` text NOT NULL COMMENT 'activity such as the last query and script',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0=incomplete,1=complete',
  `comment` text NOT NULL COMMENT 'Administrator feedback',
  `commentDate` datetime NOT NULL,
  PRIMARY KEY (`kFeedback`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='feedback system for tracking needs';



# Dump of table global_subject
# ------------------------------------------------------------

DROP TABLE IF EXISTS `global_subject`;

CREATE TABLE `global_subject` (
  `grade_start` int(2) NOT NULL,
  `grade_end` int(2) NOT NULL,
  `context` varchar(30) NOT NULL DEFAULT 'grades',
  `subjects` text NOT NULL,
  UNIQUE KEY `context_id` (`grade_start`,`grade_end`,`context`(1))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table grade
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grade`;

CREATE TABLE `grade` (
  `kGrade` int(11) NOT NULL AUTO_INCREMENT,
  `kStudent` int(5) NOT NULL,
  `kAssignment` int(5) NOT NULL,
  `points` float NOT NULL DEFAULT '0',
  `status` varchar(4) DEFAULT NULL COMMENT 'Abs for absent, Exc for excused',
  `footnote` int(1) DEFAULT NULL,
  PRIMARY KEY (`kGrade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table grade_preference
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grade_preference`;

CREATE TABLE `grade_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kStudent` int(11) unsigned NOT NULL,
  `subject` varchar(25) NOT NULL DEFAULT '',
  `school_year` int(11) NOT NULL,
  `term` varchar(25) NOT NULL DEFAULT '',
  `pass_fail` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`kStudent`,`subject`,`school_year`,`term`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table grade_scale
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grade_scale`;

CREATE TABLE `grade_scale` (
  `kTeach` int(4) NOT NULL,
  `gradeClass` enum('A-F','Pass/Fail','O/S/N') NOT NULL,
  `gradeID` varchar(5) NOT NULL,
  `gradeCutoff` double NOT NULL,
  KEY `kTeach` (`kTeach`),
  KEY `gradeClass` (`gradeClass`),
  KEY `gradeID` (`gradeID`),
  KEY `gradeCutoff` (`gradeCutoff`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table help
# ------------------------------------------------------------

DROP TABLE IF EXISTS `help`;

CREATE TABLE `help` (
  `kHelp` int(4) NOT NULL AUTO_INCREMENT,
  `helpTopic` varchar(55) NOT NULL,
  `helpSubTopic` varchar(55) NOT NULL,
  `helpText` text NOT NULL,
  PRIMARY KEY (`kHelp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='provides help text that can be summoned based on query';



# Dump of table menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `kMenu` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(35) NOT NULL DEFAULT '',
  `label` varchar(35) NOT NULL,
  `value` varchar(65) NOT NULL DEFAULT '',
  PRIMARY KEY (`kMenu`),
  KEY `class` (`category`),
  KEY `label` (`label`),
  KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='list of menu items by class for use in the UI';



# Dump of table narrative
# ------------------------------------------------------------

DROP TABLE IF EXISTS `narrative`;

CREATE TABLE `narrative` (
  `kNarrative` int(5) NOT NULL AUTO_INCREMENT,
  `kStudent` int(5) NOT NULL DEFAULT '0',
  `kTeach` int(5) NOT NULL DEFAULT '0',
  `stuGrade` varchar(2) NOT NULL DEFAULT '',
  `narrText` text COMMENT 'actual text of the report',
  `narrSubject` varchar(50) NOT NULL DEFAULT '' COMMENT 'social, academic, emotional, etc',
  `narrTerm` varchar(50) NOT NULL DEFAULT '' COMMENT 'term when the report is submitted',
  `narrYear` int(4) NOT NULL,
  `narrGrade` varchar(20) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recModifier` text NOT NULL,
  PRIMARY KEY (`kNarrative`),
  FULLTEXT KEY `repSocial` (`narrText`,`narrSubject`),
  FULLTEXT KEY `narrText` (`narrText`),
  FULLTEXT KEY `narrType` (`narrSubject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table narrative_edit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `narrative_edit`;

CREATE TABLE `narrative_edit` (
  `kNarrative` int(5) NOT NULL,
  `kTeach` int(4) NOT NULL,
  `kStudent` int(4) NOT NULL,
  `editorID` int(4) NOT NULL,
  `narrText` text NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kNarrative`),
  KEY `kNarrative` (`kNarrative`,`kTeach`,`kStudent`),
  KEY `editorID` (`editorID`),
  FULLTEXT KEY `narrText` (`narrText`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table preference
# ------------------------------------------------------------

DROP TABLE IF EXISTS `preference`;

CREATE TABLE `preference` (
  `kPreference` int(11) NOT NULL AUTO_INCREMENT,
  `kTeach` int(3) NOT NULL,
  `type` varchar(25) NOT NULL,
  `value` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`kPreference`),
  KEY `kTeach` (`kTeach`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='setting preferences for teachers such as how to view/edit re';



# Dump of table preference_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `preference_type`;

CREATE TABLE `preference_type` (
  `type` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'human-readable name of the preference',
  `description` text NOT NULL,
  `options` varchar(40) NOT NULL,
  `format` varchar(26) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `rec_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rec_modifier` int(11) NOT NULL,
  PRIMARY KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table query_tracking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `query_tracking`;

CREATE TABLE `query_tracking` (
  `kQuery` int(11) NOT NULL AUTO_INCREMENT,
  `kTeach` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `query` text NOT NULL,
  `script` varchar(25) NOT NULL,
  PRIMARY KEY (`kQuery`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Attempt at feedback query tracking';



# Dump of table student
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `kStudent` int(4) NOT NULL AUTO_INCREMENT,
  `kTeach` int(3) NOT NULL,
  `stuFirst` varchar(25) NOT NULL,
  `stuLast` varchar(45) NOT NULL,
  `baseGrade` int(1) NOT NULL DEFAULT '0' COMMENT 'the first grade entered in the system, relies on baseYear to calculate current grade',
  `baseYear` int(4) NOT NULL COMMENT 'the year the baseGrade was entered in the system. Used to calculate stuGrade',
  `stuGroup` enum('A','B') DEFAULT NULL COMMENT '"A" or "B" for middleschool groups',
  `humanitiesTeacher` int(5) DEFAULT NULL COMMENT 'kTeach fk for Humanities sections',
  `stuNickname` varchar(15) NOT NULL,
  `stuGender` varchar(1) NOT NULL,
  `stuDOB` date NOT NULL,
  `stuEmail` varchar(255) DEFAULT NULL,
  `stuEmailPermission` tinyint(1) DEFAULT NULL COMMENT 'Received parental permission',
  `stuEmailPassword` varchar(255) DEFAULT NULL COMMENT 'current password',
  `isEnrolled` int(1) NOT NULL DEFAULT '0' COMMENT 'is student currently enrolled=1',
  `isGraduate` int(1) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recModifier` varchar(55) NOT NULL,
  PRIMARY KEY (`kStudent`),
  KEY `stuNickname` (`stuNickname`),
  FULLTEXT KEY `stuFirst` (`stuFirst`),
  FULLTEXT KEY `stuLast` (`stuLast`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Student database';



# Dump of table student_attendance
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student_attendance`;

CREATE TABLE `student_attendance` (
  `kAttendance` int(5) NOT NULL AUTO_INCREMENT,
  `kStudent` int(5) NOT NULL,
  `attendDate` date NOT NULL,
  `attendType` varchar(20) NOT NULL,
  `attendOverride` tinyint(1) DEFAULT NULL,
  `attendSubtype` varchar(25) DEFAULT NULL,
  `attendLength` varchar(8) DEFAULT NULL COMMENT 'half day or full day?',
  `attendNote` varchar(100) DEFAULT NULL,
  `recModifier` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kAttendance`),
  KEY `kStudent` (`kStudent`),
  KEY `attendDate` (`attendDate`),
  KEY `attendType` (`attendType`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='1:N table for students and attendance';



# Dump of table student_benchmark
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student_benchmark`;

CREATE TABLE `student_benchmark` (
  `kStudentBenchmark` int(11) NOT NULL AUTO_INCREMENT,
  `kStudent` int(4) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `kBenchmark` int(4) NOT NULL,
  `comment` text NOT NULL,
  `grade` varchar(1) NOT NULL,
  PRIMARY KEY (`kStudentBenchmark`),
  KEY `kStudent` (`kStudent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rating student benchmark progress';



# Dump of table student_concept
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student_concept`;

CREATE TABLE `student_concept` (
  `kStudentConcept` int(11) NOT NULL AUTO_INCREMENT,
  `kStudent` int(4) NOT NULL,
  `kTeach` int(3) NOT NULL,
  `kConcept` int(4) NOT NULL,
  `comment` text NOT NULL,
  `grade` varchar(1) NOT NULL,
  PRIMARY KEY (`kStudentConcept`),
  KEY `kStudent` (`kStudent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rating student conceptual progress';



# Dump of table student_report
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student_report`;

CREATE TABLE `student_report` (
  `kReport` int(11) NOT NULL AUTO_INCREMENT,
  `kStudent` int(11) NOT NULL,
  `kAdvisor` int(11) NOT NULL,
  `kTeach` int(11) NOT NULL COMMENT 'Teacher or staff member making the report',
  `is_read` tinyint(1) DEFAULT NULL COMMENT 'has the report been read yet?',
  `rank` int(11) DEFAULT NULL COMMENT 'numeric representation of a rank such as important, urgent, etc... defined in menu.report_rank',
  `category` varchar(255) DEFAULT NULL,
  `assignment_status` tinyint(1) DEFAULT NULL,
  `assignment` varchar(255) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `comment` text,
  `parent_contact` varchar(255) DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `contact_method` varchar(255) DEFAULT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recModifier` int(11) NOT NULL,
  PRIMARY KEY (`kReport`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='On-line equivalent of orange slips';



# Dump of table subject
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subject`;

CREATE TABLE `subject` (
  `subject` varchar(55) NOT NULL,
  `gradeStart` int(1) NOT NULL,
  `gradeEnd` int(1) NOT NULL,
  `kSubject` int(2) NOT NULL COMMENT 'key and sort order for subjects',
  PRIMARY KEY (`kSubject`),
  KEY `subject` (`subject`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='a list of subjects for each grade (range)';



# Dump of table subject_sort
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subject_sort`;

CREATE TABLE `subject_sort` (
  `kReport` int(5) NOT NULL AUTO_INCREMENT,
  `kStudent` int(5) NOT NULL,
  `narrTerm` varchar(10) NOT NULL,
  `narrYear` int(4) NOT NULL,
  `context` enum('NARRATIVES','GRADES') DEFAULT NULL,
  `reportSort` text NOT NULL,
  `recModifier` int(5) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kReport`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table support
# ------------------------------------------------------------

DROP TABLE IF EXISTS `support`;

CREATE TABLE `support` (
  `kSupport` int(5) NOT NULL AUTO_INCREMENT,
  `kStudent` int(5) NOT NULL,
  `year` int(4) DEFAULT NULL COMMENT 'Year of the need entry',
  `meeting` int(1) DEFAULT NULL COMMENT 'has had the required fall meeting',
  `testDate` date DEFAULT NULL,
  `outsideSupport` text,
  `specialNeed` text NOT NULL,
  `modification` text,
  `hasIEP` tinyint(1) DEFAULT '0',
  `hasSPPS` tinyint(1) DEFAULT '0',
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recModifier` varchar(55) DEFAULT NULL COMMENT 'modifier username',
  PRIMARY KEY (`kSupport`),
  KEY `kStudent` (`kStudent`),
  FULLTEXT KEY `modification` (`modification`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='student learning support information ';



# Dump of table support_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `support_file`;

CREATE TABLE `support_file` (
  `kFile` int(11) NOT NULL AUTO_INCREMENT,
  `kSupport` int(11) NOT NULL COMMENT 'fk support',
  `kStudent` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_display_name` varchar(255) NOT NULL DEFAULT '',
  `file_type` varchar(30) NOT NULL DEFAULT '',
  `file_size` int(11) NOT NULL,
  `file_description` varchar(255) DEFAULT NULL,
  `file_path` text NOT NULL,
  `recModifier` int(4) NOT NULL,
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kFile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='related to support--student support';



# Dump of table teacher
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher` (
  `kTeach` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(55) NOT NULL,
  `teachFirst` varchar(255) NOT NULL,
  `teachLast` varchar(255) NOT NULL,
  `pwd` varchar(32) NOT NULL DEFAULT '3714faf5c6953aad726265f1e94e8bb5',
  `teachClass` varchar(25) DEFAULT NULL,
  `gradeStart` tinyint(1) DEFAULT '0',
  `gradeEnd` tinyint(1) DEFAULT '0',
  `isAdvisor` tinyint(1) DEFAULT NULL,
  `dbRole` int(1) NOT NULL DEFAULT '2' COMMENT '1=admin,2=user',
  `status` tinyint(1) NOT NULL,
  `resetHash` varchar(32) DEFAULT NULL COMMENT 'A hash for resetting a password',
  `recModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `recModifier` varchar(15) NOT NULL,
  PRIMARY KEY (`kTeach`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Teacher Database';



# Dump of table teacher_subject
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teacher_subject`;

CREATE TABLE `teacher_subject` (
  `kTeach` int(4) DEFAULT NULL,
  `kSubject` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `gradeStart` int(11) NOT NULL,
  `gradeEnd` int(11) NOT NULL,
  PRIMARY KEY (`kSubject`),
  KEY `kTeach` (`kTeach`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='N:N table for teachers and subjects';



# Dump of table template
# ------------------------------------------------------------

DROP TABLE IF EXISTS `template`;

CREATE TABLE `template` (
  `kTemplate` int(6) NOT NULL AUTO_INCREMENT,
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
  `recModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kTemplate`),
  FULLTEXT KEY `template` (`template`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table user_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_log`;

CREATE TABLE `user_log` (
  `kTeach` int(4) NOT NULL,
  `username` varchar(55) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` enum('login','logout') NOT NULL,
  KEY `kTeach` (`username`),
  KEY `logTime` (`time`),
  KEY `logAction` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='database for tracking user logins/logouts';



# Dump of table user_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_sessions`;

CREATE TABLE `user_sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
