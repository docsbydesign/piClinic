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
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','TEXT_DATE_LABEL',false); }
	if (!defined('TEXT_DECIMAL_POINT')) { define('TEXT_DECIMAL_POINT','TEXT_DECIMAL_POINT',false); }
	if (!defined('TEXT_DIAGDATA_AGE_GROUP')) { define('TEXT_DIAGDATA_AGE_GROUP','TEXT_DIAGDATA_AGE_GROUP',false); }
	if (!defined('TEXT_DIAGDATA_COUNT_HEADING')) { define('TEXT_DIAGDATA_COUNT_HEADING','TEXT_DIAGDATA_COUNT_HEADING',false); }
	if (!defined('TEXT_DIAGDATA_HEADING')) { define('TEXT_DIAGDATA_HEADING','TEXT_DIAGDATA_HEADING',false); }
	if (!defined('TEXT_DIAGDATA_MISSING_TEXT')) { define('TEXT_DIAGDATA_MISSING_TEXT','TEXT_DIAGDATA_MISSING_TEXT',false); }
	if (!defined('TEXT_DIAGDATA_REPORT_LINE')) { define('TEXT_DIAGDATA_REPORT_LINE','TEXT_DIAGDATA_REPORT_LINE',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','TEXT_EXPORT_CSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','TEXT_EXPORT_CSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','TEXT_EXPORT_TSV_BUTTON',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','TEXT_EXPORT_TSV_BUTTON_TEXT',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','TEXT_GROUP_ALL',false); }
	if (!defined('TEXT_GROUP_LABEL')) { define('TEXT_GROUP_LABEL','TEXT_GROUP_LABEL',false); }
	if (!defined('TEXT_HEADING_CONCEPT')) { define('TEXT_HEADING_CONCEPT','TEXT_HEADING_CONCEPT',false); }
	if (!defined('TEXT_HEADING_DOCTOR')) { define('TEXT_HEADING_DOCTOR','TEXT_HEADING_DOCTOR',false); }
	if (!defined('TEXT_HEADING_DR_GENERAL')) { define('TEXT_HEADING_DR_GENERAL','TEXT_HEADING_DR_GENERAL',false); }
	if (!defined('TEXT_HEADING_DR_SPECIALIST')) { define('TEXT_HEADING_DR_SPECIALIST','TEXT_HEADING_DR_SPECIALIST',false); }
	if (!defined('TEXT_HEADING_NURSE')) { define('TEXT_HEADING_NURSE','TEXT_HEADING_NURSE',false); }
	if (!defined('TEXT_HEADING_NURSE_AID')) { define('TEXT_HEADING_NURSE_AID','TEXT_HEADING_NURSE_AID',false); }
	if (!defined('TEXT_HEADING_NURSE_PRO')) { define('TEXT_HEADING_NURSE_PRO','TEXT_HEADING_NURSE_PRO',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_NAME')) { define('TEXT_HEADING_PROFESSIONAL_NAME','TEXT_HEADING_PROFESSIONAL_NAME',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_TYPE')) { define('TEXT_HEADING_PROFESSIONAL_TYPE','TEXT_HEADING_PROFESSIONAL_TYPE',false); }
	if (!defined('TEXT_HEADING_TOTAL')) { define('TEXT_HEADING_TOTAL','TEXT_HEADING_TOTAL',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','TEXT_LABEL_DR_GENERAL',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','TEXT_LABEL_DR_SPECIALIST',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','TEXT_LABEL_DR_STUDENT',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','TEXT_LABEL_NURSE_AID',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','TEXT_LABEL_NURSE_PRO',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','TEXT_LABEL_NURSE_STU',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','TEXT_LABEL_STAFF',false); }
	if (!defined('TEXT_MO_DATE_PROMPT_LABEL')) { define('TEXT_MO_DATE_PROMPT_LABEL','TEXT_MO_DATE_PROMPT_LABEL',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','TEXT_NO_REPORT_PROF_PROMPT',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','TEXT_REPORT_CLINICNAME_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINIC_CISUAPS_LABEL')) { define('TEXT_REPORT_CLINIC_CISUAPS_LABEL','TEXT_REPORT_CLINIC_CISUAPS_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','TEXT_REPORT_CLINIC_CODE_LABEL',false); }
	if (!defined('TEXT_REPORT_CLINIC_LEVEL_LABEL')) { define('TEXT_REPORT_CLINIC_LEVEL_LABEL','TEXT_REPORT_CLINIC_LEVEL_LABEL',false); }
	if (!defined('TEXT_REPORT_COLUMN_TOTAL')) { define('TEXT_REPORT_COLUMN_TOTAL','TEXT_REPORT_COLUMN_TOTAL',false); }
	if (!defined('TEXT_REPORT_DAY')) { define('TEXT_REPORT_DAY','TEXT_REPORT_DAY',false); }
	if (!defined('TEXT_REPORT_MONTH_LABEL')) { define('TEXT_REPORT_MONTH_LABEL','TEXT_REPORT_MONTH_LABEL',false); }
	if (!defined('TEXT_REPORT_MONTH_PLACEHOLDER')) { define('TEXT_REPORT_MONTH_PLACEHOLDER','TEXT_REPORT_MONTH_PLACEHOLDER',false); }
	if (!defined('TEXT_REPORT_NO_DATA')) { define('TEXT_REPORT_NO_DATA','TEXT_REPORT_NO_DATA',false); }
	if (!defined('TEXT_REPORT_REGION_LABEL')) { define('TEXT_REPORT_REGION_LABEL','TEXT_REPORT_REGION_LABEL',false); }
	if (!defined('TEXT_REPORT_SDS')) { define('TEXT_REPORT_SDS','TEXT_REPORT_SDS',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','TEXT_REPORT_SERVICE_EMERGENCY',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','TEXT_REPORT_SERVICE_EXTERNAL',false); }
	if (!defined('TEXT_REPORT_YEAR_LABEL')) { define('TEXT_REPORT_YEAR_LABEL','TEXT_REPORT_YEAR_LABEL',false); }
	if (!defined('TEXT_REPORT_YEAR_PLACEHOLDER')) { define('TEXT_REPORT_YEAR_PLACEHOLDER','TEXT_REPORT_YEAR_PLACEHOLDER',false); }
	if (!defined('TEXT_RPT_LINE_01')) { define('TEXT_RPT_LINE_01','TEXT_RPT_LINE_01',false); }
	if (!defined('TEXT_RPT_LINE_02')) { define('TEXT_RPT_LINE_02','TEXT_RPT_LINE_02',false); }
	if (!defined('TEXT_RPT_LINE_03')) { define('TEXT_RPT_LINE_03','TEXT_RPT_LINE_03',false); }
	if (!defined('TEXT_RPT_LINE_04')) { define('TEXT_RPT_LINE_04','TEXT_RPT_LINE_04',false); }
	if (!defined('TEXT_RPT_LINE_05')) { define('TEXT_RPT_LINE_05','TEXT_RPT_LINE_05',false); }
	if (!defined('TEXT_RPT_LINE_06')) { define('TEXT_RPT_LINE_06','TEXT_RPT_LINE_06',false); }
	if (!defined('TEXT_RPT_LINE_07')) { define('TEXT_RPT_LINE_07','TEXT_RPT_LINE_07',false); }
	if (!defined('TEXT_RPT_LINE_08')) { define('TEXT_RPT_LINE_08','TEXT_RPT_LINE_08',false); }
	if (!defined('TEXT_RPT_LINE_09')) { define('TEXT_RPT_LINE_09','TEXT_RPT_LINE_09',false); }
	if (!defined('TEXT_RPT_LINE_10')) { define('TEXT_RPT_LINE_10','TEXT_RPT_LINE_10',false); }
	if (!defined('TEXT_RPT_LINE_11')) { define('TEXT_RPT_LINE_11','TEXT_RPT_LINE_11',false); }
	if (!defined('TEXT_RPT_LINE_12')) { define('TEXT_RPT_LINE_12','TEXT_RPT_LINE_12',false); }
	if (!defined('TEXT_RPT_LINE_13')) { define('TEXT_RPT_LINE_13','TEXT_RPT_LINE_13',false); }
	if (!defined('TEXT_RPT_LINE_14')) { define('TEXT_RPT_LINE_14','TEXT_RPT_LINE_14',false); }
	if (!defined('TEXT_RPT_LINE_15')) { define('TEXT_RPT_LINE_15','TEXT_RPT_LINE_15',false); }
	if (!defined('TEXT_RPT_LINE_16')) { define('TEXT_RPT_LINE_16','TEXT_RPT_LINE_16',false); }
	if (!defined('TEXT_RPT_LINE_17')) { define('TEXT_RPT_LINE_17','TEXT_RPT_LINE_17',false); }
	if (!defined('TEXT_RPT_LINE_18')) { define('TEXT_RPT_LINE_18','TEXT_RPT_LINE_18',false); }
	if (!defined('TEXT_RPT_LINE_19')) { define('TEXT_RPT_LINE_19','TEXT_RPT_LINE_19',false); }
	if (!defined('TEXT_RPT_LINE_20')) { define('TEXT_RPT_LINE_20','TEXT_RPT_LINE_20',false); }
	if (!defined('TEXT_RPT_LINE_21')) { define('TEXT_RPT_LINE_21','TEXT_RPT_LINE_21',false); }
	if (!defined('TEXT_RPT_LINE_22')) { define('TEXT_RPT_LINE_22','TEXT_RPT_LINE_22',false); }
	if (!defined('TEXT_RPT_LINE_23')) { define('TEXT_RPT_LINE_23','TEXT_RPT_LINE_23',false); }
	if (!defined('TEXT_RPT_LINE_24')) { define('TEXT_RPT_LINE_24','TEXT_RPT_LINE_24',false); }
	if (!defined('TEXT_RPT_LINE_25')) { define('TEXT_RPT_LINE_25','TEXT_RPT_LINE_25',false); }
	if (!defined('TEXT_RPT_LINE_26')) { define('TEXT_RPT_LINE_26','TEXT_RPT_LINE_26',false); }
	if (!defined('TEXT_RPT_LINE_27')) { define('TEXT_RPT_LINE_27','TEXT_RPT_LINE_27',false); }
	if (!defined('TEXT_RPT_LINE_28')) { define('TEXT_RPT_LINE_28','TEXT_RPT_LINE_28',false); }
	if (!defined('TEXT_RPT_LINE_29')) { define('TEXT_RPT_LINE_29','TEXT_RPT_LINE_29',false); }
	if (!defined('TEXT_RPT_LINE_30')) { define('TEXT_RPT_LINE_30','TEXT_RPT_LINE_30',false); }
	if (!defined('TEXT_RPT_LINE_31')) { define('TEXT_RPT_LINE_31','TEXT_RPT_LINE_31',false); }
	if (!defined('TEXT_RPT_LINE_32')) { define('TEXT_RPT_LINE_32','TEXT_RPT_LINE_32',false); }
	if (!defined('TEXT_RPT_LINE_33')) { define('TEXT_RPT_LINE_33','TEXT_RPT_LINE_33',false); }
	if (!defined('TEXT_RPT_LINE_34')) { define('TEXT_RPT_LINE_34','TEXT_RPT_LINE_34',false); }
	if (!defined('TEXT_RPT_LINE_35')) { define('TEXT_RPT_LINE_35','TEXT_RPT_LINE_35',false); }
	if (!defined('TEXT_RPT_LINE_36')) { define('TEXT_RPT_LINE_36','TEXT_RPT_LINE_36',false); }
	if (!defined('TEXT_RPT_LINE_37')) { define('TEXT_RPT_LINE_37','TEXT_RPT_LINE_37',false); }
	if (!defined('TEXT_RPT_LINE_38')) { define('TEXT_RPT_LINE_38','TEXT_RPT_LINE_38',false); }
	if (!defined('TEXT_RPT_LINE_39')) { define('TEXT_RPT_LINE_39','TEXT_RPT_LINE_39',false); }
	if (!defined('TEXT_RPT_LINE_40')) { define('TEXT_RPT_LINE_40','TEXT_RPT_LINE_40',false); }
	if (!defined('TEXT_RPT_LINE_41')) { define('TEXT_RPT_LINE_41','TEXT_RPT_LINE_41',false); }
	if (!defined('TEXT_RPT_LINE_42')) { define('TEXT_RPT_LINE_42','TEXT_RPT_LINE_42',false); }
	if (!defined('TEXT_RPT_LINE_43')) { define('TEXT_RPT_LINE_43','TEXT_RPT_LINE_43',false); }
	if (!defined('TEXT_RPT_LINE_44')) { define('TEXT_RPT_LINE_44','TEXT_RPT_LINE_44',false); }
	if (!defined('TEXT_RPT_LINE_45')) { define('TEXT_RPT_LINE_45','TEXT_RPT_LINE_45',false); }
	if (!defined('TEXT_RPT_LINE_46')) { define('TEXT_RPT_LINE_46','TEXT_RPT_LINE_46',false); }
	if (!defined('TEXT_RPT_LINE_47')) { define('TEXT_RPT_LINE_47','TEXT_RPT_LINE_47',false); }
	if (!defined('TEXT_RPT_LINE_48')) { define('TEXT_RPT_LINE_48','TEXT_RPT_LINE_48',false); }
	if (!defined('TEXT_RPT_LINE_49')) { define('TEXT_RPT_LINE_49','TEXT_RPT_LINE_49',false); }
	if (!defined('TEXT_RPT_LINE_50')) { define('TEXT_RPT_LINE_50','TEXT_RPT_LINE_50',false); }
	if (!defined('TEXT_RPT_LINE_51')) { define('TEXT_RPT_LINE_51','TEXT_RPT_LINE_51',false); }
	if (!defined('TEXT_RPT_LINE_52')) { define('TEXT_RPT_LINE_52','TEXT_RPT_LINE_52',false); }
	if (!defined('TEXT_RPT_MONTH_01')) { define('TEXT_RPT_MONTH_01','TEXT_RPT_MONTH_01',false); }
	if (!defined('TEXT_RPT_MONTH_02')) { define('TEXT_RPT_MONTH_02','TEXT_RPT_MONTH_02',false); }
	if (!defined('TEXT_RPT_MONTH_03')) { define('TEXT_RPT_MONTH_03','TEXT_RPT_MONTH_03',false); }
	if (!defined('TEXT_RPT_MONTH_04')) { define('TEXT_RPT_MONTH_04','TEXT_RPT_MONTH_04',false); }
	if (!defined('TEXT_RPT_MONTH_05')) { define('TEXT_RPT_MONTH_05','TEXT_RPT_MONTH_05',false); }
	if (!defined('TEXT_RPT_MONTH_06')) { define('TEXT_RPT_MONTH_06','TEXT_RPT_MONTH_06',false); }
	if (!defined('TEXT_RPT_MONTH_07')) { define('TEXT_RPT_MONTH_07','TEXT_RPT_MONTH_07',false); }
	if (!defined('TEXT_RPT_MONTH_08')) { define('TEXT_RPT_MONTH_08','TEXT_RPT_MONTH_08',false); }
	if (!defined('TEXT_RPT_MONTH_09')) { define('TEXT_RPT_MONTH_09','TEXT_RPT_MONTH_09',false); }
	if (!defined('TEXT_RPT_MONTH_10')) { define('TEXT_RPT_MONTH_10','TEXT_RPT_MONTH_10',false); }
	if (!defined('TEXT_RPT_MONTH_11')) { define('TEXT_RPT_MONTH_11','TEXT_RPT_MONTH_11',false); }
	if (!defined('TEXT_RPT_MONTH_12')) { define('TEXT_RPT_MONTH_12','TEXT_RPT_MONTH_12',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','TEXT_SHOW_REPORT_BUTTON',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','TEXT_SHOW_REPORT_DATE_FIELD_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','TEXT_SHOW_REPORT_DATE_FIELD_TITLE',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','TEXT_SHOW_REPORT_DATE_LIST_LINK',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','TEXT_SHOW_REPORT_DATE_LIST_TITLE',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','TEXT_STAFF_LABEL',false); }
	if (!defined('TEXT_THOUSANDS_SEPARATOR')) { define('TEXT_THOUSANDS_SEPARATOR','TEXT_THOUSANDS_SEPARATOR',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','TEXT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','TEXT_VISIT_TYPE_ALL',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','Daily Report of Outpatient Care',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','Report date (YYYY-MM)',false); }
	if (!defined('TEXT_DECIMAL_POINT')) { define('TEXT_DECIMAL_POINT','.',false); }
	if (!defined('TEXT_DIAGDATA_AGE_GROUP')) { define('TEXT_DIAGDATA_AGE_GROUP',' only for patients aged ',false); }
	if (!defined('TEXT_DIAGDATA_COUNT_HEADING')) { define('TEXT_DIAGDATA_COUNT_HEADING','Counts these diagnoses',false); }
	if (!defined('TEXT_DIAGDATA_HEADING')) { define('TEXT_DIAGDATA_HEADING','Diagnoses counted in this report',false); }
	if (!defined('TEXT_DIAGDATA_MISSING_TEXT')) { define('TEXT_DIAGDATA_MISSING_TEXT','Text missing',false); }
	if (!defined('TEXT_DIAGDATA_REPORT_LINE')) { define('TEXT_DIAGDATA_REPORT_LINE','Report line',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Export as CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Export the report to a CSV file to open as a spreadsheet. This format opens in Excel automatically, but some letters might not appear correctly.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Export as TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Export the report to a TSV file to import into a spreadsheet. Importing this format might require several steps, but the data will appear correctly.',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','All',false); }
	if (!defined('TEXT_GROUP_LABEL')) { define('TEXT_GROUP_LABEL','Medical professional name',false); }
	if (!defined('TEXT_HEADING_CONCEPT')) { define('TEXT_HEADING_CONCEPT','Concept',false); }
	if (!defined('TEXT_HEADING_DOCTOR')) { define('TEXT_HEADING_DOCTOR','Doctor',false); }
	if (!defined('TEXT_HEADING_DR_GENERAL')) { define('TEXT_HEADING_DR_GENERAL','General',false); }
	if (!defined('TEXT_HEADING_DR_SPECIALIST')) { define('TEXT_HEADING_DR_SPECIALIST','Specialist',false); }
	if (!defined('TEXT_HEADING_NURSE')) { define('TEXT_HEADING_NURSE','Nurse',false); }
	if (!defined('TEXT_HEADING_NURSE_AID')) { define('TEXT_HEADING_NURSE_AID','Aid',false); }
	if (!defined('TEXT_HEADING_NURSE_PRO')) { define('TEXT_HEADING_NURSE_PRO','Profesional',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_NAME')) { define('TEXT_HEADING_PROFESSIONAL_NAME','Name',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_TYPE')) { define('TEXT_HEADING_PROFESSIONAL_TYPE','Type of health professional',false); }
	if (!defined('TEXT_HEADING_TOTAL')) { define('TEXT_HEADING_TOTAL','TOTAL',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','Doctor: General',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','Doctor: Specialist',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','Student doctor',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','Nurse: Aid',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','Nurse: Profesional',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','Student nurse',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','Clinic staff',false); }
	if (!defined('TEXT_MO_DATE_PROMPT_LABEL')) { define('TEXT_MO_DATE_PROMPT_LABEL','Date&nbsp;(yyyy-mm)',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','Select a health professional and date.',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Clinic',false); }
	if (!defined('TEXT_REPORT_CLINIC_CISUAPS_LABEL')) { define('TEXT_REPORT_CLINIC_CISUAPS_LABEL','CIS/UAPS',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','Code',false); }
	if (!defined('TEXT_REPORT_CLINIC_LEVEL_LABEL')) { define('TEXT_REPORT_CLINIC_LEVEL_LABEL','Level',false); }
	if (!defined('TEXT_REPORT_COLUMN_TOTAL')) { define('TEXT_REPORT_COLUMN_TOTAL','TOTAL',false); }
	if (!defined('TEXT_REPORT_DAY')) { define('TEXT_REPORT_DAY','Day of month',false); }
	if (!defined('TEXT_REPORT_MONTH_LABEL')) { define('TEXT_REPORT_MONTH_LABEL','Month',false); }
	if (!defined('TEXT_REPORT_MONTH_PLACEHOLDER')) { define('TEXT_REPORT_MONTH_PLACEHOLDER','Report month (MM)',false); }
	if (!defined('TEXT_REPORT_NO_DATA')) { define('TEXT_REPORT_NO_DATA','No data for this month',false); }
	if (!defined('TEXT_REPORT_REGION_LABEL')) { define('TEXT_REPORT_REGION_LABEL','Health district',false); }
	if (!defined('TEXT_REPORT_SDS')) { define('TEXT_REPORT_SDS','Secretary of Health',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','Emergency',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','Outpatient',false); }
	if (!defined('TEXT_REPORT_YEAR_LABEL')) { define('TEXT_REPORT_YEAR_LABEL','Year',false); }
	if (!defined('TEXT_REPORT_YEAR_PLACEHOLDER')) { define('TEXT_REPORT_YEAR_PLACEHOLDER','Report year (YYYY)',false); }
	if (!defined('TEXT_RPT_LINE_01')) { define('TEXT_RPT_LINE_01','Children less than 1 mon.-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_02')) { define('TEXT_RPT_LINE_02','Children less than 1 mon.-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_03')) { define('TEXT_RPT_LINE_03','1 mon. to 1 year-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_04')) { define('TEXT_RPT_LINE_04','1 mon. to 1 year-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_05')) { define('TEXT_RPT_LINE_05','1 to 4 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_06')) { define('TEXT_RPT_LINE_06','1 to 4 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_07')) { define('TEXT_RPT_LINE_07','5 to 9 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_08')) { define('TEXT_RPT_LINE_08','5 to 9 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_09')) { define('TEXT_RPT_LINE_09','10 to 14 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_10')) { define('TEXT_RPT_LINE_10','10 to 14 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_11')) { define('TEXT_RPT_LINE_11','15 to 19 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_12')) { define('TEXT_RPT_LINE_12','15 to 19 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_13')) { define('TEXT_RPT_LINE_13','20 to 49 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_14')) { define('TEXT_RPT_LINE_14','20 to 49 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_15')) { define('TEXT_RPT_LINE_15','50 to 59 years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_16')) { define('TEXT_RPT_LINE_16','50 to 59 years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_17')) { define('TEXT_RPT_LINE_17','60+ years-1st visit',false); }
	if (!defined('TEXT_RPT_LINE_18')) { define('TEXT_RPT_LINE_18','60+ years-subsequent',false); }
	if (!defined('TEXT_RPT_LINE_19')) { define('TEXT_RPT_LINE_19','Total outpatients seen',false); }
	if (!defined('TEXT_RPT_LINE_20')) { define('TEXT_RPT_LINE_20','No. Patients seen (Women)',false); }
	if (!defined('TEXT_RPT_LINE_21')) { define('TEXT_RPT_LINE_21','No. Patients seen(Men)',false); }
	if (!defined('TEXT_RPT_LINE_22')) { define('TEXT_RPT_LINE_22','No. Outpatients',false); }
	if (!defined('TEXT_RPT_LINE_23')) { define('TEXT_RPT_LINE_23','No. Referred patients',false); }
	if (!defined('TEXT_RPT_LINE_24')) { define('TEXT_RPT_LINE_24','Detection of respiratory symptoms',false); }
	if (!defined('TEXT_RPT_LINE_25')) { define('TEXT_RPT_LINE_25','Detection of cervical-uterine cancer',false); }
	if (!defined('TEXT_RPT_LINE_26')) { define('TEXT_RPT_LINE_26','New pregnancies',false); }
	if (!defined('TEXT_RPT_LINE_27')) { define('TEXT_RPT_LINE_27','Normal pregnancies',false); }
	if (!defined('TEXT_RPT_LINE_28')) { define('TEXT_RPT_LINE_28','Puerperales controls',false); }
	if (!defined('TEXT_RPT_LINE_29')) { define('TEXT_RPT_LINE_29','Oral contraceptives: 1 cycle',false); }
	if (!defined('TEXT_RPT_LINE_30')) { define('TEXT_RPT_LINE_30','Oral contraceptives: 3 cycles',false); }
	if (!defined('TEXT_RPT_LINE_31')) { define('TEXT_RPT_LINE_31','Oral contraceptives: 6 cycles',false); }
	if (!defined('TEXT_RPT_LINE_32')) { define('TEXT_RPT_LINE_32','Condoms: 10 units',false); }
	if (!defined('TEXT_RPT_LINE_33')) { define('TEXT_RPT_LINE_33','Condoms: 30 units',false); }
	if (!defined('TEXT_RPT_LINE_34')) { define('TEXT_RPT_LINE_34','Depo provera applied',false); }
	if (!defined('TEXT_RPT_LINE_35')) { define('TEXT_RPT_LINE_35','IUDs inserted',false); }
	if (!defined('TEXT_RPT_LINE_36')) { define('TEXT_RPT_LINE_36','Vaginal ring',false); }
	if (!defined('TEXT_RPT_LINE_37')) { define('TEXT_RPT_LINE_37','Sub-dermal implant',false); }
	if (!defined('TEXT_RPT_LINE_38')) { define('TEXT_RPT_LINE_38','No. children &lt; 5 yo. with Diarrhea',false); }
	if (!defined('TEXT_RPT_LINE_39')) { define('TEXT_RPT_LINE_39','No. children &lt; 5 yo. with Diarrhea in follow-up visit',false); }
	if (!defined('TEXT_RPT_LINE_40')) { define('TEXT_RPT_LINE_40','No. children &lt; 5 yo. with Dehydration: rehydrated in clinic',false); }
	if (!defined('TEXT_RPT_LINE_41')) { define('TEXT_RPT_LINE_41','No. children &lt; 5 yo. with Pneumonia (new this year)',false); }
	if (!defined('TEXT_RPT_LINE_42')) { define('TEXT_RPT_LINE_42','No. children &lt; 5 yo. with neumonia in follow-up visit',false); }
	if (!defined('TEXT_RPT_LINE_43')) { define('TEXT_RPT_LINE_43','No. children &lt; 5 yo. with any grade of anemic syndrome',false); }
	if (!defined('TEXT_RPT_LINE_44')) { define('TEXT_RPT_LINE_44','Total no. children &lt; 5 yo. seen',false); }
	if (!defined('TEXT_RPT_LINE_45')) { define('TEXT_RPT_LINE_45','No. children &lt; 5 yo. with adequate growth',false); }
	if (!defined('TEXT_RPT_LINE_46')) { define('TEXT_RPT_LINE_46','No. children &lt; 5 yo. with inadequate growth',false); }
	if (!defined('TEXT_RPT_LINE_47')) { define('TEXT_RPT_LINE_47','No. children &lt; 5 yo. with development below 3rd percentile',false); }
	if (!defined('TEXT_RPT_LINE_48')) { define('TEXT_RPT_LINE_48','No. children &lt; 5 yo. with severe malnutrition',false); }
	if (!defined('TEXT_RPT_LINE_49')) { define('TEXT_RPT_LINE_49','No. children &lt; 5 yo. with a new disability',false); }
	if (!defined('TEXT_RPT_LINE_50')) { define('TEXT_RPT_LINE_50','No. children &lt; 5 yo. with probable change in development',false); }
	if (!defined('TEXT_RPT_LINE_51')) { define('TEXT_RPT_LINE_51','Prenatal attention in first 12 weeks of pregnancy',false); }
	if (!defined('TEXT_RPT_LINE_52')) { define('TEXT_RPT_LINE_52','Puerperal attention in first 10 days',false); }
	if (!defined('TEXT_RPT_MONTH_01')) { define('TEXT_RPT_MONTH_01','January',false); }
	if (!defined('TEXT_RPT_MONTH_02')) { define('TEXT_RPT_MONTH_02','February',false); }
	if (!defined('TEXT_RPT_MONTH_03')) { define('TEXT_RPT_MONTH_03','March',false); }
	if (!defined('TEXT_RPT_MONTH_04')) { define('TEXT_RPT_MONTH_04','April',false); }
	if (!defined('TEXT_RPT_MONTH_05')) { define('TEXT_RPT_MONTH_05','May',false); }
	if (!defined('TEXT_RPT_MONTH_06')) { define('TEXT_RPT_MONTH_06','June',false); }
	if (!defined('TEXT_RPT_MONTH_07')) { define('TEXT_RPT_MONTH_07','July',false); }
	if (!defined('TEXT_RPT_MONTH_08')) { define('TEXT_RPT_MONTH_08','August',false); }
	if (!defined('TEXT_RPT_MONTH_09')) { define('TEXT_RPT_MONTH_09','September',false); }
	if (!defined('TEXT_RPT_MONTH_10')) { define('TEXT_RPT_MONTH_10','October',false); }
	if (!defined('TEXT_RPT_MONTH_11')) { define('TEXT_RPT_MONTH_11','November',false); }
	if (!defined('TEXT_RPT_MONTH_12')) { define('TEXT_RPT_MONTH_12','December',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Show report',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Show date entry field',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Display the field into which you can enter a date',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Show report date list',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Display the list of available report dates',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','Health professional',false); }
	if (!defined('TEXT_THOUSANDS_SEPARATOR')) { define('TEXT_THOUSANDS_SEPARATOR',',',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','Service type',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','All',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE')) { define('TEXT_DAILY_OUTPATIENT_LOG_PAGE_TITLE','Informe Diario de Atenciones Ambulatorias (AT2)',false); }
	if (!defined('TEXT_DATE_LABEL')) { define('TEXT_DATE_LABEL','Fecha del informe (AAAA-MM)',false); }
	if (!defined('TEXT_DECIMAL_POINT')) { define('TEXT_DECIMAL_POINT',',',false); }
	if (!defined('TEXT_DIAGDATA_AGE_GROUP')) { define('TEXT_DIAGDATA_AGE_GROUP',' solo para los pacientes con edades ',false); }
	if (!defined('TEXT_DIAGDATA_COUNT_HEADING')) { define('TEXT_DIAGDATA_COUNT_HEADING','Cuenta estos diagnósticos',false); }
	if (!defined('TEXT_DIAGDATA_HEADING')) { define('TEXT_DIAGDATA_HEADING','Diagnósticos reportados en este informe',false); }
	if (!defined('TEXT_DIAGDATA_MISSING_TEXT')) { define('TEXT_DIAGDATA_MISSING_TEXT','Texto no presente',false); }
	if (!defined('TEXT_DIAGDATA_REPORT_LINE')) { define('TEXT_DIAGDATA_REPORT_LINE','Fila del informe',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON')) { define('TEXT_EXPORT_CSV_BUTTON','Descargar como CSV',false); }
	if (!defined('TEXT_EXPORT_CSV_BUTTON_TEXT')) { define('TEXT_EXPORT_CSV_BUTTON_TEXT','Descarga el informe como un archivo CSV para abrirlo como una hoja de cálculo. Excel puede abrir este formato automáticamente, pero no muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON')) { define('TEXT_EXPORT_TSV_BUTTON','Descargar como TSV',false); }
	if (!defined('TEXT_EXPORT_TSV_BUTTON_TEXT')) { define('TEXT_EXPORT_TSV_BUTTON_TEXT','Descarga el informe como un archivo TSV para importarlo como una hoja de cálculo. Excel puede importar este formato manualmente y muestra todas las letras correctamente.',false); }
	if (!defined('TEXT_GROUP_ALL')) { define('TEXT_GROUP_ALL','Todos',false); }
	if (!defined('TEXT_GROUP_LABEL')) { define('TEXT_GROUP_LABEL','Nombre de profesional médico',false); }
	if (!defined('TEXT_HEADING_CONCEPT')) { define('TEXT_HEADING_CONCEPT','Concepto',false); }
	if (!defined('TEXT_HEADING_DOCTOR')) { define('TEXT_HEADING_DOCTOR','Médico',false); }
	if (!defined('TEXT_HEADING_DR_GENERAL')) { define('TEXT_HEADING_DR_GENERAL','General',false); }
	if (!defined('TEXT_HEADING_DR_SPECIALIST')) { define('TEXT_HEADING_DR_SPECIALIST','Especialista',false); }
	if (!defined('TEXT_HEADING_NURSE')) { define('TEXT_HEADING_NURSE','Enfermera',false); }
	if (!defined('TEXT_HEADING_NURSE_AID')) { define('TEXT_HEADING_NURSE_AID','Auxiliar',false); }
	if (!defined('TEXT_HEADING_NURSE_PRO')) { define('TEXT_HEADING_NURSE_PRO','Profesional',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_NAME')) { define('TEXT_HEADING_PROFESSIONAL_NAME','Nombre',false); }
	if (!defined('TEXT_HEADING_PROFESSIONAL_TYPE')) { define('TEXT_HEADING_PROFESSIONAL_TYPE','Tipo de profesional de salud',false); }
	if (!defined('TEXT_HEADING_TOTAL')) { define('TEXT_HEADING_TOTAL','TOTAL',false); }
	if (!defined('TEXT_LABEL_DR_GENERAL')) { define('TEXT_LABEL_DR_GENERAL','Médico: General',false); }
	if (!defined('TEXT_LABEL_DR_SPECIALIST')) { define('TEXT_LABEL_DR_SPECIALIST','Médico: Especialista',false); }
	if (!defined('TEXT_LABEL_DR_STUDENT')) { define('TEXT_LABEL_DR_STUDENT','Estudiante de medicina',false); }
	if (!defined('TEXT_LABEL_NURSE_AID')) { define('TEXT_LABEL_NURSE_AID','Enfermera: Auxiliar',false); }
	if (!defined('TEXT_LABEL_NURSE_PRO')) { define('TEXT_LABEL_NURSE_PRO','Enfermera: Profesional',false); }
	if (!defined('TEXT_LABEL_NURSE_STU')) { define('TEXT_LABEL_NURSE_STU','Estudiante de enfermeria',false); }
	if (!defined('TEXT_LABEL_STAFF')) { define('TEXT_LABEL_STAFF','Personal de la clínica',false); }
	if (!defined('TEXT_MO_DATE_PROMPT_LABEL')) { define('TEXT_MO_DATE_PROMPT_LABEL','Fecha&nbsp;(aaaa-mm)',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_NO_REPORT_PROF_PROMPT')) { define('TEXT_NO_REPORT_PROF_PROMPT','Seleccione un profesional de salud y una fecha.',false); }
	if (!defined('TEXT_REPORT_CLINICNAME_LABEL')) { define('TEXT_REPORT_CLINICNAME_LABEL','Establecimiento',false); }
	if (!defined('TEXT_REPORT_CLINIC_CISUAPS_LABEL')) { define('TEXT_REPORT_CLINIC_CISUAPS_LABEL','CIS/UAPS',false); }
	if (!defined('TEXT_REPORT_CLINIC_CODE_LABEL')) { define('TEXT_REPORT_CLINIC_CODE_LABEL','Código',false); }
	if (!defined('TEXT_REPORT_CLINIC_LEVEL_LABEL')) { define('TEXT_REPORT_CLINIC_LEVEL_LABEL','Nivel',false); }
	if (!defined('TEXT_REPORT_COLUMN_TOTAL')) { define('TEXT_REPORT_COLUMN_TOTAL','TOTAL',false); }
	if (!defined('TEXT_REPORT_DAY')) { define('TEXT_REPORT_DAY','Día del mes',false); }
	if (!defined('TEXT_REPORT_MONTH_LABEL')) { define('TEXT_REPORT_MONTH_LABEL','Mes',false); }
	if (!defined('TEXT_REPORT_MONTH_PLACEHOLDER')) { define('TEXT_REPORT_MONTH_PLACEHOLDER','Mes del informe (MM)',false); }
	if (!defined('TEXT_REPORT_NO_DATA')) { define('TEXT_REPORT_NO_DATA','No hay datos para este mes',false); }
	if (!defined('TEXT_REPORT_REGION_LABEL')) { define('TEXT_REPORT_REGION_LABEL','Región de Salud',false); }
	if (!defined('TEXT_REPORT_SDS')) { define('TEXT_REPORT_SDS','Secretaría de Salud',false); }
	if (!defined('TEXT_REPORT_SERVICE_EMERGENCY')) { define('TEXT_REPORT_SERVICE_EMERGENCY','Emergencia',false); }
	if (!defined('TEXT_REPORT_SERVICE_EXTERNAL')) { define('TEXT_REPORT_SERVICE_EXTERNAL','Consulta externa',false); }
	if (!defined('TEXT_REPORT_YEAR_LABEL')) { define('TEXT_REPORT_YEAR_LABEL','Año',false); }
	if (!defined('TEXT_REPORT_YEAR_PLACEHOLDER')) { define('TEXT_REPORT_YEAR_PLACEHOLDER','Año del informe(AAAA)',false); }
	if (!defined('TEXT_RPT_LINE_01')) { define('TEXT_RPT_LINE_01','Menores de 1 mes de la 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_02')) { define('TEXT_RPT_LINE_02','Menores de 1 mes subsiguiente ',false); }
	if (!defined('TEXT_RPT_LINE_03')) { define('TEXT_RPT_LINE_03','1 mes a 1 año de la 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_04')) { define('TEXT_RPT_LINE_04','1 mes a 1 año subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_05')) { define('TEXT_RPT_LINE_05','1-4 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_06')) { define('TEXT_RPT_LINE_06','1-4 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_07')) { define('TEXT_RPT_LINE_07','5-9 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_08')) { define('TEXT_RPT_LINE_08','5-9 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_09')) { define('TEXT_RPT_LINE_09','10-14 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_10')) { define('TEXT_RPT_LINE_10','10-14 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_11')) { define('TEXT_RPT_LINE_11','15-20 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_12')) { define('TEXT_RPT_LINE_12','15-20 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_13')) { define('TEXT_RPT_LINE_13','20-49 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_14')) { define('TEXT_RPT_LINE_14','20-49 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_15')) { define('TEXT_RPT_LINE_15','50-59 años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_16')) { define('TEXT_RPT_LINE_16','50-59 años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_17')) { define('TEXT_RPT_LINE_17','60+ años 1a. vez',false); }
	if (!defined('TEXT_RPT_LINE_18')) { define('TEXT_RPT_LINE_18','60+ años subsiguiente',false); }
	if (!defined('TEXT_RPT_LINE_19')) { define('TEXT_RPT_LINE_19','Total pacientes atendidos',false); }
	if (!defined('TEXT_RPT_LINE_20')) { define('TEXT_RPT_LINE_20','No. atenciones de mujeres',false); }
	if (!defined('TEXT_RPT_LINE_21')) { define('TEXT_RPT_LINE_21','No. Atenciones de hombres',false); }
	if (!defined('TEXT_RPT_LINE_22')) { define('TEXT_RPT_LINE_22','No. consultas espontáneas',false); }
	if (!defined('TEXT_RPT_LINE_23')) { define('TEXT_RPT_LINE_23','No. consultas referidas',false); }
	if (!defined('TEXT_RPT_LINE_24')) { define('TEXT_RPT_LINE_24','Detección de síntomas respiratorios',false); }
	if (!defined('TEXT_RPT_LINE_25')) { define('TEXT_RPT_LINE_25','Detección de cáncer cervicouterino',false); }
	if (!defined('TEXT_RPT_LINE_26')) { define('TEXT_RPT_LINE_26','Embarazadas Nuevas',false); }
	if (!defined('TEXT_RPT_LINE_27')) { define('TEXT_RPT_LINE_27','Embarazadas en Control',false); }
	if (!defined('TEXT_RPT_LINE_28')) { define('TEXT_RPT_LINE_28','Controles puerperales',false); }
	if (!defined('TEXT_RPT_LINE_29')) { define('TEXT_RPT_LINE_29','Anticoncpetivo Oral 1 Ciclo',false); }
	if (!defined('TEXT_RPT_LINE_30')) { define('TEXT_RPT_LINE_30','Anticoncpetivo Oral 3 Ciclo',false); }
	if (!defined('TEXT_RPT_LINE_31')) { define('TEXT_RPT_LINE_31','Anticonceptivo Oral 6 Ciclo',false); }
	if (!defined('TEXT_RPT_LINE_32')) { define('TEXT_RPT_LINE_32','Condones 10 Unidades',false); }
	if (!defined('TEXT_RPT_LINE_33')) { define('TEXT_RPT_LINE_33','Condones 30 Unidades',false); }
	if (!defined('TEXT_RPT_LINE_34')) { define('TEXT_RPT_LINE_34','Depo porvera Aplicadas',false); }
	if (!defined('TEXT_RPT_LINE_35')) { define('TEXT_RPT_LINE_35','DIU insertados',false); }
	if (!defined('TEXT_RPT_LINE_36')) { define('TEXT_RPT_LINE_36','(Collar)',false); }
	if (!defined('TEXT_RPT_LINE_37')) { define('TEXT_RPT_LINE_37','Implante Sub Dérmico',false); }
	if (!defined('TEXT_RPT_LINE_38')) { define('TEXT_RPT_LINE_38','No. niños/as menores de 5 años con Diarrea',false); }
	if (!defined('TEXT_RPT_LINE_39')) { define('TEXT_RPT_LINE_39','No. niños/as menores de 5 años con Diarrea que acuden a cita de seguimiento',false); }
	if (!defined('TEXT_RPT_LINE_40')) { define('TEXT_RPT_LINE_40','No. niños/as menores de 5 años con Deshidratación Rehidratados en la US',false); }
	if (!defined('TEXT_RPT_LINE_41')) { define('TEXT_RPT_LINE_41','No. niños/as menores de 5 años con neumonía (nueva en el año)',false); }
	if (!defined('TEXT_RPT_LINE_42')) { define('TEXT_RPT_LINE_42','No. niños/as menores de 5 años con neumonía  que acuden a su cita de Seguimiento',false); }
	if (!defined('TEXT_RPT_LINE_43')) { define('TEXT_RPT_LINE_43','No. niños/as menores de 5 años con algun grado de síndrome anémico diagnosticado por laboratorio',false); }
	if (!defined('TEXT_RPT_LINE_44')) { define('TEXT_RPT_LINE_44','Total no. niños/as menores de 5 años atendidos',false); }
	if (!defined('TEXT_RPT_LINE_45')) { define('TEXT_RPT_LINE_45','No. niños/as menores de 5 años con crecimiento adecuado',false); }
	if (!defined('TEXT_RPT_LINE_46')) { define('TEXT_RPT_LINE_46','No. niños/as menores de 5 años con crecimiento inadecuado',false); }
	if (!defined('TEXT_RPT_LINE_47')) { define('TEXT_RPT_LINE_47','No. niños/as menores de 5 años bajo percentil 3',false); }
	if (!defined('TEXT_RPT_LINE_48')) { define('TEXT_RPT_LINE_48','No. niños/as menores de 5 años con daño nutricional severo',false); }
	if (!defined('TEXT_RPT_LINE_49')) { define('TEXT_RPT_LINE_49','No. niños/as menores de 5 años con discapacidad nuevos en el año',false); }
	if (!defined('TEXT_RPT_LINE_50')) { define('TEXT_RPT_LINE_50','No. niños/as menores de 5 años con probable alteracion del desarrollo',false); }
	if (!defined('TEXT_RPT_LINE_51')) { define('TEXT_RPT_LINE_51','Atención prenatal nueva en las primeras 12 SG',false); }
	if (!defined('TEXT_RPT_LINE_52')) { define('TEXT_RPT_LINE_52','Atención puerperal nueva en los primeros 10 dias',false); }
	if (!defined('TEXT_RPT_MONTH_01')) { define('TEXT_RPT_MONTH_01','Enero',false); }
	if (!defined('TEXT_RPT_MONTH_02')) { define('TEXT_RPT_MONTH_02','Febrero',false); }
	if (!defined('TEXT_RPT_MONTH_03')) { define('TEXT_RPT_MONTH_03','Marzo',false); }
	if (!defined('TEXT_RPT_MONTH_04')) { define('TEXT_RPT_MONTH_04','Abril',false); }
	if (!defined('TEXT_RPT_MONTH_05')) { define('TEXT_RPT_MONTH_05','Mayo',false); }
	if (!defined('TEXT_RPT_MONTH_06')) { define('TEXT_RPT_MONTH_06','Junio',false); }
	if (!defined('TEXT_RPT_MONTH_07')) { define('TEXT_RPT_MONTH_07','Julio',false); }
	if (!defined('TEXT_RPT_MONTH_08')) { define('TEXT_RPT_MONTH_08','Agosto',false); }
	if (!defined('TEXT_RPT_MONTH_09')) { define('TEXT_RPT_MONTH_09','Septiembre',false); }
	if (!defined('TEXT_RPT_MONTH_10')) { define('TEXT_RPT_MONTH_10','Octubre',false); }
	if (!defined('TEXT_RPT_MONTH_11')) { define('TEXT_RPT_MONTH_11','Noviembre',false); }
	if (!defined('TEXT_RPT_MONTH_12')) { define('TEXT_RPT_MONTH_12','Diciembre',false); }
	if (!defined('TEXT_SHOW_REPORT_BUTTON')) { define('TEXT_SHOW_REPORT_BUTTON','Mostrar informe',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_LINK')) { define('TEXT_SHOW_REPORT_DATE_FIELD_LINK','Muestra el campo para la fecha',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_FIELD_TITLE')) { define('TEXT_SHOW_REPORT_DATE_FIELD_TITLE','Muestra el campo para entrar la fecha directamente',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_LINK')) { define('TEXT_SHOW_REPORT_DATE_LIST_LINK','Muestra la lista de fechas',false); }
	if (!defined('TEXT_SHOW_REPORT_DATE_LIST_TITLE')) { define('TEXT_SHOW_REPORT_DATE_LIST_TITLE','Muestra la lista de fechas que tengan informes',false); }
	if (!defined('TEXT_STAFF_LABEL')) { define('TEXT_STAFF_LABEL','Profesional de salud',false); }
	if (!defined('TEXT_THOUSANDS_SEPARATOR')) { define('TEXT_THOUSANDS_SEPARATOR','.',false); }
	if (!defined('TEXT_TYPE_LABEL')) { define('TEXT_TYPE_LABEL','Servicio de atención',false); }
	if (!defined('TEXT_VISIT_TYPE_ALL')) { define('TEXT_VISIT_TYPE_ALL','Todos',false); }
}
//EOF
