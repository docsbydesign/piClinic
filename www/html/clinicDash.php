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
*
*	Clinic Dashboard (home page)
*
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once './shared/piClinicConfig.php';
require_once './shared/headTag.php';
require_once './shared/dbUtils.php';
require_once './shared/logUtils.php';
require_once './api/api_common.php';
require_once './api/visit_common.php';
require_once './api/visit_get.php';
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// required for error messages
$requestData = $sessionInfo['parameters'];
require_once './uitext/clinicDashText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
$referrerUrlOverride = NO_ACCESS_URL;
require('./uiSessionInfo.php');


// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

profileLogCheckpoint($profileData,'CODE_COMPLETE');
// close any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink, WORKFLOW_STEP_COMPLETE);

$visitQueryString = [];
$visitRecord = [];
$visitRecord['httpResponse'] = 500; // not initialized, yet

$visitSortField = 'dateTimeIn';
if (!empty($requestData['sort'])) {
    switch($requestData['sort']) {
        case 'doctor':
            $visitSortField = 'staffName';
            break;

        case 'name':
            $visitSortField = 'patientLastName';
            break;

        case 'time':
        default:
            $visitSortField = 'dateTimeIn';
            break;
    }
}

// get the currently open visits (admitted patients)
$visitQueryString['visitStatus'] = 'Open';
$visitQueryString['sortfield'] = $visitSortField;
$visitQueryString['sortorder'] = 'ASC';
$visitRecord = _visit_get($dbLink, $sessionInfo['token'], $visitQueryString);
profileLogCheckpoint($profileData,'CODE_COMPLETE');

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_CLINIC_DASH_PAGE_TITLE) ?>
<body>
    <!-- "token": "<?= $sessionInfo['token'] ?>" -->
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(HOME_PAGE, $sessionInfo, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<div id="PatientLookupDiv" class="noprint">
		<form enctype="application/x-www-form-urlencoded" action="/ptResults.php" method="get">
			<p><label class="close"><?= TEXT_SEARCH_PATIENT_ID_LABEL ?>:</label>
				<?= dbFieldTextInput ($requestData, "q", TEXT_PATIENT_ID_PLACEHOLDER, false, true) ?>
			<button class="btn_ptSearch" type="submit"><?= TEXT_SHOW_PATIENT_SUBMIT_BUTTON ?></button>
			</p>
            <input type="hidden" id="WorkflowID" name="<?= WORKFLOW_QUERY_PARAM ?>" value="<?= getWorkflowID(WORKFLOW_TYPE_HOME, 'PT_SEARCH') ?>" >
            <input type="hidden" id="SearchBtnTag" name="<?= FROM_LINK ?>" value="<?= createFromLink (null, __FILE__, 'btn_ptSearch') ?>">
		</form>
	<hr>
	</div>
	<div id="ClinicVisitsDiv">
<?php
    $earlierVisits = false;
	if ($visitRecord['httpResponse'] != 200){
		// this is a normal condition and not an error
		echo ('<p>'.TEXT_NO_OPEN_VISITS.'</p>');
		if (API_DEBUG_MODE) {
			$report['visitResponse'] = $visitRecord;
			$report['query'] = $visitQueryString;
			echo ('<div id="Debug" class="noshow"> ');
			echo ('<pre>'.json_encode($visitRecord, JSON_PRETTY_PRINT).'</pre>');
			echo ('</div>');
		}
	} else {
		echo ('<h2 id="visitListHeading">'.TEXT_OPEN_VISIT_LIST_HEAD.'</h2>');
		echo ('<p class="openVisits">*'.TEXT_EARLIER_VISIT_NOTE.'</p>');
		$visitList = [];
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
				if ($visit['visitStatus'] == 'Open') {
					if (!$headerShown) {
						echo ('<table class="piClinicList"><tr>');
                       $linkUrl = cleanTheUrl ($_SERVER['REQUEST_URI'], ["sort" => "name"], (empty($requestData['fromLink']) ? null : $requestData['fromLink']));
						echo ('<th>'.
                            ($visitSortField != 'patientLastName' ? '<a href="'.$linkUrl.'" title="'.TEXT_SORT_THIS_COLUMN.'">' : '').
                            TEXT_VISIT_LIST_HEAD_NAME.
                            ($visitSortField != 'patientLastName' ? '</a>' : '').
                            '</th>');
                       $linkUrl = cleanTheUrl ($_SERVER['REQUEST_URI'], ["sort" => "time"], (empty($requestData['fromLink']) ? null : $requestData['fromLink']));
						echo ('<th>'.
                            ($visitSortField != 'dateTimeIn' ? '<a href="'.$linkUrl.'" title="'.TEXT_SORT_THIS_COLUMN.'">' : '').
                            TEXT_VISIT_LIST_ARRIVED_DATE.
                            ($visitSortField != 'dateTimeIn' ? '</a>' : '').
                            '</th>');
                       $linkUrl = cleanTheUrl ($_SERVER['REQUEST_URI'], ["sort" => "doctor"], (empty($requestData['fromLink']) ? null : $requestData['fromLink']));
						echo ('<th>'.
                            ($visitSortField != 'staffName' ? '<a href="'.$linkUrl.'" title="'.TEXT_SORT_THIS_COLUMN.'">' : '').
                            TEXT_VISIT_LIST_HEAD_DOCTOR.
                            ($visitSortField != 'staffName' ? '</a>' : '').
                            '</th>');
						echo ('<th>'.TEXT_VISIT_LIST_HEAD_COMPLAINT.'</th>');
						echo ('<th>'.TEXT_VISIT_LIST_ACTIONS.'</th>');
						echo ('</tr>');
						$headerShown = true;
					}
					echo ('<tr>');
					echo ('<td class="nowrap"><a class="a_ptedit" href="/ptInfo.php?clinicPatientID='.urlencode($visit['clinicPatientID']).
                        createFromLink (FROM_LINK_QP, __FILE__, 'a_ptedit').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_HOME, 'PT_VIEW').'" '.
						'title="'.TEXT_SHOW_PATIENT_INFO.'">'.$visit['patientLastName'].',&nbsp;'.$visit['patientFirstName'].'&nbsp;('.
                        ($visit['patientSex'] == 'M' ? TEXT_SEX_OPTION_M : ($visit['patientSex'] == 'F' ? TEXT_SEX_OPTION_F : TEXT_SEX_OPTION_X)).')</a></td>');
					$visitTimeIn = strtotime($visit['dateTimeIn']);
					$notToday = $visitTimeIn < strtotime(date('Y-m-d ').'00:00');
					if ($notToday) {
					    // set this whenever an earlier visit is detected
                        //  so the message will be shown in the heading
					    $earlierVisits = true;
                    }
					echo ('<td class="nowrap'.($notToday ? ' notToday': '' ).'" title="'.date(TEXT_VISIT_DATE_FORMAT, $visitTimeIn).'">'.($notToday ? '*': '&nbsp;' ).date(TEXT_VISIT_TIME_FORMAT, $visitTimeIn).'</td>');
					echo ('<td class="nowrap'.(isset($visit['staffName']) ? '' : ' inactive' ).'">'.(isset($visit['staffName']) ? $visit['staffName'] : TEXT_VISIT_LIST_MISSING ).'</td>');
					$complaintText = (isset($visit['primaryComplaint']) ? $visit['primaryComplaint'] : TEXT_VISIT_LIST_MISSING );
					if (strlen($complaintText) > 40) {
						$complaintText = substr($complaintText,0,40).'&nbsp;'.
						'<a href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.urlencode($visit['clinicPatientID']).
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_HOME, 'VISIT_MORE').
                        createFromLink (FROM_LINK_QP, __FILE__, 'a_visitmore').'" '.
						'class="a_visitmore moreInfo" '.
						'title="'.TEXT_MORE_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_MORE.'</a>';
					}
					echo ('<td'.(isset($visit['primaryComplaint']) ? '' : ' class="inactive"' ).'>'.$complaintText.'</td>');
					echo ('<td class="nowrap"><a class="a_visitview" href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.urlencode($visit['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitview').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_HOME, 'VISIT_VIEW').'" '.
						'title="'.TEXT_SHOW_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_VIEW.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a class="a_visitedit" href="/visitEdit.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.urlencode($visit['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitedit').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_HOME, 'VISIT_EDIT').'" '.
						'title="'.TEXT_EDIT_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_EDIT.'</a>&nbsp;&nbsp;|&nbsp;&nbsp;'.
						'<a class="a_visitclose" href="/visitClose.php?patientVisitID='.$visit['patientVisitID'].
						'&clinicPatientID='.urlencode($visit['clinicPatientID']).createFromLink (FROM_LINK_QP, __FILE__, 'a_visitclose').
                        '&'.WORKFLOW_QUERY_PARAM.'='.getWorkflowID(WORKFLOW_TYPE_HOME, 'VISIT_CLOSE').'" '.
						'title="'.TEXT_DISCHARGE_VISIT_INFO.'">'.TEXT_VISIT_LIST_ACTION_DISCHARGE.'</a></td>');
					echo ('</tr>');
				}
			}
			echo ('</table>');
		}
	}

	@mysqli_close($dbLink);
?>
	</div>
	</div>
    <style>p.openVisits <?= '{'. ($earlierVisits ? 'display:inline; ' : 'display: none; ' ).'}' ?> </style>
    <style>h2 <?= '{'. ($earlierVisits ? 'margin-bottom: 0; ' : '' ).'}' ?> </style>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $requestData); ?>
</html>
