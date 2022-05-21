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
 * DELETE: deletes the specified textmsg
 *          Deletion is actually just removing an unsent message from the active message list
 *          Deletion consists of:
 *              Removing the nextSendDateTime ( ==> null)
 *              setting the status to "Deleted"
 *		Query paramters:
 *			'Token' - the session token with permission to read messages
 *          patientID={{thisPatientID}}     deletes unsent text messages queued for this patient
 *          textmsgID={messageID}}          deletes this specific message (if not already sent)
 *          textmsgGUID={messageID}}        deletes this specific message (if not already sent)
 *
 *		Returns:
 *			200: No data
 *			400: required field is missing or $_SERVER values did not match
 *          403: forbidden (trying to delete a sent message)
 *			404: no matching textmsg found
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *  Closes a user session
 */
function _textmsg_delete ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *      Initialize profiling if enabled in piClinicConfig.php
     */
    $profileData = array();
    profileLogStart ($profileData);
    // Format return value and dbInfo array
    $returnValue = array();

    $dbInfo = array();
    $dbInfo ['requestArgs'] = $requestArgs;

    $dbArgs = array();
    $dbKey = '';

    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API', __FILE__, 'textmsg', $_SERVER['REQUEST_METHOD'],  $apiUserToken, null, null, null, null, null);

    // check for required parameters
    // must have only one of these.
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
        $returnValue['httpReason']	= "Unable to delete textmsg. Must have one and only one of the required field(s): ". $missingColumnList;
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
        return $returnValue;
    }

    $logData['logQueryString'] = $_SERVER['QUERY_STRING'];

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    // get the current record(s)
    // create query string for get operation
    $currentQueryString = 'SELECT * FROM `'. DB_TABLE_TEXTMSG . '` WHERE `'.$dbKey.'` = \''. $dbArgs[$dbKey] . '\';';
    $dbInfo['currentQueryString'] = $currentQueryString;
    // get the textmsg record that matches--there should be only one
    $currentReturnValue = getDbRecords($dbLink, $currentQueryString);

    $messageFound = false;
    $currentTextmsg = array();
    if ($currentReturnValue['count'] == 1) {
        $currentTextmsg[0] = $currentReturnValue['data'];
        $messageFound = true;
    } else if ($currentReturnValue['count'] > 1) {
        $currentTextmsg = $currentReturnValue['data'];
        $messageFound = true;
    } // else no textmsg found
    $dbInfo['currentTextmsg'] = $currentTextmsg;

    if (!$messageFound) {
        // message entry not found
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 404;
        $returnValue['httpReason']	= "textmsg not found.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_NOTFOUND);
        return $returnValue;
    }

    $currentTime = new DateTime();
    $dbArgs['modifiedDate'] = $currentTime->format('Y-m-d H:i:s');
    $dbArgs['nextSendDateTime'] = '';
    $dbArgs['lastSendStatus'] = 'Deleted';

    // check current textmsg entry and see if it can be deleted.

    $columnsUpdated = 0;
	profileLogCheckpoint($profileData,'UPDATE_READY');

    $deleteQueryString = format_object_for_SQL_update (DB_TABLE_TEXTMSG, $dbArgs, $dbKey, $columnsUpdated);

    if ($columnsUpdated > 0) {
        // append WHERE condition to only update active records
        $activeRecordCondition = ' AND `nextSendDateTime` IS NOT NULL;';
        str_replace (';', $activeRecordCondition, $deleteQueryString );
    }

    $dbInfo['deleteQueryString'] = $deleteQueryString;

	// try to update the record in the database
	$qResult = @mysqli_query($dbLink, $deleteQueryString);
	if (!$qResult) {
		// SQL ERROR
		// format response
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$dbInfo['deleteQueryString'] = $deleteQueryString;
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 500;
		$returnValue['httpReason']	= "Unable to delete user session. SQL DELETE query error.";
	} else {
		profileLogCheckpoint($profileData,'UPDATE_RETURNED');
        // get the updated record to return
        // create query string for get operation
        $getQueryString = 'SELECT * FROM `'. DB_TABLE_TEXTMSG . '` WHERE `'.
            $dbKey.'` = \''. $dbArgs[$dbKey] . '\' AND `modifiedDate` = \''.$currentTime->format('Y-m-d H:i:s') . '\';';
        $dbInfo['getQueryString'] = $getQueryString;
        // get the textmsg record that matches--there should be only one
        $getReturnValue = getDbRecords($dbLink, $getQueryString);

        if ($getReturnValue['count'] >= 1) {
            $returnValue = $getReturnValue;
        } else {
            // this is a stale token so no access anymore
            $returnValue['data'] = '';
            $returnValue['count'] = 0;
            $returnValue['httpResponse'] = 500;
            $returnValue['httpReason'] = 'Unable to read updated record from database.';
        }
        @mysqli_free_result($qResult);
	}
    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);

	$returnValue['contentType'] = CONTENT_TYPE_JSON;
	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
