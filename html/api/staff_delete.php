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

function _staff_delete ($dbLink, $requestArgs) {
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
    $logData = createLogEntry ('API',
        __FILE__,
        $requestArgs['token'],
        'session',
        $_SERVER['REQUEST_METHOD'],
        null,
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
		return $returnValue;
	} else {
		// record found so save the before version
		$logData['before'] = json_encode($testReturnValue['data']);
	}
	
    $patchArgs = array();
	// all query parameters except for the key fields are ignored

    $patchArgs['Active'] = 0; // disabled

	$updateKey = '';
	// get lookup key field
	if (isset($keyFields['staffID'])) {
		$updateKey = 'staffID';
        $patchArgs['staffID'] = $keyFields['staffID'];
	} else if (isset($keyFields['Username'])) {
		$updateKey = 'Username';
        $patchArgs['Username'] = $keyFields['Username'];
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
			$logData['after'] = json_encode($returnValue['data']);
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