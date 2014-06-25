-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 25, 2014 at 02:56 PM
-- Server version: 5.5.37-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_borang`
--

-- --------------------------------------------------------

--
-- Table structure for table `cerita_lokasi`
--

CREATE TABLE IF NOT EXISTS `cerita_lokasi` (
  `idmain_cerita` int(15) NOT NULL,
  `idlokasi` int(15) NOT NULL,
  PRIMARY KEY (`idmain_cerita`,`idlokasi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_title` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_url` text COLLATE utf8_unicode_ci,
  `file_dir` text COLLATE utf8_unicode_ci,
  `mime_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_desc` text COLLATE utf8_unicode_ci,
  `uploader_id` int(11) NOT NULL,
  `input_date` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`file_id`),
  FULLTEXT KEY `file_name` (`file_name`),
  FULLTEXT KEY `file_dir` (`file_dir`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `formulir`
--

CREATE TABLE IF NOT EXISTS `formulir` (
  `idformulir` int(15) NOT NULL AUTO_INCREMENT,
  `judul` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `deksirpsi` text COLLATE utf8_unicode_ci,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  PRIMARY KEY (`idformulir`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_access`
--

CREATE TABLE IF NOT EXISTS `group_access` (
  `group_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `r` int(1) NOT NULL DEFAULT '0',
  `w` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `group_access`
--

INSERT INTO `group_access` (`group_id`, `module_id`, `r`, `w`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(1, 3, 1, 1),
(1, 4, 1, 1),
(1, 5, 1, 1),
(1, 6, 1, 1),
(1, 7, 1, 1),
(1, 8, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE IF NOT EXISTS `lokasi` (
  `idlokasi` int(15) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `deskripsi` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`idlokasi`),
  KEY `DESA` (`nama`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `main_berkas`
--

CREATE TABLE IF NOT EXISTS `main_berkas` (
  `idlampiran` int(15) NOT NULL AUTO_INCREMENT,
  `idlokasi` int(15) NOT NULL,
  `idruas` int(15) NOT NULL,
  `idfile` int(15) NOT NULL,
  `tahun` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL,
  `file_title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(15) NOT NULL,
  `published` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idlampiran`),
  KEY `indeks` (`idlokasi`,`idruas`,`idfile`,`tahun`,`user_id`,`published`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `main_cerita`
--

CREATE TABLE IF NOT EXISTS `main_cerita` (
  `idmain_cerita` int(15) NOT NULL AUTO_INCREMENT,
  `idruas` int(15) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `idlokasi` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `published` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmain_cerita`,`idruas`),
  KEY `index` (`idruas`,`tahun`,`idlokasi`,`user_id`,`published`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `main_value`
--

CREATE TABLE IF NOT EXISTS `main_value` (
  `idmain_value` int(15) NOT NULL AUTO_INCREMENT,
  `idruas` int(15) NOT NULL,
  `value` double NOT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  PRIMARY KEY (`idmain_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mst_module`
--

CREATE TABLE IF NOT EXISTS `mst_module` (
  `module_id` int(3) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `module_path` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `module_name` (`module_name`,`module_path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=10 ;

--
-- Dumping data for table `mst_module`
--

INSERT INTO `mst_module` (`module_id`, `module_name`, `module_path`, `module_desc`) VALUES
(1, 'search', 'search', 'Search sosek data'),
(2, 'filter', 'filter', 'Filter sosek data'),
(3, 'add', 'add', 'Add sosek data'),
(4, 'edit', 'edit', 'Edit sosek data'),
(5, 'delete', 'delete', 'Delete sosek data'),
(6, 'administer', 'administer', 'Administer sosek'),
(7, 'berkas', 'berkas', 'Berkas file data entry'),
(8, 'publish', 'publish', 'Hak untuk mempublikasi');

-- --------------------------------------------------------

--
-- Table structure for table `ruas`
--

CREATE TABLE IF NOT EXISTS `ruas` (
  `idruas` int(15) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tipe` int(2) NOT NULL DEFAULT '0',
  `idkel_ruas` int(15) DEFAULT NULL,
  `deskripsi` text COLLATE utf8_unicode_ci,
  `help` text COLLATE utf8_unicode_ci NOT NULL,
  `urutan` int(2) NOT NULL DEFAULT '0',
  `tingkat` int(5) NOT NULL DEFAULT '0',
  `b_atas` int(11) NOT NULL,
  `b_bawah` int(11) NOT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  PRIMARY KEY (`idruas`,`nama`),
  KEY `index` (`urutan`,`tingkat`,`b_atas`,`b_bawah`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `ruas`
--

INSERT INTO `ruas` (`idruas`, `nama`, `tipe`, `idkel_ruas`, `deskripsi`, `help`, `urutan`, `tingkat`, `b_atas`, `b_bawah`, `create_date`, `update_date`) VALUES
(1, 'Standar 1', 0, 0, 'VISI, MISI, TUJUAN DAN SASARAN, SERTA STRATEGI PENCAPAIAN', '', 0, 0, 1, 18, NULL, NULL),
(2, 'Standar 2', 0, 0, 'TATA PAMONG, KEPEMIMPINAN, SISTEM PENGELOLAAN, DAN PENJAMINAN MUTU', '', 0, 0, 19, 26, NULL, NULL),
(3, 'Standar 3', 0, 0, 'MAHASISWA DAN LULUSAN', '', 0, 0, 21, 28, NULL, NULL),
(4, 'Standar 4', 0, 0, 'SUMBER DAYA MANUSIA', '', 0, 0, 23, 30, NULL, NULL),
(5, 'Standar 5', 0, 0, 'KURIKULUM, PEMBELAJARAN, DAN SUASANA AKADEMIK', '', 0, 0, 25, 36, NULL, NULL),
(6, 'Standar 6', 0, 0, 'PEMBIAYAAN, SARANA DAN PRASARANA, SERTA SISTEM INFORMASI', '', 0, 0, 31, 38, NULL, NULL),
(7, 'Standar 7', 0, 0, 'PENELITIAN, PELAYANAN/PENGABDIAN KEPADA MASYARAKAT, DAN KERJASAMA', '', 0, 0, 33, 40, NULL, NULL),
(14, '5.2.  Silabus/SAP dan buku ajar', 0, 5, '5.2.  Peninjauan silabus/SAP dan buku ajar dalam 5 tahun terakhir ', '', 0, 1, 28, 35, '2014-04-11', '2014-04-11'),
(13, '5.1  Kurikulum', 0, 5, '5.1  Kurikulum', '', 0, 1, 26, 33, '2014-04-11', '2014-04-11'),
(8, '1.1 Visi, Misi, Tujuan, dan Sasaran', 0, 1, '1.1  Jelaskan dasar penyusunan dan mekanisme penyusunan visi, misi, tujuan dan sasaran institusi perguruan tinggi, serta pihak-pihak yang dilibatkan dalam penyusunannya', 'Jelaskan mekanisme penyusunan visi, misi, tujuan dan sasaran program studi, serta pihak-pihak yang dilibatkan.', 0, 1, 2, 9, '2014-06-06', '2014-06-06'),
(9, 'Visi', 0, 8, 'Pernyataan Visi', '', 0, 2, 3, 4, '2014-06-06', '2014-06-06'),
(10, 'Misi', 0, 8, 'Pernyataan Misi', '', 0, 2, 5, 6, '2014-06-06', '2014-06-06'),
(11, 'Tujuan', 0, 8, 'Pernyataan Tujuan', '', 0, 2, 7, 8, '2014-06-06', '2014-06-06'),
(12, '1.2 Tonggak capaian (Milestone)', 0, 1, 'Pernyataan mengenai tonggak-tonggak capaian (milestone) tujuan yang dinyatakan dalam saran-saran yang merupakan target terukur dan penjelasan mengenai strategi serta tahapan pencapaiannya', '', 0, 1, 11, 18, '2014-06-10', '2014-06-10'),
(15, '1.3 Sosialisasi visi, misi, tujuan', 0, 1, 'Sosialisasi visi, misi, tujuan, sasaran dan strategi pencapaian dan penggunaannya sebagai acuan dalam penyusunan rencana kerja institusi PT', '', 0, 1, 12, 17, '2014-06-10', '2014-06-10'),
(16, '1.3.1 Uraikan sosialisasi', 0, 15, 'Uraikan sosialisasi visi, misi, tujuan dan sasaran PT agar dipahami seluruh pemangku kepentingan (sivitas akademika, pengguna lulusan dan masyarakat)', '', 0, 2, 13, 14, '2014-06-10', '2014-06-10'),
(17, '1.3.2 Visi, misi, tujuan sbg acuan renstra', 0, 15, 'Jelaskan bahwa visi, misi, tujuan dan sasaran PT serta strategi pencapaiannya untuk dijadikan sebagai acuan semua unit dalam institusi perguruan tinggi dalam menyusun rencana strategis (renstra) dan/atau rencana kerja unit bersangkutan', '', 0, 2, 15, 16, '2014-06-10', '2014-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `ruas_empty`
--

CREATE TABLE IF NOT EXISTS `ruas_empty` (
  `idruas` int(15) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tipe` int(2) NOT NULL DEFAULT '0',
  `idkel_ruas` int(15) DEFAULT NULL,
  `deskripsi` text COLLATE utf8_unicode_ci,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  PRIMARY KEY (`idruas`,`nama`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ruas_formulir`
--

CREATE TABLE IF NOT EXISTS `ruas_formulir` (
  `idruas` int(15) NOT NULL,
  `idformulir` int(15) NOT NULL,
  PRIMARY KEY (`idruas`,`idformulir`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruas_ruas`
--

CREATE TABLE IF NOT EXISTS `ruas_ruas` (
  `idruas` int(15) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tipe` int(2) NOT NULL DEFAULT '0',
  `idkel_ruas` int(15) DEFAULT NULL,
  `deskripsi` text COLLATE utf8_unicode_ci,
  `create_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  PRIMARY KEY (`idruas`,`nama`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=74 ;

--
-- Dumping data for table `ruas_ruas`
--

INSERT INTO `ruas_ruas` (`idruas`, `nama`, `tipe`, `idkel_ruas`, `deskripsi`, `create_date`, `update_date`) VALUES
(1, 'Standar 1', 0, 0, 'VISI, MISI, TUJUAN DAN SASARAN, SERTA STRATEGI PENCAPAIAN', NULL, NULL),
(2, 'Standar 2', 0, 0, 'TATA PAMONG, KEPEMIMPINAN, SISTEM PENGELOLAAN, DAN PENJAMINAN MUTU', NULL, NULL),
(3, 'Standar 3', 0, 0, 'MAHASISWA DAN LULUSAN', NULL, NULL),
(4, 'Standar 4', 0, 0, 'SUMBER DAYA MANUSIA', NULL, NULL),
(5, 'Standar 5', 0, 0, 'KURIKULUM, PEMBELAJARAN, DAN SUASANA AKADEMIK', NULL, NULL),
(6, 'Standar 6', 0, 0, 'PEMBIAYAAN, SARANA DAN PRASARANA, SERTA SISTEM INFORMASI', NULL, NULL),
(7, 'Standar 7', 0, 0, 'PENELITIAN, PELAYANAN/PENGABDIAN KEPADA MASYARAKAT, DAN KERJASAMA', NULL, NULL),
(73, '5.2.  Silabus/SAP dan buku ajar', 0, 5, '5.2.  Peninjauan silabus/SAP dan buku ajar dalam 5 tahun terakhir ', '2014-04-11', '2014-04-11'),
(72, '5.1  Kurikulum', 0, 5, '5.1  Kurikulum', '2014-04-11', '2014-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE IF NOT EXISTS `system_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` enum('staff','member','system') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'staff',
  `id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_location` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `log_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `log_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_type` (`log_type`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=257 ;

--
-- Dumping data for table `system_log`
--
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `realname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groups` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `input_date` date DEFAULT '0000-00-00',
  `last_update` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `realname` (`realname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `realname`, `passwd`, `last_login`, `last_login_ip`, `groups`, `input_date`, `last_update`) VALUES
(1, 'admin', 'Administrator', '21232f297a57a5a743894a0e4a801fc3', '2014-06-25 13:36:59', '127.0.0.1', 'a:1:{i:0;s:1:"1";}', '2013-05-07', '2013-05-07'),
(2, 'yono', 'Wynerst', '21232f297a57a5a743894a0e4a801fc3', '2013-09-19 13:39:02', '127.0.0.1', 'a:1:{i:0;s:1:"2";}', '0000-00-00', '2013-07-15'),
(3, 'Guest', 'Guest', '21232f297a57a5a743894a0e4a801fc3', '2013-09-19 13:39:51', '127.0.0.1', 'a:1:{i:0;s:1:"3";}', '0000-00-00', NULL),
(5, 'latihan', 'Latihan operator', '81dc9bdb52d04dc20036dbd8313ed055', '2013-07-16 14:02:16', '127.0.0.1', 'a:1:{i:0;s:1:"4";}', '2013-07-16', '2013-07-16');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `input_date` date DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`group_id`, `group_name`, `input_date`, `last_update`) VALUES
(1, 'Administrator', '2013-05-07', '2013-05-07'),
(2, 'CommDevAssessor', '2013-06-04', '2013-06-04'),
(3, 'Guest', '2013-06-04', '2013-06-04'),
(4, 'Pengawas', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `value_lokasi`
--

CREATE TABLE IF NOT EXISTS `value_lokasi` (
  `main_value_idmain_value` int(15) NOT NULL,
  `lokasi_idlokasi` int(15) NOT NULL,
  PRIMARY KEY (`main_value_idmain_value`,`lokasi_idlokasi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
