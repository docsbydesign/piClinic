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
function _logger_get($dbLink, $requestArgs) {
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
    $loggerDbFields = getLoggerFieldInfo();
    foreach ($loggerDbFields as $reqField) {
        if ($reqField[LOGGER_DB_REQ_GET]) {
            if (empty($requestArgs[$reqField[LOGGER_REQ_ARG]])) {
                if (!empty($missingColumnList)) {
                    $missingColumnList .= ", ";
                }
                $missingColumnList .= $reqField[LOGGER_REQ_ARG];
            }
        }
    }

    // If there are any missing columns, return an error here
    if (!empty($missingColumnList)) {
        // some required fields are missing so exit
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to create get logger data. Required field(s): ". $missingColumnList. " are missing.";
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

    // Create query string for get operation
	$getQueryString = makeLoggerQueryStringFromRequestParameters ($requestArgs);
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