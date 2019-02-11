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
 *	Retrieves a icd record
 * 		or an HTML error message
 *
 *	GET: Returns icd code records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The icd record(s) will be returned that the fields specified in the query parameter.
 * 		
 *			`q` - (Optional) a search term that will look for matches in the code or the description text
 *			`t` - (optional) a search term that will look for matches in the description text
 *   		`c` - (optional) a search term that will look for a regexp match in the code or code index (if no . found)
 *   		`language` - (optional) specifies the language to return (ICD-10 returns English by default)
 *			`sort` - (optional) specifies the order of the returned data (c[ode] | t[ext] | d[ate])
 *
 *		Returns:
 *			200: the matching icd record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *
 *	Returns
 * 		formInfoArray:
 *			['httpResponse'] = the HTTP response code
 *			['httpReason']	= the HTTP response reason string
 *			['contentType'] the content type of the data
 *			['data'] = the response data as an array or object
 *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _icd_get ($dbLink, $apiUserToken, $formArgs) {
	$profileData = [];
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = 'Content-Type: text/plain; charset=utf-8';
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= 'Resource not found.';

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');
	
	$dbInfo = array();
	$dbInfo ['formArgs'] = $formArgs;
	
	// create query string for get operation
	$getQueryString = makeIcdQueryStringFromRequestParameters ($formArgs, DB_VIEW_ICD10_GET);
	$dbInfo['getQueryString'] = $getQueryString;
	if (empty($getQueryString)) {
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'A required query parameter is missing.';
	} else {
		$returnValue = getDbRecords($dbLink, $getQueryString);
	}

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }

    profileLogClose($profileData, __FILE__, $formArgs);
	return $returnValue;
}
?>