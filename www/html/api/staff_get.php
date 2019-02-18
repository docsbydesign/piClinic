<?php
/*
 *	Copyright (c) 2019, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
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
 *		The staff record(s) will be returnedt that the fields specified in the query parameter.
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