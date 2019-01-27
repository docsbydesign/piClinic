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
 *	Creates/Returns staff resources from the database 
 * 		or an HTML error message
 *
 *	POST: Adds a new staff record to the database
 * 		input data:
 *			`MemberID` - (Optional) Staff ID issued by clinic.
 *			`Username` - (Required) Staff's Username (unique)
 *   		`NameLast` - (Required) Staff's last name(s)
 *   		`NameFirst` - (Required) Staff's first name
 *   		`Position` - (Required) Clinic role
 *   		`Password` - (Required) Stored as hash of user's password
 *   		`ContactInfo` - (Optional) Staff's email or phone number
 *   		`AltContactInfo` - (Optional) Staff's Additional/alternate email or phone number
 *   		`AccessGranted` - (Required) Level of access to clinic DB info
 *
 *		Returns:
 *			201: the new staff record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *	GET: Returns staff records that match the specified query parameters
 *
 *	Identification query paramters:
 *		The staff record(s) will be returnedt that the fields specified in the query parameter.
 * 		
 *			`MemberID` - (Optional) Staff ID issued by clinic.
 *			`Username` - (Required) Staff's Username (unique)
 *   		`NameLast` - (Required) Staff's last name(s)
 *   		`NameFirst` - (Required) Staff's first name
 *   		`Position` - (Required) Clinic role
 *   		`ContactInfo` - (Optional) Staff's email or phone number
 * 			`Active` - whether the user has access to the system
 *   		`AccessGranted` - (Required) Level of access to clinic DB info
 *			LastLogin - Date of last login
 *			modifiedDate - Date of last change to this user's information
 *			createdDate - Date user account was created
 *
 *		Returns:
 *			200: the matching staff record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 * PATCH: Modifies one or more fields in an existing staff record as identified by Username or staffID
 *
 * 		input data:
 *			`MemberID` - (Optional) Staff ID issued by clinic.
 *			`Username` - (Required) Staff's Username (unique)
 *   		`NameLast` - (Required) Staff's last name(s)
 *   		`NameFirst` - (Required) Staff's first name
 *   		`Position` - (Required) Clinic role
 *   		`ContactInfo` - (Optional) Staff's email or phone number
 *   		`AltContactInfo` - (Optional) Staff's Additional/alternate email or phone number
 * 			`Active` - whether the user has access to the system
 *   		`AccessGranted` - (Required) Level of access to clinic DB info
 *
 *		Returns:
 *			200: the updated staff record
 *			400: required field is missing
 *			404: no record found that matches the specified id field
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once 'staff_common.php';
require_once 'staff_post.php';
require_once 'staff_get.php';
require_once 'staff_patch.php';
require_once 'staff_delete.php';
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query paramater data from the request 
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$retVal = array();
$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

if (empty($apiUserToken)){
    // caller did not pass a security token
    $retVal = formatMissingTokenError ($retVal, 'staff');
} else {
    $logData = createLogEntry ('API',
        __FILE__,
        'staff',
        $_SERVER['REQUEST_METHOD'],
        $apiUserToken,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders ()),
        null,
        null,
        null);

    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError ($dbLink, $retVal, $apiUserToken, 'staff', $logData);
    } else {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                    $retVal = _staff_post($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }
                break;

            case 'GET':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_STAFF)) {
                    $retVal = _staff_get($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to read this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }
                break;

            case 'PATCH':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                    $retVal = _staff_patch($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to update this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }
                break;

            case 'DELETE':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                    $retVal = _staff_delete($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to disable this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
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