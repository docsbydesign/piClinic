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
