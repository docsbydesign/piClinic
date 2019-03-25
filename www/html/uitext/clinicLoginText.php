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
