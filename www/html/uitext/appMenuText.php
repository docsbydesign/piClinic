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
	define('TEXT_BLANK_STAFF_OPTION','TEXT_BLANK_STAFF_OPTION',false);
	define('TEXT_BLANK_VISIT_OPTION','TEXT_BLANK_VISIT_OPTION',false);
	define('TEXT_CLINIC_ADMIN','TEXT_CLINIC_ADMIN',false);
	define('TEXT_CLINIC_COMMENT','TEXT_CLINIC_COMMENT',false);
	define('TEXT_CLINIC_COMMENT_TITLE','TEXT_CLINIC_COMMENT_TITLE',false);
	define('TEXT_CLINIC_HELP','TEXT_CLINIC_HELP',false);
	define('TEXT_CLINIC_HOME','TEXT_CLINIC_HOME',false);
	define('TEXT_CLINIC_REPORTS','TEXT_CLINIC_REPORTS',false);
	define('TEXT_DATE_DAY_PLACEHOLDER','TEXT_DATE_DAY_PLACEHOLDER',false);
	define('TEXT_DATE_MONTH_PLACEHOLDER','TEXT_DATE_MONTH_PLACEHOLDER',false);
	define('TEXT_DATE_YEAR_PLACEHOLDER','TEXT_DATE_YEAR_PLACEHOLDER',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_BLANK_STAFF_OPTION','(Select the health professional)',false);
	define('TEXT_BLANK_VISIT_OPTION','(Select the visit type)',false);
	define('TEXT_CLINIC_ADMIN','Admin',false);
	define('TEXT_CLINIC_COMMENT','Comment',false);
	define('TEXT_CLINIC_COMMENT_TITLE','Comment on this page',false);
	define('TEXT_CLINIC_HELP','Help',false);
	define('TEXT_CLINIC_HOME','Dashboard',false);
	define('TEXT_CLINIC_REPORTS','Reports',false);
	define('TEXT_DATE_DAY_PLACEHOLDER','Day',false);
	define('TEXT_DATE_MONTH_PLACEHOLDER','Month',false);
	define('TEXT_DATE_YEAR_PLACEHOLDER','Year',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_BLANK_STAFF_OPTION','(Escoge el profesional de salud)',false);
	define('TEXT_BLANK_VISIT_OPTION','(Escoge el tipo de la atención)',false);
	define('TEXT_CLINIC_ADMIN','Admin',false);
	define('TEXT_CLINIC_COMMENT','Comentario',false);
	define('TEXT_CLINIC_COMMENT_TITLE','Hacer un comentario de esta página',false);
	define('TEXT_CLINIC_HELP','Ayuda',false);
	define('TEXT_CLINIC_HOME','Página principal',false);
	define('TEXT_CLINIC_REPORTS','Informes',false);
	define('TEXT_DATE_DAY_PLACEHOLDER','Día',false);
	define('TEXT_DATE_MONTH_PLACEHOLDER','Mes',false);
	define('TEXT_DATE_YEAR_PLACEHOLDER','Año',false);
}
//EOF
