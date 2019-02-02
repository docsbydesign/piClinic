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
require_once '../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	define('TEXT_LOGIN_PASSWORD','TEXT_LOGIN_PASSWORD',false);
	define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','TEXT_LOGIN_PASSWORD_PLACEHOLDER',false);
	define('TEXT_LOGIN_SUBMIT_BUTTON','TEXT_LOGIN_SUBMIT_BUTTON',false);
	define('TEXT_LOGIN_USERNAME','TEXT_LOGIN_USERNAME',false);
	define('TEXT_LOGIN_USERNAME_PLACEHOLDER','TEXT_LOGIN_USERNAME_PLACEHOLDER',false);
	define('TEXT_PAGE_TITLE','TEXT_PAGE_TITLE',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_LOGIN_PASSWORD','Password',false);
	define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','Password',false);
	define('TEXT_LOGIN_SUBMIT_BUTTON','Login',false);
	define('TEXT_LOGIN_USERNAME','Username',false);
	define('TEXT_LOGIN_USERNAME_PLACEHOLDER','Username',false);
	define('TEXT_PAGE_TITLE','Clinic information',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_LOGIN_PASSWORD','Contraseña',false);
	define('TEXT_LOGIN_PASSWORD_PLACEHOLDER','Contraseña',false);
	define('TEXT_LOGIN_SUBMIT_BUTTON','Iniciar sesión',false);
	define('TEXT_LOGIN_USERNAME','Usuario',false);
	define('TEXT_LOGIN_USERNAME_PLACEHOLDER','Usuario',false);
	define('TEXT_PAGE_TITLE','Información de la clínica',false);
}
?>
