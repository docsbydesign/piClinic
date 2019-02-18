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
/*
*	clinicDash
*		Starts an authenticated session
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
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// requierd for error messages
$requestData = $sessionInfo['parameters'];
require_once './uitext/clinicDashText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
$referrerUrlOverride = NO_ACCESS_URL;
require './uiSessionInfo.php';

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

profileLogCheckpoint($profileData,'CODE_COMPLETE');

// *************** HTML starts here ********************
?>
<?= pageHtmlTag($sessionInfo['sessionLanguage']) ?>
<?= pageHeadTag(TEXT_CLINIC_DASH_PAGE_TITLE) ?>
<body>
    <?= piClinicTag(); ?>
    <?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
    <?php require ('uiErrorMessage.php'); ?>
    <?= piClinicAppMenu(HOME_PAGE, $sessionInfo['pageLanguage']) ?>
    <div class="pageBody">
        <div id="PatientLookupDiv" class="noprint">
            <form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">
                <p><label><?= TEXT_PATIENT_ID_LABEL ?>:</label><br>
                    <?= dbFieldTextInput ($sessionInfo['parameters'], "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true) ?>
                    <button type="submit"><?= TEXT_SHOW_PATIENT_SUBMIT_BUTTON ?></button>
                </p>
            </form>
            <hr>
        </div>
        <div id="ClinicVisitsDiv">
            <h1>Clinic Dash</h1>
            <pre>
<?= json_encode(['apiUserToken' => $sessionInfo['token']], JSON_PRETTY_PRINT) ?><br>
<?= json_encode($_SESSION, JSON_PRETTY_PRINT) ?><br>
<?= json_encode($sessionInfo, JSON_PRETTY_PRINT) ?><br>
<?= json_encode(['pageLanguage' => $pageLanguage]) ?>
            </pre>
            <p><a href="/uihelp/endUiSession.php" title="Log out and end session">Log out</a></p>
        </div>
    </div>
</body>
</html>