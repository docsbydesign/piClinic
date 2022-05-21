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
 *   		`c` - (optional) a search term that will look for a regexp match in the code index
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
