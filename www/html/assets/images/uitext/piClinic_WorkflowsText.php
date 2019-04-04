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
	if (!defined('TEXT_HELP_WORK_ADD_PT')) { define('TEXT_HELP_WORK_ADD_PT','TEXT_HELP_WORK_ADD_PT',false); }
	if (!defined('TEXT_HELP_WORK_ADMIT_PT')) { define('TEXT_HELP_WORK_ADMIT_PT','TEXT_HELP_WORK_ADMIT_PT',false); }
	if (!defined('TEXT_HELP_WORK_DISCHARGE_PT')) { define('TEXT_HELP_WORK_DISCHARGE_PT','TEXT_HELP_WORK_DISCHARGE_PT',false); }
	if (!defined('TEXT_HELP_WORK_FIND_PT')) { define('TEXT_HELP_WORK_FIND_PT','TEXT_HELP_WORK_FIND_PT',false); }
	if (!defined('TEXT_HELP_WORK_UPDATE_PT')) { define('TEXT_HELP_WORK_UPDATE_PT','TEXT_HELP_WORK_UPDATE_PT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_HELP_WORK_ADD_PT')) { define('TEXT_HELP_WORK_ADD_PT','Add a new patient',false); }
	if (!defined('TEXT_HELP_WORK_ADMIT_PT')) { define('TEXT_HELP_WORK_ADMIT_PT','Admit a bpatient to clinic',false); }
	if (!defined('TEXT_HELP_WORK_DISCHARGE_PT')) { define('TEXT_HELP_WORK_DISCHARGE_PT','Discharge a patient',false); }
	if (!defined('TEXT_HELP_WORK_FIND_PT')) { define('TEXT_HELP_WORK_FIND_PT','Search for a patient',false); }
	if (!defined('TEXT_HELP_WORK_UPDATE_PT')) { define('TEXT_HELP_WORK_UPDATE_PT','Update patient info',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_HELP_WORK_ADD_PT')) { define('TEXT_HELP_WORK_ADD_PT','Agregar un paciente nuevo',false); }
	if (!defined('TEXT_HELP_WORK_ADMIT_PT')) { define('TEXT_HELP_WORK_ADMIT_PT','Abra una visita nueva para un paciente',false); }
	if (!defined('TEXT_HELP_WORK_DISCHARGE_PT')) { define('TEXT_HELP_WORK_DISCHARGE_PT','Dar de alta un paciente',false); }
	if (!defined('TEXT_HELP_WORK_FIND_PT')) { define('TEXT_HELP_WORK_FIND_PT','Buscar un paciente',false); }
	if (!defined('TEXT_HELP_WORK_UPDATE_PT')) { define('TEXT_HELP_WORK_UPDATE_PT','Actualizar un paciente',false); }
}
//EOF
