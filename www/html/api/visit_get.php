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
 *	Retrieves a visit resource
 * 		or returns an HTML error message
 *
 *	GET: Returns patient visits that match the specified query parameters
 *
 *	Identification query paramters:
 *		The patient visit(s) will be returned if they match all of these fields that are specified.
 *
 *			One of these identification parameters is required
 *			`ClinicPatientID` - Returns the visits by the Patient with this ID sorted by descending visit date
 *   		`visitID` - Returns a specific visit record
 *			`PatientVisitID` - Returns a specific visit record
 *			These query parameters are optional and further filter the result (if ClinicPatientID is used for ID)
 *   		`VisitType` - (optional) The type of visit being added
 *
 *		Returns:
 *			200: the matching visit (if the query identifies a unique object
 *					or a JSON object of the matching visit metadata
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *
 *	Returns
 * 		formInfoArray:
 *			['httpResponse'] = the HTTP response code
 *			['httpReason']	= the HTTP response reason string
 *			['contentType'] the content type of the data
 *			['data'] = the response data
 *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _visit_get ($dbLink, $apiUserToken, $requestArgs) {
	$profileData = [];
	profileLogStart ($profileData);

	// format db table fields as dbInfo array
	$returnValue = array();

	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

	// check for other required columns
	$requiredPatientColumns = [
		"visitID"
		,"clinicPatientID"
		,"patientVisitID"
		,"visitStatus"
		];

	//  NOTE: this is an "OR" test--only one is required
	$missingColumnList = "";
	// make sure one of the ID columns is present
	foreach ($requiredPatientColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ", ";
			}
			$missingColumnList .= $column;
		} else {
			// At least one was found, so clear the missing column list and continue
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
		$returnValue['httpReason']	= ' Unable to find a patient visit. One of these required query parameters is missing: ' . join( ', ', $requiredPatientColumns );
		return $returnValue;
	}

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// assume we can't find it...until we do
	$returnValue['contentType'] = "Content-Type: text/plain; charset=utf-8";
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= "Resource not found.";
	$returnValue['format'] = 'json';
	$returnValue['count'] = 0;

	// create query string for get operation
	$getQueryString = makeVisitQueryStringFromRequestParameters ($requestArgs);
	// get the records that match if a valid query was returned
	if (!empty($getQueryString)) {
		$returnValue = getDbRecords($dbLink, $getQueryString);
	} else {
		// format response
		$returnValue['data'] = "";
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to find a matching patient visit. A valid query could not be made from the query parameters. ";
	}

	if ($returnValue['count'] == 0) {
		//return 404
		// add debug info to the list
		if (API_DEBUG_MODE) {
			$dbInfo ['queryString'] = $getQueryString;
			$returnValue['debug'] = $dbInfo;
		}
		return $returnValue;
	}

	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
	// only log performance info on success.
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
