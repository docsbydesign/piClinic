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
 *	Replaces visit resource values 
 * 		or returns an HTML error message
 *
 *	PATCH: Replaces details of a patient visit in the database
 * 		input data:
 *			One of these is required
 *   		`visitID` - Returns a specific visit record
 *			`patientVisitID` - Returns a specific visit record
 *		these are optional (as you want to change them):
 *
 *		Returns:
 *			200: the visit entry was updated
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

function _visit_patch ($dbLink, $apiUserToken, $requestArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    $returnValue = array();

    // token parameter was verified before this function was called.
    $logData = createLogEntry ('API', __FILE__, 'visit', $_SERVER['REQUEST_METHOD'], $apiUserToken, $requestArgs, null, null, null, null);

    // check for other required columns
	$requiredPatientColumns = [
		'visitID'
		,'patientVisitID'
		];

	// TODO: Refactor this out (NOTE: this one is an "OR")
	$missingColumnList = '';
	// make sure one of the ID columns is present
	foreach ($requiredPatientColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ", ";
			}
			$missingColumnList .= $column;
		} else {
			// Found at least one required field, 
			//  so clear the missing column list and continue
			$missingColumnList = '';
			break;
		}		
	}
	
	if (!empty($missingColumnList)) {
		// some required fields are missing so exit
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to update the patient visit. At least one of these required field(s) is missing: ". $requiredPatientColumns. " ";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	
	// make sure visitID parameter is an integer
	if (isset($requestArgs['visitID']) && !is_numeric($requestArgs['visitID'])) {
		// some required fields are missing so exit
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "visitID parameter must be a number.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	
	// at this point we should have a valid unique visit record identifier

	// get the current visit record using the ID that we have
	$getQueryString = "SELECT * FROM `".
		DB_TABLE_VISIT. "` WHERE ";
	if (isset($requestArgs['visitId'])) {
		$getQueryString .= "`visitId` = ". $requestArgs['visitId'] . " AND ";
	}
	if (isset($requestArgs['patientVisitID'])) {
		$getQueryString .= "`patientVisitID` = '". $requestArgs['patientVisitID'] . "' AND ";
	}
	$getQueryString .= "TRUE ".DB_QUERY_LIMIT.";";
	
	$returnValue = getDbRecords($dbLink, $getQueryString);
	if ($returnValue['httpResponse'] != 200) {
		// the specified visit record was not found,
		//  return an error
		if (API_DEBUG_MODE) {
			$returnValue['debug']['dupCheck']['$getQueryString'] = $getQueryString;
			if (isset($returnValue['data'])){
				$returnValue['debug']['dupCheck']['$data'] = $returnValue['data'];
			}
		} else {
			// remove internal file path from object when not debug
			unset($returnValue['data']['ImagePath']);
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "The patient does not have the specified visit to update.";
		return $returnValue;
	} else {
		// save orig record
		if ($returnValue['count'] > 0) {
			$logData['before'] = $returnValue['data'];			
		}
	}	
	
	// At this point the request has passed all the validation tests 
	//  so prepare the update record
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// create the array of values to send to PATCH method
	$dbArgs = array();
	
	// only copy the values from the query parameters/data
	// 	that can be updated and ignore those that shouldn't be changed.
	$updateParams = [
		// 'staffID'
		'staffName'
        ,'staffUsername'
        ,'staffPosition'
		,'deleted'
		,'visitType'
		,'visitStatus'
		,'primaryComplaint'
		,'secondaryComplaint'
		,'dateTimeIn'
		,'dateTimeOut'
        ,'payment'
		// 'patientNationalID' // This shouldn't change after object creation
		// 'patientFamilyID' // This shouldn't change after object creation
		// 'patientID' 	// This shouldn't change after object creation
		// 'clinicPatientID' // This shouldn't change after object creation
		// 'patientVisitID' // This shouldn't change after object creation
		// 'patientNameLast' 	// This shouldn't change after object creation
		// 'patientNameFirst' 	// This shouldn't change after object creation
		// 'patientSex' 	// This shouldn't change after object creation
		// 'patientBirthDate' 	// This shouldn't change after object creation
		// 'patientHomeAddress1' 	// This shouldn't change after object creation
		// 'patientHomeAddress2' 	// This shouldn't change after object creation
		// 'patientHomeNeighborhood' 	// This shouldn't change after object creation
		// 'patientHomeCity' 	// This shouldn't change after object creation
		// 'patientHomeCounty' 	// This shouldn't change after object creation
		// 'patientHomeState' 	// This shouldn't change after object creation
		,'diagnosis1'
		,'condition1'
		,'diagnosis2'
		,'condition2'
		,'diagnosis3'
		,'condition3'
		,'referredTo'
		,'referredFrom'
		];
	
	foreach ($updateParams as $fieldName) {
		if (isset($requestArgs[$fieldName])) {
			$dbArgs[$fieldName] = trim($requestArgs[$fieldName]);
		}
	}

    // $dbArgs = cleanVisitStringFields ($requestArgs);

    // pick the update key with preference to the visitID
	$visitUpdateKey = '';
	if (isset($requestArgs['visitID'])) {
		$dbArgs['visitID'] = $requestArgs['visitID'];
		$visitUpdateKey = 'visitID';
	} else if (isset($requestArgs['patientVisitID'])) {
		$dbArgs['patientVisitID'] = $requestArgs['patientVisitID'];
		$visitUpdateKey = 'patientVisitID';
	}
	
	// save a copy of the values for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;	
	
	// make an update query string to modify the object in the DB table
	$columnCount = 0;
	$updateQueryString = format_object_for_SQL_update (DB_TABLE_VISIT, $dbArgs, $visitUpdateKey, $columnCount);
	if (API_DEBUG_MODE) {
		$dbInfo['updateQueryString'] = $updateQueryString;
	}
	if ($columnCount == 0) {
		// something  happened while creating the update SQL query and it has no fields to modify
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 500;
		$returnValue['httpReason']	= "Visit resource could not be updated. No fields to update were found in the request.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_UPDATE);
		return $returnValue;
    }

	profileLogCheckpoint($profileData,'UPDATE_READY');
	// try to update the record in the database
	$qResult = @mysqli_query($dbLink, $updateQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (!empty($dbInfo['sqlError'])) {
			// some SQL error was returned, so update the responee
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update the visit resource. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to add the visit resource. DB error.";
		}
	} else {
		profileLogCheckpoint($profileData,'UPDATE_RETURNED');
		@mysqli_free_result($qResult);
		// create query string to get the updated record from the database
		$getQueryString = "SELECT * FROM `".DB_VIEW_VISIT_EDIT_GET. "` WHERE ";
			$getQueryString .= "`".	$visitUpdateKey. "` = '".$dbArgs[$visitUpdateKey]."';";
		
		$returnValue = getDbRecords($dbLink, $getQueryString);
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if ($returnValue['httpResponse'] == 200) {
			// found the updated record
			// write the log data
			if ($returnValue['count'] > 0) {
				$logData['after'] = $returnValue['data'];				
			}
			$returnValue['httpResponse'] = 200;
			$returnValue['httpReason']	= "Success";
            $logData['logAfterData'] = $returnValue['data'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Database error: Could not retrieve updated visit record.";
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