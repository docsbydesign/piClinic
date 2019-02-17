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
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','TEXT_COMMENT_CANCEL',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','TEXT_COMMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','TEXT_COMMENT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','TEXT_USER_COMMENTS_PAGE_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','Cancel',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','Enter your comment about the piClinic here',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','Submit',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','User comments',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','Cancelar',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','Entrar su comentario del piClinic aquí',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','Entregar',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','Comentarios de los usuarios',false); }
}
//EOF
