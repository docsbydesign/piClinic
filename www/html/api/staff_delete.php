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
 *	Updates staff resources from the database
 * 		or an HTML error message
 *
 * DELETE: Disables an existing staff record as identified by Username or staffID
 *
 *		Returns:
 *			200: the updated staff record
 *			400: required field is missing
 *			404: no record found that matches the specified id field
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _staff_delete ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();

	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    // token parameter was verified before this function was called.
    $logData = createLogEntry (
        'API',
        __FILE__,
        'staff',
        $_SERVER['REQUEST_METHOD'],
        $apiUserToken, $requestArgs,
        null,
        null,
        null,
        null);

    // check for other required columns
    $staffDbFields = getStaffFieldInfo();

    // this DELETE is really an update as the record is not removed from the table.
    //   this DELETE action just disables the account.
    $missingColumnList = '';
    $requiredFieldFound = false; // only one required field must be present.
    $keyFields = array();
    foreach ($staffDbFields as $field) {
        if ($field[STAFF_DB_REQ_PATCH]) {
            if (empty($requestArgs[$field[STAFF_REQ_ARG]])) {
                if (!empty($missingColumnList)) {
                    $missingColumnList .= ', ';
                }
                $missingColumnList .= $field[STAFF_REQ_ARG];
            } else {
                $keyFields[$field[STAFF_DB_ARG]] = $requestArgs[$field[STAFF_REQ_ARG]];
                $requiredFieldFound = true; // only one required field must be present.
            }
        }
    }

    if ($requiredFieldFound == false) {
		// some required fields are missing so exit
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to deactivate staff account data. At least one of the required field(s): '. $missingColumnList. ' is missing.';
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_PARAMS);
		return $returnValue;
	}

	$patchArgs = cleanStaffStringFields ($requestArgs);

    // make sure the record is currently active
	// create query string for get operation
	$getQueryString = makeStaffQueryStringFromRequestParameters ($keyFields, DB_VIEW_STAFF_GET);
	$testReturnValue = getDbRecords($dbLink, $getQueryString);
	if ($testReturnValue['httpResponse'] !=  200) {
		// can't find the record to patch. It could already be deleted, deactivated, or it could not exist.
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= 'Staff record to deactivate  not found.';
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_NOTFOUND);
		return $returnValue;
	} else {
		// record found so save the before version
		$logData['logBeforeData'] = json_encode($testReturnValue['data']);
	}

    $patchArgs = array();
	// all query parameters except for the key fields are ignored

    $patchArgs['Active'] = 0; // disabled

	$updateKey = '';
	// get lookup key field
	if (isset($keyFields['staffID'])) {
		$updateKey = 'staffID';
        $patchArgs['staffID'] = $keyFields['staffID'];
	} else if (isset($keyFields['username'])) {
		$updateKey = 'username';
        $patchArgs['username'] = $keyFields['username'];
	} else {
		// something got lost.
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$dbInfo['keyFields'] = $keyFields;
			$dbInfo['patchArgs'] = $patchArgs;
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 500;
		$returnValue['httpReason']	= 'Staff update key was lost.';
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_KEY);
		return $returnValue;
	}

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// make update query string from data buffer
	$columnsToUpdate = 0;
	$updateQueryString = format_object_for_SQL_update (DB_TABLE_STAFF, $patchArgs, $updateKey, $columnsToUpdate);
    $dbInfo['updateQueryString'] = $updateQueryString;

	// check query string construction
	if ($columnsToUpdate < 1) {
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$dbInfo['updateQueryString'] = $updateQueryString;
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to deactivate the staff record.';
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_UPDATE);
		return $returnValue;
	}

	if (!empty($updateQueryString)) {
		// try to update the record in the database
		$qResult = @mysqli_query($dbLink, $updateQueryString);
		if (!$qResult) {
			// SQL ERROR
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = CONTENT_TYPE_JSON;
			if (API_DEBUG_MODE) {
				$returnValue['debug'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to deactivate staff record.';
		} else {
			profileLogCheckpoint($profileData,'UPDATE_RETURNED');
			// create query string for get operation
			$getQueryString = "SELECT * FROM `".
				DB_VIEW_STAFF_GET. "` WHERE `".$updateKey."` = '".
				$requestArgs[$updateKey]."';";
			$returnValue = getDbRecords($dbLink, $getQueryString);
			$logData['logAfterData'] = json_encode($returnValue['data']);
			$logData['logStatusCode'] = $returnValue['httpResponse'];
			$logData['logsStatusMessage'] = $returnValue['httpReason'];
			writeEntryToLog ($dbLink, $logData);
			@mysqli_free_result($qResult);
		}
	} else {
		// missing primary key field
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to update record. The staff ID is missing.';
	}
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
