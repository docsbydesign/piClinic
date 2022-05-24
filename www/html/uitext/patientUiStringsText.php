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
	if (!defined('TEXT_DIVORCED_OPTION')) { define('TEXT_DIVORCED_OPTION','TEXT_DIVORCED_OPTION',false); }
	if (!defined('TEXT_ENGAGED_OPTION')) { define('TEXT_ENGAGED_OPTION','TEXT_ENGAGED_OPTION',false); }
	if (!defined('TEXT_LIVINGTOGETHER_OPTION')) { define('TEXT_LIVINGTOGETHER_OPTION','TEXT_LIVINGTOGETHER_OPTION',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','TEXT_MARRIED_OPTION',false); }
	if (!defined('TEXT_OTHER_STATUS_OPTION')) { define('TEXT_OTHER_STATUS_OPTION','TEXT_OTHER_STATUS_OPTION',false); }
	if (!defined('TEXT_SEPARATED_OPTION')) { define('TEXT_SEPARATED_OPTION','TEXT_SEPARATED_OPTION',false); }
	if (!defined('TEXT_SINGLE_OPTION')) { define('TEXT_SINGLE_OPTION','TEXT_SINGLE_OPTION',false); }
	if (!defined('TEXT_WIDOWED_OPTION')) { define('TEXT_WIDOWED_OPTION','TEXT_WIDOWED_OPTION',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_DIVORCED_OPTION')) { define('TEXT_DIVORCED_OPTION','Divorced',false); }
	if (!defined('TEXT_ENGAGED_OPTION')) { define('TEXT_ENGAGED_OPTION','Engaged',false); }
	if (!defined('TEXT_LIVINGTOGETHER_OPTION')) { define('TEXT_LIVINGTOGETHER_OPTION','Living together (not married)',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','Married',false); }
	if (!defined('TEXT_OTHER_STATUS_OPTION')) { define('TEXT_OTHER_STATUS_OPTION','Other',false); }
	if (!defined('TEXT_SEPARATED_OPTION')) { define('TEXT_SEPARATED_OPTION','Separated',false); }
	if (!defined('TEXT_SINGLE_OPTION')) { define('TEXT_SINGLE_OPTION','Single',false); }
	if (!defined('TEXT_WIDOWED_OPTION')) { define('TEXT_WIDOWED_OPTION','Widowed',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_DIVORCED_OPTION')) { define('TEXT_DIVORCED_OPTION','Divorciado/a',false); }
	if (!defined('TEXT_ENGAGED_OPTION')) { define('TEXT_ENGAGED_OPTION','Comprometido/a',false); }
	if (!defined('TEXT_LIVINGTOGETHER_OPTION')) { define('TEXT_LIVINGTOGETHER_OPTION','Unión libre',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','Casado/a',false); }
	if (!defined('TEXT_OTHER_STATUS_OPTION')) { define('TEXT_OTHER_STATUS_OPTION','Otro',false); }
	if (!defined('TEXT_SEPARATED_OPTION')) { define('TEXT_SEPARATED_OPTION','Separado/a',false); }
	if (!defined('TEXT_SINGLE_OPTION')) { define('TEXT_SINGLE_OPTION','Soltero/a',false); }
	if (!defined('TEXT_WIDOWED_OPTION')) { define('TEXT_WIDOWED_OPTION','Viudo/a',false); }
}
//EOF
