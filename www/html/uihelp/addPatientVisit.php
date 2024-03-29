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
 *	Formats the data from the newPatientVisit form and adds the patient visit record
 *
 *	POST: Adds a new patient visit record to the database
 * 		input data:
 * 			form data from newPatientVisit.php
 * 				or fields from ATA pt search page
 *			visitStaffUser
 * 			visitType
 *			visitDate
 * 			patientClientID
 *
 *		Returns:
 *			201: the new patient visit record created
 *			400: required field is missing
 *			409: patient visit record already exists error
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
require_once dirname(__FILE__).'/../api/visit_post.php';
require_once dirname(__FILE__).'/../api/staff_common.php';
require_once dirname(__FILE__).'/../api/staff_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = cleanUrlQueryParams($sessionInfo['parameters']);

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
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
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
	'clinicPatientID'
	,'visitType'
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
	$redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_NO_ACCESS]);
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
	$tempDateTime = date_create_from_format('Y-m-d H:i:s', $tempDateString );
	$requestData['dateTimeIn'] = date_format ($tempDateTime, 'Y-m-d H:i:s');
} else {
	$requestData['dateTimeIn'] = NULL; // the POST method will create one
}

if (!empty($formData['dateTimeOutYear']) &&
	!empty($formData['dateTimeOutMonth']) &&
	!empty($formData['dateTimeOutDay']) &&
	!empty($formData['dateTimeOutTime'])) {
	$tempDateString = $formData['dateTimeOutYear'].'-'.
		$formData['dateTimeOutMonth'].'-'.
		$formData['dateTimeOutDay'].' '.
		$formData['dateTimeOutTime'].':00';
	$tempDateTime = date_create_from_format('Y-m-d H:i:s', $tempDateString );
	$requestData['dateTimeIn'] = date_format ($tempDateTime, 'Y-m-d H:i:s');
} else {
	$requestData['dateTimeOut'] = NULL; // this will stay empty
}

//from optional query params (form data)
$dbOptionalFields = [
	// 'visitID' filled by DB
	'staffUsername'
	,'staffName'	// 	TODO: coming soon
    ,'firstVisit'
	// 'visitType' required (see above)
	,'visitStatus'		// assign if present, otherwise, use default
	,'primaryComplaint'
	,'secondaryComplaint'
    ,'payment'
	// ,'dateTimeIn' 		(see above)
	// ,'dateTimeOut'		(see above)
	// ,'patientID' required (see above)
	// ,'clinicPatientID' required (see above)
	// ,'PatientVisitId' required (see above)
    ,'height'
    ,'heightUnits'
    ,'weight'
    ,'weightUnits'
    ,'temp'
    ,'tempUnits'
    ,'bpSystolic'
    ,'bpDiastolic'
    ,'pulse'
    ,'glucose'
    ,'glucoseUnits'
	,'diagnosis1'
	,'condition1'
	,'diagnosis2'
	,'condition2'
	,'diagnosis3'
	,'condition3'
	,'referredTo'
	,'referredFrom'
];

foreach ($dbOptionalFields as $field){
	if (isset($formData[$field])){
		$requestData[$field] = $formData[$field];
	}
}
profileLogCheckpoint($profileData,'PARAMETERS_VALID');

$dbLink = _openDB();
$dbOpenError = mysqli_connect_errno();
if ( $dbOpenError  != 0  ) {
	$retVal = array();
	// database not opened.
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
	// return to referring URL with error message
	$redirectUrl = makeUrlWithQueryParams($errorUrl,['msg'=>MSG_DB_OPEN_ERROR]);
	$retVal['httpResponse'] = 500;
	$retVal['httpReason']   = MSG_DB_OPEN_ERROR;
	$dbInfo['sqlError'] = 'Error: '. $dbOpenError .', '.
		mysqli_connect_error();
	$dbInfo['redirectUrl'] = $redirectUrl;
	$dbInfo['requestData'] = $requestData;
	$retVal['error'] = $dbInfo;
	logApiError($formData, $retVal, __FILE__ );
	header("Location: ".$redirectUrl);
	return;
}

// look up the staff name if not passed in
if (!empty($formData['staffUsername']) && empty($formData['staffName'])) {
	// get the list of staff members
	// get the list of users, sorted by username
	$staffQueryString['username'] = $formData['staffUsername'];
	$staffResponse = _staff_get ($dbLink, $sessionInfo['token'], $staffQueryString);
	if (($staffResponse['httpResponse'] == 200) && ($staffResponse['count'] == 1)) {
		// one record was returned
		$requestData['staffName'] = $staffResponse['data']['lastName']. ', '.$staffResponse['data']['firstName'];
		$requestData['staffPosition'] = $staffResponse['data']['position'];
	}
}

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
	case 'POST':
		$retVal = _visit_post ($dbLink, $sessionInfo['token'], $requestData);
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$retVal['error']['requestData'] = $requestData;
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($formData, $retVal, __FILE__ );
		break;
}

// if the new record was added successfully, go to show the patient
//  otherwise go back to the entry form
if ($retVal['httpResponse'] == 201) {
	if ($formData['ata']){
        $formData['clinicPatientID'] = $retVal['data']['clinicPatientID'];
        $formData['patientVisitID'] = $retVal['data']['patientVisitID'];
        $formData['ata'] = true;
        $redirectUrl = makeUrlWithQueryParams('/ataVisitEdit.php', $formData);
	} else{
	    $successArgs = array(
            'clinicPatientID' => $retVal['data']['clinicPatientID'],
            'patientVisitID' => $retVal['data']['patientVisitID']
        );
	    if (VISIT_PRINT_FORM_AFTER_OPEN) {
            $redirectUrl = makeUrlWithQueryParams('/visitClinicForm0.php', $successArgs);
        } else {
            $redirectUrl = makeUrlWithQueryParams('/clinicDash.php', []);
        }
	}
    // close any of the workflows that this completes
    closeMatchingWorkflow($sessionInfo, __FILE__, $dbLink,
        ['VISIT_OPEN'], $workflowStep = WORKFLOW_STEP_COMPLETE, $retVal['data']);

	header("Location: ".$redirectUrl);
} else {
	$formData['msg']= ( $retVal['httpResponse'] == 409 ? "PATIENT_ID_IN_USE" : "NOT_UPDATED");
	$redirectUrl = makeUrlWithQueryParams('/visitOpen.php', $formData);
	$logError = [];
	$logError['httpResponse'] =  $retVal['httpResponse'];
	$logError['httpReason'] =  'Unsuccessful add or update';
	$logError['error']['redirectUrl'] = $redirectUrl;
	$logError['error']['requestData'] = $requestData;
	logApiError($formData, $logError, __FILE__ );
	header("Location: ".$redirectUrl);
}
profileLogClose($profileData, __FILE__, $formData);
// close the DB link until next time
@mysqli_close($dbLink);
return;
// EOF
