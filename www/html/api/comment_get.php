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
 *	Retrieves a comment record
 * 		or an HTML error message
 *
 *	GET: Returns comment records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The comment record(s) will be returned that the fields specified in the query parameter.
 *
 *   		`commentID` - (Optional) record ID
 *  		`Username` - (Required) Username creating this session.
 *  		`ReferringUrl` - (Optional) Page URL from which comment page was called.
 *			`ReferringPage` - (Optional) Page name from path.
 *
 *		Returns:
 *			200: the matching comment record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *	Returns
 * 		formInfoArray:
 *			['httpResponse'] = the HTTP response code
 *			['httpReason']	= the HTTP response reason string
 *			['contentType'] the content type of the data
 *			['data'] = the response data
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *
 */
function _comment_get ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = 'Content-Type: text/plain; charset=utf-8';
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= 'Resource not found.';

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

	$dbView = DB_VIEW_COMMENT_GET;

	// create query string for get operation
	$getQueryString = makeCommentQueryStringFromRequestParameters ($requestArgs, $dbView);
	$returnValue = getDbRecords($dbLink, $getQueryString);

	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
