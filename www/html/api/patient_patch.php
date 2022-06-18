<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  he Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 *  of the Software, and to permit persons to whom the Software is furnished to do
 *  so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 *
 */
/*******************
 *
 *	Updates patient resources from the database
 * 		or an HTML error message
 *
 * PATCH: Modifies one or more fields in an existing patient record as identified by clinicPatientID
 *
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
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

function _patient_patch ($dbLink, $apiUserToken, $requestArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();

	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    // token parameter was verified before this function was called.
    $logData = createLogEntry ('API', __FILE__, 'patient', $_SERVER['REQUEST_METHOD'],  $apiUserToken, null, null, null, null, null);

    // confirm that we have the record ID
	if (empty( $requestArgs["clinicPatientID"])) {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update patient record. The clinicPatientID is missing.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);

        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        return $returnValue;
	}

	// make sure the record is currently active
	// create query string for get operation
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_PATIENT_GET. "` WHERE `clinicPatientID` = '".
		$requestArgs['clinicPatientID']."';";
	$testReturnValue = getDbRecords($dbLink, $getQueryString);
	if ($testReturnValue['httpResponse'] !=  200) {
		// can't find the record to delete. It could already be deleted or it could not exist.
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "Patient record to update not found.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_NOTFOUND);

        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		return $returnValue;
	} else {
		$logData['logBeforeData'] = $testReturnValue['data'];
	}

	// clean leading and trailing spaces from string fields
	$patchArgs = cleanPatientStringFields ($requestArgs);

	// make update query string from data buffer
	$columnsToUpdate = 0;
	$updateQueryString = format_object_for_SQL_update (DB_TABLE_PATIENT, $patchArgs, "clinicPatientID", $columnsToUpdate);
    $dbInfo['updateQueryString'] = $updateQueryString;

	// check query string construction
	if ($columnsToUpdate < 1) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update the patient record. No data fields were included in the request.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_UPDATE);

        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		return $returnValue;
	}
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	if (!empty($updateQueryString)) {
		// try to add the record to the database
		$qResult = @mysqli_query($dbLink, $updateQueryString);
		if (!$qResult) {
			// SQL ERROR
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
			if (API_DEBUG_MODE) {
				$returnValue['debug'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update patient.";
            profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_UPDATE);

            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
		} else {
			profileLogCheckpoint($profileData,'UPDATE_COMPLETE');
			// create query string for get operation
			$getQueryString = "SELECT * FROM `".
				DB_VIEW_PATIENT_GET. "` WHERE `clinicPatientID` = '".
				$requestArgs['clinicPatientID']."';";
			$returnValue = getDbRecords($dbLink, $getQueryString);
			$logData['logAfterData'] = $returnValue['data'];
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
			if (is_object($qResult)) { @mysqli_free_result($qResult); }
		}
	} else {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update record. The patient ID is missing.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_KEY);
	}
    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
