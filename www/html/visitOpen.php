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
* 	Creates a new patient visit
*
*		Required query parameters:
*			clinicPatientID
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
require_once ('./uitext/visitOpenText.php');
// functions to translate Enums to localized text
require_once ('./visitUiStrings.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
require('uiSessionInfo.php');
// referrer URL to return to (in most cases)
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
    $referringPageUrl = cleanedRefererUrl(createFromLink (null, __FILE__, 'a_cancel'));
} else {
    //default return is the visit info page
    $referringPageQP = array();
    if (isset($requestData['patientVisitID'])) {
        $referringPageQP['patientVisitID'] = $requestData['patientVisitID'];
    }
    if (isset($requestData['clinicPatientID'])) {
        $referringPageQP['clinicPatientID'] = $requestData['clinicPatientID'];
    }
    $referringPageQP[FROM_LINK] = createFromLink (null, __FILE__, 'a_cancel');
    $referringPageUrl = makeUrlWithQueryParams('/visitInfo.php', $referringPageQP);
}
$cancelUrl = $referringPageUrl.createFromLink (FROM_LINK_QP, __FILE__, 'a_cancel');

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// Get the selected visit record
// create query string for get operation
$visitRecord = array();
$staffResponse = array();
$getQueryString = '';
$patientRecord = null;
if (!empty($requestData['clinicPatientID'])) {
	$getQueryString = "SELECT * FROM `".
		DB_TABLE_PATIENT. "` WHERE `clinicPatientID` = '".
		$requestData['clinicPatientID']."';";
	$patientRecord = getDbRecords($dbLink, $getQueryString);
	// get the list of staff members
	// get the list of users, sorted by username
	$staffQueryString['username'] = '%';
	$staffQueryString['sort'] = 'lastName';
	$staffResponse = _staff_get ($dbLink, $sessionInfo['token'], $staffQueryString);
} else {
	$retVal = array();
	// no query parameter found.
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';		
	if (API_DEBUG_MODE) {
		$dbInfo['sqlError'] = @mysqli_error($dbLink);
		$dbInfo['getQueryString'] = $getQueryString;
		$dbInfo['requestData'] = $requestData;
		$dbInfo['language'] = $pageLanguage;
		$dbStatus['error'] = $dbInfo;
	}
	$dbStatus['httpResponse'] = 400;
	$dbStatus['httpReason']	= TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED;
}

// if the patient record was not returned, exit in error
// TODO: THIS SHOULD RETURN TO PREVIOUS PAGE!!!
$patientInfo = array();
if ($patientRecord['httpResponse'] != 200) {
	$dbStatus['httpReason']	= TEXT_MESSAGE_NO_PATIENT_FOUND;
	return;
} else {
	$patientInfo = $patientRecord['data'];
}

// define default form values
$visitInfo = array();
// `visitID`  created in POST
$visitInfo['staffUsername'] = (isset($requestData['staffUsername']) ? $requestData['staffUsername'] : NULL); 
$visitInfo['visitType'] = (isset($requestData['visitType']) ? $requestData['visitType'] : TEXT_VISIT_DEFAULT); // can be modified in form
$visitInfo['visitStatus'] = (isset($requestData['visitStatus']) ? $requestData['visitStatus'] : 'Open');	// not modified in form
$visitInfo['primaryComplaint'] = (isset($requestData['primaryComplaint']) ? $requestData['primaryComplaint'] : NULL); // assigned in form
$visitInfo['secondaryComplaint'] = (isset($requestData['secondaryComplaint']) ? $requestData['secondaryComplaint'] : NULL); // assigned in form
$visitInfo['dateTimeIn'] = date_format(date_create('now'), 'Y-m-d H:i:s'); // now
$visitInfo['dateTimeOut'] = (isset($requestData['dateTimeOut']) ? $requestData['dateTimeOut'] : NULL); // assigned on close
$visitInfo['payment'] = (isset($requestData['payment']) ? $requestData['payment'] : '0.00');
$visitInfo['firstVisit'] = (empty($requestData['firstVisit']) ? "YES" : "NO" );
// `patientID` created in POST
$visitInfo['clinicPatientID'] = $patientInfo['clinicPatientID']; // copied from patient info
// `PatientVisitID` created in POST
// `PatientVisitID`  created in POST
// `PatientNameLast`  created in POST
// `PatientNameFirst`  created in POST
// `PatientSex` created in POST
// `PatientBirthDate`  created in POST
// `PatientHomeAddress1`  created in POST
// `PatientHomeAddress2`  created in POST
// `PatientHomeNeighborhood` created in POST
// `PatientHomeCity` varchar(255)  created in POST
// `PatientHomeCounty` varchar(255)  created in POST
// `PatientHomeState` varchar(255)  created in POST
// `Diagnosis1` Default value used in create 
// `Condition1` Default value used in create 
// `Diagnosis2` Default value used in create 
// `Condition2` Default value used in create 
// `Diagnosis3` Default value used in create 
// `Condition3` Default value used in create 
// `ReferredTo` Default value used in create 
$visitInfo['referredFrom'] = (isset($requestData['referredFrom']) ? $requestData['referredFrom'] : NULL); 
// at this point, $visitInfo should have one patient visit record ready to edit
function writeTopicMenu ($cancel) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a class="a_cancel" href="'.$cancel.'">'.TEXT_CANCEL_NEW_VISIT.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_NEW_VISIT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
    <?= piClinicAppMenu(null,  $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu($cancelUrl) ?>
	<div class="nameBlock">
		<div class="infoBlock">
			<h1 class="pageHeading noBottomPad noBottomMargin"><?= formatPatientNameLastFirst ($patientInfo) ?>
				<span class="idInHeading">&nbsp;&nbsp;<?= '('.$patientInfo['sex'].')' ?></span></h1>
			<p><?= date(TEXT_BIRTHDAY_DATE_FORMAT, strtotime($patientInfo['birthDate'])) ?>&nbsp;(<?php echo date_diff(date_create($patientInfo['birthDate']), date_create('now'))->y; ?>)&nbsp;&nbsp;&nbsp;
			<span class="a_ptInfo idInHeading"><a href="/ptInfo.php?clinicPatientID=<?= $patientInfo['clinicPatientID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_ptInfo') ?>" title="<?= TEXT_SHOW_PATIENT_INFO ?>"><?= $visitInfo['clinicPatientID'] ?></a></span></p>
		</div>
		<div class="infoBlock">
			<p><label class="close"><?= TEXT_VISIT_DATE_LABEL ?>:</label><?= (!empty($visitInfo['dateTimeIn']) ? date(TEXT_VISIT_DATE_FORMAT, strtotime($visitInfo['dateTimeIn'])) : '<span class="inactive">'.TEXT_DATE_BLANK.'</span>') ?></p>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div id="PatientVisitView">
        <div class="dataBlock"></div>
        <div id="PatientVisitDetails">
            <form enctype="application/x-www-form-urlencoded" action="/uihelp/addPatientVisit.php" method="post">
                <input type="hidden" id="ClinicPatientIDField" name="clinicPatientID" value="<?= $patientInfo['clinicPatientID'] ?>">
                <div id="optionMenuDiv"></div>
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
                        <div class="dataBlock bottomSpace">
                            <p><label class="close"><?= TEXT_VISIT_TYPE_LABEL ?>:</label> &nbsp;
                                    <select id="visitTypeField" name="visitType" required class="requiredField">
                                        <option value="" <?= (empty($requestData['visitType']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_VISIT_OPTION ?></option>
                                        <?php
                                            foreach ($visitTypes as $visitType){
                                                if (showVisitType($visitType, VISIT_TYPE_EDIT)) {
                                                    echo('<option value="' . $visitType[0] . '" ' .
                                                        (isset($requestData['visitType']) ? ((!empty($visitType[0]) && ($visitType[0] == $requestData['visitType'])) ? 'selected="selected"' : '') : '') .
                                                        '>' . $visitType[1] . '</option>');
                                                }
                                            }
                                        ?>
                                    </select>
                            </p>
                        </div>
                        <div class="dataBlock bottomSpace">
                            <p><label class="close"><?= TEXT_REFERRED_FROM_LABEL ?>:</label><?= dbFieldTextInput ($visitInfo, "referredFrom", TEXT_REFERRAL_PLACEHOLDER, false) ?></p>
                        </div>
                        <div class="dataBlock">
                            <p><label><?= TEXT_COMPLAINT_PRIMARY_LABEL ?>:</label><br>
                            <textarea name="primaryComplaint" id="ComplaintPrimaryEdit" class="complaintEdit" placeholder="<?= TEXT_COMPLAINT_PRIMARY_PLACEHOLDER ?>" maxlength="2048"><?=  $visitInfo['primaryComplaint'] ?></textarea>
                            </p>
                        </div>
                        <div class="dataBlock bottomSpace">
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
                                    <option value="" <?= (empty($visitInfo['staffUsername']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_STAFF_OPTION ?></option>
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
                <div style="clear: both;"></div>
                <input type="hidden" id="SubmitBtnTag" name="<?= FROM_LINK ?>" value="<?= createFromLink (null, __FILE__, 'btn_submit') ?>">
                <p><button class="btn_submit" type="submit"><?= TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON ?></button></p>
            </form>
        </div>
        <hr>
	</div>
</body>
<?php @mysqli_close($dbLink); ?>
