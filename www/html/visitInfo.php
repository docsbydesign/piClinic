<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

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
$ptInfo = array();
if (empty($visitRecord)  || ($visitRecord['httpResponse'] != 200)) {
    if (!empty($visitRecord) ) {
        $dbStatus = $visitRecord;
        if ($dbStatus['httpResponse'] == 404) {
            $dbStatus['httpReason']	= TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND;
        } else {
            $dbStatus['httpReason']	= TEXT_MESSAGE_GENERIC;
        }
    } else {
        $requestData['msg'] = MSG_NOT_FOUND;
    }
	// load fields that should have data so the form doesn't break
    if (!isset($visitInfo['patientVisitID'])) {
        $visitInfo['patientVisitID'] = '';
    }
    if (!isset($visitInfo['dateTimeIn'])) {
        $visitInfo['dateTimeIn'] = '';
    }
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
    $visitInfo['diagnosis1'] = '';
    $visitInfo['diagnosis2'] = '';
    $visitInfo['diagnosis3'] = '';
    $visitInfo['condition1'] = '';
    $visitInfo['condition2'] = '';
    $visitInfo['condition3'] = '';
} else {
	$visitInfo = $visitRecord['data'];
    // need to get the patient's patient record to get the full patient name
    if (!empty($visitInfo['clinicPatientID'])) {
        $ptQueryString = "SELECT * FROM `". DB_VIEW_PATIENT_GET . "` WHERE `clinicPatientID` = '" .
            $visitInfo['clinicPatientID'] . "';";
        $ptRecord = getDbRecords($dbLink, $ptQueryString);
        if ($ptRecord['count'] == 1) {
            $ptInfo = $ptRecord['data'];
        }
    }
}

function writeTopicMenu ($sessionInfo) {
	$topicMenu = '<div id="topicMenuDiv" class="noprint">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
    $topicMenu .= '<li class="firstLink">'.
        '<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">'.TEXT_FIND_ANOTHER_LINK.': '.
        dbFieldTextInput ($sessionInfo['parameters'], "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true).
        '&nbsp;<button type="submit">'.TEXT_SHOW_PATIENT_SUBMIT_BUTTON.'</button>'.
        '<input type="hidden" id="WorkflowID" name="'. WORKFLOW_QUERY_PARAM .'" value="'. getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_SEARCH') .'" >'.
        '<input type="hidden" class="btn_search" id="SearchBtnTag" name="'.FROM_LINK.'" value="'.createFromLink (null, __FILE__, `btn_search`).' ?>">'.
        '</form></li>';
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
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu($sessionInfo) ?>
	<div class="nameBlock<?= (empty($visitRecord) ? ' hideDiv' : '') ?>">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($ptInfo) ?>
				<span class="linkInHeading">&nbsp;&nbsp;<?= '('.$visitInfo['sex'].')' ?></span></h1>
			<p><?= formatDbDate ($visitInfo['birthDate'], TEXT_BIRTHDAY_DATE_FORMAT, TEXT_NOT_SPECIFIED ) ?>&nbsp;<?= formatAgeFromBirthdate ($visitInfo['birthDate'], strtotime($visitInfo['dateTimeIn']), TEXT_VISIT_YEAR_TEXT, TEXT_VISIT_MONTH_TEXT, TEXT_VISIT_DAY_TEXT) ?>&nbsp;&nbsp;&nbsp;
			<span class="linkInHeading"><a class="a_ptInfo" href="/ptInfo.php?clinicPatientID=<?= urlencode($visitInfo['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_ptInfo') ?>" title="<?= TEXT_SHOW_PATIENT_INFO ?>"><?= $visitInfo['clinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= TEXT_VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
			<p><label class="close"><?= TEXT_VISIT_ID_LABEL ?>:</label><span class="linkInHeading"><?= $visitInfo['patientVisitID'] ?></span></p>
		</div>
	</div>
	<div class="clearFloat"></div>
    <div class="infoBlock">
        <img class="barcode printOnly" alt="<?= $visitInfo['patientVisitID'] ?>" src="code39.php?code=<?= $visitInfo['patientVisitID'] ?>&y=44&w=2">
    </div>
    <div class="clearFloat"></div>
	<div id="optionMenuDiv" class="noprint<?= (empty($visitRecord) ? ' hideDiv' : '') ?>">
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
						'&clinicPatientID='.urlencode($visitInfo['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitClose').
                            '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'VISIT_CLOSE').'">'. TEXT_CLOSE_VISIT . '</a></li>');
					}

					if ($firstLinkDefined) {
						echo('<li>');
					} else {
						echo('<li class="firstLink">');
						$firstLinkDefined = true;
					}
					echo ('<a class="a_visitPrint" href="/visitClinicForm0.php?patientVisitID='. $visitInfo['patientVisitID'] .
						'&clinicPatientID='.urlencode($visitInfo['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitPrint').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'VISIT_EDIT').'" '.
						'title="'.TEXT_PATIENT_PRINT_PATIENT_VISIT_BUTTON.'">'.TEXT_PATIENT_PRINT_PATIENT_VISIT_BUTTON.'</a></li>');
                    echo('<li>');
                    echo ('<a class="a_visitEdit" href="/visitEdit.php?patientVisitID='. $visitInfo['patientVisitID'] .
                        '&clinicPatientID='.urlencode($visitInfo['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitEdit').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'VISIT_EDIT').'" '.
                        'title="'.TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON.'">'.TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON.'</a></li>');
				}
			?>
        </ul>
	</div>
	<div id="PatientVisitView" class="<?= (empty($visitRecord) ? 'hideDiv' : '') ?>">
		<div id="PatientVisitDataView">
			<div id="PatientVisitDetails">
                <div class="infoBlock">
                    <h2><?= TEXT_VISIT_VISIT_HEADING ?></h2>
                    <div class="indent1">
                        <p><label class="close"><?= TEXT_FIRST_VISIT_LABEL ?>:</label>
                                <?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'YES') ? TEXT_FIRST_VISIT_TEXT : "" ) ?>
                                <?= ((!empty($visitInfo['firstVisit']) && $visitInfo['firstVisit'] == 'NO') ? TEXT_RETURN_VISIT_TEXT : "" ) ?>
                        </p>
                    </div>
                </div>
                <div class="clearFloat"></div>
                <div <?= ($visitInfo['visitStatus'] == 'Open' ? 'class="currentVisitList fullWidth"' : '') ?>>
                    <p><label class="close"><?= TEXT_VISIT_STATUS_LABEL ?>:</label>&nbsp;<?= ($visitInfo['visitStatus'] == 'Open' ? TEXT_VISIT_STATUS_OPEN : ($visitInfo['visitStatus'] == 'Closed' ? TEXT_VISIT_STATUS_CLOSED : $visitInfo['visitStatus'] )) ?></p>
                </div>
                <div class="infoBlock">
    				<div class="clearFloat"></div>
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
                                <p class="notes"><label class="close"><?= TEXT_COMPLAINT_PRIMARY_LABEL ?>:</label>&nbsp;<?= (isset($visitInfo['primaryComplaint']) ? $visitInfo['primaryComplaint'] : '<span class="inactive">'.TEXT_COMPLAINT_NOT_SPECIFIED.'</span>' ) ?></p>
                            </div>
                            <div class="dataBlock">
                                <p><label class="close"><?= TEXT_PAYMENT_LABEL ?>:</label>
                                    <?= (!empty($visitInfo['payment']) ? $visitInfo['payment'] : '<span class="inactive">0</span>' ) ?>&nbsp;<?= TEXT_PAYMENT_CURRENCY ?>
                                </p>
                            </div>
                            <div class="dataBlock">
                                <p><label class="close"><?= TEXT_ASSIGNED_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['staffName']) ? $visitInfo['staffName'] : '<span class="inactive">'.TEXT_NOT_SPECIFIED.'</span>') ?></p>
                            </div>
                        </div>
                        <h2><?= TEXT_VISIT_PRECLINIC_HEADING ?></h2>
                        <div class="indent1">
                            <table class="piClinicList">
                                <tr>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_HEIGHT_LABEL ?></label></th>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_WEIGHT_LABEL ?></label></th>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_TEMP_LABEL ?></label></th>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_BP_LABEL ?></label></th>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_PULSE_LABEL ?></label></th>
                                    <th class="sixCol"><label><?= TEXT_VISIT_FORM_BS_LABEL ?></label></th>
                                </tr>
                                <tr>
                                    <td class="sixCol"><?= (!empty($visitInfo['height']) ? $visitInfo['height'].'&nbsp;'.$visitInfo['heightUnits'] : '&nbsp;') ?></td>
                                    <td class="sixCol"><?= (!empty($visitInfo['weight']) ? $visitInfo['weight'].'&nbsp;'.$visitInfo['weightUnits'] : '&nbsp;') ?></td>
                                    <td class="sixCol"><?= (!empty($visitInfo['temp']) ? $visitInfo['temp'].'&deg;&nbsp;'.$visitInfo['tempUnits'] : '&nbsp;') ?></td>
                                    <td class="sixCol"><?= (!empty($visitInfo['bpDiastolic']) ? $visitInfo['bpSystolic'] : '&nbsp;').'/'.(!empty($visitInfo['bpDiastolic']) ? $visitInfo['bpDiastolic'] : '&nbsp;') ?></td>
                                    <td class="sixCol"><?= (!empty($visitInfo['pulse']) ? $visitInfo['pulse'] : '&nbsp;') ?></td>
                                    <td class="sixCol"><?= (!empty($visitInfo['glucose']) ? $visitInfo['glucoseUnits'].': '.$visitInfo['glucose'] : '&nbsp;') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="infoBlock">
                    <h2><?= TEXT_COMPLAINT_ADDITIONAL_LABEL ?></h2>
                    <div class="indent1">
                        <p class="notes"><?= (isset($visitInfo['secondaryComplaint']) ? $visitInfo['secondaryComplaint'] : '<span class="inactive">'.TEXT_COMPLAINT_NOT_SPECIFIED.'</span>' ) ?></p>
                    </div>
					<h2><?= TEXT_VISIT_DISCHARGE_HEADING ?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="linkInHeading"><a class="moreInfo" target="helpIndex" href="/helpHome.php?topic=icd" title="<?= TEXT_ICD_LINK_TITLE ?>"><?= TEXT_ICD_LINK_TEXT ?></a></span></h2>
					<div class="indent1">
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DATE_TIME_OUT_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['dateTimeOut']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeOut'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
						</div>
						<div class="dataBlock">
							<p><label class="close"><?= TEXT_DIAGNOSIS1_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition1']) ?><br>
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
							<p><label class="close"><?= TEXT_DIAGNOSIS2_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition2']) ?><br>
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
							<p><label class="close"><?= TEXT_DIAGNOSIS3_LABEL ?>:</label>&nbsp;<?= conditionText($visitInfo['condition3']) ?><br>
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
			<div class="clearFloat"></div>
		</div>
	</div>
    </div>
</body>
<?php @mysqli_close($dbLink); ?>
