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
 *	Retrieves a clinic record
 * 		or an HTML error message
 *
 *	GET: Clinic records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The clinic record(s) will be returned that match the fields specified in the query parameter.
 *
 *			`publicID` the internal ID of the clinic record
 *          `shortName` wildcard match of the clinic's short name
 *          `thisClinic` if true, return the clinic designated as "this" clinic
 *
 *		Returns:
 *			200: the matching clinic record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *
 */
function _clinic_get ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);

	$dbInfo = array();
	$dbInfo['requestArgs'] = $requestArgs;

	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = 'Content-Type: text/plain; charset=utf-8';
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= 'Resource not found.';

    $requiredParameters = [
        'publicID'
        , 'shortName'
        , 'thisClinic'
    ];

    //  NOTE: this is an "OR" test--only one is required
    $missingColumnList = "";
    $keyField = '';
    // make sure one of the ID columns is present
    foreach ($requiredParameters as $column) {
        if (empty($requestArgs[$column])) {
            if (!empty($missingColumnList)) {
                $missingColumnList .= ", ";
            }
            $missingColumnList .= $column;
        } else {
            // At least one was found, so clear the missing column list and continue
            $keyField = $column;
            $missingColumnList = '';
            break;
        }
    }

    if (!empty($missingColumnList)) {
        // one or more required fields are missing, so exit
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= ' Unable to find a clinic. One of these required query parameters is missing: ' . join( ', ', $requiredParameters);
        return $returnValue;
    }

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    // create query
    $getQueryString = '';

    switch ($keyField) {
        case 'publicID':
            $getQueryString = 'SELECT * FROM `'. DB_TABLE_CLINIC .
                '` WHERE `'.$keyField.'` = \''. $requestArgs[$keyField] . '\' '.
                DB_QUERY_LIMIT . ';';
            break;

        case 'shortName':
            $getQueryString = 'SELECT * FROM `'. DB_TABLE_CLINIC .
                '` WHERE `'.$keyField.'` LIKE \''. $requestArgs[$keyField] . '\' '.
                DB_QUERY_LIMIT . ';';
            break;

        case 'thisClinic':
            $getQueryString = 'SELECT * FROM `'. DB_VIEW_THISCLINIC .'` ' .
                DB_QUERY_LIMIT . ';';
            break;
    }
    if (API_DEBUG_MODE) {
        $dbInfo['getQueryString'] = $getQueryString;
    }

	// create query string for get operation
	$returnValue = getDbRecords($dbLink, $getQueryString);

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }

	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
