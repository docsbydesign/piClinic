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
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Seleccione el profesional de salud)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Seleccione el tipo de la atención)',false); }
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
