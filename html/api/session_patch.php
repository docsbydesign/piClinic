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
 *	Creates/Returns session resources from the database 
 * 		or an HTML error message
 *
 *	PATCH: Modify an existing session
 * 		input data:
 *          'token' - current token of session to modify
 *			'sessionLangauge' - the new session language (sessionLangauge)
 *			'sessionClinicPublicID' - the new default clinic (sessionClinicPublicID)
 *
 *      note that this method can only change these values (sessionLangauge, sessionClinicPublicID)
 *          of the session identified by token
 *
 *		Response: 
 *			Session data object
 *
 *		Returns:
 *			201: the new session was  created
 *			400: required field is missing
 *			409: a session already exists (existing session returned)
 *			500: server error information
 *
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *  Checks the username and password and, if they are valid,
 *    creates a new user session.
 */
function _session_patch ($dbLink, $requestArgs) {
    /*
     *      Initialize profiling if enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// Format return value and dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API', __FILE__, 'session', $_SERVER['REQUEST_METHOD'], $requestArgs['token'], null, null, null, null, null);

	// check for required parameters
    // must have at least one, and can have both.
	$requiredPatientColumns = [
		"sessionLanguage"
		,"sessionClinicPublicID"
		];

	$paramCount = 0;
	$missingColumnList = "";
	foreach ($requiredPatientColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ", ";
			}
			$missingColumnList .= $column;
		} else {
		    $paramCount += 1;
        }
	}
	
	if ($paramCount == 0) {
		// some required fields are missing so exit
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to update session session. None of the required field(s): ". $missingColumnList. " were found.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		return $returnValue;
	}

	// Don't save the whole query string because it has the password in plain text and the log isn't encrypted
    //  but at this point we know the username is present so we can save that in the log.
    $logData['logQueryString'] = $_SERVER['QUERY_STRING'];
    $dbInfo = array();
    $dbInfo['requestArgs'] = $requestArgs;

    // Make sure that the token returns a valid session record
	$userInfo = null;
	$getQueryString = "SELECT * FROM `".
		DB_TABLE_SESSION. "` WHERE `Token` = '".
		$requestArgs['token']."';";
	$dbInfo['$getQueryString'] = $getQueryString;

	$returnValue = getDbRecords($dbLink, $getQueryString);
    $dbInfo['$data'] = $returnValue['data'];

	if ($returnValue['httpResponse'] != 200) {
        // the specified user does not exist in the database
		//  return an error
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "The token did not locate a valid session. Check the token and try again.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		return $returnValue;
	} else {
		if ($returnValue['count'] == 1) {
			$userInfo = $returnValue['data'];
		} else {
			// more than one record is a server error because Username is a unique key
			if (API_DEBUG_MODE) {
                $returnValue['debug'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Multiple sessions were found with that token. Check the token and try again.";
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
			return $returnValue;
		}
	}

    $dbArgs = array();
    if (!empty($requestArgs['sessionLanguage'])) {
        if (isSupportedLanguage($requestArgs['sessionLanguage'])) {
            $dbArgs['sessionLanguage'] = $requestArgs['sessionLanguage'];
        }
    }

    // test the clinic ID
    if (!empty($requestArgs['sessionClinicPublicID'])) {
        // check if the clinic ID is valid
        $clinicQueryString = 'SELECT `PublicID` FROM '. DB_TABLE_CLINIC . ' WHERE TRUE;';
        $dbInfo['clinicQueryString'] = $clinicQueryString;
        $clinicResult = getDbRecords($dbLink, $clinicQueryString);
        if ($clinicResult['count'] >= 1) {
            // scan the list for a match
            foreach ($clinicResult['data'] as $idToCheck) {
                if ($requestArgs['sessionClinicPublicID'] == $idToCheck['PublicID']) {
                    $dbArgs['sessionClinicPublicID'] = $idToCheck['PublicID'];
                    break;
                }
            }
        }
    }

    if (count($dbArgs) == 0) {
        // no valid parameters were passed.
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "No valid parameter values were provided. Check the parameter values and try again..";
        $returnValue['debug'] = $dbInfo;
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        return $returnValue;
    }

    // add the token value
    $dbArgs['token'] = $requestArgs['token'];

    // here we have a valid username and password so create a session
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// save a copy for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;
    $updateColumns = 0;
    $insertQueryString = format_object_for_SQL_update (DB_TABLE_SESSION, $dbArgs, 'token', $updateColumns);
	$dbInfo['insertQueryString'] = $insertQueryString;

	// try to update the session in the database
	
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update the session. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update the session. DB error.";
		}
	} else {
	    // successful creation
		profileLogCheckpoint($profileData,'UPDATE_RETURNED');

		// get the new session data to return
        // create query string for get operation
        $getQueryString = 'SELECT * FROM `'. DB_TABLE_SESSION . '` WHERE `token` = \''. $requestArgs['token'] . '\';';
        $dbInfo ['queryString'] = $getQueryString;
        // get the session record that matches--there should be only one
        $getReturnValue = getDbRecords($dbLink, $getQueryString);
        if (!empty($returnValue['data'])) {
            unset ($returnValue['data']);
            $returnValue['data'] = array();
        }
        if ($getReturnValue['count'] == 1) {
            $sessionInfo['data']['token'] = $getReturnValue['data']['token'];
            $sessionInfo['data']['uername'] = $getReturnValue['data']['username'];
            $sessionInfo['data']['accessGranted'] = $getReturnValue['data']['accessGranted'];
            $sessionInfo['data']['sessionLanguage'] = $getReturnValue['data']['sessionLanguage'];
            $sessionInfo['data']['sessionClinicPublicID'] = $getReturnValue['data']['sessionClinicPublicID'];
            $sessionInfo['httpResponse'] = 200;
            $sessionInfo['httpReason'] = 'Success';
        } else {
            // this is a stale token so no access anymore
            $sessionInfo['data']['token'] = 0;
            $sessionInfo['data']['username'] = '';
            $sessionInfo['data']['accessGranted'] = 0;
            $sessionInfo['data']['sessionLanguage'] = '';
            $sessionInfo['data']['sessionClinicPublicID'] = '';
            $sessionInfo['httpResponse'] = 404;
            $sessionInfo['httpReason'] = 'Session not found.';
        }
        @mysqli_free_result($qResult);
	}

	$returnValue['contentType'] = CONTENT_TYPE_JSON;
	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
	$logData['logAfterData'] = json_encode($returnValue['data']);
    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
// EOF