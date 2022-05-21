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
