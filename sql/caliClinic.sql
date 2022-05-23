-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2019 at 02:57 PM
-- Server version: 10.1.38-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `piclinic`
--
USE `piclinic`;

--
-- UniValle clinic data
--
INSERT INTO `clinic` (`thisClinic`, `publicID`, `typeCode`, `careLevel`, `longName`, `shortName`, `currency`, `address1`, `address2`, `clinicNeighborhood`, `clinicCity`, `clinicState`, `clinicRegion`, `clinicDirector`, `clinicService`, `modifiedDate`, `createdDate`) VALUES(1, '0001', 'UAPS', 'Primerio', 'UniValle', 'UniValle', 'COP', NULL, NULL, 'Cali', 'Cali', 'Valle de Cauca', '15', 'Dra. ', 'Outpatient', NOW(), NOW());
