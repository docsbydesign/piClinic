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
// include files
require_once dirname(__FILE__).'/./shared/piClinicConfig.php';
require_once dirname(__FILE__).'/./shared/dbUtils.php';
require_once dirname(__FILE__).'/./api/api_common.php';

/*
 *  Review the fields in $ptRecord and test them per the $validationOption
 */

function validatePatient ($ptRecord, $validationType=PT_VALIDATE_NEW, $validationOption = PT_VALIDATE_NONE) {
    $validationResponse = [];
    $validationResponse['valid'] = true;
    $validationResponse['message'] = TEXT_PATIENT_VALID;

    switch ($validationOption) {
        case PT_VALIDATE_NAME_SERIAL:
        case PT_VALIDATE_CITY_SERIAL:
            if (!empty($ptRecord['familyID']) && !empty($ptRecord['clinicPatientID'])) {
                // test for patient ID format of Text-Number in family ID
                // and Text-Number-Number in Patient ID
                $familyIdPattern = '/([a-záéíóúñ0-9 ]+-[\d]+)/i';
                $patientIdPattern = '/([a-záéíóúñ0-9 ]+-[\d]+)-[\d]+/i';
                if (!preg_match ( $familyIdPattern, $ptRecord['familyID'])) {
                    $validationResponse['valid'] = false;
                    $validationResponse['message'] = TEXT_PATIENT_FAMILY_ID_NOT_VALID;
                } else if (!preg_match ( $patientIdPattern, $ptRecord['clinicPatientID'])) {
                    $validationResponse['valid'] = false;
                    $validationResponse['message'] = TEXT_PATIENT_CLINIC_ID_NOT_VALID;
                }

                if ($validationResponse['valid'] && ($validationType == PT_VALIDATE_NEW)){
                    // make sure the family ID part of the patient ID matches the Family ID on new entries
                    preg_match($familyIdPattern, $ptRecord['familyID'], $familyIdMatches);
                    preg_match($patientIdPattern, $ptRecord['clinicPatientID'], $patientIdMatches);
                    if (isset($familyIdMatches[1]) && isset($patientIdMatches[1])) {
                        if ($familyIdMatches[1] != $patientIdMatches[1]) {
                            $validationResponse['valid'] = false;
                            $validationResponse['message'] = TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID;
                        } // else they match so keep the default success value
                    } else {
                        // didn't match
                        $validationResponse['valid'] = false;
                        $validationResponse['message'] = TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID;
                    }
                }
            } else {
                $validationResponse['valid'] = false;
                $validationResponse['message'] = TEXT_PATIENT_FIELDS_MISSING;
            }
            break;

        case PT_VALIDATE_NONE:
        default:
            $validationResponse['valid'] = true;
            $validationResponse['message'] = TEXT_PATIENT_NOT_TESTED;
            break;
    }
    return $validationResponse;
}