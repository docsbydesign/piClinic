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
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','TEXT_CANCEL_VISIT_EDIT',false); }
	if (!defined('TEXT_CLOSE_VISIT_PAGE_TITLE')) { define('TEXT_CLOSE_VISIT_PAGE_TITLE','TEXT_CLOSE_VISIT_PAGE_TITLE',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','TEXT_DATE_TIME_OUT_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','TEXT_DIAGNOSIS1_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','TEXT_DIAGNOSIS1_PLACEHOLDER',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','TEXT_DIAGNOSIS2_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','TEXT_DIAGNOSIS2_PLACEHOLDER',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','TEXT_DIAGNOSIS3_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','TEXT_DIAGNOSIS3_PLACEHOLDER',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','TEXT_ICD_LINK_TEXT',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','TEXT_ICD_LINK_TITLE',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','TEXT_MESSAGE_GENERIC',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON','TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','TEXT_PAYMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','TEXT_REFERRAL_BLANK',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','TEXT_REFERRAL_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','TEXT_VISIT_DATE_FORMAT_LABEL',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','TEXT_VISIT_DATE_INPUT_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','TEXT_VISIT_DAY_FORMAT',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','TEXT_VISIT_DISCHARGE_HEADING',false); }
	if (!defined('TEXT_VISIT_EDIT_ALL_FIELDS')) { define('TEXT_VISIT_EDIT_ALL_FIELDS','TEXT_VISIT_EDIT_ALL_FIELDS',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','TEXT_VISIT_FORM_BP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','TEXT_VISIT_FORM_BS_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','TEXT_VISIT_FORM_HEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','TEXT_VISIT_FORM_PULSE_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','TEXT_VISIT_FORM_TEMP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','TEXT_VISIT_FORM_WEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','TEXT_VISIT_ID_LABEL',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','TEXT_VISIT_MONTH_FORMAT',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','TEXT_VISIT_PRECLINIC_HEADING',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','TEXT_VISIT_TIME_EDIT_FORMAT',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','TEXT_VISIT_VISIT_HEADING',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','TEXT_VISIT_YEAR_FORMAT',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION_VISIT')) { define('TEXT_BLANK_STAFF_OPTION_VISIT','(Select the health professional)',false); }
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','Cancel',false); }
	if (!defined('TEXT_CLOSE_VISIT_PAGE_TITLE')) { define('TEXT_CLOSE_VISIT_PAGE_TITLE','Discharge patient',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional visit notes',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary reason for visit',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','Not specified',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Discharged from clinic',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diganosis 1',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diganosis 2',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diganosis 3',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','Lookup ICD-10 code',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','Lookup an ICD-10 code in the reference book',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','There was a problem with the last entry. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','Patient visit not found.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON','Discharge patient',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Payment amount',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','Not specified',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Clinic name',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Referred to',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(m/d/y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','M-D-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Visit Date',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Discharge',false); }
	if (!defined('TEXT_VISIT_EDIT_ALL_FIELDS')) { define('TEXT_VISIT_EDIT_ALL_FIELDS','Edit all fields',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','B.P.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Height',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulse',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Weight',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clinic',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Discharged',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Status',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitted',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Visit type',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visit',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION_VISIT')) { define('TEXT_BLANK_STAFF_OPTION_VISIT','(Seleccione el profesional de salud)',false); }
	if (!defined('TEXT_CANCEL_VISIT_EDIT')) { define('TEXT_CANCEL_VISIT_EDIT','Cancelar',false); }
	if (!defined('TEXT_CLOSE_VISIT_PAGE_TITLE')) { define('TEXT_CLOSE_VISIT_PAGE_TITLE','Cerrar la visita',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Notas adicionales de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primer motivo de la visita',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificado)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Salió de la clínica',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diagnóstico 1',false); }
	if (!defined('TEXT_DIAGNOSIS1_PLACEHOLDER')) { define('TEXT_DIAGNOSIS1_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diagnóstico 2',false); }
	if (!defined('TEXT_DIAGNOSIS2_PLACEHOLDER')) { define('TEXT_DIAGNOSIS2_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diagnóstico 3',false); }
	if (!defined('TEXT_DIAGNOSIS3_PLACEHOLDER')) { define('TEXT_DIAGNOSIS3_PLACEHOLDER','Ingrese el diagnóstico o el código CIE-10',false); }
	if (!defined('TEXT_ICD_LINK_TEXT')) { define('TEXT_ICD_LINK_TEXT','Buscar CIE-10 código',false); }
	if (!defined('TEXT_ICD_LINK_TITLE')) { define('TEXT_ICD_LINK_TITLE','Buscar un código CIE-10 en el libro de referencia',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','Hubo un problema con el último cambio. Revisa los datos e intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','No se encontró la visita del paciente.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_CLOSE_PATIENT_VISIT_BUTTON','Dar de alta el paciente',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','HNL',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Pago',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','(No especificado)',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Nombre de la clínica',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibido de',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Enviado a',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(d-m-Y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','D-M-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Dar de alta',false); }
	if (!defined('TEXT_VISIT_EDIT_ALL_FIELDS')) { define('TEXT_VISIT_EDIT_ALL_FIELDS','Actualizar todos los dato de la visita',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','P.A.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Estatura',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulso',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Peso',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_PRECLINIC_HEADING')) { define('TEXT_VISIT_PRECLINIC_HEADING','Pre-Clínica',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Estatus',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitido',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visita',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','a',false); }
}
//EOF
