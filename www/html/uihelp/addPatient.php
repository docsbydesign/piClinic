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

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = $sessionInfo['parameters'];

// get referrer URL to return to in error or by default
$referringPageUrl = NULL;
if (isset($_SERVER['HTTP_REFERER'])) {
	$referringPageUrl = $_SERVER['HTTP_REFERER'];
} else {
	$referringPageUrl = '/clinicDash.php'; //default: return is the home page
}

// assign URLs to go to after action
$errorUrl = $referringPageUrl;
$successUrl = $referringPageUrl;
// use URL from query params if one is found
if (!empty($formData['returnUrl'])) {
	$successUrl = $formData['returnUrl'];
}

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

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
//  'birthDate',	// this is created form the Y-m-d fields
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
  'PreferredLanguage'
//  'knownAllergies',	// formatted below
//  'currentMedications'	// formatted below
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
// close the DB link until next time
@mysqli_close($dbLink);

// if the update was successful, go to show the patient 
//  otherwise go back to the entry form
if (!empty($formData['_method']) &&  ($formData['_method'] == 'PATCH')) {
	// successful update returns 200
	if ($retVal['httpResponse'] == 200) {
		$redirectUrl = '/ptInfo.php?clinicPatientID='.$requestData['clinicPatientID'];
		if (!empty($formData['lang'])) { 
			$redirectUrl .="&lang=".$formData['lang']; 
		}
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
		if (!empty($formData['lang'])) { 
			$redirectUrl .="&lang=".$formData['lang']; 
		}
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
profileLogClose($profileData, __FILE__, $formData);
return;
?>