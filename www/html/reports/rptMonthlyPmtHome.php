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
/*
*	rptMonthlyPmt
*		displays a log of the payments received each day during the selected month
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
require_once './uitext/rptMonthlyPmtHomeText.php';

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
$noData = true; // true when no report date query parameters are present
$reportYearArg = date('Y');	// numeric, 4-digit year
$reportMonthArg = date('m');	// numeric, 2-digit month
if (!empty($requestData['Year'])) {
    // check it out before keeping it
    if (is_numeric($requestData['Year'])) {
        $reportYearArg = $requestData['Year'];
    }
}

if (!empty($requestData['Month'])) {
    // check it out before keeping it
    if (is_numeric($requestData['Month'])) {
        $reportMonthArg = $requestData['Month'];
        if ($reportMonthArg < 1 ) {
            $reportMonthArg = 1;
        }
        if ($reportMonthArg > 12 ) {
            $reportMonthArg = 12;
        }
    }
}

$reportFirstDate = '';
$reportLastDate = '';
$lastReportDate = '';
$reportYearMo = '';
if (!empty($requestData['Month']) && !empty($requestData['Year'])) {
    $noData = false;
    $reportFirstDate = date('Y-m-d',strtotime($reportYearArg.'-'.$reportMonthArg.'-01'));
    $reportEndYear = $reportYearArg;
    $reportEndMonth = $reportMonthArg + 1;
    if ($reportEndMonth > 12) {
        $reportEndYear += 1;
        $reportEndMonth = 1;
    }
    $reportLastDate = date('Y-m-d',strtotime($reportEndYear.'-'.$reportEndMonth.'-01'));

    $reportYearMo = $reportYearArg.'-'.$reportMonthArg.'-';
    $lastReportDate = $reportYearMo;
}

$weekdayString = [
    '',
    TEXT_REPORT_WEEKDAY_1,
    TEXT_REPORT_WEEKDAY_2,
    TEXT_REPORT_WEEKDAY_3,
    TEXT_REPORT_WEEKDAY_4,
    TEXT_REPORT_WEEKDAY_5,
    TEXT_REPORT_WEEKDAY_6,
    TEXT_REPORT_WEEKDAY_7
];


$report = [];
$paymentList = [];
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
    $getQueryString = "SELECT DATE_FORMAT(`dateTimeIn`,'%d') as `dayOfMonth`, count(*) as `patients`, SUM(`payment`) as `totalReceived` from `".
        DB_TABLE_VISIT. "` ".
        "WHERE dateTimeIn >= '".$reportFirstDate." 00:00:00' ".
        "AND dateTimeIn < '".$reportLastDate." 00:00:00' ".
        "GROUP BY `dayOfMonth` ORDER BY `dayOfMonth`;";
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
            $paymentList[0] = $visitRecord['data'];
        } else {
            $paymentList = $visitRecord['data'];
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
$reportTitle = TEXT_MONTHLY_PAGE_TITLE;
if (!$noData) {
    //create the title from the filters
    $reportTitle = str_replace(" ", "_", TEXT_MONTHLY_PAGE_TITLE);
    $reportTitle .= "-";
    $reportTitle .= preg_replace('([_\W]+)',"_", $clinicInfo['shortName']);
    $reportTitle .= "-";
    $reportTitle .= date("Y_m", strtotime($reportFirstDate));
}

// determine how many days in this month
$daysInNormalMonth = [0,31,28,31,30,31,30,31,31,30,31,30,31];
$daysInLeapYearMonth = [0,31,29,31,30,31,30,31,31,30,31,30,31];
$daysInThisMonth = 0;
if (((int)$reportYearArg % 4) == 0 && ((int)$reportYearArg % 100) != 0) {
    $daysInThisMonth = $daysInLeapYearMonth[(int)$reportMonthArg];
} else {
    $daysInThisMonth = $daysInNormalMonth[(int)$reportMonthArg];
}
// initialize the data array for the days in the month
$displayList = [];
for ($day = 1; $day <= $daysInThisMonth; $day += 1) {
    $thisReportDate = strtotime ($reportYearMo.sprintf('%02d',$day));

    $displayList[$day] = [
        'RH_DAY_OF_MONTH' => (int)$day,
        'RH_WEEKDAY' => (int)date('N',$thisReportDate),
        'RH_PATIENT_COUNT' =>0,
        'RH_PAYMENT_TOTAL'=>0.0
    ];
}

// Create display array for table and data file
$dataDateFormat = 'Y-m-d H:i';
$dailyPmtTotal = 0;
$dailyPtTotal = 0;
$lastReportDay = 0;
$lastDate = '';

foreach ($paymentList as $daySumm) {
    // paymentList should be sorted here
    // these are the same for each record
    $day = (int)$daySumm['dayOfMonth'];
    if (isset($daySumm['dayOfMonth'])) {
        $displayList[$day]['RH_DAY_OF_MONTH'] = (int)$daySumm['dayOfMonth'];
        $thisReportDate = strtotime ($reportYearMo.sprintf('%02d',$day));
        $displayList[$day]['RH_WEEKDAY'] = (int)date('N',$thisReportDate);
        $displayList[$day]['XX_REPORTDATE'] = $thisReportDate;
    }
    $displayList[$day]['RH_PATIENT_COUNT'] = (isset($daySumm['patients']) ? (int)$daySumm['patients'] : '' );
    $displayList[$day]['RH_PAYMENT_TOTAL'] = (isset($daySumm['totalReceived']) ? (float)$daySumm['totalReceived'] : '' );
    // accumulate the total for this date
    $dailyPtTotal += $displayList[$day]['RH_PATIENT_COUNT'];
    $dailyPmtTotal += $displayList[$day]['RH_PAYMENT_TOTAL'];
    if ($lastReportDay < $displayList[$day]['RH_DAY_OF_MONTH']) {
        $lastReportDay = ($displayList[$day]['RH_DAY_OF_MONTH']);
    } else {
        $lastReportDay = ($lastReportDay);
    }
}

$lastReportDate .= sprintf('%02d', $lastReportDay);
$daysInThisMonth = (int)$lastReportDay;

$displayList[$daysInThisMonth + 1]['RH_DAY_OF_MONTH'] = '';
$displayList[$daysInThisMonth + 1]['RH_WEEKDAY'] = TEXT_RH_TOTAL;
$displayList[$daysInThisMonth + 1]['RH_PATIENT_COUNT'] = (int)$dailyPtTotal;
$displayList[$daysInThisMonth + 1]['RH_PAYMENT_TOTAL'] = (float)$dailyPmtTotal;

$displayHeader = [
    TEXT_DH_VISIT_DATE,
    TEXT_DH_VISIT_WEEKDAY,
    TEXT_DH_PATIENT_COUNT,
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
        if (!empty($displayList[1])) {
            foreach(array_keys($displayList[1]) as $key) {
                $data_keys[$key] = $displayHeader[$keyIdx];
                $keyIdx += 1;
            }
        }
        // $data['report'] = $report;
        // $data['rawData'] = $paymentList;
        $data['keys'] = $data_keys;
        $data['values'] = [];
        for ($day = 1; $day <= $lastReportDay; $day += 1) {
            if (!empty($displayList[$day])) {
                array_push($data['values'], $displayList[$day]);
            }
        }
        // append total row
        array_push($data['values'], $displayList[$day]);
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
        for ($day = 1; $day <= $lastReportDay; $day += 1) {
            writeTextRecord ($exportOut, NULL, $displayList[$day], $delimiter);
        }
        writeTextRecord ($exportOut, NULL, $displayList[$day], $delimiter);
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
        for ($day = 1; $day <= $lastReportDay; $day += 1) {
            writeTextRecord ($exportOut, NULL, $displayList[$day], $delimiter);
        }
        writeTextRecord ($exportOut, NULL, $displayList[$day], $delimiter);
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
    <div id="MonthlySummaryPrompt" class="noprint">
        <form enctype="application/x-www-form-urlencoded" action="/reports/rptMonthlyPmtHome.php" method="get">
            <p>
                <label class="close"><?= TEXT_DATE_LABEL ?>:</label><input type="number" min="2000" max="2100" id="ReportYearField" name="Year" class="fourDigitNumeric" title="<?= TEXT_REPORT_YEAR_PLACEHOLDER ?>" value="<?= $reportYearArg ?>" placeholder="<?= TEXT_REPORT_YEAR_PLACEHOLDER ?>">-
                <input type="number" min="1" max="12" id="ReportMonthField" name="Month" class="twoDigitNumeric" title="<?= TEXT_REPORT_MONTH_PLACEHOLDER ?>" value="<?= $reportMonthArg ?>" placeholder="<?= TEXT_REPORT_MONTH_PLACEHOLDER ?>">&nbsp;&nbsp;&nbsp;&nbsp;
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
    echo '<div id="DailyPaymentLog"  class="portraitReport'.($noData ? ' hideDiv' : '').'">';
    $reportRows = count($paymentList);
    if ($reportRows > 0) {
        echo '<div id="PaymentLogHeading">';
        echo '<h1>'.TEXT_MONTHLY_VISIT_HEADING.'</h1>';
        echo '<p><label class="close">'.TEXT_REPORT_CLINICNAME_LABEL.':</label>'.
            (isset($clinicInfo['shortName']) ? $clinicInfo['shortName'] : $blankField ).'</p>';
        echo '<p><label class="close">'.TEXT_REPORT_DATE_LABEL.':</label>'.date(TEXT_DATE_FORMAT, strtotime($lastReportDate)).'</p>';
        echo '</div>';
        echo '<div id="PaymentLogBody">';
        echo '<table class="report" id="dailyVisitTable">';
        echo '<tr>';
        echo '<th class="ATAheading">'.TEXT_REPORT_DAY_OF_MONTH_LABEL.'</th>';
        echo '<th class="ATAheading">'.TEXT_REPORT_WEEKDAY_LABEL.'</th>';
        echo '<th class="ATAheading">'.TEXT_REPORT_PATIENT_COUNT_LABEL.'</th>';
        echo '<th class="ATAheading">'.TEXT_REPORT_PAYMENT_TOTAL_LABEL.'</th>';
        echo '</tr>';
        for ($day = 1; $day <= $lastReportDay; $day += 1) {
            echo '<tr>'."\n";
            echo '<td class="center nowrap">'.$displayList[$day]['RH_DAY_OF_MONTH'].'</td>';
            echo '<td class="nowrap">'.$weekdayString[(int)$displayList[$day]['RH_WEEKDAY']].'</td>';
            echo '<td class="nowrap numbers">'.number_format(floatval($displayList[$day]['RH_PATIENT_COUNT']),0,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
            echo '<td class="nowrap numbers">'.number_format(floatval($displayList[$day]['RH_PAYMENT_TOTAL']),2,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
            echo '</tr>'."\n";
        }
        echo '<tr>';
        echo '<td class="bold nowrap"> </td>';
        echo '<td class="bold nowrap">'.TEXT_REPORT_TOTAL.'</td>';
        echo '<td class="bold numbers">'.number_format((int)$dailyPtTotal,0,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
        echo '<td class="bold numbers">'.number_format((float)$dailyPmtTotal,2,TEXT_RPT_DECIMAL, TEXT_RPT_DIGIT_SEPARATOR).'</td>';
        echo '</tr>'."\n";
        echo '</table>'."\n";
        echo '</div>'."\n";
        echo '<hr>';
    } else {
        // no visits
        echo '<p>'.TEXT_NO_VISITS_FOUND_MONTH.'</p>';
    }
    echo '</div>';
    ?>
</div>
</body>
<?php
@mysqli_close($dbLink);
?>
</html>