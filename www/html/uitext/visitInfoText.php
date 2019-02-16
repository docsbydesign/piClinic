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
	define('TEXT_CLOSE_VISIT','TEXT_CLOSE_VISIT',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false);
	define('TEXT_COMPLAINT_NOT_SPECIFIED','TEXT_COMPLAINT_NOT_SPECIFIED',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false);
	define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false);
	define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false);
	define('TEXT_DATE_TIME_OUT_LABEL','TEXT_DATE_TIME_OUT_LABEL',false);
	define('TEXT_DIAGNOSIS1_LABEL','TEXT_DIAGNOSIS1_LABEL',false);
	define('TEXT_DIAGNOSIS2_LABEL','TEXT_DIAGNOSIS2_LABEL',false);
	define('TEXT_DIAGNOSIS3_LABEL','TEXT_DIAGNOSIS3_LABEL',false);
	define('TEXT_DIAGNOSIS_BLANK','TEXT_DIAGNOSIS_BLANK',false);
	define('TEXT_FIND_ANOTHER_LINK','TEXT_FIND_ANOTHER_LINK',false);
	define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false);
	define('TEXT_FIRST_VISIT_TEXT','TEXT_FIRST_VISIT_TEXT',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false);
	define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false);
	define('TEXT_PATIENT_VISIT_NOT_FOUND','TEXT_PATIENT_VISIT_NOT_FOUND',false);
	define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false);
	define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false);
	define('TEXT_REFERRAL_BLANK','TEXT_REFERRAL_BLANK',false);
	define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false);
	define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false);
	define('TEXT_RETURN_VISIT_TEXT','TEXT_RETURN_VISIT_TEXT',false);
	define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false);
	define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false);
	define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false);
	define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false);
	define('TEXT_VISIT_DETAILS_PAGE_TITLE','TEXT_VISIT_DETAILS_PAGE_TITLE',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','TEXT_VISIT_DISCHARGE_HEADING',false);
	define('TEXT_VISIT_ID_LABEL','TEXT_VISIT_ID_LABEL',false);
	define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false);
	define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false);
	define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false);
	define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false);
	define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','TEXT_VISIT_UNABLE_OPEN_VISIT',false);
	define('TEXT_VISIT_VISIT_HEADING','TEXT_VISIT_VISIT_HEADING',false);
	define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_ASSIGNED_LABEL','Doctor',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false);
	define('TEXT_CLOSE_VISIT','Discharge this patient',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional complaint',false);
	define('TEXT_COMPLAINT_NOT_SPECIFIED','(Not specified)',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary complaint',false);
	define('TEXT_DATE_BLANK','(Not specified)',false);
	define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false);
	define('TEXT_DATE_TIME_OUT_LABEL','Departed clinic',false);
	define('TEXT_DIAGNOSIS1_LABEL','Diganosis 1',false);
	define('TEXT_DIAGNOSIS2_LABEL','Diganosis 2',false);
	define('TEXT_DIAGNOSIS3_LABEL','Diganosis 3',false);
	define('TEXT_DIAGNOSIS_BLANK','(Not specified)',false);
	define('TEXT_FIND_ANOTHER_LINK','Search for another patient',false);
	define('TEXT_FIRST_VISIT_LABEL','New patient?',false);
	define('TEXT_FIRST_VISIT_TEXT','Yes',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false);
	define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','Update visit info',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false);
	define('TEXT_PATIENT_VISIT_NOT_FOUND','The specified patient visit was not found',false);
	define('TEXT_PAYMENT_CURRENCY','TBD',false);
	define('TEXT_PAYMENT_LABEL','Payment',false);
	define('TEXT_REFERRAL_BLANK','(Not specified)',false);
	define('TEXT_REFERRED_FROM_LABEL','Referred from',false);
	define('TEXT_REFERRED_TO_LABEL','Referred to',false);
	define('TEXT_RETURN_VISIT_TEXT','No',false);
	define('TEXT_SHOW_PATIENT_INFO','Show patient details',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i m/d/Y',false);
	define('TEXT_VISIT_DATE_LABEL','Visit Date',false);
	define('TEXT_VISIT_DAY_TEXT','d',false);
	define('TEXT_VISIT_DETAILS_PAGE_TITLE','Patient Visit Details',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','Discharge',false);
	define('TEXT_VISIT_ID_LABEL','ID',false);
	define('TEXT_VISIT_MONTH_TEXT','m',false);
	define('TEXT_VISIT_STATUS_CLOSED','Discharged',false);
	define('TEXT_VISIT_STATUS_LABEL','Status',false);
	define('TEXT_VISIT_STATUS_OPEN','Admitted',false);
	define('TEXT_VISIT_TYPE_LABEL','Visit type',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','Unable to access this visit',false);
	define('TEXT_VISIT_VISIT_HEADING','Visit',false);
	define('TEXT_VISIT_YEAR_TEXT','y',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_ASSIGNED_LABEL','Doctor(a)',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false);
	define('TEXT_CLOSE_VISIT','Dar de alta este paciente',false);
	define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Adicional motivo de la visita',false);
	define('TEXT_COMPLAINT_NOT_SPECIFIED','(No especificado)',false);
	define('TEXT_COMPLAINT_PRIMARY_LABEL','Primero motivo de la visita',false);
	define('TEXT_DATE_BLANK','(No especificada)',false);
	define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false);
	define('TEXT_DATE_TIME_OUT_LABEL','Salió de la clínica',false);
	define('TEXT_DIAGNOSIS1_LABEL','Diagnóstico 1',false);
	define('TEXT_DIAGNOSIS2_LABEL','Diagnóstico 2',false);
	define('TEXT_DIAGNOSIS3_LABEL','Diagnóstico 3',false);
	define('TEXT_DIAGNOSIS_BLANK','(No especificada)',false);
	define('TEXT_FIND_ANOTHER_LINK','Buscar otro paciente',false);
	define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false);
	define('TEXT_FIRST_VISIT_TEXT','Nuevo',false);
	define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false);
	define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','Actualizar la información de esta visita',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false);
	define('TEXT_PATIENT_VISIT_NOT_FOUND','No encontró la visita especificada',false);
	define('TEXT_PAYMENT_CURRENCY','TBD',false);
	define('TEXT_PAYMENT_LABEL','Pago',false);
	define('TEXT_REFERRAL_BLANK','(No especificada)',false);
	define('TEXT_REFERRED_FROM_LABEL','Recibida de',false);
	define('TEXT_REFERRED_TO_LABEL','Enviada a',false);
	define('TEXT_RETURN_VISIT_TEXT','Subsiguiente',false);
	define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false);
	define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i d-m-Y',false);
	define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false);
	define('TEXT_VISIT_DAY_TEXT','d',false);
	define('TEXT_VISIT_DETAILS_PAGE_TITLE','Detalles de la visita',false);
	define('TEXT_VISIT_DISCHARGE_HEADING','Dar de alta',false);
	define('TEXT_VISIT_ID_LABEL','ID',false);
	define('TEXT_VISIT_MONTH_TEXT','m',false);
	define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false);
	define('TEXT_VISIT_STATUS_LABEL','Estatus',false);
	define('TEXT_VISIT_STATUS_OPEN','Admitido',false);
	define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false);
	define('TEXT_VISIT_UNABLE_OPEN_VISIT','No es posible abrir esta visita',false);
	define('TEXT_VISIT_VISIT_HEADING','Visita',false);
	define('TEXT_VISIT_YEAR_TEXT','a',false);
}
//EOF
