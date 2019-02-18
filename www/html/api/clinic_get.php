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
 *	Retrieves a clinic record
 * 		or an HTML error message
 *
 *	GET: Clinic records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The clinic record(s) will be returned that match the fields specified in the query parameter.
 *
 *			`publicID` the internal ID of the clinic record
 *          `shortName` wildcard match of the clinic's short name
 *          `thisClinic` if true, return the clinic designated as "this" clinic
 *
 *		Returns:
 *			200: the matching clinic record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *
 */
function _clinic_get ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);

	$dbInfo = array();
	$dbInfo['requestArgs'] = $requestArgs;

	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = 'Content-Type: text/plain; charset=utf-8';
	$returnValue['data'] = NULL;
	$returnValue['httpResponse'] = 404;
	$returnValue['httpReason']	= 'Resource not found.';

    $requiredParameters = [
        'publicID'
        , 'shortName'
        , 'thisClinic'
    ];

    //  NOTE: this is an "OR" test--only one is required
    $missingColumnList = "";
    $keyField = '';
    // make sure one of the ID columns is present
    foreach ($requiredParameters as $column) {
        if (empty($requestArgs[$column])) {
            if (!empty($missingColumnList)) {
                $missingColumnList .= ", ";
            }
            $missingColumnList .= $column;
        } else {
            // At least one was found, so clear the missing column list and continue
            $keyField = $column;
            $missingColumnList = '';
            break;
        }
    }

    if (!empty($missingColumnList)) {
        // one or more required fields are missing, so exit
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= ' Unable to find a clinic. One of these required query parameters is missing: ' . join( ', ', $requiredParameters);
        return $returnValue;
    }

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    // create query
    $getQueryString = '';

    switch ($keyField) {
        case 'publicID':
            $getQueryString = 'SELECT * FROM `'. DB_TABLE_CLINIC .
                '` WHERE `'.$keyField.'` = \''. $requestArgs[$keyField] . '\' '.
                DB_QUERY_LIMIT . ';';
            break;

        case 'shortName':
            $getQueryString = 'SELECT * FROM `'. DB_TABLE_CLINIC .
                '` WHERE `'.$keyField.'` LIKE \''. $requestArgs[$keyField] . '\' '.
                DB_QUERY_LIMIT . ';';
            break;

        case 'thisClinic':
            $getQueryString = 'SELECT * FROM `'. DB_VIEW_THISCLINIC .'` ' .
                DB_QUERY_LIMIT . ';';
            break;
    }
    if (API_DEBUG_MODE) {
        $dbInfo['getQueryString'] = $getQueryString;
    }

	// create query string for get operation
	$returnValue = getDbRecords($dbLink, $getQueryString);

    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }

	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF