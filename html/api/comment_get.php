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