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

    if (empty($requestArgs['image'])) {
        // missing required parameter.
        $returnValue['httpResponse'] = 400;
        $returnValue['httpReason']	= 'Required parameter \'image\' missing.';
        return $returnValue;
    }

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