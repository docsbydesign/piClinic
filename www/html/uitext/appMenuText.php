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
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','TEXT_BLANK_STAFF_OPTION',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','TEXT_BLANK_VISIT_OPTION',false); }
	if (!defined('TEXT_CLINIC_ADMIN')) { define('TEXT_CLINIC_ADMIN','TEXT_CLINIC_ADMIN',false); }
	if (!defined('TEXT_CLINIC_COMMENT')) { define('TEXT_CLINIC_COMMENT','TEXT_CLINIC_COMMENT',false); }
	if (!defined('TEXT_CLINIC_COMMENT_TITLE')) { define('TEXT_CLINIC_COMMENT_TITLE','TEXT_CLINIC_COMMENT_TITLE',false); }
	if (!defined('TEXT_CLINIC_HELP')) { define('TEXT_CLINIC_HELP','TEXT_CLINIC_HELP',false); }
	if (!defined('TEXT_CLINIC_HOME')) { define('TEXT_CLINIC_HOME','TEXT_CLINIC_HOME',false); }
	if (!defined('TEXT_CLINIC_REPORTS')) { define('TEXT_CLINIC_REPORTS','TEXT_CLINIC_REPORTS',false); }
	if (!defined('TEXT_DATE_DAY_PLACEHOLDER')) { define('TEXT_DATE_DAY_PLACEHOLDER','TEXT_DATE_DAY_PLACEHOLDER',false); }
	if (!defined('TEXT_DATE_MONTH_PLACEHOLDER')) { define('TEXT_DATE_MONTH_PLACEHOLDER','TEXT_DATE_MONTH_PLACEHOLDER',false); }
	if (!defined('TEXT_DATE_YEAR_PLACEHOLDER')) { define('TEXT_DATE_YEAR_PLACEHOLDER','TEXT_DATE_YEAR_PLACEHOLDER',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Select the health professional)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Select the visit type)',false); }
	if (!defined('TEXT_CLINIC_ADMIN')) { define('TEXT_CLINIC_ADMIN','Admin',false); }
	if (!defined('TEXT_CLINIC_COMMENT')) { define('TEXT_CLINIC_COMMENT','Comment',false); }
	if (!defined('TEXT_CLINIC_COMMENT_TITLE')) { define('TEXT_CLINIC_COMMENT_TITLE','Comment on this page',false); }
	if (!defined('TEXT_CLINIC_HELP')) { define('TEXT_CLINIC_HELP','Help',false); }
	if (!defined('TEXT_CLINIC_HOME')) { define('TEXT_CLINIC_HOME','Dashboard',false); }
	if (!defined('TEXT_CLINIC_REPORTS')) { define('TEXT_CLINIC_REPORTS','Reports',false); }
	if (!defined('TEXT_DATE_DAY_PLACEHOLDER')) { define('TEXT_DATE_DAY_PLACEHOLDER','Day',false); }
	if (!defined('TEXT_DATE_MONTH_PLACEHOLDER')) { define('TEXT_DATE_MONTH_PLACEHOLDER','Month',false); }
	if (!defined('TEXT_DATE_YEAR_PLACEHOLDER')) { define('TEXT_DATE_YEAR_PLACEHOLDER','Year',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Escoge el profesional de salud)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Escoge el tipo de la atención)',false); }
	if (!defined('TEXT_CLINIC_ADMIN')) { define('TEXT_CLINIC_ADMIN','Admin',false); }
	if (!defined('TEXT_CLINIC_COMMENT')) { define('TEXT_CLINIC_COMMENT','Comentario',false); }
	if (!defined('TEXT_CLINIC_COMMENT_TITLE')) { define('TEXT_CLINIC_COMMENT_TITLE','Hacer un comentario de esta página',false); }
	if (!defined('TEXT_CLINIC_HELP')) { define('TEXT_CLINIC_HELP','Ayuda',false); }
	if (!defined('TEXT_CLINIC_HOME')) { define('TEXT_CLINIC_HOME','Página principal',false); }
	if (!defined('TEXT_CLINIC_REPORTS')) { define('TEXT_CLINIC_REPORTS','Informes',false); }
	if (!defined('TEXT_DATE_DAY_PLACEHOLDER')) { define('TEXT_DATE_DAY_PLACEHOLDER','Día',false); }
	if (!defined('TEXT_DATE_MONTH_PLACEHOLDER')) { define('TEXT_DATE_MONTH_PLACEHOLDER','Mes',false); }
	if (!defined('TEXT_DATE_YEAR_PLACEHOLDER')) { define('TEXT_DATE_YEAR_PLACEHOLDER','Año',false); }
}
//EOF
