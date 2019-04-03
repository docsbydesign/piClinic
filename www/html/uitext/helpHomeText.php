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
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','TEXT_HELP_ICD',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','TEXT_HELP_INTRODUCTION',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','TEXT_HELP_MENU_HOME',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','TEXT_HELP_MENU_ICD',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','TEXT_HELP_MENU_PROMPT',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','TEXT_HELP_MENU_WORKFLOW',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','TEXT_HELP_WORKFLOW',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','TEXT_PICLINIC_HELP_PAGE_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','ICD-10 reference documents',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','Map of piClinic screens',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','piClinic screens',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','ICD-10 codes',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','Help with',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','piClinic Tasks',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','piClinic Tasks',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','piClinic help',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','Referencias del codigo CIE-10',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','Mapa de las pantallas de la consola piClinic',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','Pantallas de la consola',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','Codigos del CIE-10',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','Ayuda con',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','Tareas en la consola',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','Tareas de la consola piClinic',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','Ayuda para el piClinic',false); }
}
//EOF
