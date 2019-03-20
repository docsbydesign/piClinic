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
require_once ('./uitext/adminHomeText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('uiSessionInfo.php');

profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PICLINIC_SYSTEM_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(ADMIN_PAGE, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<div id="ReportList">
		<h1 class="pageHeading"><?= TEXT_PICLINIC_SYSTEM_PAGE_TITLE ?></h1>
		<ul>
            <li><a href="/adminShowUsers.php" title="<?= TEXT_ADMIN_MANAGE_USERS_TITLE ?>"><?= TEXT_ADMIN_MANAGE_USERS_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_MANAGE_USERS_DESCRIPTION ?></li>
            <!-- <li><a href="/adminLogViewer.php<" title="<?= TEXT_ADMIN_LOG_VIEWER_TITLE ?>"><?= TEXT_ADMIN_LOG_VIEWER_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_LOG_VIEWER_DESCRIPTION ?></li> -->
            <!-- <li><a href="/adminShowComments.php" title="<?= TEXT_ADMIN_SHOW_COMMENTS_TITLE ?>"><?= TEXT_ADMIN_SHOW_COMMENTS_LINK ?></a>:&nbsp;<?= TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION ?></li> -->
            <li class="separated"><a href="/reports/rptMonthlyPtSummHome.php?showdiag=2" title="<?= TEXT_MONTHLY_SUMMARY_DATA_TITLE ?>"><?= TEXT_MONTHLY_SUMMARY_DATA_LINK ?></a></li>
            <li><a href="/reports/rptMonthlyPosSummHome.php?showdiag=2" title="<?= TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE ?>"><?= TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK ?></a></li>
		</ul>
	</div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $sessionInfo['parameters']); ?>
</html>