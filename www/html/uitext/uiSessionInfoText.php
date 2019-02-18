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
	if (!defined('TEXT_SESSION_LOGOUT_LINK')) { define('TEXT_SESSION_LOGOUT_LINK','TEXT_SESSION_LOGOUT_LINK',false); }
	if (!defined('TEXT_SESSION_LOGOUT_TITLE')) { define('TEXT_SESSION_LOGOUT_TITLE','TEXT_SESSION_LOGOUT_TITLE',false); }
	if (!defined('TEXT_SESSION_NAME_PROMPT')) { define('TEXT_SESSION_NAME_PROMPT','TEXT_SESSION_NAME_PROMPT',false); }
	if (!defined('TEXT_SESSION_SETTINGS_LABEL')) { define('TEXT_SESSION_SETTINGS_LABEL','TEXT_SESSION_SETTINGS_LABEL',false); }
	if (!defined('TEXT_SESSION_SETTINGS_TITLE')) { define('TEXT_SESSION_SETTINGS_TITLE','TEXT_SESSION_SETTINGS_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_SESSION_LOGOUT_LINK')) { define('TEXT_SESSION_LOGOUT_LINK','Logout',false); }
	if (!defined('TEXT_SESSION_LOGOUT_TITLE')) { define('TEXT_SESSION_LOGOUT_TITLE','Logout',false); }
	if (!defined('TEXT_SESSION_NAME_PROMPT')) { define('TEXT_SESSION_NAME_PROMPT','Logged in as',false); }
	if (!defined('TEXT_SESSION_SETTINGS_LABEL')) { define('TEXT_SESSION_SETTINGS_LABEL','User settings',false); }
	if (!defined('TEXT_SESSION_SETTINGS_TITLE')) { define('TEXT_SESSION_SETTINGS_TITLE','Update user account settings and password',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_SESSION_LOGOUT_LINK')) { define('TEXT_SESSION_LOGOUT_LINK','Cerrar sesión',false); }
	if (!defined('TEXT_SESSION_LOGOUT_TITLE')) { define('TEXT_SESSION_LOGOUT_TITLE','Cerrar sesión',false); }
	if (!defined('TEXT_SESSION_NAME_PROMPT')) { define('TEXT_SESSION_NAME_PROMPT','Inscrito como',false); }
	if (!defined('TEXT_SESSION_SETTINGS_LABEL')) { define('TEXT_SESSION_SETTINGS_LABEL','Configuración del usuario',false); }
	if (!defined('TEXT_SESSION_SETTINGS_TITLE')) { define('TEXT_SESSION_SETTINGS_TITLE','Actualizar la configuración y la contraseña de la cuenta de usuario ',false); }
}
//EOF
