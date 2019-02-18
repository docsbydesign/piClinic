-- 
-- 	Copyright (c) 2019, Robert B. Watson
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
--  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
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
  `memberID` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) staff ID used by staff member',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) user name ',
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) staff member''s last name',
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) staff member''s first name',
  `position` enum('Nurse','NursesAid','NursingStudent','DoctorGeneral','DoctorSpecialist','MedicalStudent','ClinicStaff','Other') COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Staff position type (e.g. Doctor, Nurse, etc.)',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Staff''s password  (encrypted as hash. Do not enter plaintext)',
  `preferredLanguage` enum('en','es','ui') COLLATE utf8_unicode_ci DEFAULT 'en' COMMENT '(optional) preferred session language',
  `preferredClinicPublicID` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Clinic ID/code of this persons preferred clinic.',
  `contactInfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) contact info such as phone or email',
  `altContactInfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) additional contact info such as phone or email',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '(Required) status of staff member (only Active = True can log in)',
  `accessGranted` enum('SystemAdmin','ClinicAdmin','ClinicStaff','ClinicReadOnly') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ClinicReadOnly' COMMENT '(Required) Describes the member''s access to data and features.',
  `lastLogin` datetime DEFAULT NULL COMMENT '(Log Info) the date and time of the most recent login by this staff member. ',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Log Info) The last time data fields of this record were modified.',
  `createdDate` datetime NOT NULL COMMENT '(Log Info) The date and time this member was added to the system.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that lists the people who can access the system';

--
ALTER TABLE `staff`
 ADD UNIQUE KEY `username` (`username`);

--
-- ------------------------------------------------------

-- Create default ADMIN account

INSERT INTO `staff` (`memberID`, `username`, `lastName`, `firstName`, `position`, `password`, `contactInfo`, `active`, `accessGranted`, `lastLogin`, `modifiedDate`, `createdDate`)
  VALUES ('Default user', 'SystemAdmin', 'Admin', 'System', 'ClinicStaff', '$2y$12$XgB7Mo4j7TqLd3sKLpva1OP/pXljsa58U3rIRtuLPOrcxmKOiyDsG', NULL, 1, 'SystemAdmin', NULL, NOW(), NOW());

-- ------------------------------------------------------
--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique session ID for staff records',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Autofill) Username creating this session.',
  `token` char(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Token ID',
  `loggedIn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(Autofill) Set to TRUE while the session is valid',
  `accessGranted` enum('SystemAdmin','ClinicAdmin','ClinicStaff','ClinicReadOnly') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ClinicReadOnly' COMMENT '(Required) Describes the member''s access to data and features.',
  `sessionIP` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) Session IP address of client ',
  `sessionUA` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Client user agent, if available',
  `sessionLanguage` enum('en','es','ui') COLLATE  utf8_unicode_ci DEFAULT 'en' COMMENT '(optional) Preferred UI language of this session',
  `sessionClinicPublicID` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Current clinic ID/code of this sessions preferred clinic.',
  `loggedOutDate` datetime DEFAULT NULL COMMENT '(Autofill) Set to current time on log out',
  `expiresOnDate` datetime NOT NULL COMMENT '(Autofill) The date and time the session expires.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this session was created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that lists sessions that currently have access to the system.';

--
ALTER TABLE `session`
 ADD UNIQUE KEY `token` (`token`);
--

-- ------------------------------------------------------
--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `logId` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for log records',
  `sourceModule` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Module creating the log entry.',
  `userToken` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) sessionID of user.',
  `logClass` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) type of log entry.',
  `logTable` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Table being accessed.',
  `logAction` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB action being attempted.',
  `logQueryString` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Query string that initiated the action',
  `logBeforeData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record before change.',
  `logAfterData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record after change.',
  `logStatusCode` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Status code of action.',
  `logStatusMessage` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Optional) Text status message resulting from action.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this entry was created.'	
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that logs data and error events.';


-- ------------------------------------------------------
--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `commentID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique comment ID for comment records',
  `commentDate` datetime DEFAULT NULL COMMENT '(Optional) date comment was started',
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Username creating this session.',
  `referringUrl` varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) URL of Page from which comment page was called.',
  `referringPage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Page element of URL from which comment page was called.',
  `returnUrl` varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Page to which user was sent after making the comment.',
  `commentText`  varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) User comment text.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this commant was saved.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that records comments from users.';

-- --------------------------------------------------------
--
-- Table structure for table `clinic`
--

DROP TABLE IF EXISTS `clinic`;
CREATE TABLE IF NOT EXISTS `clinic` (
  `clinicID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for clinic records',
  `thisClinic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(Required) Set to 1 to indicate the clinic in which the system is installed.',
  `publicID` varchar(127) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Clinic ID/code as assigned by controlling agency.',
  `typeCode` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Cinic Type designator',
  `careLevel` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Cinic care level designator',
  `longName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Require) Full, official clinic name',
  `shortName` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Short name for clinic, if applicable',
  `currency` varchar(16) COLLATE utf8_unicode_ci DEFAULT 'USD' COMMENT '(Not Required) Three-letter code for the currency handled in the clinic.',
  `address1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic street address',
  `address2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic street address',
  `clinicNeighborhood` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic neighborhood',
  `clinicCity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Clinic City',
  `clinicState` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic address state',
  `clinicRegion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic Region name',
  `clinicDirector` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) The name of the clinics director',
  `clinicService` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Clinic service type: e.g. empty (routine), External, Emergency, other',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NOT NULL COMMENT '(Auto Fill) The time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Information about the clinic';

ALTER TABLE `clinic`
 ADD UNIQUE KEY `publicID` (`publicID`);


-- --------------------------------------------------------
--
-- Table structure for table `textmsg`
--

DROP TABLE IF EXISTS `textmsg`;
CREATE TABLE IF NOT EXISTS `textmsg` (
  `textmsgID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for text messages',
  `textmsgGUID` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) locally assigned message ID',
  `patientID` int(11) DEFAULT NULL  COMMENT '(optional) patientID of patient receiving the message',
  `messageText` varchar(1023) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) Text of message to send',
  `destNumber` varchar(1023) COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) Destination number or address of message recipient',
  `sendDateTime` datetime NOT NULL COMMENT '(required) Date/time of first message send attempt',
  `sendService`  enum('LocalMobile') COLLATE utf8_unicode_ci DEFAULT 'LocalMobile' COMMENT 'message service to use for sending message',
  `maxSendAttempts` int(11) NOT NULL DEFAULT 2 COMMENT '(optional) Number of times to try and send the message',
  `retryInterval` int(11) NOT NULL DEFAULT 300 COMMENT '(optional) Number of seconds between send attempts',
  `nextSendDateTime` datetime DEFAULT NULL COMMENT '(internal) Date/time of next message send attempt',
  `lastSendAttempt` int(11) NOT NULL DEFAULT 0 COMMENT '(internal) Last send attempt index (1 = first attempt)',
  `lastSendAttemptTime` datetime DEFAULT NULL COMMENT '(internal) The time the last message was sent',
  `lastSendStatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(internal) The status of the last message attempt',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NOT NULL COMMENT '(Auto Fill) The time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Information about text messages';

--
-- Indexes for table `patient`
--
ALTER TABLE `textmsg`
 ADD INDEX `destNumber` (`destNumber`);
ALTER TABLE `textmsg`
 ADD INDEX `nextSendDateTime` (`nextSendDateTime`);
ALTER TABLE `textmsg`
 ADD UNIQUE KEY `textmsgGUID` (`textmsgGUID`);
 
-- --------------------------------------------------------
--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `patientID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique patient record ID',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '(Autofill) True indicates the record is active and valid, FALSE indicate the patient has been "deleted"',
  `clinicPatientID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient ID issued by clinic.',
  `patientNationalID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) National ID issued by government.',
  `familyID` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) Family ID (usually ID of family folder.',
  `lastName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient''s last name(s)',
  `lastName2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) The patient''s second last name',
  `firstName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient''s first name',
  `middleInitial` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Required) Patient''s middle initial, if known',
  `sex` enum('M','F','X') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s sex',
  `birthDate` datetime DEFAULT NULL COMMENT '(Not Required) Patient''s date of birth',
  `nextVaccinationDate` datetime DEFAULT NULL COMMENT '(Not Required) Date patients next vaccination is due',
  `homeAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home address',
  `homeAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) additional home address info (e.g. apt, room, etc.)',
  `homeNeighborhood` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home neighborhood.',
  `homeCity` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home city',
  `homeCounty` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home county',
  `homeState` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s home state',
  `contactPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s primary phone number',
  `contactAltPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Requried) Patient''s alternate phone number',
  `bloodType` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','NA') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s blood type',
  `organDonor` tinyint(1) DEFAULT NULL COMMENT '(Not Required) Patient''s organ donor preference',
  `preferredLanguage` varchar(255) DEFAULT NULL COMMENT '(Not Required) Patient''s preferred language for communications',
  `knownAllergies` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Known Allergies stored as |-separated list',
  `currentMedications` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Current medications stored as |-separated list',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto Fill) The last time this record was modified',
  `createdDate` datetime NOT NULL COMMENT '(Auto Fill) the date the patient record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='information about the individual patients';

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
 ADD UNIQUE KEY `clinicPatientID` (`clinicPatientID`);
ALTER TABLE `patient`
 ADD UNIQUE KEY `patientNationalID` (`patientNationalID`);
ALTER TABLE `patient`
 ADD INDEX `familyID` (`familyID`);

 
 -- ------------------------------------------------------

--
-- Table structure for table `visit`
--
DROP TABLE IF EXISTS `visit`;
CREATE TABLE IF NOT EXISTS `visit` (
  `visitID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for visit records',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '(optional) Set true to remove record from view and from reports.',
  `staffUsername` varchar(64)  COLLATE utf8_unicode_ci NOT NULL COMMENT '(required) user name of professional attending the patient.',
  `staffName` varchar(255) DEFAULT NULL COMMENT '(Auto Fill) staff name (first/last)',
  `staffPosition` enum('Nurse','NursesAid','NursingStudent','DoctorGeneral','DoctorSpecialist','MedicalStudent','ClinicStaff','Other','Unassigned') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unassigned' COMMENT '(Auto Fill) Staff position type--loaded from staff.Position field of staffUsername.',
  `visitType` varchar(255) NOT NULL COMMENT '(Required) Type of visit or attention recevied',
  `visitStatus` enum('Open','Closed','Deleted') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Open' COMMENT '(Not Required) Current status of the patient visit.',
  `primaryComplaint` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(Not Required) summary of patient complaint or reason for visit',
  `secondaryComplaint` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(Not Required) summary of patient complaint or reason for visit',
  `dateTimeIn` datetime DEFAULT NULL COMMENT '(Required) date/time record opened.',
  `dateTimeOut` datetime DEFAULT NULL COMMENT '(Required) Date/time visit closed',
  `payment` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount patient paid at time of visit',
  `patientID` int(11) NOT NULL COMMENT '(Required) DB ID of patient being seen',
  `clinicPatientID` varchar(255) NOT NULL COMMENT '(Required) Clinic ID of patient being seen',
  `firstVisit` char(4) NOT NULL DEFAULT 'NO' COMMENT '(Required or autofill) Yes = this is the first visit record for the patient',
  `patientNationalID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) National ID issued by government.',
  `patientFamilyID` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '(optional) Family ID (usually ID of family folder.',
  `patientVisitID` varchar(22) NOT NULL COMMENT '(Required) Concatenation of internal patientID + YYYYMMDD + NN where YYYYMMDD is visit date and NN is sequence',
  `patientLastName` varchar(255) NOT NULL COMMENT '(Required) Last name of patient being seen',
  `patientFirstName` varchar(255) NOT NULL COMMENT '(Required) First name of patient being seen',
  `patientSex` enum('M','F','X') NOT NULL COMMENT '(Required) Sex of patient being seen',
  `patientBirthDate` datetime NOT NULL COMMENT '(Required) Patient''s birthdate',
  `patientHomeAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s home addres ',
  `patientHomeAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Patient''s address (2)',
  `patientHomeNeighborhood` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s neighborhood',
  `patientHomeCity` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Patient''s home city',
  `patientHomeCounty` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) patient''s county',
  `patientHomeState` varchar(255) NOT NULL COMMENT '(Required) Patient home state',
  `patientKnownAllergies` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Known Allergies stored as |-separated list',
  `patientCurrentMedications` varchar(4095) DEFAULT NULL COMMENT '(Not Required) Current medications stored as |-separated list',
  `patientNextVaccinationDate` datetime DEFAULT NULL COMMENT '(Not Required) Date patients next vaccination is due',
  `diagnosis1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `condition1` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `diagnosis2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `condition2` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `diagnosis3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis summary',
  `condition3` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT  NULL COMMENT '(Required) Condition/Activity/Diagnosis code',
  `referredTo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Indicates if patient was referred to another clinic',
  `referredFrom` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Indicates if patient was referred from another clinic',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Autofill) date/time of last record update',
  `createdDate` datetime NOT NULL COMMENT '(Auto fill) The date/time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details of a patient''s visit to the clinic';

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
 ADD UNIQUE KEY `patientVisitID` (`patientVisitID`);

-- --------------------------------------------------------
--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
`ImageID` int(11) NOT NULL AUTO_INCREMENT KEY COMMENT '(Autofill) unique record ID of image resource',
  `clinicPatientID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) Patient ID issued by clinic.',
  `patientVisitID` varchar(22) DEFAULT NULL COMMENT '(Required when image is associated with a visit as well as a patient) Visit ID of patient visit to which this image belongs',
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
 ADD KEY `clinicPatientID` (`clinicPatientID`), ADD KEY `patientVisitID` (`patientVisitID`);
 
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
	where `thisClinic` IS TRUE;
	
--
-- Create view `patientGet`
--
DROP VIEW IF EXISTS `patientGet`;
CREATE VIEW `patientGet` AS 
	select 
		`patient`.`clinicPatientID` AS `clinicPatientID`,
		`patient`.`patientNationalID` AS `patientNationalID`,
		`patient`.`familyID` AS `familyID`,
		`patient`.`lastName` AS `lastName`,
		`patient`.`lastName2` AS `lastName2`,
		`patient`.`firstName` AS `firstName`,
		`patient`.`middleInitial` AS `middleInitial`,
		`patient`.`sex` AS `sex`,
		`patient`.`birthDate` AS `birthDate`,
		`patient`.`homeAddress1` AS `homeAddress1`,
		`patient`.`homeAddress2` AS `homeAddress2`,
		`patient`.`homeNeighborhood` AS `homeNeighborhood`,
		`patient`.`homeCity` AS `homeCity`,
		`patient`.`homeCounty` AS `homeCounty`,
		`patient`.`homeState` AS `homeState`,
		`patient`.`contactPhone` AS `contactPhone`,
		`patient`.`contactAltPhone` AS `contactAltPhone`,
		`patient`.`bloodType` AS `bloodType`,
		`patient`.`organDonor` AS `organDonor`,
		`patient`.`preferredLanguage` AS `preferredLanguage`,
		`patient`.`knownAllergies` AS `knownAllergies`,
		`patient`.`currentMedications` AS `currentMedications`
	from `patient` WHERE `patient`.`active` = 1;
	
--
-- Create view `patientList`
--
DROP VIEW IF EXISTS `patientList`;
CREATE VIEW `patientList` AS 
	select 
		`patient`.`clinicPatientID` AS `clinicPatientID`,
		`patient`.`familyID` AS `familyID`,
		`patient`.`lastName` AS `lastName`,
		`patient`.`lastName2` AS `lastName2`,
		`patient`.`firstName` AS `firstName`,
		`patient`.`middleInitial` AS `middleInitial`,
		`patient`.`sex` AS `sex`,
		`patient`.`birthDate` AS `birthDate`,
		`patient`.`homeCity` AS `homeCity`,
		`patient`.`homeState` AS `homeState`
		from `patient` WHERE `patient`.`active` = 1;
	
DROP VIEW IF EXISTS `imageGet`;
CREATE VIEW `imageGet` AS 
	SELECT 
		`image`.`ImageID` AS `ImageID`, 
		`image`.`clinicPatientID` AS `clinicPatientID`,
		`image`.`patientVisitID` AS `patientVisitID`,
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
		`image`.`clinicPatientID` AS `clinicPatientID`,
		`image`.`patientVisitID` AS `patientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`patientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitPhoto' 
		OR `image`.`ImageType` = 'VisitForm' 
	GROUP BY 
		`image`.`clinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`clinicPatientID` ASC, 
		`PatientVisitDate` DESC;
		
DROP VIEW IF EXISTS `imagePatientFormList`;
CREATE VIEW `imagePatientFormList` AS
	SELECT 
		`image`.`clinicPatientID` AS `clinicPatientID`,
		`image`.`patientVisitID` AS `patientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`patientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitForm' 
	GROUP BY 
		`image`.`clinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`clinicPatientID` ASC, 
		`PatientVisitDate` DESC;
		
DROP VIEW IF EXISTS `imagePatientPhotoList`;
CREATE VIEW `imagePatientPhotoList` AS
	SELECT 
		`image`.`clinicPatientID` AS `clinicPatientID`,
		`image`.`patientVisitID` AS `patientVisitID`,
		STR_TO_DATE(SUBSTRING(`image`.`patientVisitID`,1,10), '%Y-%m-%d') AS `PatientVisitDate`,
		count(*) as `ImageCount` 
	FROM `image` 
	WHERE 
		`image`.`ImageType` = 'VisitPhoto' 
	GROUP BY 
		`image`.`clinicPatientID`, 
		`PatientVisitDate` 
	ORDER BY 
		`image`.`clinicPatientID` ASC, 
		`PatientVisitDate` DESC;

DROP VIEW IF EXISTS `staffGetByUser`;
CREATE VIEW `staffGetByUser` AS 
	SELECT  
		`memberID`, 
		`username`, 
		`lastName`, 
		`firstName`, 
		`position`,
		CASE WHEN (`position` = 'ClinicStaff') OR (`position` = 'Other') THEN 0 ELSE 1 END AS `medicalStaff`,
		`preferredLanguage`,
		`preferredClinicPublicID`,
		`contactInfo`, 
		`altContactInfo`, 
		`active`, 
		`accessGranted`, 
		`lastLogin`, 
		`modifiedDate`, 
		`createdDate` 
	FROM `staff`
	ORDER BY 
		`username` ASC;

DROP VIEW IF EXISTS `staffGetByName`;
CREATE VIEW `staffGetByName` AS 
	SELECT  
		`memberID`, 
		`username`, 
		`lastName`, 
		`firstName`, 
		`position`,
		CASE WHEN (`position` = 'ClinicStaff') OR (`position` = 'Other') THEN 0 ELSE 1 END AS `medicalStaff`,
		`preferredLanguage`,
		`preferredClinicPublicID`,
		`contactInfo`, 
		`altContactInfo`, 
		`active`, 
		`accessGranted`, 
		`lastLogin`, 
		`modifiedDate`, 
		`createdDate` 
	FROM `staff`
	ORDER BY 
		`lastName` ASC,
		`firstName` ASC;
		

DROP VIEW IF EXISTS `commentGet`;
CREATE VIEW `commentGet` AS 
	SELECT 
		`commentID`,
		`commentDate`,
		`username`,
		`referringUrl`,
		`referringPage`,
		`returnUrl`,
		`commentText`,
		`createdDate`
	FROM `comment`
	ORDER BY `createdDate` DESC
;

DROP VIEW IF EXISTS visitEditGet;
CREATE VIEW visitEditGet AS 
	SELECT 
		`patientVisitID`,
		`deleted`,
		`staffName`,
		`staffUsername`,
		`staffPosition`,
		`visitType`, 
		`visitStatus`, 
		`primaryComplaint`, 
		`secondaryComplaint`, 
		`dateTimeIn`, 
		`dateTimeOut`, 
		`payment`,
		`clinicPatientID`,
		`firstVisit`,		
		`patientNationalID`,
		`patientFamilyID`,
		`patientLastName`, 
		`patientFirstName`, 
		`patientSex`, 
		`patientBirthDate`, 
		`patientHomeAddress1`, 
		`patientHomeAddress2`, 
		`patientHomeNeighborhood`, 
		`patientHomeCity`, 
		`patientHomeCounty`, 
		`patientHomeState`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`diagnosis1`,
		`condition1`,
		`diagnosis2`,
		`condition2`,
		`diagnosis3`,
		`condition3`,
		`referredTo`,
		`referredFrom`
		FROM `visit` 
		WHERE 1;
		
DROP VIEW IF EXISTS visitGet;
CREATE VIEW visitGet AS 
	SELECT 
		`patientVisitID`, 
		`staffName`, 
		`staffUsername`,
		`staffPosition`,
		`visitType`, 
		`visitStatus`, 
		`primaryComplaint`, 
		`secondaryComplaint`, 
		`dateTimeIn`, 
		`dateTimeOut`, 
		`payment`,
		`clinicPatientID`,
		`firstVisit`,		
		`patientNationalID`,
		`patientFamilyID`,
		`patientLastName`, 
		`patientFirstName`, 
		`patientSex`, 
		`patientBirthDate`, 
		`patientHomeAddress1`, 
		`patientHomeAddress2`, 
		`patientHomeNeighborhood`, 
		`patientHomeCity`, 
		`patientHomeCounty`, 
		`patientHomeState`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`diagnosis1`,
		`condition1`,
		`diagnosis2`,
		`condition2`,
		`diagnosis3`,
		`condition3`,
		`referredTo`,
		`referredFrom`
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE;

DROP VIEW IF EXISTS visitStart;
CREATE VIEW visitStart AS 
	SELECT 
		`patientVisitID`, 
		`staffName`,
		`staffUsername`,
		`staffPosition`,
		`visitType`, 
		`visitStatus`, 
		`firstVisit`,		
		`primaryComplaint`, 
		`secondaryComplaint`, 
		`dateTimeIn`, 
		`dateTimeOut`, 
		`payment`,
		`clinicPatientID`,
		`patientNationalID`,
		`referredTo`,
		`referredFrom`		
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE;		
		
DROP VIEW IF EXISTS visitOpen;
CREATE VIEW visitOpen AS 
	SELECT 
		`patientVisitID`, 
		`staffName`,
		`staffUsername`,
		`staffPosition`,
		`visitType`, 
		`visitStatus`, 
		`primaryComplaint`, 
		`secondaryComplaint`, 
		`dateTimeIn`, 
		`dateTimeOut`, 
		`payment`,
		`clinicPatientID`, 
		`firstVisit`,		
		`patientNationalID`,
		`patientFamilyID`,
		`patientLastName`, 
		`patientFirstName`, 
		`patientSex`, 
		`patientBirthDate`, 
		`patientHomeAddress1`, 
		`patientHomeAddress2`, 
		`patientHomeNeighborhood`, 
		`patientHomeCity`, 
		`patientHomeCounty`, 
		`patientHomeState`, 
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`diagnosis1`,
		`condition1`,
		`diagnosis2`,
		`condition2`,
		`diagnosis3`,
		`condition3`,
		`referredTo`,
		`referredFrom`
		FROM `visit` 
		WHERE `deleted` = FALSE
			AND `visitStatus` = 'Open'; 
		
DROP VIEW IF EXISTS visitOpenSummary;
CREATE VIEW visitOpenSummary AS
	SELECT 
		`patientLastName`, 
		`patientFirstName`, 
		`dateTimeIn`,
		`patientID`,
		`patientVisitID`, 
		`clinicPatientID`,
		`firstVisit`,		
		`patientNationalID`
	FROM `visit` 
	WHERE `deleted` = FALSE
		AND `visitStatus`='Open';

DROP VIEW IF EXISTS visitCheck;	
CREATE VIEW visitCheck AS
	SELECT 
		`clinicPatientID`, 
		`patientNationalID`,
		`patientFamilyID`,
		`patientVisitID`,
		CONVERT(SUBSTRING(`patientVisitID`, 21, 2), UNSIGNED INTEGER) AS `patientVisitIndex`,
		`visitType`, 
		`visitStatus`, 
		`dateTimeIn`, 
		`dateTimeOut`, 
		`payment`,
		`patientLastName`, 
		`patientFirstName`,
		`firstVisit`
		FROM `visit` 
		WHERE `visit`.`deleted` = FALSE
		ORDER BY `clinicPatientID` ASC, `patientVisitID` DESC
		;

DROP VIEW IF EXISTS visitToday;
CREATE VIEW visitToday AS
	SELECT * 
		FROM visitCheck 
		WHERE DATE_FORMAT(`dateTimeIn`, '%Y%m%d') = DATE_FORMAT(NOW(), '%Y%m%d');

DROP VIEW IF EXISTS `visitPatientEditGet`;
CREATE VIEW `visitPatientEditGet` AS 
	SELECT 
		`visit`.`visitID`, 
		`visit`.`deleted`,
		`visit`.`staffUsername`, 
		`visit`.`staffName`,
		`visit`.`staffPosition`,
		`visit`.`visitType`, 
		`visit`.`visitStatus`, 
		`visit`.`primaryComplaint`, 
		`visit`.`secondaryComplaint`, 
		`visit`.`dateTimeIn`, 
		`visit`.`dateTimeOut`, 
		`visit`.`payment`,
		`visit`.`patientID`, 
		`visit`.`clinicPatientID`, 
		`visit`.`firstVisit`,		
		`visit`.`patientNationalID`,
		`visit`.`patientFamilyID`,
		`visit`.`patientVisitID`, 
		`visit`.`patientLastName` AS `lastName`, 
		`visit`.`patientFirstName` AS `firstName`, 
		`visit`.`patientSex` AS `sex`, 
		`visit`.`patientBirthDate` AS `birthDate`, 
		`visit`.`patientHomeAddress1`, 
		`visit`.`patientHomeAddress2`, 
		`visit`.`patientHomeNeighborhood`, 
		`visit`.`patientHomeCity`, 
		`visit`.`patientHomeCounty`, 
		`visit`.`patientHomeState`, 
		`visit`.`patientKnownAllergies` AS `knownAllergies`,
		`visit`.`patientCurrentMedications` AS `currentMedications`,
		`visit`.`patientNextVaccinationDate` AS `nextVaccinationDate`,
		`visit`.`diagnosis1`, 
		`visit`.`condition1`, 
		`visit`.`diagnosis2`, 
		`visit`.`condition2`, 
		`visit`.`diagnosis3`, 
		`visit`.`condition3`, 
		`visit`.`referredTo`, 
		`visit`.`referredFrom`,
		`patient`.`Active`, 
		`patient`.`ContactPhone`, 
		`patient`.`ContactAltPhone`, 
		`patient`.`BloodType`, 
		`patient`.`OrganDonor`, 
		`patient`.`PreferredLanguage`
		FROM `visit`
		JOIN `patient`  ON `patient`.`clinicPatientID` = `visit`.`clinicPatientID`
		WHERE 1
		;

DROP VIEW IF EXISTS `visitPatientGet`;
CREATE VIEW `visitPatientGet` AS 
	SELECT 
		`visit`.`visitID`,
		`visit`.`staffUsername`, 
		`visit`.`staffName`, 
		`visit`.`staffPosition`,
		`visit`.`visitType`, 
		`visit`.`visitStatus`, 
		`visit`.`primaryComplaint`, 
		`visit`.`secondaryComplaint`, 
		`visit`.`dateTimeIn`, 
		`visit`.`dateTimeOut`, 
		`visit`.`payment`,
		`visit`.`patientID`, 
		`visit`.`clinicPatientID`, 
		`visit`.`firstVisit`,		
		`visit`.`patientNationalID`,
		`visit`.`patientFamilyID`,
		`visit`.`patientVisitID`, 
		`visit`.`patientLastName` AS `lastName`, 
		`visit`.`patientFirstName` AS `firstName`, 
		`visit`.`patientSex` AS `sex`, 
		`visit`.`patientBirthDate` AS `birthDate`, 
		`visit`.`patientHomeAddress1`, 
		`visit`.`patientHomeAddress2`, 
		`visit`.`patientHomeNeighborhood`, 
		`visit`.`patientHomeCity`, 
		`visit`.`patientHomeCounty`, 
		`visit`.`patientHomeState`, 
		`visit`.`patientKnownAllergies` AS `knownAllergies`,
		`visit`.`patientCurrentMedications` AS `currentMedications`,
		`visit`.`patientNextVaccinationDate` AS `nextVaccinationDate`,
		`visit`.`diagnosis1`, 
		`visit`.`condition1`, 
		`visit`.`diagnosis2`, 
		`visit`.`condition2`, 
		`visit`.`diagnosis3`, 
		`visit`.`condition3`, 
		`visit`.`referredTo`, 
		`visit`.`referredFrom`,
		`patient`.`active`, 
		`patient`.`contactPhone`, 
		`patient`.`contactAltPhone`, 
		`patient`.`bloodType`, 
		`patient`.`organDonor`, 
		`patient`.`preferredLanguage`
		FROM `visit`
		JOIN `patient`  ON `patient`.`clinicPatientID` = `visit`.`clinicPatientID`
		WHERE `visit`.`deleted` = FALSE
;

