<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 12/20/2018
 * Time: 6:48 PM
 */
/*
 *	Copyright (c) 2019, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */
/*
 *  API endpoint for log resource requests
 */
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once 'log_common.php';
require_once 'log_post.php';
require_once 'log_get.php';

/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = [];
profileLogStart($profileData);

// Get the query paramater data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

//  Initilaize return value object
$retVal = array();

// Open the database. All subordinate functions assume access to the DB
$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

// Set the default content type
$retVal = array();
$retVal['contentType'] = 'application/json; charset=utf-8';

if (empty($apiUserToken)){
    $retVal = formatMissingTokenError ($retVal, 'log');
} else {
    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API',
        __FILE__,
        'log',
        $_SERVER['REQUEST_METHOD'],
        null,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders ()),
        null,
        null,
        null);

    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError ($dbLink, $retVal, $apiUserToken, 'log', $logData);
    } else {
        // token is OK so we can continue
        $logData['userToken'] = $apiUserToken;
        // Caller has a valid token, but check access before processing the request
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_ADMIN)) {
                    $retVal = _log_post($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                }
                break;

            case 'GET':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_STAFF)) {
                    $retVal = _log_get($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to read this resource.";
                }
                break;

            default:
                if (API_DEBUG_MODE) {
                    $retVal['error'] = $requestData;
                }
                $retVal['httpResponse'] = 405;
                $retVal['httpReason'] = $_SERVER['REQUEST_METHOD'] . ' method is not supported.';
                break;
        }
    }
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
// eof