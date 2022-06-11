<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
 *   		`c` - (optional) a search term that will look for a regexp match in the code index
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

            case 'OPTIONS':
                $retVal = createOptionsResponse ();
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
