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
*	rptDailyLog
*		displays a log of the clinic activity for the specified date
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

// always log the report time
//   this is separate because profiling is usually only done on non-production builds
$reportProfile = [];
$reportProfile['start'] = microtime(true);
$reportProfile['report'] = __FILE__;
$reportProfile['count'] = -1;

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// requierd for error messages
$requestData = $sessionInfo['parameters'];
require_once dirname(__FILE__).'/./uitext/rptDailyLogHomeText.php';
require_once dirname(__FILE__).'/../visitUiStrings.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_STAFF;
$referrerUrlOverride = NO_ACCESS_URL;
require('../uiSessionInfo.php');

// open DB or redirect to error URL1
$errorUrl = '/reportHome.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

profileLogCheckpoint($profileData,'CODE_COMPLETE');// set charset header

// check for query parameters and format query values
// initialize to current month/year
$noData = true;
$reportYearArg = date('Y');	// numeric, 4-digit year
$reportMonthArg = date('m');	// numeric, 2-digit month
$reportPosCond = ''; // default is all types of med. pro.
$reportDefaultOption = TEXT_GROUP_ALL;
$defaultReportProfType = TEXT_NOT_SPECIFIED;
$defaultReportName = TEXT_GROUP_ALL;
$reportDate = '';
$reportTypeCond = '';
if (!empty($requestData['dateTimeIn'])) {
    $reportDate = date('Y-m-d', strtotime($requestData['dateTimeIn']));
}

$staffDataRecord = NULL;
$staffInfo = NULL;
// get the list of staff who have a visit record, sorted by name
//  I used this to account for position changes that would show up in the visit table but not the staff table
//  This method of creating the filter options will always reflect what's in the visit list and should be fast enough to not cause problems
$staffQueryString = 'SELECT DISTINCT CONCAT(`staffName`, \'|\', `staffPosition`) AS `composite`, `staffName`, `staffPosition` FROM `visit` WHERE `deleted` = 0 order by `staffName`;';
$staffDataRecord = getDbRecords($dbLink, $staffQueryString);

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
/*
 *  Get the list of reportable dates from the visit table
 *      if the dateInput param is present and = select
 */
$reportDateList = [];
if (empty($requestData['dateInput']) || $requestData['dateInput'] == 'select') {
    $reportDateQuery = 'SELECT distinct DATE_FORMAT(`dateTimeIn`,\'%Y-%m-%d\') AS `reportDate` FROM `visit` WHERE 1 order by `reportDate` desc;';
    $reportDateResult = getDbRecords($dbLink, $reportDateQuery);
    if ($reportDateResult['count'] > 0) {
        if ($reportDateResult['count'] == 1) {
            $reportDateList[0] = $reportDateResult['data'];
        } else {
            $reportDateList = $reportDateResult['data'];
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
                    $reportPosCond = 'AND `staffName` = \''.$filterParams[0].'\' AND `staffPosition` = \''.$filterParams[1].'\' ';
                    $reportDefaultOption = $requestData['filter'];
                    $defaultReportName = $filterParams[0];
                    $defaultReportProfType = staffPosDisplayString ($filterParams[1]);
                } // else leave it blank and select ALL
            }
            break;
        }
    }
}

if (!empty($requestData['dateTimeIn'])) {
    $noData = false;
}

$reportTypeName = 'All';
$reportTypeDisplay = TEXT_VISIT_TYPE_ALL;

if (!empty($requestData['type'])) {
    foreach ($visitTypes as $typeItem) {
        // if the name parameter matches someone who has a visit record, create the WHERE condition text
        if ($requestData['type'] == $typeItem[0])  {
            if ($requestData['type'] != 'All') {
                // if type == all, then no condition string is necessary.
                $reportTypeCond = 'AND `visitType` = \''.$requestData['type'].'\' ';
                $reportTypeName = $requestData['type'];
                $reportTypeDisplay = $typeItem[1];
            }
            break;
        }
    }
}

$blankField = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$checkedField = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

$report = [];
$visitList = [];
$getQueryString = '';
$debugErrorInfo = '';
if (empty($dbStatus) & !$noData) {
    /*
    *	TODO_REST:
    *		Resource: Visit
    *		Filter: dateTimeIn = any time during the selected date
    *		QueryParam: dateTimeIn, staffName
    *		Return: visit object array
    */
    $getQueryString = "SELECT * FROM `".
        DB_VIEW_VISIT_GET. "` ".
        "WHERE dateTimeIn >= '".$reportDate." 00:00:00' ".
        "AND dateTimeIn <= '".$reportDate." 23:59:59' ".
        $reportPosCond.
        $reportTypeCond.
        "ORDER BY `staffName` ASC, `dateTimeIn` ASC;";
    $visitRecord = getDbRecords($dbLink, $getQueryString);

    $report['visitResponse'] = $visitRecord;
    $report['query'] = $getQueryString;
    if ($visitRecord['httpResponse'] != 200){
        // load the debug div only if it's not a 404 error, which is normal
        if ((API_DEBUG_MODE)  &&  $visitRecord['httpResponse'] != 404){
            $report['visitResponse'] = $visitRecord;
            $report['query'] = $getQueryString;
            $debugErrorInfo .= '<div id="Debug" class="noshow"';
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

    if (!$noData) {
        $reportProfile['count'] = $visitRecord['count'];
    }

    $clinicQueryString = "SELECT * FROM `thisClinicGet` WHERE 1;";
    $clinicRecord = getDbRecords($dbLink, $clinicQueryString);
    $clinicInfo = NULL;
    if ($clinicRecord['httpResponse'] != 200) {
        // unable to get info on this clinic
        if (API_DEBUG_MODE) {
            $debugErrorInfo .= '<div id="Debug" class="noshow"';
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
$reportTitle = TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE;
if (!$noData) {
    //create the title from the filters
    $reportTitle = str_replace(" ", "_", TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE);
    $reportTitle .= "-";
    $reportTitle .= preg_replace('([_\W]+)',"_", $defaultReportName);
    $reportTitle .= "-";
    $reportTitle .= date("y_m_d", strtotime($reportDate));
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

// Create display array for table and data file
$displayList = [];
$displayRecord = 0;
$dataDateFormat = 'Y-m-d';
foreach ($visitList as $visit) {
    // these are the same for each record
    $displayList[$displayRecord]['DH_CLINICNAME'] = (isset($clinicInfo['shortName']) ? $clinicInfo['shortName'] : '' );
    $displayList[$displayRecord]['DH_CLINICCODE'] = (isset($clinicInfo['publicID']) ? $clinicInfo['publicID'] : $blankField );
    // these are specific to the visit
    $displayList[$displayRecord]['DH_SERVICETYPE'] = showvisitTypeString ($visit['visitType'], $visitTypes);
    $displayList[$displayRecord]['DH_SERVICEDATE'] = date($dataDateFormat, strtotime($reportDate));
    $displayList[$displayRecord]['DH_SERVICEPROF'] = staffPosDisplayString ($visit['staffPosition']);
    $displayList[$displayRecord]['DH_SERVICENAME'] = $visit['staffName'];
    $displayList[$displayRecord]['DR_ROWNO'] = $displayRecord + 1;
    $displayList[$displayRecord]['DR_PTFAMID'] = $visit['patientFamilyID'];
    $displayList[$displayRecord]['DR_PTNAME'] = $visit['patientLastName'].', '.$visit['patientFirstName'];
    $displayList[$displayRecord]['DR_PTID'] = $visit['clinicPatientID'];
    $displayList[$displayRecord]['DR_PTSEX'] = ($visit['patientSex'] == 'M' ? TEXT_SEX_OPTION_M : ($visit['patientSex'] == 'F' ? TEXT_SEX_OPTION_F : TEXT_SEX_OPTION_X));
    $displayList[$displayRecord]['DR_PTDOB'] = formatDbDate ($visit['patientBirthDate'], $dataDateFormat, '');
    $ageYMD = dateDiffYMD (strtotime($visit['patientBirthDate']),  strtotime($reportDate));
    $displayList[$displayRecord]['DR_AGEYR'] = ($ageYMD['years'] > 0 ? $ageYMD['years'] : '');
    $displayList[$displayRecord]['DR_AGEMO'] = ((($ageYMD['years'] == 0) && ($ageYMD['months'] > 0)) ? $ageYMD['months'] : '');
    $displayList[$displayRecord]['DR_AGEDY'] = ((($ageYMD['years'] == 0) && ($ageYMD['months'] == 0)) ? $ageYMD['days'] : '');
    $displayList[$displayRecord]['DR_FIRST'] = ($visit['firstVisit'] == "YES" ? 'N' : 'S');
    $displayList[$displayRecord]['DR_STATE'] = $visit['patientHomeState'];
    $displayList[$displayRecord]['DR_CITY'] = $visit['patientHomeCity'];
    $displayList[$displayRecord]['DR_NHOOD'] = $visit['patientHomeNeighborhood'];
    $code = '';
    $diag = '';
    if (!empty($visit['diagnosis1'])) {
        $diag = getIcdDescription ($dbLink, $visit['diagnosis1'], $pageLanguage);
        if ($diag == $visit['diagnosis1']) {
            $diag =  $visit['diagnosis1'];
        } else {
            $code = $visit['diagnosis1'];
        }
    }
    $displayList[$displayRecord]['DR_ICD_1'] = $code;
    $displayList[$displayRecord]['DR_DIAG1'] = $diag;
    $displayList[$displayRecord]['DR_COND1'] = conditionText($visit['condition1'], FALSE);
    $code = '';
    $diag = '';
    if (!empty($visit['diagnosis2'])) {
        $diag = getIcdDescription ($dbLink, $visit['diagnosis2'], $pageLanguage);
        if ($diag == $visit['diagnosis2']) {
            $diag =  $visit['diagnosis2'];
        } else {
            $code = $visit['diagnosis2'];
        }
    }
    $displayList[$displayRecord]['DR_ICD_2'] = $code;
    $displayList[$displayRecord]['DR_DIAG2'] = $diag;
    $displayList[$displayRecord]['DR_COND2'] = conditionText($visit['condition2'], FALSE);
    $code = '';
    $diag = '';
    if (!empty($visit['diagnosis3'])) {
        $diag = getIcdDescription ($dbLink, $visit['diagnosis3'], $pageLanguage);
        if ($diag == $visit['diagnosis3']) {
            $diag =  $visit['diagnosis3'];
        } else {
            $code = $visit['diagnosis3'];
        }
    }
    $displayList[$displayRecord]['DR_ICD_3'] = $code;
    $displayList[$displayRecord]['DR_DIAG3'] = $diag;
    $displayList[$displayRecord]['DR_COND3'] = conditionText($visit['condition3'], FALSE);
    $displayList[$displayRecord]['DR_REFTO'] = $visit['referredTo'];
    $displayList[$displayRecord]['DR_REFFROM'] = $visit['referredFrom'];
    $displayRecord += 1;
}

$displayHeader = [
    TEXT_REPORT_CLINICNAME_LABEL,
    TEXT_REPORT_CLINIC_CODE_LABEL,
    TEXT_TYPE_LABEL,
    TEXT_DATE_LABEL,
    TEXT_STAFF_LABEL,
    TEXT_STAFF_NAME_LABEL,
    TEXT_REPORT_ROW_LABEL,
    TEXT_PATIENTVISITID_LABEL,
    TEXT_FULLNAME_LABEL,
    TEXT_CLINICPATIENTID_LABEL,
    TEXT_SEX_LABEL,
    TEXT_BIRTHDAY_DATE_LABEL,
    TEXT_REPORT_AGE_LABEL.'_'.TEXT_AGE_YEARS_LABEL,
    TEXT_REPORT_AGE_LABEL.'_'.TEXT_AGE_MONTHS_LABEL,
    TEXT_REPORT_AGE_LABEL.'_'.TEXT_AGE_DAYS_LABEL,
    TEXT_PATIENT_LABEL,
    TEXT_REPORT_ADDRESS_LABEL.'_'.TEXT_STATE_LABEL,
    TEXT_REPORT_ADDRESS_LABEL.'_'.TEXT_CITY_LABEL,
    TEXT_REPORT_ADDRESS_LABEL.'_'.TEXT_NEIGHBORHOOD_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_1_ICD_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_1_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_CONDITION_1_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_2_ICD_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_2_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_CONDITION_2_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_3_ICD_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_DIAGNOSIS_3_LABEL,
    TEXT_REPORT_DIAGNOSIS_LABEL.'_'.TEXT_CONDITION_3_LABEL,
    TEXT_REPORT_REFERRAL_LABEL.'_'.TEXT_REFERRED_TO_LABEL,
    TEXT_REPORT_REFERRAL_LABEL.'_'.TEXT_REFERRED_FROM_LABEL
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

function showDateSelect($dateArray, $reportDate) {
    $inputHtml = '';
    if (empty($dateArray)){
        if (empty($reportDate)){
            // if no date, set it to yesterday
            $reportDate = date('Y-m-d', (time() - 86400));
        }
        $inputHtml .= '<input type="text" id="dateTimeInField" name="dateTimeIn" value="'.date('Y-m-d', strtotime($reportDate)).'" '.
            'placeholder="'.TEXT_REPORT_DATE_PLACEHOLDER.'" maxlength="255">';
        $inputHtml .= '&nbsp;&nbsp;';
        $inputHtml .= '<span class="ReportDataLink"><a href="/reports/rptDailyLogHome.php?dateInput=select" title="'.
            TEXT_SHOW_REPORT_DATE_LIST_TITLE.'">'.TEXT_SHOW_REPORT_DATE_LIST_LINK.'</a></span>';
    } else {
        $inputHtml .= '<select id="dateTimeInField" name="dateTimeIn" class="requiredField">';
        foreach ($dateArray as $dateElem) {
            $inputHtml .= '<option value="'.$dateElem['reportDate'].'"';
            if ($dateElem['reportDate'] == $reportDate) {
                $inputHtml .= ' selected=selected';
            }
            $inputHtml .= '>'.$dateElem['reportDate'].'</option>';
        }
        $inputHtml .= '</select>&nbsp;&nbsp;';
        $inputHtml .= '<span class="ReportDataLink"><a href="/reports/rptDailyLogHome.php?dateInput=text" title="'.
            TEXT_SHOW_REPORT_DATE_FIELD_TITLE.'">'.TEXT_SHOW_REPORT_DATE_FIELD_LINK.'</a></span>';
    }
    $inputHtml .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    return $inputHtml;
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
<?= piClinicAppMenu(null,$sessionInfo,  $pageLanguage, __FILE__) ?>
<div class="pageBody">
    <div id="DailyVisitListPrompt" class="noprint">
        <form enctype="application/x-www-form-urlencoded" action="/reports/rptDailyLogHome.php" method="get">
            <p>
                <label><?= TEXT_DATE_PROMPT_LABEL ?>:</label>&nbsp;
                <?= showDateSelect($reportDateList, $reportDate) ?>
                <label class="close"><?= TEXT_TYPE_LABEL ?>:</label><select id="visitTypeField" name="type" class="">
                    <?php
                    foreach ($visitTypes as $typeItem) {
                        echo '<option value="'.$typeItem[0].'"'.($reportTypeName == $typeItem[0] ? ' selected="selected"' : '').">".$typeItem[1]."</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label class="close"><?= TEXT_STAFF_LABEL ?>:</label><select id="posTypeField" name="filter" class="">
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
    <div id="NoDataMessage" class="<?= (!$noData ? 'hideDiv' : '') ?>">
        <p><?= TEXT_NO_REPORT_PROF_PROMPT ?></p>
    </div>
    <div id="DailyVisitList"  class="legalPortraitReport <?= ($noData ? ' hideDiv' : '') ?>">
        <div id="ATA-ReportHeading">
            <table id="ATA-LogoTable">
                <tr>
                    <td id="ReportHeading-LeftTitle">
                        <p><?= TEXT_ATA_LEFT_TITLE ?></p>
                    </td>
                    <td id="ReportHeading-CenterTitle">
                        <h1><?= TEXT_DAILY_VISIT_HEADING ?></h1>
                    </td>
                    <td rowspan="3" id="ReportHeading-RightInfo">
                        <div class="right">
                            <table id="serviceTypeTable">
                                <tr>
                                    <td rowspan="2" id="ServiceTypeTitle">
                                        <p><label><?= TEXT_REPORT_SERVICE_TYPE ?>:</label></p>
                                    </td>
                                    <td>
                                        <label class="close"><?= TEXT_REPORT_SERVICE_EXTERNAL ?>:</label><span class="boxed"><?= ($reportTypeName == 'Outpatient' ? 'X' : '&nbsp;&nbsp;' ) ?></span>&nbsp;&nbsp;&nbsp;
                                        <label class="close"><?= TEXT_REPORT_SERVICE_EMERGENCY ?>:</label><span class="boxed"><?= ($reportTypeName == 'Emergency' ? 'X' : '&nbsp;&nbsp;' )?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="close"><?= TEXT_REPORT_SERVICE_FILTRO ?>:</label><span class="boxed"><?= ((($reportTypeName != 'Outpatient') && ($reportTypeName != 'Emergency')) ? 'X' : '&nbsp;&nbsp;' )?></span>&nbsp;&nbsp;&nbsp;
                                        <label class="close"><?= TEXT_REPORT_SERVICE_SPECIALTY ?>:</label><span class="underline"><?= ((($reportTypeName != 'Outpatient') && ($reportTypeName != 'Emergency')) ? $reportTypeDisplay : $blankField )  ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="left">
                        <div class="headingField24">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_REPORT_CLINICNAME_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= (isset($clinicInfo['shortName']) ? $clinicInfo['shortName'] : $blankField ) ?>
                            </div>
                        </div>
                        <div class="headingField12">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_REPORT_CLINIC_CODE_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= (isset($clinicInfo['publicID']) ? $clinicInfo['publicID'] : $blankField ) ?>
                            </div>
                        </div>
                        <div class="headingField12">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_REPORT_CLINIC_TYPE_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= (isset($clinicInfo['typeCode']) ? $clinicInfo['typeCode'] : $blankField ) ?>
                            </div>
                        </div>
                        <div class="headingField24">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_REPORT_STATE_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= (isset($clinicInfo['clinicState']) ? $clinicInfo['clinicState'] : $blankField ) ?>
                            </div>
                        </div>
                        <div class="headingField24">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_REPORT_CITY_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= (isset($clinicInfo['clinicCity']) ? $clinicInfo['clinicCity'] : $blankField ) ?>
                            </div>
                        </div>
                        <div class="clearFloat"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="left">
                        <div class="headingField24">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_STAFF_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= $defaultReportProfType ?>
                            </div>
                        </div>
                        <div class="headingField36">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_STAFF_NAME_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= $defaultReportName ?>
                            </div>
                        </div>
                        <div class="headingField12">
                            <div class="headingLabel">
                                <label class="close"><?= TEXT_DATE_LABEL ?>:</label>
                            </div>
                            <div class="headingValue">
                                <?= date(TEXT_DATE_FORMAT, strtotime($reportDate)) ?>
                            </div>
                        </div>
                        <div class="clearFloat"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="ATA-ReportBody">
            <?php
            // check to see if they are currently in the clinic
            if (!empty($visitList)) {
                $tablestaffName = '';
                $reportLineNumber = 0;
                echo '<table class="report" id="dailyVisitTable">';
                echo '<tr>';
                echo '<th rowspan="3" class="ATAheading">'.TEXT_REPORT_ROW_LABEL.'</th>';
                echo '<th rowspan="3" class="ATAheading">'.TEXT_PATIENTVISITID_LABEL.'</th>';
                echo '<th rowspan="3" class="ATAheading">'.TEXT_FULLNAME_LABEL.'</th>';
                echo '<th rowspan="3" class="ATAheading">'.TEXT_CLINICPATIENTID_LABEL.'</th>';
                echo '<th rowspan="3" class="ATAheading nowrap vertical"><span>'.TEXT_SEX_LABEL.'</span></th>';
                echo '<th rowspan="3" class="ATAheading">'.TEXT_BIRTHDAY_DATE_LABEL.'</th>';
                echo '<th colspan="3" class="ATAheading">'.TEXT_REPORT_AGE_LABEL.'</th>';
                echo '<th rowspan="3" class="ATAheading vertical"><span>'.TEXT_PATIENT_LABEL.'</span></th>';
                echo '<th colspan="3" rowspan="2" class="ATAheading">'.TEXT_REPORT_ADDRESS_LABEL.'</th>';
                echo '<th colspan="6" class="ATAheading">'.TEXT_REPORT_DIAGNOSIS_LABEL.'</th>';
                echo '<th colspan="2" class="ATAheading">'.TEXT_REPORT_REFERRAL_LABEL.'</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<th rowspan="2" class="ATAheading vertical"><span>'.TEXT_AGE_YEARS_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading vertical"><span>'.TEXT_AGE_MONTHS_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading vertical"><span>'.TEXT_AGE_DAYS_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading nowrap">'.TEXT_DIAGNOSIS_1_LABEL.'</th>';
                echo '<th rowspan="2" class="ATAheading nowrap vertical"><span>'.TEXT_CONDITION_1_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading nowrap">'.TEXT_DIAGNOSIS_2_LABEL.'</th>';
                echo '<th rowspan="2" class="ATAheading nowrap vertical"><span>'.TEXT_CONDITION_2_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading nowrap">'.TEXT_DIAGNOSIS_3_LABEL.'</th>';
                echo '<th rowspan="2" class="ATAheading nowrap vertical"><span>'.TEXT_CONDITION_3_LABEL.'</span></th>';
                echo '<th rowspan="2" class="ATAheading nowrap">'.TEXT_REFERRED_TO_LABEL.'</th>';
                echo '<th rowspan="2" class="ATAheading nowrap">'.TEXT_REFERRED_FROM_LABEL .'</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<th class="ATAheading">'.TEXT_STATE_LABEL.'</th>';
                echo '<th class="ATAheading">'.TEXT_CITY_LABEL.'</th>';
                echo '<th class="ATAheading">'.TEXT_NEIGHBORHOOD_LABEL.'</th>';
                echo '</tr>';
                foreach ($visitList as $visit) {
                    echo '<tr>';
                    $reportLineNumber += 1;
                    echo '<td class="center nowrap">'.$reportLineNumber.'</td>';
                    echo '<td class="nowrap">'.
                        '<a href="/visitInfo.php?patientVisitID='.urlencode($visit['patientVisitID']).'" '.
                        'class="reportLink">'.(!empty($visit['patientFamilyID']) ? $visit['patientFamilyID'] : $visit['patientVisitID'] ).'</a></td>';
                    echo '<td class="med-wide">'.str_replace(' ', '&nbsp;', $visit['patientLastName']).',&nbsp;'.$visit['patientFirstName'].'</td>';
                    echo '<td class="nowrap">'.
                        '<a href="/ptInfo.php?clinicPatientID='.urlencode($visit['clinicPatientID']).'" '.
                        'class="reportLink">'.$visit['clinicPatientID'].'</a></td>';
                    echo '<td class="center nowrap">'.($visit['patientSex'] == 'M' ? TEXT_SEX_OPTION_M : ($visit['patientSex'] == 'F' ? TEXT_SEX_OPTION_F : TEXT_SEX_OPTION_X)).'</td>';
                    echo '<td class="nowrap">'.formatDbDate ($visit['patientBirthDate'], TEXT_DATE_FORMAT, '').'</td>';
                    $ageYMD = dateDiffYMD (strtotime($visit['patientBirthDate']),  strtotime($reportDate));
                    echo '<td class="numbers nowrap">'.($ageYMD['years'] > 0 ? $ageYMD['years'] : '').'</td>';
                    echo '<td class="numbers nowrap">'.((($ageYMD['years'] == 0) && ($ageYMD['months'] > 0)) ? $ageYMD['months'] : '').'</td>';
                    echo '<td class="numbers nowrap">'.((($ageYMD['years'] == 0) && ($ageYMD['months'] == 0) && ($ageYMD['days'] > 0)) ? $ageYMD['days'] : '').'</td>';
                    echo '<td class="center nowrap">'.($visit['firstVisit'] == "YES" ? 'N' : 'S').'</td>';
                    echo '<td class="med-wide">'.$visit['patientHomeState'].'</td>';
                    echo '<td class="med-wide">'.$visit['patientHomeCity'].'</td>';
                    echo '<td class="med-wide">'.$visit['patientHomeNeighborhood'].'</td>';
                    echo '<td class="wide">';
                    $displayText = '';
                    $displayClass = '';
                    if (!empty($visit['diagnosis1'])) {
                        $displayText = getIcdDescription ($dbLink, $visit['diagnosis1'], $pageLanguage);
                        if ($displayText == $visit['diagnosis1']) {
                            $displayClass = 'rawcodevalue';
                        }
                    } else {
                        $displayText = TEXT_DIAGNOSIS_BLANK;
                        $displayClass = 'inactive noprint';
                    }
                    echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
                    echo '</td>';
                    echo '<td class="center nowrap'.(empty($visit['condition1']) ? ' noprint' : '').'">'.conditionText($visit['condition1']).'</td>';
                    echo '<td class="wide">';
                    $displayText = '';
                    $displayClass = '';
                    if (!empty($visit['diagnosis2'])) {
                        $displayText = getIcdDescription ($dbLink, $visit['diagnosis2'], $pageLanguage);
                        if ($displayText == $visit['diagnosis2']) {
                            $displayClass = 'rawcodevalue';
                        }
                    } else {
                        $displayText = TEXT_DIAGNOSIS_BLANK;
                        $displayClass = 'inactive noprint';
                    }
                    echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
                    echo '</td>';
                    echo '<td class="center nowrap'.(empty($visit['condition2']) ? ' noprint' : '').'">'.conditionText($visit['condition2']).'</td>';
                    echo '<td class="wide">';
                    $displayText = '';
                    $displayClass = '';
                    if (!empty($visit['diagnosis3'])) {
                        $displayText = getIcdDescription ($dbLink, $visit['diagnosis3'], $pageLanguage);
                        if ($displayText == $visit['diagnosis3']) {
                            $displayClass = 'rawcodevalue';
                        }
                    } else {
                        $displayText = TEXT_DIAGNOSIS_BLANK;
                        $displayClass = 'inactive noprint';
                    }
                    echo ('<span class="'.$displayClass.'">'.$displayText.'</span>');
                    echo '</td>';
                    echo '<td class="center nowrap'.(empty($visit['condition3']) ? ' noprint' : '').'">'.conditionText($visit['condition3']).'</td>';
                    echo '<td class="nowrap">'.$visit['referredTo'].'</td>';
                    echo '<td class="nowrap">'.$visit['referredFrom'].'</td>';
                }
                echo '</table>';
                echo '<hr>';
            } else {
                // no visits
                echo '<p>'.TEXT_NO_VISITS_FOUND_DAY.'</p>';
            }
            ?>
        </div>
    </div>
</div>
</body>
<?php
if ($reportProfile['count'] >= 0) {
    $reportProfile['end'] = microtime(true);
    $reportProfile['date'] = date('Y-m-d H:i:s', floor($reportProfile['start']));
    $reportProfile['elapsed'] = $reportProfile['end'] - $reportProfile['start'];
    // only log actions that displayed a report
    $logResult = logReportWorkflow ($sessionInfo, $reportProfile, $dbLink);
}
@mysqli_close($dbLink);
?>
</html>
