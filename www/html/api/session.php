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
$retVal = array();

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
	    // No Authorization is required to create a session.
        //  The user's credentials are checked during session creation.
		$retVal = _session_post($dbLink, $apiUserToken, $requestData);
		break;

	case 'GET':
        // Make sure the token is present and properly formatted.
        if (empty( $apiUserToken)) {
            $retVal = formatMissingTokenError($retVal, 'session');
        } else if (!validTokenString( $apiUserToken)) {
            $retVal = logInvalidTokenError($dbLink, $retVal, $apiUserToken, 'session', $logData);
            writeEntryToLog($dbLink, $logData);
        } else {
            // only a valid token is required to GET session info
            $retVal = _session_get($dbLink, $apiUserToken, $requestData);
        }
		break;

    case 'PATCH':
    case 'DELETE':
        // these methods require a valid token
        $retVal['debug']['requestData'] = $requestData;
        if (empty($apiUserToken)){
            // caller does not have a valid security token
            // caller did not pass a security token
            $retVal = formatMissingTokenError ($retVal, 'session');
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
