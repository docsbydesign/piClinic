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
	if (!defined('TEXT_HELP_EDIT_CANCEL')) { define('TEXT_HELP_EDIT_CANCEL','TEXT_HELP_EDIT_CANCEL',false); }
	if (!defined('TEXT_HELP_REF_PAGE')) { define('TEXT_HELP_REF_PAGE','TEXT_HELP_REF_PAGE',false); }
	if (!defined('TEXT_HELP_SUBMIT_BUTTON')) { define('TEXT_HELP_SUBMIT_BUTTON','TEXT_HELP_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE')) { define('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE','TEXT_HELP_TOPIC_EDIT_PAGE_TITLE',false); }
	if (!defined('TEXT_HELP_TOPIC_ID')) { define('TEXT_HELP_TOPIC_ID','TEXT_HELP_TOPIC_ID',false); }
	if (!defined('TEXT_HELP_TOPIC_TEXT')) { define('TEXT_HELP_TOPIC_TEXT','TEXT_HELP_TOPIC_TEXT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_HELP_EDIT_CANCEL')) { define('TEXT_HELP_EDIT_CANCEL','Cancel',false); }
	if (!defined('TEXT_HELP_REF_PAGE')) { define('TEXT_HELP_REF_PAGE','Page',false); }
	if (!defined('TEXT_HELP_SUBMIT_BUTTON')) { define('TEXT_HELP_SUBMIT_BUTTON','Save',false); }
	if (!defined('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE')) { define('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE','Help content edit',false); }
	if (!defined('TEXT_HELP_TOPIC_ID')) { define('TEXT_HELP_TOPIC_ID','Content ID',false); }
	if (!defined('TEXT_HELP_TOPIC_TEXT')) { define('TEXT_HELP_TOPIC_TEXT','HTML Content',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_HELP_EDIT_CANCEL')) { define('TEXT_HELP_EDIT_CANCEL','Cancelar',false); }
	if (!defined('TEXT_HELP_REF_PAGE')) { define('TEXT_HELP_REF_PAGE','Página',false); }
	if (!defined('TEXT_HELP_SUBMIT_BUTTON')) { define('TEXT_HELP_SUBMIT_BUTTON','Actualizar',false); }
	if (!defined('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE')) { define('TEXT_HELP_TOPIC_EDIT_PAGE_TITLE','Editar contenido de ayuda',false); }
	if (!defined('TEXT_HELP_TOPIC_ID')) { define('TEXT_HELP_TOPIC_ID','ID del contenido',false); }
	if (!defined('TEXT_HELP_TOPIC_TEXT')) { define('TEXT_HELP_TOPIC_TEXT','Contenido HTML',false); }
}
//EOF
