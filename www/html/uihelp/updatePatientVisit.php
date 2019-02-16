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
 *	Formats the data from the editPatientVisit and closePatientVisit forms 
 * 		to update the patient visit record
 *
 *	POST: Adds a new patient record to the database
 * 		input data:
 * 			Form data
 *
 *		Returns:
 *			200: the patient visit record was updated
 *			400: required field is missing
 *			404: patient visit record not found
 *			500: server error information
 *
 *
 *
 *********************/
// include files
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../shared/profile.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';
require_once dirname(__FILE__).'/../api/visit_common.php';
require_once dirname(__FILE__).'/../api/visit_patch.php';
require_once dirname(__FILE__).'/../api/staff_common.php';
require_once dirname(__FILE__).'/../api/staff_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = $sessionInfo['parameters'];

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// check for authorization to access this page
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], PAGE_ACCESS_CLINIC, $sessionInfo)){
    // show this in the error div
    $requestData['msg'] = MSG_NO_ACCESS;
    $redirectUrl = makeUrlWithQueryParams('/clinicDash.php', $requestData);
    $logError = [];
    $logError['httpResponse'] =  403;
    $logError['httpReason'] = 'User account is not authorized to access this resource.';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'staff', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    return;
}

// get referrer URL to return to in error or by default
$referringPageUrl = NULL;
$referringQueryParams = array();
if (isset($_SERVER['HTTP_REFERER'])) {
    $referringPageUrl = cleanedRefererUrl();
} else {
    //default return is the visit info page
    $referringPageUrl = '/visitInfo.php';
    if (isset($formData['patientVisitID'])) {
        $referringQueryParams['patientVisitID'] = $formData['patientVisitID'];
    }
    if (isset($formData['clinicPatientID'])) {
        $referringQueryParams['clinicPatientID'] = $formData['clinicPatientID'];
    }
    $referringPageUrl = makeUrlWithQueryParams($referringPageUrl, $referringQueryParams);
}

// assign URLs to go to after action
$errorUrl = $referringPageUrl;
$successUrl = $referringPageUrl;
// use URL from query params if one is found
if (!empty($formData['returnUrl'])) {
	$successUrl = $formData['returnUrl'];
}

// format arguments for database update
// copy required fields from reqeust data sent by form
$requestData = array();

$dbRequiredFields = [
	'patientVisitID'
];

// copy the required fields
$missingFields = '';
foreach ($dbRequiredFields as $field){
	if (isset($formData[$field])){
		$requestData[$field] = $formData[$field];
	} else {
		// required field is missing
		if (!empty($missingFields)) {
			$missingFields .= ', ';
		}
		$missingFields .= $field;		
	}
}

if (!empty($missingFields)) {
	// invalid form data
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
	// return to referring URL with error message
	$redirectUrl = makeUrlWithQueryParams($errorUrl,['msg'=>MSG_REQUIRED_FIELD_MISSING]);
	$retVal['httpResponse'] = 400;
	$retVal['httpReason']   = "Invalid request. Required fields missing: ".$missingFields;
	$dbInfo['redirectUrl'] = $redirectUrl;
	$retVal['error'] = $dbInfo;
	logApiError($formData, $retVal, __FILE__ );
	header("Location: ".$redirectUrl);
	return;
}

// interpret the date fields
if (!empty($formData['dateTimeInYear']) &&
	!empty($formData['dateTimeInMonth']) &&
	!empty($formData['dateTimeInDay']) &&
	!empty($formData['dateTimeInTime'])) {
	$tempDateString = $formData['dateTimeInYear'].'-'.
		$formData['dateTimeInMonth'].'-'.
		$formData['dateTimeInDay'].' '.
		$formData['dateTimeInTime'].':00';
	$tempdateTime = date_create_from_format('Y-m-d H:i:s', $tempDateString );
	$requestData['dateTimeIn'] = date_format ($tempdateTime, 'Y-m-d H:i:s');
} else {
	$requestData['dateTimeIn'] = NULL; // this will stay unchanged
}

if (!empty($formData['dateTimeOutYear']) &&
	!empty($formData['dateTimeOutMonth']) &&
	!empty($formData['dateTimeOutDay']) &&
	!empty($formData['dateTimeOutTime'])) {
	$tempDateString = $formData['dateTimeOutYear'].'-'.
		$formData['dateTimeOutMonth'].'-'.
		$formData['dateTimeOutDay'].' '.
		$formData['dateTimeOutTime'].':00';
	$tempdateTime = date_create_from_format('Y-m-d H:i:s', $tempDateString );
	$requestData['dateTimeOut'] = date_format ($tempdateTime, 'Y-m-d H:i:s');
} else {
	$requestData['dateTimeOut'] = NULL; // this will stay empty
}

//from optional query params (form data)
$dbOptionalFields = [
	// 'visitID' filled by DB
	'staffUsername' 	// TODO: coming soon
	,'deleted'
	,'staffName' 	// TODO: coming soon
    ,'payment'
	,'visitType' 
	,'visitStatus'		// assign if present, otherwise, use default
	,'complaintPrimary'
	,'complaintAdditional'
    ,'payment'
	// ,'dateTimeIn' 		(see above)
	// ,'dateTimeOut'		(see above)
	// ,'clinicPatientID' required (see above)
	// ,'PatientVisitId' required (see above)
	,'diagnosis1'
	,'condition1'
	,'diagnosis2'
	,'condition2'
	,'diagnosis3'
	,'condition3'
	,'referredTo'
	,'referredFrom'
];

// copy and trim the optional field values
foreach ($dbOptionalFields as $field){
	if (isset($formData[$field])){
		$requestData[$field] = $formData[$field];
	}
}

// if there are diagnosis codes defined, 
//   replace the diagnostic text with the code provided
if (!empty($formData['diagnosis1_selectval'])) {
	$requestData['diagnosis1'] = $formData['diagnosis1_selectval'];
}
if (!empty($formData['diagnosis2_selectval'])) {
	$requestData['diagnosis2'] = $formData['diagnosis2_selectval'];
}
if (!empty($formData['diagnosis3_selectval'])) {
	$requestData['diagnosis3'] = $formData['diagnosis3_selectval'];
}

$dbLink = _openDB();
$dbOpenError = mysqli_connect_errno();
if ( $dbOpenError  != 0  ) {
	$retVal = array();
	// database not opened.
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
	// return to referring URL with error message
	$redirectUrl = $errorUrl . '&msg=DB_OPEN_ERROR';
	$retVal['httpResponse'] = 500;
	$retVal['httpReason']   = MSG_DB_OPEN_ERROR;
	$dbInfo['sqlError'] = 'Error: '. $dbOpenError .', '.
		mysqli_connect_error();
	$dbInfo['redirectUrl'] = $redirectUrl;
	$retVal['error'] = $dbInfo;
	logApiError($formData, $retVal, __FILE__ );
	header("Location: ".$redirectUrl);
	return;
}

// if there's a staffUsername arg, look up the name
// to load the staffName field.

if (isset($formData['staffUsername'])) {
	// look up the username in the staff table
	$staffQueryString['username'] = $formData['staffUsername'];
	$staffResponse = _staff_get ($dbLink, $sessionInfo['token'], $staffQueryString);

	if (($staffResponse['httpResponse'] == 200) && ($staffResponse['count'] == 1)) {
		// there should only ever be 1 name returned
		$requestData['staffName'] = $staffResponse['data']['lastName'].', '.$staffResponse['data']['firstName'];
	} else {
		// no match so write it as the username
		$requestData['staffName'] = $formData['staffUsername'];
	}
}

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$retVal = _visit_patch($dbLink, $sessionInfo['token'], $requestData);
		break;
		
	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$retVal['error']['requestData'] = $requestData;
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($formData, $retVal, __FILE__ );
		break;
}
// close the DB link until next time
@mysqli_close($dbLink);

// if the update was successful, go to show the patient 
//  otherwise go back to the entry form
if ($retVal['httpResponse'] == 200) {
	// update the redirect URL if the visit was deleted
	$updatedVisit = [];
	if ($retVal['count'] == 1) {
		$updatedVisit = $retVal['data'];
	} else {
		// this should never happen, but if it does, take the first record
		$updatedVisit = $retVal['data'][0];
	}
	if ($updatedVisit['deleted']) {
		// return to patient info page
		$redirectUrl = makeUrlWithQueryParams('/ptInfo.php?clinicPatientID', ['clinicPatientID' =>$updatedVisit['clinicPatientID']]);
	} else {
		// visit still exists so it can return normally
		$redirectUrl = $successUrl;
	}
	if (API_DEBUG_MODE) {
		header ('DEBUG_RetVal: '.json_encode($retVal));
	}
	header("Location: ".$redirectUrl);
} else {
	$msgText = ( $retVal['httpResponse'] == 409 ? "PATIENT_ID_IN_USE" : "NOT_UPDATED");
	$redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg' => MSG_NOT_UPDATED]);
	$logError = [];
	$logError['httpResponse'] =  $retVal['httpResponse'];
	$logError['httpReason'] = 'Error adding new record ('.$retVal['httpResponse'].')';
	$logError['error']['redirectUrl'] = $redirectUrl;
	$logError['error']['requestData'] = $requestData;
	logApiError($formData, $logError, __FILE__ );
	header('ResponseCode: '.$retVal['httpResponse']);
	header('ReasonCode: '.$retVal['httpReason']);
	header("Location: ".$redirectUrl);
}
//EOF