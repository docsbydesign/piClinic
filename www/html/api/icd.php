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
 *	Creates/Returns icd (ICD-10 code) resources from the database 
 * 		or an HTML error message
 *
 *
 *	GET: Returns icd code records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The icd record(s) will be returned that the fields specified in the query parameter.
 * 		
 *			`q` - (Optional) a search term that will look for matches in the code or the description text
 *			`t` - (optional) a search term that will look for matches in the description text
 *   		`c` - (optional) a search term that will look for a regexp match in the code or code index (if no . found)
 *   		`lang` - (optional) specifies the language to return (ICD-10 returns English by default)
 *			`sort` - (optional) specifies the order of the returned data (c[ode] | t[ext] | d[ate])
 *			`limit` - (optional) the max # of matches to return. Default = dbConfig default;
 *
 *		Returns:
 *			200: the matching icd record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 * PATCH: Modifies last used date of the specified code (for MRU date sort)
 *
 * 		input data:
 *   		`c` - (required) Code to update LastUsedTime field with current time
 *
 *		Returns:
 *			200: the updated icd record
 *			400: required field is missing
 *			404: no record found that matches the specified id field
 *			500: server error information
 *
 *********************/
require_once('../shared/piClinicConfig.php');
require_once('../shared/dbUtils.php');
require_once('api_common.php');
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once('icd_common.php');
require_once('icd_get.php');
require_once('icd_patch.php');

$profileData = [];
profileLogStart ($profileData);

// get the query parameter data from the request 
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$retVal = array();
$dbLink = _openDBforAPI($requestData);
profileLogCheckpoint($profileData,'DB_OPEN');

if (empty($apiUserToken)){
    // caller did not pass a security token
    $retVal = formatMissingTokenError ($retVal, 'icd');
} else {
    $logData = createLogEntry('API',
        __FILE__,
        'icd',
        $_SERVER['REQUEST_METHOD'],
        $apiUserToken,
        $_SERVER['QUERY_STRING'],
        null,
        null,
        null,
        null);

    // caller must be logged in to access this resource
    if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_READONLY)) {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $retVal = _icd_get($dbLink, $apiUserToken, $requestData);
                break;

            case 'PATCH':
                $retVal = _icd_patch($dbLink, $apiUserToken, $requestData);
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
        $logData['logStatusCode'] = $retVal['httpResponse'];
        $logData['logStatusMessage'] = $retVal['httpReason'];
        writeEntryToLog($dbLink, $logData);
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
// EOF