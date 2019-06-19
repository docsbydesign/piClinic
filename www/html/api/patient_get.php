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
 *	Retrieves a patient record
 * 		or an HTML error message
 *
 *	GET: Returns patient records that match the specified query parameters
 *
 *	Identification query paramters:
 *		The patient record(s) will be returned if they match all of these fields that are specified.
 * 		
 *		ClinicPatientID		= the patient's ID issued by the clinic. This is unique to the patient.
 *		FamilyID			= the family ID or family folder number. This can match multiple patients.
 *		NameLast			= the last name of the patient to find
 *		NameFirst			= the first name of the person to find
 *		Sex					= the patient's sex
 *      BirthDate			= the patient's birthdate
 *		HomeNeighborhood	= the patient's neighborhood
 *		HomeCity			= the city (patientHomeCity) of the patient
 *
 *		Returns:
 *			200: the matching patient record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 
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

function _patient_get ($dbLink, $apiUserToken, $requestArgs) {
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

	// create query string for get operation
	$getQueryString = '';
	if (!empty($requestArgs['q'])) {
	    $queryArg = trim($requestArgs['q']);
	    // test for exact matches first, and if nothing turns up, check for a much looser match
        $getQueryString = makePatientSearchQuery ($queryArg, false);
        $dbInfo['getQueryString'] = $getQueryString;
        $returnValue = getDbRecords($dbLink, $getQueryString);
        if ($returnValue['count'] == 0) {
            // try a looser match if a tight match wasn't successful
            $getQueryString = makePatientSearchQuery ($queryArg, true);
            $dbInfo['getQueryString'] = $getQueryString;
            $returnValue = getDbRecords($dbLink, $getQueryString);
        }
        // return the result
	} else {
		$getQueryString = makePatientQueryStringFromRequestParameters ($requestArgs);
        $dbInfo['getQueryString'] = $getQueryString;
        $returnValue = getDbRecords($dbLink, $getQueryString);
	}

    profileLogClose($profileData, __FILE__, $requestArgs);

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
    return $returnValue;
}
//EOF