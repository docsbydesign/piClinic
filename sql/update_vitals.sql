--
-- Copyright 2020 by Robert B. Watson
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy of
-- this software and associated documentation files (the "Software"), to deal in
-- the Software without restriction, including without limitation the rights to
-- use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
-- of the Software, and to permit persons to whom the Software is furnished to do
-- so, subject to the following conditions:
--
-- The above copyright notice and this permission notice shall be included in all
-- copies or substantial portions of the Software.
--
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
-- IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
-- FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
-- AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
-- LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
-- OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
-- SOFTWARE.
--
--
--
-- Script to update June DB to July DB format
--
--   Should not be run on July-DB-based systems
--
--
--
--
USE `piclinic`;

ALTER TABLE `visit`
	ADD `glucoseUnits` enum('FBS','RBS') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) blood sugar test type' AFTER `patientProfession`,
	ADD `glucose` int(11) DEFAULT NULL COMMENT '(optional) Patient blood sugar measured during visit'  AFTER `patientProfession`,
	ADD `pulse` int(11) DEFAULT NULL COMMENT '(optional) Patient pulse (BPM) measured during visit'  AFTER `patientProfession`,
	ADD `bpDiastolic` int(11) DEFAULT NULL COMMENT '(optional) Patient diastolic pressure (mm/hg) measured during visit'  AFTER `patientProfession`,
	ADD `bpSystolic` int(11) DEFAULT NULL COMMENT '(optional) Patient systolic pressure (mm/hg) measured during visit'  AFTER `patientProfession`,
	ADD `tempUnits` enum('C','F') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) temp units'  AFTER `patientProfession`,
	ADD `temp` double DEFAULT NULL COMMENT '(optional) Patient height measured during visit'  AFTER `patientProfession`,
	ADD `weightUnits` enum('kg','lbs') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) weight units'  AFTER `patientProfession`,
	ADD `weight` double DEFAULT NULL COMMENT '(optional) Patient weight measured during visit'  AFTER `patientProfession`,
	ADD `heightUnits` enum('cm','mm','in') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(optional) height units'  AFTER `patientProfession`,
	ADD `height` double DEFAULT NULL COMMENT '(optional) Patient height measured during visit'  AFTER `patientProfession`
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
		`patientContactPhone`,
		`patientContactAltPhone`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`,
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
		`patientContactPhone`,
		`patientContactAltPhone`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`,
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
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`
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
		`patientContactPhone`,
		`patientContactAltPhone`,
		`patientKnownAllergies`,
		`patientCurrentMedications`,
		`patientNextVaccinationDate`,
		`patientProfession`,
		`patientResponsibleParty` ,
		`patientMaritalStatus`,
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`,
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
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`
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
		`height`,
		`heightUnits`,
		`weight`,
		`weightUnits`,
		`temp`,
		`tempUnits`,
		`bpSystolic`,
		`bpDiastolic`,
		`pulse`,
		`glucose`,
		`glucoseUnits`,
		`firstVisit`
		FROM `visit`
		WHERE `visit`.`deleted` = FALSE
		ORDER BY `clinicPatientID` ASC, `patientVisitID` DESC
		;


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
		`visit`.`patientContactPhone`,
		`visit`.`patientContactAltPhone`,
		`visit`.`patientKnownAllergies`,
		`visit`.`patientCurrentMedications`,
		`visit`.`patientNextVaccinationDate`,
		`visit`.`patientProfession`,
		`visit`.`patientResponsibleParty`,
		`visit`.`patientMaritalStatus`,
		`visit`.`height`,
		`visit`.`heightUnits`,
		`visit`.`weight`,
		`visit`.`weightUnits`,
		`visit`.`temp`,
		`visit`.`tempUnits`,
		`visit`.`bpSystolic`,
		`visit`.`bpDiastolic`,
		`visit`.`pulse`,
		`visit`.`glucose`,
		`visit`.`glucoseUnits`,
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
		`visit`.`patientContactPhone`,
		`visit`.`patientContactAltPhone`,
		`visit`.`patientKnownAllergies`,
		`visit`.`patientCurrentMedications`,
		`visit`.`patientNextVaccinationDate`,
		`visit`.`patientProfession`,
		`visit`.`patientResponsibleParty`,
		`visit`.`patientMaritalStatus`,
		`visit`.`height`,
		`visit`.`heightUnits`,
		`visit`.`weight`,
		`visit`.`weightUnits`,
		`visit`.`temp`,
		`visit`.`tempUnits`,
		`visit`.`bpSystolic`,
		`visit`.`bpDiastolic`,
		`visit`.`pulse`,
		`visit`.`glucose`,
		`visit`.`glucoseUnits`,
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
