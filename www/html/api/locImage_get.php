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