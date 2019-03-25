<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 3/25/2019
 * Time: 12:24 PM
 */
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