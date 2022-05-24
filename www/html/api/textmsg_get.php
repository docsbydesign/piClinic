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
