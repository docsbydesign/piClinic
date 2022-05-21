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
/*******************
 *
 *	Retrieves log entries
 * 		or an HTML error message
 *
 *	GET: Returns log data
 *
 *		Query paramters:
 *
 *		Response:
 *			Session data object
 *
 *		Returns:
 *			200: the session object matching the token
 *			400: required field is missing or $_SERVER values did not match
 *			404: no session object with that token found
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *  Process the GET method request
 */
function _log_get($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);

	// Initialize error message information
    $dbInfo = array();
    $dbInfo['requestArgs'] = $requestArgs;

    // Create a list of missing required columns
    $missingColumnList = '';
    $logDbFields = getLogFieldInfo();
    foreach ($logDbFields as $reqField) {
        if ($reqField[LOG_DB_REQ_GET]) {
            if (empty($requestArgs[$reqField[LOG_REQ_ARG]])) {
                if (!empty($missingColumnList)) {
                    $missingColumnList .= ", ";
                }
                $missingColumnList .= $reqField[LOG_REQ_ARG];
            }
        }
    }

    // If there are any missing columns, return an error here
    if (!empty($missingColumnList) && empty($requestArgs['fieldOpts']) ) {
        // some required fields are missing so exit
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to create get log data. Required field(s): ". $missingColumnList. " are missing.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
        return $returnValue;
    }

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// format DB table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = CONTENT_TYPE_HTML;
    $returnValue['count'] = 0;
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= "Resource not found.";
	$returnValue['format'] = 'json';

	$getQueryString = '';
	if (!empty($requestArgs['fieldOpts'])) {
        $getQueryString =
            'SELECT DISTINCT \'userToken\' AS `fieldName`, `userToken` as `fieldValue` FROM `log` where 1 '.
            'UNION '.
            'SELECT DISTINCT \'sourceModule\' AS `fieldName`, `sourceModule` as `fieldValue` FROM `log` where 1 '.
            'UNION '.
            'SELECT DISTINCT \'logClass\' AS `fieldName`, `logClass` as `fieldValue` FROM `log` where 1 '.
            'UNION '.
            'SELECT DISTINCT \'logTable\' AS `fieldName`, `logTable` as `fieldValue` FROM `log` where 1 '.
            'UNION '.
            'SELECT DISTINCT \'logAction\' AS `fieldName`, `logAction` as `fieldValue` FROM `log` where 1 '.
            'UNION '.
            'SELECT DISTINCT \'logStatusCode\' AS `fieldName`, `logStatusCode` as `fieldValue` FROM `log` where 1; ';
    } else {
        // Create query string for get operation
        $getQueryString = makeLogQueryStringFromRequestParameters($requestArgs);
    }
    $dbInfo ['queryString'] = $getQueryString;
	// get the log records that match
	$returnValue = getDbRecords($dbLink, $getQueryString);
    profileLogCheckpoint($profileData,'QUERY_RETURNED');

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
    // and return here
    profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
