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
 *	Retrieves info about a queued textmsg
 * 		or an HTML error message
 *
 *	GET: Returns textmsg information
 *
 *		Query paramters:
 *          zero or one of these ID field:
 *              'textmsgGUID'
 *              'patientID'
 *              'textmsgID'
 *          status={unsent, ready, sent, inactive}      default = all,
 *                                                          unsent = queued and ready,
 *                                                          ready = only ready,
 *                                                          sent = sent and success
 *                                                          inactive = sent and error
 *          count= max objects to return    default & max = 100, must be > 0
 *
 *		Response:
 *			textmsg data object
 *
 *		Returns:
 *			200: the textmsg object matching the token
 *			400: required field is missing or $_SERVER values did not match
 *			404: no matching textmsg found
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

/*
 *  Queries a token and returns its access if it's valid
 */
function _textmsg_get ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *      Initialize profiling if enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);

	// format not found return value
	$notFoundReturnValue = array();
	$notFoundReturnValue['contentType'] = CONTENT_TYPE_HTML;
	$notFoundReturnValue['data'] = NULL;
	$notFoundReturnValue['httpResponse'] = 404;
	$notFoundReturnValue['httpReason']	= "Resource not found.";
	$notFoundReturnValue['format'] = 'json';
	$notFoundReturnValue['count'] = 0;
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;
	$dbInfo ['apiUserToken'] = $apiUserToken;

    $logData = createLogEntry ('API',
        __FILE__,
        'textmsg',
        $_SERVER['REQUEST_METHOD'],
        null,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders ()),
        null,
        null,
        null);

    // check query parameters and build query

    $dbSortOrder = ' ORDER BY `sendDateTime` DESC';

    // check for ID parameters
    // can have any of these, but if any are present, only one can be used
    $requiredPatientColumns = [
        'textmsgGUID'
        , 'patientID'
        , 'textmsgID'
    ];

    $reqParamCount = 0;
    $missingColumnList = "";
    foreach ($requiredPatientColumns as $column) {
        if (empty($requestArgs[$column])) {
            if (!empty($missingColumnList)) {
                $missingColumnList .= ", ";
            }
            $missingColumnList .= $column;
        } else {
            $reqParamCount += 1;
            // save the parameter(s) found
            // they'll only be used if one is found
            $dbArgs[$column] = $requestArgs[$column];
            $dbKey = $column;
        }
    }

    if ($reqParamCount != 1) {
        // the required fields are not correct
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to get textmsg. Can have only one of these ID field(s): ". $missingColumnList;
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
        return $returnValue;
    }

    $dbFilter = array();

    if ($reqParamCount == 1) {
        // add the ID field to the query
       array_push($dbFilter, ' `'.$dbKey.'` = \''.$dbArgs[$dbKey] . '\'');
    }
    //  status={unsent, ready, sent, inactive}
    if (!empty($requestArgs['status'])) {
        switch ($requestArgs['status']) {
            case 'error':
                $statusFilter = " `nextSendDateTime` = NULL AND `lastSendStatus` != 'Success'";
                break;

            case 'inactive':
                $statusFilter = " `nextSendDateTime` = NULL";
                break;

            case 'ready':
                $statusFilter = " `nextSendDateTime` < NOW()";
                break;

            case 'sent':
                $statusFilter = " `nextSendDateTime` = NULL AND `lastSendStatus` = 'Success'";
                break;

            case 'unsent':
                $statusFilter = " `nextSendDateTime` IS NOT NULL";
                break;
        }
        if (!empty($statusFilter)) {
            array_push($dbFilter, $statusFilter);
        }
    }

    // count= max objects to return    default & max = 100, must be > 0
    $countFilter = DB_QUERY_LIMIT_COUNT;
    if (!empty($requestArgs['count'])) {
        if (is_numeric($requestArgs['count'])) {
            $countFilter = abs(intval($requestArgs['count']));
        }
        if ($countFilter > DB_QUERY_LIMIT_COUNT) {
            $countFilter = DB_QUERY_LIMIT_COUNT;
        }
        if ($countFilter < 1) {
            $countFilter = 1;
        }
    }
    // format the value for the DB Query
    $countFilter = ' LIMIT '. strval($countFilter);

	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    $getApiQueryString = 'SELECT * FROM `'. DB_TABLE_TEXTMSG . '` WHERE ';
    $filterString = '';
    if (empty($dbFilter)) {
        $filterString = ' 1';
    } else {
        foreach ($dbFilter as $filter) {
            if (!empty($filterString)) {
                $filterString .= ' AND';
            }
            $filterString .= $filter;
        }
    }
    $getApiQueryString .= $filterString;
    $getApiQueryString .= $dbSortOrder;
    $getApiQueryString .= strval($countFilter). ';';
    $dbInfo ['apiQueryString'] = $getApiQueryString;

    // get the textmsg record that matches--there should be only one
    $textmsgInfo = getDbRecords($dbLink, $getApiQueryString);
    $dbInfo ['apiReturnValue'] = $textmsgInfo;

    // and return here
    profileLogClose($profileData, __FILE__, $requestArgs);
    if (API_DEBUG_MODE  /*&&  $callerAccess == 2 */) {
        // only send this back to SystemAdmin queries
        $textmsgInfo['debug'] = $dbInfo;
    }

    return $textmsgInfo;
}
//EOF