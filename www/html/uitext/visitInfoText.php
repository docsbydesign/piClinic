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
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','TEXT_ASSIGNED_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_CLOSE_VISIT')) { define('TEXT_CLOSE_VISIT','TEXT_CLOSE_VISIT',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','TEXT_COMPLAINT_ADDITIONAL_LABEL',false); }
	if (!defined('TEXT_COMPLAINT_NOT_SPECIFIED')) { define('TEXT_COMPLAINT_NOT_SPECIFIED','TEXT_COMPLAINT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','TEXT_COMPLAINT_PRIMARY_LABEL',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','TEXT_DATE_TIME_IN_LABEL',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','TEXT_DATE_TIME_OUT_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','TEXT_DIAGNOSIS1_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','TEXT_DIAGNOSIS2_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','TEXT_DIAGNOSIS3_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','TEXT_DIAGNOSIS_BLANK',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','TEXT_FIND_ANOTHER_LINK',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','TEXT_FIRST_VISIT_TEXT',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_PATIENT_VISIT_NOT_FOUND','TEXT_PATIENT_VISIT_NOT_FOUND',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TEXT_PAYMENT_CURRENCY',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','TEXT_PAYMENT_LABEL',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','TEXT_REFERRAL_BLANK',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','TEXT_REFERRED_TO_LABEL',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','TEXT_RETURN_VISIT_TEXT',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','TEXT_VISIT_ARRIVAL_HEADING',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','TEXT_VISIT_DETAILS_PAGE_TITLE',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','TEXT_VISIT_DISCHARGE_HEADING',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','TEXT_VISIT_ID_LABEL',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','TEXT_VISIT_STATUS_CLOSED',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','TEXT_VISIT_STATUS_LABEL',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','TEXT_VISIT_STATUS_OPEN',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','TEXT_VISIT_TYPE_LABEL',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','TEXT_VISIT_UNABLE_OPEN_VISIT',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','TEXT_VISIT_VISIT_HEADING',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_CLOSE_VISIT')) { define('TEXT_CLOSE_VISIT','Discharge this patient',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Additional complaint',false); }
	if (!defined('TEXT_COMPLAINT_NOT_SPECIFIED')) { define('TEXT_COMPLAINT_NOT_SPECIFIED','(Not specified)',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primary complaint',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(Not specified)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Admitted to clinic',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Departed clinic',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diganosis 1',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diganosis 2',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diganosis 3',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','(Not specified)',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','Search for another patient',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','New patient?',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','Yes',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','Update visit info',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false); }
	if (!defined('TEXT_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_PATIENT_VISIT_NOT_FOUND','The specified patient visit was not found',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TBD',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Payment',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','(Not specified)',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Referred to',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','No',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Arrival',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','H:i m/d/Y',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Visit Date',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','Patient Visit Details',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Discharge',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Discharged',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Status',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitted',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Visit type',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','Unable to access this visit',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visit',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_CLOSE_VISIT')) { define('TEXT_CLOSE_VISIT','Dar de alta este paciente',false); }
	if (!defined('TEXT_COMPLAINT_ADDITIONAL_LABEL')) { define('TEXT_COMPLAINT_ADDITIONAL_LABEL','Adicional motivo de la visita',false); }
	if (!defined('TEXT_COMPLAINT_NOT_SPECIFIED')) { define('TEXT_COMPLAINT_NOT_SPECIFIED','(No especificado)',false); }
	if (!defined('TEXT_COMPLAINT_PRIMARY_LABEL')) { define('TEXT_COMPLAINT_PRIMARY_LABEL','Primero motivo de la visita',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificada)',false); }
	if (!defined('TEXT_DATE_TIME_IN_LABEL')) { define('TEXT_DATE_TIME_IN_LABEL','Llegó a la clínica',false); }
	if (!defined('TEXT_DATE_TIME_OUT_LABEL')) { define('TEXT_DATE_TIME_OUT_LABEL','Salió de la clínica',false); }
	if (!defined('TEXT_DIAGNOSIS1_LABEL')) { define('TEXT_DIAGNOSIS1_LABEL','Diagnóstico 1',false); }
	if (!defined('TEXT_DIAGNOSIS2_LABEL')) { define('TEXT_DIAGNOSIS2_LABEL','Diagnóstico 2',false); }
	if (!defined('TEXT_DIAGNOSIS3_LABEL')) { define('TEXT_DIAGNOSIS3_LABEL','Diagnóstico 3',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','(No especificada)',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','Buscar otro paciente',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','Nuevo',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_VISIT_BUTTON','Actualizar la información de esta visita',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false); }
	if (!defined('TEXT_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_PATIENT_VISIT_NOT_FOUND','No encontró la visita especificada',false); }
	if (!defined('TEXT_PAYMENT_CURRENCY')) { define('TEXT_PAYMENT_CURRENCY','TBD',false); }
	if (!defined('TEXT_PAYMENT_LABEL')) { define('TEXT_PAYMENT_LABEL','Pago',false); }
	if (!defined('TEXT_REFERRAL_BLANK')) { define('TEXT_REFERRAL_BLANK','(No especificada)',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibida de',false); }
	if (!defined('TEXT_REFERRED_TO_LABEL')) { define('TEXT_REFERRED_TO_LABEL','Enviada a',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','Subsiguiente',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false); }
	if (!defined('TEXT_VISIT_ARRIVAL_HEADING')) { define('TEXT_VISIT_ARRIVAL_HEADING','Llegada',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','H:i d-m-Y',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','Detalles de la visita',false); }
	if (!defined('TEXT_VISIT_DISCHARGE_HEADING')) { define('TEXT_VISIT_DISCHARGE_HEADING','Dar de alta',false); }
	if (!defined('TEXT_VISIT_ID_LABEL')) { define('TEXT_VISIT_ID_LABEL','ID',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_STATUS_CLOSED')) { define('TEXT_VISIT_STATUS_CLOSED','Dado de alta',false); }
	if (!defined('TEXT_VISIT_STATUS_LABEL')) { define('TEXT_VISIT_STATUS_LABEL','Estatus',false); }
	if (!defined('TEXT_VISIT_STATUS_OPEN')) { define('TEXT_VISIT_STATUS_OPEN','Admitido',false); }
	if (!defined('TEXT_VISIT_TYPE_LABEL')) { define('TEXT_VISIT_TYPE_LABEL','Tipo de la visita',false); }
	if (!defined('TEXT_VISIT_UNABLE_OPEN_VISIT')) { define('TEXT_VISIT_UNABLE_OPEN_VISIT','No es posible abrir esta visita',false); }
	if (!defined('TEXT_VISIT_VISIT_HEADING')) { define('TEXT_VISIT_VISIT_HEADING','Visita',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','a',false); }
}
//EOF
