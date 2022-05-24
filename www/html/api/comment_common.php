<?php
/*
 *
 * Copyright (c) 2018 by Robert B. Watson
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
