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
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','TEXT_ASSIGNED_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION_VISIT')) { define('TEXT_BLANK_STAFF_OPTION_VISIT','TEXT_BLANK_STAFF_OPTION_VISIT',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION_VISIT')) { define('TEXT_BLANK_VISIT_OPTION_VISIT','TEXT_BLANK_VISIT_OPTION_VISIT',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','TEXT_BP_DIA_PLACEHOLDER',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','TEXT_BP_SYS_PLACEHOLDER',false); }
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','TEXT_CANCEL_VISIT_EDIT',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','TEXT_COMPLAINT_PRIMARY_PLACEHOLDER',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','TEXT_DATE_TIME_OUT_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','TEXT_DIAGNOSIS1_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','TEXT_DIAGNOSIS1_PLACEHOLDER',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','TEXT_DIAGNOSIS2_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','TEXT_DIAGNOSIS2_PLACEHOLDER',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','TEXT_DIAGNOSIS3_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','TEXT_DIAGNOSIS3_PLACEHOLDER',false); }
	if (!defined('TEXT_EDIT_PAGE_TITLE')) { define('TEXT_EDIT_PAGE_TITLE','TEXT_EDIT_PAGE_TITLE',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','TEXT_FIRST_VISIT_SELECT',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','TEXT_GLUCOSE_PLACEHOLDER',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','TEXT_GLUCOSE_UNITS_FBS',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','TEXT_GLUCOSE_UNITS_RBS',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','TEXT_HEIGHT_PLACEHOLDER',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','TEXT_HEIGHT_UNITS_CM',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','TEXT_HEIGHT_UNITS_IN',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','TEXT_HEIGHT_UNITS_MM',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','TEXT_ICD_LINK_TEXT',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','TEXT_ICD_LINK_TITLE',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','TEXT_LAST_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','TEXT_NO_OPTION',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','TEXT_PAYMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','TEXT_PULSE_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','TEXT_REFERRAL_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','TEXT_RETURN_VISIT_SELECT',false); }
	if (!defined('TEXT_SELECT_GLUCOSE_UNITS')) { define('TEXT_SELECT_GLUCOSE_UNITS','TEXT_SELECT_GLUCOSE_UNITS',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_TEMP_PLACEHOLDER')) { define('TEXT_TEMP_PLACEHOLDER','TEXT_TEMP_PLACEHOLDER',false); }
	if (!defined('TEXT_TEMP_UNITS_C')) { define('TEXT_TEMP_UNITS_C','TEXT_TEMP_UNITS_C',false); }
	if (!defined('TEXT_TEMP_UNITS_F')) { define('TEXT_TEMP_UNITS_F','TEXT_TEMP_UNITS_F',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','TEXT_VISIT_DATE_FORMAT_LABEL',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','TEXT_VISIT_DATE_INPUT_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','TEXT_VISIT_DAY_FORMAT',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false); }
	if (!defined('TEXT_VISIT_DELETED_LABEL')) { define('TEXT_VISIT_DELETED_LABEL','TEXT_VISIT_DELETED_LABEL',false); }
	if (!defined('TEXT_VISIT_DELETED_SELECT_LABEL')) { define('TEXT_VISIT_DELETED_SELECT_LABEL','TEXT_VISIT_DELETED_SELECT_LABEL',false); }
	if (!defined('TEXT_VISIT_DELETED_TEXT')) { define('TEXT_VISIT_DELETED_TEXT','TEXT_VISIT_DELETED_TEXT',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','TEXT_VISIT_DISCHARGE_HEADING',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','TEXT_VISIT_FORM_BP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','TEXT_VISIT_FORM_BS_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','TEXT_VISIT_FORM_HEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','TEXT_VISIT_FORM_PULSE_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','TEXT_VISIT_FORM_TEMP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','TEXT_VISIT_FORM_WEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','TEXT_VISIT_ID_LABEL',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','TEXT_VISIT_MONTH_FORMAT',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false); }
	if (!defined('TEXT_VISIT_NOT_DELETED_TEXT')) { define('TEXT_VISIT_NOT_DELETED_TEXT','TEXT_VISIT_NOT_DELETED_TEXT',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','TEXT_VISIT_PRECLINIC_HEADING',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','TEXT_VISIT_TIME_EDIT_FORMAT',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','TEXT_VISIT_UNABLE_OPEN_VISIT',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','TEXT_VISIT_VISIT_HEADING',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','TEXT_VISIT_YEAR_FORMAT',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','TEXT_WEIGHT_PLACEHOLDER',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','TEXT_WEIGHT_UNITS_KG',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','TEXT_WEIGHT_UNITS_LBS',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','TEXT_YES_OPTION',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION_VISIT')) { define('TEXT_BLANK_STAFF_OPTION_VISIT','(Select the health professional)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION_VISIT')) { define('TEXT_BLANK_VISIT_OPTION_VISIT','(Select the visit type)',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','Diastolic pressure',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','Systolic pressure',false); }
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','Cancel',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional visit notes',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Additional visit notes',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary reason for visit',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primary reason for visit',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','Not specified',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Discharged from clinic',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diganosis 1',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diganosis 2',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diganosis 3',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_EDIT_PAGE_TITLE')) { define('TEXT_EDIT_PAGE_TITLE','Edit Patient Visit Details',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','New patient?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Yes',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','Glucose',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','Fasting',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','Random',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','Height',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','cm',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','in',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','mm',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','Lookup ICD-10 code',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','Lookup an ICD-10 code in the reference book',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Last visit',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','Patient visit not found.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','No',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','Update visit info',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Payment amount',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','Pulse',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Clinic name',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Referred to',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','No',false); }
	if (!defined('TEXT_SELECT_GLUCOSE_UNITS')) { define('TEXT_SELECT_GLUCOSE_UNITS','(Type)',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_TEMP_PLACEHOLDER')) { define('TEXT_TEMP_PLACEHOLDER','Temp.',false); }
	if (!defined('TEXT_TEMP_UNITS_C')) { define('TEXT_TEMP_UNITS_C','C',false); }
	if (!defined('TEXT_TEMP_UNITS_F')) { define('TEXT_TEMP_UNITS_F','F',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(m/d/y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','M-D-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Visit Date',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DELETED_LABEL')) { define('TEXT_VISIT_DELETED_LABEL','Deleted state',false); }
	if (!defined('TEXT_VISIT_DELETED_SELECT_LABEL')) { define('TEXT_VISIT_DELETED_SELECT_LABEL','Deleted',false); }
	if (!defined('TEXT_VISIT_DELETED_TEXT')) { define('TEXT_VISIT_DELETED_TEXT','Deleted',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Discharge',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','B.P.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Height',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulse',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Weight',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_NOT_DELETED_TEXT')) { define('TEXT_VISIT_NOT_DELETED_TEXT','Valid',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clinic',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Discharged',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Status',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitted',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Visit type',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','Unable to access this visit',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visit',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','y',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','Weight',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','kg',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','lbs',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','Yes',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION_VISIT')) { define('TEXT_BLANK_STAFF_OPTION_VISIT','(Seleccione el profesional de salud)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION_VISIT')) { define('TEXT_BLANK_VISIT_OPTION_VISIT','(Seleccione el tipo de la atención)',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','Diástole pressure',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','Sístole pressure',false); }
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','Cancelar',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Notas adicionales de la visita',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Notas adicionales de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primer motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primer motivo de la visita',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificado)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Salió de la clínica',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diagnóstico 1',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diagnóstico 2',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diagnóstico 3',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_EDIT_PAGE_TITLE')) { define('TEXT_EDIT_PAGE_TITLE','Actualizar detalles de la visita',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Nuevo',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','Glucosa',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','Ayuno',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','Aleatoria',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','Estatura',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','cm',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','in',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','mm',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','Buscar CIE-10 código',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','Buscar un código CIE-10 en el libro de referencia',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Úlitma visita',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','No se encontró la visita del paciente.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','No',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','Actualizar la información de esta visita',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Pago',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','Pulso',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Nombre de la clínica',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibido de',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Enviado a',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','Subsiguiente',false); }
	if (!defined('TEXT_SELECT_GLUCOSE_UNITS')) { define('TEXT_SELECT_GLUCOSE_UNITS','(Tipo)',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_TEMP_PLACEHOLDER')) { define('TEXT_TEMP_PLACEHOLDER','Temp.',false); }
	if (!defined('TEXT_TEMP_UNITS_C')) { define('TEXT_TEMP_UNITS_C','C',false); }
	if (!defined('TEXT_TEMP_UNITS_F')) { define('TEXT_TEMP_UNITS_F','F',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(d-m-Y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','D-M-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DELETED_LABEL')) { define('TEXT_VISIT_DELETED_LABEL','Borrada o valida',false); }
	if (!defined('TEXT_VISIT_DELETED_SELECT_LABEL')) { define('TEXT_VISIT_DELETED_SELECT_LABEL','Borrada',false); }
	if (!defined('TEXT_VISIT_DELETED_TEXT')) { define('TEXT_VISIT_DELETED_TEXT','Borrada',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Dar de alta',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','P.A.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Estatura',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulso',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Peso',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_NOT_DELETED_TEXT')) { define('TEXT_VISIT_NOT_DELETED_TEXT','Valida',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clínica',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Estatus',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitido',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','No es posible abrir esta visita',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visita',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','a',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','Peso',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','kg',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','lb',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','Si',false); }
}
//EOF
