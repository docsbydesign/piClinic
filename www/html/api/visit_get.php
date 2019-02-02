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