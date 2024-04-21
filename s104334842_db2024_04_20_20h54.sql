-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: ictstu-db1.cc.swin.edu.au
-- Generation Time: Apr 20, 2024 at 10:55 AM
-- Server version: 5.5.68-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s104334842_db`
--
CREATE DATABASE IF NOT EXISTS `s104334842_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `s104334842_db`;

-- --------------------------------------------------------

--
-- Table structure for table `Archer`
--

DROP TABLE IF EXISTS `Archer`;
CREATE TABLE IF NOT EXISTS `Archer` (
  `ArcherID` int(5) NOT NULL AUTO_INCREMENT,
  `ArcherName` varchar(50) NOT NULL,
  `ArcherDOB` date NOT NULL,
  PRIMARY KEY (`ArcherID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ArcherCategory`
--

DROP TABLE IF EXISTS `ArcherCategory`;
CREATE TABLE IF NOT EXISTS `ArcherCategory` (
  `ArcherCategoryID` int(5) NOT NULL AUTO_INCREMENT,
  `ArcherID` int(5) NOT NULL,
  `CategoryID` int(5) NOT NULL,
  PRIMARY KEY (`ArcherCategoryID`),
  KEY `ArcherID` (`ArcherID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Arrow`
--

DROP TABLE IF EXISTS `Arrow`;
CREATE TABLE IF NOT EXISTS `Arrow` (
  `ArrowID` int(5) NOT NULL AUTO_INCREMENT,
  `EndID` int(5) NOT NULL,
  `ArrowPoint` int(5) NOT NULL,
  PRIMARY KEY (`ArrowID`),
  KEY `EndID` (`EndID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
CREATE TABLE IF NOT EXISTS `Category` (
  `CategoryID` int(5) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(50) NOT NULL,
  `ClassID` int(5) NOT NULL,
  `DivisionID` int(5) NOT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `ClassID` (`ClassID`),
  KEY `DivisionID` (`DivisionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

DROP TABLE IF EXISTS `Class`;
CREATE TABLE IF NOT EXISTS `Class` (
  `ClassID` int(5) NOT NULL AUTO_INCREMENT,
  `ClassName` varchar(25) NOT NULL,
  PRIMARY KEY (`ClassID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DefinedRound`
--

DROP TABLE IF EXISTS `DefinedRound`;
CREATE TABLE IF NOT EXISTS `DefinedRound` (
  `DefinedRoundID` int(5) NOT NULL AUTO_INCREMENT,
  `RoundName` varchar(25) NOT NULL,
  `RangeID` int(5) NOT NULL,
  `TargetFaceID` int(5) NOT NULL,
  `PossibleScore` int(5) NOT NULL,
  PRIMARY KEY (`DefinedRoundID`),
  KEY `TargetFaceID` (`TargetFaceID`),
  KEY `RangeID` (`RangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Division`
--

DROP TABLE IF EXISTS `Division`;
CREATE TABLE IF NOT EXISTS `Division` (
  `DivisionID` int(5) NOT NULL AUTO_INCREMENT,
  `DivisionName` varchar(50) NOT NULL,
  PRIMARY KEY (`DivisionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `End`
--

DROP TABLE IF EXISTS `End`;
CREATE TABLE IF NOT EXISTS `End` (
  `EndID` int(5) NOT NULL AUTO_INCREMENT,
  `RoundID` int(5) NOT NULL,
  `TargetTypeID` int(5) NOT NULL,
  `EndOrder` int(5) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`EndID`),
  KEY `RoundID` (`RoundID`),
  KEY `TargetTypeID` (`TargetTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Ranges`
--

DROP TABLE IF EXISTS `Ranges`;
CREATE TABLE IF NOT EXISTS `Ranges` (
  `RangeID` int(5) NOT NULL AUTO_INCREMENT,
  `RangeDistance` varchar(25) NOT NULL,
  PRIMARY KEY (`RangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Round`
--

DROP TABLE IF EXISTS `Round`;
CREATE TABLE IF NOT EXISTS `Round` (
  `RoundID` int(5) NOT NULL AUTO_INCREMENT,
  `DefinedRoundID` int(5) NOT NULL,
  `ArcherCategoryID` int(5) NOT NULL,
  `Equipment` varchar(25) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`RoundID`),
  KEY `DefinedRoundID` (`DefinedRoundID`),
  KEY `ArcherCategoryID` (`ArcherCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TargetFace`
--

DROP TABLE IF EXISTS `TargetFace`;
CREATE TABLE IF NOT EXISTS `TargetFace` (
  `TargetFaceID` int(5) NOT NULL AUTO_INCREMENT,
  `TargetFace` varchar(25) NOT NULL,
  PRIMARY KEY (`TargetFaceID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TargetType`
--

DROP TABLE IF EXISTS `TargetType`;
CREATE TABLE IF NOT EXISTS `TargetType` (
  `TargetTypeID` int(5) NOT NULL AUTO_INCREMENT,
  `DefinedRoundID` int(5) NOT NULL,
  `RangeID` int(5) NOT NULL,
  `TargetFaceID` int(5) NOT NULL,
  `TargetOrder` int(5) NOT NULL,
  PRIMARY KEY (`TargetTypeID`),
  KEY `DefinedRoundID` (`DefinedRoundID`),
  KEY `TargetFaceID` (`TargetFaceID`),
  KEY `RangeID` (`RangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ArcherCategory`
--
ALTER TABLE `ArcherCategory`
  ADD CONSTRAINT `ArcherCategory_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `Category` (`CategoryID`),
  ADD CONSTRAINT `ArcherCategory_ibfk_1` FOREIGN KEY (`ArcherID`) REFERENCES `Archer` (`ArcherID`);

--
-- Constraints for table `Arrow`
--
ALTER TABLE `Arrow`
  ADD CONSTRAINT `Arrow_ibfk_1` FOREIGN KEY (`EndID`) REFERENCES `End` (`EndID`);

--
-- Constraints for table `Category`
--
ALTER TABLE `Category`
  ADD CONSTRAINT `Category_ibfk_2` FOREIGN KEY (`DivisionID`) REFERENCES `Division` (`DivisionID`),
  ADD CONSTRAINT `Category_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `Class` (`ClassID`);

--
-- Constraints for table `DefinedRound`
--
ALTER TABLE `DefinedRound`
  ADD CONSTRAINT `DefinedRound_ibfk_2` FOREIGN KEY (`RangeID`) REFERENCES `Ranges` (`RangeID`),
  ADD CONSTRAINT `DefinedRound_ibfk_1` FOREIGN KEY (`TargetFaceID`) REFERENCES `TargetFace` (`TargetFaceID`);

--
-- Constraints for table `End`
--
ALTER TABLE `End`
  ADD CONSTRAINT `End_ibfk_2` FOREIGN KEY (`TargetTypeID`) REFERENCES `TargetType` (`TargetTypeID`),
  ADD CONSTRAINT `End_ibfk_1` FOREIGN KEY (`RoundID`) REFERENCES `Round` (`RoundID`);

--
-- Constraints for table `Round`
--
ALTER TABLE `Round`
  ADD CONSTRAINT `Round_ibfk_2` FOREIGN KEY (`ArcherCategoryID`) REFERENCES `ArcherCategory` (`ArcherCategoryID`),
  ADD CONSTRAINT `Round_ibfk_1` FOREIGN KEY (`DefinedRoundID`) REFERENCES `DefinedRound` (`DefinedRoundID`);

--
-- Constraints for table `TargetType`
--
ALTER TABLE `TargetType`
  ADD CONSTRAINT `TargetType_ibfk_3` FOREIGN KEY (`RangeID`) REFERENCES `Ranges` (`RangeID`),
  ADD CONSTRAINT `TargetType_ibfk_1` FOREIGN KEY (`DefinedRoundID`) REFERENCES `DefinedRound` (`DefinedRoundID`),
  ADD CONSTRAINT `TargetType_ibfk_2` FOREIGN KEY (`TargetFaceID`) REFERENCES `TargetFace` (`TargetFaceID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
