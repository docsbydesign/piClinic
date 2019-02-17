<?php
/*

 *
 *	Copyright (c) 2018, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */

// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	define('TEXT_ASSIGNED_LABEL','TEXT_ASSIGNED_LABEL',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false);
	define('TEXT_BLANK_STAFF_OPTION_VISIT','TEXT_BLANK_STAFF_OPTION_VISIT',false);
	define('TEXT_BLANK_VISIT_OPTION_VISIT','TEXT_BLANK_VISIT_OPTION_VISIT',false);
	define('TEXT_CANCEL_VISIT_EDIT','TEXT_CANCEL_VISIT_EDIT',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false);
	define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false);
	define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','TEXT_COMPLAINT_PRIMARY_PLACEHOLDER',false);
	define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false);
	define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false);
	define('TEXT_DATE_TIME_OUT_LABEL','TEXT_DATE_TIME_OUT_LABEL',false);
	define('TEXT_DIAGNOSIS1_LABEL','TEXT_DIAGNOSIS1_LABEL',false);
	define('TEXT_DIAGNOSIS1_PLACEHOLDER','TEXT_DIAGNOSIS1_PLACEHOLDER',false);
	define('TEXT_DIAGNOSIS2_LABEL','TEXT_DIAGNOSIS2_LABEL',false);
	define('TEXT_DIAGNOSIS2_PLACEHOLDER','TEXT_DIAGNOSIS2_PLACEHOLDER',false);
	define('TEXT_DIAGNOSIS3_LABEL','TEXT_DIAGNOSIS3_LABEL',false);
	define('TEXT_DIAGNOSIS3_PLACEHOLDER','TEXT_DIAGNOSIS3_PLACEHOLDER',false);
	define('TEXT_EDIT_PAGE_TITLE','TEXT_EDIT_PAGE_TITLE',false);
	define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false);
	define('TEXT_FIRST_VISIT_SELECT','TEXT_FIRST_VISIT_SELECT',false);
	define('TEXT_LAST_VISIT_DATE_LABEL','TEXT_LAST_VISIT_DATE_LABEL',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false);
	define('TEXT_NO_OPTION','TEXT_NO_OPTION',false);
	define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON',false);
	define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false);
	define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false);
	define('TEXT_PAYMENT_PLACEHOLDER','TEXT_PAYMENT_PLACEHOLDER',false);
	define('TEXT_REFERRAL_PLACEHOLDER','TEXT_REFERRAL_PLACEHOLDER',false);
	define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false);
	define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false);
	define('TEXT_RETURN_VISIT_SELECT','TEXT_RETURN_VISIT_SELECT',false);
	define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false);
	define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false);
	define('TEXT_VISIT_DATE_FORMAT_LABEL','TEXT_VISIT_DATE_FORMAT_LABEL',false);
	define('TEXT_VISIT_DATE_INPUT_FORMAT','TEXT_VISIT_DATE_INPUT_FORMAT',false);
	define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false);
	define('TEXT_VISIT_DAY_FORMAT','TEXT_VISIT_DAY_FORMAT',false);
	define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false);
	define('TEXT_VISIT_DELETED_LABEL','TEXT_VISIT_DELETED_LABEL',false);
	define('TEXT_VISIT_DELETED_SELECT_LABEL','TEXT_VISIT_DELETED_SELECT_LABEL',false);
	define('TEXT_VISIT_DELETED_TEXT','TEXT_VISIT_DELETED_TEXT',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','TEXT_VISIT_DISCHARGE_HEADING',false);
	define('TEXT_VISIT_ID_LABEL','TEXT_VISIT_ID_LABEL',false);
	define('TEXT_VISIT_MONTH_FORMAT','TEXT_VISIT_MONTH_FORMAT',false);
	define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false);
	define('TEXT_VISIT_NOT_DELETED_TEXT','TEXT_VISIT_NOT_DELETED_TEXT',false);
	define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false);
	define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false);
	define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false);
	define('TEXT_VISIT_TIME_FORMAT','TEXT_VISIT_TIME_FORMAT',false);
	define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','TEXT_VISIT_UNABLE_OPEN_VISIT',false);
	define('TEXT_VISIT_VISIT_HEADING','TEXT_VISIT_VISIT_HEADING',false);
	define('TEXT_VISIT_YEAR_FORMAT','TEXT_VISIT_YEAR_FORMAT',false);
	define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false);
	define('TEXT_YES_OPTION','TEXT_YES_OPTION',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_ASSIGNED_LABEL','Doctor',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false);
	define('TEXT_BLANK_STAFF_OPTION_VISIT','(Select the health professional)',false);
	define('TEXT_BLANK_VISIT_OPTION_VISIT','(Select the visit type)',false);
	define('TEXT_CANCEL_VISIT_EDIT','Cancel',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional complaint',false);
	define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Additional complaint',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary complaint',false);
	define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primary complaint',false);
	define('TEXT_DATE_BLANK','(Not specified)',false);
	define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false);
	define('TEXT_DATE_TIME_OUT_LABEL','Departed clinic',false);
	define('TEXT_DIAGNOSIS1_LABEL','Diganosis 1',false);
	define('TEXT_DIAGNOSIS1_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false);
	define('TEXT_DIAGNOSIS2_LABEL','Diganosis 2',false);
	define('TEXT_DIAGNOSIS2_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false);
	define('TEXT_DIAGNOSIS3_LABEL','Diganosis 3',false);
	define('TEXT_DIAGNOSIS3_PLACEHOLDER','Enter a diagnosis or ICD-10 code',false);
	define('TEXT_EDIT_PAGE_TITLE','Edit Patient Visit Details',false);
	define('TEXT_FIRST_VISIT_LABEL','New patient?',false);
	define('TEXT_FIRST_VISIT_SELECT','Yes',false);
	define('TEXT_LAST_VISIT_DATE_LABEL','Last visit',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','Patient visit not found.',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false);
	define('TEXT_NO_OPTION','No',false);
	define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','Update visit info',false);
	define('TEXT_PAYMENT_CURRENCY','TBD',false);
	define('TEXT_PAYMENT_LABEL','Payment',false);
	define('TEXT_PAYMENT_PLACEHOLDER','Payment amount',false);
	define('TEXT_REFERRAL_PLACEHOLDER','Clinic name',false);
	define('TEXT_REFERRED_FROM_LABEL','Referred from',false);
	define('TEXT_REFERRED_TO_LABEL','Referred to',false);
	define('TEXT_RETURN_VISIT_SELECT','No',false);
	define('TEXT_SHOW_PATIENT_INFO','Show patient details',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i m/d/Y',false);
	define('TEXT_VISIT_DATE_FORMAT_LABEL','(m/d/y hh:mm)',false);
	define('TEXT_VISIT_DATE_INPUT_FORMAT','M-D-Y-T',false);
	define('TEXT_VISIT_DATE_LABEL','Visit Date',false);
	define('TEXT_VISIT_DAY_FORMAT','d',false);
	define('TEXT_VISIT_DAY_TEXT','d',false);
	define('TEXT_VISIT_DELETED_LABEL','Deleted state',false);
	define('TEXT_VISIT_DELETED_SELECT_LABEL','Deleted',false);
	define('TEXT_VISIT_DELETED_TEXT','Deleted',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','Discharge',false);
	define('TEXT_VISIT_ID_LABEL','ID',false);
	define('TEXT_VISIT_MONTH_FORMAT','m',false);
	define('TEXT_VISIT_MONTH_TEXT','m',false);
	define('TEXT_VISIT_NOT_DELETED_TEXT','Valid',false);
	define('TEXT_VISIT_STATUS_CLOSED','Discharged',false);
	define('TEXT_VISIT_STATUS_LABEL','Status',false);
	define('TEXT_VISIT_STATUS_OPEN','Admitted',false);
	define('TEXT_VISIT_TIME_FORMAT','H:i m/d/Y',false);
	define('TEXT_VISIT_TYPE_LABEL','Visit type',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','Unable to access this visit',false);
	define('TEXT_VISIT_VISIT_HEADING','Visit',false);
	define('TEXT_VISIT_YEAR_FORMAT','Y',false);
	define('TEXT_VISIT_YEAR_TEXT','y',false);
	define('TEXT_YES_OPTION','Yes',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_ASSIGNED_LABEL','Doctor(a)',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false);
	define('TEXT_BLANK_STAFF_OPTION_VISIT','(Escoge el profesional de salud)',false);
	define('TEXT_BLANK_VISIT_OPTION_VISIT','(Escoge el tipo de la atención)',false);
	define('TEXT_CANCEL_VISIT_EDIT','Cancelar',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Adicional motivo de la visita',false);
	define('TEXT_COMPLAINT_ADDITIONAL_PLACEHOLDER','Adicional motivo de la visita',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','Primero motivo de la visita',false);
	define('TEXT_COMPLAINT_PRIMARY_PLACEHOLDER','Primero motivo de la visita',false);
	define('TEXT_DATE_BLANK','(No especificada)',false);
	define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false);
	define('TEXT_DATE_TIME_OUT_LABEL','Salió de la clínica',false);
	define('TEXT_DIAGNOSIS1_LABEL','Diagnóstico 1',false);
	define('TEXT_DIAGNOSIS1_PLACEHOLDER','Ingrese el diagnóstico o el codigo CIE-10',false);
	define('TEXT_DIAGNOSIS2_LABEL','Diagnóstico 2',false);
	define('TEXT_DIAGNOSIS2_PLACEHOLDER','Ingrese el diagnóstico o el codigo CIE-10',false);
	define('TEXT_DIAGNOSIS3_LABEL','Diagnóstico 3',false);
	define('TEXT_DIAGNOSIS3_PLACEHOLDER','Ingrese el diagnóstico o el codigo CIE-10',false);
	define('TEXT_EDIT_PAGE_TITLE','Actualizar detalles de la visita',false);
	define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false);
	define('TEXT_FIRST_VISIT_SELECT','Nuevo',false);
	define('TEXT_LAST_VISIT_DATE_LABEL','Úlitma visita',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','No se encontro la visita del paciente.',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false);
	define('TEXT_NO_OPTION','No',false);
	define('TEXT_PATIENT_SUBMIT_PATIENT_VISIT_BUTTON','Actualizar la información de esta visita',false);
	define('TEXT_PAYMENT_CURRENCY','TBD',false);
	define('TEXT_PAYMENT_LABEL','Pago',false);
	define('TEXT_PAYMENT_PLACEHOLDER','Pago',false);
	define('TEXT_REFERRAL_PLACEHOLDER','Nombre de la clínica',false);
	define('TEXT_REFERRED_FROM_LABEL','Recibida de',false);
	define('TEXT_REFERRED_TO_LABEL','Enviada a',false);
	define('TEXT_RETURN_VISIT_SELECT','Subsiguiente',false);
	define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i d-m-Y',false);
	define('TEXT_VISIT_DATE_FORMAT_LABEL','(d-m-Y hh:mm)',false);
	define('TEXT_VISIT_DATE_INPUT_FORMAT','D-M-Y-T',false);
	define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false);
	define('TEXT_VISIT_DAY_FORMAT','d',false);
	define('TEXT_VISIT_DAY_TEXT','d',false);
	define('TEXT_VISIT_DELETED_LABEL','Borrada o valida',false);
	define('TEXT_VISIT_DELETED_SELECT_LABEL','Borrada',false);
	define('TEXT_VISIT_DELETED_TEXT','Borrada',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','Dar de alta',false);
	define('TEXT_VISIT_ID_LABEL','ID',false);
	define('TEXT_VISIT_MONTH_FORMAT','m',false);
	define('TEXT_VISIT_MONTH_TEXT','m',false);
	define('TEXT_VISIT_NOT_DELETED_TEXT','Valida',false);
	define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false);
	define('TEXT_VISIT_STATUS_LABEL','Estatus',false);
	define('TEXT_VISIT_STATUS_OPEN','Admitido',false);
	define('TEXT_VISIT_TIME_FORMAT','H:i d-m-Y',false);
	define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','No es posible abrir esta visita',false);
	define('TEXT_VISIT_VISIT_HEADING','Visita',false);
	define('TEXT_VISIT_YEAR_FORMAT','Y',false);
	define('TEXT_VISIT_YEAR_TEXT','a',false);
	define('TEXT_YES_OPTION','Si',false);
}
//EOF
