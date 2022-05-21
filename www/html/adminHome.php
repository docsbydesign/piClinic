<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
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
*
*	Administration Dashboard (home page)
*
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
$profileData = [];
profileLogStart ($profileData);

// get the query parameter data from the request
// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
require_once ('./uitext/adminHomeText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('uiSessionInfo.php');

// set  style for system admin only links
$showAdmin = (checkUiSessionAccess (null, $sessionInfo['token'], PAGE_ACCESS_ADMIN) ? '' : 'noshow');
// set  style for clinic admin only links
$showClinic = (checkUiSessionAccess (null, $sessionInfo['token'], PAGE_ACCESS_CLINIC) ? '' : 'noshow');

profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PICLINIC_SYSTEM_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(ADMIN_PAGE, $sessionInfo, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<div id="ReportList">
		<h1 class="pageHeading"><?= TEXT_PICLINIC_SYSTEM_PAGE_TITLE ?></h1>
		<ul>
            <li><a href="/adminShowUsers.php" title="<?= TEXT_ADMIN_MANAGE_USERS_TITLE ?>"><?= TEXT_ADMIN_MANAGE_USERS_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_MANAGE_USERS_DESCRIPTION ?></li>
            <li class="separated <?= $showAdmin ?>"><a href="/adminLogViewer.php" title="<?= TEXT_ADMIN_LOG_VIEWER_TITLE ?>"><?= TEXT_ADMIN_LOG_VIEWER_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_LOG_VIEWER_DESCRIPTION ?></li>
            <li <?= 'class="'.$showAdmin.'"' ?>><a href="/adminShowComments.php" title="<?= TEXT_ADMIN_SHOW_COMMENTS_TITLE ?>"><?= TEXT_ADMIN_SHOW_COMMENTS_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION ?></li>
            <li class="separated <?= $showClinic ?>"><a href="/adminBackup.php?type=patient" title="<?= TEXT_ADMIN_BACKUP_PATIENT_TITLE ?>"><?= TEXT_ADMIN_BACKUP_PATIENT_LINK ?></a>: <?= TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION ?></li>
            <li <?= 'class="'.$showAdmin.'"' ?>><a href="/adminBackup.php?type=log" title="<?= TEXT_ADMIN_BACKUP_LOG_TITLE ?>"><?= TEXT_ADMIN_BACKUP_LOG_LINK ?></a>: <?= TEXT_ADMIN_BACKUP_LOG_DESCRIPTION ?></li>
            <li <?= 'class="'.$showClinic.'"' ?>><a href="/adminBackup.php?type=db" title="<?= TEXT_ADMIN_BACKUP_DB_TITLE ?>"><?= TEXT_ADMIN_BACKUP_DB_LINK ?></a>: <?= TEXT_ADMIN_BACKUP_DB_DESCRIPTION ?></li>
            <li <?= 'class="'.$showAdmin.'"' ?>><a href="/adminBackup.php?type=system" title="<?= TEXT_ADMIN_BACKUP_ALL_TITLE ?>"><?= TEXT_ADMIN_BACKUP_ALL_LINK ?></a>: <?= TEXT_ADMIN_BACKUP_ALL_DESCRIPTION ?></li>
            <li class="separated"><a href="/reports/rptMonthlyPtSummHome.php?showdiag=2" title="<?= TEXT_MONTHLY_SUMMARY_DATA_TITLE ?>"><?= TEXT_MONTHLY_SUMMARY_DATA_LINK ?></a></li>
            <li><a href="/reports/rptMonthlyPosSummHome.php?showdiag=2" title="<?= TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE ?>"><?= TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK ?></a></li>
		</ul>
	</div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $sessionInfo['parameters']); ?>
</html>
