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
/*******************
 *
 *	POST: Adds a new comment record to the database
 * 		input data:
 *   		`CommentDate` - (Optional) date comment was started
 *  		`Username` - (Required) Username creating this session.
 *  		`ReferringUrl` - (Optional) Page from which comment page was called.
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
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);
/*
 *
 */
function _comment_post ($dbLink, $requestArgs) {
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
    $logData = createLogEntry ('API',
        __FILE__,
        $requestArgs['token'],
        'comment',
        $_SERVER['REQUEST_METHOD'],
        null,
        null,
        null,
        null,
        null);
	if (empty($requestArgs['Username'])) {
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		$returnValue['httpResponse'] = 400;
		$returnValue['httpReason']	= 'Unable to add comment record. The Username is missing.';
		return $returnValue;
	}
	
	// clean leading and trailing spaces from string fields
	$postArgs = cleanCommentStringFields ($requestArgs);
	// remove the token for the DB call
    if (!empty($postArgs['token'])){
        unset($postArgs['token']);
    }
    // the the comment data is empty, use the current time
    if (empty($postArgs['CommentDate'])){
        $now = new DateTime();
        $postArgs['CommentDate'] = $now->format('Y-m-d H:i:s');
    }
	
	profileLogCheckpoint($profileData,'PARAMETERS_VALID');

	// make insert query string to add new object
	$insertQueryString = format_object_for_SQL_insert (DB_TABLE_COMMENT, $postArgs);
	// try to add the record to the database
	$qResult = @mysqli_query($dbLink, $insertQueryString);
	if (!$qResult) {
		// SQL ERROR
		$dbInfo['insertQueryString'] = $insertQueryString;
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		// format response
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		if (API_DEBUG_MODE) {
			$returnValue['error'] = $dbInfo;
		}
		if (!empty($dbInfo['sqlError'])) {
			// some other error was returned, so update the responee
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to add the comment record. '.$dbInfo['sqlError'];
		} else {
			$returnValue['httpResponse'] = 500;
			$returnValue['httpReason']	= 'Unable to add comment record.';
		}
	} else {
		// create query string for get operation
		profileLogCheckpoint($profileData,'POST_RETURNED');
		// if successful, build the return object from the input parameters
		$newComment = [];
		$newComment['CommentDate'] = isset($postArgs['CommentDate']) ? $postArgs['CommentDate'] : '';
		$newComment['Username'] =  isset($postArgs['Username']) ? $postArgs['Username'] : '';
		$newComment['ReferringUrl'] =  isset($postArgs['ReferringUrl']) ? $postArgs['ReferringUrl'] : '';
		$newComment['ReferringPage'] =  isset($postArgs['ReferringPage']) ? $postArgs['ReferringPage'] : '';
		$newComment['ReturnUrl'] =  isset($postArgs['ReturnUrl']) ? $postArgs['ReturnUrl'] : '';
		$newComment['CommentText'] =  isset($postArgs['CommentText']) ? $postArgs['CommentText'] : '';
		$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
		$returnValue['httpResponse'] = 201;
		$returnValue['httpReason']	= 'Success';
		$returnValue['count'] = 1;
		$returnValue['data'] = $newComment;
		$logData['logAfterData'] = json_encode($returnValue['data']);
		writeEntryToLog ($logData);
		@mysqli_free_result($qResult);
	}
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF