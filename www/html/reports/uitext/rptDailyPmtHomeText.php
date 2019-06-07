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
	if (!defined('TEXT_DAILY_DATE_LABEL')) { define('TEXT_DAILY_DATE_LABEL','TEXT_DAILY_DATE_LABEL',false); }
	if (!defined('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE','TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE',false); }
	if (!defined('TEXT_DAILY_PMT_TITLE')) { define('TEXT_DAILY_PMT_TITLE','TEXT_DAILY_PMT_TITLE',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','TEXT_DATE_FORMAT',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','TEXT_DATE_PROMPT_LABEL',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_ID')) { define('TEXT_DH_CLINIC_PATIENT_ID','TEXT_DH_CLINIC_PATIENT_ID',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_VISIT')) { define('TEXT_DH_CLINIC_PATIENT_VISIT','TEXT_DH_CLINIC_PATIENT_VISIT',false); }
	if (!defined('TEXT_DH_DATE_TIME_IN')) { define('TEXT_DH_DATE_TIME_IN','TEXT_DH_DATE_TIME_IN',false); }
	if (!defined('TEXT_DH_PATIENT_NAME')) { define('TEXT_DH_PATIENT_NAME','TEXT_DH_PATIENT_NAME',false); }
	if (!defined('TEXT_DH_PATIENT_PAYMENT')) { define('TEXT_DH_PATIENT_PAYMENT','TEXT_DH_PATIENT_PAYMENT',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','TEXT_EXPORT_CSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','TEXT_EXPORT_CSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','TEXT_EXPORT_TSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','TEXT_EXPORT_TSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_NO_REPORT_PROMPT')) { define('TEXT_NO_REPORT_PROMPT','TEXT_NO_REPORT_PROMPT',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','TEXT_NO_VISITS_FOUND_DAY',false); }
	if (!defined('TEXT_OF_PAGES')) { define('TEXT_OF_PAGES','TEXT_OF_PAGES',false); }
	if (!defined('TEXT_PAGE_LABEL')) { define('TEXT_PAGE_LABEL','TEXT_PAGE_LABEL',false); }
	if (!defined('TEXT_PATIENT_COUNT_LABEL')) { define('TEXT_PATIENT_COUNT_LABEL','TEXT_PATIENT_COUNT_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','TEXT_REPORT_CLINICNAME_LABEL',false); }
	if (!defined('TEXT_REPORT_CONTINUED')) { define('TEXT_REPORT_CONTINUED','TEXT_REPORT_CONTINUED',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','TEXT_REPORT_DATE_PLACEHOLDER',false); }
	if (!defined('TEXT_REPORT_DATE_TIME_IN')) { define('TEXT_REPORT_DATE_TIME_IN','TEXT_REPORT_DATE_TIME_IN',false); }
	if (!defined('TEXT_REPORT_PATIENT_ID_LABEL')) { define('TEXT_REPORT_PATIENT_ID_LABEL','TEXT_REPORT_PATIENT_ID_LABEL',false); }
	if (!defined('TEXT_REPORT_PATIENT_NAME_LABEL')) { define('TEXT_REPORT_PATIENT_NAME_LABEL','TEXT_REPORT_PATIENT_NAME_LABEL',false); }
	if (!defined('TEXT_REPORT_PAYMENT_LABEL')) { define('TEXT_REPORT_PAYMENT_LABEL','TEXT_REPORT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','TEXT_REPORT_ROW_LABEL',false); }
	if (!defined('TEXT_REPORT_TOTAL')) { define('TEXT_REPORT_TOTAL','TEXT_REPORT_TOTAL',false); }
	if (!defined('TEXT_RH_TOTAL')) { define('TEXT_RH_TOTAL','TEXT_RH_TOTAL',false); }
	if (!defined('TEXT_RPT_DECIMAL')) { define('TEXT_RPT_DECIMAL','TEXT_RPT_DECIMAL',false); }
	if (!defined('TEXT_RPT_DIGIT_SEPARATOR')) { define('TEXT_RPT_DIGIT_SEPARATOR','TEXT_RPT_DIGIT_SEPARATOR',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','TEXT_SHOW_REPORT_BUTTON',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','TEXT_SHOW_REPORT_DATE_FIELD_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','TEXT_SHOW_REPORT_DATE_FIELD_TITLE',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','TEXT_SHOW_REPORT_DATE_LIST_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','TEXT_SHOW_REPORT_DATE_LIST_TITLE',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_DAILY_DATE_LABEL')) { define('TEXT_DAILY_DATE_LABEL','Report date (MM/DD/YYYY)',false); }
	if (!defined('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE','Daily Payment Log',false); }
	if (!defined('TEXT_DAILY_PMT_TITLE')) { define('TEXT_DAILY_PMT_TITLE','Daily payment log',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','Date&nbsp;(yyyy-mm-dd)',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_ID')) { define('TEXT_DH_CLINIC_PATIENT_ID','Patient_ID',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_VISIT')) { define('TEXT_DH_CLINIC_PATIENT_VISIT','Visit_ID',false); }
	if (!defined('TEXT_DH_DATE_TIME_IN')) { define('TEXT_DH_DATE_TIME_IN','Time',false); }
	if (!defined('TEXT_DH_PATIENT_NAME')) { define('TEXT_DH_PATIENT_NAME','Patient_name',false); }
	if (!defined('TEXT_DH_PATIENT_PAYMENT')) { define('TEXT_DH_PATIENT_PAYMENT','Payment',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Export as CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Export the report to a CSV file to open as a spreadsheet. This format opens in Excel automatically, but some letters might not appear correctly.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Export as TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Export the report to a TSV file to import into a spreadsheet. Importing this format might require several steps, but the data will appear correctly.',false); }
	if (!defined('TEXT_NO_REPORT_PROMPT')) { define('TEXT_NO_REPORT_PROMPT','Select a date.',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','No visits found for this date.',false); }
	if (!defined('TEXT_OF_PAGES')) { define('TEXT_OF_PAGES','of',false); }
	if (!defined('TEXT_PAGE_LABEL')) { define('TEXT_PAGE_LABEL','Page',false); }
	if (!defined('TEXT_PATIENT_COUNT_LABEL')) { define('TEXT_PATIENT_COUNT_LABEL','Patients seen',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Clinic',false); }
	if (!defined('TEXT_REPORT_CONTINUED')) { define('TEXT_REPORT_CONTINUED','Continued on next page',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','Report date (YYYY-MM-DD)',false); }
	if (!defined('TEXT_REPORT_DATE_TIME_IN')) { define('TEXT_REPORT_DATE_TIME_IN','Visit time',false); }
	if (!defined('TEXT_REPORT_PATIENT_ID_LABEL')) { define('TEXT_REPORT_PATIENT_ID_LABEL','Patient ID',false); }
	if (!defined('TEXT_REPORT_PATIENT_NAME_LABEL')) { define('TEXT_REPORT_PATIENT_NAME_LABEL','Patient name',false); }
	if (!defined('TEXT_REPORT_PAYMENT_LABEL')) { define('TEXT_REPORT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','No.',false); }
	if (!defined('TEXT_REPORT_TOTAL')) { define('TEXT_REPORT_TOTAL','Total',false); }
	if (!defined('TEXT_RH_TOTAL')) { define('TEXT_RH_TOTAL','Total',false); }
	if (!defined('TEXT_RPT_DECIMAL')) { define('TEXT_RPT_DECIMAL','.',false); }
	if (!defined('TEXT_RPT_DIGIT_SEPARATOR')) { define('TEXT_RPT_DIGIT_SEPARATOR',',',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Show report',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Show date entry field',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Display the field into which you can enter a date',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Show report date list',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Display the list of available report dates',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','All',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_DAILY_DATE_LABEL')) { define('TEXT_DAILY_DATE_LABEL','Fecha del informe (DD-MM-YYYY)',false); }
	if (!defined('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_PAYMENT_LOG_PAGE_TITLE','Registros de Pagos del Día',false); }
	if (!defined('TEXT_DAILY_PMT_TITLE')) { define('TEXT_DAILY_PMT_TITLE','Registro de pagos del día',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','Fecha&nbsp;(aaaa-mm-dd)',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_ID')) { define('TEXT_DH_CLINIC_PATIENT_ID','Número_de_identidad_del_paciente',false); }
	if (!defined('TEXT_DH_CLINIC_PATIENT_VISIT')) { define('TEXT_DH_CLINIC_PATIENT_VISIT','Número_de_la_visita',false); }
	if (!defined('TEXT_DH_DATE_TIME_IN')) { define('TEXT_DH_DATE_TIME_IN','Hora',false); }
	if (!defined('TEXT_DH_PATIENT_NAME')) { define('TEXT_DH_PATIENT_NAME','Nombre_del_paciente',false); }
	if (!defined('TEXT_DH_PATIENT_PAYMENT')) { define('TEXT_DH_PATIENT_PAYMENT','Pago',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Descargar como CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Descarga el informe como un archivo CSV para abrirlo como una hoja de cálculo. Excel puede abrir este formato automáticamente, pero no muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Descargar como TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Descarga el informe como un archivo TSV para importarlo como una hoja de cálculo. Excel puede importar este formato manualmente y muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_NO_REPORT_PROMPT')) { define('TEXT_NO_REPORT_PROMPT','Escoge una fecha.',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','No visitas encontradas en esta fecha.',false); }
	if (!defined('TEXT_OF_PAGES')) { define('TEXT_OF_PAGES','de',false); }
	if (!defined('TEXT_PAGE_LABEL')) { define('TEXT_PAGE_LABEL','Página',false); }
	if (!defined('TEXT_PATIENT_COUNT_LABEL')) { define('TEXT_PATIENT_COUNT_LABEL','Pacientes atendidos',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Establecimiento',false); }
	if (!defined('TEXT_REPORT_CONTINUED')) { define('TEXT_REPORT_CONTINUED','Continúa en la próxima página',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','Report date (AAAA-MM-DD)',false); }
	if (!defined('TEXT_REPORT_DATE_TIME_IN')) { define('TEXT_REPORT_DATE_TIME_IN','Hora de la visita',false); }
	if (!defined('TEXT_REPORT_PATIENT_ID_LABEL')) { define('TEXT_REPORT_PATIENT_ID_LABEL','Número de identidad del paciente',false); }
	if (!defined('TEXT_REPORT_PATIENT_NAME_LABEL')) { define('TEXT_REPORT_PATIENT_NAME_LABEL','Nombre del paciente',false); }
	if (!defined('TEXT_REPORT_PAYMENT_LABEL')) { define('TEXT_REPORT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','No.',false); }
	if (!defined('TEXT_REPORT_TOTAL')) { define('TEXT_REPORT_TOTAL','Total',false); }
	if (!defined('TEXT_RH_TOTAL')) { define('TEXT_RH_TOTAL','Total',false); }
	if (!defined('TEXT_RPT_DECIMAL')) { define('TEXT_RPT_DECIMAL',',',false); }
	if (!defined('TEXT_RPT_DIGIT_SEPARATOR')) { define('TEXT_RPT_DIGIT_SEPARATOR','.',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Mostrar informe',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Muestra el campo para la fecha',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Muestra el campo para entrar la fecha directamente',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Muestra la lista de fechas',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Muestra la lista de fechas que tengan informes',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','Todos',false); }
}
//EOF
