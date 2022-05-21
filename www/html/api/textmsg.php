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
 *          textmsgGUID={messageID}}        updates this specific message (if not sent)
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
 *          patientID={{thisPatientID}}         returns text messages for this patient\
 *          textmsgGUID={{GUID}}                returns the specified message
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
 *          textmsgGUID={messageID}}        deletes this specific message (if not sent)
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
    $retVal = formatMissingTokenError($retVal, 'textmsg');
} else {
    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError($dbLink, $retVal, $apiUserToken, 'textmsg', $logData);
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
                    $retVal = _textmsg_patch($dbLink, $apiUserToken, $requestData);
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
                    $retVal = _textmsg_delete($dbLink, $apiUserToken, $requestData);
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
