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
 *	Handles clinicLogin form to create session and redirect to first page
 *
 *********************/
require_once './shared/piClinicConfig.php';
require_once './shared/dbUtils.php';
require_once './shared/logUtils.php';
require_once './shared/ui_common.php';
require_once './api/api_common.php';
require_once './api/session_common.php';
require_once './api/session_post.php';
require_once './api/session_delete.php';
require_once './shared/profile.php';

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
	$_SESSION['username'] = $retVal['data']['username'];
	$_SESSION['sessionLanguage'] = $retVal['data']['sessionLanguage'];

	// redirect to success URL
	header('httpReason: Session created.');
	header('Location: '.$successUrl);
} else {
	// login failure
	$errorUrl .= ((strpos($errorUrl, '?') === FALSE) ? '?' : '&' );
	$errorUrl .= 'msg=LOGIN_FAILURE';
	$retVal['error']['redirectUrl'] = $errorUrl;
	logApiError($sessionInfo['parameters'], $retVal, __FILE__ );
	header('httpReason: username or password not valid.');
	header('Location: '.$errorUrl);
}
profileLogClose($profileData, __FILE__, $sessionInfo['parameters']);
return;
//EOF
