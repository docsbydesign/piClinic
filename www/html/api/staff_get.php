<?php
/*
 *
 * Copyright (c) 2018 by Robert B. Watson
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
 *	Retrieves a staff record
 * 		or an HTML error message
 *
 *	GET: Returns staff records that match the specified query parameters
 *
 *	Identification query paramters:
 *		The staff record(s) will be returned that the fields specified in the query parameter.
 *
 *			`MemberID` - (Optional) Staff ID issued by clinic.
 *			`Username` - (Required) Staff's Username (unique)
 *   		`NameLast` - (Required) Staff's last name(s)
 *   		`NameFirst` - (Required) Staff's first name
 *   		`Position` - (Required) Clinic role
 *   		`ContactInfo` - (Optional) Staff's email or phone number
 *   		`AltContactInfo` - (Optional) Staff's Additional/alternate email or phone number
 * 			`Active` - whether the user has access to the system
 *   		`AccessGranted` - (Required) Level of access to clinic DB info
 *			LastLogin - Date of last login
 *			modifiedDate - Date of last change to this user's information
 *			createdDate - Date user account was created
 *
 *		Returns:
 *			200: the matching staff record(s)
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

function _staff_get ($dbLink,  $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = CONTENT_TYPE_HTML;
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= 'Resource not found.';

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

	$dbView = DB_VIEW_STAFF_GET;	// the default is to order by Username
	if(isset($requestArgs['sort'])){
		if ($requestArgs['sort'] == 'lastName') {
			$dbView = DB_VIEW_STAFF_GET_BY_NAME;
		}
	}

	// create query string for get operation
	$getQueryString = makeStaffQueryStringFromRequestParameters ($requestArgs, $dbView);
    $dbInfo['getQueryString'] = $getQueryString;
	$returnValue = getDbRecords($dbLink, $getQueryString);

    profileLogClose($profileData, __FILE__, $requestArgs);

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
	return $returnValue;
}
//EOF
