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
 *	Updates patient resources from the database 
 * 		or an HTML error message
 *
 * PATCH: Modifies one or more fields in an existing patient record as identified by ClinicPatientID
 *
 * 		input data:
 *			`ClinicPatientID` - (Required) Patient ID issued by clinic.
 *			`FamilyID` - (Optional) the ID of the family's record
 *   		`NameLast` - (Optional) Patient's last name(s)'
 *   		`NameFirst` - (Optional) Patient''s first name'
 *   		`Sex` - (Optional) 'Male','Female','Other' Patient''s sex'
 *   		`BirthDate` - (Optional) Patient''s date of birth'
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
 *			200: the updated patient record
 *			400: required field is missing
 *			404: no record found that matches the specified patient clinic ID
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _patient_patch ($dbLink, $apiUserToken, $formArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['formArgs'] = $formArgs;

	$logData = array();
	$logData['table'] = 'patient';
	$logData['action'] = 'patch';
	$logData['user'] = 'SYSTEM'; // TODO; get a real user id
	
	// confirm that we have the record ID 
	if (empty( $formArgs["ClinicPatientID"])) {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update patient record. The ClinicPatientID is missing.";
		return $returnValue;
	}
	
	// make sure the record is currently active
	// create query string for get operation
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_PATIENT_GET. "` WHERE `ClinicPatientID` = '".
		$formArgs['ClinicPatientID']."';";
	$testReturnValue = getDbRecords($dbLink, $getQueryString);
	if ($testReturnValue['httpResponse'] !=  200) {
		// can't find the record to delete. It could already be deleted or it could not exist.
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "Patient record to update not found.";
		return $returnValue;
	} else {
		$logData['before'] = $testReturnValue['data'];		
	}
	
	// clean leading and trailing spaces from string fields
	$patchArgs = cleanPatientStringFields ($formArgs);
	
	// make update query string from data buffer
	$columnsToUpdate = 0;
	$updateQueryString = format_object_for_SQL_update (DB_TABLE_PATIENT, $patchArgs, "ClinicPatientID", $columnsToUpdate);
	
	// check query string construction
	if ($columnsToUpdate < 1) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$dbInfo['updateQueryString'] = $updateQueryString;
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update the patient record. No data fields were included in the request.";
		return $returnValue;		
	}
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	if (!empty($updateQueryString)) {
		// try to add the record to the database
		$qResult = @mysqli_query($dbLink, $updateQueryString);
		if (!$qResult) {
			// SQL ERROR
			$dbInfo['updateQueryString'] = $updateQueryString;
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
			if (API_DEBUG_MODE) {
				$returnValue['error'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update patient.";
		} else {
			profileLogCheckpoint($profileData,'UPDATE_COMPLETE');
			// create query string for get operation
			$getQueryString = "SELECT * FROM `".
				DB_VIEW_PATIENT_GET. "` WHERE `ClinicPatientID` = '".
				$formArgs['ClinicPatientID']."';";
			$returnValue = getDbRecords($dbLink, $getQueryString);
			$logData['after'] = $returnValue['data'];
			writeUpdateToLog ($logData);
			@mysqli_free_result($qResult);
		}			
	} else {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update record. The patient ID is missing.";
	}
	profileLogClose($profileData, __FILE__, $formArgs);	
	return $returnValue;
}
?>