<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 12/21/2018
 * Time: 11:01 AM
 */
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
 *	Utility functions used by log resource
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Deifine the elements in the LogFieldInfo
define ("LOG_REQ_ARG", 0, false);        // request param name is index 0
define ("LOG_DB_ARG", 1, false);         // db field name is index 1
define ("LOG_DB_REQ_GET", 2, false);     // whether the field must appear in a GET request
define ("LOG_DB_QS_GET",3, false);       // variable can be used to filter GFET query
define ("LOG_DB_REQ_POST", 4, false);    // whether the field must appear in a POST request

/*
 * Returns an array that defines the query paramters and DB field names used by the log
 */
function getLogFieldInfo() {
    $returnValue = [
        ["userToken",           "userToken",        false,  true,   true],
        ["sourceModule",        "sourceModule",     false,  true,   true],
        ["logClass",            "logClass",         false,  true,   true],
        ["logTable",            "logTable",         false,  true,   true],
        ["logAction",           "logAction",        false,  true,   true],
        ["logQueryString",      "logQueryString",   false,  false,  false],
        ["logBeforeData",       "logBeforeData",    false,  false,  false],
        ["logAfterData",        "logAfterData",     false,  false,  false],
        ["logStatusCode",       "logStatusCode",    false,  true,   false],
        ["logStatusMessage",    "logStatusMessage", false,  false,  false]
    ];
    return $returnValue;
}

function filterRequestToValidParameters ($queryParamArrayIn) {
    $fieldInfoArray = getLogFieldInfo();
    $queryParamArrayOut = array();
    if (!empty($queryParamArrayIn)){
        foreach ($queryParamArrayIn as $p_key => $p_value) {
            // find key (field name) in list of valid query params
            foreach ($fieldInfoArray as $fieldInfo) {
                if ($p_key == $fieldInfo[LOG_REQ_ARG]) {
                    // found it
                    $queryParamArrayOut[$p_key] = $p_value;
                    break;
                }
            }
        }
    }
    return $queryParamArrayOut;
}

/*
 *  Creates a MySQL query string to retrieve log reoords as filtered by
 *      the fields passed in the $requestParamters argument.
 *
 *      $requestParameters: the query string of an API call interpreted into an associative array
 *
 *      Returns a MySQL query string.
 */
function makeLogQueryStringFromRequestParameters ($requestParameters) {
    // create query string for get operation
    $queryString = "SELECT * FROM `". DB_TABLE_LOG . "` WHERE ";
    $paramCount = 0;

    $logDbFields = getLogFieldInfo();

    foreach ($logDbFields as $reqField) {
        if ($reqField[LOG_DB_QS_GET]) {
            if (!empty($requestParameters[$reqField[LOG_REQ_ARG]])) {
                $queryString .= "`". $reqField[LOG_DB_ARG] ."` LIKE '".$requestParameters[$reqField[LOG_REQ_ARG]]."' AND ";
                $paramCount += 1;
            }
        }
    }

    // if no paremeters, then select all from the most recent 100 log entries
    $queryString .= "TRUE ORDER BY `createdDate` DESC, `logID` DESC";
    $queryString .= ' '.DB_QUERY_LIMIT.';';

    return $queryString;
}
//EOF
