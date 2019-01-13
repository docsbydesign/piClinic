<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 12/20/2018
 * Time: 6:53 PM
 */
/*
 * Request Args
 *      usertoken   [UserToken]     user session ID
 *      [sourceModule]      the file from which the log entry is made
 *      [logClass]          log type enum
 *      [logTable]          DB Table Name
 *      [logAction]         API method
 *      [logQueryString]    Query string (decoded)
 *      [logBeforeData]     DB record as json string
 *      [logAfterData]      DB record as json string
 *      [logStatusCode]     HTTP Error value
 *      [logStatusMessage]  string (decoded)
 *
 * Returns the data passed in to Post (A complete record is NOT returned)
 */
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _log_post($dbLink, $apiUserToken, $requestArgs) {
    /*
     * Initialize profile data if configured in piClinicConfig.php
     */
    $profileData = [];
    profileLogStart ($profileData);

    // Initialize return value ind error message arrays
    $returnValue = array();
    $dbInfo = array();
    $dbInfo ['requestArgs'] = $requestArgs;

    // Create a list of missing required fields
    $missingColumnList = "";
    $logDbFields = getLogFieldInfo();
    foreach ($logDbFields as $reqField) {
        if ($reqField[LOG_DB_REQ_POST]) {
            if (empty($requestArgs[$reqField[LOG_REQ_ARG]])) {
                if (!empty($missingColumnList)) {
                    $missingColumnList .= ", ";
                }
                $missingColumnList .= $reqField[LOG_REQ_ARG];
            }
        }
    }

    // If there are any missing columns, return an error here
    if (!empty($missingColumnList)) {
        // some required fields are missing so exit
        $returnValue['contentType'] = CONTENT_TYPE_JSON;
        if (API_DEBUG_MODE) {
            $returnValue['debug'] = $dbInfo;
        }
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= "Unable to create create a new log entry. Required field(s): ". $missingColumnList. " are missing.";
        profileLogClose($profileData, __FILE__, $requestArgs, PROFILE_ERROR_PARAMS);
        return $returnValue;
    }

    // Here we have a valid username and password so create a session
    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    //Initialize DB fields to use to create query string
    $dbArgs = array();

    foreach ($logDbFields as $dbField) {
        if (!empty($requestArgs[$dbField[LOG_REQ_ARG]])) {
            $dbArgs[$dbField[LOG_DB_ARG]] = $requestArgs[$dbField[LOG_REQ_ARG]];
        }
    }
    $now = new DateTime();
    $dbArgs['createdDate'] = $now->format('Y-m-d H:i:s');
    // create expiration date as tomorrow for now.
    // save a copy for the debugging output
    $dbInfo['dbArgs'] = $dbArgs;

    // make insert query string to add new object to DB table
    profileLogCheckpoint($profileData,'POST_READY');
    // the log utility module actually writes the record.

    $returnValue = writeEntryToLog ($dbLink, $dbArgs);
    $returnValue['contentType'] = CONTENT_TYPE_JSON;
    if (API_DEBUG_MODE) {
        $returnValue['debug'] = $dbInfo;
    }
    profileLogClose($profileData, __FILE__, $requestArgs);
    return $returnValue;
}
//EOF