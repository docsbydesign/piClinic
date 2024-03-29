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
 *	Formats the data from the newPatient form and adds the patient record
 *
 *	Adds a new patient record to the database
 * 		input data:
 *			`clinicPatientID` - (Required) Patient ID issued by clinic.
 *   		`lastName` - (Required) Patient's last name(s)
 *   		`firstName` - (Required) Patient''s first name
 *			`middleInitial` - (Optional) Patient's middle initial
 *   		`sex` - (Required) 'Male','Female','Other' Patient''s sex
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
 * 			`organDonor` - (Optional) Patient''s organ donor preference
 * 			`PreferredLanguage` - '(Optional) Patient''s preferred language for communications
 *          `knownAllergies	` - (Optional) Patient's known allergies
 *			`currentMedications` - (Optional) Patient's current medications
 *
 *		Returns:
 *			201: the new patient record created
 *			400: required field is missing
 *			409: record already exists error
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
require_once dirname(__FILE__).'/../api/patient_common.php';
require_once dirname(__FILE__).'/../api/patient_post.php';
require_once dirname(__FILE__).'/../api/patient_patch.php';
require_once dirname(__FILE__).'/../validatePatient.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = $sessionInfo['parameters'];

// get referrer URL to return to in error or by default
$referringPageUrl = NULL;
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
	$referringPageUrl = cleanedRefererUrl();
} else {
	$referringPageUrl = '/clinicDash.php'; //default: return is the home page
}

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// check for authorization to access this page
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], PAGE_ACCESS_CLINIC, $sessionInfo)){
    // show this in the error div
    $requestData['msg'] = MSG_NO_ACCESS;
    $redirectUrl = makeUrlWithQueryParams($errorUrl, $requestData);
    $logError = [];
    $logError['httpResponse'] =  403;
    $logError['httpReason'] = 'User account is not authorized to access this resource.';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'patient', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    exit;
}

// assign URLs to go to after action
$errorUrl = $referringPageUrl;
$successUrl = $referringPageUrl;
// use URL from query params if one is found
if (!empty($formData['returnUrl'])) {
	$successUrl = $formData['returnUrl'];
}


// format arguments for database update
// copy from request data sent by form
$ptFields = [
  'clinicPatientID',
  'patientNationalID',
  'familyID',
  'lastName',
  'lastName2',
  'firstName',
  'middleInitial',
  'sex',
//  'birthDate',	// this is created from the Y-m-d fields
//  'nextVaccinationDate // created from the Y-m-d fields
  'homeAddress1',
  'homeAddress2',
  'homeNeighborhood',
  'homeCity',
  'homeCounty',
  'homeState',
  'contactPhone',
  'contactAltPhone',
  'bloodType',
  'organDonor',
  'preferredLanguage'
//  'knownAllergies',	// formatted below
//  'currentMedications'	// formatted below
  ,'responsibleParty'
  ,'maritalStatus'
  ,'profession'
];

foreach ($ptFields as $field) {
	if (isset($formData[$field])) {
		$requestData[$field] = trim($formData[$field]);
	}
}

// update the exceptions
if (!empty($formData['birthDateMonth']) &&
	!empty($formData['birthDateDay']) &&
	!empty($formData['birthDateYear'])) {
	// create a valid date string
	$dateString = $formData['birthDateYear'].'-'.
		$formData['birthDateMonth'].'-'.
		$formData['birthDateDay'].' 00:00:00';
	$birthDateTime = date_create_from_format('Y-m-d G:i:s', $dateString );
	$requestData['birthDate'] = date_format ($birthDateTime, 'Y-m-d G:i:s');
}

// update the exceptions
if (!empty($formData['nextVaccinationDateMonth']) &&
    !empty($formData['nextVaccinationDateDay']) &&
    !empty($formData['nextVaccinationDateYear'])) {
    // create a valid date string
    $dateString = $formData['nextVaccinationDateYear'].'-'.
        $formData['nextVaccinationDateMonth'].'-'.
        $formData['nextVaccinationDateDay'].' 00:00:00';
    $nextVaccinationDateTime = date_create_from_format('Y-m-d G:i:s', $dateString );
    $requestData['nextVaccinationDate'] = date_format ($nextVaccinationDateTime, 'Y-m-d G:i:s');
}

// clear the query parameters that shouldn't be repeated
unset ($formData['msg']);

// format the known allergies data
if (!empty($formData['knownAllergies'])){
	$requestData['knownAllergies'] = str_replace("\n",'|',$formData['knownAllergies']);
}

// format the currentMedications data
if (!empty($formData['currentMedications'])){
	$requestData['currentMedications'] = str_replace("\n",'|',$formData['currentMedications']);
}

// Validate data fields
$validationType = PT_VALIDATE_NEW;
if (!empty($formData['_method']) &&  ($formData['_method'] == 'PATCH')) {
    $validationType = PT_VALIDATE_UPDATE;
}

// the validationOption is defined in clinicSpecific.php
$validationResponse = validatePatient($requestData, $validationType, PT_VALIDATE_MODE);
if (!$validationResponse['valid']) {
    // show this in the error div
    $requestData['msg'] = MSG_VALIDATION_FAILED;
    $redirectUrl = makeUrlWithQueryParams($errorUrl, $requestData);
    $logError = [];
    $logError['httpResponse'] =  400;
    $logError['httpReason'] = $validationResponse['message'];
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'patient', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    exit;
} else {
    if (API_DEBUG_MODE) {
        header("DEBUG: " . json_encode($validationResponse));
    }
}

profileLogCheckpoint($profileData,'PARAMETERS_VALID');

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		if (!empty($formData['_method']) &&  ($formData['_method'] == 'PATCH')) {
			$retVal = _patient_patch($dbLink, $sessionInfo['token'], $requestData);
		} else {
			$retVal = _patient_post($dbLink, $sessionInfo['token'], $requestData);
		}
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$retVal['error'] = $requestData;
		}
		$retVal['error']['requestData'] = $requestData;
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($formData, $retVal, __FILE__ );
		break;
}

// if the update was successful, go to show the patient
//  otherwise go back to the entry form
if (!empty($formData['_method']) &&  ($formData['_method'] == 'PATCH')) {
	// successful update returns 200
	if ($retVal['httpResponse'] == 200) {
		$redirectUrl = '/ptInfo.php?clinicPatientID='.$requestData['clinicPatientID'];
        closeMatchingWorkflow($sessionInfo, __FILE__, $dbLink,
            ['PT_EDIT'], $workflowStep = WORKFLOW_STEP_COMPLETE, $retVal['data']);
		header("Location: ".$redirectUrl);
	} else {
		$returnQP = "";
		foreach ($formData as $key => $val) {
			if (isset($val)) {
				if (isset($returnQP)) {
					$returnQP .= '&';
				}
				$returnQP .= $key.'='.urlencode($val);
			}
		}
		$msgText = ( $retVal['httpResponse'] == 409 ? "PATIENT_ID_IN_USE" : "NOT_UPDATED");
		$returnQP = 'msg='.
			($retVal['httpResponse'] == 409 ? "PATIENT_ID_IN_USE" : "NOT_UPDATED").
			'&'.$returnQP.'&updateErr=';
		$redirectUrl = '/ptAddEdit.php?'.$returnQP;
		$logError = [];
		$logError['httpResponse'] =  $retVal['httpResponse'];
		$logError['httpReason'] = 'Unsuccessful update';
		$logError['error']['redirectUrl'] = $redirectUrl;
		$logError['error']['requestData'] = $requestData;
		logApiError($formData, $logError, __FILE__ );
		header("DEBUG: ".json_encode($logError));
		header("Location: ". $redirectUrl);
	}
} else {
	// patient record was created
	$redirectUrl = '';
	if ($retVal['httpResponse'] == 201) {
		if (!empty($formData['ata'])) {
			// this patient was added to create a new visit
			$searchValue = '';
			if (($retVal['count'] == 1) && (!empty($retVal['data']['familyID']))) {
				$searchValue = $retVal['data']['familyID'];
			} else {
				// recycle q from ataEntry
				$searchValue = $formData['q'];
			}
			$redirectUrl = '/ptResults.php?q='.$searchValue.'&ata=true'.
				(isset($formData['visitType']) ? '&visitType='.$formData['visitType'] :'').
				(isset($formData['visitStaffUser']) ? '&visitStaffUser='.$formData['visitStaffUser'] :'').
				(isset($formData['visitDateYear']) ? '&visitDateYear='.$formData['visitDateYear'] :'').
				(isset($formData['visitDateMonth']) ? '&visitDateMonth='.$formData['visitDateMonth'] :'').
				(isset($formData['visitDateDay']) ? '&visitDateDay='.$formData['visitDateDay'] :'').
				(isset($formData['visitDateTime']) ? '&visitDateTime='.$formData['visitDateTime'] :'');
		} else {
			//  this patient was added for another reason so show the new patient
			$redirectUrl = '/ptInfo.php?clinicPatientID='.$requestData['clinicPatientID'];
		}
        closeMatchingWorkflow($sessionInfo, __FILE__, $dbLink,
            ['PT_ADD_NEW'], $workflowStep = WORKFLOW_STEP_COMPLETE, $retVal['data']);
		header("httpReason: Successful new record");
		header("Location: ".$redirectUrl);
	} else {
		$returnQP = "";
		foreach ($formData as $key => $val) {
			if (isset($val)) {
				if (isset($returnQP)) {
					$returnQP .= '&';
				}
				$returnQP .= $key.'='.urlencode($val);
			}
		}
		$returnQP = 'msg='.
			($retVal['httpResponse'] == 409 ? "PATIENT_ID_IN_USE" : "NOT_CREATED").
			'&'.$returnQP.'&updateErr=';
		$redirectUrl = '/ptAddEdit.php?'.$returnQP;
		$logError = [];
		$logError['httpResponse'] =  $retVal['httpResponse'];
		$logError['httpReason'] = 'Error adding new record ('.$retVal['httpResponse'].') - '.$retVal['httpReason'];
		$logError['error']['redirectUrl'] = $redirectUrl;
		$logError['error']['requestData'] = $requestData;
		logApiError($formData, $logError, __FILE__ );
		header("DEBUG: ".json_encode($logError));
		header("Location: ".$redirectUrl);
	}
}
// close the DB link until next time
@mysqli_close($dbLink);

profileLogClose($profileData, __FILE__, $formData);
return;
//EOV
