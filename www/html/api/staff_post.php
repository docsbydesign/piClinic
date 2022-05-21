<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
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
 *	Creates/Returns staff resources from the database
 * 		or an HTML error message
 *
 *	POST: Adds a new staff record to the database
 * 		input data:
 *			`MemberID` - (Optional) Staff ID issued by clinic.
 *			`Username` - (Required) Staff's Username (unique)
 *   		`NameLast` - (Required) Staff's last name(s)
 *   		`NameFirst` - (Required) Staff's first name
 *   		`Position` - (Required) Clinic role
 *   		`Password` - (Required) Stored as hash of user's password
 *   		`ContactInfo` - (Optional) Staff's email or phone number
 *   		`AltContactInfo` - (Optional) Staff's Additional/alternate email or phone number
 *   		`AccessGranted` - (Required) Level of access to clinic DB info
 *
 *		Returns:
 *			201: the new staff record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _staff_post ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();

	$dbInfo = array();
	$dbInfo['requestArgs'] = $requestArgs;

	// token parameter was verified before this function was called.
    $logData = createLogEntry ('API', __FILE__, 'staff', $_SERVER['REQUEST_METHOD'], $apiUserToken, $requestArgs, null, null, null, null);

    // Create a list of missing required fields
    $missingColumnList = "";
    $staffDbFields = getStaffFieldInfo();
    foreach ($staffDbFields as $reqField) {
        if ($reqField[STAFF_DB_REQ_POST]) {
            if (empty($requestArgs[$reqField[STAFF_REQ_ARG]])) {
                if (!empty($missingColumnList)) {
                    $missingColumnList .= ", ";
                }
                $missingColumnList .= $reqField[STAFF_REQ_ARG];
            }
        }
    }

	if (!empty($missingColumnList)) {
		// some required fields are missing so exit
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to add staff record. Required staff record field(s): '. $missingColumnList. ' are missing.';
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}

    // clean leading and trailing spaces from string fields and remove token
	$postArgs = cleanStaffStringFields ($requestArgs);

    // if a password string is present, hash the plain text before updating
    if (!empty($postArgs['password'])) {
        $postArgs['password'] = password_hash($postArgs['password'], PASSWORD_DEFAULT);
    }

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// make insert query string to add new object
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_STAFF, $postArgs);
    $dbInfo['insertQueryString'] = $insertQueryString;
	// try to add the record to the database1
	$qResult = @mysqli_query($dbLink, $insertQueryString);
    // create query string for get operation
    profileLogCheckpoint($profileData,'POST_RETURNED');

	if (!$qResult) {
		// SQL ERROR
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		if (substr($dbInfo['sqlError'], 0, 9) == 'Duplicate') {
			// a 'duplicate record' error was returned, so update the responee
			$returnValue['httpResponse'] = 409;
			$returnValue['httpReason']	= 'Duplicate entry. The Username is already in the database. '.$dbInfo['sqlError'];
		} else if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to add the staff record. '.$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to add staff record.';
		}
	} else {
	    //the post was successful so get the new record from the DB and return it.
		$getQueryString = "SELECT * FROM `".
			DB_VIEW_STAFF_GET. "` WHERE `Username` = '".
			$postArgs['username']."';";
        $dbInfo['getQueryString'] = $getQueryString;

		$returnValue = getDbRecords($dbLink, $getQueryString);
		if ($returnValue['httpResponse'] == 200) {
			// found the new record
			$logData['logAfterData'] = json_encode($returnValue['data']);
			// adjust return value to reflect POST operation
			$returnValue['httpResponse'] = 201;
			$returnValue['httpReason']	= 'Success';
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logsStatusMessage'] = $returnValue['httpReason'];
			writeEntryToLog ($dbLink, $logData);
		} else {
            $dbInfo['sqlError'] = @mysqli_error($dbLink);
        }
		@mysqli_free_result($qResult);
	}
    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
