-- 
-- 	Copyright (c) 2018, Robert B. Watson
-- 
--	This file is part of the piClinic Console.
--
--  piClinic Console is free software: you can redistribute it and/or modify
--  it under the terms of the GNU General Public License as published by
--  the Free Software Foundation, either version 3 of the License, or
--  (at your option) any later version.
--
--  piClinic Console is distributed in the hope that it will be useful,
--  but WITHOUT ANY WARRANTY; without even the implied warranty of
--  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
--  GNU General Public License for more details.
--
--  You should have received a copy of the GNU General Public License
--  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
--	If not, see <http://www.gnu.org/licenses/>.
--
 
-- Host: localhost
-- Generation Time: Sep 01, 2017 at 02:51 PM
-- Server version: 5.5.57-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `piclinic`
--
CREATE DATABASE IF NOT EXISTS `piclinic` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `piclinic`;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `staffID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for staff records',
  `MemberID` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) staff ID used by staff member',
  `Username` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) user name ',
  `NameLast` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) staff member''s last name',
  `NameFirst` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) staff member''s first name',
  `Position` enum('Nurse','NursesAid','NursingStudent','DoctorGeneral','DoctorSpecialist','MedicalStudent','ClinicStaff','Other') COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Staff position type (e.g. Doctor, Nurse, etc.)',
  `Password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Staff''s password  (encrypted as hash. Do not enter plaintext)',
  `PrefLang` varchar(8) COLLATE utf8_unicode_ci DEFAULT 'en' COMMENT '(optional) preferred session language',
  `PrefClinicPublicID` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Clinic ID/code of this persons preferred clinic.',
  `ContactInfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) contact info such as phone or email',
  `AltContactInfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) additional contact info such as phone or email',
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '(Required) status of staff member (only Active = True can log in)',
  `AccessGranted` enum('SystemAdmin','ClinicAdmin','ClinicStaff','ClinicReadOnly') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ClinicReadOnly' COMMENT '(Required) Describes the member''s access to data and features.',
  `LastLogin` datetime DEFAULT NULL COMMENT '(Log Info) the date and time of the most recent login by this staff member. ',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Log Info) The last time data fields of this record were modified.',
  `createdDate` datetime NOT NULL COMMENT '(Log Info) The date and time this member was added to the system.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that lists the people who can access the system';

--
ALTER TABLE `staff`
 ADD UNIQUE KEY `Username` (`Username`);

--
-- ------------------------------------------------------

-- Create default ADMIN account

INSERT INTO `staff` (`MemberID`, `Username`, `NameLast`, `NameFirst`, `Position`, `Password`, `ContactInfo`, `Active`, `AccessGranted`, `LastLogin`, `modifiedDate`, `createdDate`)
  VALUES ('Default user', 'SystemAdmin', 'Admin', 'System', 'ClinicStaff', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'SystemAdmin', NULL, NOW(), NOW());

-- ------------------------------------------------------
--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique session ID for staff records',
  `Username` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Autofill) Username creating this session.',
  `Token` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Token ID',
  `LoggedIn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(Autofill) Set to TRUE while the session is valid',
  `AccessGranted` enum('SystemAdmin','ClinicAdmin','ClinicStaff','ClinicReadOnly') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ClinicReadOnly' COMMENT '(Required) Describes the member''s access to data and features.',
  `SessionIP` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) Session IP address of client ',
  `SessionUA` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Client user agent, if available',
  `SessionLang` varchar(8) COLLATE  utf8_unicode_ci DEFAULT 'en' COMMENT '(optional) Preferred UI language of this session',
  `SessionClinicPublicID` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Current clinic ID/code of this sessions preferred clinic.',
 `LoggedOutDate` datetime DEFAULT NULL COMMENT '(Autofill) Set to current time on log out',
  `ExpiresOnDate` datetime NOT NULL COMMENT '(Autofill) The date and time the session expires.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this session was created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that lists sessions that currently have access to the system.';

--
ALTER TABLE `session`
 ADD UNIQUE KEY `Token` (`Token`);
--

-- ------------------------------------------------------
--
-- Table structure for table `logger`
--

DROP TABLE IF EXISTS `logger`;
CREATE TABLE IF NOT EXISTS `logger` (
  `loggerId` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for logger records',
  `SourceModule` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Module creating the log entry.',
  `UserToken` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) sessionID of user.',
  `LogClass` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) type of log entry.',
  `LogTable` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Table being accessed.',
  `LogAction` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB action being attempted.',
  `LogQueryString` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Query string that initiated the action',
  `LogBeforeData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record before change.',
  `LogAfterData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record after change.',
  `LogStatusCode` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Status code of action.',
  `LogStatusMessage` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Optional) Text status message resulting from action.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this entry was created.'	
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that logs data and error events.';


-- ------------------------------------------------------
--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `commentID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique comment ID for comment records',
  `CommentDate` datetime DEFAULT NULL COMMENT '(Optional) date comment was started',
  `Username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Username creating this session.',
  `ReferringUrl` varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) URL of Page from which comment page was called.',
  `ReferringPage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Page element of URL from which comment page was called.',
  `ReturnUrl` varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Page to which user was sent after making the comment.',
  `CommentText`  varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) User comment text.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this commant was saved.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that records comments from users.';

-- --------------------------------------------------------
--
-- Table structure for table `clinic`
--

DROP TABLE IF EXISTS `clinic`;
CREATE TABLE IF NOT EXISTS `clinic` (
  `clinicID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for clinic records',
  `ThisClinic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(Required) Set to 1 to indicate the clinic in which the system is installed.',
  `PublicID` varchar(127) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Clinic ID/code as assigned by controlling agency.',
  `TypeCode` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Cinic Type designator',
  `CareLevel` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Cinic care level designator',
  `LongName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Require) Full, official clinic name',
  `ShortName` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Short name for clinic, if applicable',
  `Currency` varchar(16) COLLATE utf8_unicode_ci DEFAULT 'USD' COMMENT '(Not Required) Three-letter code for the currency handled in the clinic.',
  `Address1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic street address',
  `Address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic street address',
  `ClinicNeighborhood` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic neighborhood',
  `ClinicCity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic City',
  `ClinicState` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic address state',
  `ClinicRegion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic Region name',
  `ClinicDirector` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) The name of the clinics director',
  `ClinicService` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic service type: e.g. empty (routine), External, Emergency, other',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NOT NULL COMMENT '(Auto Fill) The time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Information about the clinic';

ALTER TABLE `clinic`
 ADD UNIQUE KEY `PublicID` (`PublicID`);


-- --------------------------------------------------------
--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `patientID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique patient record ID',
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '(Autofill) True indicates the record is active and valid, FALSE indicate the patient has been "deleted"',
  `ClinicPatientID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient ID issued by clinic.',
  `PatientNationalID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) National ID issued by government.',
  `FamilyID` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) Family ID (usually ID of family folder.',
  `NameLast` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient''s last name(s)',
  `NameLast2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) The patient''s second last name',
  `NameFirst` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient''s first name',
  `NameMI` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Patient''s middle initial, if known',
  `Sex` enum('M','F','X') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s sex',
  `BirthDate` datetime DEFAULT NULL COMMENT '(Not Required) Patient''s date of birth',
  `NextVaccinationDate` datetime DEFAULT NULL COMMENT '(Not Required) Date patients next vaccination is due',
  `HomeAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home address',
  `HomeAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) additional home address info (e.g. apt, room, etc.)',
  `HomeNeighborhood` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home neighborhood.',
  `HomeCity` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home city',
  `HomeCounty` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home county',
  `HomeState` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home state',
  `ContactPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s primary phone number',
  `ContactAltPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Requried) Patient''s alternate phone number',
  `BloodType` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','NA') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s blood type',
  `OrganDonor` tinyint(1) DEFAULT NULL COMMENT '(Not Required) Patient''s organ donor preference',
  `PreferredLanguage` varchar(255) DEFAULT NULL COMMENT '(Not Required) Patient''s preferred language for communications',
  `KnownAllergies` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Known Allergies stored as |-separated list',
  `CurrentMedications` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Current medications stored as |-separated list',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto Fill) The last time this record was modified',
  `createdDate` datetime NOT NULL COMMENT '(Auto Fill) the date the patient record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='information about the individual patients';

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
 ADD UNIQUE KEY `ClinicPatientID` (`ClinicPatientID`);
ALTER TABLE `patient`
 ADD UNIQUE KEY `PatientNationalID` (`PatientNationalID`);
ALTER TABLE `patient`
 ADD INDEX `FamilyID` (`FamilyID`);

 
 -- ------------------------------------------------------

--
-- Table structure for table `visit`
--
DROP TABLE IF EXISTS `visit`;
CREATE TABLE IF NOT EXISTS `visit` (
  `visitID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for visit records',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(optional) Set true to remove record from view and from reports.',
  `StaffUsername` varchar(64)  COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) user name of professional attending the patient.',
  `StaffName` varchar(255) DEFAULT NULL COMMENT '(Auto Fill) staff name (first/last)',
  `StaffPosition` enum('Nurse','NursesAid','NursingStudent','DoctorGeneral','DoctorSpecialist','MedicalStudent','ClinicStaff','Other','Unassigned') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unassigned' COMMENT '(Auto Fill) Staff position type--loaded from staff.Position field of StaffUsername.',
  `VisitType` varchar(255) NOT NULL COMMENT '(Required) Type of visit or attention recevied',
  `VisitStatus` enum('Open','Closed','Deleted') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Open' COMMENT '(Not Required) Current status of the patient visit.',
  `ComplaintPrimary` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(Not Required) summary of patient complaint or reason for visit',
  `ComplaintAdditional` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(Not Required) summary of patient complaint or reason for visit',
  `DateTimeIn` datetime DEFAULT NULL COMMENT '(Required) date/time record opened.',
  `DateTimeOut` datetime DEFAULT NULL COMMENT '(Required) Date/time visit closed',
  `Payment` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount patient paid at time of visit',
  `patientID` int(11) NOT NULL COMMENT '(Required) DB ID of patient being seen',
  `ClinicPatientID` varchar(255) NOT NULL COMMENT '(Required) Clinic ID of patient being seen',
  `FirstVisit` char(4) NOT NULL DEFAULT 'NO' COMMENT '(Required or autofill) Yes = this is the first visit record for the patient',
  `PatientNationalID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) National ID issued by government.',
  `PatientFamilyID` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) Family ID (usually ID of family folder.',
  `PatientVisitID` varchar(22) NOT NULL COMMENT '(Required) Concatenation of internal patientID + YYYYMMDD + NN where YYYYMMDD is visit date and NN is sequence',
  `PatientNameLast` varchar(255) NOT NULL COMMENT '(Required) Last name of patient being seen',
  `PatientNameFirst` varchar(255) NOT NULL COMMENT '(Required) First name of patient being seen',
  `PatientSex` enum('M','F','X') NOT NULL COMMENT '(Required) Sex of patient being seen',
  `PatientBirthDate` datetime NOT NULL COMMENT '(Required) Patient''s birthdate',
  `PatientHomeAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s home addres ',
  `PatientHomeAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s address (2)',
  `PatientHomeNeighborhood` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s neighborhood',
  `PatientHomeCity` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Patient''s home city',
  `PatientHomeCounty` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s county',
  `PatientHomeState` varchar(255) NOT NULL COMMENT '(Required) Patient home state',
  `PatientKnownAllergies` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Known Allergies stored as |-separated list',
  `PatientCurrentMedications` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Current medications stored as |-separated list',
  `Diagnosis1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `Condition1` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `Diagnosis2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `Condition2` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `Diagnosis3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `Condition3` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `ReferredTo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Indicates if patient was referred to another clinic',
  `ReferredFrom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Indicates if patient was referred from another clinic',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Autofill) date/time of last record update',
  `createdDate` datetime NOT NULL COMMENT '(Auto fill) The date/time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details of a patient''s visit to the clinic';

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
 ADD UNIQUE KEY `PatientVisitID` (`PatientVisitID`);

-- --------------------------------------------------------
--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
`ImageID` int(11) NOT NULL AUTO_INCREMENT KEY COMMENT '(Autofill) unique record ID of image resource',
  `ClinicPatientID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient ID issued by clinic.',
  `PatientVisitID` varchar(22) DEFAULT NULL COMMENT '(Required when image is associated with a visit as well as a patient) Visit ID of patient visit to which this image belongs',
  `ImagePath` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Path to image in local file system',
  `OriginalFileName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Autofill) original file name from source image',
  `ImageType` enum('ID','VisitForm','VisitPhoto','Other') COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Image use type. ID = patient face; VisitForm = a patient form; VisitPhoto = a photo taken as part of a visit.',
  `ImageDate` datetime NULL COMMENT '(NotRequired) Date/time to associate with the image (e.g. visit date)',
  `Page` int(11) DEFAULT 1 COMMENT '(Not Required) The page of an image sequence',
  `ImageCaption` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(Not Required) Short text to describe the file',
  `ImageDescription` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(Not Required) Text to describe the file',
  `MimeType` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Autofill) Mime type of image as read from POST info',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(AutoFill) date/time of the most recent update',
  `createdDate` datetime NOT NULL COMMENT '(Required) Date/time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table of images';

ALTER TABLE `image`
 ADD KEY `ClinicPatientID` (`ClinicPatientID`), ADD KEY `PatientVisitID` (`PatientVisitID`);
 
-- ------------------------------------------------------

DROP TABLE IF EXISTS `monthdays`;
CREATE TABLE IF NOT EXISTS `monthdays` (
  `monthId` int NOT NULL PRIMARY KEY,
  `days` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Days in month table used in age calculations.';

INSERT INTO `monthdays` (`monthId`, `days`) VALUES
	('1', '31'),
	('2', '28'),
	('3', '31'),
	('4', '30'),
	('5', '31'),
	('6', '30'),
	('7', '31'),
	('8', '31'),
	('9', '30'),
 	('10', '31'),
	('11', '30'),
	('12', '31');

-- --------------------------------------------------------
--
-- Table structure for table `icd10`
--

DROP TABLE IF EXISTS `icd10`;
CREATE TABLE IF NOT EXISTS `icd10` (
  `icd10ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for ICD10 records.',
  `tableSeq` int(11) NOT NULL COMMENT '(Required) sequence from ICD-10 data file.',
  `lang` char(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) 2-letter language ID',
  `Icd10Code` char(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) ICD-10 code with punctuation',
  `Icd10Index` char(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required)  ICD-10 code without punctuation',
  `HIPAA` Boolean DEFAULT 0 COMMENT '(Not Required) 1 if HIPAA code',
  `ShortDescription` varchar(255) COLLATE utf8_unicode_ci NULL COMMENT '(Required) Short description of diagnosis',
  `LongDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Long description of diagnosis',
  `useCount` bigint(20) NOT NULL DEFAULT 0 COMMENT '(Not required) Incremented when lastUsedDate is updated.',
  `lastUsedDate` datetime NULL COMMENT '(Not required) Updated when used by the app.',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NULL COMMENT '(Auto Fill) The time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ICD-10 diagnosis codes and their description.';

DROP VIEW IF EXISTS `icd10Get`;
CREATE VIEW `icd10Get` AS 
	SELECT 
		`lang` AS `Lang`, 
		`Icd10Code`, `Icd10Index`, 
		`ShortDescription`,
		`useCount` AS `UseCount`,
		`lastUsedDate` AS `LastUsedDate`		
	FROM `icd10` 
	WHERE 1;
	
	
-- --------------------------------------------------------
--  VIEWS
--

--
-- Create view to get this clinic
--
DROP VIEW IF EXISTS `thisClinicGet`;
CREATE VIEW `thisClinicGet` AS
	select * from `clinic`
	where `ThisClinic` IS TRUE;
	
--
-- Create view `patientGet`
--
DROP VIEW IF EXISTS `patientGet`;
CREATE VIEW `patientGet` AS 
	select 
		`patient`.`ClinicPatientID` AS `ClinicPatientID`,
		`patient`.`PatientNationalID` AS `PatientNationalID`,
		`patient`.`FamilyID` AS `FamilyID`,
		`patient`.`NameLast` AS `NameLast`,
		`patient`.`NameLast2` AS `NameLast2`,
		`patient`.`NameFirst` AS `NameFirst`,
		`patient`.`NameMI` AS `NameMI`,
		`patient`.`Sex` AS `Sex`,
		`patient`.`BirthDate` AS `BirthDate`,
		`patient`.`HomeAddress1` AS `HomeAddress1`,
		`patient`.`HomeAddress2` AS `HomeAddress2`,
		`patient`.`HomeNeighborhood` AS `HomeNeighborhood`,
		`patient`.`HomeCity` AS `HomeCity`,
		`patient`.`HomeCounty` AS `HomeCounty`,
		`patient`.`HomeState` AS `HomeState`,
		`patient`.`ContactPhone` AS `ContactPhone`,
		`patient`.`ContactAltPhone` AS `ContactAltPhone`,
		`patient`.`BloodType` AS `BloodType`,
		`patient`.`OrganDonor` AS `OrganDonor`,
		`patient`.`PreferredLanguage` AS `PreferredLanguage`,
		`patient`.`KnownAllergies` AS `KnownAllergies`,
		`patient`.`CurrentMedications` AS `CurrentMedications`
	from `patient` WHERE `patient`.`Active` = 1;
	
--
-- Create view `patientList`
--
DROP VIEW IF EXISTS `patientList`;
CREATE VIEW `patientList` AS 
	select 
		`patient`.`ClinicPatientID` AS `ClinicPatientID`,
		`patient`.`FamilyID` AS `FamilyID`,
		`patient`.`NameLast` AS `NameLast`,
		`patient`.`NameLast2` AS `NameLast2`,
		`patient`.`NameFirst` AS `NameFirst`,
		`patient`.`NameMI` AS `NameMI`,
		`patient`.`Sex` AS `Sex`,
		`patient`.`BirthDate` AS `BirthDate`,
		`patient`.`HomeCity` AS `HomeCity`,
		`patient`.`HomeState` AS `HomeState`
	from `patient` WHERE `patient`.`Active` = 1;
	
DROP VIEW IF EXISTS `imageGet`;
CREATE VIEW `imageGet` AS 
	SELECT 
		`image`.`ImageID` AS `ImageID`, 
		`image`.`ClinicPatientID` AS `ClinicPatientID`,
		`image`.`PatientVisitID` AS `PatientVisitID`,
		`image`.`ImagePath` AS `ImagePath`, 
		`image`.`OriginalFileName` AS `OriginalFileName`, 
		`image`.`MimeType` AS `MimeType`, 		
		`image`.`ImageType` AS `ImageType`, 
		`image`.`ImageDate` AS `ImageDate`,
		`image`.`Page` AS `Page`,
		`image`.`ImageCaption`  AS `ImageCaption`,
		`image`.`ImageDescription`  AS `ImageDescription`
	FROM `image` WHERE 1;

DROP VIEW IF EXISTS `imagePatientList`;
CREATE VIEW `imagePatientList` AS
	SELECT 
		`image`.`ClinicPatientID` AS `ClinicPatientID`,
		`image`.`PatientVisitID` AS `PatientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`PatientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitPhoto' 
		OR `image`.`ImageType` = 'VisitForm' 
	GROUP BY 
		`image`.`ClinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`ClinicPatientID` ASC, 
		`PatientVisitDate` DESC;
		
DROP VIEW IF EXISTS `imagePatientFormList`;
CREATE VIEW `imagePatientFormList` AS
	SELECT 
		`image`.`ClinicPatientID` AS `ClinicPatientID`,
		`image`.`PatientVisitID` AS `PatientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`PatientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitForm' 
	GROUP BY 
		`image`.`ClinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`ClinicPatientID` ASC, 
		`PatientVisitDate` DESC;
		
DROP VIEW IF EXISTS `imagePatientPhotoList`;
CREATE VIEW `imagePatientPhotoList` AS
	SELECT 
		`image`.`ClinicPatientID` AS `ClinicPatientID`,
		`image`.`PatientVisitID` AS `PatientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`PatientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitPhoto' 
	GROUP BY 
		`image`.`ClinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`ClinicPatientID` ASC, 
		`PatientVisitDate` DESC;

DROP VIEW IF EXISTS `staffGetByUser`;
CREATE VIEW `staffGetByUser` AS 
	SELECT  
		`MemberID`, 
		`Username`, 
		`NameLast`, 
		`NameFirst`, 
		`Position`,
		CASE WHEN (`Position` = 'ClinicStaff') OR (`Position` = 'Other') THEN 0 ELSE 1 END AS `MedicalStaff`,
		`ContactInfo`, 
		`AltContactInfo`, 
		`Active`, 
		`AccessGranted`, 
		`LastLogin`, 
		`modifiedDate`, 
		`createdDate` 
	FROM `staff`
	ORDER BY 
		`Username` ASC;

DROP VIEW IF EXISTS `staffGetByName`;
CREATE VIEW `staffGetByName` AS 
	SELECT  
		`MemberID`, 
		`Username`, 
		`NameLast`, 
		`NameFirst`, 
		`Position`, 
		CASE WHEN (`Position` = 'ClinicStaff') OR (`Position` = 'Other') THEN 0 ELSE 1 END AS `MedicalStaff`,
		`ContactInfo`, 
		`AltContactInfo`, 		
		`Active`, 
		`AccessGranted`, 
		`LastLogin`, 
		`modifiedDate`, 
		`createdDate` 
	FROM `staff`
	ORDER BY 
		`NameLast` ASC,
		`NameFirst` ASC;
		

DROP VIEW IF EXISTS `commentGet`;
CREATE VIEW `commentGet` AS 
	SELECT 
		`commentID`,
		`CommentDate`,
		`Username`,
		`ReferringUrl`,
		`ReferringPage`,
		`ReturnUrl`,
		`CommentText`,
		`createdDate`
	FROM `comment`
	ORDER BY `createdDate` DESC
;

DROP VIEW IF EXISTS visitEditGet;
CREATE VIEW visitEditGet AS 
	SELECT 
		`PatientVisitID`,
		`deleted`,
		`StaffName`,
		`StaffUsername`,
		`StaffPosition`,
		`VisitType`, 
		`VisitStatus`, 
		`ComplaintPrimary`, 
		`ComplaintAdditional`, 
		`DateTimeIn`, 
		`DateTimeOut`, 
		`Payment`,
		`ClinicPatientID`,
		`FirstVisit`,		
		`PatientNationalID`,
		`PatientFamilyID`,
		`PatientNameLast`, 
		`PatientNameFirst`, 
		`PatientSex`, 
		`PatientBirthDate`, 
		`PatientHomeAddress1`, 
		`PatientHomeAddress2`, 
		`PatientHomeNeighborhood`, 
		`PatientHomeCity`, 
		`PatientHomeCounty`, 
		`PatientHomeState`,
		`PatientKnownAllergies`,
		`PatientCurrentMedications`,
		`Diagnosis1`,
		`Condition1`,
		`Diagnosis2`,
		`Condition2`,
		`Diagnosis3`,
		`Condition3`,
		`ReferredTo`,
		`ReferredFrom`
		FROM `visit` 
		WHERE 1;
		
DROP VIEW IF EXISTS visitGet;
CREATE VIEW visitGet AS 
	SELECT 
		`PatientVisitID`, 
		`StaffName`, 
		`StaffUsername`,
		`StaffPosition`,
		`VisitType`, 
		`VisitStatus`, 
		`ComplaintPrimary`, 
		`ComplaintAdditional`, 
		`DateTimeIn`, 
		`DateTimeOut`, 
		`Payment`,
		`ClinicPatientID`,
		`FirstVisit`,		
		`PatientNationalID`,
		`PatientFamilyID`,
		`PatientNameLast`, 
		`PatientNameFirst`, 
		`PatientSex`, 
		`PatientBirthDate`, 
		`PatientHomeAddress1`, 
		`PatientHomeAddress2`, 
		`PatientHomeNeighborhood`, 
		`PatientHomeCity`, 
		`PatientHomeCounty`, 
		`PatientHomeState`,
		`PatientKnownAllergies`,
		`PatientCurrentMedications`,
		`Diagnosis1`,
		`Condition1`,
		`Diagnosis2`,
		`Condition2`,
		`Diagnosis3`,
		`Condition3`,
		`ReferredTo`,
		`ReferredFrom`
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE;

DROP VIEW IF EXISTS visitStart;
CREATE VIEW visitStart AS 
	SELECT 
		`PatientVisitID`, 
		`StaffName`,
		`StaffUsername`,
		`StaffPosition`,
		`VisitType`, 
		`VisitStatus`, 
		`FirstVisit`,		
		`ComplaintPrimary`, 
		`ComplaintAdditional`, 
		`DateTimeIn`, 
		`DateTimeOut`, 
		`Payment`,
		`ClinicPatientID`,
		`PatientNationalID`,
		`ReferredTo`,
		`ReferredFrom`		
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE;		
		
DROP VIEW IF EXISTS visitOpen;
CREATE VIEW visitOpen AS 
	SELECT 
		`PatientVisitID`, 
		`StaffName`,
		`StaffUsername`,
		`StaffPosition`,
		`VisitType`, 
		`VisitStatus`, 
		`ComplaintPrimary`, 
		`ComplaintAdditional`, 
		`DateTimeIn`, 
		`DateTimeOut`, 
		`Payment`,
		`ClinicPatientID`, 
		`FirstVisit`,		
		`PatientNationalID`,
		`PatientFamilyID`,
		`PatientNameLast`, 
		`PatientNameFirst`, 
		`PatientSex`, 
		`PatientBirthDate`, 
		`PatientHomeAddress1`, 
		`PatientHomeAddress2`, 
		`PatientHomeNeighborhood`, 
		`PatientHomeCity`, 
		`PatientHomeCounty`, 
		`PatientHomeState`, 
		`PatientKnownAllergies`,
		`PatientCurrentMedications`,
		`Diagnosis1`,
		`Condition1`,
		`Diagnosis2`,
		`Condition2`,
		`Diagnosis3`,
		`Condition3`,
		`ReferredTo`,
		`ReferredFrom`
		FROM `visit` 
		WHERE `deleted` = FALSE
			AND `VisitStatus` = 'Open'; 
		
DROP VIEW IF EXISTS visitOpenSummary;
CREATE VIEW visitOpenSummary AS
	SELECT 
		`PatientNameLast`, 
		`PatientNameFirst`, 
		`DateTimeIn`,
		`patientID`,
		`PatientVisitID`, 
		`ClinicPatientID`,
		`FirstVisit`,		
		`PatientNationalID`
	FROM `visit` 
	WHERE `deleted` = FALSE
		AND `VisitStatus`='Open';

DROP VIEW IF EXISTS visitCheck;	
CREATE VIEW visitCheck AS
	SELECT 
		`ClinicPatientID`, 
		`PatientNationalID`,
		`PatientFamilyID`,
		`PatientVisitID`,
		CONVERT(SUBSTRING(`PatientVisitID`, 21, 2), UNSIGNED INTEGER) AS `PatientVisitIndex`,
		`VisitType`, 
		`VisitStatus`, 
		`DateTimeIn`, 
		`DateTimeOut`, 
		`Payment`,
		`PatientNameLast`, 
		`PatientNameFirst`,
		`FirstVisit`
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE
		ORDER BY `ClinicPatientID` ASC, `PatientVisitID` DESC
		;

DROP VIEW IF EXISTS visitToday;
CREATE VIEW visitToday AS
	SELECT * 
		FROM visitCheck 
		WHERE DATE_FORMAT(`DateTimeIn`, '%Y%m%d') = DATE_FORMAT(NOW(), '%Y%m%d');

DROP VIEW IF EXISTS `visitPatientEditGet`;
CREATE VIEW `visitPatientEditGet` AS 
	SELECT 
		`visit`.`visitID`, 
		`visit`.`deleted`,
		`visit`.`StaffUsername`, 
		`visit`.`StaffName`,
		`visit`.`StaffPosition`,
		`visit`.`VisitType`, 
		`visit`.`VisitStatus`, 
		`visit`.`ComplaintPrimary`, 
		`visit`.`ComplaintAdditional`, 
		`visit`.`DateTimeIn`, 
		`visit`.`DateTimeOut`, 
		`visit`.`Payment`,
		`visit`.`patientID`, 
		`visit`.`ClinicPatientID`, 
		`visit`.`FirstVisit`,		
		`visit`.`PatientNationalID`,
		`visit`.`PatientFamilyID`,
		`visit`.`PatientVisitID`, 
		`visit`.`PatientNameLast` AS `NameLast`, 
		`visit`.`PatientNameFirst` AS `NameFirst`, 
		`visit`.`PatientSex` AS `Sex`, 
		`visit`.`PatientBirthDate` AS `BirthDate`, 
		`visit`.`PatientHomeAddress1`, 
		`visit`.`PatientHomeAddress2`, 
		`visit`.`PatientHomeNeighborhood`, 
		`visit`.`PatientHomeCity`, 
		`visit`.`PatientHomeCounty`, 
		`visit`.`PatientHomeState`, 
		`visit`.`PatientKnownAllergies` AS `KnownAllergies`,
		`visit`.`PatientCurrentMedications` AS `CurrentMedications`,
		`visit`.`Diagnosis1`, 
		`visit`.`Condition1`, 
		`visit`.`Diagnosis2`, 
		`visit`.`Condition2`, 
		`visit`.`Diagnosis3`, 
		`visit`.`Condition3`, 
		`visit`.`ReferredTo`, 
		`visit`.`ReferredFrom`,
		`patient`.`Active`, 
		`patient`.`ContactPhone`, 
		`patient`.`ContactAltPhone`, 
		`patient`.`BloodType`, 
		`patient`.`OrganDonor`, 
		`patient`.`PreferredLanguage`
		FROM `visit`
		JOIN `patient`  ON `patient`.`ClinicPatientID` = `visit`.`ClinicPatientID`
		WHERE 1
		;

DROP VIEW IF EXISTS `visitPatientGet`;
CREATE VIEW `visitPatientGet` AS 
	SELECT 
		`visit`.`visitID`,
		`visit`.`StaffUsername`, 
		`visit`.`StaffName`, 
		`visit`.`StaffPosition`,
		`visit`.`VisitType`, 
		`visit`.`VisitStatus`, 
		`visit`.`ComplaintPrimary`, 
		`visit`.`ComplaintAdditional`, 
		`visit`.`DateTimeIn`, 
		`visit`.`DateTimeOut`, 
		`visit`.`Payment`,
		`visit`.`patientID`, 
		`visit`.`ClinicPatientID`, 
		`visit`.`FirstVisit`,		
		`visit`.`PatientNationalID`,
		`visit`.`PatientFamilyID`,
		`visit`.`PatientVisitID`, 
		`visit`.`PatientNameLast` AS `NameLast`, 
		`visit`.`PatientNameFirst` AS `NameFirst`, 
		`visit`.`PatientSex` AS `Sex`, 
		`visit`.`PatientBirthDate` AS `BirthDate`, 
		`visit`.`PatientHomeAddress1`, 
		`visit`.`PatientHomeAddress2`, 
		`visit`.`PatientHomeNeighborhood`, 
		`visit`.`PatientHomeCity`, 
		`visit`.`PatientHomeCounty`, 
		`visit`.`PatientHomeState`, 
		`visit`.`PatientKnownAllergies` AS `KnownAllergies`,
		`visit`.`PatientCurrentMedications` AS `CurrentMedications`,
		`visit`.`Diagnosis1`, 
		`visit`.`Condition1`, 
		`visit`.`Diagnosis2`, 
		`visit`.`Condition2`, 
		`visit`.`Diagnosis3`, 
		`visit`.`Condition3`, 
		`visit`.`ReferredTo`, 
		`visit`.`ReferredFrom`,
		`patient`.`Active`, 
		`patient`.`ContactPhone`, 
		`patient`.`ContactAltPhone`, 
		`patient`.`BloodType`, 
		`patient`.`OrganDonor`, 
		`patient`.`PreferredLanguage`
		FROM `visit`
		JOIN `patient`  ON `patient`.`ClinicPatientID` = `visit`.`ClinicPatientID`
		WHERE `visit`.`deleted` = FALSE
;

