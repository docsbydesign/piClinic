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
* 	Closes single patient visit
*
*		Required query parameters:
*			PatientVisitID
*	`		
*		Optional
* 			ClinicPatientID
*			lang
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files 
require_once('dbConfig.php');
require_once('db_utils.php');
require_once('api_common.php');
require_once('ui_common.php');
require_once('headTag.php');
require_once('patient_common.php');
require_once('patient_get.php');
require_once('visit_common.php');
require_once('visit_get.php');
require_once('security.php');

// read request parameters
$requestData = readRequestData ();
// Get the language for this page
$pageLanguage = getUiLanguage ($requestData);
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('visitCloseText.php');
require_once ('staffUiStrings.php');
require_once ('visitUiStrings.php');
// Get the referral URL to return after edit
// referrer URL to return to (in most cases)
if (isset($_SERVER['HTTP_REFERER'])) {
	$referringPageUrl = $_SERVER['HTTP_REFERER'];
} else {
	//default return is the visit info page
	$referringPageUrl = '/visitInfo.php';
	$qpAdded = FALSE;
	if (isset($requestData['PatientVisitID'])) {
		if (!$qpAdded) {
			$referringPageUrl .= '?';
			$qpAdded = TRUE;
		} else {
			$referringPageUrl .= '&';
		}
		$referringPageUrl .= 'PatientVisitID='.$requestData['PatientVisitID'];
	}
	if (isset($requestData['ClinicPatientID'])) {
		if (!$qpAdded) {
			$referringPageUrl .= '?';
			$qpAdded = TRUE;
		} else {
			$referringPageUrl .= '&';
		}
		$referringPageUrl .= 'ClinicPatientID='.$requestData['ClinicPatientID'];
	}
}

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('uiSessionInfo.php');

// open DB
$dbStatus = array();
$dbLink = openDbForUi ($requestData, $pageLanguage, $dbStatus);
// Get the selected visit record
// create query string for get operation
$visitRecord = array();
$getQueryString = "";
if ((!empty($requestData['PatientVisitID'])) && empty($dbStatus)) {
	/*
	*	TODO_REST:
	*		Resource: visit
	*		Filter: PatientVisitID = PatientVisitID
	*		Sort: N/A
	*		Return: visit object array
	*/
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_VISIT_PATIENT_GET. "` WHERE `PatientVisitID` = '".
		$requestData['PatientVisitID']."';";
	$visitRecord = getDbRecords($dbLink, $getQueryString);
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
		$dbStatus['httpReason']	= MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED;		
	} // else display DB error message
}
// if the visit record was not returned, exit in error
// if it was, save the data to $visitInfo
$visitInfo = array();
if ($visitRecord['httpResponse'] != 200) {
	$dbStatus = $visitRecord;
	if ($dbStatus['httpResponse'] == 404) {
		$dbStatus['httpReason']	= MESSAGE_PATIENT_VISIT_NOT_FOUND;
	} else {
		$dbStatus['httpReason']	= MESSAGE_GENERIC;
	}
	// load fields that should have data so the form doesn't break
	$visitInfo['ClinicPatientID'] = '';
	$visitInfo['PatientFamilyID'] = '';
	$visitInfo['NameLast'] = '';
	$visitInfo['NameFirst'] = '';
	$visitInfo['Sex'] = '';
	$visitInfo['BirthDate'] = '';
	$visitInfo['StaffName'] = '';
	$visitInfo['ComplaintPrimary'] = '';
	$visitInfo['ComplaintAdditional'] = '';
	$visitInfo['VisitType'] = '';
	$visitInfo['VisitStatus'] = '';
} else {
	$visitInfo = $visitRecord['data'];
}
// at this point, $visitInfo should have one patient visit record ready to edit
// set default values for closing
$visitInfo['VisitStatus'] = 'Closed';
$visitInfo['DateTimeOut'] =  date_format(date_create('now'), 'Y-m-d H:i:s'); // now;

function writeTopicMenu ($lang, $visitInfo) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a href="/visitInfo.php?PatientVisitID='.$visitInfo['PatientVisitID'].
		'&ClinicPatientID='. $visitInfo['ClinicPatientID'].
		(!empty($lang) ? "&lang=".$lang : ""). '">'.CANCEL_VISIT_EDIT.'</a></li>';
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

function writeOptionsMenu ($lang, $visitInfo) {
	$optionsMenu = '';
	if (isset($visitInfo['ClinicPatientID'])) {
		$optionsMenu .= '<div id="optionMenuDiv">'."\n";
		$optionsMenu .= '<ul class="topLinkMenuList">';
		$optionsMenu .= '<li class="firstLink"><a href="/visitEdit.php?PatientVisitID='.$visitInfo['PatientVisitID'].
			'&ClinicPatientID='. $visitInfo['ClinicPatientID'].
			(!empty($lang) ? "&lang=".$lang : ""). '">'.VISIT_EDIT_ALL_FIELDS.'</a></li>';
		$optionsMenu .= '</ul></div>';	}
	return $optionsMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(CLOSE_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage) ?>
	<datalist id="diagData"></datalist>
	<div class="pageBody">
	<?= writeTopicMenu($pageLanguage, $visitInfo) ?>
	<div class="nameBlock">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($visitInfo) ?>
				<span class="idInHeading">&nbsp;&nbsp;<?= '('.$visitInfo['Sex'].')' ?></span></h1>
			<p><?= date(BIRTHDAY_DATE_FORMAT, strtotime($visitInfo['BirthDate'])) ?>&nbsp;(<?= formatAgeFromBirthdate ($visitInfo['BirthDate'], strtotime($visitInfo['DateTimeIn']), VISIT_YEAR_TEXT, VISIT_MONTH_TEXT, VISIT_DAY_TEXT) ?>)&nbsp;&nbsp;&nbsp;
			<span class="idInHeading"><a href="/ptInfo.php?ClinicPatientID=<?= $visitInfo['ClinicPatientID'] ?><?= (!empty($requestData['lang']) ? '&lang='.$pageLanguage : '') ?>" title="<?= SHOW_PATIENT_INFO ?>"><?= $visitInfo['ClinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['DateTimeIn']) ? date(VISIT_DATE_FORMAT, strtotime($visitInfo['DateTimeIn'])) : '<span class="inactive">'.DATE_BLANK.'</span>') ?></p>
			<p><label class="close"><?= VISIT_ID_LABEL ?>:</label><span class="idInHeading"><?= $visitInfo['PatientVisitID'] ?></span></p>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div id="PatientVisitView">
		<?= writeOptionsMenu((empty($requestData['lang']) ? null : $requestData['lang']), $visitInfo) ?>
		<div id="PatientVisitDataView">
			<div id="PatientVisitDetails">
				<form enctype="application/x-www-form-urlencoded" action="/updatePatientVisit.php" method="post">
					<h2><?= VISIT_VISIT_HEADING ?></h2>
					<div class="indent1">
						<div id="visitStatus">
							<p><label class="close"><?= VISIT_STATUS_LABEL ?>:</label>
									<select id="VisitStatusSelect" name="VisitStatus" class="requiredField">
										<option value="Open" <?= ((!empty($visitInfo['VisitStatus']) && $visitInfo['VisitStatus'] == 'Open') ? "selected" : "" ) ?>><?= VISIT_STATUS_OPEN ?></option>
										<option value="Closed" <?= ((!empty($visitInfo['VisitStatus']) && $visitInfo['VisitStatus'] == 'Closed') ? "selected" : "" ) ?>><?= VISIT_STATUS_CLOSED ?></option>
									</select>
								</p>
						</div>
					</div>
					<div class="infoBlock">
						<h2><?= VISIT_ARRIVAL_HEADING ?></h2>
						<div class="indent1">
							<div class="dataBlock">
								<p><label class="close"><?= DATE_TIME_IN_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['DateTimeIn']) ? date(VISIT_DATE_FORMAT, strtotime($visitInfo['DateTimeIn'])) : '<span class="inactive">'.DATE_BLANK.'</span>') ?>
								&nbsp;&nbsp;&nbsp;<label class="close"><?= VISIT_TYPE_LABEL ?>:</label>&nbsp;<?= $visitInfo['VisitType'] ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= REFERRED_FROM_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['ReferredFrom']) ? $visitInfo['ReferredFrom'] : '<span class="inactive">'.REFERRAL_BLANK.'</span>') ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= COMPLAINT_PRIMARY_LABEL ?>:</label>&nbsp;<?= $visitInfo['ComplaintPrimary'] ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= COMPLAINT_ADDITIONAL_LABEL ?>:</label>&nbsp;<?= $visitInfo['ComplaintAdditional'] ?></p>
							</div>
                            <div class="dataBlock bottomSpace">
                                <p><label class="close"><?= PAYMENT_LABEL ?>:</label>
                                    <input type="number" name="Payment" id="PaymentEdit" class="paymentEdit" min="0.00" max="9999999999.99" step="0.01" placeholder="<?= PAYMENT_PLACEHOLDER ?>" value="<?=  (!empty($visitInfo['Payment']) ? $visitInfo['Payment'] : "0.00") ?>" />
                                    <?= PAYMENT_CURRENCY ?>
                                </p>
                            </div>
							<div class="dataBlock">
								<p><label class="close"><?= ASSIGNED_LABEL ?>:</label>&nbsp;<?= $visitInfo['StaffName'] ?></p>
							</div>
						</div>
					</div>
					<div class="infoBlock">
						<h2><?= VISIT_DISCHARGE_HEADING ?></h2>
						<div class="indent1">
							<div class="dataBlock">
								<p><label class="close"><?= DATE_TIME_OUT_LABEL.' '.VISIT_DATE_FORMAT_LABEL ?></label>
									<?= outputDateInputFields (VISIT_DATE_INPUT_FORMAT, 'DateTimeOut',
										(!empty($visitInfo['DateTimeOut']) ? date(VISIT_MONTH_FORMAT, strtotime($visitInfo['DateTimeOut'])) : "" ),
										(!empty($visitInfo['DateTimeOut']) ? date(VISIT_DAY_FORMAT, strtotime($visitInfo['DateTimeOut'])) : "" ),
										(!empty($visitInfo['DateTimeOut']) ? date(VISIT_YEAR_FORMAT, strtotime($visitInfo['DateTimeOut'])) : "" ),
										(!empty($visitInfo['DateTimeOut']) ? date(VISIT_TIME_FORMAT, strtotime($visitInfo['DateTimeOut'])) : "" )
									) ?>
								</p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= DIAGNOSIS1_LABEL ?>:</label>
									<select id="Condition1Select" name="Condition1">
										<option value="" <?= (empty($visitInfo['Condition1'])  ? "selected" : "" ) ?> ><?= CONDITION_SELECT ?></option>
										<option value="NEWDIAG" <?= ((!empty($visitInfo['Condition1']) && $visitInfo['Condition1'] == 'NEWDIAG') ? "selected" : "" ) ?>><?= CONDITION_NEW_SELECT ?></option>
										<option value="SUBSDIAG" <?= ((!empty($visitInfo['Condition1']) && $visitInfo['Condition1'] == 'SUBSDIAG') ? "selected" : "" ) ?>><?= CONDITION_SUBSEQUENT_SELECT ?></option>
									</select>
								<br><?= showDiagnosisInput ($dbLink, $visitInfo, "Diagnosis1", $pageLanguage, DIAGNOSIS1_PLACEHOLDER, DIAGNOSIS_LOADING, 'piClinicEdit fullWidth', 255, true, true) ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= DIAGNOSIS2_LABEL ?>:</label>
									<select id="Condition2Select" name="Condition2">
										<option value="" <?= (empty($visitInfo['Condition2'])  ? "selected" : "" ) ?> ><?= CONDITION_SELECT ?></option>
										<option value="NEWDIAG" <?= ((!empty($visitInfo['Condition2']) && $visitInfo['Condition2'] == 'NEWDIAG') ? "selected" : "" ) ?>><?= CONDITION_NEW_SELECT ?></option>
										<option value="SUBSDIAG" <?= ((!empty($visitInfo['Condition2']) && $visitInfo['Condition2'] == 'SUBSDIAG') ? "selected" : "" ) ?>><?= CONDITION_SUBSEQUENT_SELECT ?></option>
									</select>
								<br><?= showDiagnosisInput ($dbLink, $visitInfo, "Diagnosis2", $pageLanguage, DIAGNOSIS2_PLACEHOLDER, DIAGNOSIS_LOADING, 'piClinicEdit fullWidth') ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= DIAGNOSIS3_LABEL ?>:</label>
									<select id="Condition3Select" name="Condition3">
										<option value="" <?= (empty($visitInfo['Condition3'])  ? "selected" : "" ) ?> ><?= CONDITION_SELECT ?></option>
										<option value="NEWDIAG" <?= ((!empty($visitInfo['Condition3']) && $visitInfo['Condition3'] == 'NEWDIAG') ? "selected" : "" ) ?>><?= CONDITION_NEW_SELECT ?></option>
										<option value="SUBSDIAG" <?= ((!empty($visitInfo['Condition3']) && $visitInfo['Condition3'] == 'SUBSDIAG') ? "selected" : "" ) ?>><?= CONDITION_SUBSEQUENT_SELECT ?></option>
									</select>
								<br><?= showDiagnosisInput ($dbLink, $visitInfo, "Diagnosis3", $pageLanguage, DIAGNOSIS3_PLACEHOLDER, DIAGNOSIS_LOADING, 'piClinicEdit fullWidth') ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= REFERRED_TO_LABEL ?>:</label><?= dbFieldTextInput ($visitInfo, "ReferredTo", REFERRAL_PLACEHOLDER, false) ?></p>
							</div>
						</div>
					</div>
					<div style="clear: both;"></div>
					<input type="hidden" id="PatientVisitIDField" name="PatientVisitID" value="<?= $visitInfo['PatientVisitID'] ?>" >
					<?= (!empty($requestData['lang']) ? '<input type="hidden" id="langField" name="lang" value="'.$pageLanguage.'" >': "") ?>
					<input type="hidden" id="returnUrlField" name="returnUrl" value="<?= $referringPageUrl ?>">
					<p><button type="submit"><?= PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON ?></button></p>
				</form>
			</div>
		</div>
	</div>
	</div>
	<?= icdLookupJavaScript() ?>
</body>
<?php @mysqli_close($dbLink); ?>
