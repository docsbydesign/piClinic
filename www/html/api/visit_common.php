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
 *	utility functions used by visit resource
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

require_once 'icd_common.php';
require_once 'icd_get.php';

function makeVisitQueryStringFromRequestParameters ($requestParameters) {
	 // create query string for get operation
	$queryString = "SELECT * FROM `". DB_VIEW_VISIT_GET. "` WHERE ";
	$paramCount = 0;
	// only one of clinicPatientID or visitID should be present.
	//  if both are present, use visitID
	if (!empty($requestParameters['clinicPatientID'])) {
		$queryString .= "`clinicPatientID` LIKE '".$requestParameters['clinicPatientID']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['visitID'])) {
		// this value must be a non-negative integer
		if (! filter_var($requestParameters['visitID'], FILTER_VALIDATE_INT)) {
			return "";
		} else {
			if (intval($requestParameters['visitID']) <= 0) {
				return "";
			}
		}
		// for this query, we use the visit table, and not the GET view
		$queryString .= DB_TABLE_VISIT. "` WHERE ";
		$queryString .= "`visitID` = ".$requestParameters['visitID']." AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['patientVisitID'])) {
		$queryString .= "`patientVisitID` LIKE '".$requestParameters['patientVisitID']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['visitType'])) {
		$queryString .= "`visitType` LIKE '".$requestParameters['visitType']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['visitStatus'])) {
		$queryString .= "`visitStatus` LIKE '".$requestParameters['visitStatus']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['staffName'])) {
		$queryString .= "`staffName` LIKE '".$requestParameters['staffName']."' AND ";
		$paramCount += 1;
	}

	if ($paramCount > 0) {
		$queryString .= "TRUE ORDER BY ";
		if (!empty($requestParameters['sortfield'])) {
			$queryString .= '`'.$requestParameters['sortfield'].'` '; // invalid field will be caught by mysql call
		} else {
			$queryString .= '`dateTimeIn` ';
		}
		if (!empty($requestParameters['sortorder'])) {
			$queryString .= $requestParameters['sortorder']; // invalid order will be caught by mysql call
		} else {
			$queryString .= 'DESC';
		}
		$queryString .= ' '.DB_QUERY_LIMIT.';';
	} else {
		// no valid query parameters found so return an empty query.
		$queryString = "";
	}
	return $queryString;
}
 /*
 *
 *	Trims whitespace from string fields
 *
 */
 function cleanVisitStringFields ($visitIn) {
	// copy all fields and then clean those with strings
 	$visitOut = $visitIn;
	//  default list of string fields are listed here
	$stringFields = array(
		'clinicPatientID'
		,'patientNationalID'
		,'patientFamilyID'
		,'visitType'
		,'primaryComplaint'
		,'secondaryComplaint'
		,'dateTimeIn'
		,'dateTimeOut'
        ,'payment'
		// These are checked by POST and shouldn't change after creation
		// 'patientID'
		// 'clinicPatientID'
		// 'patientVisitID'
		// 'patientLastName'
		// 'patientFirstName'
		// 'patientSex'
		// 'patientBirthDate'
		// 'patientHomeAddress1'
		// 'patientHomeAddress2'
		// 'patientHomeNeighborhood'
		// 'patientHomeCity'
		// 'patientHomeCounty'
		// 'patientHomeState'
        //  'height'
        ,'heightUnits'
        //  'weight'
        ,'weightUnits'
        //  'temp'
        ,'tempUnits'
        //  'bpSystolic'
        //  'bpDiastolic'
        //  'pulse'
        //  'glucose'
        ,'glucoseUnits'
        ,'diagnosis1'
		,'diagnosis2'
		,'diagnosis3'
		,'referredTo'
		,'referredFrom'
	);
	// trim the string fields in the $visitIn array
	//  and copy the processed ones to $visitOut
	//  and remove the empty ones from the array
	foreach ($stringFields as $fieldName) {
		if (!empty($visitIn[$fieldName])) {
			$visitOut[$fieldName] = trim($visitIn[$fieldName]);
			// set case on selected fields
            switch($visitOut[$fieldName]) {
                case 'heightUnits':
                case 'weightUnits':
                    $visitOut[$fieldName] = strtolower($visitOut[$fieldName]);
                    break;

                case 'tempUnits':
                case 'glucoseUnits':
                    $visitOut[$fieldName] = strtoupper($visitOut[$fieldName]);
                    break;

                default:
                    // do nothing
                    break;
            }
		} else {
			unset($visitOut[$fieldName]);
		}
	}
	return ($visitOut);
 }

define ('SHOWCODE_TEXT_ONLY',0,false);
define ('SHOWCODE_CODE_BEFORE_TEXT',-1,false);
define ('SHOWCODE_CODE_AFTER_TEXT',1,false);

function getIcdDescription ($dbLink, $icdCode, $lang, $showCode=0) {
	// looks up code in specified language and returns
	// description if found, or code, if not.
	//  $showCode:
	//		0 = don't show, just return the corresponding text
	//		-1 = show code before text
	//		+1 = show code after text

	$icdArgs = [];
	$icdArgs['language'] = $lang;
	$icdArgs['ce'] = trim($icdCode);
	$getValue = _icd_get ($dbLink, null,  $icdArgs);

	$returnString = '';
	if ($getValue['httpResponse'] == 200)  {
		// found something
		if ($getValue['count'] == 1) {
			if ($showCode > 0) {
				$returnString = $getValue['data']['shortDescription'].' ['.$getValue['data']['icd10code'].']';
			}
			if ($showCode < 0) {
				$returnString = '['.$getValue['data']['icd10code'].'] '.$getValue['data']['shortDescription'];
			}
			if ($showCode == 0) {
				$returnString = $getValue['data']['shortDescription'];
			}
			return $returnString;
		} else {
			// only one should match
			return ($icdCode);
		}
	} else {
		// no match
		return ($icdCode);
	}
}

function showDiagnosisInput ($dbLink, $inputValue, $field, $sessionInfo, $placeholderText, $loadingText, $class='piClinicEdit', $charLimit=255, $autofocus=false, $requiredField=false) {
	// create text control
	$decodedValue = getIcdDescription($dbLink, $inputValue, $sessionInfo['pageLanguage'], SHOWCODE_CODE_AFTER_TEXT);

	$elemString = '<input '.
		'type="text" '.
		'id="'.$field.'InputId" '.
		'name="'.$field.'Desc" '.
		'list="diagData" '.
		'data-last-search="" '.
		'data-last-list-size="9999" '.
		($autofocus ? 'autofocus="autofocus" ' : '').
		($requiredField ? 'required ' : '').
		'onkeyup="inputKeyUpEventHandler (event, this, \'diagData\', \''.$sessionInfo['token'].'\', \''.$sessionInfo['pageLanguage'].'\')" '.
		'onchange="setCodeValue(this, \'diagData\', \''.$field.'CodeId\', \''.$sessionInfo['token'].'\', \''.$sessionInfo['pageLanguage'].'\')" '.
		'value="'.$decodedValue. '" '.
		'placeholder="'.$placeholderText.'" '. 'class="'. $class . '" '.
		'maxlength="'.(string)$charLimit.'"'.
		'/><span class="loading" id="'.$field.'InputIdLoading">'.$loadingText.'</span>'."\n".
		'<input '.
		'type="hidden" '.
		'id="'.$field.'CodeId" '.
		'name="'.$field.'" '.
		'value="'. $inputValue .'" />'."\n";
	return $elemString;
}

function icdLookupJavaScript () {
	$js = "<script src=\"/assets/js/icdHelper.js\"></script>\n";
	return ($js);
}

 function conditionText ($conditionValue, $formatText = TRUE) {
	$conditionText = '';
	if ($formatText) {
		$conditionText = '<span class="inactive">'.TEXT_CONDITION_BLANK.'</span>';
	 }
	switch ($conditionValue) {
		case "NEWDIAG":
			$conditionText = TEXT_CONDITION_NEW_REPORT;
			break;
		case "SUBSDIAG":
			$conditionText = TEXT_CONDITION_SUBSEQUENT_REPORT;
			break;
		default:
			// leave as blank placeholder
			break;
	}
	return $conditionText;
}

?>
