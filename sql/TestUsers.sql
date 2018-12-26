SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cts`
--
USE `piclinic`;


INSERT INTO `staff` 
 (`MemberID`, `username`, `lastName`, `firstName`, `position`, `password`, `contactInfo`, `active`, `accessGranted`, `lastLogin`, `modifiedDate`, `createdDate`) 
VALUES  
 ('Test Nurse', 'Alaniz', 'Alaniz', 'Test', 'Nurse', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test Nurse', 'García', 'García', 'Test', 'Nurse', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test Nurse', 'Saavedra', 'Saavedra', 'Test', 'Nurse', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test NursesAid', 'Najera', 'Najera', 'Test', 'NursesAid', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test DoctorGeneral', 'Verduzco', 'Verduzco', 'Test', 'DoctorGeneral', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test DoctorGeneral', 'Griego', 'Griego', 'Test', 'DoctorGeneral', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test DoctorGeneral', 'Corral', 'Corral', 'Test', 'DoctorGeneral', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test DoctorGeneral', 'Agosto', 'Agosto', 'Test', 'DoctorGeneral', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test DoctorSpecialist', 'Valadez', 'Valadez', 'Test', 'DoctorSpecialist', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test MedicalStudent', 'MedicalStudent', 'MedicalStudent', 'Test', 'MedicalStudent', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test NursingStudent', 'NursingStudent', 'NursingStudent', 'Test', 'NursingStudent', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test ClinicStaff', 'ClinicStaff', 'ClinicStaff', 'Test', 'ClinicStaff', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicStaff', NULL, NOW(), NOW())
,('Test Other', 'Other', 'Other', 'Test', 'Other', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicAdmin', NULL, NOW(), NOW())
,('Test RO', 'TestRO', 'Read', 'Test', 'Other', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'ClinicReadOnly', NULL, NOW(), NOW())
;
