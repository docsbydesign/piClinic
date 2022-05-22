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

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
$referrerUrlOverride = NO_ACCESS_URL;
require('./uiSessionInfo.php');

$errorUrl = '/adminHome.php';

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
}
$now = new DateTime();
$dateString = $now->format('_Y-m-d_H-i-s');

$backupCommand = ROOT_DIR_PATH.'../scripts/piClinicDownload.sh '.
    (API_DEBUG_MODE ? '-v ' : '-v ').
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

if (empty($backupRepsonse)) {
    // a problem occured
    $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_BACKUP_FAIL]);
    header('httpStatus: 500'); // server error
    header("httpReason: Error creating backup archive - No response from archive utility");
    header("Location: ".$redirectUrl);
}


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
    $redirectUrl = makeUrlWithQueryParams($errorUrl, ['msg'=>MSG_BACKUP_FAIL]);
    header('httpStatus: 500'); // server error
    header("httpReason: Error creating backup archive - archive utility returned an error");
    header("Location: ".$redirectUrl);
}
