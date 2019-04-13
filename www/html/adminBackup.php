<?php
/*
 *
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
/*
 *      Calls the piClinic Backup procedure to backup system and database content
 *
 *          query parameters supported:
 *
 *              type=<one of the following>
 *                  patient - backs up patient data
 *                  logs    - backs up usage logs and comments
 *                  db      - backs up entire piclinic database
 *                  system  - backs up logs and entire database
 *
 */
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once './shared/piClinicConfig.php';
require_once './shared/headTag.php';
require_once './shared/dbUtils.php';
require_once './shared/logUtils.php';
require_once './api/api_common.php';
require_once './api/visit_common.php';
require_once './api/visit_get.php';
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';
require_once '../pass/dbPass.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// requierd for error messages
$requestData = $sessionInfo['parameters'];
require_once './uitext/adminBackupText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
$referrerUrlOverride = NO_ACCESS_URL;
require('./uiSessionInfo.php');

// check for query parameters and format query values
if (empty($requestData) || empty($requestData['type'])) {
    // server error
    $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_REQUIRED_FIELD_MISSING]);
    header('httpStatus: 500'); // server error
    header("httpReason: Required query parameter missing");
    header("Location: ".$redirectUrl);
    exit();
}

// create command line for backup script
$backupParam = '';
switch ($requestData['type']) {
    case 'patient':
    case 'db':
    case 'log':
    case 'system':
        $backupParam = $requestData['type'];
        break;

    default:
        // server error
        $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_REQUIRED_FIELD_MISSING]);
        header('httpStatus: 500'); // server error
        header("httpReason: Required query parameter missing");
        header("Location: ".$redirectUrl);
        exit();
        break;
}
$now = new DateTime();
$dateString = $now->format('_Y-m-d_H-i-s');

$backupCommand = ROOT_DIR_PATH.'../scripts/piClinicDownload.sh '.
    (API_DEBUG_MODE ? '-v ' : '').
    '-f piClinicBackup_'.$backupParam.$dateString.'.tgz '.
    '-t '.$backupParam.' '.
    '-p '.DB_PASS;

// $backupCommand = 'sh ../scripts/piClinicDownload.sh -h';
profileLogCheckpoint($profileData,'CODE_COMPLETE');

$cmdStatus = -1;
$cmdOutput = array();
$backupRepsonse = exec($backupCommand, $cmdOutput, $cmdStatus);

// check on result of command:
// success response starts with "piClinic archive ready: " followed by the archive file path

$successResponse = 'piClinic archive ready: ';
if (substr_compare($backupRepsonse, $successResponse, 0, strlen($successResponse) ) == 0) {
    // success create link
    $result = 'success';
    $filePath = substr($backupRepsonse, strlen($successResponse));
    if (file_exists($filePath)) {
        $fileSize = filesize($filePath);
        if ($fileSize > 1024*1024 ) {
            $fileSizeString = strval(round($fileSize/(1024*1024), 0)).' MB';
        } else if ($fileSize > 1024 ) {
            $fileSizeString = strval(round($fileSize/1024, 0)).' KB';
        } else {
            $fileSizeString = strval($fileSize).' bytes';
        }
        header('Content-type: text/html; charset=utf-8');
        header('Content-Description: File Transfer');
        header('Content-Type: application/tar+gzip');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        }
} else {
    // a problem occured
    $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_REQUIRED_FIELD_MISSING]);
    header('httpStatus: 500'); // server error
    header("httpReason: Error creating backup archive");
    header("Location: ".$redirectUrl);
}
