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
 *	Deletes patient resources from the database
 * 		or an HTML error message
 *
 * DELETE: marks a patient record as inactive, the record is not removed from the database
 * 		input data:
 *			`patientClinicID` - (Required) Patient ID issued by clinic.
 *
 *		Returns:
 *			200: the updated patient record
 *			400: required field is missing
 *			404: no record found that matches the specified patient clinic ID
 *			500: server error information
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function _patient_delete ($dbLink, $apiUserToken, $requestArgs) {
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
    $profileData = array();
    profileLogStart ($profileData);
    // format db table fields as dbInfo array
    $returnValue = array();

    $dbInfo = array();
    $dbInfo ['requestArgs'] = $requestArgs;

    // token parameter was verified before this function was called.
    $logData = createLogEntry (
        'API',
        __FILE__,
        'patient',
         $_SERVER['REQUEST_METHOD'],
         $apiUserToken,
         $requestArgs,
        null,
        null,
        null,
        null);

	// confirm that we have the record ID
	if (empty( $requestArgs["clinicPatientID"])) {
		// missing primary key field
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= "Unable to delete patient record. The clinicPatientID is missing.";
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_PARAMS);
		return $returnValue;
	}
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// make sure the record is currently active
	// create query string for get operation
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_PATIENT_GET. "` WHERE `clinicPatientID` = '".
		$requestArgs['clinicPatientID']."';";
	$testReturnValue = getDbRecords($dbLink, $getQueryString);
	if ($testReturnValue['httpResponse'] !=  200) {
		// can't find the record to delete. It could already be deleted or it could not exist.
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 404;
		$returnValue['httpReason']	= "Patient record to delete not found.";
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_NOTFOUND);
		return $returnValue;
	} else {
        $logData['logBeforeData'] = json_encode($testReturnValue['data']);
	}

	// delete is really deactivate
	// so the only field necessary from the caller is the primary key
	$deleteArgs = array();
	$deleteArgs["clinicPatientID"] = $requestArgs["clinicPatientID"];
	$deleteArgs["active"] = 0;
	// and now it's ready to update
	$columnsToUpdate = 0;
	$deleteQueryString = format_object_for_SQL_update (DB_TABLE_PATIENT, $deleteArgs, "clinicPatientID", $columnsToUpdate);

	// check query string construction
	if ($columnsToUpdate < 1) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$dbInfo['deleteQueryString'] = $deleteQueryString;
			$dbInfo['columnsToUpdate'] = $columnsToUpdate;
			$dbInfo['deleteArgs'] = $deleteArgs;
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 500;
		$returnValue['httpReason']	= "Unable to delete the patient record. There was a problem with the request.";
        profileLogClose($profileData, __FILE__, $requestArgs,PROFILE_ERROR_UPDATE);
		return $returnValue;
	}

	// try to delete the record in the database by marking it as inactive
	$qResult = @mysqli_query($dbLink, $deleteQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['deleteQueryString'] = $deleteQueryString;
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['debug'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 500;
		$returnValue['httpReason']	= "Unable to update patient.";
	} else {
		profileLogCheckpoint($profileData,'UPDATE_COMPLETE');
		// successfully deleted (deactivated)
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$dbInfo['deleteQueryString'] = $deleteQueryString;
			$dbInfo['columnsToUpdate'] = $columnsToUpdate;
			$dbInfo['deleteArgs'] = $deleteArgs;
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			$returnValue['debug'] = $dbInfo;
		}
        $logData['logAfterData'] = '';
		$returnValue['httpResponse'] = 200;
		$returnValue['httpReason']	= "Patient record deactivated.";
        $logData['logStatusCode'] = $returnValue['httpResponse'];
        $logData['logsStatusMessage'] = $returnValue['httpReason'];
        writeEntryToLog ($dbLink, $logData);
		@mysqli_free_result($qResult);
	}
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
?>
