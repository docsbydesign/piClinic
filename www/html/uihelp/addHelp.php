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
 *	Formats the data from the help content entry form and adds or updates the comment record
 *
 *	POST: Adds a new comment record to the database
 * 		POST input data:
 *
 *    {
 *       "topicID": "Sample",
 *       "refPage": null,
 *       "en": {
 *           "helpID": "1",
 *           "helpText": "This is sample 1 in english",
 *           "lastChangeBy": "SystemAdmin"
 *       },
 *       "es": {
 *           "helpID": "2",
 *           "helpText": "Este es un ejemplo en espanol",
 *           "lastChangeBy": "SystemAdmin"
 *       }
 *    }
 *
 *********************/
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../shared/profile.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$formData = $sessionInfo['parameters'];
// get supported languages
require dirname(__FILE__).'/../uiSessionInfo.php';

$successUrl = '/adminHome.php';
$errorUrl =  'adminHelpAddEdit.php';

$postData = array();
if (!empty($formData['topic'])){
    $supportedLanguages = getSupportedUiLanguages();
    $postIdx = 0;
    foreach ($supportedLanguages as $thisLanguage => $langName){
        if (!empty($formData['topic'][$thisLanguage])) {
            $postData[$postIdx]['helpID']  = $formData['topic'][$thisLanguage]['helpID'];
            $postData[$postIdx]['topicID'] = $formData['topic']['topicID'];
            $postData[$postIdx]['language'] = $thisLanguage;
            $postData[$postIdx]['refPage'] = $formData['topic']['refPage'];
            $postData[$postIdx]['helpText'] = $formData['topic'][$thisLanguage]['helpText'];
            $postIdx += 1;
        }
    }

    if (!empty($formData['topic']['errorUrl'])) {
        $errorUrl = $formData['topic']['errorUrl'];
    }
    if (!empty($formData['topic']['successUrl'])) {
        $successUrl = $formData['topic']['successUrl'];
    }
}

if (!empty($postData)) {
    $updateQueries = array();
    foreach ($postData as $helpTopic) {
        $queryString = '';
        if (empty($helpTopic['helpID'])) {
            // it's a new topic
            $queryString =  format_object_for_SQL_insert (DB_TABLE_HELP, $helpTopic);
        } else {
            // it's an update of an existing topic
            $columnsAdded = 0;
            $queryString = format_object_for_SQL_update (DB_TABLE_HELP, $helpTopic, 'helpID',$columnsAdded);
            assert ($columnsAdded > 0, 'ERROR: Help topic update had no data.');
        }
        array_push($updateQueries, $queryString);
    }
}

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// check for authorization to access this page
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], PAGE_ACCESS_CLINIC, $sessionInfo)){
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

profileLogCheckpoint($profileData,'PARAMETERS_VALID');

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
        $successes = 0;
        $attempts = 0;
        $errors = '';
        foreach ($updateQueries as $queryString) {
            $attempts += 1;
            $qResult = @mysqli_query($dbLink, $queryString);
            if ($qResult) {
                $successes += 1;
            } else {
                // append error message
                $errors .= @mysqli_error($dbLink).'/';
            }
        }
        if (empty($errors)) {
            $retVal['httpResponse'] = 200;
            $retVal['httpReason']	= "Help content updated.";
        } else {
            $retVal['httpResponse'] = 500;
            $retVal['httpReason']	= $errors;
        }
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
if ($retVal['httpResponse'] == 200) {
	$redirectUrl = $successUrl;
	header("httpReason: Successful update");
	header("Location: ".$redirectUrl);
} else {
    $redirectUrl = makeUrlWithQueryParams($errorUrl, $requestData);
	logApiError($formData, $logError, __FILE__ );
	header("Location: ".$redirectUrl);
}
profileLogClose($profileData, __FILE__, $formData);
return;
// EOF
