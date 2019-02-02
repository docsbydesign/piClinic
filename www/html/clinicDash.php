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
*	Clinic Dashboard (home page)
*
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
require_once('visit_common.php');
require_once('visit_get.php');
require_once('security.php');
require_once('profile.php');
$profileData = [];
profileLogStart ($profileData);

$requestData = readRequestData ();
$pageLanguage = getUiLanguage ($requestData);
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('clinicDashText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
$referrerUrlOverride = NO_ACCESS_URL;
require('uiSessionInfo.php');

// open DB
$dbStatus = array();
$dbLink = openDbForUi ($requestData, $pageLanguage, $dbStatus);

profileLogCheckpoint($profileData,'DB_OPEN');

$visitQueryString = [];
$visitRecord = [];
$visitRecord['httpResponse'] = 500; // not initialized, yet

if (empty($dbStatus)) {
	// get the currently open visits (admitted patients)
	$visitQueryString['VisitStatus'] = 'Open';
	$visitQueryString['sortfield'] = 'PatientNameLast';
	$visitQueryString['sortorder'] = 'ASC';
	$visitRecord = _visit_get($dbLink, $visitQueryString);
} else {
	// this error is caught by uiErrorMessage.php below, but 
	//  it should probably be logged as well
	$retVal = [];
	// database not opened.
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
	$retVal['httpResponse'] = 500;
	$retVal['httpReason']   = "Server Error - Database not opened.";
	$retVal['error'] = $dbStatus;
	logUiError($requestData, $retVal, __FILE__, $sessionUser);
	// this message overrides any preceding error
	$requestData['msg'] = 'DB_OPEN_ERROR';
}
profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(HOME_PAGE, $pageLanguage) ?>
	<div class="pageBody">
	<div id="PatientLookupDiv" class="noprint">
		<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">
			<p><label><?= PATIENT_ID_LABEL ?>:</label><br>
				<?= dbFieldTextInput ($requestData, "q", PATIENT_ID_PLACEHOLDER, false, true) ?>
				<?= (!empty($requestData['lang']) ? '<input type="hidden" id="langField" name="lang" value="'.$pageLanguage.'" >': "") ?>
			<button type="submit"><?= SHOW_PATIENT_SUBMIT_BUTTON ?></button>
			</p>
		</form>
		<!--
		<p><a href="/ptSearch.php<?= (!empty($requestData['lang']) ? "?lang=".$pageLanguage : "") ?>"><?= LOOKUP_PATIENT_LINK ?></a></p>
		-->
	<hr>
	</div>
	<div id="ClinicVisitsDiv">
<?php
	if ($visitRecord['httpResponse'] != 200){
		// this is a normal condition and not an error
		echo ('<p>'.NO_OPEN_VISITS.'</p>');
		if (API_DEBUG_MODE) {
			$report['visitRecord'] = $visitRecord;
			$report['query'] = $visitQueryString;
			echo ('<div id="Debug" style="display:none;"> ');
			echo ('<pre>'.json_encode($visitRecord, JSON_PRETTY_PRINT).'</pre>');
			echo ('</div>');
		}
	} else {
		echo ('<h2 id="visitListHeading">'.OPEN_VISIT_LIST_HEAD.'</h2>');
		echo ('<p class="openVisits">*'.EARLIER_VISIT_NOTE.'</p>');
		$visitList = [];
		$earlierVisits = false;
		if ($visitRecord['count'] == 1) {
			// there's only one so make it an array element 
			// so the rest of the code works
			$visitList[0] = $visitRecord['data'];
		} else {
			$visitList = $visitRecord['data'];
		}
		// check to see if they are currently in the clinic
		if (!empty($visitList)) {
			$headerShown = false;
			foreach ($visitList as $visit) {
				if ($visit['VisitStatus'] == 'Open') {
					if (!$headerShown) {
						echo ('<table class="piClinicList"><tr>');
						echo ('<th>'.VISIT_LIST_HEAD_NAME.'</th>');								
						echo ('<th>'.VISIT_LIST_HEAD_DATE.'</th>');
						echo ('<th>'.VISIT_LIST_HEAD_DOCTOR.'</th>');
						echo ('<th>'.VISIT_LIST_HEAD_COMPLAINT.'</th>');
						echo ('<th>'.VISIT_LIST_ACTIONS.'</th>');
						echo ('</tr>');
						$headerShown = true;
					}
					echo ('<tr>');
					echo ('<td class="nowrap"><a href="/ptInfo.php?ClinicPatientID='.$visit['ClinicPatientID'].
						(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "").'" '.
						'title="'.SHOW_PATIENT_INFO.'">'.$visit['PatientNameLast'].',&nbsp;'.$visit['PatientNameFirst'].'&nbsp;('.($visit['PatientSex'] == 'M' ? SEX_OPTION_M : ($visit['PatientSex'] == 'F' ? SEX_OPTION_F : SEX_OPTION_X)).')</a></td>');
					$visitTimeIn = strtotime($visit['DateTimeIn']);
					$notToday = $visitTimeIn < strtotime(date('Y-m-d ').'00:00');
					if ($notToday) {
					    // set this whenever an earlier visit is detected
                        //  so the message will be shown in the heading
					    $earlierVisits = true;
                    }
					echo ('<td class="nowrap'.($notToday ? ' notToday': '' ).'" title="'.date(VISIT_DATE_FORMAT, $visitTimeIn).'">'.($notToday ? '*': '&nbsp;' ).date(VISIT_TIME_FORMAT, $visitTimeIn).'</td>');
					echo ('<td class="nowrap'.(isset($visit['StaffName']) ? '' : ' inactive' ).'">'.(isset($visit['StaffName']) ? $visit['StaffName'] : VISIT_LIST_MISSING ).'</td>');
					$complaintText = (isset($visit['ComplaintPrimary']) ? $visit['ComplaintPrimary'] : VISIT_LIST_MISSING );
					if (strlen($complaintText) > 40) {
						$complaintText = substr($complaintText,0,40).'&nbsp;'.
						'<a href="/visitInfo.php?PatientVisitID='.$visit['PatientVisitID'].
						'&ClinicPatientID='.$visit['ClinicPatientID'].
						(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "").'" '.
						'class="moreInfo"'.
						'title="'.MORE_VISIT_INFO.'">'.VISIT_LIST_ACTION_MORE.'</a>';						
					}
					echo ('<td'.(isset($visit['ComplaintPrimary']) ? '' : ' class="inactive"' ).'>'.$complaintText.'</td>');
					echo ('<td class="nowrap"><a href="/visitInfo.php?PatientVisitID='.$visit['PatientVisitID'].
						'&ClinicPatientID='.$visit['ClinicPatientID'].
						(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "").'" '.
						'title="'.SHOW_VISIT_INFO.'">'.VISIT_LIST_ACTION_VIEW.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a href="/visitEdit.php?PatientVisitID='.$visit['PatientVisitID'].
						'&ClinicPatientID='.$visit['ClinicPatientID'].
						(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "").'" '.
						'title="'.EDIT_VISIT_INFO.'">'.VISIT_LIST_ACTION_EDIT.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a href="/visitClose.php?PatientVisitID='.$visit['PatientVisitID'].
						'&ClinicPatientID='.$visit['ClinicPatientID'].
						(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "").'" '.
						'title="'.DISCHARGE_VISIT_INFO.'">'.VISIT_LIST_ACTION_DISCHARGE.'</a></td>');					
					echo ('</tr>');					
				}
			}
			echo ('</table>');
			echo ('<style>p.openVisits {'. ($earlierVisits ? 'display:inline; ' : 'display: none; ' ).'} </style>');
			echo ('<style>h2 {'. ($earlierVisits ? 'margin-bottom: 0; ' : '' ).'} </style>');
		}
	}

	@mysqli_close($dbLink);
?>
	</div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $requestData); ?>
</html>
