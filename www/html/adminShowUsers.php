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
*	List of clinic staff managed by this system
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
require_once './api/staff_common.php';
require_once './api/staff_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/adminShowUsersText.php');
require_once ('./staffUiStrings.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('uiSessionInfo.php');

// open DB or redirect to error URL
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$staffResponse = null;
// get the list of users, sorted by username
$staffQueryString['username'] = '%';
if (!empty($requstData['sort'])) {
    $staffQueryString['sort'] = $requstData['sort'];
}
$staffResponse = _staff_get ($dbLink, $sessionInfo['token'], $staffQueryString);
// $staffResponse has the current list of staff / users

function translateClinicGroup($staffPositions, $value) {
    foreach ($staffPositions as $staffPosition){
        if ($staffPosition[0] == $value) {
            return $staffPosition[1];
        }
    }
	return TEXT_STAFF_POSITION_UNKNOWN;
}
function translateAccessGranted($value) {
	switch($value) {
		case 'SystemAdmin':
			return TEXT_ACCESS_GRANTED_OPTION_ADMIN;
		case 'ClinicAdmin':
			return TEXT_ACCESS_GRANTED_OPTION_CLINIC;
		case 'ClinicStaff':
			return TEXT_ACCESS_GRANTED_OPTION_STAFF;
		case 'ClinicReadOnly':
			return TEXT_ACCESS_GRANTED_OPTION_RO;
		default:
			return TEXT_ACCESS_GRANTED_OPTION_UNKNOWN;
	}
}
function writeTopicMenu () {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a href="/staffAddEdit.php">'.TEXT_ADD_NEW_USER.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}


profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PICLINIC_USERS_PAGE_TITLE) ?>
<body>
    <?= piClinicTag(); ?>
    <?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
    <?php require ('uiErrorMessage.php') ?>
    <?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
    <div class="pageBody">
	<div id="StaffListDiv">
<?php
	if (isset($staffResponse)) {
		if ($staffResponse['httpResponse'] != 200){
			echo ('<h1 class="pageHeading">'.TEXT_NO_STAFF_RECORDS.'</h1>');
			echo (writeTopicMenu ());
		} else {
			echo ('<h1 class="pageHeading" id="staffListHeading">'.TEXT_STAFF_LIST_HEAD.'</h1>');
			echo (writeTopicMenu ());
			$staffList = [];
			if ($staffResponse['count'] == 1) {
				// there's only one so make it an array element 
				// so the rest of the code works
				$staffList[0] = $staffResponse['data'];
			} else {
				$staffList = $staffResponse['data'];
			}
			// check to see if they are currently in the clinic
			if (!empty($staffList)) {
				$headerShown = false;
				foreach ($staffList as $staff) {
					if (!$headerShown) {
						echo ('<table class="piClinicList"><tr>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_MEMBERID.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_USERNAME.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_NAME.'</th>');								
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_POSITION.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_CONTACTINFO.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_ACCESSGRANTED.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_LASTLOGIN.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_MODIFIEDDATE.'</th>');
						echo ('<th>'.TEXT_STAFF_LIST_HEAD_CREATEDDATE.'</th>');
						echo ('</tr>');
						$headerShown = true;
					}
					$usernameLink = TEXT_VALUE_NOT_SET;
					if (!empty($staff['username'])){
						$usernameLink = '<a href="/staffAddEdit.php?username='.$staff['username'].
							'" title="'.TEXT_STAFF_EDIT_USERNAME_TITLE.'">'.$staff['username'].'</a>';						
					} 
					echo ('<tr>');
					// memberID
					echo ('<td class="nowrap'.(empty($staff['memberID']) ? ' inactive': '').'">'.(empty($staff['memberID']) ? TEXT_VALUE_NOT_SET : $staff['memberID'] ).'</td>');
					// username (and !active)
					echo ('<td class="nowrap">'.$usernameLink.'</td>');
					// Name (Last & First)
					echo ('<td class="nowrap'.(empty($staff['active']) ? ' inactive': '').'">'.$staff['lastName'].',&nbsp;'.$staff['firstName'].'</td>');
					// position
					echo ('<td class="nowrap'.(empty($staff['position']) ? ' inactive': '').'">'.(empty($staff['position']) ? TEXT_VALUE_NOT_SET : translateClinicGroup($staffPositions, $staff['position']) ).'</td>');
					// contactInfo
					echo ('<td class="nowrap'.(empty($staff['contactInfo']) ? ' inactive': '').'">'.(empty($staff['contactInfo']) ? TEXT_VALUE_NOT_SET : $staff['contactInfo'] ).'</td>');
					// accessGranted
					echo ('<td class="nowrap'.(empty($staff['accessGranted']) ? ' inactive': '').'">'.(empty($staff['accessGranted']) ? TEXT_VALUE_NOT_SET : translateAccessGranted($staff['accessGranted']) ).'</td>');
					// lastLogin
					echo ('<td class="nowrap'.(empty($staff['lastLogin']) ? ' inactive': '').'">'.(empty($staff['lastLogin']) ? TEXT_VALUE_NOT_SET : date(TEXT_STAFF_DATE_FORMAT, strtotime($staff['lastLogin'])).'</td>'));
					// Last Modified
					echo ('<td class="nowrap'.(empty($staff['modifiedDate']) ? ' inactive': '').'">'.(empty($staff['modifiedDate']) ? TEXT_VALUE_NOT_SET : date(TEXT_STAFF_DATE_FORMAT, strtotime($staff['modifiedDate'])).'</td>'));
					// Created date
					echo ('<td class="nowrap'.(empty($staff['createdDate']) ? ' inactive': '').'">'.(empty($staff['createdDate']) ? TEXT_VALUE_NOT_SET : date(TEXT_STAFF_DATE_FORMAT, strtotime($staff['createdDate'])).'</td>'));
					echo ('</tr>');					
				}
				if ($headerShown) {
					// there's a table so draw a rule below it
					echo ('<tr><td class="noborder" colspan="2">&nbsp;</td>');
					echo ('<td class="noborder inactive nowrap">'.TEXT_USERNAME_NOT_ACTIVE.'</td>');
					echo ('<td class="noborder" colspan="6">&nbsp;</td></tr>');
					echo ('</table>');
				}			
			}
		} 
	} else {
		echo ('<h1 class="pageHeading">'.TEXT_NO_STAFF_RECORDS.'</h1>');
	}

	@mysqli_close($dbLink);
?>
	</div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $sessionInfo['parameters']); ?>
</html>
