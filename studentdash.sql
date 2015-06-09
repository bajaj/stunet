-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 01, 2013 at 07:05 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `studentdash`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `category` varchar(255) NOT NULL,
  `allowcomments` tinyint(4) DEFAULT '1',
  `content` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_blog` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`ID`, `title`, `creator`, `created`, `type`, `category`, `allowcomments`, `content`) VALUES
(3, 'connections', 4, '2013-03-01 09:20:18', 'Public', 'people', 1, '<p>Here I''ll be posting about people and the importance of understanding your relationship with people!</p>\r\n<p>&nbsp;</p>\r\n<p><iframe class="youtube" title="YouTube video player" src="http://www.youtube.com/embed/nKIu9yen5nc" frameborder="0" width="430" height="253"></iframe></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>'),
(4, 'Stats', 4, '2013-03-01 10:09:11', 'Private', 'Maths', 1, '<p>This group is only for people in Stats!</p>'),
(5, 'My 3rd blog', 4, '2013-03-01 10:14:13', 'Public', 'personal', 0, '<p>Third blog!</p>');

-- --------------------------------------------------------

--
-- Table structure for table `blogcomments`
--

CREATE TABLE IF NOT EXISTS `blogcomments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `blogid` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `idx_blogcomments` (`creator`),
  KEY `idx_blogcomments_0` (`blogid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `chat_users`
--
CREATE TABLE IF NOT EXISTS `chat_users` (
`user_id` int(11)
,`fname` text
,`lname` text
);
-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `controller` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `controller` (`controller`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`ID`, `active`, `controller`) VALUES
(1, 1, 'authenticate'),
(2, 1, 'members'),
(3, 1, 'relationship'),
(4, 1, 'relationships'),
(5, 1, 'profile'),
(6, 1, 'stream'),
(7, 1, 'messages'),
(8, 1, 'calendar'),
(9, 1, 'event'),
(10, 1, 'groups'),
(11, 1, 'group'),
(12, 1, 'pdm'),
(13, 1, 'blogs'),
(14, 1, 'blog'),
(15, 1, 'admin'),
(16, 1, 'chat'),
(17, 1, 'evaluation'),
(18, 1, 'timetable'),
(19, 1, 'home');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_details`
--

CREATE TABLE IF NOT EXISTS `evaluation_details` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `out_of_marks` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `date` text NOT NULL,
  `description` text NOT NULL,
  `creator` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_evaluation_details_groups` (`group_id`),
  KEY `idx_evaluation_details` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `evaluation_details`
--

INSERT INTO `evaluation_details` (`ID`, `name`, `out_of_marks`, `group_id`, `date`, `description`, `creator`) VALUES
(2, 'MST', 10, 5, '1 Mar 2013', 'Hi', 8),
(3, 'CT2', 10, 5, '1 Mar 2013', 'class test even sem', 6);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_results`
--

CREATE TABLE IF NOT EXISTS `evaluation_results` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `marks` int(11) NOT NULL,
  `remarks` text,
  `evaluation_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_evaluation_results` (`evaluation_id`),
  KEY `idx_evaluation_results` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `evaluation_results`
--

INSERT INTO `evaluation_results` (`ID`, `marks`, `remarks`, `evaluation_id`, `user_id`) VALUES
(2, 7, 'V. Good', 2, 4),
(3, 8, 'v. good', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `date` date NOT NULL,
  `start_time` text NOT NULL,
  `end_time` text NOT NULL,
  `type` enum('Public','Private') NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_events_users` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`ID`, `creator`, `name`, `description`, `date`, `start_time`, `end_time`, `type`, `active`) VALUES
(1, 8, 'Hacking Seminar', 'Its a hacking program', '2013-03-03', '04:19PM', '07:19PM', 'Public', 0),
(2, 8, 'My Engagement', 'My engagement, everyone has to come', '2019-12-06', '04:03PM', '05:03PM', 'Public', 0),
(3, 4, 'All', 'hi', '2015-03-03', '07:13PM', '06:13PM', 'Public', 0),
(4, 4, 'Hi', 'ssss', '2013-03-09', '07:15PM', '09:16PM', 'Public', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE IF NOT EXISTS `event_attendees` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `isgroup` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `idx_event_attendees` (`event_id`),
  KEY `idx_event_attendees_0` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `event_attendees`
--

INSERT INTO `event_attendees` (`ID`, `event_id`, `user_id`, `status`, `isgroup`) VALUES
(1, 1, 4, 'invited', 0),
(2, 1, 8, 'attending', 0),
(3, 2, 5, 'invited', 0),
(4, 2, 4, 'invited', 0),
(5, 2, 4, 'invited', 1),
(6, 2, 5, 'invited', 0),
(7, 2, 8, 'attending', 0),
(8, 3, 1, 'invited', 0),
(9, 3, 6, 'invited', 0),
(10, 3, 5, 'invited', 0),
(11, 3, 8, 'invited', 0),
(12, 3, 4, 'attending', 0),
(13, 4, 5, 'invited', 1),
(14, 4, 5, 'invited', 0),
(15, 4, 6, 'invited', 0),
(16, 4, 4, 'attending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `teacher` int(11) NOT NULL,
  `secured` text,
  `subject` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_grade_groups` (`group_id`),
  KEY `idx_grade` (`user_id`),
  KEY `idx_grade_0` (`teacher`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`ID`, `date`, `teacher`, `secured`, `subject`, `group_id`, `user_id`) VALUES
(2, 1, 6, 'A', 'NSP', 5, 4),
(3, 1, 8, 'B', 'JAVA', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('Public','Private') NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `college` varchar(255) NOT NULL DEFAULT 'Any',
  `creator` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_groups` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`ID`, `name`, `description`, `created`, `type`, `active`, `college`, `creator`) VALUES
(1, 'Vjti Time', 'This is a group for T.I.M.E students of VJTI college only!', '2013-02-28 13:38:43', 'Public', 1, 'VJTi', 4),
(2, 'gre', 'GRE', '2013-02-28 14:04:53', 'Public', 1, 'vjti', 6),
(3, 'BTech computers', '<p>This is a group only for fourth year students of VJTi computers Batch 2012 - 2013</p>', '2013-03-01 07:21:02', 'Private', 1, 'VJTi', 4),
(4, 'Ecell', 'The Entreprenuer cell''s group (VJTI)', '2013-03-01 10:02:52', 'Private', 1, 'VJTi', 4),
(5, 'Vjti', 'Vjti', '2013-03-01 10:38:45', 'Public', 1, 'vjti', 8),
(6, 'TYBtech', 'Private', '2013-03-01 12:00:46', 'Public', 1, 'VJTI', 5);

-- --------------------------------------------------------

--
-- Table structure for table `group_membership`
--

CREATE TABLE IF NOT EXISTS `group_membership` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `requested` tinyint(1) NOT NULL DEFAULT '0',
  `invited` tinyint(1) NOT NULL DEFAULT '0',
  `requested_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invited_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `join_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inviter` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_group_membership` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `group_membership`
--

INSERT INTO `group_membership` (`ID`, `group`, `user`, `approved`, `requested`, `invited`, `requested_date`, `invited_date`, `join_date`, `inviter`) VALUES
(1, 1, 7, 0, 1, 0, '2013-02-28 08:48:31', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(2, 2, 7, 1, 1, 0, '2013-02-28 08:48:46', '0000-00-00 00:00:00', '2013-02-28 08:52:29', 0),
(3, 1, 6, 1, 1, 0, '2013-02-28 08:49:07', '0000-00-00 00:00:00', '2013-03-01 05:05:18', 0),
(4, 3, 5, 0, 0, 1, '0000-00-00 00:00:00', '2013-03-01 01:51:02', '0000-00-00 00:00:00', 4),
(7, 4, 5, 0, 0, 1, '0000-00-00 00:00:00', '2013-03-01 04:32:52', '0000-00-00 00:00:00', 4),
(8, 4, 8, 0, 0, 1, '0000-00-00 00:00:00', '2013-03-01 04:32:52', '0000-00-00 00:00:00', 4),
(9, 2, 8, 0, 1, 0, '2013-03-01 05:04:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(10, 1, 8, 1, 1, 0, '2013-03-01 05:04:49', '0000-00-00 00:00:00', '2013-03-01 05:05:22', 0),
(11, 5, 5, 0, 0, 1, '0000-00-00 00:00:00', '2013-03-01 05:08:46', '0000-00-00 00:00:00', 8),
(12, 5, 4, 1, 0, 1, '0000-00-00 00:00:00', '2013-03-01 05:08:46', '2013-03-01 05:09:28', 8),
(13, 5, 6, 1, 1, 0, '2013-03-01 05:31:09', '0000-00-00 00:00:00', '2013-03-01 05:31:18', 0),
(14, 6, 8, 1, 0, 1, '0000-00-00 00:00:00', '2013-03-01 06:30:46', '2013-03-01 06:35:08', 5);

-- --------------------------------------------------------

--
-- Table structure for table `logged_in`
--

CREATE TABLE IF NOT EXISTS `logged_in` (
  `user_id` int(11) NOT NULL,
  KEY `idx_logged_in` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logged_in`
--

INSERT INTO `logged_in` (`user_id`) VALUES
(1),
(1),
(1),
(1),
(4),
(4),
(4),
(5),
(5),
(5),
(6),
(6),
(6),
(6),
(6),
(8),
(8);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `recipient` int(11) NOT NULL,
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `sender_delete` tinyint(1) NOT NULL DEFAULT '0',
  `recipient_delete` tinyint(1) NOT NULL DEFAULT '0',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `event_mail` tinyint(1) NOT NULL DEFAULT '0',
  `group` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `fk_messages_users2` (`sender`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`ID`, `sender`, `recipient`, `sent`, `read`, `subject`, `message`, `sender_delete`, `recipient_delete`, `draft`, `event_mail`, `group`) VALUES
(100, 8, 5, '2013-03-01 14:24:05', 1, 'Event Invitation', '<p>Rahul Sharma Invited you to the event <a href="event/view/6"> Hi </a></p>', 0, 1, 0, 1, 0),
(101, 5, 4, '2013-03-01 14:33:32', 0, 'hi', 'hello sir', 0, 0, 0, 0, 0),
(102, 5, 8, '2013-03-01 14:34:08', 1, 'rahul shamr', 'rahul shamr', 1, 0, 0, 0, 0),
(103, 8, 5, '2013-03-01 14:34:36', 0, 'Re: rahul shamr', 'save', 0, 0, 1, 0, 0),
(104, 8, 5, '2013-03-01 14:34:49', 1, 'Re: rahul shamr', 'ok thanks', 0, 0, 0, 0, 0),
(105, 8, 5, '2013-03-01 14:35:28', 0, 'Re: rahul shamr', 'save\r\nok', 0, 0, 0, 0, 0),
(106, 8, 5, '2013-03-01 14:35:53', 0, 'Re: rahul shamr', 'This shoulld be in draft', 0, 0, 0, 0, 0),
(107, 5, 4, '2013-03-01 14:38:21', 0, '', '', 0, 0, 1, 0, 0),
(108, 8, 5, '2013-03-01 14:42:12', 0, 'lol', 'lol', 0, 0, 0, 0, 1),
(109, 8, 5, '2013-03-01 14:42:13', 0, 'lol', 'lol', 0, 0, 0, 1, 0),
(110, 8, 4, '2013-03-01 14:42:13', 0, 'lol', 'lol', 0, 0, 0, 1, 0),
(111, 8, 6, '2013-03-01 14:42:13', 0, 'lol', 'lol', 0, 0, 0, 1, 0),
(112, 8, 4, '2013-03-01 14:42:13', 0, 'lol', 'lol', 0, 0, 0, 0, 0),
(113, 8, 5, '2013-03-01 14:46:20', 0, 'This should not be sent to Priyank', 'This should not be sent to Priyank', 1, 0, 0, 0, 1),
(114, 8, 4, '2013-03-01 14:46:20', 0, 'This should not be sent to Priyank', 'This should not be sent to Priyank', 0, 0, 0, 1, 0),
(115, 8, 6, '2013-03-01 14:46:20', 0, 'This should not be sent to Priyank', 'This should not be sent to Priyank', 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `topic` int(11) NOT NULL,
  `post` longtext NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isfirst` tinyint(1) DEFAULT '0',
  `creator` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_post_topic` (`topic`),
  KEY `idx_post` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`ID`, `topic`, `post`, `created`, `isfirst`, `creator`) VALUES
(1, 1, '<p>Hey guys welcome to this group, hope this group helps each other in some way or the other</p>', '2013-02-28 13:40:01', 1, 4),
(2, 2, '<p>Hey guys welcome to this group, hope this group helps each other in some way or the other</p>', '2013-02-28 13:40:15', 1, 4),
(3, 3, '<p>Hey guys welcome to this group, hope this group helps each other in some way or the other ok</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', '2013-02-28 13:42:14', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `info1` varchar(255) NOT NULL,
  `info2` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `bio` longtext,
  `photo` varchar(255) DEFAULT NULL,
  `type` enum('professor','student') NOT NULL,
  `college` varchar(255) NOT NULL,
  `roll_no` varchar(255) NOT NULL,
  `mobile_no` bigint(20) NOT NULL,
  `fname` text,
  `lname` text,
  `g_default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`ID`, `info1`, `info2`, `dob`, `gender`, `bio`, `photo`, `type`, `college`, `roll_no`, `mobile_no`, `fname`, `lname`, `g_default`) VALUES
(1, '', '', '2013-02-05', 'Male', 'i am the admin of this website.', 'admin.jpg', 'professor', '', '12', 7498381799, 'admin', NULL, 0),
(4, 'computer', 'vjti', '1991-03-02', 'Male', 'I Love Programming.', '1362049148ritesh.jpg', 'student', 'vjti', '101070045', 9022510258, 'ritesh', 'bajaj', 0),
(5, 'computer', 'Tybtech', '1995-01-02', 'Male', 'i am a programmer.I love hacking.', '1362050868priyank.jpg', 'student', 'vjti', '101070048', 9022510258, 'Priyank', 'Jain', 6),
(6, 'computer', 'Tybtech', '1979-08-01', 'Male', 'I am a man of my words.', '1362051722jigar.jpg', 'professor', 'vjti', '101070043', 9769122691, 'Jigar', 'Bhat', 2),
(8, 'Networking', '3', '1984-05-05', 'Male', ' I love programming in Java. Feel free to message me for help any time ;)', '1362052234rahul.jpg', 'professor', 'vjti', '-', 9022510123, 'Rahul', 'Sharma', 0),
(9, 'Computers', 'TYBtech', '1993-12-17', 'Male', 'I am Rushit Mehta', '1362164274shoppingcart.gif', 'student', 'VJTI', '101070043', 9022510231, 'Rushit', 'Mehta', 0),
(10, 'computer', 'tybtech', '1987-01-01', 'Male', 'i am the hero', '1362164490ornaments1.jpg', 'student', 'vjti', '101070032', 9022510258, 'jayesh', 'wavel', 0);

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE IF NOT EXISTS `relationships` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `usera` int(11) NOT NULL,
  `userb` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`ID`, `type`, `usera`, `userb`, `accepted`) VALUES
(4, 0, 5, 7, 1),
(5, 0, 8, 4, 1),
(6, 0, 8, 7, 0),
(7, 0, 1, 4, 1),
(8, 0, 1, 8, 0),
(9, 0, 1, 6, 1),
(15, 0, 4, 6, 1),
(16, 0, 5, 6, 0),
(17, 0, 5, 4, 0),
(20, 0, 8, 5, 1),
(21, 0, 8, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `skey` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `svalue` longtext COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`ID`, `skey`, `svalue`) VALUES
(1, 'view', 'default'),
(2, 'sitename', 'StuNet'),
(3, 'siteurl', 'http://localhost/'),
(4, 'captcha.enabled', '1'),
(5, 'cms_name', 'StuNet'),
(6, 'adminEmailAddress', 'admin@stunet'),
(7, 'uploads_path', 'uploads/');

-- --------------------------------------------------------

--
-- Table structure for table `table_check`
--

CREATE TABLE IF NOT EXISTS `table_check` (
  `exam` tinyint(1) NOT NULL DEFAULT '0',
  `class` tinyint(11) NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  KEY `fk_table_check_groups` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_check`
--

INSERT INTO `table_check` (`exam`, `class`, `group_id`) VALUES
(1, 1, 2),
(1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE IF NOT EXISTS `timetable` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `timing` text NOT NULL,
  `mon` text NOT NULL,
  `tues` text NOT NULL,
  `wed` text NOT NULL,
  `thu` text NOT NULL,
  `fri` text NOT NULL,
  `Sat` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `type` text NOT NULL,
  `empty` int(11) NOT NULL DEFAULT '0',
  `row_no` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_timetable_groups` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`ID`, `timing`, `mon`, `tues`, `wed`, `thu`, `fri`, `Sat`, `group_id`, `type`, `empty`, `row_no`) VALUES
(1, 'DATE', '01-MAR-2013', '02-MAR-2013', '03-MAR-2013', '05-MAR-2013', '06-MAR-2013', '', 2, 'exam', 0, 1),
(2, '10-11AM', 'OS', 'JAVA', 'C++', 'WEB', 'PHP', '', 2, 'exam', 0, 2),
(3, '11-12 AM', 'JNP', 'NSP', 'OS', 'OSWE', 'TT', '', 2, 'exam', 0, 3),
(4, '', '', '', '', '', '', '', 2, 'exam', 1, 4),
(5, '', '', '', '', '', '', '', 2, 'exam', 1, 5),
(6, '', '', '', '', '', '', '', 2, 'exam', 1, 6),
(7, '', '', '', '', '', '', '', 2, 'exam', 1, 7),
(8, '', '', '', '', '', '', '', 2, 'exam', 1, 8),
(9, '', '', '', '', '', '', '', 2, 'exam', 1, 9),
(10, '', '', '', '', '', '', '', 2, 'exam', 1, 10),
(11, '9-10', 'ISMS', 'NSP', 'JNP', 'OS', 'WT', '-', 2, 'class', 0, 1),
(12, '10-11', 'JNP', 'TT', 'ELIB', 'MATHS', 'STATS', 'HP', 2, 'class', 0, 2),
(13, '11-12', 'JNP', 'TT', 'ELIB', 'STATS', 'HPCA', 'STATS', 2, 'class', 0, 3),
(14, '12-1', 'TT', 'OS', 'WT', 'JNP', 'STATS', 'HP', 2, 'class', 0, 4),
(15, '', '', '', '', '', '', '', 2, 'class', 1, 5),
(16, '', '', '', '', '', '', '', 2, 'class', 1, 6),
(17, '', '', '', '', '', '', '', 2, 'class', 1, 7),
(18, '', '', '', '', '', '', '', 2, 'class', 1, 8),
(19, '', '', '', '', '', '', '', 2, 'class', 1, 9),
(20, '', '', '', '', '', '', '', 2, 'class', 1, 10),
(21, 'Date', '11MAR', '12MAR', '13MAR', '14MAR', '15MAR', '16MAR', 5, 'exam', 0, 1),
(22, '9-11', 'HPCA', 'STATS', 'NSP', 'JNP', 'OS', 'WT', 5, 'exam', 0, 2),
(23, '2-4', 'ITC', 'DSA', 'JAVA', 'C ', 'C++', '--', 5, 'exam', 0, 3),
(24, '', '', '', '', '', '', '', 5, 'exam', 1, 4),
(25, '', '', '', '', '', '', '', 5, 'exam', 1, 5),
(26, '', '', '', '', '', '', '', 5, 'exam', 1, 6),
(27, '', '', '', '', '', '', '', 5, 'exam', 1, 7),
(28, '', '', '', '', '', '', '', 5, 'exam', 1, 8),
(29, '', '', '', '', '', '', '', 5, 'exam', 1, 9),
(30, '', '', '', '', '', '', '', 5, 'exam', 1, 10),
(31, '9-10', 'lec1', 'lec2', 'lec3', 'lec4', 'lec5', '-', 5, 'class', 0, 1),
(32, '10-11', 'lec5', 'lec6', 'lec7', 'lec8', 'lec9', '', 5, 'class', 0, 2),
(33, '11-12', 'lec10', 'lec11', 'lec12', 'lec13', 'lec14', '', 5, 'class', 0, 3),
(34, '12-1', 'lec15', 'lec16', 'lec17', 'lec18', 'lec19', '', 5, 'class', 0, 4),
(35, '', '', '', '', '', '', '', 5, 'class', 1, 5),
(36, '', '', '', '', '', '', '', 5, 'class', 1, 6),
(37, '', '', '', '', '', '', '', 5, 'class', 1, 7),
(38, '', '', '', '', '', '', '', 5, 'class', 1, 8),
(39, '', '', '', '', '', '', '', 5, 'class', 1, 9),
(40, '', '', '', '', '', '', '', 5, 'class', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `timetable_note`
--

CREATE TABLE IF NOT EXISTS `timetable_note` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `note` longtext NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_timetable_note_groups` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `timetable_note`
--

INSERT INTO `timetable_note` (`ID`, `group_id`, `note`, `type`) VALUES
(1, 2, 'best of luck', 'exam'),
(2, 2, 'Subject to change', 'class'),
(3, 5, 'All the best for your exams :)\r\n\r\nEdit: Note that the timings for the second papers have been changed so that you guys have a long break :D', 'exam'),
(4, 5, 'Time table for even semester', 'class');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_topic_groups` (`groupid`),
  KEY `idx_topic` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`ID`, `name`, `creator`, `created`, `groupid`) VALUES
(1, 'Welcome to our new group', 4, '2013-02-28 13:40:00', 1),
(2, 'Welcome to our new group', 4, '2013-02-28 13:40:15', 1),
(3, 'Welcome to our new group', 4, '2013-02-28 13:42:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `reset_key` varchar(50) DEFAULT NULL,
  `reset_expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verify_email` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `password`, `active`, `banned`, `admin`, `deleted`, `reset_key`, `reset_expires`, `verify_email`) VALUES
(1, 'admin', 'admin@stunet.com', 'a4182b38098deeaa48b71ad299541d74', 1, 0, 1, 0, '', '2013-03-02 00:19:01', 'admin@stunet.com'),
(4, 'ritesh123', 'ritesh.bajaj6@gmail.com', '85c7646ca832123176d48c6f52cb3911', 1, 0, 0, 0, 'va4hn5azmbsu7miogow7', '2013-03-01 07:48:54', 'gy5qn3r4wovv7h7x8fux'),
(5, 'priyank', 'priyankjain@gmail.com', '7c94eaef2ac7c8a274b160e3a1ee1139', 1, 0, 0, 0, NULL, '2013-02-28 11:27:48', 'o12yolrybxtwpok0gzay'),
(6, 'jigar', 'jigar.bhati@gmail.com', '9f4192cf6da74fb94e8922c3dbe53374', 1, 0, 0, 0, NULL, '2013-02-28 11:42:02', 'xi1wwkxhqpcolgwfuffi'),
(8, 'rahul', 'rahulsharma@gmail.com', '205f768fd2732e30929c66bdd9bd13e0', 1, 0, 0, 0, NULL, '2013-02-28 11:50:34', '4hyfn8wbobzyopb5opwk'),
(9, 'rushit', 'rushitmehta@gmail.com', '5532ba3810221ee6e1bd5c511aed41fe', 0, 0, 0, 0, NULL, '2013-03-01 18:57:54', 'yd70p7xnm6xsoxw1y7v4'),
(10, 'jayesh', 'ritesh3@gmail.com', '69b0871587c9025d1099b32eff660569', 0, 0, 0, 0, NULL, '2013-03-01 19:01:30', 'xzvrgrukefrznf7wrsx1');

-- --------------------------------------------------------

--
-- Structure for view `chat_users`
--
DROP TABLE IF EXISTS `chat_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `chat_users` AS select `l`.`user_id` AS `user_id`,`p`.`fname` AS `fname`,`p`.`lname` AS `lname` from (`logged_in` `l` join `profile` `p`) where (`l`.`user_id` = `p`.`ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `fk_blog_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blogcomments`
--
ALTER TABLE `blogcomments`
  ADD CONSTRAINT `fk_blogcomments_blog` FOREIGN KEY (`blogid`) REFERENCES `blog` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_blogcomments_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  ADD CONSTRAINT `fk_evaluation_details_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evaluation_details_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD CONSTRAINT `fk_evaluation_results` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_details` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evaluation_results_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `fk_event_attendees_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_event_attendees_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `fk_grade_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grade_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grade_users2` FOREIGN KEY (`teacher`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `fk_groups_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logged_in`
--
ALTER TABLE `logged_in`
  ADD CONSTRAINT `fk_logged_in_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_topic` FOREIGN KEY (`topic`) REFERENCES `topic` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_profile_users` FOREIGN KEY (`ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `table_check`
--
ALTER TABLE `table_check`
  ADD CONSTRAINT `fk_table_check_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `fk_timetable_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetable_note`
--
ALTER TABLE `timetable_note`
  ADD CONSTRAINT `fk_timetable_note_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `fk_topic_groups` FOREIGN KEY (`groupid`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_topic_users` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
