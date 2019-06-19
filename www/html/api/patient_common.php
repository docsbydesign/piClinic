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
 *	utility functions used by patient resource
 *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header('Content-Type: text/html; charset=utf-8');
	exit;	
}

function formatPatientNameLastFirst ($ptData) {
	$patientName = $ptData['lastName'];
	if (!empty($ptData['lastName2'])) {
		$patientName .= ' ' . $ptData['lastName2'];
	}
	$patientName .= ', ' . $ptData['firstName'];
	if (!empty($ptData['middleInitial'])) {
		$patientName .= ' ' . $ptData['middleInitial'];
		if (strlen($ptData['middleInitial']) == 1) {
			$patientName .= '.';
		}
	}
	return $patientName;
 }
 
 function formatPatientNameFirstLast ($ptData) {
	$patientName = $ptData['firstName'];
	if (!empty($ptData['middleInitial'])) {
		$patientName .= ' ' . $ptData['middleInitial'];
		if (strlen($ptData['middleInitial']) == 1) {
			$patientName .= '.';
		}
	}
	$patientName .= ' ' . $ptData['lastName'];
	if (!empty($ptData['lastName2'])) {
		$patientName .= ' ' . $ptData['lastName2'];
	}
	$patientName .= ', ' . $ptData['firstName'];
	return $patientName;
 }
 /*
 *
 *
 */
function makeOneQueryTermString ($searchTerm) {
	// replace _ for space character so terms with spaces can be used
	//  by passing them with an underscore.
	$searchTerm = str_replace ('_', ' ', $searchTerm);
	$termString = '(';
	$termString .= "`clinicPatientID` LIKE '".$searchTerm."' OR ";
	$termString .= "`familyID` LIKE '".$searchTerm."' OR ";
	$termString .= "`lastName` LIKE '%".$searchTerm."%' OR ";
	$termString .= "`lastName2` LIKE '%".$searchTerm."%' OR ";
	$termString .= "`firstName` LIKE '%".$searchTerm."%' OR ";
	$termString .= "`middleInitial` LIKE '%".$searchTerm."%' OR ";
	$termString .= "`homeNeighborhood` LIKE '".$searchTerm."%' OR ";
	$termString .= "`homeCity` LIKE '".$searchTerm."%' OR ";
	$termString .= "`homeCounty` LIKE '".$searchTerm."%' OR ";
	$termString .= "`homeState` LIKE '".$searchTerm."%' ";
	$termString .= ') ';
	return $termString;
	 
}
define ('MAX_SEARCH_TERMS', 5, false);
function makePatientSearchQuery ($searchString, $looseQuery=true) {
    $queryString = "SELECT * FROM `".
        DB_VIEW_PATIENT_GET. "` WHERE ";
    if ($looseQuery) {
        // create query string for get operation
        // explode string on spaces and create one query for each string (up to 5 terms)
        $queryTerms = explode (' ', $searchString, MAX_SEARCH_TERMS);
        if ($queryTerms === FALSE) {
            // no terms so return an empty query string;
            return '';
        } else {
            // add the whole string as a term the array if there are multiple terms
            if (count($queryTerms) > 1) {
                array_push($queryTerms, $searchString);
            }
        }
        // build query string
        $paramCount = 0;
        foreach ($queryTerms as $term) {
            if ($paramCount > 0) {
                $queryString .= ' OR ';
            }
            $queryString .= makeOneQueryTermString ($term);
            $paramCount += 1;
        }
    } else {
        // a tight query checks for an exact match of the ID fields
        $queryString .= "`clinicPatientID` LIKE '".$searchString."' OR ";
        $queryString .= "`patientNationalID` LIKE '".$searchString."' OR ";
        $queryString .= "`familyID` LIKE '".$searchString. "' ";
    }
	$queryString .= " ORDER BY `lastName`".DB_QUERY_LIMIT.";";
	return $queryString;
 }
/*
 *
 *
 */
function makePatientQueryStringFromRequestParameters ($requestParameters) {
	 // create query string for get operation
	$queryString = "SELECT * FROM `".
		DB_VIEW_PATIENT_GET. "` WHERE ";
	$paramCount = 0;
	if (!empty($requestParameters['clinicPatientID'])) {
		$queryString .= "`clinicPatientID` LIKE '".$requestParameters['clinicPatientID']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['familyID'])) {
		$queryString .= "`familyID` LIKE '".$requestParameters['familyID']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['lastName'])) {
		$queryString .= "`lastName` LIKE '%".$requestParameters['lastName']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['lastName2'])) {
		$queryString .= "`lastName2` LIKE '%".$requestParameters['lastName2']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['firstName'])) {
		$queryString .= "`firstName` LIKE '%".$requestParameters['firstName']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['middleInitial'])) {
		$queryString .= "`middleInitial` LIKE '%".$requestParameters['middleInitial']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['sex'])) {
		$queryString .= "`sex` LIKE '".$requestParameters['sex']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['birthDate'])) {
		$queryString .= "`birthDate` = '".$requestParameters['birthDate']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['homeNeighborhood'])) {
		$queryString .= "`homeNeighborhood` = '".$requestParameters['homeNeighborhood']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['homeCity'])) {
		$queryString .= "`homeCity` = '".$requestParameters['homeCity']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['homeCounty'])) {
		$queryString .= "`homeCounty` = '".$requestParameters['homeCounty']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['homeState'])) {
		$queryString .= "`homeState` = '".$requestParameters['homeState']."%' AND ";
		$paramCount += 1;
	}
	$queryString .= "TRUE ORDER BY `lastName`".DB_QUERY_LIMIT.";";
	return $queryString;
 }
 /*
 *
 *	Trims whitespace from string fields
 *
 */
 function cleanPatientStringFields ($patientIn) {
 	$patientOut = $patientIn;
	// clean strings of extra white space
	//  string fields are listed here
	$stringFields = array(
		'clinicPatientID'
		,'patientNationalID'
		,'lastName'
		,'lastName2'
		,'firstName'
		,'middleInitial'
		,'preferredLanguage'
		,'homeAddress1'
		,'homeAddress2'
		,'homeNeighborhood'
		,'homeCity'
		,'homeCounty'
		,'homeState'
		,'contactPhone'
		,'contactAltPhone'
		,'knownAllergies'
		,'currentMedications'
        ,'responsibleParty'
        ,'maritalStatus'
        ,'profession'
	);
	foreach ($stringFields as $fieldName) {
		if (isset($patientIn[$fieldName])) {
			$patientOut[$fieldName] = trim($patientIn[$fieldName]);
		}
	}
	return ($patientOut);	
 }
 //EOF