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
*	Shows the details of specified patient 
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
$requestData = $sessionInfo['parameters'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/ptInfoText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
require('uiSessionInfo.php');

// open DB or redirect to error URL
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($requestData, $errorUrl);
// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

// get patient info (if DB opened successfully)
$patientInfo = array();
// database is good, so read the patient record
// create query string for get operation
if (!empty($requestData['clinicPatientID'])){
    // Get the patient info from the database
    $getQP['clinicPatientID'] = $requestData['clinicPatientID'];
    $patientInfo = _patient_get ($dbLink, $sessionInfo['token'], $getQP);
} else {
    // no query parameter found.
    $retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
    $dbInfo['sqlError'] = @mysqli_error($dbLink);
    $dbInfo['requestData'] = $requestData;
    $dbInfo['language'] = $pageLanguage;
    $retVal['error'] = json_encode ($dbInfo);
    $retVal['httpResponse'] = 400;
    $retVal['httpReason']	= TEXT_MESSAGE_PATIENT_ID_NOT_FOUND;
    // no patients found, return to search page with message
    logUiError($requestData, $retVal, __FILE__, $sessionInfo['username']);
}

// get any earlier visits by this patient to the clinic
$visitList = [];

// get all of this patient's visit records
if (empty($requestData['clinicPatientID'])) {
    $requestData['msg'] = MSG_NOT_FOUND;
} else {
    $getQueryString['clinicPatientID'] = $requestData['clinicPatientID'];
    $getQueryString['sortfield'] = 'DateTimeIn';
    $getQueryString['sortorder'] = 'DESC';
    $visitRecord = _visit_get($dbLink, $sessionInfo['token'], $getQueryString);
    if ($visitRecord['httpResponse'] != 200){
        if (API_DEBUG_MODE) {
            $report['visitRecord'] = $visitRecord;
            $report['query'] = $getQueryString;
            if ($visitRecord['httpResponse'] != 404) {
                // 	This will create an error message below.
                //	Anything but 404 here is normal.
                $dbStatus['visitlookup'] = $report;
            }
        }
    } else {
        if ($visitRecord['count'] == 1) {
            // there's only one so make it an array element
            // so the rest of the code works
            $visitList[0] = $visitRecord['data'];
        } else {
            $visitList = $visitRecord['data'];
        }
    } // else unable to get any visits
}
$indent = '&nbsp;&nbsp;&nbsp;&nbsp;';
// at this point, $patientInfo['data'] should have one patient record
$patientData = null;
if (!empty($patientInfo)) {
    if ($patientInfo['count'] == 1) {
        $patientData = $patientInfo['data'];
    }
}

function writeTopicMenu ($sessionInfo) {
	$topicMenu = '<div id="topicMenuDiv" class="noprint">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink">'.
        '<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">'.
        '<input type="hidden" id="WorkflowID" name="'. WORKFLOW_QUERY_PARAM .'" value="'. getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_SEARCH') .'" >'.
        '<input type="hidden" class="btn_search" id="SearchBtnTag" name="'.FROM_LINK.'" value="'.createFromLink (null, __FILE__, `btn_search`).' ?>">'.
        TEXT_FIND_ANOTHER_LINK.': '.
        dbFieldTextInput ($sessionInfo['parameters'], "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true).
        '&nbsp;<button type="submit">'.TEXT_SHOW_PATIENT_SUBMIT_BUTTON.'</button></form></li>';
	$topicMenu .= '<li><a class="a_patientAddNew" href="/ptAddEdit.php'.createFromLink (FIRST_FROM_LINK_QP, __FILE__, 'a_patientAddNew').
        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_ADD_NEW').'">'.TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PATIENT_INFO_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<?= writeTopicMenu($sessionInfo) ?>
    <div class="<?= (empty($patientData) ? '' : 'hideDiv') ?>">

    </div>
	<div class="nameBlock<?= (empty($patientData) ? ' hideDiv' : '' ) ?>">
		<div class="infoBlock">
		    <h1 class="pageHeading">
                <?= formatPatientNameLastFirst ($patientData) ?>&nbsp;&nbsp;
   			    <img class="barcode" alt="<?= $patientData['clinicPatientID'] ?>" src="code39.php?code=<?= $patientData['clinicPatientID'] ?>&y=34">
            </h1>
		</div>
	</div>
	<br style="clear: left;" />
	<div id="optionMenuDiv" class="noprint<?= (empty($patientData) ? ' hideDiv' : '' ) ?>">
		<ul class="topLinkMenuList">
			<li class="firstLink">
                <?php  $linkParams = array(); $linkParams['clinicPatientID'] = $patientData['clinicPatientID']; ?>
				<a class="a_ptedit" href="<?= makeUrlWithQueryParams('/ptAddEdit.php', $linkParams).
                '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_EDIT').
                createFromLink (FROM_LINK_QP, __FILE__, 'a_ptedit') ?><?= '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_EDIT') ?>" ><?= TEXT_PATIENT_EDIT_PATIENT_BUTTON ?></a>
			</li>
			<?php
				// show the admit patient link only if there is not an open visit.
				if (isset($patientData['clinicPatientID'])) {
					$inTheClinic = False;
					if (!empty($visitList)) {
						foreach ($visitList as $visit) {
							if ($visit['visitStatus'] == 'Open') {
								$inTheClinic = True;
								break; // we just need one.
							}
						}
					}
					if (!$inTheClinic) {
						// only show this link if they do not have a visit open at the moment
                        $linkParams = [
                            'clinicPatientID' => $patientData['clinicPatientID'],
                            'lastVisit' => (empty($visitList) ? '' : $visitList[0]['dateTimeIn'] )];
						echo ('<li><a class="a_visitopen" href="'.
                            makeUrlWithQueryParams('/visitOpen.php', $linkParams).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitopen').
                            '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'VISIT_OPEN').'">'.TEXT_PATIENT_OPEN_NEW_VISIT.'</a></li>');
					}
				}				
			?>
		</ul>
	</div>
<?php
		// check to see if they are currently in the clinic
		if (!empty($visitList)) {
			$headerShown = false;
			foreach ($visitList as $visit) {
				if ($visit['visitStatus'] == 'Open') {
					if (!$headerShown) {
						echo('<div class="currentVisitList" id="currentVisitList">');
						echo ('<h2 id="visitListHeading">'.TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD.'</h2>');
						echo ('<table class="piClinicList"><tr>');
						echo ('<th>'.TEXT_VISIT_LIST_HEAD_TIME.'</th>');
						echo ('<th>'.TEXT_VISIT_LIST_HEAD_DOCTOR.'</th>');
						echo ('<th>'.TEXT_VISIT_LIST_HEAD_COMPLAINT.'</th>');
						echo ('<th>'.TEXT_VISIT_LIST_ACTIONS.'</th>');
						echo ('</tr>');
						$headerShown = true;
					}
					echo ('<tr>');
					echo ('<td class="nowrap" title="'.date(TEXT_VISIT_DATE_ONLY_FORMAT, strtotime($visit['dateTimeIn'])).'">'.date(TEXT_VISIT_DATE_ONLY_FORMAT, strtotime($visit['dateTimeIn'])).'</td>');
					echo ('<td class="nowrap'.(isset($visit['staffName']) ? '' : ' inactive' ).'">'.(isset($visit['staffName']) ? $visit['staffName'] : TEXT_VISIT_LIST_MISSING ).'</td>');
					$complaintText = (isset($visit['primaryComplaint']) ? $visit['primaryComplaint'] : TEXT_VISIT_LIST_MISSING );
					if (strlen($complaintText) > 40) {
						$complaintText = substr($complaintText,0,40).'&nbsp;'.
						    '<a href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].
                            '&clinicPatientID='.$visit['clinicPatientID'].
                            createFromLink (FROM_LINK_QP, __FILE__, 'a_inclinic_moreInfo').
                            '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'CLINIC_VISIT_VIEW').
                            '" '.
                            'class="a_inclinic_moreInfo moreInfo" '.
                            'title="'.TEXT_MORE_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_MORE.'</a>';
					}
					echo ('<td'.(isset($visit['primaryComplaint']) ? '' : ' class="inactive"' ).'>'.$complaintText.'</td>');
					echo ('<td class="nowrap"><a class="a_inclinic_visitview" href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.$visit['clinicPatientID'].
                        createFromLink (FROM_LINK_QP, __FILE__, 'a_inclinic_visitview').'" '.
						'title="'.TEXT_SHOW_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_VIEW.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a class="a_inclinic_visitedit" href="/visitEdit.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.$visit['clinicPatientID'].
                        createFromLink (FROM_LINK_QP, __FILE__, 'a_inclinic_visitedit').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'CLINIC_VISIT_EDIT').'" '.
						'title="'.TEXT_EDIT_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_EDIT.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a class="a_inclinic_visitclose" href="/visitClose.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.$visit['clinicPatientID'].
                        createFromLink (FROM_LINK_QP, __FILE__, 'a_inclinic_visitclose').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'CLINIC_VISIT_CLOSE').'" '.
						'title="'.TEXT_DISCHARGE_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_DISCHARGE.'</a></td>');
					echo ('</tr>');					
				}
			}
			if ($headerShown) {
				echo ('</table>	</div>');
			}
		}
?>
	<div id="PatientView" class="<?= (empty($patientData) ? 'hideDiv' : '' ) ?>">
		<div id="PatientDataView">
			<h2 id="patientInfoHeading"><?= TEXT_PATIENT_DATA_HEAD ?></h2>
			<div class="indent1 infoBlock">
				<div class="infoBlock">
					<div class="dataBlock">
						<p><label><?= TEXT_FULLNAME_LABEL ?>:</label> <?= formatPatientNameLastFirst ($patientData) ?>&nbsp;&nbsp;<?= '('.
						($patientData['sex'] == 'M' ? TEXT_SEX_OPTION_M : ($patientData['sex'] == 'F' ? TEXT_SEX_OPTION_F : TEXT_SEX_OPTION_X)).')' ?></p>
					</div>
					<div class="dataBlock">
                        <?php $linkParams = ['familyID' => $patientData['familyID'], ]; ?>
						<p><label><?= TEXT_FAMILYID_LABEL ?>:</label>
                            <a class="a_viewFamily" href="<?= makeUrlWithQueryParams('/ptResults.php', $linkParams).createFromLink (FROM_LINK_QP, __FILE__, 'a_viewFamily').
                            '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_SUB, 'PT_FAMILY_LIST') ?>">
                            <?= $patientData['familyID'] ?></a></p>
					</div>
					<div class="dataBlock">
						<p><label><?= TEXT_CLINICPATIENTID_LABEL ?>:</label> <?= $patientData['clinicPatientID'] ?></p>
					</div>
					<div class="dataBlock">
						<p><label><?= TEXT_PATIENTNATIONALID_LABEL ?>:</label> <?= $patientData['patientNationalID'] ?></p>
					</div>
					<div class="dataBlock">
						<p><label><?= TEXT_BIRTHDATE_LABEL ?>:</label> <?= date(TEXT_BIRTHDAY_DATE_FORMAT, strtotime($patientData['birthDate'])) ?>&nbsp;(<?= formatAgeFromBirthdate ($patientData['birthDate'], time(), TEXT_YMD_AGE_YEARS, TEXT_YMD_AGE_MONTHS, TEXT_YMD_AGE_DAYS) ?>) &nbsp;&nbsp;
						</p>
					</div>
					<div class="dataBlock">
						<label><?= TEXT_PATIENT_ALLERGY_LIST_HEAD ?>:</label>
						<?php
							if (empty($patientData['knownAllergies'])){
								echo ('<p>'.TEXT_PATIENT_NO_KNOWN_ALLERGIES.'</p>');
							} else {
								$allergies = explode ('|',$patientData['knownAllergies']);
								if ($allergies === false) {
									echo ('<p>'.TEXT_PATIENT_NO_KNOWN_ALLERGIES.'</p>');
								} else {
									echo ('<ul class="allergyList">');
									foreach ($allergies as $allergy) {
										echo ('<li class="allergy">'.$allergy.'</li>');
									}
									echo ('</ul>');
								}
							}
						?>
					</div>
					<div class="dataBlock">
						<label><?= TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD ?>:</label>
						<?php
							if (empty($patientData['currentMedications'])){
								echo ('<p>'.TEXT_PATIENT_NO_CURRENT_MEDS.'</p>');
							} else {
								$allergies = explode ('|',$patientData['currentMedications']);
								if ($allergies === false) {
									echo ('<p>'.TEXT_PATIENT_NO_CURRENT_MEDS.'</p>');
								} else {
									echo ('<ul class="medsList">');
									foreach ($allergies as $allergy) {
										echo ('<li class="meds">'.$allergy.'</li>');
									}
									echo ('</ul>');
								}
							}
						?>
					</div>
					<div class="dataBlock">
						<p><label><?= TEXT_BLOODTYPE_LABEL ?>:</label> <?= $patientData['bloodType'] ?></p>
					</div>
					<div class="dataBlock">
						<p><label><?= (!empty($patientData['organDonor']) ? TEXT_ORGAN_DONOR : '') ?></label></p>
					</div>
					<div class="dataBlock">
						<p><label><?= TEXT_PREFERREDLANGUAGE_LABEL ?>:</label><span<?= (empty($patientData['homeAddress1']) ? ' class="inactive"' : '') ?>>
							<?= (!empty($patientData['preferredLanguage']) ? $patientData['preferredLanguage'] : TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED )?></span></p>
					</div>
				</div>
			</div>
			<div class="indent1 infoBlock">
				<p><label><?= TEXT_HOMEADDRESS_LABEL ?>:</label></p>
				<div class="indent1 infoBlock">
					<p><span<?= (empty($patientData['homeAddress1']) ? ' class="inactive"' : '') ?>>
							<?= (empty($patientData['homeAddress1']) ? TEXT_HOMEADDRESS1_MISSING : $patientData['homeAddress1']) ?></span><br>
						<span<?= (empty($patientData['homeAddress2']) ? ' class="inactive"' : '') ?>>
							<?= (empty($patientData['homeAddress2']) ? TEXT_HOMEADDRESS2_MISSING : $patientData['homeAddress2']) ?></span><br>
						<span<?= (empty($patientData['homeNeighborhood']) ? ' class="inactive"' : '') ?>><?= (empty($patientData['homeNeighborhood']) ? TEXT_HOMENEIGHBORHOOD_MISSING : $patientData['homeNeighborhood']) ?></span><br>
						<span<?= (empty($patientData['homeCity']) ? ' class="inactive"' : '') ?>><?= (empty($patientData['homeCity']) ? TEXT_HOMECITY_MISSING : $patientData['homeCity']) ?></span><br>
						<span<?= (empty($patientData['homeCounty']) ? ' class="inactive noshow"' : '') ?>><?= (empty($patientData['homeCounty']) ? TEXT_HOMECOUNTY_MISSING : $patientData['homeCounty']) ?><br></span>
						<span<?= (empty($patientData['homeState']) ? ' class="inactive"' : '') ?>><?= (empty($patientData['homeState']) ? TEXT_HOMESTATE_MISSING : $patientData['homeState']) ?></span><br>
					</p>
				</div>
				<div class="infoBlock">
					<p><label><?= TEXT_PHONE_LABEL ?>:</label></p>
					<div class="indent1 dataBlock">
						<p><label><?= TEXT_CONTACTPHONE_LABEL ?>:</label>
							<span<?= (empty($patientData['contactPhone']) ? ' class="inactive"' : '') ?>>
							<?= (empty($patientData['contactPhone']) ? TEXT_CONTACTPHONE_MISSING : $patientData['contactPhone']) ?></span></p>
					</div>
					<div class="indent1 dataBlock">
						<p><label><?= TEXT_CONTACTALTPHONE_LABEL ?>:</label>
							<span<?= (empty($patientData['contactAltPhone']) ? ' class="inactive"' : '') ?>>
							<?= (empty($patientData['contactAltPhone']) ? TEXT_CONTACTALTPHONE_MISSING : $patientData['contactAltPhone']) ?></span></p>
					</div>
				</div>
			</div>
		</div>
		<div id="PatientHistoryDiv">
			<div id="patientVisitListDiv">
<?php
				if (empty($visitList)){
					echo ('<p>'.TEXT_NO_PREVIOUS_VISITS.'</p>');
				} else {
					$headerShown = false;
					foreach ($visitList as $visit) {
						if ($visit['visitStatus'] != 'Open') {
							if (!$headerShown) {
								echo ('<h2 id="patientVisitListHeading">'.TEXT_PATIENT_VISIT_LIST_HEAD.'</h2>');
								echo ('<table class="indent1 piClinicList"><tr>');
								echo ('<th>'.TEXT_VISIT_LIST_HEAD_DATE.'</th>');
								echo ('<th>'.TEXT_VISIT_LIST_HEAD_DOCTOR.'</th>');
								echo ('<th>'.TEXT_VISIT_LIST_HEAD_DIAGNOSIS1.'</th>');
								echo ('</tr>');
								$headerShown = true;
							}
							echo ('<tr>');
							echo ('<td class="nowrap"><a class="a_visitSummaryView" href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].
								'&clinicPatientID='.$visit['clinicPatientID'].createFromLink (FROM_LINK_QP, __FILE__, 'a_visitSummaryView').'">'.
								date(TEXT_VISIT_DATE_ONLY_FORMAT, strtotime($visit['dateTimeIn'])).'</a></td>');
							echo ('<td class="nowrap"'.(isset($visit['staffName']) ? '' : ' class="inactive"' ).'>'.(isset($visit['staffName']) ? $visit['staffName'] : TEXT_VISIT_LIST_MISSING ).'</td>');
							echo ('<td>');
							$displayText = '';
							$displayClass = '';
							if (!empty($visit['diagnosis1'])) {
								$displayText = getIcdDescription ($dbLink, $visit['diagnosis1'], $pageLanguage, 0);
								if ($displayText == $visit['diagnosis1']) {
									$displayClass = 'rawcodevalue';
								}
							} else {
								$displayText = TEXT_DIAGNOSIS_BLANK;
								$displayClass = 'inactive';
							}
							echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
							echo ('</td>');
							echo ('</tr>');					
						}
					}
					if ($headerShown) {
					    echo ('</table>');
                    }
				}
?>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
<?php
@mysqli_close($dbLink);
?>