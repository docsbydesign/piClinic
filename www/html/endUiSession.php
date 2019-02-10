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
 *	Terminates a clinic UI session and redirect to login page
 *
 *********************/
require_once './shared/dbUtils.php';
require_once './shared/piClinicConfig.php';
require_once './shared/ui_common.php';
require_once './api/api_common.php';
require_once './api/session_common.php';
require_once './api/session_delete.php';
require_once './shared/security.php';
require_once './shared/profile.php';

$profileData = [];
profileLogStart ($profileData);

// get the query paramater data from the request
$sessionInfo = getUiSessionInfo();

// redirect to this page whether successful or not
$errorUrl =
    $redirectUrl = '/clinicLogin.php';

$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);
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