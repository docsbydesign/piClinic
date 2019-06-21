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
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','TEXT_ADMIN_LOG_VIEWER_TITLE',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','TEXT_BLANK_OPTION_SELECT',false); }
	if (!defined('TEXT_LOG_ACTION_FIELD_NAME_SELECT')) { define('TEXT_LOG_ACTION_FIELD_NAME_SELECT','TEXT_LOG_ACTION_FIELD_NAME_SELECT',false); }
	if (!defined('TEXT_LOG_CLASS_FIELD_NAME_SELECT')) { define('TEXT_LOG_CLASS_FIELD_NAME_SELECT','TEXT_LOG_CLASS_FIELD_NAME_SELECT',false); }
	if (!defined('TEXT_LOG_DISPLAY_ACTION')) { define('TEXT_LOG_DISPLAY_ACTION','TEXT_LOG_DISPLAY_ACTION',false); }
	if (!defined('TEXT_LOG_DISPLAY_AFTER_DATA')) { define('TEXT_LOG_DISPLAY_AFTER_DATA','TEXT_LOG_DISPLAY_AFTER_DATA',false); }
	if (!defined('TEXT_LOG_DISPLAY_BEFORE_DATA')) { define('TEXT_LOG_DISPLAY_BEFORE_DATA','TEXT_LOG_DISPLAY_BEFORE_DATA',false); }
	if (!defined('TEXT_LOG_DISPLAY_CLASS')) { define('TEXT_LOG_DISPLAY_CLASS','TEXT_LOG_DISPLAY_CLASS',false); }
	if (!defined('TEXT_LOG_DISPLAY_CREATED_DATE')) { define('TEXT_LOG_DISPLAY_CREATED_DATE','TEXT_LOG_DISPLAY_CREATED_DATE',false); }
	if (!defined('TEXT_LOG_DISPLAY_ID')) { define('TEXT_LOG_DISPLAY_ID','TEXT_LOG_DISPLAY_ID',false); }
	if (!defined('TEXT_LOG_DISPLAY_QUERY_STRING')) { define('TEXT_LOG_DISPLAY_QUERY_STRING','TEXT_LOG_DISPLAY_QUERY_STRING',false); }
	if (!defined('TEXT_LOG_DISPLAY_SOURCE')) { define('TEXT_LOG_DISPLAY_SOURCE','TEXT_LOG_DISPLAY_SOURCE',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_CODE')) { define('TEXT_LOG_DISPLAY_STATUS_CODE','TEXT_LOG_DISPLAY_STATUS_CODE',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_MSG')) { define('TEXT_LOG_DISPLAY_STATUS_MSG','TEXT_LOG_DISPLAY_STATUS_MSG',false); }
	if (!defined('TEXT_LOG_DISPLAY_TABLE')) { define('TEXT_LOG_DISPLAY_TABLE','TEXT_LOG_DISPLAY_TABLE',false); }
	if (!defined('TEXT_LOG_DISPLAY_TOKEN')) { define('TEXT_LOG_DISPLAY_TOKEN','TEXT_LOG_DISPLAY_TOKEN',false); }
	if (!defined('TEXT_LOG_FILE_SUBMIT_BUTTON')) { define('TEXT_LOG_FILE_SUBMIT_BUTTON','TEXT_LOG_FILE_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_LOG_STATUS_FIELD_NAME_SELECT')) { define('TEXT_LOG_STATUS_FIELD_NAME_SELECT','TEXT_LOG_STATUS_FIELD_NAME_SELECT',false); }
	if (!defined('TEXT_LOG_TABLE_FIELD_NAME_SELECT')) { define('TEXT_LOG_TABLE_FIELD_NAME_SELECT','TEXT_LOG_TABLE_FIELD_NAME_SELECT',false); }
	if (!defined('TEXT_NO_LOG_DATA')) { define('TEXT_NO_LOG_DATA','TEXT_NO_LOG_DATA',false); }
	if (!defined('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT')) { define('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT','TEXT_SOURCE_MODULE_FIELD_NAME_SELECT',false); }
	if (!defined('TEXT_USER_TOKEN_FIELD_NAME_SELECT')) { define('TEXT_USER_TOKEN_FIELD_NAME_SELECT','TEXT_USER_TOKEN_FIELD_NAME_SELECT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','Display system errors and events',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Choose',false); }
	if (!defined('TEXT_LOG_ACTION_FIELD_NAME_SELECT')) { define('TEXT_LOG_ACTION_FIELD_NAME_SELECT','Action',false); }
	if (!defined('TEXT_LOG_CLASS_FIELD_NAME_SELECT')) { define('TEXT_LOG_CLASS_FIELD_NAME_SELECT','Class',false); }
	if (!defined('TEXT_LOG_DISPLAY_ACTION')) { define('TEXT_LOG_DISPLAY_ACTION','Action',false); }
	if (!defined('TEXT_LOG_DISPLAY_AFTER_DATA')) { define('TEXT_LOG_DISPLAY_AFTER_DATA','After data',false); }
	if (!defined('TEXT_LOG_DISPLAY_BEFORE_DATA')) { define('TEXT_LOG_DISPLAY_BEFORE_DATA','Before data',false); }
	if (!defined('TEXT_LOG_DISPLAY_CLASS')) { define('TEXT_LOG_DISPLAY_CLASS','Class',false); }
	if (!defined('TEXT_LOG_DISPLAY_CREATED_DATE')) { define('TEXT_LOG_DISPLAY_CREATED_DATE','Date',false); }
	if (!defined('TEXT_LOG_DISPLAY_ID')) { define('TEXT_LOG_DISPLAY_ID','ID',false); }
	if (!defined('TEXT_LOG_DISPLAY_QUERY_STRING')) { define('TEXT_LOG_DISPLAY_QUERY_STRING','Query string',false); }
	if (!defined('TEXT_LOG_DISPLAY_SOURCE')) { define('TEXT_LOG_DISPLAY_SOURCE','Source',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_CODE')) { define('TEXT_LOG_DISPLAY_STATUS_CODE','Status code',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_MSG')) { define('TEXT_LOG_DISPLAY_STATUS_MSG','Status message',false); }
	if (!defined('TEXT_LOG_DISPLAY_TABLE')) { define('TEXT_LOG_DISPLAY_TABLE','Table',false); }
	if (!defined('TEXT_LOG_DISPLAY_TOKEN')) { define('TEXT_LOG_DISPLAY_TOKEN','Token',false); }
	if (!defined('TEXT_LOG_FILE_SUBMIT_BUTTON')) { define('TEXT_LOG_FILE_SUBMIT_BUTTON','Show log',false); }
	if (!defined('TEXT_LOG_STATUS_FIELD_NAME_SELECT')) { define('TEXT_LOG_STATUS_FIELD_NAME_SELECT','Status',false); }
	if (!defined('TEXT_LOG_TABLE_FIELD_NAME_SELECT')) { define('TEXT_LOG_TABLE_FIELD_NAME_SELECT','Table',false); }
	if (!defined('TEXT_NO_LOG_DATA')) { define('TEXT_NO_LOG_DATA','No data in this log file.',false); }
	if (!defined('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT')) { define('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT','Source',false); }
	if (!defined('TEXT_USER_TOKEN_FIELD_NAME_SELECT')) { define('TEXT_USER_TOKEN_FIELD_NAME_SELECT','Token',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','Mostrar los errores y eventos del sistema',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Seleccione',false); }
	if (!defined('TEXT_LOG_ACTION_FIELD_NAME_SELECT')) { define('TEXT_LOG_ACTION_FIELD_NAME_SELECT','Acción',false); }
	if (!defined('TEXT_LOG_CLASS_FIELD_NAME_SELECT')) { define('TEXT_LOG_CLASS_FIELD_NAME_SELECT','Clase',false); }
	if (!defined('TEXT_LOG_DISPLAY_ACTION')) { define('TEXT_LOG_DISPLAY_ACTION','Acción',false); }
	if (!defined('TEXT_LOG_DISPLAY_AFTER_DATA')) { define('TEXT_LOG_DISPLAY_AFTER_DATA','After data',false); }
	if (!defined('TEXT_LOG_DISPLAY_BEFORE_DATA')) { define('TEXT_LOG_DISPLAY_BEFORE_DATA','Before data',false); }
	if (!defined('TEXT_LOG_DISPLAY_CLASS')) { define('TEXT_LOG_DISPLAY_CLASS','Class',false); }
	if (!defined('TEXT_LOG_DISPLAY_CREATED_DATE')) { define('TEXT_LOG_DISPLAY_CREATED_DATE','Fecha',false); }
	if (!defined('TEXT_LOG_DISPLAY_ID')) { define('TEXT_LOG_DISPLAY_ID','ID',false); }
	if (!defined('TEXT_LOG_DISPLAY_QUERY_STRING')) { define('TEXT_LOG_DISPLAY_QUERY_STRING','QUery string',false); }
	if (!defined('TEXT_LOG_DISPLAY_SOURCE')) { define('TEXT_LOG_DISPLAY_SOURCE','Source',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_CODE')) { define('TEXT_LOG_DISPLAY_STATUS_CODE','Status code',false); }
	if (!defined('TEXT_LOG_DISPLAY_STATUS_MSG')) { define('TEXT_LOG_DISPLAY_STATUS_MSG','Status message',false); }
	if (!defined('TEXT_LOG_DISPLAY_TABLE')) { define('TEXT_LOG_DISPLAY_TABLE','Table',false); }
	if (!defined('TEXT_LOG_DISPLAY_TOKEN')) { define('TEXT_LOG_DISPLAY_TOKEN','Token',false); }
	if (!defined('TEXT_LOG_FILE_SUBMIT_BUTTON')) { define('TEXT_LOG_FILE_SUBMIT_BUTTON','Mostrar registro',false); }
	if (!defined('TEXT_LOG_STATUS_FIELD_NAME_SELECT')) { define('TEXT_LOG_STATUS_FIELD_NAME_SELECT','Status',false); }
	if (!defined('TEXT_LOG_TABLE_FIELD_NAME_SELECT')) { define('TEXT_LOG_TABLE_FIELD_NAME_SELECT','Table',false); }
	if (!defined('TEXT_NO_LOG_DATA')) { define('TEXT_NO_LOG_DATA','Este registro no tiene datos.',false); }
	if (!defined('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT')) { define('TEXT_SOURCE_MODULE_FIELD_NAME_SELECT','Source',false); }
	if (!defined('TEXT_USER_TOKEN_FIELD_NAME_SELECT')) { define('TEXT_USER_TOKEN_FIELD_NAME_SELECT','Token',false); }
}
//EOF
