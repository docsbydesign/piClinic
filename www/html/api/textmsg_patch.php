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
 *	Updates a queued text message in the database
 * 		or an HTML error message
 *
 *	PATCH: Updates an unsent textmsg in the database
 * 		input data:
 *
 *          textmsgGUID={messageGUID}}      updates this specific message (if not sent)
 * `        "status":                       Status from last send attempt:
 *                                              "Success" (marks message as sent)
 *                                              (anything else)  an unsuccessful attempt so retry is scheduled if any remain.*
 *		Response:
 *			success/error in full data object
 *
 *		Returns:
 *			201: the text message was queued
 *			400: required field is missing
 *          403: forbidden (trying to update a sent message)
 *          404: textmsg not found
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *  updates the test message entry
 */
function _textmsg_patch ($dbLink, $apiUserToken, $requestArgs) {
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
		$returnValue['httpReason']	= "Unable to update textmsg. Must have one and only one of the required field(s): ". $missingColumnList;
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
		return $returnValue;
	}

	// check status
    if (empty($requestArgs['status'])) {
        // nothing to do
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "No status value provided. Nothing to do.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
        return $returnValue;
    }

    $logData['logQueryString'] = $_SERVER['QUERY_STRING'];

   // here we have a valid parameters
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    // get the current record
    // create query string for get operation
    $currentQueryString = 'SELECT * FROM `'. DB_TABLE_TEXTMSG . '` WHERE `'.$dbKey.'` = \''. $dbArgs[$dbKey] . '\';';
    $dbInfo ['queryString'] = $currentQueryString;
    // get the textmsg record that matches--there should be only one
    $currentReturnValue = getDbRecords($dbLink, $currentQueryString);

    $messageFound = false;
    $currentMessage = null;
    if ($currentReturnValue['count'] == 1) {
        $currentMessage = $currentReturnValue['data'];
        // make sure the message is still active
        if (!empty($currentMessage['nextSendDateTime'])) {
            $messageFound = true;
            $dbInfo ['currentMessage'] = $currentMessage;
        }
    }

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
    $dbArgs[$dbKey] = $currentMessage[$dbKey]; // get ID field
    $dbArgs['lastSendAttempt'] = $currentMessage['lastSendAttempt'] + 1;
    $dbArgs['lastSendAttemptTime'] = $currentTime->format('Y-m-d H:i:s');
    $dbArgs['lastSendStatus'] = $requestArgs['status'];
    switch ($requestArgs['status']) {
        case 'Success':
            // message sent.
            $dbArgs['nextSendDateTime'] = null; // no more resending
            break;

        default:
            if ($dbArgs['lastSendAttempt'] >= $currentMessage['maxSendAttempts']) {
                // no more retries so clear the next time
                $dbArgs['nextSendDateTime'] = null; // no more resending
                $dbArgs['lastSendStatus'] = 'Error: Retries exhausted'; // override status
            } else {
                // try again
                $intervalString = 'PT'. $currentMessage['retryInterval'] .'S';
                $nextSendTime = $currentTime->add(new DateInterval($intervalString));
                $dbArgs['nextSendDateTime'] = $nextSendTime->format('Y-m-d H:i:s'); // next time to try again
            }
            break;
    }
	// save a copy for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;
    $updateColumns = 0;
    $insertQueryString = format_object_for_SQL_update (DB_TABLE_TEXTMSG, $dbArgs, $dbKey, $updateColumns);
	$dbInfo['insertQueryString'] = $insertQueryString;

	// try to update the message record in the database
    $textmsgInfo = array();

	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update the textmsg. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to update the textmsg. DB error.";
		}
	} else {
	    // successful creation
		profileLogCheckpoint($profileData,'UPDATE_RETURNED');

		// get the updated record to return
        // create query string for get operation
        $getQueryString = 'SELECT * FROM `'. DB_TABLE_TEXTMSG . '` WHERE `'.$dbKey.'` = \''. $dbArgs[$dbKey] . '\';';
        $dbInfo ['queryString'] = $getQueryString;
        // get the textmsg record that matches--there should be only one
        $getReturnValue = getDbRecords($dbLink, $getQueryString);

        if ($getReturnValue['count'] == 1) {
            $returnValue = $getReturnValue;
        } else {
            // this is a stale token so no access anymore
            $returnValue['data'] = '';
            $returnValue['count'] = 0;
            $returnValue['httpResponse'] = 500;
            $returnValue['httpReason'] = 'Unable to read updated record from database.';
        }
		if (is_object($qResult)) { @mysqli_free_result($qResult); }
	}

	$returnValue['contentType'] = CONTENT_TYPE_JSON;
	if (API_DEBUG_MODE) {
		$returnValue['debug'] = $dbInfo;
	}
	$logData['logAfterData'] = json_encode($returnValue['data']);
    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
// EOF
