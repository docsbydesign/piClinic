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
  `logAction` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB action being attempted.',
  `logQueryString` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Query string that initiated the action',
  `logBeforeData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record before change.',
  `logAfterData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) DB Record after change.',
  `logStatusCode` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Status code of action.',
  `logStatusMessage` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Optional) Text status message resulting from action.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this entry was created.'	
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that logs data and error events.';

-- ------------------------------------------------------
--
-- Table structure for table `wflog`
--

DROP TABLE IF EXISTS `wflog`;
CREATE TABLE IF NOT EXISTS `wflog` (
  `wflogId` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique record ID for log records',
  `sourceModule` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Module creating the log entry.',
  `logQueryString` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Query string that initiated the action',
  `prevPage` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) page before the one logging the workflow',
  `prevLink` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Link used on the page before the one logging the workflow',
  `requestId` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Unique request ID provided by Web server',
  `userToken` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(optional) sessionID of user.',
  `logClass` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) type of log entry (home or sub step).',
  `wfName` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) workflow name',
  `wfGuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) unique workflow ID',
  `wfStep` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) workflow step',
  `wfHomeGuid` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) unique workflow ID of HOME step',
  `wfMicrotime` double DEFAULT 0.0 COMMENT '(optional) micro time stamp of log entry',
  `wfMicrotimeString` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Microtime value formatted as date/time string',
  `activeWorkflows` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) JSON string of session workflow list',
  `logBeforeData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) data.',
  `logAfterData` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) data',
  `logStatusCode` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Status code of action.',
  `logStatusMessage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Text status message resulting from action.',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this entry was created.'	
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that logs workflow events.';

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

-- ------------------------------------------------------
--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `helpID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '(Autofill) Unique comment ID for comment records',
  `topicID` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) The topic ID used to relate this entry to similar entries in other languages.',
  `language` varchar (8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) The 2-letter language code that describes the language.',
  `refPage` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) The page this topic applies to. NULL indicates the topic is accessed by reference.',
  `helpText`  varchar(4095) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) The text to display. Can be HTML.',
  `lastChangeBy` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Optional) Username of last editor.',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NOT NULL COMMENT '(Autofill) The date and time this commant was saved.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table that records comments from users.';
--
-- Indexes for table `help`
--
ALTER TABLE `help`
 ADD UNIQUE KEY (`topicID`, `language`);
 
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
  `responsibleParty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Name of responsible party, e.g. for minors.',
  `maritalStatus` enum('NotMarried','Married','','') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Marital status',
  `profession` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) profession',
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
  `patientResponsibleParty` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Name of responsible party, e.g. for minors,  from patient record.',
  `patientMaritalStatus` enum('Married','NotMarried','','') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) Marital status from patient record',
  `patientProfession` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) profession from patient record',
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
  `language` char(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) 2-letter language ID',
  `icd10code` char(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required) ICD-10 code with punctuation',
  `icd10index` char(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '(Required)  ICD-10 code without punctuation',
  `hipaa` Boolean DEFAULT 0 COMMENT '(Not Required) 1 if HIPAA code',
  `shortDescription` varchar(255) COLLATE utf8_unicode_ci NULL COMMENT '(Required) Short description of diagnosis',
  `longDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(Not Required) Long description of diagnosis',
  `useCount` bigint(20) NOT NULL DEFAULT 0 COMMENT '(Not required) Incremented when lastUsedDate is updated.',
  `lastUsedDate` datetime NULL COMMENT '(Not required) Updated when used by the app.',
  `modifiedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '(Auto fill) The date/time of the most recent update',
  `createdDate` datetime NULL COMMENT '(Auto Fill) The time the record was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ICD-10 diagnosis codes and their description.';

DROP VIEW IF EXISTS `icd10Get`;
CREATE VIEW `icd10Get` AS 
	SELECT 
		`language` AS `language`, 
		`icd10code`, 
		`icd10index`, 
		`shortDescription`,
		`useCount` AS `useCount`,
		`lastUsedDate` AS `lastUsedDate`		
	FROM `icd10` 
	WHERE 1;

--
-- Functions
--
DELIMITER $$

DROP FUNCTION IF EXISTS `AgeYMD`$$
-- Returns a string showing the age (date difference) in years, months, days (YY-MM-DD)
CREATE DEFINER=`root`@`localhost` FUNCTION `AgeYMD` (`lateDateArg` DATETIME, `earlyDateArg` DATETIME) RETURNS VARCHAR(10) CHARSET utf8 NO SQL
BEGIN
  DECLARE Y_ED INT;
  DECLARE M_ED INT;
  DECLARE D_ED INT;
  DECLARE Y_LD INT;
  DECLARE M_LD INT;
  DECLARE D_LD INT;
  DECLARE D_MONTH INT;
  DECLARE Y_DIF INT;
  DECLARE M_DIF INT;
  DECLARE D_DIF INT;
  DECLARE RETURN_AGE VARCHAR(10);
  
  IF (lateDateArg = earlyDateArg) THEN
	-- if the dates are the same, the age is 0
	SET @RETURN_AGE = '00-00-00';
  ELSE
	-- arrange the local values such that the 
	-- later date is larger than the earlier date
  	IF(lateDateArg > earlyDateArg) THEN
    	SET @Y_ED = YEAR(earlyDateArg);
        SET @M_ED = MONTH(earlyDateArg);
        SET @D_ED = DAY(earlyDateArg);
        SET @Y_LD = YEAR(lateDateArg);
        SET @M_LD = MONTH(lateDateArg);
        SET @D_LD = DAY(lateDateArg);
    ELSE
    	SET @Y_LD = YEAR(earlyDateArg);
        SET @M_LD = MONTH(earlyDateArg);
        SET @D_LD = DAY(earlyDateArg);
        SET @Y_ED = YEAR(lateDateArg);
        SET @M_ED = MONTH(lateDateArg);
        SET @D_ED = DAY(lateDateArg);
    END IF;
	-- if the early days value is larger than the later days value
	-- borrow from the other fields to make it work
	IF (@D_LD < @D_ED) THEN
		-- borrow the days from the previous month so the 
		-- later days value is larger than the early days
		SET @M_LD = @M_LD -1;
		-- if the previous math results in a month before January...
		IF (@M_LD = 0) THEN
			-- set it to December of prev. year.
			SET @M_LD = 12;
			SET @Y_LD = @Y_LD - 1;
		END IF;
		-- Get the days from the previous month
		-- first, assuming it's not a leap year
		SELECT `days` FROM `monthdays` WHERE `monthId` = @M_LD INTO @D_MONTH;
		-- and add them to the current day value
		SET @D_LD = @D_LD + @D_MONTH;
		-- then adjust it if the M_LD is Feb of a leap year
		IF ((@Y_LD % 4) = 0) AND (@M_LD = 2) THEN
			-- if it's not one of the fake leap years, add in the leap day
			IF ((@Y_LD % 400) > 0) THEN
				SET @D_LD = @D_LD + 1;
			END IF;
		END IF;
	END IF;
	IF (@M_LD < @M_ED) THEN
		SET @M_LD = @M_LD + 12;
		SET @Y_LD = @Y_LD - 1;
	END IF;
	
	-- do the math and compute the differences
  	SET @Y_DIF = @Y_LD - @Y_ED;
    SET @M_DIF = @M_LD - @M_ED;
    SET @D_DIF = @D_LD - @D_ED;	
	-- format the string
	SET @RETURN_AGE = CONCAT(LPAD(CAST(@Y_DIF as CHAR),2,'0'), '-',
							LPAD(CAST(@M_DIF as CHAR),2,'0'), '-',
							LPAD(CAST(@D_DIF as CHAR),2,'0'));
	
  END IF;
  RETURN @RETURN_AGE;
END$$

DELIMITER ;
	
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
		`patient`.`currentMedications` AS `currentMedications`,
		`patient`.`nextVaccinationDate` AS `nextVaccinationDate`,
		`patient`.`profession` AS `profession`,
		`patient`.`responsibleParty` AS `responsibleParty`,
		`patient`.`maritalStatus` AS `maritalStatus`
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
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
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
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
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
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
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
		`visit`.`patientProfession` AS `patientProfession`,
		`visit`.`patientResponsibleParty` AS `patientResponsibleParty`,
		`visit`.`patientMaritalStatus` AS `patientMaritalStatus`,
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
		`visit`.`patientProfession` AS `patientProfession`,
		`visit`.`patientResponsibleParty` AS `patientResponsibleParty`,
		`visit`.`patientMaritalStatus` AS `patientMaritalStatus`,
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

DROP VIEW IF EXISTS visitGetWithAgeGroup;
CREATE VIEW visitGetWithAgeGroup AS 
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
		`clinicPatientID`, 
		`firstVisit`,
		`patientNationalID`,
		`patientFamilyID`,
		`patientLastName`, 
		`patientFirstName`, 
		`patientSex`, 
		`patientBirthDate`,
		AgeYMD(`dateTimeIn`, `patientBirthDate`) as `patientAgeYMD`,
		`patientHomeAddress1`, 
		`patientHomeAddress2`, 
		`patientHomeNeighborhood`, 
		`patientHomeCity`, 
		`patientHomeCounty`, 
		`patientHomeState`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`diagnosis1`,
		`condition1`,
		`diagnosis2`,
		`condition2`,
		`diagnosis3`,
		`condition3`,
		`referredTo`,
		`referredFrom`,
-- computed age groups
		CASE WHEN (`patientSex` = 'M')  THEN 1 ELSE 0 END AS `PT_MALE`,
		CASE WHEN (`patientSex` = 'F')  THEN 1 ELSE 0 END AS `PT_FEMALE`,
		CASE WHEN (`referredFrom` IS NOT NULL AND `referredFrom` != '') THEN 1 ELSE 0 END AS `PT_REFERRED_IN`,
		CASE WHEN (`referredFrom` IS NOT NULL AND `referredFrom` != '') THEN 0 ELSE 1 END AS `PT_WALK_IN`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) = '00-00')  THEN 1 ELSE 0 END AS `LT_1MO`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00')  THEN 1 ELSE 0 END AS `LT_1YR`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  THEN 1 ELSE 0 END AS `LT_5YR`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) = '00-00') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `LT_1MO_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `LT_1YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `LT_5YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) = '00-00') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `LT_1MO_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `LT_1YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `LT_5YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) >= '00-01') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_1MO_LT_1YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '01') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_1YR_LT_5YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '10') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_5YR_LT_10YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_5YR_LT_15YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '10') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_10YR_LT_15YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '20') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_15YR_LT_20YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_15YR_LT_50YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_15YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '25') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_20YR_LT_25YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_20YR_LT_50YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '25') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '30') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_25YR_LT_30YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '25') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '40') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_25YR_LT_40YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '30') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_30YR_LT_50YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '40') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_40YR_LT_60YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_50YR_LT_60YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_50YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '60') AND (`firstVisit` = 'YES') THEN 1 ELSE 0 END AS `GE_60YR_N`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) >= '00-01') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_1MO_LT_1YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '01') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_1YR_LT_5YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '10') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_5YR_LT_10YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_5YR_LT_15YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '10') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_10YR_LT_15YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '20') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_15YR_LT_20YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_15YR_LT_50YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_15YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '25') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_20YR_LT_25YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_20YR_LT_50YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '25') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '30') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_25YR_LT_30YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '25') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '40') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_25YR_LT_40YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '30') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_30YR_LT_50YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '40') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_40YR_LT_60YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_50YR_LT_60YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_50YR_S`,
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '60') AND (`firstVisit` = 'NO') THEN 1 ELSE 0 END AS `GE_60YR_S`	FROM `visit` 
	WHERE `visit`.`deleted` = FALSE;
		
DROP VIEW IF EXISTS visitGetAT2Data;
CREATE VIEW visitGetAT2Data AS 
	SELECT 
		`patientVisitID`, 
		`staffName`, 
		`staffPosition`,
		`visitType`,
		`dateTimeIn`, 
		`dateTimeOut`, 
		`clinicPatientID`, 
		`firstVisit`,
		`patientNationalID`,
		`patientFamilyID`,
		`patientLastName`, 
		`patientFirstName`, 
		`patientSex`, 
		`patientBirthDate`,
		AgeYMD(`dateTimeIn`, `patientBirthDate`) as `patientAgeYMD`,
		`diagnosis1`,
		`condition1`,
		`diagnosis2`,
		`condition2`,
		`diagnosis3`,
		`condition3`,
-- computed fields
-- AT2 Line 1
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) = '00-00') AND (`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_01`,
-- AT2 Line 2
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) = '00-00') AND (`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_02`,
-- AT2 Line 3
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) >= '00-01') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_03`,
-- AT2 Line 4
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,5) >= '00-01') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) = '00') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_04`,
-- AT2 Line 5
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '01') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_05`,
-- AT2 Line 6
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '01') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_06`,
-- AT2 Line 7
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '10') AND
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_07`,
-- AT2 Line 8
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '05') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '10') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_08`,
-- AT2 Line 9
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '10') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_09`,
-- AT2 Line 10
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '10') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '15') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_10`,
-- AT2 Line 11
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '20') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_11`,
-- AT2 Line 12
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '15') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '20') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_12`,
-- AT2 Line 13
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_13`,
-- AT2 Line 14
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '20') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '50') AND 
				(`firstVisit` = 'NO')
			THEN 1 ELSE 0 END AS `RPT_LINE_14`,
-- AT2 Line 15
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND 
				(`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_15`,
-- AT2 Line 16
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '50') AND 
				(SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '60') AND 
				(`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_16`,
-- AT2 Line 17
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '60') AND (`firstVisit` = 'YES') 
			THEN 1 ELSE 0 END AS `RPT_LINE_17`,
-- AT2 Line 18
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) >= '60') AND (`firstVisit` = 'NO') 
			THEN 1 ELSE 0 END AS `RPT_LINE_18`,
-- AT2 Line 19
		1  AS `RPT_LINE_19`,
-- AT2 Line 20
		CASE WHEN (`patientSex` = 'F')  
			THEN 1 ELSE 0 END AS `RPT_LINE_20`,
-- AT2 Line 21
		CASE WHEN (`patientSex` = 'M')  
			THEN 1 ELSE 0 END AS `RPT_LINE_21`,
-- AT2 Line 23
		CASE WHEN (`referredFrom` IS NOT NULL AND `referredFrom` != '') 
			THEN 0 ELSE 1 END AS `RPT_LINE_22`,
-- AT2 Line 23
		CASE WHEN (`referredFrom` IS NOT NULL AND `referredFrom` != '') 
			THEN 1 ELSE 0 END AS `RPT_LINE_23`,
-- AT2 Line 24
		CASE WHEN ((`diagnosis1` REGEXP '^[*]RPT001') OR  
				(`diagnosis2` REGEXP '^[*]RPT001') OR  
				(`diagnosis3` REGEXP '^[*]RPT001'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_24`,
-- AT2 Line 25
		CASE WHEN ((`diagnosis1` REGEXP '^D06$') OR  
				(`diagnosis2` REGEXP '^D06$') OR  
				(`diagnosis3` REGEXP '^D06$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_25`,
-- AT2 Line 26
		CASE WHEN (((`diagnosis1` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition1` = 'NEWDIAG')) OR 
				((`diagnosis2` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition2` = 'NEWDIAG')) OR 
				((`diagnosis3` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition3` = 'NEWDIAG')))
			THEN 1 ELSE 0 END AS `RPT_LINE_26`,	
-- AT2 Line 27
		CASE WHEN (((`diagnosis1` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition1` = 'SUBSDIAG')) OR 
				((`diagnosis2` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition2` = 'SUBSDIAG')) OR 
				((`diagnosis3` REGEXP '^Z3[3|4][0-9]?$' ) AND (`condition3` = 'SUBSDIAG')))
			THEN 1 ELSE 0 END AS `RPT_LINE_27`,
-- AT2 Line 28
		CASE WHEN ((`diagnosis1` REGEXP '^Z392$') OR  
				(`diagnosis2` REGEXP '^Z392$') OR  
				(`diagnosis3` REGEXP '^Z392$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_28`,
-- AT2 Line 29
		CASE WHEN ((`diagnosis1` REGEXP '^Z304101$') OR  
				(`diagnosis2` REGEXP '^Z304101$') OR  
				(`diagnosis3` REGEXP '^Z304101$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_29`,
-- AT2 Line 30
		CASE WHEN ((`diagnosis1` REGEXP '^Z304103$') OR  
				(`diagnosis2` REGEXP '^Z304103$') OR  
				(`diagnosis3` REGEXP '^Z304103$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_30`,
-- AT2 Line 31
		CASE WHEN ((`diagnosis1` REGEXP '^Z304106$') OR  
				(`diagnosis2` REGEXP '^Z304106$') OR  
				(`diagnosis3` REGEXP '^Z304106$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_31`,
-- AT2 Line 32
		CASE WHEN ((`diagnosis1` REGEXP '^Z304910$') OR  
				(`diagnosis2` REGEXP '^Z304910$') OR  
				(`diagnosis3` REGEXP '^Z304910$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_32`,
-- AT2 Line 33
		CASE WHEN ((`diagnosis1` REGEXP '^Z304030$') OR  
				(`diagnosis2` REGEXP '^Z304030$') OR  
				(`diagnosis3` REGEXP '^Z304030$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_33`,
-- AT2 Line 34
		CASE WHEN ((`diagnosis1` REGEXP '^Z3042$') OR 
				(`diagnosis2` REGEXP '^Z3042$') OR 
				(`diagnosis3` REGEXP '^Z3042$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_34`,
-- AT2 Line 35
		CASE WHEN ((`diagnosis1` REGEXP '^Z30430$') OR
				(`diagnosis2` REGEXP '^Z30430$') OR
				(`diagnosis3` REGEXP '^Z30430$'))
			THEN 1 ELSE 0 END AS `RPT_LINE_35`,
-- AT2 Line 36
		CASE WHEN ((`diagnosis1` REGEXP '^[*]RPT002$') OR 
				(`diagnosis2` REGEXP '^[*]RPT002$') OR
				(`diagnosis3` REGEXP '^[*]RPT002$'))  
			THEN 1 ELSE 0 END AS `RPT_LINE_36`,
-- AT2 Line 37
		CASE WHEN ((`diagnosis1` REGEXP '^Z3046$') OR
				(`diagnosis2` REGEXP '^Z3046$') OR
				(`diagnosis3` REGEXP '^Z3046$')) 
			THEN 1 ELSE 0 END AS `RPT_LINE_37`,
-- AT2 Line 38
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^A09$') OR  
					 (`diagnosis2` REGEXP '^A09$')  OR  
					 (`diagnosis3` REGEXP '^A09$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_38`,
-- AT2 Line 39
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					(((`diagnosis1` REGEXP '^A09$' ) AND (`condition1` = 'SUBSDIAG')) OR 
					((`diagnosis2` REGEXP '^A09$' ) AND (`condition2` = 'SUBSDIAG')) OR 
					((`diagnosis3` REGEXP '^A09$' ) AND (`condition3` = 'SUBSDIAG'))))  
			THEN 1 ELSE 0 END AS `RPT_LINE_39`,
-- AT2 Line 40
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT005$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT005$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT005$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_40`,
-- AT2 Line 41
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND (`firstVisit` = 'YES') AND
					((`diagnosis1` REGEXP '^J15$' ) OR 
					(`diagnosis2` REGEXP '^J15$' ) OR 
					(`diagnosis3` REGEXP '^J15$' )))
			THEN 1 ELSE 0 END AS `RPT_LINE_41`,
-- AT2 Line 42
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					(((`diagnosis1` REGEXP '^J15$' ) AND (`condition1` = 'SUBSDIAG')) OR 
					((`diagnosis2` REGEXP '^J15$' ) AND (`condition2` = 'SUBSDIAG')) OR 
					((`diagnosis3` REGEXP '^J15$' ) AND (`condition3` = 'SUBSDIAG'))))  
			THEN 1 ELSE 0 END AS `RPT_LINE_42`,
-- AT2 Line 43
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05') AND
					((`diagnosis1` REGEXP '^D(5[6-9]|6[0-9]|7[0-7])[0-9]?$' ) OR 
					(`diagnosis2` REGEXP '^D(5[6-9]|6[0-9]|7[0-7])[0-9]?$' ) OR 
					(`diagnosis3` REGEXP '^D(5[6-9]|6[0-9]|7[0-7])[0-9]?$' )))
			THEN 1 ELSE 0 END AS `RPT_LINE_43`,
-- AT2 Line 44
		CASE WHEN (SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  THEN 1 ELSE 0 END AS `RPT_LINE_44`,
-- AT2 Line 45
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT007$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT007$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT007$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_45`,
-- AT2 Line 46
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT008$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT008$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT008$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_46`,
-- AT2 Line 47
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT009$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT009$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT009$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_47`,
-- AT2 Line 48
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT010$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT010$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT010$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_48`,
-- AT2 Line 49
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT011$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT011$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT011$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_49`,
-- AT2 Line 50
		CASE WHEN ((SUBSTR(AgeYMD(`dateTimeIn`, `patientBirthDate`),1,2) < '05')  AND 
					((`diagnosis1` REGEXP '^[*]RPT012$') OR  
					 (`diagnosis2` REGEXP '^[*]RPT012$')  OR  
					 (`diagnosis3` REGEXP '^[*]RPT012$')))  
			THEN 1 ELSE 0 END AS `RPT_LINE_50`,
-- AT2 Line 51
		CASE WHEN (`diagnosis1` REGEXP '^[*]RPT013$') OR  
				(`diagnosis2` REGEXP '^[*]RPT013$')  OR  
				(`diagnosis3` REGEXP '^[*]RPT013$')
			THEN 1 ELSE 0 END AS `RPT_LINE_51`,
-- AT2 Line 52
		CASE WHEN (`diagnosis1` REGEXP '^[*]RPT014$') OR  
				(`diagnosis2` REGEXP '^[*]RPT014$')  OR  
				(`diagnosis3` REGEXP '^[*]RPT014$')
			THEN 1 ELSE 0 END AS `RPT_LINE_52`
	FROM `visit` 
	WHERE `visit`.`deleted` = FALSE;

-- Diagnosis code report data for preceding query
DROP VIEW IF EXISTS visitGetAT2DataFields;
CREATE VIEW visitGetAT2DataFields AS 
	SELECT '24' AS `REPORT_ROW`, 'RPT_LINE_24' AS `RPT_STRING`, 'Deteccion de Sintomaticos Respiratorios' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT001$') 
	UNION ALL SELECT '25' AS `REPORT_ROW`, 'RPT_LINE_25' AS `RPT_STRING`, 'Deteccion de Cancer Cervico Uterino' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^D06$') 
	UNION ALL SELECT '26' AS `REPORT_ROW`, 'RPT_LINE_26' AS `RPT_STRING`, 'Embarazadas Nueava' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z3[3|4][0-9]?$') 
	UNION ALL SELECT '27' AS `REPORT_ROW`, 'RPT_LINE_27' AS `RPT_STRING`, 'Embarazadas en Control' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z3[3|4][0-9]?$') 
	UNION ALL SELECT '28' AS `REPORT_ROW`, 'RPT_LINE_28' AS `RPT_STRING`, 'Controles Puerperales' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z392$') 
	UNION ALL SELECT '29' AS `REPORT_ROW`, 'RPT_LINE_29' AS `RPT_STRING`, 'Anticoncpetivo Oral 1 Ciclo' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z304101$') 
	UNION ALL SELECT '30' AS `REPORT_ROW`, 'RPT_LINE_30' AS `RPT_STRING`, 'Anticoncpetivo Oral 3 Ciclo' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z304103$') 
	UNION ALL SELECT '31' AS `REPORT_ROW`, 'RPT_LINE_31' AS `RPT_STRING`, 'Anticonceptivo Oral 6 Ciclo' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z304106$') 
	UNION ALL SELECT '32' AS `REPORT_ROW`, 'RPT_LINE_32' AS `RPT_STRING`, 'Condones 10 Unidades' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z304910$') 
	UNION ALL SELECT '33' AS `REPORT_ROW`, 'RPT_LINE_33' AS `RPT_STRING`, 'Condones 30 Unidades' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z304030$') 
	UNION ALL SELECT '34' AS `REPORT_ROW`, 'RPT_LINE_34' AS `RPT_STRING`, 'Depo porvera Aplicadas' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z3042$') 
	UNION ALL SELECT '35' AS `REPORT_ROW`, 'RPT_LINE_35' AS `RPT_STRING`, 'DIU insertados' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z30430$') 
	UNION ALL SELECT '36' AS `REPORT_ROW`, 'RPT_LINE_36' AS `RPT_STRING`, '(Collar)' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT002$') 
	UNION ALL SELECT '37' AS `REPORT_ROW`, 'RPT_LINE_37' AS `RPT_STRING`, 'Implante Sub Dermico' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^Z3046$') 
	UNION ALL SELECT '38' AS `REPORT_ROW`, 'RPT_LINE_38' AS `RPT_STRING`, 'Diarrea' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^A09$') 
	UNION ALL SELECT '39' AS `REPORT_ROW`, 'RPT_LINE_39' AS `RPT_STRING`, 'Diarrea que acuden a cita de seguimiento' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^A09$') 
	UNION ALL SELECT '40' AS `REPORT_ROW`, 'RPT_LINE_40' AS `RPT_STRING`, 'Deshidratacion Rehidratados en la US' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT005$') 
	UNION ALL SELECT '41' AS `REPORT_ROW`, 'RPT_LINE_41' AS `RPT_STRING`, 'Neumonia (nueva en el año)' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^J15$') 
	UNION ALL SELECT '42' AS `REPORT_ROW`, 'RPT_LINE_42' AS `RPT_STRING`, 'Neumonia que acuden a su cita de Seguimiento' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^J15$') 
	UNION ALL SELECT '43' AS `REPORT_ROW`, 'RPT_LINE_43' AS `RPT_STRING`, 'Algun grado de sindrome anémico diagnosticado por laboratorio' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^D(5[6-9]|6[0-9]|7[0-7])[0-9]?$') 
	UNION ALL SELECT '45' AS `REPORT_ROW`, 'RPT_LINE_45' AS `RPT_STRING`, 'Crecimiento adecuado' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT007$') 
	UNION ALL SELECT '46' AS `REPORT_ROW`, 'RPT_LINE_46' AS `RPT_STRING`, 'Crecimiento inadecuado' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT008$') 
	UNION ALL SELECT '47' AS `REPORT_ROW`, 'RPT_LINE_47' AS `RPT_STRING`, 'Bajo percentil 3' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT009$') 
	UNION ALL SELECT '48' AS `REPORT_ROW`, 'RPT_LINE_48' AS `RPT_STRING`, 'Daño nutricional servero' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT010$') 
	UNION ALL SELECT '49' AS `REPORT_ROW`, 'RPT_LINE_49' AS `RPT_STRING`, 'Discapacidad nuevo en el año' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT011$') 
	UNION ALL SELECT '50' AS `REPORT_ROW`, 'RPT_LINE_50' AS `RPT_STRING`, 'Probable alteracion del desarrollo' AS `ROW_TXT_ES`, '< 05' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT012$') 
	UNION ALL SELECT '51' AS `REPORT_ROW`, 'RPT_LINE_51' AS `RPT_STRING`, 'Atención pretnatal nueva en las primeras 12 SG' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT013$') 
	UNION ALL SELECT '52' AS `REPORT_ROW`, 'RPT_LINE_52' AS `RPT_STRING`, 'Atención puerperal nueva en los primeros 10 dias' AS `ROW_TXT_ES`, '' AS AGE_GROUP, `icd10code`,`icd10index`,`language`,`shortDescription` FROM `icd10` WHERE 1 AND (`icd10index` REGEXP '^[*]RPT014$') 
;