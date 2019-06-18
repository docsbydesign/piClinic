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
