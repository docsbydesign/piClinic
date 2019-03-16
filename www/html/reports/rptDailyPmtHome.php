<?php
/*
 *
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
 *      Report files for a report name ReportName
 *          rptReportNameMenu.php = the file the describes the report for the report menu page
 *          rptReportNameHome.php = the file with the report's home page
 *          rptReportNameHomeText.php = the auto-generated file with the UI text strings (in /reports/uitext)
 *          rptReportNameXXX.php = Additional files as needed for this report (in /reports/support)
 *          rptReportNameXXXText.php = auto-generated UI Text files for additional files (in /reports/uitext)
 *
*	rptMenuDailyLog.php
*		displays a log of daily patients seen (Atenciones Ambulatorios)
*
*/
// This page is not implemented yet, so return to the report home with an error message
//$redirectUrl = '/reportHome.php?msg=UNSUPPORTED';
//header("Location: ". $redirectUrl);
// exit;
/*
*	rptDailyPmt
*		displays a log of the payments received on the specified date
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once '../shared/piClinicConfig.php';
require_once '../shared/headTag.php';
require_once '../shared/dbUtils.php';
require_once '../shared/logUtils.php';
require_once '../api/api_common.php';
require_once '../api/visit_common.php';
require_once '../api/visit_get.php';
require_once '../shared/profile.php';
require_once '../shared/security.php';
require_once '../shared/ui_common.php';
require_once './support/textFileUtils.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// requierd for error messages
$requestData = $sessionInfo['parameters'];
require_once './uitext/rptDailyPmtHomeText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
$referrerUrlOverride = NO_ACCESS_URL;
require('../uiSessionInfo.php');

// open DB or redirect to error URL1
$errorUrl = '/reportHome.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

profileLogCheckpoint($profileData,'CODE_COMPLETE');

// check for query parameters and format query values
// initialize to current month/year
$noData = true;
$reportYearArg = date('Y');	// numeric, 4-digit year
$reportMonthArg = date('m');	// numeric, 2-digit month
$reportDate = '';
$reportTypeCond = '';
if (!empty($requestData['dateTimeIn'])) {
    $noData = false;
    $reportDate = date('Y-m-d', strtotime($requestData['dateTimeIn']));
} else {
    // default is yesterday
    // time value is in seconds and 86400 seconds = 1 day
    $reportDate = date('Y-m-d', (time() - 86400));
}

$reportTypeName = 'All';
$reportTypeDisplay = TEXT_VISIT_TYPE_ALL;

$report = [];
$visitList = [];
$getQueryString = '';
$debugErrorInfo = '';
if (empty($dbStatus) & !$noData) {
    /*
    *	TODO_REST:
    *		Resource: Visit
    *		Filter: dateTimeIn = any time during the selected date
    *		QueryParam: dateTimeIn, StaffName
    *		Return: visit object array
    */
    $getQueryString = "SELECT * FROM `".
        DB_VIEW_VISIT_GET. "` ".
        "WHERE dateTimeIn >= '".$reportDate." 00:00:00' ".
        "AND dateTimeIn <= '".$reportDate." 23:59:59' ".
        "ORDER BY `dateTimeIn` ASC;";
    $visitRecord = getDbRecords($dbLink, $getQueryString);

    $report['visitRecord'] = $visitRecord;
    $report['query'] = $getQueryString;
    if ($visitRecord['httpResponse'] != 200){
        // load the debug div only if it's not a 404 error, which is normal
        if ((API_DEBUG_MODE)  &&  $visitRecord['httpResponse'] != 404){
            $report['visitRecord'] = $visitRecord;
            $report['query'] = $getQueryString;
            $debugErrorInfo .= '<div id="Debug" style="display:none;"';
            $debugErrorInfo .= '<pre>'.json_encode($visitRecord, JSON_PRETTY_PRINT).'</pre>';
            $debugErrorInfo .= '</div>';
        }
    } else {
        if ($visitRecord['count'] == 1) {
            // there's only one so make it an array element
            // so the rest of the code works
            $visitList[0] = $visitRecord['data'];
        } else {
            $visitList = $visitRecord['data'];
        }
    }

    $clinicQueryString = "SELECT * FROM `thisClinicGet` WHERE 1;";
    $clinicRecord = getDbRecords($dbLink, $clinicQueryString);
    $clinicInfo = NULL;
    if ($clinicRecord['httpResponse'] != 200) {
        // unable to get info on this clinic
        if (API_DEBUG_MODE) {
            $debugErrorInfo .= '<div id="Debug" style="display:none;"';
            $debugErrorInfo .= '<pre>'.json_encode($clinicInfo, JSON_PRETTY_PRINT).'</pre>';
            $debugErrorInfo .= '</div>';
        }
        // keep null info and let the display logic below do the right thing
    } else {
        if ($clinicRecord['count'] == 1) {
            // there's only one so make it an array element
            // so the rest of the code works
            $clinicInfo = $clinicRecord['data'];
        } else {
            // somehow more than one clinic was returned so take the first one.
            $clinicInfo = $clinicRecord['data'][0];
        }
    }
}
// set page title
$reportTitle = TEXT_DAILY_PMT_TITLE;
if (!$noData) {
    //create the title from the filters
    $reportTitle = str_replace(" ", "_", TEXT_DAILY_PMT_TITLE);
    $reportTitle .= "-";
    $reportTitle .= preg_replace('([_\W]+)',"_", $clinicInfo['shortName']);
    $reportTitle .= "-";
    $reportTitle .= date("y_m_d", strtotime($reportDate));
}

// Create display array for table and data file
$displayList = [];
$displayRecord = 0;
$dataDateFormat = 'Y-m-d H:i';
$dailyTotal = 0;
foreach ($visitList as $visit) {
    // these are the same for each record
    $displayList[$displayRecord]['RH_DATE_TIME_IN'] = (isset($visit['dateTimeIn']) ? date($dataDateFormat, strtotime($visit['dateTimeIn'])) : '' );
    $displayList[$displayRecord]['RH_CLINIC_PATIENT_VISIT'] = (isset($visit['patientVisitID']) ? $visit['patientVisitID'] : '' );
    $displayList[$displayRecord]['RH_PATIENT_NAME'] = (isset($visit['patientLastName']) ? $visit['patientLastName'] : '' ).
        (isset($visit['patientFirstName']) ? ', '.$visit['patientFirstName'] : '' );
    $displayList[$displayRecord]['RH_CLINIC_PATIENT_ID'] = (isset($visit['clinicPatientID']) ? $visit['clinicPatientID'] : '' );
    $displayList[$displayRecord]['RH_PATIENT_PAYMENT'] = (float)(isset($visit['payment']) ? $visit['payment'] : 0.00 );
    // accumulate the total for this date
    $dailyTotal += $displayList[$displayRecord]['RH_PATIENT_PAYMENT'];
    $displayRecord += 1;
}

$displayList[$displayRecord]['RH_DATE_TIME_IN'] = date("Y-m-d", strtotime($reportDate));
$displayList[$displayRecord]['RH_CLINIC_PATIENT_VISIT'] = '' ;
$displayList[$displayRecord]['RH_PATIENT_NAME'] = TEXT_RH_TOTAL;
$displayList[$displayRecord]['RH_CLINIC_PATIENT_ID'] = '';
$displayList[$displayRecord]['RH_PATIENT_PAYMENT'] = (float)$dailyTotal;
$displayRecord += 1;

$displayHeader = [
    TEXT_DH_DATE_TIME_IN,
    TEXT_DH_CLINIC_PATIENT_VISIT,
    TEXT_DH_PATIENT_NAME,
    TEXT_DH_CLINIC_PATIENT_ID,
    TEXT_DH_PATIENT_PAYMENT
];

// clean out HTML that might be in the field headers
for ($h= 0; $h < count($displayHeader); $h++) {
    $displayHeader[$h] = str_replace ('&nbsp;',' ', $displayHeader[$h]);
}

if (!empty($requestData['export'])) {
    if ($requestData['export'] == 'json') {
        header('Content-Type: application/json; charset=UTF-8');
        // TODO: write the correct HTML headers here
        $data = [];
        $data_keys = [];
        $keyIdx = 0;
        // map the display strings to the key values
        foreach(array_keys($displayList[0]) as $key) {
            $data_keys[$key] = $displayHeader[$keyIdx];
            $keyIdx += 1;
        }
        // $data['rawData'] = $visitList;
        $data['keys'] = $data_keys;
        $data['values'] = $displayList;
        echo json_encode($data, JSON_PRETTY_PRINT);
        return;
    }
    if ($requestData['export'] == 'csv') {
        // TODO: write the correct HTML headers here
        header('Content-Encoding: WINDOWS-1252');
        header('Content-Type: text/csv; charset=WINDOWS-1252');
        header('Content-Disposition: attachment; filename='.$reportTitle.'.csv');
        // echo "\xEF\xBB\xBF"; // UTF-8 BOM
        $delimiter = ',';
        $exportOut = fopen('php://output', 'w');
        $status = writeTextHeader ($exportOut, $displayHeader, $delimiter);
        foreach($displayList as $listItem) {
            writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
        }
        fclose ($exportOut);
        return;
    }
    if ($requestData['export'] == 'tsv') {
        // TODO: write the correct HTML headers here
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename='.$reportTitle.'.tsv');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        $delimiter = "\t";
        $exportOut = fopen('php://output', 'w');
        $status = writeTextHeader ($exportOut, $displayHeader, $delimiter);
        foreach($displayList as $listItem) {
            writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
        }
        fclose ($exportOut);
        return;
    }
}
header('Content-type: text/html; charset=utf-8');
// $visitList has the list of visits specified by the query parameters
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag($reportTitle) ?>
<body>
<?= piClinicTag(); ?>
<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
<?php require ('../uiErrorMessage.php') ?>
<?= piClinicAppMenu(null,  $pageLanguage, __FILE__) ?>
<div class="pageBody">
    <div id="DailyVisitListPrompt" class="noprint">
        <form enctype="application/x-www-form-urlencoded" action="/reports/rptDailyPmtHome.php" method="get">
            <p>
                <label><?= TEXT_DATE_PROMPT_LABEL ?>:</label>&nbsp;
                <input type="text" id="dateTimeInField" name="dateTimeIn" value="<?= date('Y-m-d', strtotime($reportDate)) ?>" placeholder="<?= TEXT_REPORT_DATE_PLACEHOLDER ?>" maxlength="255">&nbsp;&nbsp;&nbsp;&nbsp;
            </p>
            <p>
                <button type="submit"><?= TEXT_SHOW_REPORT_BUTTON ?></button>
                <span class="leftButtonMargin">
					<button type="submit" name="export" value="csv" title="<?= TEXT_EXPORT_CSV_BUTTON_TEXT ?>"><?= TEXT_EXPORT_CSV_BUTTON ?></button>
					<button type="submit" name="export" value="tsv" title="<?= TEXT_EXPORT_TSV_BUTTON_TEXT ?>"><?= TEXT_EXPORT_TSV_BUTTON ?></button>
					</span>
            </p>
        </form>
        <hr>
    </div>
    <?= $debugErrorInfo ?>
    <div id="NoDataMessage" class="<?= (!$noData ? 'hideDiv' : '') ?>">
        <p><?= TEXT_NO_REPORT_PROMPT ?></p>
    </div>
    <?php
    echo '<div id="DailypaymentLog"  class="portraitReport'.($noData ? ' hideDiv' : '').'">';
    DEFINE('ROWS_PER_PAGE',30,false);
    $reportLineNumber = 0;
    $blankField = '';
    $pageRows = 0;
    $pageNo = 0;
    $reportRows = count($visitList);
    $pageCount = ceil ($reportRows / ROWS_PER_PAGE);
    if ($reportRows > 0) {
        foreach ($visitList as $visit) {
            if (($pageRows % ROWS_PER_PAGE) == 0) {
                $pageRows = 0;
                $pageNo += 1;
                // start a new page
                echo '<div id="paymentLogHeading_page'.sprintf('%03d', ($pageNo % 1000)).'" class="reportPage'.($pageNo > 1 ? ' newPage': '').'">';
                echo '<h1>'.TEXT_DAILY_VISIT_HEADING.'</h1>';
                echo '<p><label class="close">'.TEXT_REPORT_CLINICNAME_LABEL.':</label>'.
                    (isset($clinicInfo['shortName']) ? $clinicInfo['shortName'] : $blankField ).'</p>';
                echo '<p><label class="close">'.TEXT_DATE_LABEL.':</label>'.date(TEXT_DATE_FORMAT, strtotime($reportDate)).
                    '&nbsp;&nbsp;&nbsp;&nbsp;<label class="close">'.TEXT_PATIENT_COUNT_LABEL.':</label>'.$reportRows.
                    '&nbsp;&nbsp;&nbsp;&nbsp;<label class="close">'.TEXT_PAGE_LABEL.':</label>'.$pageNo.' '.TEXT_OF_PAGES.' '.$pageCount.
                    '</p>';
                echo '</div>';
                echo '<div id="paymentLogBody_page'.sprintf('%03d', ($pageNo % 1000)).'">';
                echo '<table class="report" id="dailyVisitTable_page'.sprintf('%03d', ($pageNo % 1000)).'">';
                echo '<tr>';
                echo '<th class="ATAheading">'.TEXT_REPORT_ROW_LABEL.'</th>';
                echo '<th class="ATAheading">'.TEXT_REPORT_DATE_TIME_IN.'</th>';
                echo '<th class="ATAheading">'.TEXT_REPORT_PATIENT_NAME_LABEL.'</th>';
                echo '<th class="ATAheading">'.TEXT_REPORT_PATIENT_ID_LABEL.'</th>';
                echo '<th class="ATAheading">'.TEXT_REPORT_PAYMENT_LABEL.'</th>';
                echo '</tr>';
            }
            // write the next report line
            echo '<tr>'."\n";
            $reportLineNumber += 1;
            $pageRows += 1;
            echo '<td class="center nowrap">'.$reportLineNumber.'</td>';
            echo '<td class="nowrap">'.
                '<a href="/visitInfo.php?patientVisitID='.$visit['patientVisitID'].'" '.
                'class="reportLink">'.(isset($visit['dateTimeIn']) ? date('H:i', strtotime($visit['dateTimeIn'])) : '' ).'</a></td>';
            echo '<td class="nowrap">'.
                '<a href="/ptInfo.php?clinicPatientID='.$visit['clinicPatientID'].'" '.
                'class="reportLink">'.str_replace(' ', '&nbsp;', (isset($visit['patientLastName']) ? $visit['patientLastName'] : '' ).
                    (isset($visit['patientFirstName']) ? ', '.$visit['patientFirstName'] : '' )).'</a></td>';
            echo '<td class="nowrap">'.
                '<a href="/ptInfo.php?clinicPatientID='.$visit['clinicPatientID'].'" '.
                'class="reportLink">'.$visit['clinicPatientID'].'</a></td>';
            echo '<td class="numbers">'.number_format($visit['payment'],2,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
            // DEBUG echo '<td>'.$pageRows.'</td><td>'.$reportLineNumber.'</td><td>'.$reportRows.'</td>';
            echo '</tr>'."\n";
            if ($reportLineNumber == $reportRows) {
                // write the total and close up the table
                echo '<tr>';
                echo '<td class="nowrap"></td>';
                echo '<td class="nowrap"></td>';
                echo '<td class="nowrap"></td>';
                echo '<td class="bold nowrap">'.TEXT_REPORT_TOTAL.'</td>';
                echo '<td class="bold numbers">'.number_format($dailyTotal,2,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
                echo '</tr>'."\n";
                echo '</table>'."\n";
                echo '</div>'."\n";
            } else if (($pageRows % ROWS_PER_PAGE) == 0) {
                // the pages is full so close the table and start a new page
                echo '</table>'."\n";
                echo '<p class="continued">'.TEXT_REPORT_CONTINUED.'</p>';
                echo '</div>'."\n";
            }
        }

        echo '<hr>';
    } else {
        // no visits
        echo '<p>'.TEXT_NO_VISITS_FOUND_DAY.'</p>';
    }
    echo ('</div>');
    ?>
</div>
</body>
<?php
@mysqli_close($dbLink);
?>
</html>