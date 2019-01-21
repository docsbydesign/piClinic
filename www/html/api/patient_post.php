<?php
/*
 *	Copyright (c) 2018, Robert B. Watson
 *
 *	This file is part of the piClinic Console.
 *
 *  piClinic Console is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  piClinic Console is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */
/*******************
 *
 *	Creates/Returns patient resources from the database 
 * 		or an HTML error message
 *
 *	POST: Adds a new patient record to the database
 * 		input data:
 *			`ClinicPatientID` - (Required) Patient ID issued by clinic.
 *			`FamilyID` - (Optional) the ID of the family's record
 *   		`NameLast` - (Required) Patient's last name(s)'
 *   		`NameFirst` - (Required) Patient''s first name'
 *   		`Sex` - (Required) 'Male','Female','Other' Patient''s sex'
 *   		`BirthDate` - (Required) Patient''s date of birth'
 *   		`HomeAddress1` - (Optional) Patient''s home address'
 *   		`HomeAddress2` - (Optional) additional home address info (e.g. apt, room, etc.)'
 *   		`HomeNeighborhood` - (Optional) Patient''s home neighborhood.'
 *   		`HomeCity` - (Optional) Patient''s home city'
 *   		`HomeCounty` - (Optional) Patient''s home county'
 *   		`HomeState` - (Optional) Patient''s home state',
 *			`ContactPhone` - (Optional) Patient''s primary phone number'
 *   		`ContactAltPhone` - (Optional) Patient''s alternate phone number'
 * 			`BloodType` - (Optional) Patient''s blood type ('A+','A-','B+','B-','AB+','AB-','O+','O-','NA')
 * 			`OrganDoner` - (Optional) Patient''s organ donor preference'
 * 			`PreferredLanguage` - '(Optional) Patient''s preferred language for communications'
 *
 *		Returns:
 *			201: the new patient record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _patient_post ($dbLink, $apiUserToken, $requestArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

	$logData = array();
	$logData['table'] = 'patient';
	$logData['action'] = 'post';
	$logData['user'] = 'SYSTEM'; // TODO; get a real user id
	$logData['before'] = ''; // no data, yet

	if (empty($requestArgs['ClinicPatientID'])) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to add patient record. The patient ID is missing.";
		return $returnValue;
	}
	
	// check for other required columns
	$requiredPatientColumns = [
		"NameLast"
		,"NameFirst"
		,"Sex"
		];
	$missingColumnList = "";
	foreach ($requiredPatientColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ", ";
			}
			$missingColumnList .= $column;
		}		
	}
	
	if (!empty($missingColumnList)) {
		// some required fields are missing so exit
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to add patient record. Required patient record field(s): ". $missingColumnList. " are missing.";
		return $returnValue;
	}
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// clean leading and trailing spaces from string fields
	$postArgs = cleanPatientStringFields ($requestArgs);

	// make insert query string to add new object
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_PATIENT, $postArgs);
	// try to add the record to the database
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['insertQueryString'] = $insertQueryString;
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		if (substr($dbInfo['sqlError'], 0, 9) == "Duplicate") {
			// a "duplicate record" error was returned, so update the response
			$returnValue['httpResponse'] = 409;
			$returnValue['httpReason']	= "Duplicate entry. The patient ID is already in the database. ".$dbInfo['sqlError'];
		} else if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 400;
			$returnValue['httpReason']	= "Unable to add the patient record. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to add patient.";
		}
	} else {
		profileLogCheckpoint($profileData,'POST_COMPLETE');
		// create query string for get operation
		$getQueryString = "SELECT * FROM `".
			DB_VIEW_PATIENT_GET. "` WHERE `ClinicPatientID` = '".
			$postArgs['ClinicPatientID']."';";
		$returnValue = getDbRecords($dbLink, $getQueryString);
		if ($returnValue['httpResponse'] == 200) {
			// found the new record
			$logData['after'] = $returnValue['data'];
			// adjust return value to reflect POST operation				
			$returnValue['httpResponse'] = 201;
			$returnValue['httpReason']	= "Success";
			writeUpdateToLog ($logData);
		}
		@mysqli_free_result($qResult);
	}
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
?>