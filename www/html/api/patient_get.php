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
