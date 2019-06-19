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
	if (!defined('TEXT_AGE_DAYS_LABEL')) { define('TEXT_AGE_DAYS_LABEL','TEXT_AGE_DAYS_LABEL',false); }
	if (!defined('TEXT_AGE_MONTHS_LABEL')) { define('TEXT_AGE_MONTHS_LABEL','TEXT_AGE_MONTHS_LABEL',false); }
	if (!defined('TEXT_AGE_YEARS_LABEL')) { define('TEXT_AGE_YEARS_LABEL','TEXT_AGE_YEARS_LABEL',false); }
	if (!defined('TEXT_ATA_LEFT_TITLE')) { define('TEXT_ATA_LEFT_TITLE','TEXT_ATA_LEFT_TITLE',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_LABEL')) { define('TEXT_BIRTHDAY_DATE_LABEL','TEXT_BIRTHDAY_DATE_LABEL',false); }
	if (!defined('TEXT_CITY_LABEL')) { define('TEXT_CITY_LABEL','TEXT_CITY_LABEL',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','TEXT_CLINICPATIENTID_LABEL',false); }
	if (!defined('TEXT_CONDITION_1_LABEL')) { define('TEXT_CONDITION_1_LABEL','TEXT_CONDITION_1_LABEL',false); }
	if (!defined('TEXT_CONDITION_2_LABEL')) { define('TEXT_CONDITION_2_LABEL','TEXT_CONDITION_2_LABEL',false); }
	if (!defined('TEXT_CONDITION_3_LABEL')) { define('TEXT_CONDITION_3_LABEL','TEXT_CONDITION_3_LABEL',false); }
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE',false); }
	if (!defined('TEXT_DAILY_VISIT_HEADING')) { define('TEXT_DAILY_VISIT_HEADING','TEXT_DAILY_VISIT_HEADING',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','TEXT_DATE_FORMAT',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','TEXT_DATE_LABEL',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','TEXT_DATE_PROMPT_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_1_ICD_LABEL')) { define('TEXT_DIAGNOSIS_1_ICD_LABEL','TEXT_DIAGNOSIS_1_ICD_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','TEXT_DIAGNOSIS_1_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_2_ICD_LABEL')) { define('TEXT_DIAGNOSIS_2_ICD_LABEL','TEXT_DIAGNOSIS_2_ICD_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','TEXT_DIAGNOSIS_2_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_3_ICD_LABEL')) { define('TEXT_DIAGNOSIS_3_ICD_LABEL','TEXT_DIAGNOSIS_3_ICD_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','TEXT_DIAGNOSIS_3_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','TEXT_DIAGNOSIS_BLANK',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','TEXT_EXPORT_CSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','TEXT_EXPORT_CSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','TEXT_EXPORT_TSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','TEXT_EXPORT_TSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','TEXT_FULLNAME_LABEL',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','TEXT_GROUP_ALL',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','TEXT_LABEL_DR_GENERAL',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','TEXT_LABEL_DR_SPECIALIST',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','TEXT_LABEL_DR_STUDENT',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','TEXT_LABEL_NURSE_AID',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','TEXT_LABEL_NURSE_PRO',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','TEXT_LABEL_NURSE_STU',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','TEXT_LABEL_STAFF',false); }
	if (!defined('TEXT_NEIGHBORHOOD_LABEL')) { define('TEXT_NEIGHBORHOOD_LABEL','TEXT_NEIGHBORHOOD_LABEL',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','TEXT_NO_REPORT_PROF_PROMPT',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','TEXT_NO_VISITS_FOUND_DAY',false); }
	if (!defined('TEXT_PATIENTVISITID_LABEL')) { define('TEXT_PATIENTVISITID_LABEL','TEXT_PATIENTVISITID_LABEL',false); }
	if (!defined('TEXT_PATIENT_LABEL')) { define('TEXT_PATIENT_LABEL','TEXT_PATIENT_LABEL',false); }
	if (!defined('TEXT_RECEIVED_FROM_LABEL')) { define('TEXT_RECEIVED_FROM_LABEL','TEXT_RECEIVED_FROM_LABEL',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false); }
	if (!defined('TEXT_REPORT_ADDRESS_LABEL')) { define('TEXT_REPORT_ADDRESS_LABEL','TEXT_REPORT_ADDRESS_LABEL',false); }
	if (!defined('TEXT_REPORT_AGE_LABEL')) { define('TEXT_REPORT_AGE_LABEL','TEXT_REPORT_AGE_LABEL',false); }
	if (!defined('TEXT_REPORT_CITY_LABEL')) { define('TEXT_REPORT_CITY_LABEL','TEXT_REPORT_CITY_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','TEXT_REPORT_CLINICNAME_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','TEXT_REPORT_CLINIC_CODE_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINIC_TYPE_LABEL')) { define('TEXT_REPORT_CLINIC_TYPE_LABEL','TEXT_REPORT_CLINIC_TYPE_LABEL',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','TEXT_REPORT_DATE_PLACEHOLDER',false); }
	if (!defined('TEXT_REPORT_DIAGNOSIS_LABEL')) { define('TEXT_REPORT_DIAGNOSIS_LABEL','TEXT_REPORT_DIAGNOSIS_LABEL',false); }
	if (!defined('TEXT_REPORT_REFERRAL_LABEL')) { define('TEXT_REPORT_REFERRAL_LABEL','TEXT_REPORT_REFERRAL_LABEL',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','TEXT_REPORT_ROW_LABEL',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','TEXT_REPORT_SERVICE_EMERGENCY',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','TEXT_REPORT_SERVICE_EXTERNAL',false); }
	if (!defined('TEXT_REPORT_SERVICE_FILTRO')) { define('TEXT_REPORT_SERVICE_FILTRO','TEXT_REPORT_SERVICE_FILTRO',false); }
	if (!defined('TEXT_REPORT_SERVICE_SPECIALTY')) { define('TEXT_REPORT_SERVICE_SPECIALTY','TEXT_REPORT_SERVICE_SPECIALTY',false); }
	if (!defined('TEXT_REPORT_SERVICE_TYPE')) { define('TEXT_REPORT_SERVICE_TYPE','TEXT_REPORT_SERVICE_TYPE',false); }
	if (!defined('TEXT_REPORT_STATE_LABEL')) { define('TEXT_REPORT_STATE_LABEL','TEXT_REPORT_STATE_LABEL',false); }
	if (!defined('TEXT_SEX_LABEL')) { define('TEXT_SEX_LABEL','TEXT_SEX_LABEL',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','TEXT_SHOW_REPORT_BUTTON',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','TEXT_SHOW_REPORT_DATE_FIELD_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','TEXT_SHOW_REPORT_DATE_FIELD_TITLE',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','TEXT_SHOW_REPORT_DATE_LIST_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','TEXT_SHOW_REPORT_DATE_LIST_TITLE',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','TEXT_STAFF_LABEL',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','TEXT_STAFF_NAME_LABEL',false); }
	if (!defined('TEXT_STATE_LABEL')) { define('TEXT_STATE_LABEL','TEXT_STATE_LABEL',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','TEXT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_AGE_DAYS_LABEL')) { define('TEXT_AGE_DAYS_LABEL','Days',false); }
	if (!defined('TEXT_AGE_MONTHS_LABEL')) { define('TEXT_AGE_MONTHS_LABEL','Mos',false); }
	if (!defined('TEXT_AGE_YEARS_LABEL')) { define('TEXT_AGE_YEARS_LABEL','Yrs',false); }
	if (!defined('TEXT_ATA_LEFT_TITLE')) { define('TEXT_ATA_LEFT_TITLE','Secretary of Health<br>Department of Statistics<br>Honduras, Central America',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_LABEL')) { define('TEXT_BIRTHDAY_DATE_LABEL','Birth date (M/D/Y)',false); }
	if (!defined('TEXT_CITY_LABEL')) { define('TEXT_CITY_LABEL','City',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','Patient&nbsp;ID',false); }
	if (!defined('TEXT_CONDITION_1_LABEL')) { define('TEXT_CONDITION_1_LABEL','Condition',false); }
	if (!defined('TEXT_CONDITION_2_LABEL')) { define('TEXT_CONDITION_2_LABEL','Condition',false); }
	if (!defined('TEXT_CONDITION_3_LABEL')) { define('TEXT_CONDITION_3_LABEL','Condition',false); }
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','Daily Report of Outpatient Care',false); }
	if (!defined('TEXT_DAILY_VISIT_HEADING')) { define('TEXT_DAILY_VISIT_HEADING','Daily Outpatient Log',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','Report date (YYYY-MM)',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','Date&nbsp;(yyyy-mm-dd)',false); }
	if (!defined('TEXT_DIAGNOSIS_1_ICD_LABEL')) { define('TEXT_DIAGNOSIS_1_ICD_LABEL','Diagnosis 1 (ICD-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','Diagnosis 1',false); }
	if (!defined('TEXT_DIAGNOSIS_2_ICD_LABEL')) { define('TEXT_DIAGNOSIS_2_ICD_LABEL','Diagnosis 2 (ICD-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','Diagnosis 2',false); }
	if (!defined('TEXT_DIAGNOSIS_3_ICD_LABEL')) { define('TEXT_DIAGNOSIS_3_ICD_LABEL','Diagnosis 3 (ICD-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','Diagnosis 3',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','(Not specified)',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Export as CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Export the report to a CSV file to open as a spreadsheet. This format opens in Excel automatically, but some letters might not appear correctly.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Export as TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Export the report to a TSV file to import into a spreadsheet. Importing this format might require several steps, but the data will appear correctly.',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Patient name',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','All',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','Doctor: General',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','Doctor: Specialist',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','Student doctor',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','Nurse: Aid',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','Nurse: Profesional',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','Student nurse',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','Clinic staff',false); }
	if (!defined('TEXT_NEIGHBORHOOD_LABEL')) { define('TEXT_NEIGHBORHOOD_LABEL','Neighborhood',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','Select a health professional and date.',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','No visits found for this date.',false); }
	if (!defined('TEXT_PATIENTVISITID_LABEL')) { define('TEXT_PATIENTVISITID_LABEL','Clinical history number',false); }
	if (!defined('TEXT_PATIENT_LABEL')) { define('TEXT_PATIENT_LABEL','Patient',false); }
	if (!defined('TEXT_RECEIVED_FROM_LABEL')) { define('TEXT_RECEIVED_FROM_LABEL','Received from',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Referred to',false); }
	if (!defined('TEXT_REPORT_ADDRESS_LABEL')) { define('TEXT_REPORT_ADDRESS_LABEL','Home town',false); }
	if (!defined('TEXT_REPORT_AGE_LABEL')) { define('TEXT_REPORT_AGE_LABEL','Age',false); }
	if (!defined('TEXT_REPORT_CITY_LABEL')) { define('TEXT_REPORT_CITY_LABEL','City',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Clinic',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','Code',false); }
	if (!defined('TEXT_REPORT_CLINIC_TYPE_LABEL')) { define('TEXT_REPORT_CLINIC_TYPE_LABEL','Clinic type',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','Report date (YYYY-MM-DD)',false); }
	if (!defined('TEXT_REPORT_DIAGNOSIS_LABEL')) { define('TEXT_REPORT_DIAGNOSIS_LABEL','Diagnoses/Activities',false); }
	if (!defined('TEXT_REPORT_REFERRAL_LABEL')) { define('TEXT_REPORT_REFERRAL_LABEL','Referrals',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','No.',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','Emergency',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','Outpatient',false); }
	if (!defined('TEXT_REPORT_SERVICE_FILTRO')) { define('TEXT_REPORT_SERVICE_FILTRO','Filter',false); }
	if (!defined('TEXT_REPORT_SERVICE_SPECIALTY')) { define('TEXT_REPORT_SERVICE_SPECIALTY','Specialty',false); }
	if (!defined('TEXT_REPORT_SERVICE_TYPE')) { define('TEXT_REPORT_SERVICE_TYPE','Type of<br>attention',false); }
	if (!defined('TEXT_REPORT_STATE_LABEL')) { define('TEXT_REPORT_STATE_LABEL','State',false); }
	if (!defined('TEXT_SEX_LABEL')) { define('TEXT_SEX_LABEL','Sex (M/F)',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Show report',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Show date entry field',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Display the field into which you can enter a date',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Show report date list',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Display the list of available report dates',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','Health professional',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','Name',false); }
	if (!defined('TEXT_STATE_LABEL')) { define('TEXT_STATE_LABEL','State',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','Service type',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','All',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_AGE_DAYS_LABEL')) { define('TEXT_AGE_DAYS_LABEL','Días',false); }
	if (!defined('TEXT_AGE_MONTHS_LABEL')) { define('TEXT_AGE_MONTHS_LABEL','Meses',false); }
	if (!defined('TEXT_AGE_YEARS_LABEL')) { define('TEXT_AGE_YEARS_LABEL','Años',false); }
	if (!defined('TEXT_ATA_LEFT_TITLE')) { define('TEXT_ATA_LEFT_TITLE','Secretaría de Salud<br>Departamento de Estadística<br>Honduras, Centro América',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_LABEL')) { define('TEXT_BIRTHDAY_DATE_LABEL','Fecha de nacimiento (dd/mm/aa)',false); }
	if (!defined('TEXT_CITY_LABEL')) { define('TEXT_CITY_LABEL','Municipio',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','Identidad&nbsp;del&nbsp;paciente',false); }
	if (!defined('TEXT_CONDITION_1_LABEL')) { define('TEXT_CONDITION_1_LABEL','Condición',false); }
	if (!defined('TEXT_CONDITION_2_LABEL')) { define('TEXT_CONDITION_2_LABEL','Condición',false); }
	if (!defined('TEXT_CONDITION_3_LABEL')) { define('TEXT_CONDITION_3_LABEL','Condición',false); }
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','Informe Diario de Atenciones Ambulatorias',false); }
	if (!defined('TEXT_DAILY_VISIT_HEADING')) { define('TEXT_DAILY_VISIT_HEADING','Atenciones Ambulatorias',false); }
	if (!defined('TEXT_DATE_FORMAT')) { define('TEXT_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','Fecha del informe (AAAA-MM)',false); }
	if (!defined('TEXT_DATE_PROMPT_LABEL')) { define('TEXT_DATE_PROMPT_LABEL','Fecha&nbsp;(aaaa-mm-dd)',false); }
	if (!defined('TEXT_DIAGNOSIS_1_ICD_LABEL')) { define('TEXT_DIAGNOSIS_1_ICD_LABEL','Diagnóstico 1 (CIE-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','Diagnóstico 1',false); }
	if (!defined('TEXT_DIAGNOSIS_2_ICD_LABEL')) { define('TEXT_DIAGNOSIS_2_ICD_LABEL','Diagnóstico 2 (CIE-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','Diagnóstico 2',false); }
	if (!defined('TEXT_DIAGNOSIS_3_ICD_LABEL')) { define('TEXT_DIAGNOSIS_3_ICD_LABEL','Diagnóstico 3 (CIE-10)',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','Diagnóstico 3',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','(No especificada)',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Descargar como CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Descarga el informe como un archivo CSV para abrirlo como una hoja de cálculo. Excel puede abrir este formato automáticamente, pero no muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Descargar como TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Descarga el informe como un archivo TSV para importarlo como una hoja de cálculo. Excel puede importar este formato manualmente y muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Nombre del paciente',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','Todos',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','Médico: General',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','Médico: Especialista',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','Estudiante de medicina',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','Enfermera: Auxiliar',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','Enfermera: Profesional',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','Estudiante de enfermeria',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','Personal de la clínica',false); }
	if (!defined('TEXT_NEIGHBORHOOD_LABEL')) { define('TEXT_NEIGHBORHOOD_LABEL','Localidad',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificada',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','Escoge un profesional de salud y una fecha.',false); }
	if (!defined('TEXT_NO_VISITS_FOUND_DAY')) { define('TEXT_NO_VISITS_FOUND_DAY','No se encontraron visitas en esta fecha.',false); }
	if (!defined('TEXT_PATIENTVISITID_LABEL')) { define('TEXT_PATIENTVISITID_LABEL','Número de historia clínica',false); }
	if (!defined('TEXT_PATIENT_LABEL')) { define('TEXT_PATIENT_LABEL','Paciente',false); }
	if (!defined('TEXT_RECEIVED_FROM_LABEL')) { define('TEXT_RECEIVED_FROM_LABEL','Recibida de',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Enviada a',false); }
	if (!defined('TEXT_REPORT_ADDRESS_LABEL')) { define('TEXT_REPORT_ADDRESS_LABEL','Procedencia',false); }
	if (!defined('TEXT_REPORT_AGE_LABEL')) { define('TEXT_REPORT_AGE_LABEL','Edad',false); }
	if (!defined('TEXT_REPORT_CITY_LABEL')) { define('TEXT_REPORT_CITY_LABEL','Municipio',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Establecimiento',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','Código',false); }
	if (!defined('TEXT_REPORT_CLINIC_TYPE_LABEL')) { define('TEXT_REPORT_CLINIC_TYPE_LABEL','Tipo de establecimiento',false); }
	if (!defined('TEXT_REPORT_DATE_PLACEHOLDER')) { define('TEXT_REPORT_DATE_PLACEHOLDER','Report date (AAAA-MM-DD)',false); }
	if (!defined('TEXT_REPORT_DIAGNOSIS_LABEL')) { define('TEXT_REPORT_DIAGNOSIS_LABEL','Diagnóstico/Actividad',false); }
	if (!defined('TEXT_REPORT_REFERRAL_LABEL')) { define('TEXT_REPORT_REFERRAL_LABEL','Remisiones',false); }
	if (!defined('TEXT_REPORT_ROW_LABEL')) { define('TEXT_REPORT_ROW_LABEL','No.',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','Emergencia',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','Consulta externa',false); }
	if (!defined('TEXT_REPORT_SERVICE_FILTRO')) { define('TEXT_REPORT_SERVICE_FILTRO','Filtro',false); }
	if (!defined('TEXT_REPORT_SERVICE_SPECIALTY')) { define('TEXT_REPORT_SERVICE_SPECIALTY','Especialidad',false); }
	if (!defined('TEXT_REPORT_SERVICE_TYPE')) { define('TEXT_REPORT_SERVICE_TYPE','Servicio de<br>Atención',false); }
	if (!defined('TEXT_REPORT_STATE_LABEL')) { define('TEXT_REPORT_STATE_LABEL','Departamento',false); }
	if (!defined('TEXT_SEX_LABEL')) { define('TEXT_SEX_LABEL','Sexo (H/M)',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','M',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','H',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Mostrar informe',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Muestra el campo para la fecha',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Muestra el campo para entrar la fecha directamente',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Muestra la lista de fechas',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Muestra la lista de fechas que tengan informes',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','Profesional de salud',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','Nombre',false); }
	if (!defined('TEXT_STATE_LABEL')) { define('TEXT_STATE_LABEL','Departamento',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','Servicio de atención',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','Todos',false); }
}
//EOF
