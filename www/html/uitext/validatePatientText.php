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

// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
$apiCommonInclude = dirname(__FILE__).'/../api/api_common.php';
if (!file_exists($apiCommonInclude)) {
    // if not over one, try up one more directory and then over.
    $apiCommonInclude = dirname(__FILE__).'/../../api/api_common.php';
    if (!file_exists($apiCommonInclude)) {
        // if not over one, try up one more directory and then over.
        $apiCommonInclude = dirname(__FILE__).'/../../../api/api_common.php';
    }
}
require_once $apiCommonInclude;
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID','TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID',false); }
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_VALID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_VALID','TEXT_PATIENT_CLINIC_ID_NOT_VALID',false); }
	if (!defined('TEXT_PATIENT_FAMILY_ID_NOT_VALID')) { define('TEXT_PATIENT_FAMILY_ID_NOT_VALID','TEXT_PATIENT_FAMILY_ID_NOT_VALID',false); }
	if (!defined('TEXT_PATIENT_FIELDS_MISSING')) { define('TEXT_PATIENT_FIELDS_MISSING','TEXT_PATIENT_FIELDS_MISSING',false); }
	if (!defined('TEXT_PATIENT_NOT_TESTED')) { define('TEXT_PATIENT_NOT_TESTED','TEXT_PATIENT_NOT_TESTED',false); }
	if (!defined('TEXT_PATIENT_VALID')) { define('TEXT_PATIENT_VALID','TEXT_PATIENT_VALID',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID','Patient ID doesn\'t match Family ID',false); }
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_VALID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_VALID','Patient ID is not formatted correctly',false); }
	if (!defined('TEXT_PATIENT_FAMILY_ID_NOT_VALID')) { define('TEXT_PATIENT_FAMILY_ID_NOT_VALID','Family ID is not formatted correctly',false); }
	if (!defined('TEXT_PATIENT_FIELDS_MISSING')) { define('TEXT_PATIENT_FIELDS_MISSING','Patient record is missing required fields',false); }
	if (!defined('TEXT_PATIENT_NOT_TESTED')) { define('TEXT_PATIENT_NOT_TESTED','The patient record was not validated',false); }
	if (!defined('TEXT_PATIENT_VALID')) { define('TEXT_PATIENT_VALID','The patient record is valid',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_IN_FAMILY_ID','Identidad del paciente no es egual al de  la carpeta (familia)',false); }
	if (!defined('TEXT_PATIENT_CLINIC_ID_NOT_VALID')) { define('TEXT_PATIENT_CLINIC_ID_NOT_VALID','Identidad del paciente no es en el formato correcto',false); }
	if (!defined('TEXT_PATIENT_FAMILY_ID_NOT_VALID')) { define('TEXT_PATIENT_FAMILY_ID_NOT_VALID','Carpeta (familia) no es en el formato correcto',false); }
	if (!defined('TEXT_PATIENT_FIELDS_MISSING')) { define('TEXT_PATIENT_FIELDS_MISSING','No se encuentran los datos requeridos del paciente',false); }
	if (!defined('TEXT_PATIENT_NOT_TESTED')) { define('TEXT_PATIENT_NOT_TESTED','Los datos del paciente no han sido revisados',false); }
	if (!defined('TEXT_PATIENT_VALID')) { define('TEXT_PATIENT_VALID','Los datos del paciente son validos',false); }
}
//EOF
