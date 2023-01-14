-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 06, 2023 at 10:18 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Database: `automat`
--
DROP DATABASE IF EXISTS `automat`;
CREATE DATABASE IF NOT EXISTS `automat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `automat`;
-- --------------------------------------------------------
--
-- Table structure for table `artikli`
--
CREATE TABLE IF NOT EXISTS `artikli` (
  `sifra` int NOT NULL,
  `naziv` varchar(50) NOT NULL,
  `cena` int NOT NULL,
  `kolicina` int NOT NULL,
  PRIMARY KEY (`sifra`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `artikli`
--
INSERT INTO `artikli` (`sifra`, `naziv`, `cena`, `kolicina`) VALUES
(66, 'bravo sunny orange mango', 100, 10),
(65, 'pepsi limenka', 100, 10),
(64, 'coca cola limenka', 100, 10),
(63, 'fanta pomorandza limenka', 100, 10),
(62, 'fruvita jabuke visnje', 100, 10),
(61, 'fruvita narandza', 100, 10),
(59, 'schweppes bitter lemon', 100, 10),
(58, 'next joy zova', 100, 10),
(57, 'bravo sunny orange', 90, 10),
(56, 'sprite lemon', 100, 10),
(55, 'sinalco lemon', 70, 10),
(54, 'sinalco orange', 70, 10),
(53, 'sinalco cola', 70, 10),
(52, 'coca cola', 100, 10),
(51, 'coca cola', 100, 10),
(49, 'knjaz milos', 60, 10),
(48, 'knjaz milos', 60, 10),
(47, 'aqua viva', 60, 10),
(46, 'aqua viva', 60, 10),
(45, 'aqua viva', 60, 10),
(44, 'aqua viva', 60, 10),
(42, 'aqua viva', 60, 10),
(43, 'aqua viva', 60, 10),
(41, 'aqua viva', 60, 10),
(39, '7 days kakao', 70, 10),
(38, '7 days kakao', 70, 10),
(37, 'pardon stapici kikiriki', 100, 10),
(36, 'jaffa brownie choco', 90, 10),
(35, 'jaffa brownie choco', 90, 10),
(34, 'pardon stapici original', 60, 10),
(33, 'jumbo protein mix', 90, 10),
(32, 'jumbo-student-mix', 90, 10),
(31, 'gud kikiriki', 60, 10),
(29, 'jaffa brownie choco', 60, 10),
(28, 'jaffa orange choco', 60, 10),
(27, '7 days cake bar cream', 60, 10),
(26, '7 days kakao', 70, 10),
(25, 'sweeet lesnik', 60, 10),
(24, 'gud kikiriki', 60, 10),
(23, 'jaffa brownie choco', 60, 10),
(22, 'kiddy jagoda', 60, 10),
(21, 'kiddy original', 60, 10),
(19, 'krem bananica', 60, 10),
(18, 'snickers', 80, 10),
(17, 'twix', 80, 10),
(16, 'mars', 80, 10),
(15, 'orbit spearmint', 70, 10),
(14, 'gud kikiriki', 60, 10),
(13, 'eurovafel choco', 60, 10),
(12, 'eurocrem blok', 60, 10),
(11, 'choco biscuit', 60, 10),
(67, 'lemon soda limenka', 100, 10),
(68, 'ultra energy original limenka', 100, 10),
(69, 'ultra energy original limenka', 100, 10);
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;