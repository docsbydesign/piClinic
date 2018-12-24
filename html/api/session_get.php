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
 *	Retrieves a user session
 * 		or an HTML error message
 *
 *	GET: Returns session information
 *
 *		Query paramters:
 *			'token' - the session token
 *		  looks up and checks these $_SERVER values match those of the session
 *			REMOTE_ADDR - the IP of the client making the request
 *			HTTP_USER_AGENT (if present) - the USER AGENT string of the client making the reaquest
 *
 *		Response:
 *			Session data object
 *			
 *		Returns:
 *			200: the session object matching the token
 *			400: required field is missing or $_SERVER values did not match
 *			404: no session object with that token found
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

/*
 *  Queries a token and returns its access if it's valid
 */
function _session_get ($dbLink, $requestArgs) {
    /*
     *      Initialize profiling if enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);

	// format return value
	$returnValue = array();
	$returnValue['contentType'] = CONTENT_TYPE_HTML;
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= "Resource not found.";
	$returnValue['format'] = 'json';
	$returnValue['count'] = 0;
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

	// Make sure the token is present and properly formatted.
	if (empty($requestArgs['token'])) {
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason'] = 'Required parameter is missing.';
		return $returnValue;
    } else if (!validTokenString($requestArgs['token'])) {
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to access user session. Invalid token.";
        $logData['LogStatusCode'] = $returnValue['httpResponse'];
        $logData['LogStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        return $returnValue;
	}

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// create query string for get operation
	$getQueryString = 'SELECT * FROM `'. DB_TABLE_SESSION . '` WHERE `Token` = \''. $requestArgs['token'] . '\';';
    $dbInfo ['queryString'] = $getQueryString;

	// get the session record that matches--there should be only one
	$returnValue = getDbRecords($dbLink, $getQueryString);
	
	if ($returnValue['count'] == 0) {
		//return 404
		// add debug info to the list
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		return $returnValue;
	}
	
	if ($returnValue['count'] == 1) {
		// Get the session info and return it
		$sessionInfo = array();
		$validSession = true;
		
		// confirm that the session is still valid and hasn't expired
		if (!$returnValue['data']['LoggedIn']) {
			// user is logged out
			$validSession = false;
		}
		
		$sessionExpirationTime = strtotime($returnValue['data']['ExpiresOnDate']);
		$timeNow = time();
		if ($timeNow > $sessionExpirationTime) {
			// session expired
			$validSession = false;
		}
			
		// confirm that this request is from the IP that created the token
		if ($_SERVER['REMOTE_ADDR'] != $returnValue['data']['SessionIP']) {
			// token is being used from another IP
			$validSession = false;			
		}
				
		// confirm that this request has the same User Agent string as the browser that created the token
		$sessionUA = (!empty($returnValue['data']['SessionUA']) ? $returnValue['data']['SessionUA'] : '');
	    $localUA = (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
        if ($localUA != $sessionUA) {
            // token could be used from another browser
            $dbInfo['thisUserAgent'] = $localUA ;
            $dbInfo['sessionData'] = $returnValue['data'];
            $validSession = false;
        }

		$sessionInfo['contentType'] = CONTENT_TYPE_JSON;
		$sessionInfo['count'] = 1;
		if ($validSession) {
			$sessionInfo['data']['Token'] = $returnValue['data']['Token'];
			$sessionInfo['data']['Username'] = $returnValue['data']['Username'];
			$sessionInfo['data']['AccessGranted'] = $returnValue['data']['AccessGranted'];
            $sessionInfo['httpResponse'] = 200;
            $sessionInfo['httpReason'] = 'Success';
		} else {
			// this is a stale token so no access anymore
			$sessionInfo['data']['Token'] = 0;
			$sessionInfo['data']['Username'] = '';
			$sessionInfo['data']['AccessGranted'] = 0;
            $sessionInfo['httpResponse'] = 404;
            $sessionInfo['httpReason'] = 'Session not found.';
		}
		// and return here
		profileLogClose($profileData, __FILE__, $requestArgs);

        if (API_DEBUG_MODE) {
            $sessionInfo['debug'] = $dbInfo;
        }

        return $sessionInfo;
	}
	
	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
		
	return $returnValue;
}
//EOF