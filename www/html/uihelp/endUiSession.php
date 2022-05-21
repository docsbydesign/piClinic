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
 *	Terminates a clinic UI session and redirect to login page
 *
 *********************/
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../api/session_common.php';
require_once dirname(__FILE__).'/../api/session_delete.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../shared/profile.php';

$profileData = [];
profileLogStart ($profileData);

// get the query paramater data from the request
$sessionInfo = getUiSessionInfo();

// redirect to this page whether successful or not
$errorUrl =
    $redirectUrl = '/clinicLogin.php';
if (!empty($sessionInfo['parameters']['msg'])) {
    // pass message to session close
    $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg' => $sessionInfo['parameters']['msg']]);
}

$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// check for authorization to access this page
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], PAGE_ACCESS_READONLY, $sessionInfo)){
    // show this in the error div
    $requestData['msg'] = MSG_NO_ACCESS;
    $redirectUrl = makeUrlWithQueryParams($errorUrl, $requestData);
    $logError = [];
    $logError['httpResponse'] =  403;
    $logError['httpReason'] = 'User account is not authorized to access this resource.';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'session', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    exit;
}

profileLogCheckpoint($profileData,'DB_OPEN');

// format form parameters for call to post session

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
	case 'POST':
	case 'DELETE':
		$retVal = _session_delete($dbLink, $sessionInfo['token'], $sessionInfo['parameters']);
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$retVal['error']['requestData'] = $sessionInfo['parameters'];
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($sessionInfo['parameters'], $retVal, __FILE__ );
		break;
}
// close any open workflows before destroying the session
closeSessionWorkflow($sessionInfo, __FILE__, $dbLink, $workflowStep = WORKFLOW_STEP_ABANDONED);

// close the DB link until next time
@mysqli_close($dbLink);

// session created successfully so open PHP session
//  and redirect to home page
if (!isset($_SESSION)) {
    session_start();
}
// destroy the session
session_destroy();
// remove all session variables
$_SESSION = [];
// clear the session cookie
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));

// set the appropriate response
if ($retVal['httpResponse'] == 200) {
	header("httpReason: Session ended.");
} else {
	logApiError($sessionInfo['parameters'], $retVal, __FILE__ );
	header("httpReason: Error encountered termiating session.");
}
// redirect to success URL
header("Location: ".$redirectUrl);
profileLogClose($profileData, __FILE__, $sessionInfo['parameters']);
return;
//EOF
