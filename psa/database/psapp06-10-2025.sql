-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 01:27 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `psapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cred`
--

CREATE TABLE `tbl_cred` (
  `cid` int(11) NOT NULL,
  `sname` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_cred`
--

INSERT INTO `tbl_cred` (`cid`, `sname`, `username`, `password`, `email`) VALUES
(1, 'www.instagram.com', '269 1668 1282 918 1282 542 1181 142 142 142', '269 1668 1282 918 1282 542 312 1426 949 949 1426', 'khaderabdul200402@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_document`
--

CREATE TABLE `tbl_document` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_document`
--

INSERT INTO `tbl_document` (`id`, `email`, `path`) VALUES
(1, 'khaderabdul200402@gmail.com', '918 181 111 239 38 1694 268 1265 704 1365 879 415 1265 542 1282 111 1265 1181 846 918 1761');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE `tbl_image` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_image`
--

INSERT INTO `tbl_image` (`id`, `email`, `path`) VALUES
(1, 'khaderabdul200402@gmail.com', '239 846 1752 181 1282 918 415 704 1313 111 542 1694 1694 268 415 1668 181 1265 911 1270 961 1681 1181 846 268 1519');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `usertype` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`email`, `password`, `usertype`) VALUES
('admin@gmail.com', 'admin123', 0),
('khaderabdul200402@gmail.com', 'khadar123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lsession`
--

CREATE TABLE `tbl_lsession` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `datet` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_lsession`
--

INSERT INTO `tbl_lsession` (`id`, `email`, `datet`) VALUES
(1, 'khaderabdul200402@gmail.com', '2025-10-06 04:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_master`
--

CREATE TABLE `tbl_master` (
  `mid` int(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mpassword` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_master`
--

INSERT INTO `tbl_master` (`mid`, `email`, `mpassword`, `salt`) VALUES
(1, 'khaderabdul200402@gmail.com', 'ac44077ce1e1acf78f4e830f630a8ece3a39bb121efa77b75059bb78b06608457ea8769b1897583468273e9ec4919d962903ca0dc26e91163f970ab961d0b6cd', '2caa1dff7b0da346db0c5b6ee680c32c67c903ffde8980a0b5adc75ba60be7e4');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message`
--

CREATE TABLE `tbl_message` (
  `mid` int(10) NOT NULL,
  `mobile` bigint(10) NOT NULL,
  `content` varchar(250) NOT NULL,
  `datet` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_message`
--

INSERT INTO `tbl_message` (`mid`, `mobile`, `content`, `datet`) VALUES
(1, 8589933092, 'It has been 3 months your password has been changed so please update it as soon as possible', '2025-10-06 04:53:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_register`
--

CREATE TABLE `tbl_register` (
  `uid` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_register`
--

INSERT INTO `tbl_register` (`uid`, `name`, `email`, `mobile`) VALUES
(1, 'ABDUL KHADAR', 'khaderabdul200402@gmail.com', 8589933092);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_video`
--

CREATE TABLE `tbl_video` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_video`
--

INSERT INTO `tbl_video` (`id`, `email`, `path`) VALUES
(1, 'khaderabdul200402@gmail.com', '8 1624 918 1694 181 704 689 1668 1282 1265 415 1365 846 846 911 1548 1624 918 1694 181 911 1426 949 1426 762 1630 949 961 1630 1426 1438 911 1282 1265 911 651 1226 1181 1226 1226 1181 1226 1438 142 111 879 111 1426 651 1282 1282 1426 1181 38 846 56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cred`
--
ALTER TABLE `tbl_cred`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tbl_document`
--
ALTER TABLE `tbl_document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `tbl_lsession`
--
ALTER TABLE `tbl_lsession`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_master`
--
ALTER TABLE `tbl_master`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `tbl_message`
--
ALTER TABLE `tbl_message`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `tbl_register`
--
ALTER TABLE `tbl_register`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `tbl_video`
--
ALTER TABLE `tbl_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cred`
--
ALTER TABLE `tbl_cred`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_document`
--
ALTER TABLE `tbl_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_lsession`
--
ALTER TABLE `tbl_lsession`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_master`
--
ALTER TABLE `tbl_master`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_register`
--
ALTER TABLE `tbl_register`
  MODIFY `uid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_video`
--
ALTER TABLE `tbl_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
