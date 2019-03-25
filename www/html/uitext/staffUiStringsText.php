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
    // it should be in one of these two locations.
    $apiCommonInclude = dirname(__FILE__).'/../../api/api_common.php';
}
require_once $apiCommonInclude;
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	if (!defined('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF')) { define('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF','TEXT_STAFF_POSITION_OPTION_CLINICSTAFF',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL','TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST','TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT','TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSE')) { define('TEXT_STAFF_POSITION_OPTION_NURSE','TEXT_STAFF_POSITION_OPTION_NURSE',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSESAID')) { define('TEXT_STAFF_POSITION_OPTION_NURSESAID','TEXT_STAFF_POSITION_OPTION_NURSESAID',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT','TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_OTHER')) { define('TEXT_STAFF_POSITION_OPTION_OTHER','TEXT_STAFF_POSITION_OPTION_OTHER',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF')) { define('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF','Clinic staff',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL','Doctor-general',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST','Doctor-specialist',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT','Medical student',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSE')) { define('TEXT_STAFF_POSITION_OPTION_NURSE','Nurse',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSESAID')) { define('TEXT_STAFF_POSITION_OPTION_NURSESAID','Nurse\'s aid',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT','Nursing student',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_OTHER')) { define('TEXT_STAFF_POSITION_OPTION_OTHER','Other staff',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF')) { define('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF','Personal clínica',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL','Medico-general',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST','Medico-especialista',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT','Estudiante de medicina',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSE')) { define('TEXT_STAFF_POSITION_OPTION_NURSE','Enfermera-profesional',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSESAID')) { define('TEXT_STAFF_POSITION_OPTION_NURSESAID','Enefermera-auxiliar',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT','Estudiante de enfermeria',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_OTHER')) { define('TEXT_STAFF_POSITION_OPTION_OTHER','Otro personal',false); }
}
//EOF
