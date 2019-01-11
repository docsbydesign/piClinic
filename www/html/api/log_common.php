<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 12/21/2018
 * Time: 11:01 AM
 */
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
    $queryString .= "TRUE ORDER BY `createdDate` DESC, `loggerID` DESC";
    $queryString .= ' '.DB_QUERY_LIMIT.';';

    return $queryString;
}
//EOF