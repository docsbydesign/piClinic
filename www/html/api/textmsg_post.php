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
 *	Queues a text message to send later
 * 		or an HTML error message
 *
 *	POST: Queues a new textmsg to the database
 * 		input data:
 *
 *          "messageText": message to send (1023 characters max)
 *          "patientID": patient ID (can be null)
 *          "destNumber": Phone number to send message to
 *          "sendDateTime": Time to send the first message (null/not present = now)
 *          "sendService": "LocalMobile" is currently the only service supported
 *          "maxSendAttempts": how many times to try sending the message before giving up
 *          "retryInterval": how long to wait (in seconds) after a failed message to retry sending
 *
 *		Response:
 *			success/error in full data object
 *
 *		Returns:
 *			201: the text message was queued
 *			400: required field is missing
 *          404: patient not found
 *			500: server error information
 *
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *  add a new text message request to the message queue
 */
function _textmsg_post ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *      Initialize profiling if enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// Format return value and dbInfo array
	$returnValue = array();
	
	$dbInfo = array();
	$dbInfo ['requestArgs'] = $requestArgs;

    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API', __FILE__, 'textmsg', $_SERVER['REQUEST_METHOD'], $apiUserToken, null, null, null, null, null);

	// check for required parameters
	$requiredPatientColumns = [
		"messageText"
		,"destNumber"
		];

	$missingColumnList = "";
	foreach ($requiredPatientColumns as $column) {
		if (empty($requestArgs[$column])) {
			if (!empty($missingColumnList)) {
				$missingColumnList .= ", ";
			}
			$missingColumnList .= $column;
		}		
	}
	
	if (!empty($missingColumnList)) {
		// some required fields are missing so exit
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to create new textmsg. Required field(s): ". $missingColumnList. " are missing.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
        profileLogClose($profileData, __FILE__, $requestArgs);
		return $returnValue;
	}

    $logData['requestArgs'] = json_encode($requestArgs);

    // Build the DB request
    $dbArgs = array();

    // assign a unique ID for this message
    $dbArgs['textmsgGUID'] = guidString();

    // validate and store message text
    $dbArgs['messageText'] = substr(trim($requestArgs['messageText']), 0, MAX_TEXT_MESSAGE_LENGTH);

    // If there's a patientID, it must be valid
    // TODO: add in patient ID check after patient table has been implemented

    // Make sure that the Username returns a valid user record
	if (!empty($requestArgs['patientID'])) {
        $getQueryString = "SELECT * FROM `".
            DB_TABLE_PATIENT. "` WHERE `patientID` = '".
            $requestArgs['patientID']."';";
        $dbInfo['$getQueryString'] = $getQueryString;

        $returnValue = getDbRecords($dbLink, $getQueryString);
        $dbInfo['$data'] = $returnValue['data'];

        if ($returnValue['httpResponse'] != 200) {
            // the specified user does not exist in the database
            //  return an error
            if (API_DEBUG_MODE) {
                $returnValue['debug'] = $dbInfo;
            }
            $returnValue['httpResponse'] = 404;
            $returnValue['httpReason']	= "The username is not in the system. Check the username and try again.";
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
            profileLogClose($profileData, __FILE__, $requestArgs);
            return $returnValue;
        } else {
            if ($returnValue['count'] == 1) {
                $dbArgs['patientID'] =  $returnValue['data']['patientID'];
            } else {
                // more than one record is a server error because patientID is a unique key
                if (API_DEBUG_MODE) {
                    $returnValue['debug'] = $dbInfo;
                }
                $returnValue['httpResponse'] = 500;
                $returnValue['httpReason']	= "Multiple usernames were found in the system. Check the patientID and try again or contact the administrator.";
                $logData['logStatusCode'] = $returnValue['httpResponse'];
                $logData['logStatusMessage'] = $returnValue['httpReason'];
                writeEntryToLog ($dbLink, $logData);
                profileLogClose($profileData, __FILE__, $requestArgs);
                return $returnValue;
            }
        }
    }

    // copy the destination phone number
    $dbArgs['destNumber'] = $requestArgs['destNumber'];

	// copy the send date/time
    if (empty($requestArgs['sendDateTime'])) {
        // no time supplied, send the message ASAP
        $now = new DateTime();
        $dbArgs['sendDateTime'] = $now->format('Y-m-d H:i:s');
    } else {
        // try to read the time supplied and keep it if it seems to make sense
        $now = new DateTime($requestArgs['sendDateTime']);
        if ($now === false) {
            // bad parameter: unrecognized time string
            $returnValue['contentType'] = CONTENT_TYPE_JSON;
            if (API_DEBUG_MODE) {
                $returnValue['debug'] = $dbInfo;
            }
            $returnValue['httpResponse'] = 400;
            $returnValue['httpReason']	= "Unable to queue the text mssage. The sendDateTime value is not a valid time format.";
            $logData['logStatusCode'] = $returnValue['httpResponse'];
            $logData['logStatusMessage'] = $returnValue['httpReason'];
            writeEntryToLog ($dbLink, $logData);
            profileLogClose($profileData, __FILE__, $requestArgs);
            return $returnValue;
        } else {
            $dbArgs['sendDateTime'] = $now->format('Y-m-d H:i:s');
        }
    }

    // prepare for the first send attempt
    $dbArgs['nextSendDateTime'] = $dbArgs['sendDateTime'];

    // set the send service
    if (empty($requestArgs['sendService'])) {
        $dbArgs['sendService'] = DEFAULT_SEND_SERVICE;
    } else {
        $dbArgs['sendService'] = $requestArgs['sendService'];
    }

    if (empty($requestArgs['maxSendAttempts'])) {
        $dbArgs['maxSendAttempts'] = DEFAULT_TEXTMSG_MAX_SEND_ATTEMPTS;
    } else {
        $dbArgs['maxSendAttempts'] = $requestArgs['maxSendAttempts'];
    }

    if (empty($requestArgs['retryInterval'])) {
        $dbArgs['retryInterval'] = DEFAULT_RETRY_INTERVAL;
    } else {
        $dbArgs['retryInterval'] = $requestArgs['retryInterval'];
    }


    $now = new DateTime();
    $dbArgs['createdDate'] = $now->format('Y-m-d H:i:s');

	// if here, the parameters should work
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// save a copy for the debugging output
	$dbInfo['dbArgs'] = $dbArgs;

	// make insert query string to add new object to DB table
	profileLogCheckpoint($profileData,'POST_READY');
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_TEXTMSG, $dbArgs);
	if (API_DEBUG_MODE) {
		$dbInfo['insertQueryString'] = $insertQueryString;
	}
	// try to add the record to the database
	
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['insertQueryString'] = $insertQueryString;
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = CONTENT_TYPE_JSON;
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the response
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to create a new text message. ".$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= "Unable to create a new text message. DB error.";
		}
	} else {
	    // successful creation
		profileLogCheckpoint($profileData,'POST_RETURNED');

		// Get the newly added textmsg record
        $recallQueryString = "SELECT * FROM `". DB_TABLE_TEXTMSG. "` ".
    		"WHERE `textmsgGUID` = '".$dbArgs['textmsgGUID']."';";
        $dbInfo['recallQueryString'] = $recallQueryString;

        $getReturnValue = getDbRecords($dbLink, $recallQueryString);

        if ($getReturnValue['count'] >= 1) {
            $returnValue = $getReturnValue;
            // set status to reflect a successful addition
            $returnValue['httpResponse'] = 201;
            $returnValue['httpReason']	= "New text message created.";
        } else {
            // Could not recall the new record so this is broken
            $returnValue['data'] = '';
            $returnValue['count'] = 0;
            $returnValue['httpResponse'] = 500;
            $returnValue['httpReason'] = 'Unable to read updated record from database.';
        }

		@mysqli_free_result($qResult);
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