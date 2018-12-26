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
 *	POST: Adds a new user session to the database
 * 		input data:
 *			'Username' - The username of the user opening a session
 *			'Password' - The password of the user opening a session
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
 *	GET: Returns session information
 *
 *		Query paramters:
 *			'Token' - the session token
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
 * DELETE: deletes the specified session
 *		Query paramters:
 *			'Token' - the session token
 *		  looks up and checks these $_SERVER values match those of the session
 *			REMOTE_ADDR - the IP of the client making the request
 *			HTTP_USER_AGENT (if present) - the USER AGENT string of the client making the reaquest
 *
 *		Returns:
 *			200: No data
 *			400: required field is missing or $_SERVER values did not match
 *			404: no session with that token found
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once '../shared/logUtils.php';
require_once '../shared/profile.php';
require_once '../shared/security.php';
require_once 'api_common.php';
require_once 'session_common.php';
require_once 'session_post.php';
require_once 'session_get.php';
require_once 'session_delete.php';
require_once 'session_patch.php';

/*
 *      Initialize profiling if enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// Get the query paramater data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();
$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

$logData = createLogEntry ('API',
    __FILE__,
    'session',
    $_SERVER['REQUEST_METHOD'],
    null,
    $_SERVER['QUERY_STRING'],
    json_encode(getallheaders ()),
    null,
    null,
    null
    );

// None of these methods require an access check
switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$retVal = _session_post($dbLink, $apiUserToken, $requestData);
		break;
	
	case 'GET':
		$retVal = _session_get($dbLink, $apiUserToken, $requestData);
		break;

    case 'PATCH':
    case 'DELETE':
        // these methods require a valid token
        $retVal['debug']['requestData'] = $requestData;
        $retVal['debug']['reqHeaders'] =  getallheaders ();
        if (empty($apiUserToken)){
            // caller does not have a valid security token
            // caller did not pass a security token
            $retVal = formatMissingTokenError ($retVal, 'session');
            $logData['logStatusCode'] = $retVal['httpResponse'];
            $logData['logStatusMessage'] = $retVal['httpReason'];
            writeEntryToLog($dbLink, $logData);
        } else {
            // token is OK so we can continue
            if (!validTokenString($apiUserToken)) {
                $retVal = logInvalidTokenError ($dbLink, $retVal, $apiUserToken, 'session', $logData);
            } else {
                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'PATCH':
                        // any user with a valid token can access this method
                        $retVal = _session_patch($dbLink, $apiUserToken,  $requestData);
                        break;
                    case 'DELETE':
                        // any user with a valid token can access this method
                        $retVal = _session_delete($dbLink, $apiUserToken, $requestData);
                        break;
                }
            }
        }
        break;

	default:
		$retVal['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$retVal['error'] = $requestData;
		}
		$retVal['httpResponse'] = 405;
        $retVal['httpReason'] = $_SERVER['REQUEST_METHOD'] . ' method is not supported.';
		break;
}
// close the DB link until next time0
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
exit;
//EOF