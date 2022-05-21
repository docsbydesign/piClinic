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
	if (!defined('TEXT_HELP_NOKEY_ADD')) { define('TEXT_HELP_NOKEY_ADD','TEXT_HELP_NOKEY_ADD',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIN')) { define('TEXT_HELP_NOKEY_ADMIN','TEXT_HELP_NOKEY_ADMIN',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIT')) { define('TEXT_HELP_NOKEY_ADMIT','TEXT_HELP_NOKEY_ADMIT',false); }
	if (!defined('TEXT_HELP_NOKEY_DASHBOARD')) { define('TEXT_HELP_NOKEY_DASHBOARD','TEXT_HELP_NOKEY_DASHBOARD',false); }
	if (!defined('TEXT_HELP_NOKEY_DISCHARGE')) { define('TEXT_HELP_NOKEY_DISCHARGE','TEXT_HELP_NOKEY_DISCHARGE',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT')) { define('TEXT_HELP_NOKEY_EDIT','TEXT_HELP_NOKEY_EDIT',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS')) { define('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS','TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS',false); }
	if (!defined('TEXT_HELP_NOKEY_FIND_PATIENT')) { define('TEXT_HELP_NOKEY_FIND_PATIENT','TEXT_HELP_NOKEY_FIND_PATIENT',false); }
	if (!defined('TEXT_HELP_NOKEY_PATIENT_INFO')) { define('TEXT_HELP_NOKEY_PATIENT_INFO','TEXT_HELP_NOKEY_PATIENT_INFO',false); }
	if (!defined('TEXT_HELP_NOKEY_REPORTS')) { define('TEXT_HELP_NOKEY_REPORTS','TEXT_HELP_NOKEY_REPORTS',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_HELP_NOKEY_ADD')) { define('TEXT_HELP_NOKEY_ADD','Add new patient',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIN')) { define('TEXT_HELP_NOKEY_ADMIN','System administration',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIT')) { define('TEXT_HELP_NOKEY_ADMIT','Admit patient',false); }
	if (!defined('TEXT_HELP_NOKEY_DASHBOARD')) { define('TEXT_HELP_NOKEY_DASHBOARD','Clinic information',false); }
	if (!defined('TEXT_HELP_NOKEY_DISCHARGE')) { define('TEXT_HELP_NOKEY_DISCHARGE','Discharge this patient',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT')) { define('TEXT_HELP_NOKEY_EDIT','Edit patient info',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS')) { define('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS','Edit visit info',false); }
	if (!defined('TEXT_HELP_NOKEY_FIND_PATIENT')) { define('TEXT_HELP_NOKEY_FIND_PATIENT','Find patient',false); }
	if (!defined('TEXT_HELP_NOKEY_PATIENT_INFO')) { define('TEXT_HELP_NOKEY_PATIENT_INFO','View patient info',false); }
	if (!defined('TEXT_HELP_NOKEY_REPORTS')) { define('TEXT_HELP_NOKEY_REPORTS','Reports',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_HELP_NOKEY_ADD')) { define('TEXT_HELP_NOKEY_ADD','Agregar paciente nuevo',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIN')) { define('TEXT_HELP_NOKEY_ADMIN','Administración del sistema',false); }
	if (!defined('TEXT_HELP_NOKEY_ADMIT')) { define('TEXT_HELP_NOKEY_ADMIT','Abra una visita nueva',false); }
	if (!defined('TEXT_HELP_NOKEY_DASHBOARD')) { define('TEXT_HELP_NOKEY_DASHBOARD','Información de la clínica',false); }
	if (!defined('TEXT_HELP_NOKEY_DISCHARGE')) { define('TEXT_HELP_NOKEY_DISCHARGE','Dar de alta este paciente',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT')) { define('TEXT_HELP_NOKEY_EDIT','Actualizar información del paciente',false); }
	if (!defined('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS')) { define('TEXT_HELP_NOKEY_EDIT_VISIT_DETAILS','Actualizar información de la visita',false); }
	if (!defined('TEXT_HELP_NOKEY_FIND_PATIENT')) { define('TEXT_HELP_NOKEY_FIND_PATIENT','Buscar paciente',false); }
	if (!defined('TEXT_HELP_NOKEY_PATIENT_INFO')) { define('TEXT_HELP_NOKEY_PATIENT_INFO','Revisar información del paciente',false); }
	if (!defined('TEXT_HELP_NOKEY_REPORTS')) { define('TEXT_HELP_NOKEY_REPORTS','Informes',false); }
}
//EOF
