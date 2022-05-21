<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
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
	if (!defined('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION','TEXT_ADMIN_BACKUP_ALL_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_LINK')) { define('TEXT_ADMIN_BACKUP_ALL_LINK','TEXT_ADMIN_BACKUP_ALL_LINK',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_TITLE')) { define('TEXT_ADMIN_BACKUP_ALL_TITLE','TEXT_ADMIN_BACKUP_ALL_TITLE',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_DB_DESCRIPTION','TEXT_ADMIN_BACKUP_DB_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_LINK')) { define('TEXT_ADMIN_BACKUP_DB_LINK','TEXT_ADMIN_BACKUP_DB_LINK',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_TITLE')) { define('TEXT_ADMIN_BACKUP_DB_TITLE','TEXT_ADMIN_BACKUP_DB_TITLE',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION','TEXT_ADMIN_BACKUP_LOG_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_LINK')) { define('TEXT_ADMIN_BACKUP_LOG_LINK','TEXT_ADMIN_BACKUP_LOG_LINK',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_TITLE')) { define('TEXT_ADMIN_BACKUP_LOG_TITLE','TEXT_ADMIN_BACKUP_LOG_TITLE',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION','TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_LINK')) { define('TEXT_ADMIN_BACKUP_PATIENT_LINK','TEXT_ADMIN_BACKUP_PATIENT_LINK',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_TITLE')) { define('TEXT_ADMIN_BACKUP_PATIENT_TITLE','TEXT_ADMIN_BACKUP_PATIENT_TITLE',false); }
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
	if (!defined('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION','Backup piClinic System (This can take up to a minute or two)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_LINK')) { define('TEXT_ADMIN_BACKUP_ALL_LINK','Backup entire system',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_TITLE')) { define('TEXT_ADMIN_BACKUP_ALL_TITLE','Backup piClinic System',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_DB_DESCRIPTION','Backup piClinic database (This can take up to a minute)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_LINK')) { define('TEXT_ADMIN_BACKUP_DB_LINK','Backup piClinic database',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_TITLE')) { define('TEXT_ADMIN_BACKUP_DB_TITLE','Backup piClinic database',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION','Backup piClinic system logs (This can take up to a minute or two)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_LINK')) { define('TEXT_ADMIN_BACKUP_LOG_LINK','Backup system logs',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_TITLE')) { define('TEXT_ADMIN_BACKUP_LOG_TITLE','Backup piClinic system logs',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION','Backup piClinic patient info',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_LINK')) { define('TEXT_ADMIN_BACKUP_PATIENT_LINK','Backup patient info',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_TITLE')) { define('TEXT_ADMIN_BACKUP_PATIENT_TITLE','Backup piClinic patient info',false); }
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
	if (!defined('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_ALL_DESCRIPTION','Guardar todo el sistema de la consola piClinic (Se puede demorar unos minutos)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_LINK')) { define('TEXT_ADMIN_BACKUP_ALL_LINK','Guardar el sistema',false); }
	if (!defined('TEXT_ADMIN_BACKUP_ALL_TITLE')) { define('TEXT_ADMIN_BACKUP_ALL_TITLE','Guardar todo el sistema de la consola piClinic',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_DB_DESCRIPTION','Guardar la base de datos de la consola piClinic (Se puede demorar hasta un minuto)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_LINK')) { define('TEXT_ADMIN_BACKUP_DB_LINK','Guardar la base de datos',false); }
	if (!defined('TEXT_ADMIN_BACKUP_DB_TITLE')) { define('TEXT_ADMIN_BACKUP_DB_TITLE','Guardar la base de datos de la consola piClinic',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_LOG_DESCRIPTION','Guardar los registros de la actividad de la consola piClinic (Se puede demorar unos minutos)',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_LINK')) { define('TEXT_ADMIN_BACKUP_LOG_LINK','Guardar los registros de la consola',false); }
	if (!defined('TEXT_ADMIN_BACKUP_LOG_TITLE')) { define('TEXT_ADMIN_BACKUP_LOG_TITLE','Guardar los registros de la actividad de la consola piClinic',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION')) { define('TEXT_ADMIN_BACKUP_PATIENT_DESCRIPTION','Guardar los datos de todos los pacientes',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_LINK')) { define('TEXT_ADMIN_BACKUP_PATIENT_LINK','Guardar los datos del paciente',false); }
	if (!defined('TEXT_ADMIN_BACKUP_PATIENT_TITLE')) { define('TEXT_ADMIN_BACKUP_PATIENT_TITLE','Guardar los datos de todos los pacientes',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION')) { define('TEXT_ADMIN_LOG_VIEWER_DESCRIPTION','Mostrar los errores y eventos del sistema',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_LINK')) { define('TEXT_ADMIN_LOG_VIEWER_LINK','Registro del sistema piClinic',false); }
	if (!defined('TEXT_ADMIN_LOG_VIEWER_TITLE')) { define('TEXT_ADMIN_LOG_VIEWER_TITLE','Mostrar los errores y eventos del sistema',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION')) { define('TEXT_ADMIN_MANAGE_USERS_DESCRIPTION','Maneja las configuración de los usuarios',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_LINK')) { define('TEXT_ADMIN_MANAGE_USERS_LINK','Usuarios del piClinic',false); }
	if (!defined('TEXT_ADMIN_MANAGE_USERS_TITLE')) { define('TEXT_ADMIN_MANAGE_USERS_TITLE','Maneja la configuración de los usuarios del sistema piClinic',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION')) { define('TEXT_ADMIN_SHOW_COMMENTS_DESCRIPTION','Mostrar los comentarios del piClinic más recientes',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_LINK')) { define('TEXT_ADMIN_SHOW_COMMENTS_LINK','Comentarios de los usuarios',false); }
	if (!defined('TEXT_ADMIN_SHOW_COMMENTS_TITLE')) { define('TEXT_ADMIN_SHOW_COMMENTS_TITLE','Comentarios de los usuarios',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_LINK','Diagnósticos tabulados en el Informe Mensual de Atenciones Ambulatorias (AT2-R)',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_BY_POS_DATA_TITLE','Lista de los códigos de diagnósticos tabulados en el Informe Mensual de Atenciones Ambulatorias (AT2-R)',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_LINK')) { define('TEXT_MONTHLY_SUMMARY_DATA_LINK','Diagnósticos tabulados en el Informe Diario de Atenciones Ambulatorias',false); }
	if (!defined('TEXT_MONTHLY_SUMMARY_DATA_TITLE')) { define('TEXT_MONTHLY_SUMMARY_DATA_TITLE','Lista de los códigos de diagnósticos tabulado en el Informe Diario de Atenciones Ambulatorias',false); }
	if (!defined('TEXT_PICLINIC_SYSTEM_PAGE_TITLE')) { define('TEXT_PICLINIC_SYSTEM_PAGE_TITLE','Administración del sistema piClinic',false); }
}
//EOF
