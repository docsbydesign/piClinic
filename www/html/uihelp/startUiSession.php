<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
 *	Handles clinicLogin form to create session and redirect to first page
 *
 *********************/
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../shared/logUtils.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../api/session_common.php';
require_once dirname(__FILE__).'/../api/session_post.php';
require_once dirname(__FILE__).'/../api/session_delete.php';
require_once dirname(__FILE__).'/../shared/profile.php';

$profileData = [];
profileLogStart ($profileData);

$sessionInfo = getUiSessionInfo();

if (!empty($sessionInfo['token'])){
	header('DEBUG_OldSessionFound: '.session_id());
    $retVal = _session_delete($dbLink, $sessionInfo['token'], $sessionInfo['parameters']);
	// whatever happens, clear out the existing session to start cleanly
	// destroy the session
	session_destroy();
	// remove all session variables
	$_SESSION = [];
	// clear the session cookie
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
}

// format form parameters for call to post session
$requestData = array();
$requestData['username'] = $sessionInfo['parameters']['username'];
$requestData['password'] = $sessionInfo['parameters']['password'];

$successUrl = '/clinicDash.php';
$errorUrl = '/clinicLogin.php';

$dbLink = _openDBforUI($requestData, $errorUrl);
profileLogCheckpoint($profileData,'DB_OPEN');

// no need to check authorization because the session initialization will do that

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$retVal = _session_post($dbLink, null, $requestData);
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json';
		// log error
		$retVal['error']['requestData'] = $requestData;
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($sessionInfo['parameters'], $retVal, __FILE__ );
		break;
}
// close the DB link until next time
@mysqli_close($dbLink);

if ($retVal['httpResponse'] == 201) {
	// session created successfully so open PHP session
	//  and redirect to home page
    if (empty(session_id())){
        session_start();
    }
    $_SESSION['token'] = $retVal['data']['token'];
    $_SESSION['accessGranted'] = $retVal['data']['accessGranted'];
	$_SESSION['username'] = $retVal['data']['username'];
	$_SESSION['sessionLanguage'] = $retVal['data']['sessionLanguage'];

	// redirect to success URL
	header('httpReason: Session created.');
	header('Location: '.$successUrl);
} else {
	// login failure
	$errorUrl .= ((strpos($errorUrl, '?') === FALSE) ? '?' : '&' );
	$errorUrl .= 'msg='.MSG_LOGIN_FAILURE;
	$retVal['error']['redirectUrl'] = $errorUrl;
	logApiError($sessionInfo['parameters'], $retVal, __FILE__ );
	header('httpReason: username or password not valid.');
	header('Location: '.$errorUrl);
}

profileLogClose($profileData, __FILE__, $sessionInfo['parameters']);
return;
//EOF
