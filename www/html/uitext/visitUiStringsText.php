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
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','TEXT_CONDITION_BLANK',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','TEXT_CONDITION_NEW_SELECT',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','TEXT_CONDITION_SELECT',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','TEXT_CONDITION_SUBSEQUENT_SELECT',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','TEXT_DIAGNOSIS_LOADING',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','TEXT_VISIT_TYPE_EMERGENCY',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','TEXT_VISIT_TYPE_OUTPATIENT',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','TEXT_VISIT_TYPE_SPECIALIST',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','TEXT_VISIT_TYPE_TEST',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','(None)',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','N',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','(Select new or subsequent)',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','S',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','Loading...',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','All',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','Emergency',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','Outpatient',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','Specialist',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','Test',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_CONDITION_BLANK')) { define('TEXT_CONDITION_BLANK','(Ninguno)',false); }
	if (!defined('TEXT_CONDITION_NEW_SELECT')) { define('TEXT_CONDITION_NEW_SELECT','N',false); }
	if (!defined('TEXT_CONDITION_SELECT')) { define('TEXT_CONDITION_SELECT','(Escoge nuevo o subsiguiente)',false); }
	if (!defined('TEXT_CONDITION_SUBSEQUENT_SELECT')) { define('TEXT_CONDITION_SUBSEQUENT_SELECT','S',false); }
	if (!defined('TEXT_DIAGNOSIS_LOADING')) { define('TEXT_DIAGNOSIS_LOADING','Cargando...',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','Todos',false); }
	if (!defined('TEXT_VISIT_TYPE_EMERGENCY')) { define('TEXT_VISIT_TYPE_EMERGENCY','Emergencia',false); }
	if (!defined('TEXT_VISIT_TYPE_OUTPATIENT')) { define('TEXT_VISIT_TYPE_OUTPATIENT','Consulta externa',false); }
	if (!defined('TEXT_VISIT_TYPE_SPECIALIST')) { define('TEXT_VISIT_TYPE_SPECIALIST','Especialista',false); }
	if (!defined('TEXT_VISIT_TYPE_TEST')) { define('TEXT_VISIT_TYPE_TEST','Prueba',false); }
}
//EOF
