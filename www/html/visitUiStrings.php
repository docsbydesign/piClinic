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
/**
 * Created by PhpStorm.
 * User: BobW
 * Date: 3/6/2018
 * Time: 5:12 PM
 */
/* These strings are used in the UI and must be included after the language specific strings */
/* Define staff position values */
require_once ('./uitext/visitUiStringsText.php');

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
	return NOT_SPECIFIED;
}

function writeDiagnosisDataBlock ($sessionInfo, $dbLink, $visitInfo, $index, $diagnosisLabel, $diagnosisPlaceholder) {
    assert (($index == 1) || ($index == 2) || ($index == 3));
    $conditionIndex = 'condition'.strval($index);
    $diagnosisField = 'diagnosis'.strval($index);
    echo '<div class="dataBlock">'."\n";
	echo '  <p><label class="close">'.$diagnosisLabel.':</label>'."\n";
    echo '  <select id="Condition'.strval($index).'Select" name="Condition'.strval($index).'">'."\n";
    echo '      <option value="" '.(empty($visitInfo[$conditionIndex])  ? "selected" : "").'>'.TEXT_CONDITION_SELECT.'</option>'."\n";
    echo '	    <option value="NEWDIAG" '.((!empty($visitInfo[$conditionIndex]) && $visitInfo[$conditionIndex] == 'NEWDIAG') ? "selected" : "" ).'>'.TEXT_CONDITION_NEW_SELECT. '</option>'."\n";
	echo '      <option value="SUBSDIAG" '.((!empty($visitInfo[$conditionIndex]) && $visitInfo[$conditionIndex] == 'SUBSDIAG') ? "selected" : "" ).'>'.TEXT_CONDITION_SUBSEQUENT_SELECT. '</option>'."\n";
    echo '  </select>'."\n";
	echo '  <br>'.showDiagnosisInput ($dbLink, $visitInfo, $diagnosisField, $sessionInfo['pageLanguage'], $diagnosisPlaceholder, TEXT_DIAGNOSIS_LOADING, 'piClinicEdit fullWidth').'</p>'."\n";
	echo '</div>'."\n";
    return;
}

