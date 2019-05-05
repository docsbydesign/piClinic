<?php
/*
 *	Copyright (c) 2019, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */
/*******************
 *
 *	Creates/Returns visit resources from the database 
 * 		or an HTML error message
 *
 *	POST: Adds a new patient visit to the database
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *   		`VisitType` - (Required) The type of visit
 *			`dateTimeIn` - (Optional) Current time, if not the current time
 *
 *		Returns:
 *			201: the new visit record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _visit_post ($dbLink, $apiUserToken, $requestArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	
	// format db table fields as dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    // token parameter was verified before this function was called.
    $logData = createLogEntry ('API', __FILE__, 'visit', $_SERVER['REQUEST_METHOD'], $apiUserToken, $requestArgs, null, null, null, null);
	
	// check for other required columns
	$requiredPatientColumns = [
		"visitType"
		,"clinicPatientID"
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
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to add patient visit. Required field(s): ". $missingColumnList. " are missing.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	
	profileLogCheckpoint($profileData,'DUPE_CHECK_READY');
	// make sure that the patient ID corresponds to a real patient
	// see if the specified patient is in the patient table
	$ptQuery = 'SELECT * FROM `'. DB_TABLE_PATIENT .
		'` WHERE `clinicPatientID` = \''. $requestArgs['clinicPatientID'] . '\';';
	$ptRecord = getDbRecords($dbLink, $ptQuery);
	$ptInfo = [];
	if ($ptRecord['httpResponse'] != 200) {
		// could not find the patient so return an error
		if (API_DEBUG_MODE) {
			$dbInfo['patientQueryString'] = $ptQuery;
			$dbInfo['ptRecord'] = $ptRecord;
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "Patient ". $requestArgs['clinicPatientID']. " was not found in the system.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		return $returnValue;
	} else {
		$ptInfo = $ptRecord['data'];
	}

	// At this point the request has passed all the validation tests 
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// determine the visit date: use the provided or NOW is none provided
	$tempDateTime = NULL;
	if (!empty($requestArgs['dateTimeIn'])) {
		// this will fix any wackyness in the string
		$tempDateTime = date_create_from_format('Y-m-d H:i:s', $requestArgs['dateTimeIn'] );
	} 
	
	// if that didn't convert well, use the current date/time for the visit 
	if (empty($tempDateTime)) {
		// if no date/time in, use the current time
		$tempDateTime = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s') );
	}
	$visitDate = date_format ($tempDateTime, 'Y-m-d');
	
	// create the patient visit ID value from:
	//	The patient's patient record ID as an 12-digit string
	//	The current date as YYYYMMDD
	//	The next available index (starting with 01, if none found for the day)
	
	// see if this patient has a visit record on the visit date ...
	$ptVisitIndex = 1;
	$visitQuery = 'SELECT * FROM `'. DB_VIEW_VISIT_CHECK .
		'` WHERE `clinicPatientID` = \''. $requestArgs['clinicPatientID'].'\' '.
		'AND DATE_FORMAT(`dateTimeIn`, \'%Y-%m-%d\') = \''.$visitDate.'\';';
	$dbInfo['visitQuery'] = $visitQuery;
	$visitRecord = getDbRecords($dbLink, $visitQuery);

	profileLogCheckpoint($profileData,'VISIT_CHECK_RETURNED');
	$dbInfo['visitPreviewQuery'] = $visitQuery;
	if (($visitRecord['httpResponse'] == 200) && ($visitRecord['count'] > 0)) {
		$ptVisit = [];
		// found a visit record from today, so increment the Index 
		if ($visitRecord['count'] == 1) {
			$ptVisit = $visitRecord['data'];
		} else {
			// the records are sorted with the most recent first
			$ptVisit = $visitRecord['data'][0];
		}
		if (isset($ptVisit['patientVisitIndex'])) {
			$ptVisitIndex = $ptVisit['patientVisitIndex'] + 1;
		} // else, leave it at 1
		if ($ptVisitIndex > 99) {
			// this is a server error. The same person should not
			//   show up 100 times to the same clinic the same day
			if (API_DEBUG_MODE) {
				$dbInfo['ptVisit'] = $ptVisit;
				$returnValue['debug'] = $dbInfo;
			}
			$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Patient ". $requestArgs['clinicPatientID']. " has visited the clinic more than 99 times today.";
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logsStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
			return $returnValue;
		}
	}
	
	// copy and clean query parameters to post
	$dbArgs = cleanVisitStringFields ($requestArgs);

	// calculated from existing values
	$dbArgs['dateTimeIn'] = date_format ($tempDateTime, 'Y-m-d H:i:s'); // from earlier
	
	// create the new patient visit ID from the visit date
	$ptVisitId = sprintf("%'012d%8s%'02d", $ptInfo['patientID'],$tempDateTime->format('Ymd'),$ptVisitIndex);	
	$dbArgs['patientVisitID'] = $ptVisitId;
	$dbArgs['patientID'] = $ptInfo['patientID'];
	if (isset($ptInfo['patientNationalID'])) {
		$dbArgs['patientNationalID'] = $ptInfo['patientNationalID'];
	}

	// load fields from patient record found earlier
    // [ 'patient' field name, 'visit' field name]
	$dbPatientFields = [
		['lastName', 'patientLastName']
		,['firstName', 'patientFirstName']
		,['familyID', 'patientFamilyID']
		,['sex', 'patientSex']
		,['birthDate', 'patientBirthDate']
		,['homeAddress1', 'patientHomeAddress1']
		,['homeAddress2', 'patientHomeAddress2']
		,['homeNeighborhood', 'patientHomeNeighborhood']
		,['homeCity', 'patientHomeCity']
		,['homeCounty', 'patientHomeCounty']
		,['homeState', 'patientHomeState']
		,['knownAllergies','patientKnownAllergies']
		,['currentMedications', 'patientCurrentMedications']
        ,['nextVaccinationDate', 'patientNextVaccinationDate']
        ,['responsibleParty','patientResponsibleParty']
        ,['maritalStatus','patientMaritalStatus']
        ,['profession','patientProfession']
		];

	foreach ($dbPatientFields as $field){
		if (isset($ptInfo[$field[0]])){
			$dbArgs[$field[1]] = trim($ptInfo[$field[0]]);
		}
	}

	// append 2nd last name if present
	if (!empty($ptInfo['lastName2'])) {
		$dbArgs['patientLastName'] .= ' '.trim($ptInfo['lastName2']);
	}
	
	// save a copy for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;
	$dbInfo['ptInfo'] = $ptInfo;
	
	// make insert query string to add new object to DB table
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_VISIT, $dbArgs);
	if (API_DEBUG_MODE) {
		$dbInfo['insertQueryString'] = $insertQueryString;
	}

	// try to add the record to the database
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['insertQueryString'] = $insertQueryString;
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		if (substr($dbInfo['sqlError'], 0, 9) == "Duplicate") {
			// a "duplicate record" error was returned, so update the responee
			$returnValue['httpResponse'] = 409;
			$returnValue['httpReason']	= "Duplicate entry. The visit is already in the database. ".$dbInfo['sqlError'];
		} else if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the responee
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to add the visit. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to add the visit. DB error.";
		}
	} else {
		profileLogCheckpoint($profileData,'POST_RETURNED');
		// free the last query result
		@mysqli_free_result($qResult);
		// create query string for get operation
		$getQueryString = "SELECT * FROM `".
			DB_VIEW_VISIT_GET. "` WHERE `patientVisitID` = '". $ptVisitId. "' ".
			"AND `clinicPatientID` = '" . $requestArgs['clinicPatientID']. "';";
		$returnValue = getDbRecords($dbLink, $getQueryString);
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if ($returnValue['httpResponse'] == 200) {
			// found the new record
			$logData['after'] = $returnValue['data'];
			// adjust return value to reflect POST operation
			$returnValue['httpResponse'] = 201;
			$returnValue['httpReason']	= "Success";
			$logData['logAfterData'] = $returnValue['data'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Database error: Could not retrieve new visit record.";
		}
	}

	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
	// only log performance info on success.
	profileLogClose($profileData, __FILE__, $requestArgs);
    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logsStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);
	return $returnValue;
}
//EOF