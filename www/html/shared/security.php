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
 *	Processes authorization and authentication functions
 *
 *********************/
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
require_once dirname(__FILE__).'/dbUtils.php';
require_once dirname(__FILE__).'/logUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../api/session_common.php';
require_once dirname(__FILE__).'/../api/session_get.php';
require_once dirname(__FILE__).'/profile.php';

define('PAGE_ACCESS_ADMIN', 32, false); 	// SystemAdmin
define('PAGE_ACCESS_CLINIC', 16, false); 	// ClinicAdmin
define('PAGE_ACCESS_STAFF', 8, false); 		// ClinicStaff
define('PAGE_ACCESS_READONLY', 4, false); 	// ClinicReadOnly (Any authorized user)
define('PAGE_ACCESS_NONE', 0, false);		// no access

define('NO_ACCESS_URL','/clinicLogin.php', false);	// default URL for access denials--it should not require an access check

function getTokenFromHeaders() {
    if (!empty($_SERVER['HTTP_X_PICLINIC_TOKEN'])) {
        return $_SERVER['HTTP_X_PICLINIC_TOKEN'];
    } else {
        return null;
    }
}

function getSessionInfo ($dbLink, $sessionToken) {
    $httpReason = '';
    $dbOpenedHere = false;
    $sessionInfo = array();

    if (empty($dbLink)){
        // open DB to check for access
        $dbLink = _openDB();
        $dbOpenError = mysqli_connect_errno();
        if ( $dbOpenError  == 0  ) {
            $dbOpenedHere = true;
        }
    }

    if ($dbLink) {
        $requestParams = array();
        $requestParams['token'] = $sessionToken;
        $sessionData = _session_get($dbLink, $sessionToken, $requestParams);
        if ($sessionData['httpResponse'] == 200) {
            // successful call, now check the response\
            $sessionInfo = $sessionData['data'];
        }
    }

    if ($dbOpenedHere) {
        // close the db if we opened it for this check
        @mysqli_close($dbLink);
    }
    return $sessionInfo;
}

function checkUiSessionAccess($dbLink, $sessionToken, $pageAccess, $sessionInfo=null) {
	$profileData = [];
	profileLogStart ($profileData);
    // assume access is granted until a test fails
    $dbAccessGranted = true;

    if (empty($sessionInfo)){
        $sessionInfo = getSessionInfo ($dbLink, $sessionToken);
    }

    // 0 means session is not valid
    if (!empty($sessionInfo['token']) && ($sessionInfo['token'] != '0')) {
        // valid session so check page access
        switch ($sessionInfo['accessGranted']) {
            case 'ClinicStaff':
                $sessionAccess = PAGE_ACCESS_STAFF;
                break;

            case 'ClinicAdmin':
                $sessionAccess = PAGE_ACCESS_CLINIC;
                break;

            case 'SystemAdmin':
                $sessionAccess = PAGE_ACCESS_ADMIN;
                break;

            case 'ClinicReadOnly':
                $sessionAccess = PAGE_ACCESS_READONLY;
                break;

            default:
                // unrecognized
                $sessionAccess = PAGE_ACCESS_NONE;
                break;
        }
        header('Debug_SECURITY_AccessStatus: USER: '.strval($sessionAccess).', PAGE: '.strval($pageAccess));
        if ($sessionAccess < $pageAccess) {
            $dbAccessGranted = false;
            if (API_DEBUG_MODE) {
                header('Debug_SECURITY_AccessDenied: '.strval($sessionAccess).'<'.strval($pageAccess));
            }
        }
    } else {
        $dbAccessGranted = false;
        if (API_DEBUG_MODE) {
            header('Debug_SECURITY_Token: (cookie != DB  )'. $sessionToken. ' != '.
                (empty($sessionInfo['token']) ? 'null' : $sessionInfo['token']));
        }
    }

    profileLogClose($profileData, __FILE__);
	return ($dbAccessGranted);
}
//EOF