<?php
/*
 *	Copyright (c) 2019, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    // the file was not included so return an error
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8;');
    header("HTTP/1.1 404 Not Found");
    echo <<<MESSAGE
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
MESSAGE;
    echo "\n<p>The requested URL " . $_SERVER['PHP_SELF'] ." was not found on this server.</p>\n";
    echo "<hr>\n";
    echo '<address>'. apache_get_version() . ' Server at ' . $_SERVER['SERVER_ADDR'] . ' Port '. $_SERVER['SERVER_PORT'] . "</address>\n";
    echo "</body></html>\n";
    exit(0);

}
/*******************
 *
 *	Functions used by all API scripts
 *
 *********************/
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
/*
* 	Profile log
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
function logUiError($inputParamList,
                     $error='NotSpecified',
                     $scriptFile = 'NotSpecified',
                     $username = 'NotSpecified',
                     $action = 'NotSpecified',
                     $message = null) {
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
		$logRecord['type'] = 'uierror';
		$logRecord['time'] = date ( 'c' ); //  ISO 8601 date format
		$logRecord['file'] = $scriptFile;
		if (empty($inputParamList)) {
			$inputParamList = (empty($_SERVER['QUERY_STRING']) ? 'NotSpecified' : $_SERVER['QUERY_STRING']);
		}
		$logRecord['params'] = $inputParamList;
		$logRecord['user'] = $username;
		$logRecord['method'] = (empty($_SERVER['REQUEST_METHOD']) ? 'NotSpecified' : $_SERVER['REQUEST_METHOD']);
		$logRecord['addr'] = (empty($_SERVER['REMOTE_ADDR']) ? 'NotSpecified' : $_SERVER['REMOTE_ADDR']);
		$logRecord['referrer'] = (empty($_SERVER['HTTP_REFERER']) ? 'NotSpecified' : $_SERVER['HTTP_REFERER']);
		$logRecord['error'] = $error;
        $logRecord['action'] = $action;
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
*	Log API error
*/
function logApiError($inputParamList,
                     $error='NotSpecified',
                     $scriptFile = 'NotSpecified',
                     $username = 'NotSpecified',
                     $table = 'NotSpecified',
                     $message = null) {
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
            $inputParamList = (empty($_SERVER['QUERY_STRING']) ? 'NotSpecified' : $_SERVER['QUERY_STRING']);
        }
        $logRecord['params'] = $inputParamList;
        $logRecord['user'] = $username;
        $logRecord['method'] = (empty($_SERVER['REQUEST_METHOD']) ? 'NotSpecified' : $_SERVER['REQUEST_METHOD']);
        $logRecord['addr'] = (empty($_SERVER['REMOTE_ADDR']) ? 'NotSpecified' : $_SERVER['REMOTE_ADDR']);
        $logRecord['referrer'] = (empty($_SERVER['HTTP_REFERER']) ? 'NotSpecified' : $_SERVER['HTTP_REFERER']);
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
                         $logTable,
                         $logAction,
                         $userToken = null,
                         $logQueryString = null,
                         $logBeforeData = null,
                         $logAfterData = null,
                         $logStatusCode = null,
                         $logStatusMessage = null)
{
    $logEntryObject = [];
    if (empty($logClass)) return false; //required field
    $logEntryObject['logClass'] = $logClass;

    if (empty($sourceModule)) return false; //required field
    $logEntryObject['sourceModule'] = $sourceModule;

    if (empty($logTable)) return false; //required field
    $logEntryObject['logTable'] = $logTable;

    if (empty($logAction)) return false; //required field
    $logEntryObject['logAction'] = $logAction;

    if (!empty($logData['logQueryString']) && is_array($logData['logQueryString'])) {
        $logData['logQueryString'] = "*" . json_encode($logData['logQueryString']);
    } else {
        $logEntryObject['logQueryString'] = $logQueryString;
    }
    $logEntryObject['userToken'] = $userToken;
    if (!empty($logBeforeData)) {
        if (is_array($logBeforeData)) {
            // only encode array object
            $logEntryObject['logBeforeData'] = json_encode($logBeforeData);
        } else {
            $logEntryObject['logBeforeData'] = $logBeforeData;
        }
    } else {
        $logEntryObject['logBeforeData'] = null;
    }
    if (!empty($logAfterData)) {
        // only encode array object
        if (is_array($logAfterData)) {
            $logEntryObject['logAfterData'] = json_encode($logAfterData);
        } else {
            $logEntryObject['logAfterData'] = $logAfterData;
        }
    } else {
        $logEntryObject['logAfterData'] = null;
    }
    $logEntryObject['logStatusCode'] = $logStatusCode;
    $logEntryObject['logStatusMessage'] = $logStatusMessage;
    $now = new DateTime();
    $logEntryObject['createdDate'] = $now->format('Y-m-d H:i:s');
    return $logEntryObject;
}

// expects a logEntryObject created by createLogEntry
function writeEntryToLog ($dbLink, $logData) {
    // test for invalid parameter.
    if (empty($logData)) return false;
    if (empty($dbLink)) return false;
    //---------------
    // clean fields for writing to the DB
    if (!empty($logData['logQueryString']) && is_array($logData['logQueryString'])) {
        $logData['logQueryString'] = "*" . json_encode($logData['logQueryString']);
    }
    if (!empty($logData['logBeforeData']) && is_array($logData['logBeforeData'])) {
        $logData['logBeforeData'] = "*" . json_encode($logData['logBeforeData']);
    }
    if (!empty($logData['logAfterData']) && is_array($logData['logAfterData'])) {
        $logData['logAfterData'] = "*" . json_encode($logData['logAfterData']);
    }
    if (!empty($logData['logStatusCode']) && is_array($logData['logStatusCode'])) {
        $logData['logStatusCode'] = "*" . json_encode($logData['logStatusCode']);
    }
    if (!empty($logData['logStatusMessage']) && is_array($logData['logStatusMessage'])) {
        $logData['logStatusMessage'] = "*" . json_encode($logData['logStatusMessage']);
    }
    $dbInfo = [];
    $returnValue = [];
    $insertQueryString = format_object_for_SQL_insert (DB_TABLE_LOG, $logData);
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

/*
 * Workflow tracking functions
 */
define('WORKFLOW_TYPE_HOME','HF',false);
define('WORKFLOW_TYPE_SUB','SF',false);
define('WORKFLOW_TYPE_REPORT','RP',false);
define('WORKFLOW_TYPE_LEN', 2, false); // the size of the type prefix
define('WORKFLOW_QUERY_PARAM','workflow',false);
define('WORKFLOW_SESSION_ARRAY','workflow',false);
define('WORKFLOW_STEP_STARTED','Start', false);
define('WORKFLOW_STEP_STEP','Step', false);
define('WORKFLOW_STEP_COMPLETE','Complete', false);
define('WORKFLOW_STEP_ABANDONED','Abandoned', false);
define('WORKFLOW_NOT_ACTIVE','Inactive',false);

function logGetRootWorkflowID() {
    // the current one is the last one in the array
    if (empty(session_id())){
        session_start();
    }
    assert (!empty($_SESSION), "ERROR: Session array is not initialized.");
    // the current one is the last one in the array
    if (!empty($_SESSION[WORKFLOW_SESSION_ARRAY])) {
        for ($wfItem = 0; $wfItem < count($_SESSION[WORKFLOW_SESSION_ARRAY]); $wfItem += 1) {
            if (getWorkflowIdComponent($_SESSION[WORKFLOW_SESSION_ARRAY][$wfItem], WF_TYPE) == WORKFLOW_TYPE_HOME) {
                return ($_SESSION[WORKFLOW_SESSION_ARRAY][$wfItem]);
            }
        }
    }
    // no workflow is active
    return null;
}

function logGetCurrentWorkflowID() {
    // the current one is the last one in the array
    if (empty(session_id())){
        session_start();
    }
    assert (!empty($_SESSION), "ERROR: Session array is not initialized.");
    // the current one is the last one in the array
    if (!empty($_SESSION[WORKFLOW_SESSION_ARRAY])) {
        return ($_SESSION[WORKFLOW_SESSION_ARRAY][count($_SESSION[WORKFLOW_SESSION_ARRAY])-1]);
    }
    // no workflow is active
    return null;
}

define("WF_TYPE",1, false);
define("WF_NAME",4, false);
define("WF_GUID",5, false);

function getWorkflowIdComponent ($workflowID, $component) {
    // parse the ID to see if it has a name. If it does, pull it out.
    if (($component == WF_TYPE) || ($component == WF_NAME) || ($component == WF_GUID)) {
        $nameElements = array();
        $workflowFormat = '/^(HF|SF)_((([A-Z_]*[A-Z]{1})_))?([a-z0-9_]{8}_[a-z0-9_]{4}_[a-z0-9_]{4}_[a-z0-9_]{4}_[a-z0-9_]{12})$/';
        if (preg_match ($workflowFormat, $workflowID, $nameElements) == 1) {
            if (!empty($nameElements[$component])) {
                return $nameElements[$component];
            } else {
                // component not present
                return 'NOT_FOUND';
            }
        } else {
            // ID didn't parse
            return 'BAD_ID';
        }
    } else {
        // unrecognized component
        return 'BAD_ID_COMPONENT';
    }
}

function logSessionWorkflow ($sessionInfo, $filename, $step, $workflowID, $dblink, $activeWorkflows = null, $logData = null) {
    $wfSuccess = true;
    $wfLogEntry = array();
    $wfLogEntry['sourceModule'] = $filename;
    $wfLogEntry['logQueryString'] = $sessionInfo['parameters'];

    // see if there is some path data to record
    $wfLogEntry['prevPage'] = null;
    if (!empty($sessionInfo['parameters']['fromLink'])) {
        $fromLink = explode(FROM_LINK_SEP, $sessionInfo['parameters']['fromLink']);
        if (count($fromLink) == 2) {
            // then this is what we expect
            $wfLogEntry['prevPage'] = $fromLink[0];
            $wfLogEntry['prevLink'] = $fromLink[1];
        }
    } // else leave it as null
    $wfLogEntry['requestId'] = null;
    if (!empty(getenv('UNIQUE_ID'))) {
        $wfLogEntry['requestId'] = getenv('UNIQUE_ID');
    }
    $wfLogEntry['userToken'] = $sessionInfo['token'];
    $wfLogEntry['wfHomeGuid'] = logGetRootWorkflowID();

    $currentTime = microtime(true);
    $currentTimeString = sprintf("%06d",($currentTime - floor($currentTime)) * 1000000);
    $timestamp= new DateTime( date('Y-m-d H:i:s.'.$currentTimeString, $currentTime) );

    $wfLogEntry['wfMicrotime'] = $currentTime;
    $wfLogEntry['wfMicrotimeString'] = $timestamp->format("Y-m-d H:i:s.u");
    if (!empty($activeWorkflows)) {
        $wfLogEntry['activeWorkflows'] = json_encode($activeWorkflows);

        for ($wfItem = count($activeWorkflows)-1; $wfItem >= 0; $wfItem -= 1) {
            if (isset($activeWorkflows[$wfItem])) {
                // for each entry in the session workflow list
                $wfLogEntry['logClass'] = getWorkflowIdComponent($activeWorkflows[$wfItem], WF_TYPE);
                $wfLogEntry['wfName'] = getWorkflowIdComponent($activeWorkflows[$wfItem], WF_NAME);
                $wfLogEntry['wfGuid'] = getWorkflowIdComponent($activeWorkflows[$wfItem], WF_GUID);
                if ($workflowID == $activeWorkflows[$wfItem]) {
                    $wfLogEntry['wfStep'] = $step;
                    $wfLogEntry['logAfterData'] = (is_array($logData) ? json_encode($logData) : $logData);
                } else {
                    $wfLogEntry['wfStep'] = WORKFLOW_STEP_STEP;
                    $wfLogEntry['logAfterData'] = null;
                }
                $wfLogEntry['logBeforeData'] = null;
                $wfLogEntry['logStatusCode'] = null;
                $wfLogEntry['logStatusMessage'] = null;

                $logQueryString = format_object_for_SQL_insert(DB_TABLE_WFLOG, $wfLogEntry);
                $dbResult = @mysqli_query($dblink, $logQueryString);
                assert($dbResult, "Unable to write to workflow log with query: ".$logQueryString);
                if (!$dbResult) {
                    // SQL ERROR
                    if (API_DEBUG_MODE) {
                        $dbInfo['logQueryString'] = $logQueryString;
                        $dbInfo['sqlError'] = @mysqli_error($dblink);
                        // format response
                        echo ($dbInfo['sqlError']. "\n");
                        echo ($logQueryString. "\n");
                    }
                    $wfSuccess = false;
                }
            }
        }
    } else {
        // for each entry in the session workflow list
        $wfLogEntry['logClass'] = null;
        $wfLogEntry['wfName'] = null;
        $wfLogEntry['wfGuid'] = null;
        $wfLogEntry['wfStep'] = $step;
        $wfLogEntry['logBeforeData'] = null;
        $wfLogEntry['logAfterData'] = null;
        $wfLogEntry['logStatusCode'] = null;
        $wfLogEntry['logStatusMessage'] = null;

        $logQueryString = format_object_for_SQL_insert(DB_TABLE_WFLOG, $wfLogEntry);
        $dbResult = @mysqli_query($dblink, $logQueryString);
        assert($dbResult, "Unable to write to workflow log with query: ".$logQueryString);
        if (!$dbResult) {
            // log an error?
            $wfSuccess = false;
        }
    }
    return $wfSuccess;
}

function logReportWorkflow ($sessionInfo, $reportInfo, $dblink) {
    $wfSuccess = true;
    $wfLogEntry = [];
    $wfLogEntry[0] = [];
    $wfLogEntry[1] = [];
    // values shared by start and end records
    $wfLogEntry[0]['sourceModule'] = $wfLogEntry[1]['sourceModule'] = $reportInfo['report'];
    $wfLogEntry[0]['logQueryString'] = $wfLogEntry[1]['logQueryString'] = $sessionInfo['parameters'];
    $wfLogEntry[0]['prevPage'] = $wfLogEntry[1]['prevPage'] = '';
    $wfLogEntry[0]['prevLink'] = $wfLogEntry[1]['prevLink'] = '';
    $wfLogEntry[0]['requestId'] = $wfLogEntry[1]['requestId'] = '';
    $wfLogEntry[0]['userToken'] = $wfLogEntry[1]['userToken'] = $sessionInfo['token'];
    $wfLogEntry[0]['logClass'] = $wfLogEntry[1]['logClass'] = WORKFLOW_TYPE_REPORT;
    $wfLogEntry[0]['wfName'] = $wfLogEntry[1]['wfName'] = basename($reportInfo['report'], '.php');
    $wfLogEntry[0]['wfGuid'] = $wfLogEntry[1]['wfGuid'] =  getWorkflowID(WORKFLOW_TYPE_REPORT, strtoupper(basename($reportInfo['report'],'.php')));
    $wfLogEntry[0]['activeWorkflows'] = $wfLogEntry[1]['activeWorkflows'] = '[]';
    $wfLogEntry[0]['logBeforeData'] = $wfLogEntry[1]['logBeforeData'] = json_encode($reportInfo);
    $wfLogEntry[0]['logAfterData'] = $wfLogEntry[1]['logAfterData'] = json_encode(array("count"=>$reportInfo['count']));
        // values that differ between start and end
    // start values
    $wfLogEntry[0]['wfStep'] = 'Start';
    $wfLogEntry[0]['wfMicrotime'] = $reportInfo['start'];
    $wfLogEntry[0]['wfMicrotimeString'] = date('Y-m-d H:i:s', floor($reportInfo['start']));
    $wfLogEntry[0]['logStatusCode'] = '';
    $wfLogEntry[0]['logStatusMessage'] = '';
    // end values
    $wfLogEntry[1]['wfStep'] = 'Complete';
    $wfLogEntry[1]['wfMicrotime'] = $reportInfo['end'];
    $wfLogEntry[1]['wfMicrotimeString'] = date('Y-m-d H:i:s', floor($reportInfo['end']));
    $wfLogEntry[1]['logStatusCode'] = '';
    $wfLogEntry[1]['logStatusMessage'] = '';

        // get root workflow ID
    $rootWorkflowID = '';
    $rootWorkflowName = '';
    if (!empty($activeWorkflowArray)) {
        foreach ($activeWorkflowArray as $wfID) {
            if (getWorkflowIdComponent ($wfID, WF_TYPE) == WORKFLOW_TYPE_HOME) {
                $rootWorkflowID = $wfID;
                break;
            }
        }
        if (!empty($rootWorkflowID)) {
            // parse the ID to see if it has a name. If it does, pull it out.
            $rootWorkflowName = getWorkflowIdComponent ($rootWorkflowID, WF_NAME);
        } else {
            $rootWorkflowID = 'NO_ID';
        }
    }
    $wfLogEntry[0]['wfHomeGuid'] = $wfLogEntry[1]['wfHomeGuid'] = $rootWorkflowID;

    for ($wfIdx = 0; $wfIdx < 2; $wfIdx++) {
        $logQueryString = format_object_for_SQL_insert(DB_TABLE_WFLOG, $wfLogEntry[$wfIdx]);
        // $dbResult = @mysqli_query($dblink, $logQueryString);
        // assert($dbResult, "Unable to write to workflow log with query: ".$logQueryString);
        // if (!$dbResult) {
        if (!@mysqli_query($dblink, $logQueryString)) {
            // log an error?
            $wfLogEntry[$wfIdx]['dbSuccess'] = $wfSuccess = false;
            // $wfLogEntry[$wfIdx]['dbStatus'] = $dbResult;
            $wfLogEntry[$wfIdx]['dbQuery'] = $logQueryString;
            $wfLogEntry[$wfIdx]['dbMessage'] = @mysqli_error($dblink);
        }
    }
    return $wfLogEntry;
}

function closeMatchingWorkflow($sessionInfo, $filename, $dbLink, $workflowsToMatch, $workflowStep = WORKFLOW_STEP_COMPLETE, $logData = null) {
    // closes the last (most recent) workflow in the session workflow list
    if (empty(session_id())){
        session_start();
    }
    assert (!empty($_SESSION), "ERROR: Session array is not initialized.");
    $returnValue = null;
    if (!empty($_SESSION[WORKFLOW_SESSION_ARRAY])) {
        // close the last (most recent) workflow from last to first
        // the one to close should be the last one
        $itemID = count($_SESSION[WORKFLOW_SESSION_ARRAY])-1;
        $workflowMatchList = array();
        if (is_array($workflowsToMatch)) {
            $workflowMatchList = $workflowsToMatch;
        } else {
            $workflowMatchList[0] = $workflowsToMatch;
        }
        $wfClosed = false;
        foreach ($workflowMatchList as $workflowName) {
            if (getWorkflowIdComponent($_SESSION[WORKFLOW_SESSION_ARRAY][$itemID],WF_NAME) == $workflowName ) {
                $returnValue = logSessionWorkflow($sessionInfo, $filename, $workflowStep, $_SESSION[WORKFLOW_SESSION_ARRAY][$itemID], $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY], $logData);
                // and then remove it
                unset($_SESSION[WORKFLOW_SESSION_ARRAY][$itemID]);
                $wfClosed = true;
                break;
            }
        }
        if (!$wfClosed) {
            // if it's not the last one, find it and close it
            for ($itemID = count($_SESSION[WORKFLOW_SESSION_ARRAY])-1; $itemID >= 0; $itemID -= 1) {
                foreach ($workflowMatchList as $workflowName) {
                    if (getWorkflowIdComponent($_SESSION
                        [WORKFLOW_SESSION_ARRAY][$itemID], WF_NAME) == $workflowName) {
                        $returnValue = logSessionWorkflow($sessionInfo, $filename, $workflowStep, $_SESSION[WORKFLOW_SESSION_ARRAY][$itemID], $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
                        // and then remove it
                        unset($_SESSION[WORKFLOW_SESSION_ARRAY][$itemID]);
                        $wfClosed = true;
                        break;
                    }
                }
                if ($wfClosed) { break; }
            }
        }
    } // else, no workflow is active so nothing to do
    return $returnValue;
}


function  closeSessionWorkflow($sessionInfo, $filename, $dbLink, $workflowStep = WORKFLOW_STEP_COMPLETE) {
    if (empty(session_id())){
        session_start();
    }
    assert (!empty($_SESSION), "ERROR: Session array is not initialized.");
    $returnValue = null;
    if (!empty($_SESSION[WORKFLOW_SESSION_ARRAY])) {
        // close each open workflow from last to first
        for ($itemID = count($_SESSION[WORKFLOW_SESSION_ARRAY])-1; $itemID >= 0; $itemID -= 1) {
            $returnValue = logSessionWorkflow ($sessionInfo, $filename, $workflowStep, $_SESSION[WORKFLOW_SESSION_ARRAY][$itemID], $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
        }
        // then clear the workflow array
        unset($_SESSION[WORKFLOW_SESSION_ARRAY]);
    } // else, no workflow is active so nothing to do
    return $returnValue;
}

function createNewSessionWorkflow ($sessionInfo, $filename, $workflowID, $dbLink) {
    if (empty(session_id())){
        session_start();
    }
    assert (!empty($_SESSION), "ERROR: Session array is not initialized.");
    if (empty($_SESSION[WORKFLOW_SESSION_ARRAY])) {
        $_SESSION[WORKFLOW_SESSION_ARRAY] = array();
    }
    $returnValue = null;
    // add a new workflow to the existing array
    foreach ($_SESSION[WORKFLOW_SESSION_ARRAY] as $activeWorkflow) {
        if ($activeWorkflow == $workflowID ) {
            // log as a step and exit
            $returnValue = logSessionWorkflow ($sessionInfo, $filename,WORKFLOW_STEP_STEP, $workflowID, $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
            return $returnValue; // this workflow has already been opened.
        }
    }
    // if here, this is a new workflow so log the start and add it to the end
    array_push($_SESSION[WORKFLOW_SESSION_ARRAY], $workflowID);
    $returnValue = logSessionWorkflow ($sessionInfo, $filename,WORKFLOW_STEP_STARTED, $workflowID, $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
    return $returnValue;
}

function getWorkflowID($type, $name = null) {
    // only return a Workflow ID if a valid type was passed in.
    if (($type == WORKFLOW_TYPE_HOME) || ($type == WORKFLOW_TYPE_SUB)  || ($type == WORKFLOW_TYPE_REPORT)) {
        if (!empty($name)) {
            // limit name strings to 24 characters or less.
            return $type.'_'.substr($name,0,24).'_'.guidString('_');
        } else {
            return $type.'_'.guidString('_');
        }
    }
    return '';
}

function logWorkflow($sessionInfo, $filename, $dbLink, $workflowStep = null) {
        $logProcesssed = 'None';
    $logResult = null;
    assert (!empty($dbLink), "ERROR: workflow logging requires database access.");
    // if there's a workflow ID in the QP
    $queryParams = $sessionInfo['parameters'];
    // if this is a Home flow, close out any existing flows and start a new one; otherwise add it to the list of workflows
    if (!empty($queryParams[WORKFLOW_QUERY_PARAM])) {
        switch (getWorkflowIdComponent ($queryParams[WORKFLOW_QUERY_PARAM], WF_TYPE)) {
            case WORKFLOW_TYPE_HOME:
                $logResult = closeSessionWorkflow($sessionInfo, $filename, $dbLink, WORKFLOW_STEP_ABANDONED);
                $logResult = createNewSessionWorkflow($sessionInfo, $filename, $queryParams[WORKFLOW_QUERY_PARAM], $dbLink);
                $logProcesssed = 'NewHome';
                break;

            case WORKFLOW_TYPE_SUB:
            default:
                $logResult = createNewSessionWorkflow($sessionInfo, $filename, $queryParams[WORKFLOW_QUERY_PARAM], $dbLink);
                $logProcesssed = 'NewSub';
                break;
        }
    } else if (!empty($workflowStep)) {
        // this is just another step in the workflow so log it.
        if (($workflowStep == WORKFLOW_STEP_COMPLETE) || ($workflowStep == WORKFLOW_STEP_ABANDONED)) {
            $logResult = closeSessionWorkflow($sessionInfo, $filename, $dbLink, $workflowStep);
            $logProcesssed = 'Closed';
        } else {
            $logResult = logSessionWorkflow ($sessionInfo, $filename, $workflowStep, logGetCurrentWorkflowID(), $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
            $logProcesssed = 'Step';
        }
    } else if (!empty($sessionInfo['workflow'])) {
        // this is just another step in the workflow so log it.
        $logResult = logSessionWorkflow ($sessionInfo, $filename, WORKFLOW_STEP_STEP, logGetCurrentWorkflowID(), $dbLink, $_SESSION[WORKFLOW_SESSION_ARRAY]);
        $logProcesssed = 'Step';
    } else {
        // no workflow started so nothing to do
        $logResult = logSessionWorkflow ($sessionInfo, $filename, WORKFLOW_NOT_ACTIVE, null, $dbLink);
        $logProcesssed = 'PageView';
    }
    if (API_DEBUG_MODE) {
        header('X-piClinic-LogDebugResult: '. json_encode($logResult));
        header('X-piClinic-LogDebugStep: '. $logProcesssed);
    }
    return $logProcesssed . "\n". json_encode($logResult, JSON_PRETTY_PRINT);
}
//EOF