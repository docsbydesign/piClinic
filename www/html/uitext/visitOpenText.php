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
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','TEXT_ASSIGNED_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','TEXT_BLANK_STAFF_OPTION',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','TEXT_BLANK_VISIT_OPTION',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','TEXT_CANCEL_NEW_VISIT',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','TEXT_COMPLAINT_PRIMARY_PLACEHOLDER',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','TEXT_FIRST_VISIT_SELECT',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','TEXT_LAST_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','TEXT_MESSAGE_NO_PATIENT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','TEXT_NEW_VISIT_PAGE_TITLE',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','TEXT_PAYMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','TEXT_REFERRAL_PLACEHOLDER',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','TEXT_RETURN_VISIT_SELECT',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','TEXT_VISIT_DATE_FORMAT_LABEL',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','TEXT_VISIT_DATE_INPUT_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','TEXT_VISIT_DAY_FORMAT',false); }
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','TEXT_VISIT_DEFAULT',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','TEXT_VISIT_MONTH_FORMAT',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','TEXT_VISIT_TIME_EDIT_FORMAT',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','TEXT_VISIT_YEAR_FORMAT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Select the health professional)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Select the visit type)',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','Cancel',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional complaint',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Additional complaint',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary complaint',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primary complaint',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(Not specified)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','New patient?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Yes',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Last visit',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','Could not find any patients that match.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','The patient ID was not specified.',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','Admit patient',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','Admit',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TBD',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Payment amount',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Clinic name',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','No',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(m/d/y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','M-D-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Visit Date',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','Outpatient',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Discharged',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Status',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitted',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Visit type',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_BLANK_STAFF_OPTION')) { define('TEXT_BLANK_STAFF_OPTION','(Escoge el profesional de salud)',false); }
	if (!defined('TEXT_BLANK_VISIT_OPTION')) { define('TEXT_BLANK_VISIT_OPTION','(Escoge el tipo de la atención)',false); }
	if (!defined('TEXT_CANCEL_NEW_VISIT')) { define('TEXT_CANCEL_NEW_VISIT','Cancelar',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Adicional motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER')) { define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Adicional motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primero motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER')) { define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primero motivo de la visita',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificada)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false); }
	if (!defined('TEXT_FIRST_VISIT_SELECT')) { define('TEXT_FIRST_VISIT_SELECT','Nuevo',false); }
	if (!defined('TEXT_LAST_VISIT_DATE_LABEL')) { define('TEXT_LAST_VISIT_DATE_LABEL','Úlitma visita',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','No se encontró ninguna paciente que coincide.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_SPECIFIED','No estuvo especificado el ID del paciente.',false); }
	if (!defined('TEXT_NEW_VISIT_PAGE_TITLE')) { define('TEXT_NEW_VISIT_PAGE_TITLE','Abre una visita nueva para el paciente',false); }
	if (!defined('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_SUBMIT_NEW_PATIENT_VISIT_BUTTON','Abra esta visita',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TBD',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_PAYMENT_PLACEHOLDER')) { define('TEXT_PAYMENT_PLACEHOLDER','Pago',false); }
	if (!defined('TEXT_REFERRAL_PLACEHOLDER')) { define('TEXT_REFERRAL_PLACEHOLDER','Nombre de la clínica',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibida de',false); }
	if (!defined('TEXT_RETURN_VISIT_SELECT')) { define('TEXT_RETURN_VISIT_SELECT','Subsiguiente',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT_LABEL')) { define('TEXT_VISIT_DATE_FORMAT_LABEL','(d-m-Y hh:mm)',false); }
	if (!defined('TEXT_VISIT_DATE_INPUT_FORMAT')) { define('TEXT_VISIT_DATE_INPUT_FORMAT','D-M-Y-T',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_DAY_FORMAT')) { define('TEXT_VISIT_DAY_FORMAT','d',false); }
	if (!defined('TEXT_VISIT_DEFAULT')) { define('TEXT_VISIT_DEFAULT','Outpatient',false); }
	if (!defined('TEXT_VISIT_MONTH_FORMAT')) { define('TEXT_VISIT_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Estatus',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitido',false); }
	if (!defined('TEXT_VISIT_TIME_EDIT_FORMAT')) { define('TEXT_VISIT_TIME_EDIT_FORMAT','H:i',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false); }
	if (!defined('TEXT_VISIT_YEAR_FORMAT')) { define('TEXT_VISIT_YEAR_FORMAT','Y',false); }
}
//EOF
