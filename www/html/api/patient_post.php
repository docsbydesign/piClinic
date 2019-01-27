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
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *			`familyID` - (Optional) the ID of the family's record
 *   		`lastName` - (Required) Patient's last name(s)'
 *   		`lastName1` - (Required) Patient's last name(s)'
 *   		`firstName` - (Required) Patient''s first name'
 *   		`sex` - (Required) 'Male','Female','Other' Patient''s sex'
 *   		`birthDate` - (Required) Patient''s date of birth'
 *   		`homeAddress1` - (Optional) Patient''s home address'
 *   		`homeAddress2` - (Optional) additional home address info (e.g. apt, room, etc.)'
 *   		`homeNeighborhood` - (Optional) Patient''s home neighborhood.'
 *   		`homeCity` - (Optional) Patient''s home city'
 *   		`homeCounty` - (Optional) Patient''s home county'
 *   		`homeState` - (Optional) Patient''s home state',
 *			`contactPhone` - (Optional) Patient''s primary phone number'
 *   		`contactAltPhone` - (Optional) Patient''s alternate phone number'
 * 			`bloodType` - (Optional) Patient''s blood type ('A+','A-','B+','B-','AB+','AB-','O+','O-','NA')
 * 			`organDoner` - (Optional) Patient''s organ donor preference'
 * 			`preferredLanguage` - '(Optional) Patient''s preferred language for communications'
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

    // token parameter was verified before this function was called.
    $logData = createLogEntry (
        'API',
        __FILE__,
        'patient',
         $_SERVER['REQUEST_METHOD'],
         $apiUserToken,
         $requestArgs,
        null,
        null,
        null,
        null);

	if (empty($requestArgs['clinicPatientID'])) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to add patient record. The patient ID is missing.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	
	// check for other required columns
	$requiredPatientColumns = [
		"lastName"
		,"firstName"
		,"sex"
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
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// clean leading and trailing spaces from string fields
	$postArgs = cleanPatientStringFields ($requestArgs);

    $dbInfo['postArgs'] = $postArgs;
	// make insert query string to add new object
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_PATIENT, $postArgs);
    $dbInfo['insertQueryString'] = $insertQueryString;
	// try to add the record to the database
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
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
			DB_VIEW_PATIENT_GET. "` WHERE `clinicPatientID` = '".
			$postArgs['clinicPatientID']."';";
		$returnValue = getDbRecords($dbLink, $getQueryString);
		if ($returnValue['httpResponse'] == 200) {
			// found the new record
			$logData['after'] = $returnValue['data'];
			// adjust return value to reflect POST operation				
			$returnValue['httpResponse'] = 201;
			$returnValue['httpReason']	= "Success";
		}
		@mysqli_free_result($qResult);
	}

    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logsStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
    profileLogClose($profileData, __FILE__, $requestArgs);
    return $returnValue;
}
//EOF