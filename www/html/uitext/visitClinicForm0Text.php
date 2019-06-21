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
	if (!defined('TEXT_ASSESSMENT_NOTES_LABEL')) { define('TEXT_ASSESSMENT_NOTES_LABEL','TEXT_ASSESSMENT_NOTES_LABEL',false); }
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','TEXT_ASSIGNED_LABEL',false); }
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','TEXT_BIRTHDATE_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_CLINIC_VISIT_HEADING')) { define('TEXT_CLINIC_VISIT_HEADING','TEXT_CLINIC_VISIT_HEADING',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN')) { define('TEXT_CLINIC_VISIT_RETURN','TEXT_CLINIC_VISIT_RETURN',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN_LINK')) { define('TEXT_CLINIC_VISIT_RETURN_LINK','TEXT_CLINIC_VISIT_RETURN_LINK',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','TEXT_DATE_BLANK',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','TEXT_DIAGNOSIS_1_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','TEXT_DIAGNOSIS_2_LABEL',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','TEXT_DIAGNOSIS_3_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','TEXT_FIRST_VISIT_LABEL',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','TEXT_FIRST_VISIT_TEXT',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','TEXT_MARITAL_STATUS_LABEL',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','TEXT_MESSAGE_GENERIC',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','TEXT_NEXT_VAX_DATE_INPUT_LABEL',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','TEXT_PATIENT_ALLERGY_LIST_HEAD',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','TEXT_PATIENT_NEW_ADDRESS_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','TEXT_PATIENT_NEW_CONTACT_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','TEXT_PATIENT_NEW_PROFESSION_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','TEXT_PATIENT_NO_CURRENT_MEDS',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','TEXT_PATIENT_NO_KNOWN_ALLERGIES',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','TEXT_REFERRED_FROM_LABEL',false); }
	if (!defined('TEXT_REFER_TO_LABEL')) { define('TEXT_REFER_TO_LABEL','TEXT_REFER_TO_LABEL',false); }
	if (!defined('TEXT_REPRINT_HEADING')) { define('TEXT_REPRINT_HEADING','TEXT_REPRINT_HEADING',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','TEXT_RETURN_VISIT_TEXT',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','TEXT_VISIT_DATE_LABEL',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','TEXT_VISIT_DAY_TEXT',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','TEXT_VISIT_DETAILS_PAGE_TITLE',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','TEXT_VISIT_FORM_BP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','TEXT_VISIT_FORM_BS_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL')) { define('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL','TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','TEXT_VISIT_FORM_HEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','TEXT_VISIT_FORM_PULSE_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','TEXT_VISIT_FORM_TEMP_LABEL',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','TEXT_VISIT_FORM_WEIGHT_LABEL',false); }
	if (!defined('TEXT_VISIT_ID_PRINT_LABEL')) { define('TEXT_VISIT_ID_PRINT_LABEL','TEXT_VISIT_ID_PRINT_LABEL',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','TEXT_VISIT_LIST_HEAD_COMPLAINT',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NOTES')) { define('TEXT_VISIT_LIST_HEAD_NOTES','TEXT_VISIT_LIST_HEAD_NOTES',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','TEXT_VISIT_MONTH_TEXT',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','TEXT_VISIT_YEAR_TEXT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ASSESSMENT_NOTES_LABEL')) { define('TEXT_ASSESSMENT_NOTES_LABEL','Assessment',false); }
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor',false); }
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Birthdate',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_CLINIC_VISIT_HEADING')) { define('TEXT_CLINIC_VISIT_HEADING','Patient Visit Chart',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN')) { define('TEXT_CLINIC_VISIT_RETURN','Return to Visit Info',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN_LINK')) { define('TEXT_CLINIC_VISIT_RETURN_LINK','Return',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','Not specified',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','Diagnosis 1',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','Diagnosis 2',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','Diagnosis 3',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','New patient?',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','Yes',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','Marital status',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','There was a problem with the last entry. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','Patient visit not found.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','Patient visit ID not specified.',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','Next vaccination date',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Known allergies',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Current medications',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Home address',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','Contact info',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','Profession',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','Responsible person',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','No medications on file',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','No known allergies',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Referred from',false); }
	if (!defined('TEXT_REFER_TO_LABEL')) { define('TEXT_REFER_TO_LABEL','Refer to',false); }
	if (!defined('TEXT_REPRINT_HEADING')) { define('TEXT_REPRINT_HEADING','This is a reprint of the visit form. See the orignal patient record for details.',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','No',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Visit Date',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','Patient Visit Details',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','B.P.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL')) { define('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL','N&nbsp;|&nbsp;S&nbsp;',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Height',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulse',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Weight',false); }
	if (!defined('TEXT_VISIT_ID_PRINT_LABEL')) { define('TEXT_VISIT_ID_PRINT_LABEL','Visit ID',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Reason for visit',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NOTES')) { define('TEXT_VISIT_LIST_HEAD_NOTES','Additional visit notes',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ASSESSMENT_NOTES_LABEL')) { define('TEXT_ASSESSMENT_NOTES_LABEL','Tratamiento',false); }
	if (!defined('TEXT_ASSIGNED_LABEL')) { define('TEXT_ASSIGNED_LABEL','Doctor(a)',false); }
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Fecha de nacimiento',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_CLINIC_VISIT_HEADING')) { define('TEXT_CLINIC_VISIT_HEADING','Historia Clínica',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN')) { define('TEXT_CLINIC_VISIT_RETURN','Volver a la información de la visita',false); }
	if (!defined('TEXT_CLINIC_VISIT_RETURN_LINK')) { define('TEXT_CLINIC_VISIT_RETURN_LINK','Volver',false); }
	if (!defined('TEXT_DATE_BLANK')) { define('TEXT_DATE_BLANK','(No especificada)',false); }
	if (!defined('TEXT_DIAGNOSIS_1_LABEL')) { define('TEXT_DIAGNOSIS_1_LABEL','Diagnóstico 1',false); }
	if (!defined('TEXT_DIAGNOSIS_2_LABEL')) { define('TEXT_DIAGNOSIS_2_LABEL','Diagnóstico 2',false); }
	if (!defined('TEXT_DIAGNOSIS_3_LABEL')) { define('TEXT_DIAGNOSIS_3_LABEL','Diagnóstico 3',false); }
	if (!defined('TEXT_FIRST_VISIT_LABEL')) { define('TEXT_FIRST_VISIT_LABEL','Nuevo o Subsiguiente?',false); }
	if (!defined('TEXT_FIRST_VISIT_TEXT')) { define('TEXT_FIRST_VISIT_TEXT','Nuevo',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','Estado civil',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','Hubo un problema con el último cambio. Revisa los datos e intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_FOUND','No se encontró la visita del paciente.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED')) { define('TEXT_MESSAGE_PATIENT_VISIT_NOT_SPEICIFIED','ID de la visita no está especificada.',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','La próxima fecha de vacunación',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificada',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Alergias conocidas',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Medicamentos actuales',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Dirección de la casa',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','Información del contacto',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','Ocupación',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','Encargado/a',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','No hay medicamentos en el archivo',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','Sin alergias conocidas',false); }
	if (!defined('TEXT_REFERRED_FROM_LABEL')) { define('TEXT_REFERRED_FROM_LABEL','Recibida de',false); }
	if (!defined('TEXT_REFER_TO_LABEL')) { define('TEXT_REFER_TO_LABEL','Se remite a',false); }
	if (!defined('TEXT_REPRINT_HEADING')) { define('TEXT_REPRINT_HEADING','Este es un reimpreso del formulario de la visita. Revisar la historia clínica original para los detalles de la visita.',false); }
	if (!defined('TEXT_RETURN_VISIT_TEXT')) { define('TEXT_RETURN_VISIT_TEXT','Subsiguiente',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_VISIT_DATE_LABEL')) { define('TEXT_VISIT_DATE_LABEL','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_DAY_TEXT')) { define('TEXT_VISIT_DAY_TEXT','d',false); }
	if (!defined('TEXT_VISIT_DETAILS_PAGE_TITLE')) { define('TEXT_VISIT_DETAILS_PAGE_TITLE','Detalles de la visita',false); }
	if (!defined('TEXT_VISIT_FORM_BP_LABEL')) { define('TEXT_VISIT_FORM_BP_LABEL','P.A.',false); }
	if (!defined('TEXT_VISIT_FORM_BS_LABEL')) { define('TEXT_VISIT_FORM_BS_LABEL','RBS/FBS',false); }
	if (!defined('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL')) { define('TEXT_VISIT_FORM_DIAGNOSIS_PROMPT_LABEL','N&nbsp;|&nbsp;S&nbsp;',false); }
	if (!defined('TEXT_VISIT_FORM_HEIGHT_LABEL')) { define('TEXT_VISIT_FORM_HEIGHT_LABEL','Estatura',false); }
	if (!defined('TEXT_VISIT_FORM_PULSE_LABEL')) { define('TEXT_VISIT_FORM_PULSE_LABEL','Pulso',false); }
	if (!defined('TEXT_VISIT_FORM_TEMP_LABEL')) { define('TEXT_VISIT_FORM_TEMP_LABEL','Temp.',false); }
	if (!defined('TEXT_VISIT_FORM_WEIGHT_LABEL')) { define('TEXT_VISIT_FORM_WEIGHT_LABEL','Peso',false); }
	if (!defined('TEXT_VISIT_ID_PRINT_LABEL')) { define('TEXT_VISIT_ID_PRINT_LABEL','ID de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Motivo de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NOTES')) { define('TEXT_VISIT_LIST_HEAD_NOTES','Notas adicionales de la visita',false); }
	if (!defined('TEXT_VISIT_MONTH_TEXT')) { define('TEXT_VISIT_MONTH_TEXT','m',false); }
	if (!defined('TEXT_VISIT_YEAR_TEXT')) { define('TEXT_VISIT_YEAR_TEXT','a',false); }
}
//EOF
