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
	$icdArgs['lang'] = $lang;
	$icdArgs['ce'] = trim($icdCode);
	$getValue = _icd_get ($dbLink, $icdArgs);
	
	$returnString = '';
	if ($getValue['httpResponse'] == 200)  {
		// found something		
		if ($getValue['count'] == 1) {
			if ($showCode > 0) {
				$returnString = $getValue['data']['shortDescription'].' ['.$getValue['data']['icd10Code'].']';
			}
			if ($showCode < 0) {
				$returnString = '['.$getValue['data']['icd10Code'].'] '.$getValue['data']['shortDescription'];
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

function showDiagnosisInput ($dbLink, $visitInfo, $field, $lang, $placeholderText, $loadingText, $class='piClinicEdit', $charLimit=255, $autofocus=false, $requiredField=false) {
	// create text control
	$decodedValue = getIcdDescription($dbLink, $visitInfo[$field], $lang, SHOWCODE_CODE_AFTER_TEXT);

	$elemString = '<input '.
		'type="text" '.
		'id="'.$field.'InputId" '.
		'name="'.$field.'Desc" '.
		'list="diagData" '.
		'data-last-search="" '.
		'data-last-list-size="9999" '.
		($autofocus ? 'autofocus="autofocus" ' : '').
		($requiredField ? 'required ' : '').
		'onkeyup="inputKeyUpEventHandler (event, this, \'diagData\', \''.$lang.'\')" '.
		'onchange="setCodeValue(this, \'diagData\', \''.$field.'CodeId\', \''.$lang.'\')" '.
		'value="'.$decodedValue. '" '.
		'placeholder="'.$placeholderText.'" '. 'class="'. $class . '" '.
		'maxlength="'.(string)$charLimit.'"'.
		'/><span class="loading" id="'.$field.'InputIdLoading">'.$loadingText.'</span>'."\n".
		'<input '.
		'type="hidden" '.
		'id="'.$field.'CodeId" '.
		'name="'.$field.'" '.
		'value="'. $visitInfo[$field] .'" />'."\n";
	return $elemString;
}

function icdLookupJavaScript () {
	$js = "<script src=\"/assets/js/icdHelper.js\"></script>\n";
	return ($js);
}

 function conditionText ($conditionValue, $formatText = TRUE) {
	$conditionText = ''; 
	if ($formatText) {
		$conditionText = '<span class="inactive">'.CONDITION_BLANK.'</span>'; 
	 } 
	switch ($conditionValue) {
		case "NEWDIAG":
			$conditionText = CONDITION_NEW_SELECT;
			break;
		case "SUBSDIAG":
			$conditionText = CONDITION_SUBSEQUENT_SELECT;
			break;
		default:
			// leave as blank placeholder
			break;
	}
	return $conditionText;
}

?>