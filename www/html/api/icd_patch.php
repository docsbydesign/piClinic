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
 *	Updates ICD resources from the database
 * 		or an HTML error message
 *
 * PATCH: Modifies last used date of the specified code (for MRU date sort)
 *
 * 		input data:
 *   		`icd10index` - (required) index of code to update LastUsedTime field with current time
 *
 *		Returns:
 *			200: the updated icd record
 *			400: required field is missing
 *			404: no record found that matches the specified id field
 *			500: server error information
  *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _icd_patch ($dbLink, $requestArgs, $apiUserToken) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;
    // token parameter was verified before this function was called.
    $logData = createLogEntry ('API',
        __FILE__,
        'icd',
        $_SERVER['REQUEST_METHOD'],
        $apiUserToken,
        $requestArgs,
        null,
        null,
        null,
        null);
	
	// check for required columns and save the key field
	$requiredIcdColumns = [
		'icd10index'	// code, exact max
		,'language'		// language to update
		];

	$missingColumnList = '';
	$requiredFieldCount = 0;
	$keyFields = [];
	foreach ($requiredIcdColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ', ';
			}
			$missingColumnList .= $column;
		} else {
			$keyFields[$column] = $requestArgs[$column];
			$requiredFieldCount += 1;
		}	
	}
	
	if ($requiredFieldCount != 2) {
		// some required fields are missing so exit
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to update ICD-10 data. At least one of the required field(s): '. $missingColumnList. ' is missing.';
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_PARAMS);
        return $returnValue;
	}
	
	$patchArgs = $keyFields; // load the key values
	// the values to update
	$patchArgs['useCount'] = '`useCount` + 1';	
	$patchArgs['lastUsedDate'] = 'NOW()';
	
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');
	
	// make update query string from data buffer
	$columnsToUpdate = 0;
	$updateQueryString = format_object_for_multikey_SQL_update (DB_TABLE_ICD10, $patchArgs,
		$requiredIcdColumns, $columnsToUpdate, FALSE);
	
	// check query string construction
	if ($columnsToUpdate < 1) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$dbInfo['updateQueryString'] = $updateQueryString;
			$dbInfo['patchArgs'] = $patchArgs;
			$dbInfo['keyFields'] = $keyFields;
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to update the ICD-10 record. No data fields to update were included in the request.';
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_UPDATE);
        return $returnValue;
	}

	if (!empty($updateQueryString)) {
		// try to update the record in the database
		$qResult = @mysqli_query($dbLink, $updateQueryString);
		if (!$qResult) {
			// SQL ERROR
			$dbInfo['updateQueryString'] = $updateQueryString;
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
			if (API_DEBUG_MODE) {
				$returnValue['debug'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to update ICD-10 record.';
		} else {
			profileLogCheckpoint($profileData,'UPDATE_RETURNED');
			// create query string for get operation			
			$getQueryString = "SELECT * FROM `".
				DB_VIEW_ICD10_GET. "` WHERE `icd10index` = '".
				$requestArgs['icd10index']."' AND `language` = '".$requestArgs['language']."';";
			$returnValue = getDbRecords($dbLink, $getQueryString);
            $logData['logAfterData'] = json_encode($returnValue['data']);
            writeEntryToLog ($dbLink, $logData);
			@mysqli_free_result($qResult);
		}			
	} else {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to update record. The ICD-10 entry key is missing.';
	}
    writeEntryToLog ($dbLink, $logData);
    profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF