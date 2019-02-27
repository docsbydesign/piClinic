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
*	rptMonthlySummary
*		displays a log of the clinic activity for the specified month
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
require_once dirname(__FILE__).'/./uitext/rptMonthlyPtSummHomeText.php';
require_once dirname(__FILE__).'/../visitUiStrings.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
$referrerUrlOverride = NO_ACCESS_URL;
require('../uiSessionInfo.php');

// open DB or redirect to error URL1
$errorUrl = '/reportHome.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

profileLogCheckpoint($profileData,'CODE_COMPLETE');// set charset header

$reportMonthName = [
    TEXT_RPT_MONTH_01,
    TEXT_RPT_MONTH_02,
    TEXT_RPT_MONTH_03,
    TEXT_RPT_MONTH_04,
    TEXT_RPT_MONTH_05,
    TEXT_RPT_MONTH_06,
    TEXT_RPT_MONTH_07,
    TEXT_RPT_MONTH_08,
    TEXT_RPT_MONTH_09,
    TEXT_RPT_MONTH_10,
    TEXT_RPT_MONTH_11,
    TEXT_RPT_MONTH_12,
];

// check for query parameters and format query values
// initialize to current month/year
$noData = true; // true when no report date query parameters are present
$reportYearArg = date('Y');	// numeric, 4-digit year
$reportMonthArg = date('m');	// numeric, 2-digit month
$reportPosCond = ''; // default is all types of med. pro.
$reportPosField = ''; //field for med. pro.
$reportDefaultOption = TEXT_GROUP_ALL;
$defaultReportProfType = TEXT_NOT_SPECIFIED;
$defaultReportName = TEXT_GROUP_ALL;
$reportFormat = RPT_SHOW_REPORT;
if (!empty($requestData['year'])) {
    // check it out before keeping it
    if (is_numeric($requestData['year'])) {
        $reportYearArg = $requestData['year'];
    }
}

if (!empty($requestData['month'])) {
    // check it out before keeping it
    if (is_numeric($requestData['month'])) {
        $reportMonthArg = $requestData['month'];
        if ($reportMonthArg < 1 ) {
            $reportMonthArg = 1;
        }
        if ($reportMonthArg > 12 ) {
            $reportMonthArg = 12;
        }
    }
}

if (!empty($requestData['month']) && !empty($requestData['year'])) {
    $noData = false;
}

if (!empty($requestData['showdiag'])) {
    if ($requestData['showdiag'] == RPT_SHOW_DATA) {
        $reportFormat = RPT_SHOW_DATA;
        $noData = true;	 // don't query the database if its a report-data query
    }
}

$reportTypeCond = '';
$reportTypeName = 'All';
$reportTypeDisplay = TEXT_VISIT_TYPE_ALL;

if (!empty($requestData['type'])) {
    foreach ($visitTypes as $typeItem) {
        // if the name parameter matches someone who has a visit record, create the WHERE condition text
        if ($requestData['type'] == $typeItem[0])  {
            if ($requestData['type'] != 'All') {
                // if type == all, then no condition string is necessary.
                $reportTypeCond = ' AND `visitType` = \''.$requestData['type'].'\' ';
                $reportTypeName = $requestData['type'];
                $reportTypeDisplay = $typeItem[1];
            }
            break;
        }
    }
}

$staffDataRecord = NULL;
$reportDataRecord = NULL;
$staffInfo = NULL;
$reportInfo = NULL;
if (empty($dbStatus)) {
    if ($reportFormat == RPT_SHOW_REPORT) {
        // get the list of staff who have a visit record, sorted by name
        //  I used this to account for position changes that would show up in the visit table but not the staff table
        //  This method of creating the filter options will always reflect what's in the visit list and should be fast enough to not cause problems
        $staffQueryString = 'SELECT DISTINCT CONCAT(`staffName`, \'|\', `staffPosition`) AS `composite`, `staffName`, `staffPosition` FROM `visit` WHERE `deleted` = 0 order by `staffName`;';
        $staffDataRecord = getDbRecords($dbLink, $staffQueryString);
    }
    if ($reportFormat == RPT_SHOW_DATA) {
        // get the list of staff who have a visit record, sorted by name
        //  I used this to account for position changes that would show up in the visit table but not the staff table
        //  This method of creating the filter options will always reflect what's in the visit list and should be fast enough to not cause problems
        $reportQueryString = 'SELECT * FROM `visitGetAT2DataFields` WHERE 1 ORDER BY `REPORT_ROW` ASC, `icd10index` ASC, `language` ASC;';
        $reportDataRecord = getDbRecords($dbLink, $reportQueryString);
    }
    // else no data is retrieved
} else {
    // this error is caught by uiErrorMessage.php below, but
    //  it should probably be logged as well
    $retVal = [];
    // database not opened.
    $retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
    $retVal['httpResponse'] = 500;
    $retVal['httpReason']   = "Server Error - Database not opened.";
    $dbInfo['sqlError'] = 'Error: '. $dbOpenError .', '.
        mysqli_connect_error();
    $retVal['error'] = $dbInfo;
    logUiError($requestData, $retVal['httpResponse'], __FILE__, $sessionInfo['username'], "report", $retVal['httpReason']);
    // this message overrides any preceding error
    $requestData['msg'] = 'DB_OPEN_ERROR';
}

// if the staff can't be read, this is the only name to pick
$medProfs = [];

array_push ( $medProfs, ["value" => '', "display" => TEXT_GROUP_ALL] );

if (($staffDataRecord != NULL) && ($staffDataRecord['count'] >= 1)) {
    if ($staffDataRecord['count'] == 1) {
        $staffPush = [
            "value" => $staffDataRecord['data']['composite'],
            "display" => $staffDataRecord['data']['staffName'].' - '.staffPosDisplayString ($staffDataRecord['data']['staffPosition'])
        ];
        array_push ( $medProfs, $staffPush);
    } else {
        foreach ($staffDataRecord['data'] as $staffItem) {
            $staffPush = [
                "value" => $staffItem['composite'],
                "display" => $staffItem['staffName'].' - '.staffPosDisplayString ($staffItem['staffPosition'])
            ];
            array_push ( $medProfs, $staffPush);
        }
    }
}

if (!empty($requestData['filter'])) {
    // select this name if it exists in the database, otherwise get all of them by default
    foreach ($medProfs as $displayItem) {
        // if the name parameter matches someone who has a visit record, create the WHERE condition text
        if ($requestData['filter'] == $displayItem['value'])  {
            if ($requestData['filter'] != '%') {
                // if name == all, then no condition string is necessary.
                $filterParams = explode ('|', $requestData['filter']);
                if (count($filterParams) == 2) {
                    $reportPosCond = ' AND `staffName` = \''.$filterParams[0].'\' AND `staffPosition` = \''.$filterParams[1].'\'';
                    $reportPosField = "`visitGetAT2Data`.`staffPosition`,";
                    $reportDefaultOption = $requestData['filter'];
                    $defaultReportName = $filterParams[0];
                    $defaultReportProfType = staffPosDisplayString ($filterParams[1]);
                } // else leave it blank and select ALL
            }
            break;
        }
    }
}
$blankField = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$checkedField = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

$exportReportColumns = [
    ['NursesAid',TEXT_HEADING_NURSE.'_'.TEXT_HEADING_NURSE_AID],
    ['Nurse',TEXT_HEADING_NURSE.'_'.TEXT_HEADING_NURSE_PRO],
    ['DoctorGeneral',TEXT_HEADING_DOCTOR.'_'.TEXT_HEADING_DR_GENERAL],
    ['DoctorSpecialist',TEXT_HEADING_DOCTOR.'_'.TEXT_HEADING_DR_SPECIALIST]
];


$reportGroups = [
    //[Field name, display string symbol, report line, bold style]
    ["RPT_LINE_01", TEXT_RPT_LINE_01,1,false],
    ["RPT_LINE_02", TEXT_RPT_LINE_02,2,false],
    ["RPT_LINE_03", TEXT_RPT_LINE_03,3,false],
    ["RPT_LINE_04", TEXT_RPT_LINE_04,4,false],
    ["RPT_LINE_05", TEXT_RPT_LINE_05,5,false],
    ["RPT_LINE_06", TEXT_RPT_LINE_06,6,false],
    ["RPT_LINE_07", TEXT_RPT_LINE_07,7,false],
    ["RPT_LINE_08", TEXT_RPT_LINE_08,8,false],
    ["RPT_LINE_09", TEXT_RPT_LINE_09,9,false],
    ["RPT_LINE_10", TEXT_RPT_LINE_10,10,false],
    ["RPT_LINE_11", TEXT_RPT_LINE_11,11,false],
    ["RPT_LINE_12", TEXT_RPT_LINE_12,12,false],
    ["RPT_LINE_13", TEXT_RPT_LINE_13,13,false],
    ["RPT_LINE_14", TEXT_RPT_LINE_14,14,false],
    ["RPT_LINE_15", TEXT_RPT_LINE_15,15,false],
    ["RPT_LINE_16", TEXT_RPT_LINE_16,16,false],
    ["RPT_LINE_17", TEXT_RPT_LINE_17,17,false],
    ["RPT_LINE_18", TEXT_RPT_LINE_18,18,false],
    ["RPT_LINE_19", TEXT_RPT_LINE_19,19,true],
    ["RPT_LINE_20", TEXT_RPT_LINE_20,20,false],
    ["RPT_LINE_21", TEXT_RPT_LINE_21,21,false],
    ["RPT_LINE_22", TEXT_RPT_LINE_22,22,false],
    ["RPT_LINE_23", TEXT_RPT_LINE_23,23,false],
    ["RPT_LINE_24", TEXT_RPT_LINE_24,24,false],
    ["RPT_LINE_25", TEXT_RPT_LINE_25,25,false],
    ["RPT_LINE_26", TEXT_RPT_LINE_26,26,false],
    ["RPT_LINE_27", TEXT_RPT_LINE_27,27,false],
    ["RPT_LINE_28", TEXT_RPT_LINE_28,28,false],
    ["RPT_LINE_29", TEXT_RPT_LINE_29,29,false],
    ["RPT_LINE_30", TEXT_RPT_LINE_30,30,false],
    ["RPT_LINE_31", TEXT_RPT_LINE_31,31,false],
    ["RPT_LINE_32", TEXT_RPT_LINE_32,32,false],
    ["RPT_LINE_33", TEXT_RPT_LINE_33,33,false],
    ["RPT_LINE_34", TEXT_RPT_LINE_34,34,false],
    ["RPT_LINE_35", TEXT_RPT_LINE_35,35,false],
    ["RPT_LINE_36", TEXT_RPT_LINE_36,36,false],
    ["RPT_LINE_37", TEXT_RPT_LINE_37,37,false],
    ["RPT_LINE_38", TEXT_RPT_LINE_38,38,false],
    ["RPT_LINE_39", TEXT_RPT_LINE_39,39,false],
    ["RPT_LINE_40", TEXT_RPT_LINE_40,40,false],
    ["RPT_LINE_41", TEXT_RPT_LINE_41,41,false],
    ["RPT_LINE_42", TEXT_RPT_LINE_42,42,false],
    ["RPT_LINE_43", TEXT_RPT_LINE_43,43,false],
    ["RPT_LINE_44", TEXT_RPT_LINE_44,44,true],
    ["RPT_LINE_45", TEXT_RPT_LINE_45,45,false],
    ["RPT_LINE_46", TEXT_RPT_LINE_46,46,false],
    ["RPT_LINE_47", TEXT_RPT_LINE_47,47,false],
    ["RPT_LINE_48", TEXT_RPT_LINE_48,48,false],
    ["RPT_LINE_49", TEXT_RPT_LINE_49,49,false],
    ["RPT_LINE_50", TEXT_RPT_LINE_50,50,false],
    ["RPT_LINE_51", TEXT_RPT_LINE_51,51,false],
    ["RPT_LINE_52", TEXT_RPT_LINE_52,52,false]
];

// set page title
$reportTitle = TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE;
if (!$noData) {
    //create the title from the filters
    $reportTitle = str_replace(" ", "_", TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE);
    $reportTitle .= "-";
    $reportTitle .= preg_replace('([_\W]+)',"_", $defaultReportName);
    $reportTitle .= "-";
    $reportTitle .= sprintf("%04d", $reportYearArg) . '_' . sprintf("%02d", $reportMonthArg);
}

$report = [];
$summaryStats = [];
$getQueryString = '';
$debugErrorInfo = '';
if (empty($dbStatus) && !$noData) {
    /*
    *	TODO_REST:
    *		Resource: Visit
    *		Filter: dateTimeIn = any time during the selected date
    *		QueryParam: dateTimeIn, staffName
    *		Return: visit object array
    */
    $reportEndMonth = $reportMonthArg + 1;
    $reportEndYear = $reportYearArg;
    if ($reportEndMonth > 12) {
        $reportEndMonth = 1;
        $reportEndYear += 1;
    }

    $getQueryString = "select year(`visitGetAT2Data`.`dateTimeIn`) AS `reportYear`,".
        "month(`visitGetAT2Data`.`dateTimeIn`) AS `reportMonth`,".
        "dayofmonth(`visitGetAT2Data`.`dateTimeIn`) AS `reportDay`,".
        $reportPosField.
        "sum(`visitGetAT2Data`.`RPT_LINE_01`) AS `RPT_LINE_01`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_02`) AS `RPT_LINE_02`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_03`) AS `RPT_LINE_03`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_04`) AS `RPT_LINE_04`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_05`) AS `RPT_LINE_05`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_06`) AS `RPT_LINE_06`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_07`) AS `RPT_LINE_07`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_08`) AS `RPT_LINE_08`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_09`) AS `RPT_LINE_09`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_10`) AS `RPT_LINE_10`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_11`) AS `RPT_LINE_11`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_12`) AS `RPT_LINE_12`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_13`) AS `RPT_LINE_13`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_14`) AS `RPT_LINE_14`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_15`) AS `RPT_LINE_15`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_16`) AS `RPT_LINE_16`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_17`) AS `RPT_LINE_17`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_18`) AS `RPT_LINE_18`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_19`) AS `RPT_LINE_19`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_20`) AS `RPT_LINE_20`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_21`) AS `RPT_LINE_21`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_22`) AS `RPT_LINE_22`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_23`) AS `RPT_LINE_23`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_24`) AS `RPT_LINE_24`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_25`) AS `RPT_LINE_25`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_26`) AS `RPT_LINE_26`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_27`) AS `RPT_LINE_27`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_28`) AS `RPT_LINE_28`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_29`) AS `RPT_LINE_29`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_30`) AS `RPT_LINE_30`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_31`) AS `RPT_LINE_31`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_32`) AS `RPT_LINE_32`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_33`) AS `RPT_LINE_33`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_34`) AS `RPT_LINE_34`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_35`) AS `RPT_LINE_35`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_36`) AS `RPT_LINE_36`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_37`) AS `RPT_LINE_37`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_38`) AS `RPT_LINE_38`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_39`) AS `RPT_LINE_39`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_40`) AS `RPT_LINE_40`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_41`) AS `RPT_LINE_41`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_42`) AS `RPT_LINE_42`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_43`) AS `RPT_LINE_43`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_44`) AS `RPT_LINE_44`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_45`) AS `RPT_LINE_45`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_46`) AS `RPT_LINE_46`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_47`) AS `RPT_LINE_47`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_48`) AS `RPT_LINE_48`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_49`) AS `RPT_LINE_49`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_50`) AS `RPT_LINE_50`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_51`) AS `RPT_LINE_51`, ".
        "sum(`visitGetAT2Data`.`RPT_LINE_52`) AS `RPT_LINE_52` ".
        "from `visitGetAT2Data` ".
        "WHERE `dateTimeIn` >= '".$reportYearArg."-".$reportMonthArg."-01' AND `dateTimeIn` < '".$reportEndYear."-".$reportEndMonth."-01'".
        $reportPosCond.
        $reportTypeCond.
        "group by ".$reportPosField."`reportYear`,`reportMonth`,`reportDay`".
        "order by ".$reportPosField."`reportYear`,`reportMonth`,`reportDay`;";

    $dataRecord = getDbRecords($dbLink, $getQueryString);

    $report['dataRecord'] = $dataRecord;
    $report['query'] = $getQueryString;
    if ($dataRecord['httpResponse'] != 200){
        // show debug info if the error is something besides a 404
        if ((API_DEBUG_MODE) && ($dataRecord['httpResponse'] != 404)) {
            $report['dataRecord'] = $dataRecord;
            $report['query'] = $getQueryString;
            $debugErrorInfo .= '<div id="Debug" style="display:none;">';
            $debugErrorInfo .= '<pre>'.json_encode($dataRecord, JSON_PRETTY_PRINT).'</pre>';
            $debugErrorInfo .= '</div>';
        }
    } else {
        if ($dataRecord['count'] == 1) {
            // there's only one so make it an array element
            // so the rest of the code works
            $summaryStats[0] = $dataRecord['data'];
        } else {
            $summaryStats = $dataRecord['data'];
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

    if (!empty($requestData['export'])) {
        $exportData = [];
        $exportDataTotal = [];
        foreach ($summaryStats as $sumStat) {
            $lastSumStat = count($exportData);
            // these fields must be assigned in the order shown here to keep the field alignment correct.
            $exportDataTotal['DH_CLINICNAME'] =
            $exportData[$lastSumStat]['DH_CLINICNAME'] = (isset($clinicInfo['shortName']) ? $clinicInfo['shortName'] : '' );
            $exportDataTotal['DH_CLINICCODE'] =
            $exportData[$lastSumStat]['DH_CLINICCODE'] = (isset($clinicInfo['publicID']) ? $clinicInfo['publicID'] : '');
            $exportDataTotal['DH_SERVICETYPE'] =
            $exportData[$lastSumStat]['DH_SERVICETYPE'] = $reportTypeDisplay;
            $exportDataTotal['DH_SERVICEDATE'] =
            $exportData[$lastSumStat]['DH_SERVICEDATE'] = $sumStat['reportMonth'].'-'.$sumStat['reportYear'];
            $exportDataTotal['DH_REPORTDAY'] = TEXT_HEADING_TOTAL;
            $exportData[$lastSumStat]['DH_REPORTDAY'] = $sumStat['reportDay'];
            $exportDataTotal['DH_SERVICEPROF'] =
            $exportData[$lastSumStat]['DH_SERVICEPROF'] = $defaultReportProfType;
            $exportDataTotal['DH_SERVICENAME'] =
            $exportData[$lastSumStat]['DH_SERVICENAME'] = $defaultReportName;
            // end of critical alignment
            foreach ($sumStat as $key => $value) {
                switch ($key) {
                    case "staffPosition":
                    case "reportDay":
                    case "reportYear":
                    case "reportMonth":
                        // these values were written above
                        break;

                    default:
                        // copy all the rest
                        $exportData[$lastSumStat][$key] = $value;
                        if (isset($exportDataTotal[$key])) {
                            $exportDataTotal[$key] += $value;
                        } else {
                            $exportDataTotal[$key] = $value;
                        }
                        break;
                }
            }
        }
        // append the total values for each row
        array_push ($exportData, $exportDataTotal);

        $exportHeaders = [
            TEXT_REPORT_CLINICNAME_LABEL,
            TEXT_REPORT_CLINIC_CODE_LABEL,
            TEXT_TYPE_LABEL,
            TEXT_DATE_LABEL,
            TEXT_REPORT_DAY,
            TEXT_STAFF_LABEL,
            TEXT_HEADING_PROFESSIONAL_NAME
        ];

        foreach ($reportGroups as $groupItem) {
            array_push($exportHeaders,$groupItem[1]);
        }

        if ($requestData['export'] == 'json') {
            header('Content-Type: application/json; charset=UTF-8');
            $data = [];
            $data_keys = [];
            $keyIdx = 0;
            // map the display strings to the key values
            foreach(array_keys($exportData[0]) as $key) {
                $data_keys[$key] = $exportHeaders[$keyIdx];
                $keyIdx += 1;
            }
            $data['keys'] = $data_keys;
            $data['values'] = $exportData;
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }
        if ($requestData['export'] == 'csv') {
            header('Content-Encoding: WINDOWS-1252');
            header('Content-Type: text/csv; charset=WINDOWS-1252');
            header('Content-Disposition: attachment; filename='.$reportTitle.'.csv');
            $delimiter = ',';
            $exportOut = fopen('php://output', 'w');
            $status = writeTextHeader ($exportOut, $exportHeaders, $delimiter);
            foreach($exportData as $listItem) {
                writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
            }
            fclose ($exportOut);
            return;
        }
        if ($requestData['export'] == 'tsv') {
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$reportTitle.'.tsv');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            $delimiter = "\t";
            $exportOut = fopen('php://output', 'w');
            $status = writeTextHeader ($exportOut, $exportHeaders, $delimiter);
            foreach($exportData as $listItem) {
                writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
            }
            fclose ($exportOut);
            return;
        }
    }
}

$diagReportDisplay = null;
if ($reportFormat == RPT_SHOW_DATA) {
    $diagReportDisplay = [];
    foreach ($reportDataRecord['data'] as $thisEntry) {
        if (empty($diagReportDisplay[$thisEntry['REPORT_ROW']])) {
            $diagReportDisplay[$thisEntry['REPORT_ROW']] =	array (
                'REPORT_ROW' => $thisEntry['REPORT_ROW'],
                'RPT_STRING' => $thisEntry['RPT_STRING'],
                'ROW_TXT_ES' => $thisEntry['ROW_TXT_ES'],
                'AGE_GROUP' => $thisEntry['AGE_GROUP'],
                'DIAGNOSES' => []
            );
        }
        array_push(
            $diagReportDisplay[$thisEntry['REPORT_ROW']]['DIAGNOSES'],
            array(
                'icd10code' => $thisEntry['icd10code'],
                'icd10index' => $thisEntry['icd10index'],
                'language' => $thisEntry['language'],
                'shortDescription' => $thisEntry['shortDescription']
            )
        );
    }

    if (!empty($requestData['export'])) {
        $exportHeaders = [
            'REPORT_ROW',
            'RPT_STRING',
            'ROW_TXT_ES',
            'AGE_GROUP',
            'icd10code',
            'icd10index',
            'language',
            'shortDescription'
        ];

        if ($requestData['export'] == 'json') {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($diagReportDisplay, JSON_PRETTY_PRINT);
            return;
        }
        if ($requestData['export'] == 'csv') {
            header('Content-Encoding: WINDOWS-1252');
            header('Content-Type: text/csv; charset=WINDOWS-1252');
            header('Content-Disposition: attachment; filename=AT2_fields.csv');
            $delimiter = ',';
            $exportOut = fopen('php://output', 'w');
            $status = writeTextHeader ($exportOut, $exportHeaders, $delimiter);
            foreach($reportDataRecord['data'] as $listItem) {
                writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
            }
            fclose ($exportOut);
            return;
        }
        if ($requestData['export'] == 'tsv') {
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=AT2_fields.tsv');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            $delimiter = "\t";
            $exportOut = fopen('php://output', 'w');
            $status = writeTextHeader ($exportOut, $exportHeaders, $delimiter);
            foreach($reportDataRecord['data'] as $listItem) {
                writeTextRecord ($exportOut, NULL, $listItem, $delimiter);
            }
            fclose ($exportOut);
            return;
        }
    }
}


function getStatsValue($summaryStats, $statGroup, $dayNo) {
    foreach ($summaryStats as $stat) {
        if ($stat['reportDay'] == $dayNo) {
            return $stat[$statGroup];
        }
    }
    return ('');
}

function staffPosDisplayString ($staffPosData) {
    switch ($staffPosData) {
        case 'NursesAid':
            return (TEXT_LABEL_NURSE_AID);
        case 'Nurse':
            return TEXT_LABEL_NURSE_PRO;
        case 'DoctorGeneral':
            return TEXT_LABEL_DR_GENERAL;
        case 'DoctorSpecialist':
            return TEXT_LABEL_DR_SPECIALIST;
        case 'NursingStudent':
            return TEXT_LABEL_NURSE_STU;
        case 'MedicalStudent':
            return TEXT_LABEL_DR_STUDENT;
        case 'ClinicStaff':
            return TEXT_LABEL_STAFF;
        default:
            return TEXT_NOT_SPECIFIED;
    }
}

// set charset header
header('Content-type: text/html; charset=utf-8');
// $summaryStats has the list of stats specified by the query parameters
@mysqli_close($dbLink);
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag($reportTitle) ?>
<body>
<?= piClinicTag(); ?>
<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
<?php require ('../uiErrorMessage.php') ?>
<?= piClinicAppMenu(null, $pageLanguage) ?>
<div class="pageBody">
    <div id="MonthlySummaryPrompt" class="noprint <?= ( $reportFormat == RPT_SHOW_DATA ? 'hideDiv' : '') ?>">
        <form enctype="application/x-www-form-urlencoded" action="/reports/rptMonthlyPtSummHome.php" method="get">
            <p>
                <label class="close"><?= TEXT_DATE_LABEL ?>:</label><input type="number" min="2000" max="2100" id="ReportYearField" name="year" class="fourDigitNumeric" title="<?= TEXT_REPORT_YEAR_PLACEHOLDER ?>" value="<?= $reportYearArg ?>" placeholder="<?= TEXT_REPORT_YEAR_PLACEHOLDER ?>">-
                <input type="number" min="1" max="12" id="ReportMonthField" name="month" class="twoDigitNumeric" title="<?= TEXT_REPORT_MONTH_PLACEHOLDER ?>" value="<?= $reportMonthArg ?>" placeholder="<?= TEXT_REPORT_MONTH_PLACEHOLDER ?>">&nbsp;&nbsp;&nbsp;&nbsp;
                <label class="close"><?= TEXT_TYPE_LABEL ?>:</label><select id="visitTypeField" name="type" class="">
                    <?php
                    foreach ($visitTypes as $typeItem) {
                        echo '<option value="'.$typeItem[0].'"'.($reportTypeName == $typeItem[0] ? ' selected="selected"' : '').">".$typeItem[1]."</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="close"><?= TEXT_GROUP_LABEL ?>:</label><select id="posTypeField" name="filter" class="">
                    <?php
                    foreach ($medProfs as $profOption) {
                        echo '<option value="'.$profOption['value'].'"'.($reportDefaultOption == $profOption['value'] ? ' selected="selected"' : '').">".$profOption['display']."</option>";
                    }
                    ?>
                </select>
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
    <div id="NoDataMessage" class="<?= ( (!$noData || ($reportFormat == RPT_SHOW_DATA)) ? 'hideDiv' : '') ?>">
        <p><?= TEXT_NO_REPORT_PROMPT ?></p>
    </div>
    <div id="MonthlySummaryList" class="ledgerLandscapeReport <?= ( ($noData && ($reportFormat == RPT_SHOW_REPORT)) ? ' hideDiv' : '') ?>">
        <div id="AT2-ReportHeading">
            <table id="AT2-LogoTable">
                <tr>
                    <td id="ReportHeading-LeftLogo">&nbsp;
                    </td>
                    <td id="ReportHeading-CenterTitle">
                        <p><?= TEXT_REPORT_SDS ?>&nbsp;-&nbsp;<?= TEXT_REPORT_RSMDC ?></p>
                        <p><?= TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE ?></p>
                    </td>
                    <td rowspan="3" id="ReportHeading-RightLogo">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="left">
                        <p class="left">
                            <label class="close"><?= TEXT_REPORT_REGION_LABEL ?>:</label><span class="underline"><?= (isset($clinicInfo['clinicRegion']) ? $clinicInfo['clinicRegion'] : $blankField ) ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_CLINIC_LEVEL_LABEL ?>:</label><span class="underline"><?= (isset($clinicInfo['careLevel']) ? $clinicInfo['careLevel'] : $blankField ) ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_CLINIC_CISUAPS_LABEL ?>:</label><span class="underline"><?= (isset($clinicInfo['typeCode']) ? $clinicInfo['typeCode'] : $blankField ) ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_MONTH_LABEL ?>:</label><span class="underline"><?= (isset($reportMonthArg) ? $reportMonthName[intval($reportMonthArg)-1] : $blankField ) ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_YEAR_LABEL ?>:</label><span class="underline"><?= (isset($reportYearArg) ? $reportYearArg : $blankField ) ?></span>
                        </p>
                </tr>
                <tr>
                    <td colspan="2" class="left">
                        <p class="left">
                            <label class="close"><?= TEXT_HEADING_PROFESSIONAL_TYPE ?>:</label><span class="underline"><?= $defaultReportProfType ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_HEADING_PROFESSIONAL_NAME ?>:</label><span class="underline"><?= $defaultReportName ?></span>&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_SERVICE_EXTERNAL ?>:</label><span class="underline"><?= ($reportTypeName == 'Outpatient' ? $checkedField : $blankField ) ?></span>&nbsp;&nbsp;&nbsp;
                            <label class="close"><?= TEXT_REPORT_SERVICE_EMERGENCY ?>:</label><span class="underline"><?= ($reportTypeName == 'Emergency' ? $checkedField : $blankField ) ?></span>
                            <span class="underline"><?= ((($reportTypeName != 'Outpatient') && ($reportTypeName != 'Emergency')) ? $reportTypeDisplay : '' )?></span>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="AT2-ReportBody">
            <?php
            // check to see if they are currently in the clinic
            if (!empty($summaryStats)) {
                echo '<table class="report" id="monthlyStatsTable">';
                $tablestaffName = '';
                echo '<tr>';
                echo '<th class="AT2heading"></th>';
                echo '<th class="AT2heading left">'.TEXT_HEADING_CONCEPT.'</th>';
                for ($dayNo = 1; $dayNo <= 31; $dayNo++) {
                    echo '<th class="AT2heading">'.$dayNo.'</th>';
                }
                echo '<th class="AT2heading">'.TEXT_REPORT_COLUMN_TOTAL.'</th>';
                echo '</tr>';

                $dayTotals = [];
                foreach ($reportGroups as $statRow) {
                    echo '<tr>';
                    $groupTotal = 0;
                    echo '<td class="center nowrap'.($statRow[3] ? ' bold' : '').'">'.$statRow[2].'</td>';
                    echo '<td class="nowrap very-wide'.($statRow[3] ? ' bold' : '').'">'.$statRow[1].'</td>';
                    for ($dayNo = 1; $dayNo <= 31; $dayNo++) {
                        $thisStat = (int)getStatsValue($summaryStats, $statRow[0], $dayNo);
                        if (isset($dayTotals[$dayNo])) {
                            $dayTotals[$dayNo] += (int)$thisStat;
                        } else {
                            $dayTotals[$dayNo] = $thisStat;
                        }
                        $groupTotal += $thisStat;
                        $statString = number_format ( (double)$thisStat, 0 , TEXT_DECIMAL_POINT, TEXT_THOUSANDS_SEPARATOR );
                        echo '<td class="numbers'.($statRow[3] ? ' bold' : '').'">'.$thisStat.'</td>';
                    }
                    $statString = number_format ( $groupTotal, 0 , TEXT_DECIMAL_POINT, TEXT_THOUSANDS_SEPARATOR );
                    echo '<td class="numbers'.($statRow[3] ? ' bold' : '').'">'.$statString.'</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '<hr>';
            } else if (!empty($diagReportDisplay)) {
                // show diagnosis data
                echo ('<h1 class="DiagList">'.TEXT_DIAGDATA_HEADING.'</h1>'."\n");
                $missingText = '<span class="DiagListMissing">'.TEXT_DIAGDATA_MISSING_TEXT.'</span>';
                foreach ($reportGroups as $statRow) {
                    if (isset($diagReportDisplay[$statRow[2]])) {
                        $reportLineInfo = $diagReportDisplay[$statRow[2]];
                        echo ('<h2 class="DiagList">'.TEXT_DIAGDATA_REPORT_LINE.' '.$reportLineInfo['REPORT_ROW'].': '. $statRow[1] .'</h2>'."\n");
                        echo ('<div class="indent1">'."\n");
                        echo ('<p class="DiagList">'.TEXT_DIAGDATA_COUNT_HEADING);
                        if (!empty($reportLineInfo['AGE_GROUP'])) {
                            // convert  < > symbols to HTML before displaying
                            echo (TEXT_DIAGDATA_AGE_GROUP. htmlentities($reportLineInfo['AGE_GROUP']));
                        }
                        echo (':</p>'."\n");
                        echo ('<div class="indent1">'."\n");
                        echo ('<table class="DiagList">'."\n");
                        foreach ($reportLineInfo['DIAGNOSES'] as $diagListing) {
                            echo ('<tr>'."\n");
                            echo ('<td class="DiagList" title="icd10code">'.$diagListing['icd10code'].'</td>'."\n");
                            echo ('<td class="DiagList" title="icd10index">'.$diagListing['icd10index'].'</td>'."\n");
                            echo ('<td class="DiagList" title="language">'.$diagListing['language'].'</td>'."\n");
                            echo ('<td class="DiagList" title="shortDescription">'.(!empty($diagListing['shortDescription']) ? $diagListing['shortDescription'] : $missingText ).'</td>'."\n");
                            echo ('</tr>'."\n");
                        }
                        echo ('</table>'."\n");
                        echo ('</div>'."\n");
                        echo ('</div>'."\n");
                    } // else this row isn't reported so skip it
                }
            } else {
                // no visits
                echo ('<p>'.TEXT_REPORT_NO_DATA.'</p>'."\n");
            }
            ?>
        </div>
    </div>
</div>
</body>
<?php
@mysqli_close($dbLink);
?>
</html>