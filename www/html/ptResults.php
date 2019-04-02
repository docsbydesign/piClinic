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
*	ptResults
*		Searches for patients that match the form fields passed
*		and shows a list if more than one patient is returned
* 		or redirects to the patient info page if one match is found
*		or returns to the search form to specify a new patient.
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
require_once './api/visit_common.php';
require_once './api/visit_get.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/ptResultsText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
require('uiSessionInfo.php');

// define the option links
$cancelLink = '';
				
// define cancel link for the current context
if (!empty($sessionInfo['parameters']['ata'])) {
	// show error and message to return or add the patient
	$cancelLink = '/ataEntry.php?ata=true'.
		(isset($sessionInfo['parameters']['visitType']) ? '&visitType='.$sessionInfo['parameters']['visitType'] : "").
		(isset($sessionInfo['parameters']['visitStaffUser']) ? '&visitStaffUser='.$sessionInfo['parameters']['visitStaffUser'] : "").
		(isset($sessionInfo['parameters']['visitDateYear']) ? '&visitDateYear='.$sessionInfo['parameters']['visitDateYear'] : "").
		(isset($sessionInfo['parameters']['visitDateMonth']) ? '&visitDateMonth='.$sessionInfo['parameters']['visitDateMonth'] : "").
		(isset($sessionInfo['parameters']['visitDateDay']) ? '&visitDateDay='.$sessionInfo['parameters']['visitDateDay'] : "").
		(isset($sessionInfo['parameters']['visitDateTime']) ? '&visitDateTime='.$sessionInfo['parameters']['visitDateTime'] : "");
	$addNewLink = '/ptAddEdit.php?ata=true'.
		(isset($sessionInfo['parameters']['visitType']) ? '&visitType='.$sessionInfo['parameters']['visitType'] : "").
		(isset($sessionInfo['parameters']['visitStaffUser']) ? '&visitStaffUser='.$sessionInfo['parameters']['visitStaffUser'] : "").
		(isset($sessionInfo['parameters']['visitDateYear']) ? '&visitDateYear='.$sessionInfo['parameters']['visitDateYear'] : "").
		(isset($sessionInfo['parameters']['visitDateMonth']) ? '&visitDateMonth='.$sessionInfo['parameters']['visitDateMonth'] : "").
		(isset($sessionInfo['parameters']['visitDateDay']) ? '&visitDateDay='.$sessionInfo['parameters']['visitDateDay'] : "").
		(isset($sessionInfo['parameters']['visitDateTime']) ? '&visitDateTime='.$sessionInfo['parameters']['visitDateTime'] : "");
	// check for required fields
	if (!isset($sessionInfo['parameters']['visitType']) ||
		!isset($sessionInfo['parameters']['visitStaffUser']) ||
		!isset($sessionInfo['parameters']['visitDateYear']) ||
		!isset($sessionInfo['parameters']['visitDateMonth']) ||
		!isset($sessionInfo['parameters']['visitDateDay'])) {
        // a required field is missing to return to the entry page with error message;
        $redirectUrl = $cancelLink . '&msg=REQUIRED_FIELD_MISSING';
        header("Location: " . $redirectUrl);
        return;
    }
} else {
    $cancelLink = '/clinicDash.php'.createFromLink (FIRST_FROM_LINK_QP, __FILE__, 'Cancel');
	$addNewLink = '/ptAddEdit.php'.
        '?'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_ADD_NEW').
        createFromLink (FROM_LINK_QP, __FILE__, 'AddNewPatient');
}

// open DB
$errorUrl = makeUrlWithQueryParams('/clinicDash.php', ['msg'=>MSG_DB_OPEN_ERROR]);
// this will open the DB or, if it can't open the DB, return to the dashboard with an error
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);
// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$ptArray = [];

// make query string to use for return to search URL
// remove any messages from the calling request
$returnQP = $sessionInfo['parameters'];
if (!empty($returnQP['msg'])) {
    unset($returnQP['msg']);
}

if (!empty($sessionInfo['parameters']['q'])){
	// check to see if this is a visit ID
	$visitQuery = [];
	$visitQuery['patientVisitID'] = $sessionInfo['parameters']['q'];
	$visitInfo = _visit_get($dbLink, $sessionInfo['token'], $visitQuery);
	if ($visitInfo['count'] == 1) {
		// there's only one match found so show the visit that matched to the user
		$redirectUrl = makeUrlWithQueryParams('/visitInfo.php', $visitQuery);
		header("Location: ".$redirectUrl);
		@mysqli_close($dbLink);
		return;
	}
} // else, continue

// if here, there was no visit match so check the patient records
// Get patient records that match
$patientInfo = _patient_get($dbLink, $sessionInfo['token'], $sessionInfo['parameters']);

// at this point, $patientInfo['data'] can have zero or more patient records (up to query limit)

if (($patientInfo['count'] == 0) && (empty($sessionInfo['parameters']['ata']))) {
	// Display error on this page
    $requestData['msg'] = MSG_NOT_FOUND;
} // else continue

// if not a familyID search and only one result, show the result.
if ($patientInfo['count'] == 1) {
	// there's only one match found so just show the patient form
    //  if a familyID query returns only one result, show the list so the user knows
    //  there is only one member in the family
	if ((empty($sessionInfo['parameters']['ata']))  && (empty($sessionInfo['parameters']['familyID']))) {
		// Automatically jump unless it's an ATA entries
        $redirQP = array();
        $redirQP['clinicPatientID'] = $patientInfo['data']['clinicPatientID'];
        $redirectUrl =  makeUrlWithQueryParams('/ptInfo.php', $redirQP);
		header("Location: ".$redirectUrl);
		@mysqli_close($dbLink);
		return;
	} else {
		// there's only one so put it into an array for later
		$ptArray[0] = $patientInfo['data'];
	}
} else {
	// move data array into local variable for display
	$ptArray = $patientInfo['data'];
}
	
function writeTopicMenu ($cancel, $new, $sessionInfo) {
	$topicMenu = '<div id="topicMenuDiv" class="noprint">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
    $topicMenu .= '<li class="firstLink"><a href="'.$cancel.'">'.TEXT_CANCEL_SEARCH.'</a></li>'."\n";
    $topicMenu .= '<li>'.
        '<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">'.TEXT_RETURN_TO_SEARCH_LINK.': '.
        '<input type="hidden" id="WorkflowID" name="'. WORKFLOW_QUERY_PARAM .'" value="'. getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_SEARCH') .'" >'.
        '<input type="hidden" class="btn_search" id="SearchBtnTag" name="'.FROM_LINK.'" value="'.createFromLink (null, __FILE__, `btn_search`).' ?>">'.
        dbFieldTextInput ($sessionInfo['parameters'], "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true).
        '&nbsp;<button type="submit">'.TEXT_SHOW_PATIENT_SUBMIT_BUTTON.'</button> </form></li>';
	$topicMenu .= '<li><a href="'.$new.'">'.
		TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PATIENTS_FOUND_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu ($cancelLink, $addNewLink, $sessionInfo) ?>
	<div id="PatientList">
		<h1 class="pageHeading"><?= TEXT_PATIENT_SEARCH_RESULTS_HEADING ?></h1>
<?php
	if ($patientInfo['count'] == 0) {
		// no records found
		// this should only happen when ata=true because
		// when ata= false, the page redirects
		echo '<p>'.TEXT_PATIENT_SEARCH_NOT_FOUND_ATA.'</p>';
	} else {
		// display table heading
		if (!empty($sessionInfo['parameters']['ata'])) {
			// show ATA message
			echo '<p>'.TEXT_PATIENT_SEARCH_FOUND_ATA.'</p>';
		} else {
			// show search message
			echo '<p>'.TEXT_PATIENT_SEARCH_FOUND_NOT_ATA.'</p>';
		}
		
		echo '<table id="searchResultsTable" class="piClinicList">';
		echo '	<tr>';
		echo '		<th>'. TEXT_FULLNAME_LABEL.'</th>';
		echo '		<th>'. TEXT_BIRTHDATE_LABEL .'</th>';
		echo '		<th>'. TEXT_HOMECITY_LABEL .'</th>';
		echo '	</tr>';
		// data contains an array of structures so loop through them
		foreach ($ptArray as $pt) {		
			echo '<tr>';
			echo '<td class="fullNameCell">';
			$nameLink = '';
			if (!empty($sessionInfo['parameters']['ata'])) {
				// for ATA entries, clicking on a person creates a visit record
				$nameLink = '/addPatientVisit.php'.'?ata=true'.
					'&clinicPatientID='.$pt['clinicPatientID'].
					(isset($sessionInfo['parameters']['visitType']) ? '&visitType='.$sessionInfo['parameters']['visitType'] :'').
					(isset($sessionInfo['parameters']['visitStaffUser']) ? '&staffUsername='.$sessionInfo['parameters']['visitStaffUser'] :'').
					(isset($sessionInfo['parameters']['visitDateYear']) ? '&dateTimeInYear='.$sessionInfo['parameters']['visitDateYear'] :'').
					(isset($sessionInfo['parameters']['visitDateMonth']) ? '&dateTimeInMonth='.$sessionInfo['parameters']['visitDateMonth'] :'').
					(isset($sessionInfo['parameters']['visitDateDay']) ? '&dateTimeInDay='.$sessionInfo['parameters']['visitDateDay'] :'').
					(isset($sessionInfo['parameters']['visitDateTime']) ? '&dateTimeInTime='.$sessionInfo['parameters']['visitDateTime'] :'');
			} else {
				// for generic searches, clicking on a person goes to their pt. record
				$nameLink = '/ptInfo.php?clinicPatientID='.$pt['clinicPatientID'];
			}
			echo '<p><a class="a_ptNameView" href="'.$nameLink.createFromLink (FROM_LINK_QP, __FILE__, 'a_ptNameView').'">';
			echo formatPatientNameLastFirst ($pt).'&nbsp;&nbsp;';
			echo '('.($pt['sex'] == 'M' ? TEXT_SEX_OPTION_M : ($pt['sex'] == 'F' ? TEXT_SEX_OPTION_F : TEXT_SEX_OPTION_X)).')<br>';
			echo '<span class="idInHeading">'.$pt['clinicPatientID'].'</span></a></p>';
			echo '<p class="familyLink">';
			if (empty($sessionInfo['parameters']['ata'])) {
				// create the link only if this is a regular pt. search result
				echo '<a class="a_ptFamilyView" href="/ptResults.php?familyID='.
					$pt['familyID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_ptFamilyView').'">';
			}
			echo $pt['familyID'];
			if (empty($sessionInfo['parameters']['ata'])) {
				echo '</a>';
			}
			echo '</p></td>';
			echo '<td class="birthDateCell">';
			echo date(TEXT_BIRTHDAY_DATE_FORMAT, strtotime($pt['birthDate']));
			echo '</td>';
			echo '<td class="homeCityCell">';
			echo $pt['homeCity'];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table><hr>';
	}
?>		
	</div>
	</div>
</body>
<?php
@mysqli_close($dbLink);
?>
</html>