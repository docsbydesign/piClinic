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
