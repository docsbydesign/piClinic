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
	if (!defined('TEXT_HELP_DOCS_MISSING')) { define('TEXT_HELP_DOCS_MISSING','TEXT_HELP_DOCS_MISSING',false); }
	if (!defined('TEXT_HELP_DOC_NOT_FOUND')) { define('TEXT_HELP_DOC_NOT_FOUND','TEXT_HELP_DOC_NOT_FOUND',false); }
	if (!defined('TEXT_HELP_ENGLISH_DOC_HEADING')) { define('TEXT_HELP_ENGLISH_DOC_HEADING','TEXT_HELP_ENGLISH_DOC_HEADING',false); }
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','TEXT_HELP_ICD',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','TEXT_HELP_INTRODUCTION',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','TEXT_HELP_MENU_HOME',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','TEXT_HELP_MENU_ICD',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','TEXT_HELP_MENU_PROMPT',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','TEXT_HELP_MENU_WORKFLOW',false); }
	if (!defined('TEXT_HELP_SPANISH_DOC_HEADING')) { define('TEXT_HELP_SPANISH_DOC_HEADING','TEXT_HELP_SPANISH_DOC_HEADING',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','TEXT_HELP_WORKFLOW',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','TEXT_PICLINIC_HELP_PAGE_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_HELP_DOCS_MISSING')) { define('TEXT_HELP_DOCS_MISSING','See the system\'s administrator about the ICD-10 references.',false); }
	if (!defined('TEXT_HELP_DOC_NOT_FOUND')) { define('TEXT_HELP_DOC_NOT_FOUND','Some documents aren\'t installed.',false); }
	if (!defined('TEXT_HELP_ENGLISH_DOC_HEADING')) { define('TEXT_HELP_ENGLISH_DOC_HEADING','English references',false); }
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','ICD-10 reference documents',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','Map of piClinic screens',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','piClinic screens',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','ICD-10 codes',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','Help with',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','piClinic Tasks',false); }
	if (!defined('TEXT_HELP_SPANISH_DOC_HEADING')) { define('TEXT_HELP_SPANISH_DOC_HEADING','Spanish references',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','piClinic Tasks',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','piClinic help',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_HELP_DOCS_MISSING')) { define('TEXT_HELP_DOCS_MISSING','Hable con el administrador del sistema sobre las referencias del CIE-10.',false); }
	if (!defined('TEXT_HELP_DOC_NOT_FOUND')) { define('TEXT_HELP_DOC_NOT_FOUND','Algunos documentos no están instalados.',false); }
	if (!defined('TEXT_HELP_ENGLISH_DOC_HEADING')) { define('TEXT_HELP_ENGLISH_DOC_HEADING','Referencias en ingles',false); }
	if (!defined('TEXT_HELP_ICD')) { define('TEXT_HELP_ICD','Referencias del código CIE-10',false); }
	if (!defined('TEXT_HELP_INTRODUCTION')) { define('TEXT_HELP_INTRODUCTION','Mapa de las pantallas de la consola piClinic',false); }
	if (!defined('TEXT_HELP_MENU_HOME')) { define('TEXT_HELP_MENU_HOME','Pantallas de la consola',false); }
	if (!defined('TEXT_HELP_MENU_ICD')) { define('TEXT_HELP_MENU_ICD','Códigos del CIE-10',false); }
	if (!defined('TEXT_HELP_MENU_PROMPT')) { define('TEXT_HELP_MENU_PROMPT','Ayuda con',false); }
	if (!defined('TEXT_HELP_MENU_WORKFLOW')) { define('TEXT_HELP_MENU_WORKFLOW','Tareas en la consola',false); }
	if (!defined('TEXT_HELP_SPANISH_DOC_HEADING')) { define('TEXT_HELP_SPANISH_DOC_HEADING','Referencias en español',false); }
	if (!defined('TEXT_HELP_WORKFLOW')) { define('TEXT_HELP_WORKFLOW','Tareas de la consola piClinic',false); }
	if (!defined('TEXT_PICLINIC_HELP_PAGE_TITLE')) { define('TEXT_PICLINIC_HELP_PAGE_TITLE','Ayuda para el piClinic',false); }
}
//EOF
