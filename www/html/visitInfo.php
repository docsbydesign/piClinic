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
* 	Show the contents of a single patient visit for reading/viewing
*
*		Required query parameters:
*			patientVisitID
*	`		
*		Optional
* 			clinicPatientID
*			lang
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
require_once './api/visit_common.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$requestData = $sessionInfo['parameters'];
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/visitInfoText.php');
// functions to translate Enums to localized text
require_once ('./visitUiStrings.php');

// open DB
// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
require('uiSessionInfo.php');

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// Get the selected visit record
// create query string for get operation
$visitRecord = [];
$staffResponse = [];
$getQueryString = '';
if ((!empty($requestData['patientVisitID'])) && empty($dbStatus)) {
	/*
	*	TODO_REST:
	*		Resource: visit
	*		Filter: patientVisitID = patientVisitID
	*		Sort: N/A
	*		Return: visit object array
	*/
	$getQueryString = "SELECT * FROM `".
		DB_VIEW_VISIT_PATIENT_GET. "` WHERE `patientVisitID` = '".
		$requestData['patientVisitID']."';";
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
		$dbStatus['httpReason']	= TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED;
	} // else display DB error message
}
// if the visit record was not returned, exit in error
// if it was, save the data to $visitInfo
$visitInfo = array();
if ($visitRecord['httpResponse'] != 200) {
	$dbStatus = $visitRecord;
	if ($dbStatus['httpResponse'] == 404) {
		$dbStatus['httpReason']	= TEXT_PATIENT_VISIT_NOT_FOUND;
	} else {
		$dbStatus['httpReason']	= TEXT_VISIT_UNABLE_OPEN_VISIT;
	}
	// load fields that should have data so the form doesn't break
	$visitInfo['clinicPatientID'] = '';
	$visitInfo['patientLastName'] = '';
	$visitInfo['patientFirstName'] = '';
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

function writeTopicMenu ($sessionInfo) {
	$topicMenu = '<div id="topicMenuDiv" class="noprint">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
    $topicMenu .= '<li class="firstLink">'.
        '<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">'.TEXT_FIND_ANOTHER_LINK.': '.
        dbFieldTextInput ($sessionInfo['parameters'], "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true).
        '&nbsp;<button type="submit">'.TEXT_SHOW_PATIENT_SUBMIT_BUTTON.'</button></form></li>';
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_VISIT_DETAILS_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu($sessionInfo) ?>
	<div class="nameBlock">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($visitInfo) ?>
				<span class="idInHeading">&nbsp;&nbsp;<?= '('.$visitInfo['sex'].')' ?></span></h1>
			<p><?= date(TEXT_BIRTHDAY_DATE_FORMAT, strtotime($visitInfo['birthDate'])) ?>&nbsp;(<?= formatAgeFromBirthdate ($visitInfo['birthDate'], strtotime($visitInfo['dateTimeIn']), TEXT_VISIT_YEAR_TEXT, TEXT_VISIT_MONTH_TEXT, TEXT_VISIT_DAY_TEXT) ?>)&nbsp;&nbsp;&nbsp;
			<span class="idInHeading"><a class="a_ptInfo" href="/ptInfo.php?clinicPatientID=<?= $visitInfo['clinicPatientID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_ptInfo') ?>" title="<?= TEXT_SHOW_PATIENT_INFO ?>"><?= $visitInfo['clinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= TEXT_VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
			<p><label class="close"><?= TEXT_VISIT_ID_LABEL ?>:</label><span class="idInHeading"><?= $visitInfo['patientVisitID'] ?></span></p>
		</div>
		<div class="infoBlock">
			<img class="barcode" alt="<?= $visitInfo['patientVisitID'] ?>" src="code39.php?code=<?= $visitInfo['patientVisitID'] ?>&y=44">
		</div>
	</div>
	<div style="clear: both;"></div>
	<div id="optionMenuDiv" class="noprint">
		<ul class="topLinkMenuList">
			<?php
				$firstLinkDefined = false;
				if (isset($visitInfo['clinicPatientID'])) {
					if (isset($visitInfo['visitStatus']) && ($visitInfo['visitStatus'] == 'Open')) {
						// this visit is open so show the close option
						if ($firstLinkDefined) {
							echo('<li>');
						} else {
							echo('<li class="firstLink">');
							$firstLinkDefined = true;
						}
						echo ('<a class="a_visitClose" href="/visitClose.php?patientVisitID='.$visitInfo['patientVisitID'].
						'&clinicPatientID='.$visitInfo['clinicPatientID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_visitClose').'">'. TEXT_CLOSE_VISIT . '</a></li>');
					}
				
					if ($firstLinkDefined) {
						echo('<li>');
					} else {
						echo('<li class="firstLink">');
						$firstLinkDefined = true;
					}
					echo ('<a class="a_visitEdit" href="/visitEdit.php?patientVisitID='. $visitInfo['patientVisitID'] .
						'&clinicPatientID='.$visitInfo['clinicPatientID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_visitEdit').'" '.
						'title="'.TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON.'">'.TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON.'</a></li>');
				}
			?>	
			</ul>
	</div>
	<div id="PatientVisitView">
		<div id="PatientVisitDataView">
			<div id="PatientVisitDetails">
				<h2><?= TEXT_VISIT_VISIT_HEADING ?></h2>
				<div class="indent1">
					<p><label class="close"><?= TEXT_FIRST_VISIT_LABEL ?>:</label>
							<?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'YES') ? TEXT_FIRST_VISIT_TEXT : "" ) ?>
							<?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'NO') ? TEXT_RETURN_VISIT_TEXT : "" ) ?>
					</p>
					</div>
					<div <?= ($visitInfo['visitStatus'] == 'Open' ? 'class="currentVisitList"' : '') ?>>
						<p><label class="close"><?= TEXT_VISIT_STATUS_LABEL ?>:</label>&nbsp;<?= ($visitInfo['visitStatus'] == 'Open' ? TEXT_VISIT_STATUS_OPEN : ($visitInfo['visitStatus'] == 'Closed' ? TEXT_VISIT_STATUS_CLOSED : $visitInfo['visitStatus'] )) ?></p>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div class="infoBlock">
					<h2><?= TEXT_VISIT_ARRIVAL_HEADING ?></h2>
					<div class="indent1">
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DATE_TIME_IN_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?>
							&nbsp;&nbsp;&nbsp;<label class="close"><?= TEXT_VISIT_TYPE_LABEL ?>:</label>&nbsp;<?= showVisitTypeString($visitInfo['visitType'], $visitTypes) ?></p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_REFERRED_FROM_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['referredFrom']) ? $visitInfo['referredFrom'] : '<span class="inactive">'.TEXT_REFERRAL_BLANK.'</span>') ?></p>
						</div>
						<div class="dataBlock">							
							<p><label class="close"><?= TEXT_COMPLAINT_PRIMARY_LABEL ?>:</label>&nbsp;<?= (isset($visitInfo['primaryComplaint']) ? $visitInfo['primaryComplaint'] : '<span class="inactive">'.TEXT_COMPLAINT_NOT_SPECIFIED.'</span>' ) ?></p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_COMPLAINT_ADDITIONAL_LABEL ?>:</label>&nbsp;<?= (isset($visitInfo['secondaryComplaint']) ? $visitInfo['secondaryComplaint'] : '<span class="inactive">'.TEXT_COMPLAINT_NOT_SPECIFIED.'</span>' ) ?></p>
						</div>
                        <div class="dataBlock">
                            <p><label class="close"><?= TEXT_PAYMENT_LABEL ?>:</label>
                                <?= (!empty($visitInfo['payment']) ? $visitInfo['payment'] : '<span class="inactive">0</span>' ) ?>&nbsp;<?= TEXT_PAYMENT_CURRENCY ?>
                            </p>
                        </div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_ASSIGNED_LABEL ?>:</label>&nbsp;<?= $visitInfo['staffName'] ?></p>
						</div>
					</div>
				</div>
				<div class="infoBlock">
					<h2><?= TEXT_VISIT_DISCHARGE_HEADING ?></h2>
					<div class="indent1">
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DATE_TIME_OUT_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeOut'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DIAGNOSIS1_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition1']) ?>
							<br>
								<?php
									$displayText = '';
									$displayClass = '';
									if (!empty($visitInfo['diagnosis1'])) {
										$displayText = getIcdDescription ($dbLink, $visitInfo['diagnosis1'], $pageLanguage, SHOWCODE_CODE_BEFORE_TEXT);
										if ($displayText == $visitInfo['diagnosis1']) {
											$displayClass = 'rawcodevalue';
										}
									} else {
										$displayText = TEXT_DIAGNOSIS_BLANK;
										$displayClass = 'inactive';
									}
									echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
								?>
							</p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DIAGNOSIS2_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition2']) ?>
							<br>
								<?php
									$displayText = '';
									$displayClass = '';
									if (!empty($visitInfo['diagnosis2'])) {
										$displayText = getIcdDescription ($dbLink, $visitInfo['diagnosis2'], $pageLanguage, SHOWCODE_CODE_BEFORE_TEXT);
										if ($displayText == $visitInfo['diagnosis2']) {
											$displayClass = 'rawcodevalue';
										}
									} else {
										$displayText = TEXT_DIAGNOSIS_BLANK;
										$displayClass = 'inactive';
									}
									echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
								?>
							</p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DIAGNOSIS3_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition3']) ?>
							<br>
								<?php
									$displayText = 'text';
									$displayClass = '';
									if (!empty($visitInfo['diagnosis3'])) {
										$displayText = getIcdDescription ($dbLink, $visitInfo['diagnosis3'], $pageLanguage, SHOWCODE_CODE_BEFORE_TEXT);
										if ($displayText == $visitInfo['diagnosis3']) {
											$displayClass = 'rawcodevalue';
										}
									} else {
										$displayText = TEXT_DIAGNOSIS_BLANK;
										$displayClass = 'inactive';
									}
									echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
								?>
							</p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_REFERRED_TO_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['referredTo']) ? $visitInfo['referredTo'] : '<span class="inactive">'.TEXT_REFERRAL_BLANK.'</span>') ?></p>
						</div>
					</div>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
</body>
<?php @mysqli_close($dbLink); ?>