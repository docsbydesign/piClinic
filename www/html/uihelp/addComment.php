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
 *	Formats the data from the userComment form and adds or updates the comment record
 *
 *	POST: Adds a new comment record to the database
 * 		input data:
 *   		`CommentDate` - (Optional) date comment was started
 *  		`Username` - (Required) Username creating this session.
 *  		`ReferringUrl` - (Optional) Page URL from which comment page was called.
 *  		`ReferringPage` - (Optional) Page from which comment page was called.
 *  		`ReturnUrl` - (Optional) Page to which user was sent after making the comment.
 *  		`CommentText` - (0Optional) User comment text.
 *
 *
 *
 *********************/
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/headTag.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../shared/profile.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';
require_once dirname(__FILE__).'/../api/comment_common.php';
require_once dirname(__FILE__).'/../api/comment_post.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = $sessionInfo['parameters'];

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// check for authorization to access this page
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], PAGE_ACCESS_READONLY, $sessionInfo)){
    // show this in the error div
    $requestData['msg'] = MSG_NO_ACCESS;
    $redirectUrl = makeUrlWithQueryParams($errorUrl, $requestData);
    $logError = [];
    $logError['httpResponse'] =  403;
    $logError['httpReason'] = 'User account is not authorized to access this resource.';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'comment', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    exit;
}

// clear the query parameters that shouldn't be repeated
unset ($formData['msg']);

// copy and interpret form fields
$stringFields = array(
	'username'
	,'commentDate'
	,'referringUrl'
	,'returnUrl'
	,'commentText'
	,'referringPage'
);
$requestData = [];
foreach ($stringFields as $fieldName) {
	// copy only the fields with a value
	// did not use empty because it returns false for 0, but 0 is valid for Active
	// isset returns true for empty strings which are stored as NULL in the DB
	if (isset($formData[$fieldName]) && strlen($formData[$fieldName]) > 0) {
		$requestData[$fieldName] = $formData[$fieldName];
	}
}
profileLogCheckpoint($profileData,'PARAMETERS_VALID');

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$retVal = _comment_post($dbLink, $sessionInfo['token'], $requestData);
		break;

	default:
		$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$retVal['error']['requestData'] = $requestData;
		$retVal['httpResponse'] = 405;
		$retVal['httpReason']	= "Method not supported.";
		logApiError($formData, $retVal, __FILE__ );
		break;
}
// close the DB link until next time
@mysqli_close($dbLink);

// return to the page before the comment form
//  or the edit page if not
if ($retVal['httpResponse'] == 201) {
	$redirectUrl = '';
	if (!empty($formData['returnUrl'])) {
		// a return URL was provided
		$redirectUrl = $formData['returnUrl'];
	} else {
		// use the default and return to home
		$redirectUrl = '/clinicDash.php';
	}
	header("httpReason: Successful update");
	header("Location: ".$redirectUrl);
} else {
	// redirect back to edit page with error message
	$returnQP = "";
	foreach ($formData as $key => $val) {
		if (isset($val)) {
			if (!empty($returnQP)) {
				$returnQP .= '&';
			}
			$returnQP .= $key.'='.urlencode($val);
		}
	}
	$redirectUrl = '/userComment.php?'.$returnQP;
	$logError = [];
	$logError['httpResponse'] =  $retVal['httpResponse'];
	$logError['httpReason'] =  'Unsuccessful add';
	$logError['error']['redirectUrl'] = $redirectUrl;
	$logError['error']['requestData'] = $requestData;
	logApiError($formData, $logError, __FILE__ );
	header("Location: ".$redirectUrl);
}
profileLogClose($profileData, __FILE__, $formData);
return;
// EOF
