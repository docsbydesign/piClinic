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
        profileLogClose($profileData, __FILE__, $requestArgs);
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
        profileLogClose($profileData, __FILE__, $requestArgs);
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