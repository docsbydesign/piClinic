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
*
*	UI to add new and edit existing patient records
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
require_once './api/patient_common.php';
require_once './api/patient_get.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/ptAddEditText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('uiSessionInfo.php');

// get referrer URL to return to in error or if cancelled
$referringPageUrl = NULL;
if (isset($_SERVER['HTTP_REFERER'])) {
	// this link should have the language, if it has been specified
	$referringPageUrl = $_SERVER['HTTP_REFERER'];
} else {
	//default: return is the home page
	$referringPageUrl = '/clinicDash.php';
}

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// if here, the DB is open 

// get patient info if possible
//	if patient record found, enter "edit" mode
//    else, enter "new" patient mode.
$patientData = '';
$pageMode = '';
$patientInfo = '';
if (!empty($requestData['mode'])) {
	// use mode from query parameters, if specified
	if ($requestData['mode'] == 'add') {
		$pageMode = 'add';
	} else if ($requestData['mode'] == 'edit') {
		$pageMode = 'edit';
		// clear ata value if present. it's only allowed on add
		if (!empty($requestData['ata'])) {
			unset($requestData['ata']);
		}
	}
} else if (!empty($requestData['clinicPatientID'])){
	// if the page mode is not specified in the query parameters
	//   but the Username is, look up the username in the DB
	// Get the patient info from the database
	$getQP['clinicPatientID'] = $requestData['clinicPatientID'];
	$patientInfo = _patient_get ($dbLink, $sessionInfo['token'], $getQP);
	// if the patient record was not returned, exit in error
	if ($patientInfo['httpResponse'] == 200) {
		$patientData = $patientInfo['data'];
		$pageMode = 'edit';
	} else {
		// no patients found
		$patientData = '';
		$pageMode = 'add';
	}
} else {
	// neither a mode nor a Patient ID were specified,
	//  so it's probably a new patient so mode=add
	$pageMode = 'add';
	$patientData = '';
}
$queryParamFields = null;
if (empty($patientData)){
	// initialize $patientData from query parameters
	$patientData = array();
	$queryParamFields = array(
		'clinicPatientID'
		,'patientNationalID'
		,'familyID'
		,'lastName'
		,'lastName2'
		,'firstName'
		,'middleInitial'
		,'sex'
		,'bloodType'
		,'orgonDonor'
		,'preferredLanguage'
		,'homeAddress1'
		,'homeAddress2'
		,'homeNeighborhood'
		,'homeCity'
		,'homeCounty'
		,'homeState'
		,'contactPhone'
		,'contactAltPhone'
		,'knownAllergies'
		,'currentMedications'
	);
	foreach ($queryParamFields as $fieldName) {
		if (isset($requestData[$fieldName])) {
			$patientData[$fieldName] = $requestData[$fieldName];
		}
	}
	// recreate the birthdate if the parameters are present
	if (!empty($requestData['birthDateMonth']) &&
		!empty($requestData['birthDateDay']) &&
		!empty($requestData['birthDateYear'])) {
		$tempDateString = $requestData['birthDateYear'].'-'.
			$requestData['birthDateMonth'].'-'.
			$requestData['birthDateDay'].' 00:00:00';
		$tempDateTime = date_create_from_format('Y-m-d H:i:s', $tempDateString );
		$patientData['birthDate'] = date_format ($tempDateTime, 'Y-m-d H:i:s');	
	}
}

function writeTopicMenu ($cancelLink, $ata=false) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a href="'.$cancelLink.'">'.TEXT_PATIENT_CANCEL_ADD.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag($pageMode == 'add' ? TEXT_PATIENT_NEW_PAGE_TITLE : TEXT_PATIENT_EDIT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $sessionInfo['pageLanguage']) ?>
	<div class="pageBody">
	<?= writeTopicMenu($referringPageUrl) ?>
	<div id="patientDataDiv">
		<h2><?= ($pageMode == 'add' ? TEXT_NEW_PATIENT_HEADING  : TEXT_EDIT_PATIENT_HEADING  ) ?></h2>
		<form enctype="application/x-www-form-urlencoded" action="/uihelp/addPatient.php" method="post">
			<p>
				<label class="close"><?= TEXT_PATIENT_ID_LABEL ?>:</label>&nbsp;
					<span style="display:<?= ($pageMode == 'edit' ?  'inline' : 'none' ) ?>">
					<?= ($pageMode == 'edit' ? $patientData['clinicPatientID'] : '') ?></span>
				<input type="<?= ($pageMode == 'add' ? 'text' : 'hidden' ) ?>" id="clinicPatientIDfield" name="clinicPatientID" 
					value="<?php if (!empty($requestData['clinicPatientID'])) {echo $requestData['clinicPatientID'];} ?>" 
					class="requiredField" <?= ($pageMode == 'add' ? 'placeholder="'.TEXT_PATIENT_ID_PLACEHOLDER.'"' :'') ?>>&nbsp;&nbsp;
				<label class="close"><?= TEXT_FAMILYID_LABEL ?>:</label>&nbsp;
					<?= dbFieldTextInput ($patientData, 'familyID', TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER, true) ?>

				<label class="close"><?= TEXT_PATIENTNATIONALID_LABEL ?>:</label>&nbsp;
					<?= dbFieldTextInput ($patientData, 'patientNationalID', TEXT_PATIENTNATIONALID_PLACEHOLDER, true) ?>
			</p>
			<p><label><?= TEXT_PATIENT_NAME_LABEL ?>:</label><br>
				<?= dbFieldTextInput ($patientData, 'lastName', TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER, true) ?>
				<?= dbFieldTextInput ($patientData, 'lastName2', TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER, false) ?>,&nbsp;&nbsp;
				<?= dbFieldTextInput ($patientData, 'firstName', TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER, true) ?>&nbsp;
				<?= dbFieldTextInput ($patientData, 'middleInitial', TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER, false) ?>
			</p>
			<h2><?= TEXT_PATIENT_PERSONAL_LABEL ?></h2>
			<p><label class="close"><?= TEXT_SEX_INPUT_LABEL ?></label>:&nbsp;
					<select id="newsex" name="sex" class="requiredField">
						<option value="" <?= (empty($patientData['sex']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
						<option value="F" <?= ((!empty($patientData['sex']) && $patientData['sex'] == 'F') ? 'selected' : '' ) ?>><?= TEXT_SEX_OPTION_F ?></option>
						<option value="M" <?= ((!empty($patientData['sex']) && $patientData['sex'] == 'M') ? 'selected' : '' ) ?>><?= TEXT_SEX_OPTION_M ?></option>
						<option value="X" <?= ((!empty($patientData['sex']) && $patientData['sex'] == 'X') ? 'selected' : '' ) ?>><?= TEXT_SEX_OPTION_X ?></option>
					</select>&nbsp;&nbsp;
				<label class="close"><?= TEXT_BIRTHDATE_INPUT_LABEL.' '.TEXT_BIRTHDATE_FORMAT_LABEL ?>:</label>&nbsp;
				<?= outputDateInputFields (TEXT_BIRTHDATE_FORMAT, 'birthDate',
					(!empty($patientData['birthDate']) ? date(TEXT_BIRTHDAY_MONTH_FORMAT, strtotime($patientData['birthDate'])) : ''),
					(!empty($patientData['birthDate']) ? date(TEXT_BIRTHDAY_DAY_FORMAT, strtotime($patientData['birthDate'])) : ''),
					(!empty($patientData['birthDate']) ? date(TEXT_BIRTHDAY_YEAR_FORMAT, strtotime($patientData['birthDate'])) : '' )
				) ?>
			</p>
			<p><label class="close"><?= TEXT_PATIENT_NEW_BLOODTYPE_LABEL ?>:</label>&nbsp;
				<select id="newbloodType" name="bloodType">
					<option value="" <?= (empty($patientData['bloodType']) ? 'selected' : '' ) ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
					<option value="O+" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'O+') ? 'selected' : '' ) ?>>O+</option>
					<option value="O-" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'O-') ? 'selected' : '' ) ?>>O-</option>
					<option value="A+" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'A+') ? 'selected' : '' ) ?>>A+</option>
					<option value="A-" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'A-') ? 'selected' : '' ) ?>>A-</option>
					<option value="B+" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'B+') ? 'selected' : '' ) ?>>B+</option>
					<option value="B-" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'B-') ? 'selected' : '' ) ?>>B-</option>
					<option value="AB+" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'AB+') ? 'selected' : '' ) ?>>AB+</option>
					<option value="AB-" <?= ((!empty($patientData['bloodType']) && $patientData['bloodType'] == 'AB-') ? 'selected' : '' ) ?>>AB-</option>
				</select>&nbsp;&nbsp;
				<label class="close"><?= TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL ?>:</label>&nbsp;
					<select id="neworgonDonor" name="orgonDonor">
						<?php
							// TODO: this is too complicated and should be fixed in the DB
							$donorSelected = -1;
							if (isset ($patientData['orgonDonor']) && ($patientData['orgonDonor'] == '1')) { $donorSelected = 1; }
							if (isset ($patientData['orgonDonor']) && ($patientData['orgonDonor'] == '0')) { $donorSelected = 0; }
							// else it's not a 1 or a 0 so pick blank
						?>
						<option value="" <?= ($donorSelected == -1  ? 'selected' : '') ?>><?= TEXT_BLANK_OPTION_SELECT ?></option>
						<option value="1" <?= ($donorSelected == 1  ? 'selected' : '') ?>><?= TEXT_YES_OPTION ?></option>
						<option value="0" <?= ($donorSelected == 0  ? 'selected' : '') ?>><?= TEXT_NO_OPTION ?></option>
					</select>&nbsp;&nbsp;
				<label class="close"><?= TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL ?>:</label>&nbsp;
					<input type="text" id="newpreferredLanguage" name="preferredLanguage" 
						value="<?php if (!empty($patientData['preferredLanguage'])) {echo $patientData['preferredLanguage'];} ?>" placeholder="<?= TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER ?>" maxlength="255">
			</p>
			<p>
				<label><?= TEXT_PATIENT_KNOWN_ALLERGIES_LABEL ?>:</label><br>
				<?php 
					$allergyText = '';
					$allergyItems = 0;
					if (!empty($patientData['knownAllergies'])) {
						$allergyItems = substr_count ($patientData['knownAllergies'],'|') + 1;
						$allergyText = str_replace('|',"\n",$patientData['knownAllergies'] );
					}
					// by default show at least 4 rows
					$allergyEditHeight = (string)(($allergyItems < 4 ? 4 : $allergyItems) * 15).'pt';
				?>
				<textarea name="knownAllergies" id="knownAllergiesEdit" class="allergyEdit" style="height: <?= $allergyEditHeight ?>;" placeholder="<?= TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER ?>"><?=  $allergyText ?></textarea>
			</p>
			<p>
				<label><?= TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL ?>:</label><br>
				<?php 
					$currentMedText = '';
					$currentMedItems = 0;
					if (!empty($patientData['currentMedications'])) {
						$currentMedItems = substr_count ($patientData['currentMedications'],'|') + 1;
						$currentMedText = str_replace('|',"\n",$patientData['currentMedications'] );
					}
					// by default show at least 4 rows
					$currentMedEditHeight = (string)(($currentMedItems < 4 ? 4 : $currentMedItems) * 15).'pt';
				?>
				<textarea name="currentMedications" id="currentMedicationsEdit" class="currentMedicationsEdit" style="height: <?= $currentMedEditHeight ?>;" placeholder="<?= TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER ?>"><?=  $currentMedText ?></textarea>
			</p>
			<p><label><?= TEXT_PATIENT_NEW_ADDRESS_LABEL ?>:</label><br>
				<?= dbFieldTextInput ($patientData, 'homeAddress1', TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER, false, false, 'text', 'piClinicEdit wide') ?>
			</p>
			<p>
				<?= dbFieldTextInput ($patientData, 'homeAddress2', TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER, false, false, 'text', 'piClinicEdit wide') ?>
			</p>
			<p>
				<?= dbFieldTextInput ($patientData, 'homeNeighborhood', TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER, false) ?>
				<?= dbFieldTextInput ($patientData, 'homeCity', TEXT_PATIENT_NEW_CITY_PLACEHOLDER, true) ?>
				<?= dbFieldTextInput ($patientData, 'homeCounty', TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER, false, false, 'text', 'piClinicEdit noshow') ?>
				<?= dbFieldTextInput ($patientData, 'homeState', TEXT_PATIENT_NEW_STATE_PLACEHOLDER, true) ?>
			</p>
			<p><label><?= TEXT_PATIENT_NEW_CONTACT_LABEL ?>:</label><br>
				<?= dbFieldTextInput ($patientData, 'contactPhone', TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER, false) ?></p>
				<p><?= dbFieldTextInput ($patientData, 'contactAltPhone', TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER, false) ?></p>
			<input type="hidden" id="modeField" name="mode" value="<?= $pageMode ?>">
			<input type="hidden" id="methodField" name="_method" value="<?= ($pageMode == 'add' ? 'POST' : 'PATCH'  ) ?>">
			<?php
				if (!empty($requestData['ata'])) {
					// add the ata values to pass on
					echo (!empty($requestData['visitType']) ? '<input type="hidden" id="visitTypeField" name="visitType" value="'.$requestData['visitType'].'">' : '');
					echo (!empty($requestData['visitStaffUser']) ? '<input type="hidden" id="visitStaffUserField" name="visitStaffUser" value="'.$requestData['visitStaffUser'].'">' : '');
					echo (!empty($requestData['visitDateYear']) ? '<input type="hidden" id="visitDateYearField" name="visitDateYear" value="'.$requestData['visitDateYear'].'">' : '');
					echo (!empty($requestData['visitDateMonth']) ? '<input type="hidden" id="visitDateMonthField" name="visitDateMonth" value="'.$requestData['visitDateMonth'].'">' : '');
					echo (!empty($requestData['visitDateDay']) ? '<input type="hidden" id="visitDateDayField" name="visitDateDay" value="'.$requestData['visitDateDay'].'">' : '');
					echo (!empty($requestData['visitDateTime']) ? '<input type="hidden" id="visitDateTimeField" name="visitDateTime" value="'.$requestData['visitDateTime'].'">' : '');
					echo ('<input type="hidden" id="ataField" name="ata" value="true">');
				}			
			?>
			<?= (!empty($requestData['lang']) ? '<input type="hidden" id="langField1" name="lang" value="'.$pageLanguage.'" >': '') ?>
			<p><button type="submit"><?= ($pageMode == 'add' ? TEXT_PATIENT_NEW_SUBMIT_BUTTON  : TEXT_PATIENT_EDIT_SUBMIT_BUTTON  ) ?></button></p>
		</form>
		<hr>
	</div>
	</div>
</body>
<?php
@mysqli_close($dbLink);
?>