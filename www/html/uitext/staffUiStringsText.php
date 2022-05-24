<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
	if (!defined('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF')) { define('TEXT_STAFF_POSITION_OPTION_CLINICSTAFF','Personal clínico',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL','Médico-general',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST')) { define('TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST','Médico-especialista',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT','Estudiante de medicina',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSE')) { define('TEXT_STAFF_POSITION_OPTION_NURSE','Enfermera-profesional',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSESAID')) { define('TEXT_STAFF_POSITION_OPTION_NURSESAID','Enfermera-auxiliar',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT')) { define('TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT','Estudiante de enfermería',false); }
	if (!defined('TEXT_STAFF_POSITION_OPTION_OTHER')) { define('TEXT_STAFF_POSITION_OPTION_OTHER','Otro personal',false); }
}
//EOF
