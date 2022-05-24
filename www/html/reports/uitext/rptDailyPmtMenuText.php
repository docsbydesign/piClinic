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
	if (!defined('TEXT_REPORT_GROUP_FINANCE')) { define('TEXT_REPORT_GROUP_FINANCE','TEXT_REPORT_GROUP_FINANCE',false); }
	if (!defined('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG','TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG',false); }
	if (!defined('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG','TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG',false); }
	if (!defined('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG','TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG',false); }
	if (!defined('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG','TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_REPORT_GROUP_FINANCE')) { define('TEXT_REPORT_GROUP_FINANCE','Financial Reports',false); }
	if (!defined('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG','List of payments received from patients on a specified date',false); }
	if (!defined('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG','Daily Payment Log',false); }
	if (!defined('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG','Daily payment log',false); }
	if (!defined('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG','',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_REPORT_GROUP_FINANCE')) { define('TEXT_REPORT_GROUP_FINANCE','Informes Financieros',false); }
	if (!defined('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_DESC_DAILY_PAYMENT_LOG','Registro de pagos recibidos por los pacientes durante la fecha especificada',false); }
	if (!defined('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TEXT_DAILY_PAYMENT_LOG','Registros de Pagos del Día',false); }
	if (!defined('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_TITLE_DAILY_PAYMENT_LOG','Registro de pagos del día',false); }
	if (!defined('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG')) { define('TEXT_REPORT_LINK_URI_DAILY_PAYMENT_LOG','',false); }
}
//EOF
