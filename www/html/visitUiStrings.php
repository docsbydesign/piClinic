<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
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
/* These strings are used in the UI and must be included after the language specific strings */
/* Define staff position values */
require_once dirname(__FILE__).'/./uitext/visitUiStringsText.php';

// [DB Value, Display String, Usage]
//
//  usage: VISIT_TYPE_REPORT = show in reports
//          VISIT_TYPE_EDIT = show in data entry
DEFINE('VISIT_TYPE_REPORT',1,false);
DEFINE('VISIT_TYPE_EDIT',2, false);

// referenced here so string definer will pick them up
$unused = TEXT_CONDITION_BLANK;
$unused = TEXT_CONDITION_NEW_SELECT;
$unused = TEXT_CONDITION_SUBSEQUENT_SELECT;
$unused = TEXT_CONDITION_NEW_REPORT;
$unused = TEXT_CONDITION_SUBSEQUENT_REPORT;

$visitTypes = [
	['All', TEXT_VISIT_TYPE_ALL, VISIT_TYPE_REPORT],
	['Outpatient', TEXT_VISIT_TYPE_OUTPATIENT,(VISIT_TYPE_EDIT + VISIT_TYPE_REPORT)],
	['Emergency', TEXT_VISIT_TYPE_EMERGENCY,(VISIT_TYPE_EDIT + VISIT_TYPE_REPORT)],
	['Specialist', TEXT_VISIT_TYPE_SPECIALIST,(VISIT_TYPE_EDIT + VISIT_TYPE_REPORT)],
	['Test', TEXT_VISIT_TYPE_TEST,(VISIT_TYPE_EDIT + VISIT_TYPE_REPORT)]
];

function showVisitType ($visitType, $usage) {
    if (isset($visitType[2])) {
        return (($visitType[2] & $usage) == $usage);
    }
}

function showVisitTypeString($visitTypeID, $visitTypeArray) {
	if (isset($visitTypeID)) {
		foreach ($visitTypeArray as $thisType) {
			if ($visitTypeID == $thisType[0]) {
				return $thisType[1];
			}
		}
		// if no match found return the ID string
		return ($visitTypeID);
	}
	// no valid parameter so return an empty string
	return TEXT_NOT_SPECIFIED;
}

function writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, $index, $diagnosisLabel, $diagnosisPlaceholder) {
    assert (($index == 0) ||($index == 1) || ($index == 2) || ($index == 3));
    $conditionIndex = 'condition'.strval($index);
    $diagnosisField = 'diagnosis'.strval($index);
    $returnString = '<div class="dataBlock">'."\n";
    $returnString .= '  <p><label class="close">'.$diagnosisLabel.':</label>'."\n";
    if ($index > 0) {
        $returnString .= '  <select id="Condition'.strval($index).'Select" name="condition'.strval($index).'">'."\n";
        $returnString .= '      <option value="" '.(empty($visitInfo[$conditionIndex])  ? "selected" : "").'>'.TEXT_CONDITION_SELECT.'</option>'."\n";
        $returnString .= '	    <option value="NEWDIAG" '.((!empty($visitInfo[$conditionIndex]) && $visitInfo[$conditionIndex] == 'NEWDIAG') ? "selected" : "" ).'>'.TEXT_CONDITION_NEW_SELECT. '</option>'."\n";
        $returnString .= '      <option value="SUBSDIAG" '.((!empty($visitInfo[$conditionIndex]) && $visitInfo[$conditionIndex] == 'SUBSDIAG') ? "selected" : "" ).'>'.TEXT_CONDITION_SUBSEQUENT_SELECT. '</option>'."\n";
        $returnString .= '  </select>'."\n";
        $returnString .= '  <br>';
    } else {
        $diagnosisField = 'diag';
    }
    $returnString .= showDiagnosisInput ($dbLink, $visitInfo[$diagnosisField], $diagnosisField, $sessionInfo, $diagnosisPlaceholder, TEXT_DIAGNOSIS_LOADING, 'piClinicEdit fullWidth').'</p>'."\n";
    $returnString .= '</div>'."\n";
    return $returnString;
}
