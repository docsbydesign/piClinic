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
/*
*	adminLogViewer
*
*		Finds the currently open log files and display the selected one in the UI
*		
*			query parameters:
*				logFile = filename of log file
*				log = the type of log file to view (latest opened by default)
*				date = the date of the log file to open (error log opened by default)
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once './shared/piClinicConfig.php';
require_once './shared/headTag.php';
require_once './shared/dbUtils.php';
require_once './api/api_common.php';
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';
require_once './api/log_common.php';
require_once './api/log_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
$requestData = $sessionInfo['parameters'];
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/adminLogViewerText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_ADMIN;
require('uiSessionInfo.php');

// open DB or redirect to error URL
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$logData = _log_get($dbLink, $sessionInfo['token'], []);
$logFilters = _log_get($dbLink, $sessionInfo['token'], ['fieldOpts' => 'true']);

if ($logFilters['count'] > 1) {
    // sort the array
    $logFilterList = $logFilters['data'];
    array_multisort(array_column($logFilterList, 'fieldName'),  SORT_NATURAL | SORT_FLAG_CASE ,
        array_column($logFilterList, 'fieldValue'), SORT_NATURAL | SORT_FLAG_CASE,
        $logFilterList);
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_ADMIN_LOG_VIEWER_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<h1 class="pageHeading"><?= TEXT_ADMIN_LOG_VIEWER_TITLE ?></h1>
	<div id="logSelectorDiv">
		<form enctype="application/x-www-form-urlencoded" action="/adminLogViewer.php"  method="get">
				<div class="infoBlock">
                    <pre>
                        <?= json_encode($logFilterList, JSON_PRETTY_PRINT) ?>
                    </pre>
                    <table>
                        <tr>
                            <th>fieldName</th>
                            <th>fieldValue</th>
                        </tr>
                    <?php

                        foreach ($logFilterList as $row) {
                            echo ('<tr>');
                            echo ('<td>');
                                echo ($row['fieldName']);
                            echo ('</td>');
                            echo ('<td>');
                                echo ($row['fieldValue']);
                            echo ('</td>');
                            echo ('</tr>');
                        }
                    ?>
                    </table>
					<p><button type="submit"><?= TEXT_LOG_FILE_SUBMIT_BUTTON ?></button></p>
				</div>
			</div>
			<div style="clear: both;"></div>
		</form>
	</div>
	<hr>
	<div id="TESTING">
	</div>
	</div>
</body>
</html>