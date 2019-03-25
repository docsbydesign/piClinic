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
	if (!defined('TEXT_COMMENT_LIST_HEAD')) { define('TEXT_COMMENT_LIST_HEAD','TEXT_COMMENT_LIST_HEAD',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_CREATEDATE')) { define('TEXT_COMMENT_LIST_HEAD_CREATEDATE','TEXT_COMMENT_LIST_HEAD_CREATEDATE',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_PAGE')) { define('TEXT_COMMENT_LIST_HEAD_PAGE','TEXT_COMMENT_LIST_HEAD_PAGE',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_TEXT')) { define('TEXT_COMMENT_LIST_HEAD_TEXT','TEXT_COMMENT_LIST_HEAD_TEXT',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_USERNAME')) { define('TEXT_COMMENT_LIST_HEAD_USERNAME','TEXT_COMMENT_LIST_HEAD_USERNAME',false); }
	if (!defined('TEXT_COMMENT_PAGE_TITLE')) { define('TEXT_COMMENT_PAGE_TITLE','TEXT_COMMENT_PAGE_TITLE',false); }
	if (!defined('TEXT_NO_COMMENT_RECORDS')) { define('TEXT_NO_COMMENT_RECORDS','TEXT_NO_COMMENT_RECORDS',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','TEXT_VALUE_NOT_SET',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_LIST_HEAD')) { define('TEXT_COMMENT_LIST_HEAD','User comments',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_CREATEDATE')) { define('TEXT_COMMENT_LIST_HEAD_CREATEDATE','Comment date',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_PAGE')) { define('TEXT_COMMENT_LIST_HEAD_PAGE','Page',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_TEXT')) { define('TEXT_COMMENT_LIST_HEAD_TEXT','Comment',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_USERNAME')) { define('TEXT_COMMENT_LIST_HEAD_USERNAME','Username',false); }
	if (!defined('TEXT_COMMENT_PAGE_TITLE')) { define('TEXT_COMMENT_PAGE_TITLE','piClinic Comment',false); }
	if (!defined('TEXT_NO_COMMENT_RECORDS')) { define('TEXT_NO_COMMENT_RECORDS','No comments',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','(not set)',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_LIST_HEAD')) { define('TEXT_COMMENT_LIST_HEAD','Comentarios de los usuarios',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_CREATEDATE')) { define('TEXT_COMMENT_LIST_HEAD_CREATEDATE','Fecha del comentario',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_PAGE')) { define('TEXT_COMMENT_LIST_HEAD_PAGE','Página',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_TEXT')) { define('TEXT_COMMENT_LIST_HEAD_TEXT','Comentario',false); }
	if (!defined('TEXT_COMMENT_LIST_HEAD_USERNAME')) { define('TEXT_COMMENT_LIST_HEAD_USERNAME','Usuario',false); }
	if (!defined('TEXT_COMMENT_PAGE_TITLE')) { define('TEXT_COMMENT_PAGE_TITLE','Comentario de piClinic',false); }
	if (!defined('TEXT_NO_COMMENT_RECORDS')) { define('TEXT_NO_COMMENT_RECORDS','No hay comentarios',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','(vacio)',false); }
}
//EOF
