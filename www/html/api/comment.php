<?php
/*
 *
 * Copyright (c) 2018 by Robert B. Watson
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
 *	Creates/Returns comment resources from the database
 * 		or an HTML error message
 *
 *	POST: Adds a new comment record to the database
 * 		input data:
 *   		`CommentDate` - (Optional) date comment was started
 *  		`Username` - (Required) Username creating this session.
 *  		`ReferringUrl` - (Optional) Page from which comment page was called.
 *			`ReferringPage` - (Optional) Page name from path.
 *  		`ReturnUrl` - (Optional) Page to which user was sent after making the comment.
 *  		`CommentText` - (0Optional) User comment text.
 *
 *		Returns:
 *			201: the new comment record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *	GET: Returns comment records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The comment record(s) will be returned that the fields specified in the query parameter.
 *
 *   		`CommentDate` - (Optional) date comment was started
 *  		`Username` - (Required) Username creating this session.
 *  		`ReferringUrl` - (Optional) Page URL from which comment page was called.
 *			`ReferringPage` - (Optional) Page name from path.
 *  		`ReturnUrl` - (Optional) Page to which user was sent after making the comment.
 *  		`CommentText` - (0Optional) User comment text.
 *			`createdDate` - Date user account was created
 *
 *		Returns:
 *			200: the matching comment record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once 'comment_common.php';
require_once 'comment_post.php';
require_once 'comment_get.php';
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query paramater data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders();

$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

$retVal = array();

if (empty($apiUserToken)){
    $retVal = formatMissingTokenError ($retVal, 'comment');
} else {
    // Initalize the log entry for this call
    //  more fields will be added later in the routine
    $logData = createLogEntry ('API',
        __FILE__,
        'comment',
        $_SERVER['REQUEST_METHOD'],
        null,
        $_SERVER['QUERY_STRING'],
        json_encode(getallheaders ()),
        null,
        null,
        null);

    if (!validTokenString($apiUserToken)) {
        $retVal = logInvalidTokenError ($dbLink, $retVal, $apiUserToken, 'comment', $logData);
    } else {
        $logData['userToken'] = $apiUserToken;
        if (checkUiSessionAccess($dbLink, $apiUserToken, PAGE_ACCESS_READONLY)) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $retVal = _comment_post($dbLink, $apiUserToken, $requestData);
                    break;

                case 'GET':
                    $retVal = _comment_get($dbLink, $apiUserToken, $requestData);
                    break;

                case 'OPTIONS':
                    $retVal = createOptionsResponse ();
                    break;
                
                default:
                    $retVal['contentType'] = 'Content-Type: application/json';
                    if (API_DEBUG_MODE) {
                        $retVal['error'] = $requestData;
                    }
                    $retVal['httpResponse'] = 405;
                    $retVal['httpReason'] = 'Method not supported.';
                    break;
            }
        } else {
            // caller does not have a valid security token
            $retVal['httpResponse'] = 401;
            $retVal['httpReason'] = "User account is not authorized to access this resource.";
        }
    }
}
// close the DB link until next time
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
//EOF
