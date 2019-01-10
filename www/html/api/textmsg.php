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
 *	Creates/Returns text message resources from the database
 * 		or an HTML error message
 *
 *	POST: Queues a new textmsg to the database
 * 		input data:
 *
 *          "messageText": message to send (1023 characters max)
 *          "patientID": patient ID (can be null)
 *          "destNumber": Phone number to send message to
 *          "sendDateTime": Time to send the first message
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
 *	PATCH: Updates an unsent textmsg in the database
 * 		input data:
 *
 *          testmsgGUID={messageID}}        updates this specific message (if not sent)
 *          "sendDateTime":                 Time to send the first message
 *          "nextSendDateTime":             when to try/retry sending the message
 *          "lastSendAttempt"               Last send attempt count
 *          "lastSendAttemptTime"           Last send attempt time
 *          "lastSendStatus":               Status from last send attempt
 *
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
 *
 *	GET: Returns textmsg information
 *
 *		Query paramters:
 *          patientID={{thisPatientID}}     returns text messages queued for this patient
 *          status={unsent, ready, sent, inactive}      default = all, unsent = queued and ready, ready = only ready, inactive = only sent
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
 * DELETE: deletes the specified textmsg
 *		Query paramters:
 *			'Token' - the session token with permission to read messages
 *          patientID={{thisPatientID}}     deletes unsent text messages queued for this patient
 *          testmsgGUID={messageID}}        deletes this specific message (if not sent)
 *
 *		Returns:
 *			200: No data
 *			400: required field is missing or $_SERVER values did not match
 *          403: forbidden (trying to delete a sent message)
 *			404: no matching textmsg found
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once '../shared/logUtils.php';
require_once '../shared/profile.php';
require_once '../shared/security.php';
require_once 'api_common.php';
require_once 'textmsg_common.php';
require_once 'textmsg_post.php';
require_once 'textmsg_get.php';
require_once 'textmsg_delete.php';
require_once 'textmsg_patch.php';

/*
 *      Initialize profiling if enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// Get the query paramater data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();
$retVal = array();

$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

$logData = createLogEntry ('API',
    __FILE__,
    'textmsg',
    $_SERVER['REQUEST_METHOD'],
    null,
    $_SERVER['QUERY_STRING'],
    json_encode(getallheaders ()),
    null,
    null,
    null
    );

// all methods need a token
if (empty( $apiUserToken)) {
    $retVal = formatMissingTokenError($retVal, 'session');
} else {
    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError($dbLink, $retVal, $apiUserToken, 'session', $logData);
        writeEntryToLog($dbLink, $logData);
    } else {
        $session = getSessionInfo($dbLink, $apiUserToken);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_STAFF, $session)) {
                   $retVal = _textmsg_post($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);                }
                break;

            case 'GET':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_READONLY, $session)) {
                    // only a valid token is required to GET session info
                    $retVal = _textmsg_get($dbLink, $apiUserToken, $requestData);
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }
                break;

            case 'PATCH':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_ADMIN, $session)) {
                   // $retVal = _textmsg_patch($dbLink, $apiUserToken, $requestData);
                    $retVal['contentType'] = CONTENT_TYPE_JSON;
                    if (API_DEBUG_MODE) {
                        $retVal['error'] = $requestData;
                    }
                    $retVal['httpResponse'] = 405;
                    $retVal['httpReason'] = $_SERVER['REQUEST_METHOD'] . ' method is not supported.';
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }

                break;

            case 'DELETE':
                if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_STAFF, $session)) {
                  //  $retVal = _textmsg_delete($dbLink, $apiUserToken, $requestData);
                    $retVal['contentType'] = CONTENT_TYPE_JSON;
                    if (API_DEBUG_MODE) {
                        $retVal['error'] = $requestData;
                    }
                    $retVal['httpResponse'] = 405;
                    $retVal['httpReason'] = $_SERVER['REQUEST_METHOD'] . ' method is not supported.';
                } else {
                    // caller does not have a valid security token
                    $retVal['httpResponse'] = 401;
                    $retVal['httpReason'] = "User account is not authorized to create this resource.";
                    $logData['logStatusCode'] = $retVal['httpResponse'];
                    $logData['logStatusMessage'] = $retVal['httpReason'];
                    writeEntryToLog($dbLink, $logData);
                }
                break;

            default:
                $retVal['contentType'] = CONTENT_TYPE_JSON;
                if (API_DEBUG_MODE) {
                    $retVal['error'] = $requestData;
                }
                $retVal['httpResponse'] = 405;
                $retVal['httpReason'] = $_SERVER['REQUEST_METHOD'] . ' method is not supported.';
                break;
        }
    }
}

    // close the DB link until next time0
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
exit;
//EOF