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
 *	Creates/Returns patient resources from the database
 * 		or an HTML error message
 *
 *	POST: Adds a new patient record to the database
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *   		`NameLast` - (Required) Patient's last name(s)
 *   		`NameFirst` - (Required) Patient''s first name
 *			`NameMI` - (Optional) Patient's middle initial
 *   		`Sex` - (Required) 'Male','Female','Other' Patient''s sex
 *   		`BirthDate` - (Required) Patient''s date of birth
 *   		`HomeAddress1` - (Optional) Patient''s home address
 *   		`HomeAddress2` - (Optional) additional home address info (e.g. apt, room, etc.)
 *   		`HomeNeighborhood` - (Optional) Patient''s home neighborhood.
 *   		`HomeCity` - (Optional) Patient''s home city
 *   		`HomeCounty` - (Optional) Patient''s home county
 *   		`HomeState` - (Optional) Patient''s home state
 *			`ContactPhone` - (Optional) Patient''s primary phone number
 *   		`ContactAltPhone` - (Optional) Patient''s alternate phone number
 * 			`BloodType` - (Optional) Patient''s blood type ('A+','A-','B+','B-','AB+','AB-','O+','O-','NA')
 * 			`OrganDoner` - (Optional) Patient''s organ donor preference
 * 			`PreferredLanguage` - '(Optional) Patient''s preferred language for communications
 *			`KnownAllergies` - (Optional) Known Allergies stored as |-separated list
 *			`CurrentMedications` - (Optional) Current Medications stored as |-separated list
 *
 *		Returns:
 *			201: the new patient record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *	GET: Returns patient records that match the specified query parameters
 *
 *	Identification query paramters:
 *		The patient record(s) will be returned if they match all of these fields that are specified.
 *
 *		ClinicPatientID		= the patient's ID issued by the clinic. This is unique to the patient.
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
 *
 * PATCH: Modifies one or more fields in an existing patient record as identified by patientClinicID
 *
 * 		input data:
 *			`ClinicPatientID` - (Required) Patient ID issued by clinic.
 *   		`NameLast` - (Optional) Patient's last name(s)
 *   		`NameFirst` - (Optional) Patient''s first name
 *			`NameMI` - (Optional) Patient's middle initial
 *   		`Sex` - (Optional) 'Male','Female','Other' Patient''s sex
 *   		`BirthDate` - (Optional) Patient''s date of birth
 *   		`HomeAddress1` - (Optional) Patient''s home address
 *   		`HomeAddress2` - (Optional) additional home address info (e.g. apt, room, etc.)
 *   		`HomeNeighborhood` - (Optional) Patient''s home neighborhood.
 *   		`HomeCity` - (Optional) Patient''s home city
 *   		`HomeCounty` - (Optional) Patient''s home county
 *   		`HomeState` - (Optional) Patient''s home state
 *			`ContactPhone` - (Optional) Patient''s primary phone number
 *   		`ContactAltPhone` - (Optional) Patient''s alternate phone number
 * 			`BloodType` - (Optional) Patient''s blood type ('A+','A-','B+','B-','AB+','AB-','O+','O-','NA')
 * 			`OrganDoner` - (Optional) Patient''s organ donor preference
 * 			`PreferredLanguage` - '(Optional) Patient''s preferred language for communications
 *		Returns:
 *			200: the updated patient record
 *			400: required field is missing
 *			404: no record found that matches the specified patient clinic ID
 *			500: server error information
 *
 * DELETE: marks a patient record as inactive, the record is not removed from the database
 * 		input data:
 *			`ClinicPatientID` - (Required) Patient ID issued by clinic.
 *
 *		Returns:
 *			200: the updated patient record
 *			400: required field is missing
 *			404: no record found that matches the specified patient clinic ID
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once('patient_common.php');
require_once('patient_post.php');
require_once('patient_get.php');
require_once('patient_patch.php');
require_once('patient_delete.php');
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query parameter data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$retVal = array();
$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

if (empty($apiUserToken)){
    // caller did not pass a security token
    $retVal = formatMissingTokenError ($retVal, 'patient');
} else {
    $logData = createLogEntry('API',
        __FILE__,
        'patient',
        $_SERVER['REQUEST_METHOD'],
        $apiUserToken,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders()),
        null,
        null,
        null);

    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError($dbLink, $retVal, $apiUserToken, 'patient', $logData);
    } else {
        switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                $retVal = _patient_post($dbLink, $apiUserToken, $requestData);
            } else {
                // caller does not have a valid security token
                $retVal['httpResponse'] = 401;
                $retVal['httpReason'] = "User account is not authorized to create this resource.";
                $logData['logStatusCode'] = $retVal['httpResponse'];
                $logData['logStatusMessage'] = $retVal['httpReason'];
                writeEntryToLog($dbLink, $logData);
            }
            break;

        case 'GET':
            if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_STAFF)) {
                $retVal = _patient_get($dbLink, $apiUserToken, $requestData);
            } else {
                // caller does not have a valid security token
                $retVal['httpResponse'] = 401;
                $retVal['httpReason'] = "User account is not authorized to create this resource.";
                $logData['logStatusCode'] = $retVal['httpResponse'];
                $logData['logStatusMessage'] = $retVal['httpReason'];
                writeEntryToLog($dbLink, $logData);
            }
            break;

        case 'PATCH':
            if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                $retVal = _patient_patch($dbLink, $apiUserToken, $requestData);
            } else {
                // caller does not have a valid security token
                $retVal['httpResponse'] = 401;
                $retVal['httpReason'] = "User account is not authorized to create this resource.";
                $logData['logStatusCode'] = $retVal['httpResponse'];
                $logData['logStatusMessage'] = $retVal['httpReason'];
                writeEntryToLog($dbLink, $logData);
            }
            break;

        case 'DELETE':
            if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_CLINIC)) {
                $retVal = _patient_delete($dbLink, $apiUserToken, $requestData);
            } else {
                // caller does not have a valid security token
                $retVal['httpResponse'] = 401;
                $retVal['httpReason'] = "User account is not authorized to create this resource.";
                $logData['logStatusCode'] = $retVal['httpResponse'];
                $logData['logStatusMessage'] = $retVal['httpReason'];
                writeEntryToLog($dbLink, $logData);
            }
            break;

        default:
            $retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
            if (API_DEBUG_MODE) {
                $retVal['error'] = $requestData;
            }
            $retVal['httpResponse'] = 405;
            $retVal['httpReason'] = "Method not supported.";
            break;
        }

    }
}
// close the DB link until next time
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
//EOF
