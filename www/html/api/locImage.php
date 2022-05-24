<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 3/25/2019
 * Time: 12:24 PM
 */
 /*
  *
  * Copyright (c) 2019 by Robert B. Watson
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
 *	Returns the specified SVG image with localized text inserted
 * 		or an HTML error message
 *
 *      format:    /api/locImage.php?image=<image_filename>
 *
 *      where: image=<image_filename> refers to the relative URL of the .SVG file to return
 *
 *      The module assumes that a UI text file that can be included is located in a subdirectory and named appropriately
 *      For example: if the image file is /assets/images/imagefile.svg,
 *                      the text file would be in /assets/images/uitext/imagefileText.php
 *                      the text file is a php include file with the strings defined in the same manner as the UI files.
 *
 ******************/
require_once '../shared/piClinicConfig.php';
require_once '../shared/dbUtils.php';
require_once 'api_common.php';
require_once '../shared/security.php';
require_once '../shared/profile.php';
require_once '../shared/logUtils.php';
require_once 'locImage_get.php';
/*
 *  Initialize profiling when enabled in piClinicConfig.php
 */
$profileData = array();
profileLogStart ($profileData);

// get the query paramater data from the request
$requestData = readRequestData();
$apiUserToken = getTokenFromHeaders(); // we get it, but it's not used.

$dbLink = _openDBforAPI($requestData);

profileLogCheckpoint($profileData,'DB_OPEN');

$retVal = array();

// Initalize the log entry for this call
//  more fields will be added later in the routine
$logData = createLogEntry ('API',
    __FILE__,
    'locImage',
    $_SERVER['REQUEST_METHOD'],
    null,
    $_SERVER['QUERY_STRING'],
    json_encode(getallheaders ()),
    null,
    null,
    null);

$logData['userToken'] = $apiUserToken;
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $retVal = _locImage_get($dbLink, $apiUserToken, $requestData);
        break;

    default:
        $retVal['contentType'] = 'Content-Type: application/json';
        if (API_DEBUG_MODE) {
            $retVal['error'] = $requestData;
        }
        $retVal['httpResponse'] = 405;
        $retVal['httpReason'] = 'Method not supported.';
        break;
}

// close the DB link until next time
@mysqli_close($dbLink);
$profileTime = profileLogClose($profileData, __FILE__, $requestData);
if ($profileTime !== false) {
    if (empty($retVal['debug'])) {
        $retVal['debug'] = array();
    }
    $retVal['debug']['profile'] = $profileTime;
}
outputResults ($retVal);
//EOF
