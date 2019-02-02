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
 *	Returns clinic resources from the database
 * 		or an HTML error message
 *
 *	GET: Clinic records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The clinic record(s) will be returned that match the fields specified in the query parameter.
 * 		
 *			`clinicID` the internal ID of the clinic record
 *          `shortName` wildcard match of the clinic's short name
 *          `this` if true, return the clinic designated as "this" clinic
 *
 *		Returns:
 *			200: the matching clinic record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once 'clinic_common.php';
require_once 'clinic_get.php';
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query paramater data from the request 
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

$retVal = array();

if (empty($apiUserToken)){
    $retVal = formatMissingTokenError ($retVal, 'clinic');
} else {
    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API',
        __FILE__,
        'clinic',
        $_SERVER['REQUEST_METHOD'],
        null,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders ()),
        null,
        null,
        null);

    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError ($dbLink, $retVal, $apiUserToken, 'clinic', $logData);
    } else {
        $logData['userToken'] = $apiUserToken;
        if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_READONLY)) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $retVal = _clinic_get($dbLink, $apiUserToken, $requestData);
                    break;

                default:
                    $retVal['contentType'] = 'Content-Type: application/json';
                    if (API_DEBUG_MODE) {
                        $retVal['error'] = $requestData;
                    }
                    $retVal['httpResponse'] = 405;
                    $retVal['httpReason'] = 'Method not supported.';
                    break;
            }
        } else {
            // caller does not have a valid security token
            $retVal['httpResponse'] = 401;
            $retVal['httpReason'] = "User account is not authorized to access this resource.";
        }
    }
}
// close the DB link until next time
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
//EOF