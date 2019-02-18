<?php
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
/*******************
 *
 *	Creates/Returns visit resources from the database 
 * 		or an HTML error message
 *
 *	POST: Adds a new visit record to the database
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *   		`visitType` - (Required) The type of visit record being added
 *
 *		Returns:
 *			201: the new visit record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *	GET: Returns patient visits that match the specified query parameters
 *
 *	Identification query paramters:
 *		The patient record(s) will be returned if they match all of these fields that are specified.
 * 		
 *   		`visitID` - (Required only if associated with a patient visit) Patient's last name(s)
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *
 *		Returns:
 *			200: the matching visit (if the query identifies a unique object 
 *					or a JSON object of the matching visit metadata
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *
 * DELETE: deletes the specified image
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *			`visitID` - (Required) Image ID issued .
 *
 *		Returns:
 *			200: No data
 *			400: required field is missing
 *			404: no record found that matches the specified patient clinic ID
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once('api_common.php');
require_once('visit_common.php');
require_once('visit_post.php');
require_once('visit_get.php');
require_once('visit_patch.php');

/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query parameter data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$retVal = array();
$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
        if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
            $retVal = _visit_post ($dbLink, $apiUserToken, $requestData);
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
			$retVal = _visit_get($dbLink, $apiUserToken, $requestData);
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
			$retVal = _visit_patch($dbLink, $apiUserToken, $requestData);
        } else {
            // caller does not have a valid security token
            $retVal['httpResponse'] = 401;
            $retVal['httpReason'] = "User account is not authorized to update this resource.";
            $logData['logStatusCode'] = $retVal['httpResponse'];
            $logData['logStatusMessage'] = $retVal['httpReason'];
            writeEntryToLog($dbLink, $logData);
        }
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$retVal['error']['queryParameters'] = $requestData;
		}
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";		
		break;
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
// EOF