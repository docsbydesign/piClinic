<?php
/*

 *
 *	Copyright (c) 2018, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */

// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	define('TEXT_CONDITION_BLANK','TEXT_CONDITION_BLANK',false);
	define('TEXT_CONDITION_NEW_SELECT','TEXT_CONDITION_NEW_SELECT',false);
	define('TEXT_CONDITION_SUBSEQUENT_SELECT','TEXT_CONDITION_SUBSEQUENT_SELECT',false);
	define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false);
	define('TEXT_VISIT_TYPE_EMERGENCY','TEXT_VISIT_TYPE_EMERGENCY',false);
	define('TEXT_VISIT_TYPE_OUTPATIENT','TEXT_VISIT_TYPE_OUTPATIENT',false);
	define('TEXT_VISIT_TYPE_SPECIALIST','TEXT_VISIT_TYPE_SPECIALIST',false);
	define('TEXT_VISIT_TYPE_TEST','TEXT_VISIT_TYPE_TEST',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_CONDITION_BLANK','(None)',false);
	define('TEXT_CONDITION_NEW_SELECT','N',false);
	define('TEXT_CONDITION_SUBSEQUENT_SELECT','S',false);
	define('TEXT_VISIT_TYPE_ALL','All',false);
	define('TEXT_VISIT_TYPE_EMERGENCY','Emergency',false);
	define('TEXT_VISIT_TYPE_OUTPATIENT','Outpatient',false);
	define('TEXT_VISIT_TYPE_SPECIALIST','Specialist',false);
	define('TEXT_VISIT_TYPE_TEST','Test',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_CONDITION_BLANK','(Ninguno)',false);
	define('TEXT_CONDITION_NEW_SELECT','N',false);
	define('TEXT_CONDITION_SUBSEQUENT_SELECT','S',false);
	define('TEXT_VISIT_TYPE_ALL','Todos',false);
	define('TEXT_VISIT_TYPE_EMERGENCY','Emergencia',false);
	define('TEXT_VISIT_TYPE_OUTPATIENT','Consulta externa',false);
	define('TEXT_VISIT_TYPE_SPECIALIST','Especialista',false);
	define('TEXT_VISIT_TYPE_TEST','Prueba',false);
}
//EOF
