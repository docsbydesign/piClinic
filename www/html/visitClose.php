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
* 	Closes single patient visit
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
require_once './api/patient_get.php';
require_once './api/staff_common.php';
require_once './api/staff_get.php';
require_once './api/visit_common.php';
require_once './api/visit_get.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$requestData = $sessionInfo['parameters'];
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/visitCloseText.php');
// functions to translate Enums to localized text
require_once ('./visitUiStrings.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
require('uiSessionInfo.php');
// referrer URL to return to (in most cases)
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
    $referringPageUrl = cleanedRefererUrl(createFromLink (null, __FILE__, 'Cancel'));
} else {
    //default return is the visit info page
    $referringPageQP = array();
    if (isset($requestData['patientVisitID'])) {
        $referringPageQP['patientVisitID'] = $requestData['patientVisitID'];
    }
    if (isset($requestData['clinicPatientID'])) {
        $referringPageQP['clinicPatientID'] = $requestData['clinicPatientID'];
    }
    $referringPageQP[FROM_LINK] = createFromLink (null, __FILE__, 'Cancel');
    $referringPageUrl = makeUrlWithQueryParams('/visitInfo.php', $referringPageQP);
}
$cancelUrl = $referringPageUrl;

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);
// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

// Get the selected visit record
// create query string for get operation
$visitRecord = array();
$getQueryString = "";
$staffResponse = [];
if (!empty($requestData['patientVisitID'])) {
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
	$visitInfo['patientFamilyID'] = '';
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
// at this point, $visitInfo should have one patient visit record ready to edit
// set default values for closing
$visitInfo['visitStatus'] = 'Closed';
$visitInfo['dateTimeOut'] =  date_format(date_create('now'), 'Y-m-d H:i:s'); // now;

function writeTopicMenu ($cancelLink) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a class="a_cancel" href="'. $cancelLink. '">'.TEXT_CANCEL_VISIT_EDIT.'</a></li>';
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

function writeOptionsMenu ($visitInfo) {
	$optionsMenu = '';
	if (isset($visitInfo['clinicPatientID'])) {
		$optionsMenu .= '<div id="optionMenuDiv">'."\n";
		$optionsMenu .= '<ul class="topLinkMenuList">';
		$optionsMenu .= '<li class="firstLink"><a class="a_editall" href="/visitEdit.php?patientVisitID='.$visitInfo['patientVisitID'].
			'&clinicPatientID='. urlencode($visitInfo['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_editall') .'">'.TEXT_VISIT_EDIT_ALL_FIELDS.'</a></li>';
		$optionsMenu .= '</ul></div>';	}
	return $optionsMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_CLOSE_VISIT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
	<datalist id="diagData"></datalist>
	<div class="pageBody">
	<?= writeTopicMenu($cancelUrl) ?>
	<div class="nameBlock<?= (empty($visitRecord) ? ' hideDiv' : '') ?>">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($ptInfo) ?>
				<span class="linkInHeading">&nbsp;&nbsp;<?= '('.$visitInfo['sex'].')' ?></span></h1>
			<p><?= formatDbDate ($visitInfo['birthDate'], TEXT_BIRTHDAY_DATE_FORMAT, TEXT_NOT_SPECIFIED ) ?>&nbsp;<?= formatAgeFromBirthdate ($visitInfo['birthDate'], strtotime($visitInfo['dateTimeIn']), TEXT_VISIT_YEAR_TEXT, TEXT_VISIT_MONTH_TEXT, TEXT_VISIT_DAY_TEXT) ?>&nbsp;&nbsp;&nbsp;
			<span class="linkInHeading"><a class="a_patientview" href="/ptInfo.php?clinicPatientID=<?= urlencode($visitInfo['clinicPatientID']) ?><?= createFromLink (FROM_LINK_QP, __FILE__, 'a_patientview') ?>" title="<?= TEXT_SHOW_PATIENT_INFO ?>"><?= $visitInfo['clinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= TEXT_VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
			<p><label class="close"><?= TEXT_VISIT_ID_LABEL ?>:</label><span class="linkInHeading"><?= $visitInfo['patientVisitID'] ?></span></p>
		</div>
	</div>
	<div class="clearFloat"></div>
	<div id="PatientVisitView" class="<?= (empty($visitRecord) ? 'hideDiv' : '') ?>">
		<?= writeOptionsMenu($visitInfo) ?>
		<div id="PatientVisitDataView">
			<div id="PatientVisitDetails">
				<form enctype="multipart/form-data" action="/uihelp/updatePatientVisit.php" method="post">
					<h2><?= TEXT_VISIT_VISIT_HEADING ?></h2>
					<div class="indent1">
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
								<p><label class="close"><?= TEXT_DATE_TIME_IN_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?>
								&nbsp;&nbsp;&nbsp;<label class="close"><?= TEXT_VISIT_TYPE_LABEL ?>:</label>&nbsp;<?= showVisitTypeString($visitInfo['visitType'], $visitTypes) ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= TEXT_REFERRED_FROM_LABEL ?>:</label>&nbsp;<?= (!empty($visitInfo['referredFrom']) ? $visitInfo['referredFrom'] : '<span class="inactive">'.TEXT_REFERRAL_BLANK.'</span>') ?></p>
							</div>
							<div class="dataBlock">
								<p><label class="close"><?= TEXT_COMPLAINT_PRIMARY_LABEL ?>:</label>&nbsp;<?= $visitInfo['primaryComplaint'] ?></p>
							</div>
                            <div class="dataBlock bottomSpace">
                                <p><label class="close"><?= TEXT_PAYMENT_LABEL ?>:</label>
                                    <input type="number" name="payment" id="PaymentEdit" class="paymentEdit" min="0" max="9999999999.99" step="1" placeholder="<?= TEXT_PAYMENT_PLACEHOLDER ?>" value="<?=  (!empty($visitInfo['payment']) ? $visitInfo['payment'] : "") ?>" />
                                    <?= TEXT_PAYMENT_CURRENCY ?>
                                </p>
                            </div>
                            <div class="dataBlock">
                                <p><label class="close"><?= TEXT_ASSIGNED_LABEL ?>:</label> &nbsp;
                                    <select id="StaffUsernameField" name="staffUsername" class="requiredField">
                                        <option value="" <?= (empty($visitInfo['staffUsername']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_STAFF_OPTION_VISIT ?></option>
                                        <?php
                                        if (!empty($staffResponse)) {
                                            // if there are some staff entries to load, load 'em
                                            if ($staffResponse['count'] > 1) {
                                                foreach ($staffResponse['data'] as $staffMember){
                                                    if ($staffMember['medicalStaff']) {
                                                        echo ('<option value="'.$staffMember['username'].'" '.
                                                            ((!empty($visitInfo['staffUsername']) && $staffMember['username'] == $visitInfo['staffUsername']) ? 'selected' : '' ).
                                                            '>'.$staffMember['lastName'].','. $staffMember['firstName'] .'</option>');
                                                    }
                                                }
                                            } else if ($staffResponse['count'] == 1) {
                                                $staffMember = $staffResponse['data'];
                                                if ($staffMember['medicalStaff']) {
                                                    echo ('<option value="'.$staffMember['username'].'" '.
                                                        ((!empty($visitInfo['staffUsername']) && $staffMember['username'] == $visitInfo['staffUsername']) ? 'selected' : '' ).
                                                        '>'.$staffMember['lastName'].', '. $staffMember['firstName'] .'</option>');
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                            </div>
						</div>
                        <h2><?= TEXT_VISIT_PRECLINIC_HEADING ?></h2>
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
                    <div class="infoBlock">
                        <h2><?= TEXT_COMPLAINT_ADDITIONAL_LABEL ?></h2>
                        <div class="dataBlock">
                            <p><?= (!empty($visitInfo['secondaryComplaint']) ? $visitInfo['secondaryComplaint'] : '<span class="inactive">'.TEXT_NOT_SPECIFIED.'</span>' ) ?></p>
                        </div>
                    </div>
                    <div class="infoBlock">
						<h2><?= TEXT_VISIT_DISCHARGE_HEADING ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="linkInHeading"><a class="moreInfo" target="helpIndex" href="/helpHome.php?topic=icd" title="<?= TEXT_ICD_LINK_TITLE ?>"><?= TEXT_ICD_LINK_TEXT ?></a></span></h2>
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
							<div class="dataBlock">
                                <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 1, TEXT_DIAGNOSIS1_LABEL, TEXT_DIAGNOSIS1_PLACEHOLDER ) ?>
                                <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 2, TEXT_DIAGNOSIS2_LABEL, TEXT_DIAGNOSIS2_PLACEHOLDER ) ?>
                                <?= writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, 3, TEXT_DIAGNOSIS3_LABEL, TEXT_DIAGNOSIS3_PLACEHOLDER ) ?>
                            </div>
							<div class="dataBlock">
								<p><label class="close"><?= TEXT_REFERRED_TO_LABEL ?>:</label><?= dbFieldTextInput ($visitInfo, "referredTo", TEXT_REFERRAL_PLACEHOLDER, false) ?></p>
							</div>
						</div>
					</div>
					<div class="clearFloat"></div>
					<input type="hidden" id="PatientVisitIDField" name="patientVisitID" value="<?= $visitInfo['patientVisitID'] ?>" >
					<input type="hidden" id="returnUrlField" name="returnUrl" value="<?= $referringPageUrl ?>">
                    <input type="hidden" class="btn_submit" id="SubmitBtnTag" name="<?= FROM_LINK ?>" value="<?= createFromLink (null, __FILE__, 'btn_submit') ?>">
					<p><button type="submit"><?= TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON ?></button></p>
				</form>
			</div>
		</div>
	</div>
	<?= icdLookupJavaScript() ?>
    </div>
</body>
<?php @mysqli_close($dbLink); ?>
