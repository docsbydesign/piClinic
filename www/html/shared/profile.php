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
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header('Content-Type: application/json; charset=utf-8;');
    header("HTTP/1.1 404 Not Found");
    echo <<<MESSAGE
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
MESSAGE;
    echo "\n<p>The requested URL " . $_SERVER['PHP_SELF'] ." was not found on this server.</p>\n";
    echo "<hr>\n";
    echo '<address>'. apache_get_version() . ' Server at ' . $_SERVER['SERVER_ADDR'] . ' Port '. $_SERVER['SERVER_PORT'] . "</address>\n";
    echo "</body></html>\n";
    exit(0);

}
require_once dirname(__FILE__).'/piClinicConfig.php';

define('PROFILE_END','End',false);
define('PROFILE_ERROR_TOKEN','Error_token',false);
define('PROFILE_ERROR_PARAMS','Error_params',false);
define('PROFILE_ERROR_KEY','Error_key',false);
define('PROFILE_ERROR_UPDATE','Error_update',false);
define('PROFILE_ERROR_NOTFOUND','Error_notfound',false);
define('PROFILE_ERROR_DELETED','Error_deleted',false);

/*
* 	Profile log
*	
*		Writes one timestamp log entry when called.
*
*/
function profileLogStart (&$profileInfo) {
	$profileInfo['start'] = microtime(true);
}

function profileLogCheckpoint(&$profileInfo, $cpName) {
	$profileInfo[$cpName] = microtime(true);
}

function profileLogClose(&$profileInfo, $script, $inputParamList = null, $profileEnd = PROFILE_END) {
	$profileInfo[$profileEnd] = microtime(true);
	if (!API_PROFILE) {
		// if no logging is desired, return now
		return;
	}
	$logFileName =  API_LOG_FILEPATH . "cts-profile-" . date ('Y-m-d') . ".jlog";
	// open the file for append access and create a new one if this one doesn't exist
	$logFileHandle = fopen ($logFileName, "a+", false);
	if ($logFileHandle) {
		$logRecord = [];
		$logRecord['logtype'] = 'profile';
		$logRecord['logtime'] = date ( 'c' ); //  ISO 8601 date format
		$logRecord['file'] = $script;
		if (!isset($inputParamList)) {
			$inputParamList = $_SERVER['QUERY_STRING'];
		}
		$logRecord['params'] = $inputParamList;
		// try to identify the user
		$localUser = '';
		if (!isset($_SESSION)) { 
			session_start(); 
		}
		if (isset($_SESSION['Username'])) {
			$localUser = $_SESSION['Username'];
		}		
		$logRecord['user'] = $localUser;
		$logRecord['method'] = $_SERVER['REQUEST_METHOD'];
		$logRecord['addr'] = (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '' );
		$logRecord['referrer'] = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' );
		$logRecord['profile'] = [];
		
		$profileStartTime = $profileInfo['start'];
        $lastElapsedTime = 0;
		foreach ($profileInfo as $cpName => $cpTime) {
            $profileVal = [];
			$profileVal['checkpoint'] = $cpName;
			$profileVal['checkTime'] = $cpTime;
			$profileVal['elapsedTime'] = $cpTime - $profileStartTime;
			if ($profileVal['elapsedTime'] > $lastElapsedTime ) {
                $lastElapsedTime = $profileVal['elapsedTime'];
            }
			$logRecord['profile'] = $profileVal;
			// write profile data from each profileLogCheckpoint
			fwrite ($logFileHandle, json_encode($logRecord)."\n");
		}
		fclose ($logFileHandle);
		unset ($profileInfo[$script]);
		return $lastElapsedTime;
	} else {
		// not sure what to do if the log file doesn't open
		return false;
	}
}
//EOF