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
*	UI to add new and edit existing staff
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

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/staffAddEditText.php');

// open session variables and check for access to this page
$pageAccessRequired = (!empty($sessionInfo['parameters']['useredit']) ? PAGE_ACCESS_READONLY : PAGE_ACCESS_CLINIC);
require('uiSessionInfo.php');

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// get staff info if possible
//	if staff record found, enter "edit" mode
//    else, enter "new" staff mode.
$staffData = '';
$pageMode = '';
$userEdit = false;
$staffInfo = '';

/*
 *  Determine the interaction mode.
 *
 *      1. it is specified in the mode query parameter
 *      2. It is inferred as edit by passing a username to edit
 *      3. It is inferred as edit the current user by the useredit query parameter
 *
 */

if (!empty($sessionInfo['parameters']['mode'])) {
	// use mode from query parameters, if specified
	if ($sessionInfo['parameters']['mode'] == 'add') {
		$pageMode = 'add';
	} else if ($sessionInfo['parameters']['mode'] == 'edit') {
		$pageMode = 'edit';
	}
} else if (!empty($sessionInfo['parameters']['username'])){
	// if the page mode is not specified in the query parameters
	//   but the username is, look up the username in the DB
	// Get the staff info from the database
	$getQP['username'] = $sessionInfo['parameters']['username'];
	$staffInfo = _staff_get ($dbLink, $sessionInfo['token'], $getQP);
	// if the staff record was not returned, exit in error
	if ($staffInfo['httpResponse'] == 200) {
		$staffData = $staffInfo['data'];
		$pageMode = 'edit';
	} else {
		// no staff records found
		$staffData = '';
		$pageMode = 'add';
	}
} else if (!empty($sessionInfo['parameters']['useredit'])){
	// the page is being called by the user to edit their personal data
	//  so look up the logged in  user
	$getQP['username'] = $sessionInfo['username'];
	$staffInfo = _staff_get ($dbLink, $sessionInfo['token'], $getQP);
	// if the staff record was not returned, exit in error
	if ($staffInfo['httpResponse'] == 200) {
		$staffData = $staffInfo['data'];
		$pageMode = 'edit';
		$userEdit = true;
	} else {
		// logged in user not found for some reason
		// return to calling page
		$redirectUrl = NULL;
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
			$redirectUrl = cleanedRefererUrl(createFromLink (null, __FILE__, 'ErrorRedirect'));
		} else {
			$redirectUrl = '/clinicDash.php'.createFromLink (FIRST_FROM_LINK_QP, __FILE__, 'ErrorRedirect'); //default: return is the home page
		}
		// Leave to the previous page or the default page as set.
		header("Location: ".$redirectUrl);
		return;
	}	
} else {
	// new staff so mode=add
	//  neither a mode nor a username was specified, 
	//  so it's probably a new record
	$pageMode = 'add';
	$staffData = '';
}
// if the record was not found in the database,
//   initialize the edit form by reading the query parameters
$queryParamFields = null;

$cancelLink = '';
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
	// default cancel action is to go back to the calling page
	$cancelLink = cleanedRefererUrl(createFromLink (null, __FILE__, 'a_cancel'));
} else {
	// if no referrer, define a reasonable value.
	if ($userEdit) {
		$cancelLink = "/clinicDash.php";
	} else {
		$cancelLink = "/adminShowUsers.php";
	}
	$cancelLink .= createFromLink (FIRST_FROM_LINK_QP, __FILE__, 'a_cancel');
}

if (empty($staffData)){
	// initialize $staffData from query parameters
	$staffData = array();
	$queryParamFields = array(
		'memberID'
		,'username'
		,'lastName'
		,'firstName'
		,'position'
		// don't copy the password
		,'contactInfo'
        ,'altContactInfo'
		,'accessGranted'
		,'active'
	);
	foreach ($queryParamFields as $fieldName) {
		if (isset($sessionInfo['parameters'][$fieldName])) {
			$staffData[$fieldName] = $sessionInfo['parameters'][$fieldName];
		}
	}
}
// clear password if present
$staffData['password'] = '';

// if pagemode == add, set the "active" field 
if (($pageMode == 'add') && (!(isset($staffData['active']) && (($staffData['active'] == 0) || ($staffData['active'] == 1))))) {
	// if this is an add operation and active has not already been assigned a value, assign it 1
	$staffData['active'] = 1;
}


function writeTopicMenu ($lang, $cancelLink) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a class="a_cancel" href="'.$cancelLink.'">'.TEXT_CANCEL_EDIT.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag($pageMode == 'add' ? TEXT_PAGE_NEW_STAFF_TITLE : TEXT_PAGE_EDIT_STAFF_TITLE ) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu ($pageLanguage, $cancelLink) ?>
	<div id="staffDataDiv">
		<div class="nameBlock">
			<h1 class="pageHeading noBottomPad"><?= ($pageMode == 'add' ? TEXT_NEW_STAFF_HEADING  : TEXT_EDIT_STAFF_HEADING  ) ?></h1>
		</div>
		<div style="clear: both;"></div>
		<form enctype="application/x-www-form-urlencoded" action="/uihelp/addStaff.php" method="post">
			<h2><?= TEXT_STAFF_ACCOUNT_INFO_LABEL ?></h2>
			<div id="accountInfo" class="indent1">
				<p <?= ($userEdit ? 'style="display: none;"' : '') ?>><label class="close"><?= TEXT_STAFF_ACTIVE_LABEL ?>:</label>&nbsp;
						<select id="activeField" name="active" class="requiredField">
							<option value="" <?= (!isset($staffData['active']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
							<option value="1" <?= ((isset($staffData['active']) && $staffData['active'] == 1) ? 'selected' : '' ) ?>><?= TEXT_STAFF_ACTIVE_OPTION_ACTIVE ?></option>
							<option value="0" <?= ((isset($staffData['active']) && $staffData['active'] == 0) ? 'selected' : '' ) ?>><?= TEXT_STAFF_ACTIVE_OPTION_INACTIVE ?></option>
						</select>
				</p>
				<p><label><?= TEXT_STAFF_USERNAME_LABEL ?>:</label><br>
					<?php
						if ($pageMode == 'add') {
							echo (dbFieldTextInput ($staffData, 'username', TEXT_STAFF_USERNAME_PLACEHOLDER, true, false,
								'text', 'piClinicEdit', 64).'&nbsp;&nbsp;');
						} else {
							echo ('<label>'.$staffData['username'].'</label>&nbsp;&nbsp;');
							echo ('<input type="hidden" id="usernameField" name="username" value="'.$staffData['username'].'">');
						}
					?>
					<?= dbFieldTextInput ($staffData, 'Password', TEXT_STAFF_PASSWORD_PLACEHOLDER, ($pageMode == 'add' ? true : false )) ?>
				</p>
				<p><label><?= TEXT_STAFF_NAME_LABEL ?>:</label><br>
					<?= dbFieldTextInput ($staffData, 'lastName', TEXT_STAFF_NAMELAST_PLACEHOLDER, true) ?>,&nbsp;
					<?= dbFieldTextInput ($staffData, 'firstName', TEXT_STAFF_NAMEFIRST_PLACEHOLDER, true) ?><br>
				</p>
				<p><label><?= TEXT_STAFF_CONTACTINFO_LABEL ?>:</label><br>
					<?= dbFieldTextInput ($staffData, 'contactInfo', TEXT_STAFF_CONTACTINFO_PLACEHOLDER, false) ?>&nbsp; &nbsp;
                    <?= dbFieldTextInput ($staffData, 'AltcontactInfo', TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER, false) ?>
				</p>
                <p><label class="close"><?= TEXT_STAFF_PREFERRED_LANGUAGE_LABEL ?>:</label>&nbsp;
                    <select id="activeField" name="preferredLanguage" class="requiredField">
                        <option value="" <?= (!isset($staffData['preferredLanguage']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
                        <option value="en" <?= ((isset($staffData['preferredLanguage']) && $staffData['preferredLanguage'] == 'en') ? 'selected' : '' ) ?>><?= TEXT_STAFF_LANGUAGE_OPTION_ENGLISH ?></option>
                        <option value="es" <?= ((isset($staffData['preferredLanguage']) && $staffData['preferredLanguage'] == 'es') ? 'selected' : '' ) ?>><?= TEXT_STAFF_LANGUAGE_OPTION_SPANISH ?></option>
                    </select>
                </p>
			</div>
			<h2 <?= ($userEdit ? 'style="display: none;"' : '') ?>><?= TEXT_STAFF_CLINIC_INFO_LABEL ?></h2>
			<div id="clinicInfo" class="indent1" <?= ($userEdit ? 'style="display: none;"' : '') ?>>
				<p><label><?= TEXT_STAFF_MEMBERID_LABEL ?>:</label><br>
					<?= dbFieldTextInput ($staffData, 'memberID', TEXT_STAFF_MEMBERID_PLACEHOLDER, false, false,
					'text', 'piClinicEdit', 32) ?>
				</p>
				<p><label class="close"><?= TEXT_STAFF_ACCESS_LABEL ?>:</label>&nbsp;
						<select id="accessGrantedField" name="accessGranted" class="requiredField">
							<option value="" <?= (empty($staffData['accessGranted']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
							<option value="ClinicReadOnly" <?= ((!empty($staffData['accessGranted']) && $staffData['accessGranted'] == 'ClinicReadOnly') ? 'selected' : '' ) ?>><?= TEXT_ACCESS_GRANTED_OPTION_RO ?></option>
							<option value="ClinicStaff" <?= ((!empty($staffData['accessGranted']) && $staffData['accessGranted'] == 'ClinicStaff') ? 'selected' : '' ) ?>><?= TEXT_ACCESS_GRANTED_OPTION_STAFF ?></option>
							<option value="ClinicAdmin" <?= ((!empty($staffData['accessGranted']) && $staffData['accessGranted'] == 'ClinicAdmin') ? 'selected' : '' ) ?>><?= TEXT_ACCESS_GRANTED_OPTION_CLINIC ?></option>
							<option value="SystemAdmin" <?= ((!empty($staffData['accessGranted']) && $staffData['accessGranted'] == 'SystemAdmin') ? 'selected' : '' ) ?>><?= TEXT_ACCESS_GRANTED_OPTION_ADMIN ?></option>
						</select>
				</p>
				<p><label class="close"><?= TEXT_STAFF_POSITION_LABEL ?>:</label>&nbsp;
						<select id="positionField" name="position" class="requiredField">
							<option value="" <?= (empty($staffData['position']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
                            <?php
                            foreach ($staffpositions as $staffposition){
                                echo ('<option value="'.$staffposition[0].'" '.
                                    ((!empty($staffData['position']) && $staffData['position'] == $staffposition[0]) ? 'selected' : '' ).
                                    '>'.$staffposition[1].'</option>');
                            }
                            ?>
						</select>
				</p>
				<input type="hidden" id="modeField" name="mode" value="<?= $pageMode ?>">
				<?= ($userEdit ? '<input type="hidden" id="UserEditField" name="useredit" value="1">' : '') ?>
				<input type="hidden" id="methodField" name="_method" value="<?= ($pageMode == 'add' ? 'POST' : 'PATCH'  ) ?>">
                <input type="hidden" id="SubmitBtnTag" name="<?= FROM_LINK ?>" value="<?= createFromLink (null, __FILE__, 'btn_submit') ?>">
			</div>
			<p><button class="btn_submit" type="submit"><?= ($pageMode == 'add' ? TEXT_STAFF_NEW_SUBMIT_BUTTON  : TEXT_STAFF_EDIT_SUBMIT_BUTTON  ) ?></button></p>
		</form>
	</div>
	</div>
</body>
<?php
@mysqli_close($dbLink);
?>