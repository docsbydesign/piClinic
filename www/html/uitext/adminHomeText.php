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
	if (!defined('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION')) { define('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION','TEXT_ADMIN_LOG_VIEWER_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_LINK')) { define('TEXT_ADMIN_LOG_VIEWER_LINK','TEXT_ADMIN_LOG_VIEWER_LINK',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','TEXT_ADMIN_LOG_VIEWER_TITLE',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION')) { define('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION','TEXT_ADMIN_MANAGE_USERS_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_LINK')) { define('TEXT_ADMIN_MANAGE_USERS_LINK','TEXT_ADMIN_MANAGE_USERS_LINK',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_TITLE')) { define('TEXT_ADMIN_MANAGE_USERS_TITLE','TEXT_ADMIN_MANAGE_USERS_TITLE',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION')) { define('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION','TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_LINK')) { define('TEXT_ADMIN_SHOW_COMMENTS_LINK','TEXT_ADMIN_SHOW_COMMENTS_LINK',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_TITLE')) { define('TEXT_ADMIN_SHOW_COMMENTS_TITLE','TEXT_ADMIN_SHOW_COMMENTS_TITLE',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK','TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE','TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_DATA_LINK','TEXT_MONTHLY_SUMMARY_DATA_LINK',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_DATA_TITLE','TEXT_MONTHLY_SUMMARY_DATA_TITLE',false); }
	if (!defined('TEXT_PICLINIC_SYSTEM_PAGE_TITLE')) { define('TEXT_PICLINIC_SYSTEM_PAGE_TITLE','TEXT_PICLINIC_SYSTEM_PAGE_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION')) { define('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION','Display system errors and events',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_LINK')) { define('TEXT_ADMIN_LOG_VIEWER_LINK','piClinic system log',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','Display system errors and events',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION')) { define('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION','Manage user settings',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_LINK')) { define('TEXT_ADMIN_MANAGE_USERS_LINK','piClinic users',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_TITLE')) { define('TEXT_ADMIN_MANAGE_USERS_TITLE','Manage settings of the piClinic users',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION')) { define('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION','Show the most recent comments about the piClinic',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_LINK')) { define('TEXT_ADMIN_SHOW_COMMENTS_LINK','User comments',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_TITLE')) { define('TEXT_ADMIN_SHOW_COMMENTS_TITLE','User comments',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK','Diagnoses tabulated in the Monthly Report of Outpatient Care',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE','List of diagnostic codes tabulated in the Monthly Report of Outpatient Care',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_DATA_LINK','Diagnoses tabulated in the Daily Report of Outpatient Care',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_DATA_TITLE','List of diagnosic codes tabulated in the Daily Report of Outpatient Care',false); }
	if (!defined('TEXT_PICLINIC_SYSTEM_PAGE_TITLE')) { define('TEXT_PICLINIC_SYSTEM_PAGE_TITLE','piClinic system administration',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION')) { define('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION','Mostrar los errores y eventos del sistema',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_LINK')) { define('TEXT_ADMIN_LOG_VIEWER_LINK','Registro del sistema piClinic',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','Mostrar los errores y eventos del sistema',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION')) { define('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION','Maneja las configuracións de los usuarios',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_LINK')) { define('TEXT_ADMIN_MANAGE_USERS_LINK','Usuarios del piClinic',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_TITLE')) { define('TEXT_ADMIN_MANAGE_USERS_TITLE','Maneja la configuración de los usuarios del sistema piClinic',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION')) { define('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION','Mostrar los comentarios del piClinic más recientes',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_LINK')) { define('TEXT_ADMIN_SHOW_COMMENTS_LINK','Comentarios de los usuarios',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_TITLE')) { define('TEXT_ADMIN_SHOW_COMMENTS_TITLE','Comentarios de los usuarios',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK','Diagnosticos tabulados en el Informe Mensual de Atenciones Ambulatorias (AT2-R)',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE','Lista de los codigos diagnosticos tabulados en el Informe Mensual de Atenciones Ambulatorias (AT2-R)',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_DATA_LINK','Diagnosticos tabulados en el Informe Diario de Atenciones Ambulatorias',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_DATA_TITLE','Lista de los codigos diagnosticos tabulado en el Informe Diario de Atenciones Ambulatorias',false); }
	if (!defined('TEXT_PICLINIC_SYSTEM_PAGE_TITLE')) { define('TEXT_PICLINIC_SYSTEM_PAGE_TITLE','Administración del sistema piClinic',false); }
}
//EOF
