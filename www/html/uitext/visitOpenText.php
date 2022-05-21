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
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','TEXT_BLANK_STAFF_OPTION',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','TEXT_BLANK_VISIT_OPTION',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','TEXT_BP_DIA_PLACEHOLDER',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','TEXT_BP_SYS_PLACEHOLDER',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','TEXT_CANCEL_NEW_VISIT',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','TEXT_COMPLAINT_PRIMARY_PLACEHOLDER',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','TEXT_FIRST_VISIT_SELECT',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','TEXT_GLUCOSE_PLACEHOLDER',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','TEXT_GLUCOSE_UNITS_FBS',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','TEXT_GLUCOSE_UNITS_RBS',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','TEXT_HEIGHT_PLACEHOLDER',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','TEXT_HEIGHT_UNITS_CM',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','TEXT_HEIGHT_UNITS_IN',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','TEXT_HEIGHT_UNITS_MM',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','TEXT_LAST_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','TEXT_MESSAGE_NO_PATIENT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','TEXT_NEW_VISIT_PAGE_TITLE',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','TEXT_PAYMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','TEXT_PULSE_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','TEXT_REFERRAL_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
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
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','TEXT_VISIT_DEFAULT',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','TEXT_VISIT_FORM_BP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','TEXT_VISIT_FORM_BS_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','TEXT_VISIT_FORM_HEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','TEXT_VISIT_FORM_PULSE_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','TEXT_VISIT_FORM_TEMP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','TEXT_VISIT_FORM_WEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','TEXT_VISIT_MONTH_FORMAT',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','TEXT_VISIT_PRECLINIC_HEADING',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','TEXT_VISIT_TIME_EDIT_FORMAT',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','TEXT_VISIT_YEAR_FORMAT',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','TEXT_WEIGHT_PLACEHOLDER',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','TEXT_WEIGHT_UNITS_KG',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','TEXT_WEIGHT_UNITS_LBS',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','TEXT_YMD_AGE_DAYS',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','TEXT_YMD_AGE_MONTHS',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','TEXT_YMD_AGE_YEARS',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Select the health professional)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Select the visit type)',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','Diastolic pressure',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','Systolic pressure',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','Cancel',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional visit notes',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Additional visit notes',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary reason for visit',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primary reason for visit',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','Not specified',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','New patient?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Yes',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','Glucose',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','Fasting',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','Random',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','Height',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','cm',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','in',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','mm',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Last visit',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','Could not find any patients that match.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','The patient ID was not specified.',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','Admit patient',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','Admit',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Payment amount',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','Pulse',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Clinic name',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
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
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','Outpatient',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','B.P.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Height',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulse',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Weight',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clinic',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Discharged',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Status',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitted',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Visit type',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','Weight',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','kg',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','lbs',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','d',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','m',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Seleccione el profesional de salud)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Seleccione el tipo de la atención)',false); }
	if (!defined('TEXT_BP_DIA_PLACEHOLDER')) { define('TEXT_BP_DIA_PLACEHOLDER','Diástole pressure',false); }
	if (!defined('TEXT_BP_SYS_PLACEHOLDER')) { define('TEXT_BP_SYS_PLACEHOLDER','Sístole pressure',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','Cancelar',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Notas adicionales de la visita',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Notas adicionales de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primer motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primer motivo de la visita',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificado)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Nuevo',false); }
	if (!defined('TEXT_GLUCOSE_PLACEHOLDER')) { define('TEXT_GLUCOSE_PLACEHOLDER','Glucosa',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_FBS')) { define('TEXT_GLUCOSE_UNITS_FBS','Ayuno',false); }
	if (!defined('TEXT_GLUCOSE_UNITS_RBS')) { define('TEXT_GLUCOSE_UNITS_RBS','Aleatoria',false); }
	if (!defined('TEXT_HEIGHT_PLACEHOLDER')) { define('TEXT_HEIGHT_PLACEHOLDER','Estatura',false); }
	if (!defined('TEXT_HEIGHT_UNITS_CM')) { define('TEXT_HEIGHT_UNITS_CM','cm',false); }
	if (!defined('TEXT_HEIGHT_UNITS_IN')) { define('TEXT_HEIGHT_UNITS_IN','in',false); }
	if (!defined('TEXT_HEIGHT_UNITS_MM')) { define('TEXT_HEIGHT_UNITS_MM','mm',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Úlitma visita',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','No se encontró ningún paciente que coincide.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','No estuvo especificado el ID del paciente.',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','Abre una visita nueva para el paciente',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','Abra esta visita',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Pago',false); }
	if (!defined('TEXT_PULSE_PLACEHOLDER')) { define('TEXT_PULSE_PLACEHOLDER','Pulso',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Nombre de la clínica',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibido de',false); }
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
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','Outpatient',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','P.A.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Estatura',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulso',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Peso',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clínica',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Estatus',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitido',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_WEIGHT_PLACEHOLDER')) { define('TEXT_WEIGHT_PLACEHOLDER','Peso',false); }
	if (!defined('TEXT_WEIGHT_UNITS_KG')) { define('TEXT_WEIGHT_UNITS_KG','kg',false); }
	if (!defined('TEXT_WEIGHT_UNITS_LBS')) { define('TEXT_WEIGHT_UNITS_LBS','lb',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','d',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','m',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','a',false); }
}
//EOF
