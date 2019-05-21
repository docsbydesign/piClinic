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
