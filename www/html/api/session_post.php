<?php
/*
 *
 * Copyright (c) 2018 by Robert B. Watson
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
 *	Creates/Returns session resources from the database
 * 		or an HTML error message
 *
 *	POST: Adds a new user session to the database
 * 		input data:
 *			'username' - The username of the user opening a session
 *			'password' - The password of the user opening a session
 *		  looks up and saves these $_SERVER values
 *			REMOTE_ADDR - the IP of the client making the request
 *			HTTP_USER_AGENT (if present) - the USER AGENT string of the client making the reaquest
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
function _session_post ($dbLink, $apiUserToken, $requestArgs) {
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
    $logData = createLogEntry ('API', __FILE__, 'session', $_SERVER['REQUEST_METHOD'], $requestArgs['username'], null, null, null, null, null);

	// check for required parameters
	$requiredPatientColumns = [
		"username"
		,"password"
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
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to create new session. Required field(s): ". $missingColumnList. " are missing.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}

	// Don't save the whole query string because it has the password in plain text and the log isn't encrypted
    //  but at this point we know the username is present so we can save that in the log.
    $logData['logQueryString'] = 'username='. $requestArgs['username'];
    $dbInfo = array();

    // Make sure that the Username returns a valid user record
	$userInfo = null;
	$getQueryString = "SELECT * FROM `".
		DB_TABLE_STAFF. "` WHERE `Username` = '".
		$requestArgs['username']."';";
	$dbInfo['$getQueryString'] = $getQueryString;

	$staffResponse = getDbRecords($dbLink, $getQueryString);
    $dbInfo['$data'] = $staffResponse['data'];

	if ($staffResponse['httpResponse'] != 200) {
        // the specified user does not exist in the database
		//  return an error
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "The username is not in the system. Check the username and try again.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_NOTFOUND);
		return $returnValue;
	} else {
		if ($staffResponse['count'] == 1) {
			$userInfo = $staffResponse['data'];
		} else {
			// more than one record is a server error because Username is a unique key
			if (API_DEBUG_MODE) {
                $returnValue['debug'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Multiple usernames were found in the system. Check the username and try again or contact the administrator.";
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
            profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_KEY);
			return $returnValue;
		}
	}

	if (!$userInfo['active']) {
		// account has been disabled
		$returnValue['httpResponse'] = 401;
		$returnValue['httpReason']	= 'Account is disabled.';
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_DELETED);
		return $returnValue;
	}

	// At this point we have a valid user and request, which have passed
    // all the validation tests, so check the password

	if (!password_verify($requestArgs['password'], $userInfo['password'])) {
        $dbInfo['passArg'] = $requestArgs['password'];
        $dbInfo['passHash'] = password_hash($requestArgs['password'], PASSWORD_DEFAULT);
        $dbInfo['passSaved'] = $userInfo['password'];
		// password does not match
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 401;
		$returnValue['httpReason']	= 'Password does not match the password saved for this user.';
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_TOKEN);
		return $returnValue;
	}

	// here we have a valid username and password so create a session
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// Build the DB request
	$dbArgs = array();
	$dbArgs['token'] = guidString ('_');
	$dbArgs['sessionIP'] = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
	$dbArgs['sessionUA'] = (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
	$dbArgs['username'] = $userInfo['username'];
	$dbArgs['loggedIn'] = 1;
	$dbArgs['accessGranted'] = $userInfo['accessGranted'];
	$dbArgs['sessionLanguage'] = $userInfo['preferredLanguage'];
    $dbArgs['sessionClinicPublicID'] = (!empty($userInfo['preferredClinicPublicID']) ? $userInfo['preferredClinicPublicID'] : null);
	$now = new DateTime();
	$dbArgs['createdDate'] = $now->format('Y-m-d H:i:s');
	// create expiration date as tomorrow for now.
	$later = $now;
	$later->modify('+1 day');
	$dbArgs['expiresOnDate'] = $later->format('Y-m-d H:i:s');

	// save a copy for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;

	// make insert query string to add new object to DB table
	profileLogCheckpoint($profileData,'POST_READY');
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_SESSION, $dbArgs);
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
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		if (substr($dbInfo['sqlError'], 0, 9) == "Duplicate") {
			// a "duplicate record" error was returned, so update the responee
			$returnValue['httpResponse'] = 409;
			$returnValue['httpReason']	= "Duplicate entry. The session already exists in the database. ".$dbInfo['sqlError'];
		} else if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to create a new session. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to create a new session. DB error.";
		}
	} else {
	    // successful creation
		profileLogCheckpoint($profileData,'POST_RETURNED');
		$returnValue['data'] = $dbArgs;
		$returnValue['count'] = 1;
		$returnValue['httpResponse'] = 201;
		$returnValue['httpReason']	= "New session created.";

		// update the user record to show the new login.
		$updateQueryString = "UPDATE `". DB_TABLE_STAFF. "` ".
    		"SET `LastLogin`='".$now->format('Y-m-d H:i:s')."' ".
	    	"WHERE `staffID` = '".$userInfo['staffID']."';";
        $dbInfo['updateQueryString'] = $updateQueryString;

		// try to update the record to the database
		$qResult = @mysqli_query($dbLink, $updateQueryString);
		if (!$qResult) {
			// SQL ERROR
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = CONTENT_TYPE_JSON;
			if (API_DEBUG_MODE) {
				$returnValue['debug'] = $dbInfo;
			}
			if (!empty($dbInfo['sqlError'])) {
				$returnValue['httpReason']	.= " Unable to update user last login time. ".$dbInfo['sqlError'];
			} else {
				$returnValue['httpReason']	.= " Unable to update user last login time.";
			}
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
