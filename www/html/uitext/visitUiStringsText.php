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
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','TEXT_CONDITION_BLANK',false); }
	if (!defined('TEXT_CONDITION_NEW_REPORT')) { define('TEXT_CONDITION_NEW_REPORT','TEXT_CONDITION_NEW_REPORT',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','TEXT_CONDITION_NEW_SELECT',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','TEXT_CONDITION_SELECT',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_REPORT')) { define('TEXT_CONDITION_SUBSEQUENT_REPORT','TEXT_CONDITION_SUBSEQUENT_REPORT',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','TEXT_CONDITION_SUBSEQUENT_SELECT',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','TEXT_DIAGNOSIS_LOADING',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','TEXT_VISIT_TYPE_EMERGENCY',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','TEXT_VISIT_TYPE_OUTPATIENT',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','TEXT_VISIT_TYPE_SPECIALIST',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','TEXT_VISIT_TYPE_TEST',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','(None)',false); }
	if (!defined('TEXT_CONDITION_NEW_REPORT')) { define('TEXT_CONDITION_NEW_REPORT','N',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','New diagnosis',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','(Select new or subsequent)',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_REPORT')) { define('TEXT_CONDITION_SUBSEQUENT_REPORT','S',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','Subsequent diagnosis',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','Loading...',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','All',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','Emergency',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','Outpatient',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','Specialist',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','Test',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','(Ninguno)',false); }
	if (!defined('TEXT_CONDITION_NEW_REPORT')) { define('TEXT_CONDITION_NEW_REPORT','N',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','Nuevo diagnóstico',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','(Seleccione nuevo o subsiguiente)',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_REPORT')) { define('TEXT_CONDITION_SUBSEQUENT_REPORT','S',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','Subsiguiente diagnóstico',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','Cargando...',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','Todos',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','Emergencia',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','Consulta externa',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','Especialista',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','Prueba',false); }
}
//EOF
