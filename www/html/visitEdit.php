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
* 	Edit the contents of a single patient visit
*
*		Required query parameters:
*			patientVisitID
*	`		
*		Optional
* 			clinicPatientID
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
require_once './api/patient_common.php';
require_once './api/staff_common.php';
require_once './api/staff_get.php';
require_once './api/visit_common.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$requestData = $sessionInfo['parameters'];
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/visitEditText.php');
// functions to translate Enums to localized text
require_once ('./visitUiStrings.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
require('uiSessionInfo.php');

// referrer URL to return to (in most cases)
if (isset($_SERVER['HTTP_REFERER'])) {
    $referringPageUrl = cleanedRefererUrl();
} else {
    //default return is the visit info page
    $referringPageQP = array();
    if (isset($requestData['patientVisitID'])) {
        $referringPageQP['patientVisitID'] = $requestData['patientVisitID'];
    }
    if (isset($requestData['clinicPatientID'])) {
        $referringPageQP['clinicPatientID'] = $requestData['clinicPatientID'];
    }
    $referringPageUrl = makeUrlWithQueryParams('/visitInfo.php', $referringPageQP); 
}

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// Get the selected visit record
// create query string for get operation
$visitRecord = array();
$getQueryString = "";
if (!empty($requestData['patientVisitID'])) {
	/*
	*	TODO_REST:
	*		Resource: visit
	*		Filter: patientVisitID = patientVisitID
	*		Sort: N/A
	*		Return: visit object array
	*/
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_VISIT_PATIENT_EDIT_GET. "` WHERE `patientVisitID` = '".
		$requestData['patientVisitID']."';";
	$visitRecord = getDbRecords($dbLink, $getQueryString);
	// get the list of staff members
	// get the list of users, sorted by username
	$staffQueryString['username'] = '%';
	$staffQueryString['sort'] = 'lastName';
	$staffResponse = _staff_get ($dbLink, $sessionInfo['token'], $staffQueryString);
} else {
	if (empty($dbStatus)) {
		// no query parameter found.
		$dbStatus['contentType'] = 'Content-Type: application/json; charset=utf-8';		
		if (API_DEBUG_MODE) {
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			$dbInfo['getQueryString'] = $getQueryString;
			$dbInfo['requestData'] = $requestData;
			$dbInfo['language'] = $pageLanguage;
			$dbStatus['error'] = $dbInfo;
		}
		$dbStatus['httpResponse'] = 400;
		$dbStatus['httpReason']	= TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED;
	} // else display DB error message
}
// if the visit record was not returned, exit in error
// if it was, save the data to $visitInfo
$visitInfo = array();
if ($visitRecord['httpResponse'] != 200) {
	$dbStatus = $visitRecord;
	if ($dbStatus['httpResponse'] == 404) {
		$dbStatus['httpReason']	= TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND;
	} else {
		$dbStatus['httpReason']	= TEXT_VISIT_UNABLE_OPEN_VISIT;
	}
	// load fields that should have data so the form doesn't break
	$visitInfo['deleted'] = FALSE;
	$visitInfo['firstVisit'] = 'YES';
	$visitInfo['clinicPatientID'] = '';
	$visitInfo['lastName'] = '';
	$visitInfo['firstName'] = '';
	$visitInfo['sex'] = '';
	$visitInfo['birthDate'] = '';
	$visitInfo['staffName'] = '';
	$visitInfo['primaryComplaint'] = '';
	$visitInfo['secondaryComplaint'] = '';
	$visitInfo['visitType'] = '';
	$visitInfo['visitStatus'] = '';
} else {
	$visitInfo = $visitRecord['data'];
}
// at this point, $visitInfo should have one patient visit record ready to edit.

function writeOptionsMenu ($visitInfo) {
	$optionsMenu = '';
	if (isset($visitInfo['clinicPatientID'])) {
		$optionsMenu .= '<div id="optionMenuDiv">'."\n";
		$optionsMenu .= '<ul class="topLinkMenuList">';
		$optionsMenu .= '<li class="firstLink"><a href="/visitInfo.php?patientVisitID='.$visitInfo['patientVisitID'].
			'&clinicPatientID='. $visitInfo['clinicPatientID'].'">'.TEXT_CANCEL_VISIT_EDIT.'</a></li>';
		$optionsMenu .= '</ul></div>';	}
	return $optionsMenu;
}
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_EDIT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage) ?>
	<datalist id="diagData"></datalist>
	<div class="pageBody">
	<?= writeOptionsMenu($visitInfo) ?>
	<div class="nameBlock">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($visitInfo) ?>
				<span class="idInHeading">&nbsp;&nbsp;<?= '('.$visitInfo['sex'].')' ?></span></h1>
			<p><?= date(TEXT_BIRTHDAY_DATE_FORMAT, strtotime($visitInfo['birthDate'])) ?>&nbsp;(<?= formatAgeFromBirthdate ($visitInfo['birthDate'], strtotime($visitInfo['dateTimeIn']), TEXT_VISIT_YEAR_TEXT, TEXT_VISIT_MONTH_TEXT, TEXT_VISIT_DAY_TEXT) ?>)&nbsp;&nbsp;&nbsp;
			<span class="idInHeading"><a href="/ptInfo.php?clinicPatientID=<?= $visitInfo['clinicPatientID'] ?>" title="<?= TEXT_SHOW_PATIENT_INFO ?>"><?= $visitInfo['clinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= TEXT_VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
			<p><label class="close"><?= TEXT_VISIT_ID_LABEL ?>:</label><span class="idInHeading"><?= $visitInfo['patientVisitID'] ?></span></p>
		</div>
		<div class="infoBlock <?= ($visitInfo['deleted'] ? 'showDiv' : 'hideDiv') ?>">
			<p><label class="close"><?= TEXT_VISIT_DELETED_LABEL ?>:</label><?= ($visitInfo['deleted'] ?  TEXT_VISIT_DELETED_TEXT : TEXT_VISIT_NOT_DELETED_TEXT ) ?></p>
		</div>
	</div>
    <div style="clear: both;"></div>
    <div id="optionMenuDiv"></div>
	<div id="PatientVisitView">
		<div id="PatientVisitDetails">
			<form enctype="application/x-www-form-urlencoded" action="/uihelp/updatePatientVisit.php" method="post">
				<h2><?= TEXT_VISIT_VISIT_HEADING ?></h2>
				<div class="indent1">
				<div class="dataBlock">
					<p><label class="close"><?= TEXT_FIRST_VISIT_LABEL ?>:</label>
						<select id="FirstVisitSelect" name="firstVisit" class="requiredField">
							<option value="YES" <?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'YES') ? "selected" : "" ) ?>><?= TEXT_FIRST_VISIT_SELECT ?></option>
							<option value="NO" <?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'NO') ? "selected" : "" ) ?>><?= TEXT_RETURN_VISIT_SELECT ?></option>
						</select>
						<?php
							if (!empty($requestData['lastVisit'])) {
								echo ('&nbsp;&nbsp;<label class="close">'.TEXT_LAST_VISIT_DATE_LABEL.':</label>'.
									date(TEXT_VISIT_DATE_FORMAT, strtotime($requestData['lastVisit'])));
							}
						?>
					</p>
				</div>
				<div id="visitStatus">
					<p><label class="close"><?= TEXT_VISIT_STATUS_LABEL ?>:</label>
							<select id="VisitStatusSelect" name="visitStatus" class="requiredField">
								<option value="Open" <?= ((!empty($visitInfo['visitStatus']) && $visitInfo['visitStatus'] == 'Open') ? "selected" : "" ) ?>><?= TEXT_VISIT_STATUS_OPEN ?></option>
								<option value="Closed" <?= ((!empty($visitInfo['visitStatus']) && $visitInfo['visitStatus'] == 'Closed') ? "selected" : "" ) ?>><?= TEXT_VISIT_STATUS_CLOSED ?></option>
							</select>
						</p>
				</div>
				</div>
				<div class="infoBlock">
					<h2><?= TEXT_VISIT_ARRIVAL_HEADING ?></h2>
					<div class="indent1">
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DATE_TIME_IN_LABEL.' '.TEXT_VISIT_DATE_FORMAT_LABEL ?></label>
								<?= outputDateInputFields (TEXT_VISIT_DATE_INPUT_FORMAT, 'dateTimeIn',
									(!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_MONTH_FORMAT, strtotime($visitInfo['dateTimeIn'])) : "" ),
									(!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DAY_FORMAT, strtotime($visitInfo['dateTimeIn'])) : "" ),
									(!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_YEAR_FORMAT, strtotime($visitInfo['dateTimeIn'])) : "" ),
									(!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_TIME_EDIT_FORMAT, strtotime($visitInfo['dateTimeIn'])) : "" )
								) ?>
							</p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_VISIT_TYPE_LABEL ?>:</label> &nbsp;
									<select id="visitTypeField" name="visitType" class="requiredField">
										<option value="" <?= (empty($visitInfo['visitType']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_VISIT_OPTION_VISIT ?></option>
										<?php
											foreach ($visitTypes as $visitType){
												echo ('<option value="'.$visitType[0].'" '.
													((!empty($visitType[0]) && $visitType[0] == $visitInfo['visitType']) ? 'selected' : '' ).
													'>'.$visitType[1].'</option>');
											}
										?>
									</select>
							</p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_REFERRED_FROM_LABEL ?>:</label><?= dbFieldTextInput ($visitInfo, "referredFrom", TEXT_REFERRAL_PLACEHOLDER, false) ?></p>
						</div>
						<div class="dataBlock">
							<p><label><?= TEXT_COMPLAINT_PRIMARY_LABEL ?>:</label><br>
							<textarea name="primaryComplaint" id="ComplaintPrimaryEdit" class="complaintEdit" placeholder="<?= TEXT_COMPLAINT_PRIMARY_PLACEHOLDER ?>" maxlength="2048"><?=  $visitInfo['primaryComplaint'] ?></textarea>
							</p>
						</div>
						<div class="dataBlock">
							<p><label><?= TEXT_COMPLAINT_ADDITIONAL_LABEL ?>:</label><br>
							<textarea name="secondaryComplaint" id="ComplaintAdditionalEdit" class="complaintEdit" placeholder="<?= TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER ?>" maxlength="2048"><?=  $visitInfo['secondaryComplaint'] ?></textarea>
							</p>
						</div>
                        <div class="dataBlock bottomSpace">
                            <p><label class="close"><?= TEXT_PAYMENT_LABEL ?>:</label>
                                <input type="number" name="payment" id="PaymentEdit" class="paymentEdit" min="0.00" max="9999999999.99" step="0.01" placeholder="<?= TEXT_PAYMENT_PLACEHOLDER ?>" value="<?=  (!empty($visitInfo['payment']) ? $visitInfo['payment'] : "0.00") ?>" />
                                <?= TEXT_PAYMENT_CURRENCY ?>
                            </p>
                        </div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_ASSIGNED_LABEL ?>:</label> &nbsp;
								<select id="StaffUsernameField" name="staffUsername" class="requiredField">
									<option value="" <?= (empty($visitInfo['staffUsername']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_STAFF_OPTION_VISIT ?></option>
									<?php
									if ($staffResponse['count'] > 1) {
										foreach ($staffResponse['data'] as $staffMember){
											if ($staffMember['medicalStaff']) {
												echo ('<option value="'.$staffMember['username'].'" '.
													((!empty($visitInfo['staffUsername']) && $staffMember['username'] == $visitInfo['staffUsername']) ? 'selected' : '' ).
													'>'.$staffMember['lastName'].','. $staffMember['firstName'] .'</option>');
											}
										}
									} else {
										$staffMember = $staffResponse['data'];
										if ($staffMember['medicalStaff']) {
											echo ('<option value="'.$staffMember['username'].'" '.
												((!empty($visitInfo['staffUsername']) && $staffMember['username'] == $visitInfo['staffUsername']) ? 'selected' : '' ).
												'>'.$staffMember['lastName'].', '. $staffMember['firstName'] .'</option>');
										}
									}
									?>
								</select>
							</p>
						</div>
					</div>
				</div>
				<div class="infoBlock">
					<h2><?= TEXT_VISIT_DISCHARGE_HEADING ?></h2>
					<div class="indent1">
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DATE_TIME_OUT_LABEL.' '.TEXT_VISIT_DATE_FORMAT_LABEL ?></label>
								<?= outputDateInputFields (TEXT_VISIT_DATE_INPUT_FORMAT, 'dateTimeOut',
									(!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_MONTH_FORMAT, strtotime($visitInfo['dateTimeOut'])) : "" ),
									(!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_DAY_FORMAT, strtotime($visitInfo['dateTimeOut'])) : "" ),
									(!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_YEAR_FORMAT, strtotime($visitInfo['dateTimeOut'])) : "" ),
									(!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_TIME_EDIT_FORMAT, strtotime($visitInfo['dateTimeOut'])) : "" )
								) ?>
							</p>
						</div>
                        <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 1, TEXT_DIAGNOSIS1_LABEL, TEXT_DIAGNOSIS1_PLACEHOLDER ) ?>
                        <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 2, TEXT_DIAGNOSIS2_LABEL, TEXT_DIAGNOSIS2_PLACEHOLDER ) ?>
                        <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 3, TEXT_DIAGNOSIS3_LABEL, TEXT_DIAGNOSIS3_PLACEHOLDER ) ?>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_REFERRED_TO_LABEL ?>:</label><?= dbFieldTextInput ($visitInfo, "referredTo", TEXT_REFERRAL_PLACEHOLDER, false) ?></p>
						</div>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div class="infoBlock fullWidth">
					<p class="textAlignRight"><label class="close"><?= TEXT_VISIT_DELETED_SELECT_LABEL ?>:</label>
						<select id="DeletedSelect" name="deleted">
							<option value="0" <?= ($visitInfo['deleted'] == 0 ? "selected" : "" ) ?>><?= TEXT_NO_OPTION ?></option>
							<option value="1" <?= ($visitInfo['deleted'] != 0 ? "selected" : "" ) ?>><?= TEXT_YES_OPTION ?></option>
						</select>
					</p>
				</div>
				<div style="clear: both;"></div>
				<input type="hidden" id="PatientVisitIDField" name="patientVisitID" value="<?= $visitInfo['patientVisitID'] ?>" >
				<input type="hidden" id="returnUrlField" name="returnUrl" value="<?= $referringPageUrl ?>">
				<p><button type="submit"><?= TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON ?></button></p>
			</form>
		</div>
	</div>
	</div>
	<?= icdLookupJavaScript() ?>
</body>
<?php @mysqli_close($dbLink); ?>