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
include 'exitIfCalledFromBrowser.php';
/*******************
 *
 *	Functions used by all API scripts
 *
 *********************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
/*
* 	Profile logger
*	
*		Writes one timestamp log entry when called.
*
*/
function profileLog () {
	// not implemented yet
	return FALSE;
}
/*
*	Log UI error
*/
function logApiError($inputParamList,
                     $error='',
                     $scriptFile='NotSpecified',
                     $username='NotSpecified',
                     $table='NotSpecified',
                     $message=null) {
    /*
     *   This is for errors that cannot be logged to the DB (for example, because
     *      the DB could not be opened.
    */
	$logFileName =  API_LOG_FILEPATH . "cts-error-" . date ('Y-m') . ".jlog";
	$logFileTimeStamp = date ( 'c' ); //  ISO 8601 date format
	// open the file for append access and create a new one if this one doesn't exist

	$logFileHandle = fopen ($logFileName, "a+", false);
	if ($logFileHandle) {
		$logRecord = array();
		$logRecord['type'] = 'apierror';
		$logRecord['time'] = date ( 'c' ); //  ISO 8601 date format
		$logRecord['file'] = $scriptFile;
		if (empty($inputParamList)) {
			$inputParamList = $_SERVER['QUERY_STRING'];
		}
		$logRecord['params'] = $inputParamList;
		$logRecord['user'] = $username;
		$logRecord['method'] = $_SERVER['REQUEST_METHOD'];
		$logRecord['addr'] = $_SERVER['REMOTE_ADDR'];
		$logRecord['referrer'] = $_SERVER['HTTP_REFERER'];
		$logRecord['error'] = $error;
        $logRecord['table'] = $table;
        $logRecord['message'] = $message;
		// write result to file
		fwrite ($logFileHandle, json_encode($logRecord)."\n");
		fclose ($logFileHandle);
		return true;
	} else {
		// not sure what to do if the log file doesn't open
		return false;
	}
}
/*
*
*	Contents of $logData:
*		$logData['table'] = the database table being changed
*		$logData['action'] = type of change
*		$logData['user'] = user making the change
*		$logData['before'] = record before change
*		$logData['after'] = record after change
*
*/
function createLogEntry ($logClass,
                         $sourceModule,
                         $userToken,
                         $table,
                         $action,
                         $queryString=null,
                         $beforeObject=null,
                         $afterObject=null,
                         $status=null,
                         $message=null)
{
    $logEntryObject = [];
    if (empty($logClass)) return false; //required field
    $logEntryObject['LogClass'] = $logClass;
    if (empty($sourceModule)) return false; //required field
    $logEntryObject['SourceModule'] = $sourceModule;
    if (empty($sessionID)) return false; //required field
    $logEntryObject['UserToken'] = $userToken;
    if (empty($table)) return false; //required field
    $logEntryObject['LogTable'] = $table;
    if (empty($action)) return false; //required field
    $logEntryObject['LogAction'] = $action;
    if (!empty($logData['LogQueryString']) && is_array($logData['LogQueryString'])) {
        $logData['LogQueryString'] = "*" . json_encode($logData['LogQueryString']);
    } else {
        $logEntryObject['LogQueryString'] = $queryString;
    }
    $logEntryObject['LogBeforeData'] = json_encode($beforeObject);
    $logEntryObject['LogAfterData'] = json_encode($afterObject);
    $logEntryObject['LogStatusCode'] = $status;
    $logEntryObject['LogStatusMessage'] = $message;
    $now = new DateTime();
    $logEntryObject['createdDate'] = $now->format('Y-m-d H:i:s');
    return $logEntryObject;
}

// expects a logEntryObject created by createLogEntry
function writeEntryToLog ($dbLink, $logData) {
    // test for invalid parameter.
    if ($logData === false) return false;
    if ($dbLink === false) return false;
    //---------------
    // clean fields for writing to the DB
    if (!empty($logData['LogQueryString']) && is_array($logData['LogQueryString'])) {
        $logData['LogQueryString'] = "*" . json_encode($logData['LogQueryString']);
    }
    if (!empty($logData['LogBeforeData']) && is_array($logData['LogBeforeData'])) {
        $logData['LogBeforeData'] = "*" . json_encode($logData['LogBeforeData']);
    }
    if (!empty($logData['LogAfterData']) && is_array($logData['LogAfterData'])) {
        $logData['LogAfterData'] = "*" . json_encode($logData['LogAfterData']);
    }
    if (!empty($logData['LogStatusCode']) && is_array($logData['LogStatusCode'])) {
        $logData['LogStatusCode'] = "*" . json_encode($logData['LogStatusCode']);
    }
    if (!empty($logData['LogStatusMessage']) && is_array($logData['LogStatusMessage'])) {
        $logData['LogStatusMessage'] = "*" . json_encode($logData['LogStatusMessage']);
    }
    $dbInfo = [];
    $returnValue = [];
    $insertQueryString = format_object_for_SQL_insert (DB_TABLE_LOGGER, $logData);
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
        $returnValue['httpResponse'] = 500;
        if (!empty($dbInfo['sqlError'])) {
            // some other error was returned, so update the responee
            $returnValue['httpReason']	= "Unable to create a new log entry. ".$dbInfo['sqlError'];
        } else {
            $returnValue['httpReason']	= "Unable to create a new log entry. DB error.";
        }
    } else {
        $returnValue['data'] = $logData;
        $returnValue['count'] = 1;
        $returnValue['httpResponse'] = 201;
        $returnValue['httpReason']	= "Log entry created.";

        @mysqli_free_result($qResult);
    }
    return $returnValue;
}
//EOF