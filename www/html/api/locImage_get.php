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
 *	Retrieves a localized image and returns it with the correct text
 * 		or an HTML error message
 *
 *	GET: Returns comment records that match the specified query parameters
 *
 *	Identification query parameters:
 *		The comment record(s) will be returned that the fields specified in the query parameter.
 *
 *   		`image` - the image file to process
 *
 *		Returns:
 *			200: the matching comment record(s)
 *			404: no record found that matches the query parameters
 *			500: server error information
 *
 *	Returns
 *      the image bytes
 *
 *********************/
exitIfCalledFromBrowser(__FILE__);
/*
 *
 */
function _locImage_get ($dbLink, $apiUserToken, $requestArgs) {
    /*
     *  Initialize profiling when enabled in piClinicConfig.php
     */
	$profileData = array();
	profileLogStart ($profileData);
	// format db table fields as dbInfo array
	$returnValue = array();
	$returnValue['contentType'] = 'Content-Type: application/json; charset=utf-8';
	$returnValue['data'] = NULL;
    $returnValue['httpResponse'] = 404;
    $returnValue['httpReason']	= 'Resource not found.';

    if (empty($requestArgs['image']) || empty($requestArgs['language'])) {
        // missing required parameter.
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= 'A required parameter is missing. Both image and language must be specified.';
        return $returnValue;
    }

    $pageLanguage = $requestArgs['language']; // used by included file

    profileLogCheckpoint($profileData,'PARAMETERS_VALID');

    $dbVal = array();
    $dbVal['request'] = $requestArgs;
    $dbVal['root'] =  $_SERVER['DOCUMENT_ROOT'];

    $imageData = array();
    $imageData['imagePath'] = $_SERVER['DOCUMENT_ROOT'].$requestArgs['image'];
    $dbVal['imagePath'] = $imageData['imagePath'];

    if (file_exists($dbVal['imagePath'])){
        $dbVal['imageExists'] = 'Yes';
        $imageData['mimeType'] = mime_content_type ($imageData['imagePath']);
        if ($imageData['mimeType'] == 'image/svg+xml') {
            // see if this image has a corresponding localized text file
            // file is in the uitext sub folder of the folder that has the image
            // get the base filename
            $uiTextFile = basename($imageData['imagePath']);
            // find the file's directory
            $uiTextFolder = substr($imageData['imagePath'],0, -(strlen($uiTextFile)));
            $uiTextFolder .= 'uitext/'; // add in subfolder name
            $uiTextFile = substr($uiTextFile, 0,  -(strlen('.svg')));
            // build the text file path now
            $uiTextFilePath = $uiTextFolder . $uiTextFile . 'Text.php';
            $dbVal['textFilePath'] = $uiTextFilePath;
            if (file_exists($uiTextFilePath)) {
                // read it in
                require_once ($uiTextFilePath);
                // scan and replace the text
                $imageFileData = file_get_contents($imageData['imagePath']);
                if ($imageFileData !== FALSE) {
                    // create a list of substitutable text tokens
                    $locTags = array();
                    preg_match_all("/TEXT_[0-9A-Z_]+/", $imageFileData, $locTags, PREG_SET_ORDER);
                    $dbVal['locTags'] = $locTags;
                    foreach ($locTags as $tag) {
                        // collect the changes in the original string
                        // add characters to provide context for the search and replace strings
                        $oldString = '>'.$tag[0].'<';
                        $newString = '>'.constant($tag[0]).'<';
                        $imageFileData = str_replace ($oldString, $newString, $imageFileData);
                    }
                    $imageData['imageBytes'] = $imageFileData;
                } else {
                    $dbVal['textFileError'] = error_get_last();
                }
            } else {
                $dbVal['textFileError'] = ["error" => 'Text file not found', "path" => $uiTextFilePath];
            }
        }

        $returnValue['data'] = $imageData;
        $returnValue['httpResponse'] = 200;
        $returnValue['httpReason']	= 'Success-1';
        $returnValue['count'] = 1;
        $returnValue['format'] = 'image';
        $dbVal['fileSize'] = filesize($imageData['imagePath']);
    } else {
        // leave as default
        $dbVal['imageExists'] = 'No';
    }

    $returnValue['debug'] = $dbVal;
	profileLogClose($profileData, __FILE__, $requestArgs);
	return $returnValue;
}
//EOF
