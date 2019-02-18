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
/*******************
 *
 *	utility functions used by comment resource
 *
 *********************/
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Deifine the elements in the CommentFieldInfo
define ("COMMENT_REQ_ARG", 0, false);        // request param name is index 0
define ("COMMENT_DB_ARG", 1, false);         // db field name is index 1
define ("COMMENT_DB_REQ_GET", 2, false);     // whether the field must appear in a GET request
define ("COMMENT_DB_QS_GET",3, false);       // variable can be used to filter GFET query
define ("COMMENT_DB_REQ_POST", 4, false);    // whether the field must appear in a POST request
define ("COMMENT_DB_CLEANFIRST", 5, false);  // whether to clean the string before using

/*
 * Returns an array that defines the query parameters and DB field names used by the comment table
 */
function getCommentFieldInfo() {
    $returnValue = [
        // COMMENT_REQ_ARG,  COMMENT_DB_ARG,COMMENT_DB_REQ_GET,
        //                                          COMMENT_DB_QS_GET,
        //                                                  COMMENT_DB_REQ_POST,
        //                                                           COMMENT_DB_CLEANFIRST
        ["commentID",       "commentID",    false,  true,   false,   false],
        ["commentDate",     "commentDate",  false,  false,  false,   false],
        ["username",        "username",     false,  true,   true,    true],
        ["referringUrl",    "referringUrl", false,  true,   false,   true],
        ["referringPage",   "referringPage",false,  true,   false,   true],
        ["returnUrl",       "returnUrl",    false,  false,  false,   true],
        ["commentText",     "commentText",  false,  false,  false,   true]
    ];
    return $returnValue;
}

/*
 *
 */
function makeCommentQueryStringFromRequestParameters ($requestParameters, $dbView = 'DB_TABLE_COMMENT') {
	 // create query string for get operation
	$queryString = "SELECT * FROM `".
		$dbView. "` WHERE ";

    $commentDbFields = getCommentFieldInfo();
    foreach ($commentDbFields as $reqField) {
        if ($reqField[COMMENT_DB_QS_GET]) {
            if (!empty($requestParameters[$reqField[COMMENT_REQ_ARG]])) {
                $queryString .= "`". $reqField[COMMENT_DB_ARG] ."` LIKE '".$requestParameters[$reqField[COMMENT_REQ_ARG]]."' AND ";
            }
        }
    }

	$queryString .= "TRUE  ORDER BY `commentDate` DESC".DB_QUERY_LIMIT.";";
	return $queryString;
 }
 /*
 *
 *	Trims whitespace from string fields
 *
 */
 function cleanCommentStringFields ($commentIn) {
 	$commentOut = $commentIn;
	// clean strings of extra white space
	//  string fields are listed here
     // clean strings of extra white space
     //  string fields are listed here
     $stringFields = getCommentFieldInfo();
     foreach ($stringFields as $field) {
         if (($field[COMMENT_DB_CLEANFIRST]) && (!empty($commentIn[$field[COMMENT_REQ_ARG]]))) {
             $commentOut[$field[COMMENT_REQ_ARG]] = trim($commentIn[$field[COMMENT_REQ_ARG]]);
         }
     }
	return ($commentOut);	
 }
//EOF