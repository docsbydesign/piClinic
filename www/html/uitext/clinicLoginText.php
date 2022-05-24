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
	if (!defined('TEXT_CLINIC_LOGIN_PAGE_TITLE')) { define('TEXT_CLINIC_LOGIN_PAGE_TITLE','TEXT_CLINIC_LOGIN_PAGE_TITLE',false); }
	if (!defined('TEXT_LOGIN_PASSWORD')) { define('TEXT_LOGIN_PASSWORD','TEXT_LOGIN_PASSWORD',false); }
	if (!defined('TEXT_LOGIN_PASSWORD_PLACEHOLDER')) { define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','TEXT_LOGIN_PASSWORD_PLACEHOLDER',false); }
	if (!defined('TEXT_LOGIN_SUBMIT_BUTTON')) { define('TEXT_LOGIN_SUBMIT_BUTTON','TEXT_LOGIN_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_LOGIN_USERNAME')) { define('TEXT_LOGIN_USERNAME','TEXT_LOGIN_USERNAME',false); }
	if (!defined('TEXT_LOGIN_USERNAME_PLACEHOLDER')) { define('TEXT_LOGIN_USERNAME_PLACEHOLDER','TEXT_LOGIN_USERNAME_PLACEHOLDER',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_CLINIC_LOGIN_PAGE_TITLE')) { define('TEXT_CLINIC_LOGIN_PAGE_TITLE','Clinic login',false); }
	if (!defined('TEXT_LOGIN_PASSWORD')) { define('TEXT_LOGIN_PASSWORD','Password',false); }
	if (!defined('TEXT_LOGIN_PASSWORD_PLACEHOLDER')) { define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','Password',false); }
	if (!defined('TEXT_LOGIN_SUBMIT_BUTTON')) { define('TEXT_LOGIN_SUBMIT_BUTTON','Login',false); }
	if (!defined('TEXT_LOGIN_USERNAME')) { define('TEXT_LOGIN_USERNAME','Username',false); }
	if (!defined('TEXT_LOGIN_USERNAME_PLACEHOLDER')) { define('TEXT_LOGIN_USERNAME_PLACEHOLDER','Username',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_CLINIC_LOGIN_PAGE_TITLE')) { define('TEXT_CLINIC_LOGIN_PAGE_TITLE','Iniciar sesión',false); }
	if (!defined('TEXT_LOGIN_PASSWORD')) { define('TEXT_LOGIN_PASSWORD','Contraseña',false); }
	if (!defined('TEXT_LOGIN_PASSWORD_PLACEHOLDER')) { define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','Contraseña',false); }
	if (!defined('TEXT_LOGIN_SUBMIT_BUTTON')) { define('TEXT_LOGIN_SUBMIT_BUTTON','Iniciar sesión',false); }
	if (!defined('TEXT_LOGIN_USERNAME')) { define('TEXT_LOGIN_USERNAME','Usuario',false); }
	if (!defined('TEXT_LOGIN_USERNAME_PLACEHOLDER')) { define('TEXT_LOGIN_USERNAME_PLACEHOLDER','Usuario',false); }
}
//EOF
