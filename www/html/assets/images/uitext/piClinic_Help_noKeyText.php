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
