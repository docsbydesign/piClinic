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
	define('TEXT_BIRTHDATE_LABEL','TEXT_BIRTHDATE_LABEL',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false);
	define('TEXT_BLOODTYPE_LABEL','TEXT_BLOODTYPE_LABEL',false);
	define('TEXT_CLINICPATIENTID_LABEL','TEXT_CLINICPATIENTID_LABEL',false);
	define('TEXT_CONTACTALTPHONE_LABEL','TEXT_CONTACTALTPHONE_LABEL',false);
	define('TEXT_CONTACTALTPHONE_MISSING','TEXT_CONTACTALTPHONE_MISSING',false);
	define('TEXT_CONTACTPHONE_LABEL','TEXT_CONTACTPHONE_LABEL',false);
	define('TEXT_CONTACTPHONE_MISSING','TEXT_CONTACTPHONE_MISSING',false);
	define('TEXT_DIAGNOSIS_BLANK','TEXT_DIAGNOSIS_BLANK',false);
	define('TEXT_DISCHARGE_VISIT_INFO','TEXT_DISCHARGE_VISIT_INFO',false);
	define('TEXT_EDIT_VISIT_INFO','TEXT_EDIT_VISIT_INFO',false);
	define('TEXT_FAMILYID_LABEL','TEXT_FAMILYID_LABEL',false);
	define('TEXT_FIND_ANOTHER_LINK','TEXT_FIND_ANOTHER_LINK',false);
	define('TEXT_FULLNAME_LABEL','TEXT_FULLNAME_LABEL',false);
	define('TEXT_HOMEADDRESS1_MISSING','TEXT_HOMEADDRESS1_MISSING',false);
	define('TEXT_HOMEADDRESS2_MISSING','TEXT_HOMEADDRESS2_MISSING',false);
	define('TEXT_HOMEADDRESS_LABEL','TEXT_HOMEADDRESS_LABEL',false);
	define('TEXT_HOMECITY_MISSING','TEXT_HOMECITY_MISSING',false);
	define('TEXT_HOMECOUNTY_MISSING','TEXT_HOMECOUNTY_MISSING',false);
	define('TEXT_HOMENEIGHBORHOOD_MISSING','TEXT_HOMENEIGHBORHOOD_MISSING',false);
	define('TEXT_HOMESTATE_MISSING','TEXT_HOMESTATE_MISSING',false);
	define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','TEXT_MESSAGE_PATIENT_ID_NOT_FOUND',false);
	define('TEXT_MORE_VISIT_INFO','TEXT_MORE_VISIT_INFO',false);
	define('TEXT_NO_PREVIOUS_VISITS','TEXT_NO_PREVIOUS_VISITS',false);
	define('TEXT_ORGAN_DONOR','TEXT_ORGAN_DONOR',false);
	define('TEXT_PATIENTNATIONALID_LABEL','TEXT_PATIENTNATIONALID_LABEL',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON',false);
	define('TEXT_PATIENT_ALLERGY_LIST_HEAD','TEXT_PATIENT_ALLERGY_LIST_HEAD',false);
	define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD',false);
	define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD',false);
	define('TEXT_PATIENT_DATA_HEAD','TEXT_PATIENT_DATA_HEAD',false);
	define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','TEXT_PATIENT_EDIT_PATIENT_BUTTON',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false);
	define('TEXT_PATIENT_INFO_PAGE_TITLE','TEXT_PATIENT_INFO_PAGE_TITLE',false);
	define('TEXT_PATIENT_NO_CURRENT_MEDS','TEXT_PATIENT_NO_CURRENT_MEDS',false);
	define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','TEXT_PATIENT_NO_KNOWN_ALLERGIES',false);
	define('TEXT_PATIENT_OPEN_NEW_VISIT','TEXT_PATIENT_OPEN_NEW_VISIT',false);
	define('TEXT_PATIENT_VISIT_LIST_HEAD','TEXT_PATIENT_VISIT_LIST_HEAD',false);
	define('TEXT_PHONE_LABEL','TEXT_PHONE_LABEL',false);
	define('TEXT_PREFERREDLANGUAGE_LABEL','TEXT_PREFERREDLANGUAGE_LABEL',false);
	define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED',false);
	define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false);
	define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false);
	define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false);
	define('TEXT_SHOW_VISIT_INFO','TEXT_SHOW_VISIT_INFO',false);
	define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false);
	define('TEXT_VISIT_LIST_ACTIONS','TEXT_VISIT_LIST_ACTIONS',false);
	define('TEXT_VISIT_LIST_ACTION_DISCHARGE','TEXT_VISIT_LIST_ACTION_DISCHARGE',false);
	define('TEXT_VISIT_LIST_ACTION_EDIT','TEXT_VISIT_LIST_ACTION_EDIT',false);
	define('TEXT_VISIT_LIST_ACTION_MORE','TEXT_VISIT_LIST_ACTION_MORE',false);
	define('TEXT_VISIT_LIST_ACTION_VIEW','TEXT_VISIT_LIST_ACTION_VIEW',false);
	define('TEXT_VISIT_LIST_HEAD_COMPLAINT','TEXT_VISIT_LIST_HEAD_COMPLAINT',false);
	define('TEXT_VISIT_LIST_HEAD_DATE','TEXT_VISIT_LIST_HEAD_DATE',false);
	define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','TEXT_VISIT_LIST_HEAD_DIAGNOSIS1',false);
	define('TEXT_VISIT_LIST_HEAD_DOCTOR','TEXT_VISIT_LIST_HEAD_DOCTOR',false);
	define('TEXT_VISIT_LIST_HEAD_TIME','TEXT_VISIT_LIST_HEAD_TIME',false);
	define('TEXT_VISIT_LIST_MISSING','TEXT_VISIT_LIST_MISSING',false);
	define('TEXT_VISIT_TIME_FORMAT','TEXT_VISIT_TIME_FORMAT',false);
	define('TEXT_YMD_AGE_DAYS','TEXT_YMD_AGE_DAYS',false);
	define('TEXT_YMD_AGE_MONTHS','TEXT_YMD_AGE_MONTHS',false);
	define('TEXT_YMD_AGE_YEARS','TEXT_YMD_AGE_YEARS',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_LABEL','Birthdate',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false);
	define('TEXT_BLOODTYPE_LABEL','Blood Type',false);
	define('TEXT_CLINICPATIENTID_LABEL','Patient ID',false);
	define('TEXT_CONTACTALTPHONE_LABEL','Alternate phone',false);
	define('TEXT_CONTACTALTPHONE_MISSING','(alternate phone number not provided)',false);
	define('TEXT_CONTACTPHONE_LABEL','Primary phone',false);
	define('TEXT_CONTACTPHONE_MISSING','(primary phone number not provided)',false);
	define('TEXT_DIAGNOSIS_BLANK','(Not specified)',false);
	define('TEXT_DISCHARGE_VISIT_INFO','Discharge this patient',false);
	define('TEXT_EDIT_VISIT_INFO','Edit this visit',false);
	define('TEXT_FAMILYID_LABEL','Family ID',false);
	define('TEXT_FIND_ANOTHER_LINK','Search for another patient',false);
	define('TEXT_FULLNAME_LABEL','Patient name',false);
	define('TEXT_HOMEADDRESS1_MISSING','(Address 1 not specified)',false);
	define('TEXT_HOMEADDRESS2_MISSING','(Address 2 not specified)',false);
	define('TEXT_HOMEADDRESS_LABEL','Home address',false);
	define('TEXT_HOMECITY_MISSING','(City not specified)',false);
	define('TEXT_HOMECOUNTY_MISSING','(County not specified)',false);
	define('TEXT_HOMENEIGHBORHOOD_MISSING','(Neighborhood not specified)',false);
	define('TEXT_HOMESTATE_MISSING','(State not specified)',false);
	define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','The patient with that ID was not found.',false);
	define('TEXT_MORE_VISIT_INFO','See the complete complaint in the visit description',false);
	define('TEXT_NO_PREVIOUS_VISITS','No previous admission records',false);
	define('TEXT_ORGAN_DONOR','[Organ Donor]',false);
	define('TEXT_PATIENTNATIONALID_LABEL','National ID',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Add a new patient',false);
	define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Known allergies',false);
	define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','Current medications',false);
	define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','Patient is currently in the clinic',false);
	define('TEXT_PATIENT_DATA_HEAD','Patient data',false);
	define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','Update this patient\'s info',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false);
	define('TEXT_PATIENT_INFO_PAGE_TITLE','Patient Information',false);
	define('TEXT_PATIENT_NO_CURRENT_MEDS','No medications on file',false);
	define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','No known allergies',false);
	define('TEXT_PATIENT_OPEN_NEW_VISIT','Admit this patient to the clinic',false);
	define('TEXT_PATIENT_VISIT_LIST_HEAD','Earlier visits to this clinic',false);
	define('TEXT_PHONE_LABEL','Contact info',false);
	define('TEXT_PREFERREDLANGUAGE_LABEL','Preferred language',false);
	define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','(not specified)',false);
	define('TEXT_SEX_OPTION_F','F',false);
	define('TEXT_SEX_OPTION_M','M',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false);
	define('TEXT_SHOW_VISIT_INFO','Show visit details',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i m/d/Y',false);
	define('TEXT_VISIT_LIST_ACTIONS','Visit actions',false);
	define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Discharge',false);
	define('TEXT_VISIT_LIST_ACTION_EDIT','Edit',false);
	define('TEXT_VISIT_LIST_ACTION_MORE','more...',false);
	define('TEXT_VISIT_LIST_ACTION_VIEW','View',false);
	define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Reason for visit',false);
	define('TEXT_VISIT_LIST_HEAD_DATE','Arrived',false);
	define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','Diagnosis 1',false);
	define('TEXT_VISIT_LIST_HEAD_DOCTOR','Doctor',false);
	define('TEXT_VISIT_LIST_HEAD_TIME','Arrived',false);
	define('TEXT_VISIT_LIST_MISSING','(missing)',false);
	define('TEXT_VISIT_TIME_FORMAT','H:i m/d/Y',false);
	define('TEXT_YMD_AGE_DAYS','d',false);
	define('TEXT_YMD_AGE_MONTHS','m',false);
	define('TEXT_YMD_AGE_YEARS','y',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_LABEL','Fecha de nacimiento',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false);
	define('TEXT_BLOODTYPE_LABEL','Tipo de Sangre',false);
	define('TEXT_CLINICPATIENTID_LABEL','Identidad del paciente',false);
	define('TEXT_CONTACTALTPHONE_LABEL','Otro número telefónico',false);
	define('TEXT_CONTACTALTPHONE_MISSING','(Otro número telefónico no especificado)',false);
	define('TEXT_CONTACTPHONE_LABEL','Primer número telefónico',false);
	define('TEXT_CONTACTPHONE_MISSING','(Primer número telefónico no especificado)',false);
	define('TEXT_DIAGNOSIS_BLANK','(No especificada)',false);
	define('TEXT_DISCHARGE_VISIT_INFO','Dar de alta este paciente',false);
	define('TEXT_EDIT_VISIT_INFO','Actualizar esta visita',false);
	define('TEXT_FAMILYID_LABEL','Carpeta (familia)',false);
	define('TEXT_FIND_ANOTHER_LINK','Buscar otro paciente',false);
	define('TEXT_FULLNAME_LABEL','Nombre del paciente',false);
	define('TEXT_HOMEADDRESS1_MISSING','(Dirección no especificada)',false);
	define('TEXT_HOMEADDRESS2_MISSING','(Dirección adicional no especificada)',false);
	define('TEXT_HOMEADDRESS_LABEL','Dirección de la casa',false);
	define('TEXT_HOMECITY_MISSING','(Municipio no especificada)',false);
	define('TEXT_HOMECOUNTY_MISSING','(Condado no especificada)',false);
	define('TEXT_HOMENEIGHBORHOOD_MISSING','(Localidad no especificada)',false);
	define('TEXT_HOMESTATE_MISSING','(Departamento no especificada)',false);
	define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','No se encontró el paciente con ese ID.',false);
	define('TEXT_MORE_VISIT_INFO','Ver la descripcción completa',false);
	define('TEXT_NO_PREVIOUS_VISITS','No hay archivos de admisiónes anteriores',false);
	define('TEXT_ORGAN_DONOR','[Donante de órganos]',false);
	define('TEXT_PATIENTNATIONALID_LABEL','Cedula',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Agregar un paciente nuevo',false);
	define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Alergias conocidas',false);
	define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','Medicamentos actuales',false);
	define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','El paciente está en la clínica ahora',false);
	define('TEXT_PATIENT_DATA_HEAD','Datos del paciente',false);
	define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','Actualizar la información del paciente',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false);
	define('TEXT_PATIENT_INFO_PAGE_TITLE','Información del paciente',false);
	define('TEXT_PATIENT_NO_CURRENT_MEDS','No hay medicamentos en el archivo',false);
	define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','Sin alergias conocidas',false);
	define('TEXT_PATIENT_OPEN_NEW_VISIT','Abra una visita nueva para este paciente',false);
	define('TEXT_PATIENT_VISIT_LIST_HEAD','Visitas anteriores a esta clínica',false);
	define('TEXT_PHONE_LABEL','Información del contacto',false);
	define('TEXT_PREFERREDLANGUAGE_LABEL','Idioma preferido',false);
	define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','(no está especificado)',false);
	define('TEXT_SEX_OPTION_F','M',false);
	define('TEXT_SEX_OPTION_M','H',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false);
	define('TEXT_SHOW_VISIT_INFO','Mostrar los detalles de la visita',false);
	define('TEXT_VISIT_DATE_FORMAT','H:i d-m-Y',false);
	define('TEXT_VISIT_LIST_ACTIONS','Acciones de la visita',false);
	define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Dar de alta',false);
	define('TEXT_VISIT_LIST_ACTION_EDIT','Actualizar',false);
	define('TEXT_VISIT_LIST_ACTION_MORE','más...',false);
	define('TEXT_VISIT_LIST_ACTION_VIEW','Ver',false);
	define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Motivo de la visita',false);
	define('TEXT_VISIT_LIST_HEAD_DATE','Llegó',false);
	define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','Diagnóstico 1',false);
	define('TEXT_VISIT_LIST_HEAD_DOCTOR','Medico',false);
	define('TEXT_VISIT_LIST_HEAD_TIME','Llegó',false);
	define('TEXT_VISIT_LIST_MISSING','(no especificado)',false);
	define('TEXT_VISIT_TIME_FORMAT','H:i d-m-Y',false);
	define('TEXT_YMD_AGE_DAYS','d',false);
	define('TEXT_YMD_AGE_MONTHS','m',false);
	define('TEXT_YMD_AGE_YEARS','a',false);
}
//EOF
