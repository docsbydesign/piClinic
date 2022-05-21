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
 *	Formats the data from the staffAddEdit form and adds or updates the staff record
 *
 *	POST: Adds or updates a new staff record to the database
 * 		input data:
 *			`memberID` - (Optional) Staff ID issued by clinic.
 *			`username` - (Required) Staff's username (unique)
 *   		`lastName` - (Required) Staff's last name(s)
 *   		`firstName` - (Required) Staff's first name
 *   		`position` - (Required) Clinic role
 *   		`password` - (Required) Stored as hash of user's password
 *   		`contactInfo` - (Optional) Staff's email or phone number
 *   		`altContactInfo` - (Optional) Staff's email or phone number
 *   		`accessGranted` - (Required) Level of access to clinic DB info
 *			`mode` - add/edit depending on the action requested
 *			`useredit` - present when called by user changing their own account info
 *
 *		Returns:
 *			200: updated staff record
 *			201: the new staff record created
 *			400: required field is missing
 *			409: record already exists error
 *			500: server error information
 *
 *
 *
 *********************/

require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';
require_once dirname(__FILE__).'/../shared/profile.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../api/staff_common.php';
require_once dirname(__FILE__).'/../api/staff_get.php';
require_once dirname(__FILE__).'/../api/staff_patch.php';
require_once dirname(__FILE__).'/../api/staff_post.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

$formData = $sessionInfo['parameters'];

// check for authorization to access this page
$pageAccessRequired = (!empty($sessionInfo['parameters']['useredit']) ? PAGE_ACCESS_READONLY : PAGE_ACCESS_CLINIC);
if (!checkUiSessionAccess($dbLink, $sessionInfo['token'], $pageAccessRequired, $sessionInfo)){
    // show this in the error div
    $requestData['msg'] = MSG_NO_ACCESS;
    $redirectUrl = makeUrlWithQueryParams('/clinicDash.php', $requestData);
    $logError = [];
    $logError['httpResponse'] =  403;
    $logError['httpReason'] = 'User account is not authorized to access this resource.';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($sessionInfo['parameters'], $logError, __FILE__ , $sessionInfo['username'], 'staff', $logError['httpReason']);
    if (API_DEBUG_MODE) {
        header("DEBUG: ".json_encode($logError));
    }
    header("Location: ". $redirectUrl);
    return;
}

// clear the query parameters that shouldn't be repeated
unset ($formData['msg']);

// copy and interpret form fields
$stringFields = array(
 'memberID'
,'username'
,'lastName'
,'firstName'
,'position'
,'password'
,'contactInfo'
,'preferredLanguage'
,'preferredClinicPublicID'
,'altContactInfo'
,'active'
,'accessGranted'
);

$requestData = [];
foreach ($stringFields as $fieldName) {
    // copy only the fields with a value
    // did not use empty because it returns false for 0, but 0 is valid for active
    // isset returns true for empty strings which are stored as NULL in the DB
    if (isset($formData[$fieldName]) && strlen($formData[$fieldName]) > 0) {
        $requestData[$fieldName] = $formData[$fieldName];
    }
}
profileLogCheckpoint($profileData,'PARAMETERS_VALID');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (!empty($formData['_method']) &&  ($formData['_method'] == 'PATCH')) {
            $retVal = _staff_patch($dbLink, $sessionInfo['token'], $requestData);
        } else {
            $retVal = _staff_post($dbLink, $sessionInfo['token'], $requestData);
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

// return to the staff username list after successful operation
//  or the edit page if not
$successfulCall = false;
// if PATCH check for 200 error
if (!empty($formData['_method']) && ($formData['_method'] == 'PATCH')) {
    // successful update returns 200
    if ($retVal['httpResponse'] == 200) {
        $successfulCall = true;
    }
} else {
    // successful POST returns 201
    if ($retVal['httpResponse'] == 201) {
        $successfulCall = true;
    }
}
if ($successfulCall) {
    $redirectUrl = '';
    if (!empty($formData['useredit'])) {
        // the user is editing their own data so, update their session and go to home
        if ((!empty($sessionInfo['token'])) && (!empty($requestData['preferredLanguage']))) {
            $_SESSION['sessionLanguage'] = $requestData['preferredLanguage'];
        }

        $redirectUrl = '/clinicDash.php';
    } else {
        // the admin is editing a user account, go to admin show user page
        $redirectUrl = '/adminShowUsers.php';
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
    $msgText = ( $retVal['httpResponse'] == 409 ? "USERNAME_ID_IN_USE" : "NOT_UPDATED");
    $returnQP = 'msg='.
        ($retVal['httpResponse'] == 409 ? "USERNAME_ID_IN_USE" : "NOT_UPDATED").
        '&'.$returnQP.'&updateErr=';
    $redirectUrl = '/staffAddEdit.php?'.$returnQP;
    $logError = [];
    $logError['httpResponse'] =  $retVal['httpResponse'];
    $logError['httpReason'] =  'Unsuccessful add or update';
    $logError['error']['redirectUrl'] = $redirectUrl;
    $logError['error']['requestData'] = $requestData;
    logApiError($formData, $logError, __FILE__ );
    header("Location: ".$redirectUrl);
}
profileLogClose($profileData, __FILE__, $formData);
return;
// EOF
