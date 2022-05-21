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
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','TEXT_BIRTHDATE_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_BLOODTYPE_LABEL')) { define('TEXT_BLOODTYPE_LABEL','TEXT_BLOODTYPE_LABEL',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','TEXT_CLINICPATIENTID_LABEL',false); }
	if (!defined('TEXT_CONTACTALTPHONE_LABEL')) { define('TEXT_CONTACTALTPHONE_LABEL','TEXT_CONTACTALTPHONE_LABEL',false); }
	if (!defined('TEXT_CONTACTALTPHONE_MISSING')) { define('TEXT_CONTACTALTPHONE_MISSING','TEXT_CONTACTALTPHONE_MISSING',false); }
	if (!defined('TEXT_CONTACTPHONE_LABEL')) { define('TEXT_CONTACTPHONE_LABEL','TEXT_CONTACTPHONE_LABEL',false); }
	if (!defined('TEXT_CONTACTPHONE_MISSING')) { define('TEXT_CONTACTPHONE_MISSING','TEXT_CONTACTPHONE_MISSING',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','TEXT_DIAGNOSIS_BLANK',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','TEXT_DISCHARGE_VISIT_INFO',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','TEXT_EDIT_VISIT_INFO',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','TEXT_FAMILYID_LABEL',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','TEXT_FIND_ANOTHER_LINK',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','TEXT_FULLNAME_LABEL',false); }
	if (!defined('TEXT_HOMEADDRESS1_MISSING')) { define('TEXT_HOMEADDRESS1_MISSING','TEXT_HOMEADDRESS1_MISSING',false); }
	if (!defined('TEXT_HOMEADDRESS2_MISSING')) { define('TEXT_HOMEADDRESS2_MISSING','TEXT_HOMEADDRESS2_MISSING',false); }
	if (!defined('TEXT_HOMEADDRESS_LABEL')) { define('TEXT_HOMEADDRESS_LABEL','TEXT_HOMEADDRESS_LABEL',false); }
	if (!defined('TEXT_HOMECITY_MISSING')) { define('TEXT_HOMECITY_MISSING','TEXT_HOMECITY_MISSING',false); }
	if (!defined('TEXT_HOMECOUNTY_MISSING')) { define('TEXT_HOMECOUNTY_MISSING','TEXT_HOMECOUNTY_MISSING',false); }
	if (!defined('TEXT_HOMENEIGHBORHOOD_MISSING')) { define('TEXT_HOMENEIGHBORHOOD_MISSING','TEXT_HOMENEIGHBORHOOD_MISSING',false); }
	if (!defined('TEXT_HOMESTATE_MISSING')) { define('TEXT_HOMESTATE_MISSING','TEXT_HOMESTATE_MISSING',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','TEXT_MARITAL_STATUS_LABEL',false); }
	if (!defined('TEXT_MARITAL_STATUS_NOT_SPECIFIED')) { define('TEXT_MARITAL_STATUS_NOT_SPECIFIED','TEXT_MARITAL_STATUS_NOT_SPECIFIED',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','TEXT_MESSAGE_PATIENT_ID_NOT_FOUND',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','TEXT_MORE_VISIT_INFO',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','TEXT_NEXT_VAX_DATE_INPUT_LABEL',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','TEXT_NOT_SPECIFIED',false); }
	if (!defined('TEXT_NO_PREVIOUS_VISITS')) { define('TEXT_NO_PREVIOUS_VISITS','TEXT_NO_PREVIOUS_VISITS',false); }
	if (!defined('TEXT_ORGAN_DONOR')) { define('TEXT_ORGAN_DONOR','TEXT_ORGAN_DONOR',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','TEXT_PATIENTNATIONALID_LABEL',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','TEXT_PATIENT_ALLERGY_LIST_HEAD',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD',false); }
	if (!defined('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD',false); }
	if (!defined('TEXT_PATIENT_DATA_HEAD')) { define('TEXT_PATIENT_DATA_HEAD','TEXT_PATIENT_DATA_HEAD',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','TEXT_PATIENT_EDIT_PATIENT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_INFO_PAGE_TITLE')) { define('TEXT_PATIENT_INFO_PAGE_TITLE','TEXT_PATIENT_INFO_PAGE_TITLE',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','TEXT_PATIENT_NO_CURRENT_MEDS',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','TEXT_PATIENT_NO_KNOWN_ALLERGIES',false); }
	if (!defined('TEXT_PATIENT_OPEN_NEW_VISIT')) { define('TEXT_PATIENT_OPEN_NEW_VISIT','TEXT_PATIENT_OPEN_NEW_VISIT',false); }
	if (!defined('TEXT_PATIENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_VISIT_LIST_HEAD','TEXT_PATIENT_VISIT_LIST_HEAD',false); }
	if (!defined('TEXT_PHONE_LABEL')) { define('TEXT_PHONE_LABEL','TEXT_PHONE_LABEL',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PREFERREDLANGUAGE_LABEL','TEXT_PREFERREDLANGUAGE_LABEL',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED')) { define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED',false); }
	if (!defined('TEXT_PROFESSION_LABEL')) { define('TEXT_PROFESSION_LABEL','TEXT_PROFESSION_LABEL',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_RESPONSIBLE_PARTY_LABEL','TEXT_RESPONSIBLE_PARTY_LABEL',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED')) { define('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED','TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','TEXT_SHOW_VISIT_INFO',false); }
	if (!defined('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED')) { define('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED','TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED',false); }
	if (!defined('TEXT_VISIT_DATE_ONLY_FORMAT')) { define('TEXT_VISIT_DATE_ONLY_FORMAT','TEXT_VISIT_DATE_ONLY_FORMAT',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','TEXT_VISIT_LIST_ACTIONS',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','TEXT_VISIT_LIST_ACTION_DISCHARGE',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','TEXT_VISIT_LIST_ACTION_EDIT',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','TEXT_VISIT_LIST_ACTION_MORE',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','TEXT_VISIT_LIST_ACTION_VIEW',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','TEXT_VISIT_LIST_HEAD_COMPLAINT',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DATE')) { define('TEXT_VISIT_LIST_HEAD_DATE','TEXT_VISIT_LIST_HEAD_DATE',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1')) { define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','TEXT_VISIT_LIST_HEAD_DIAGNOSIS1',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','TEXT_VISIT_LIST_HEAD_DOCTOR',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_TIME')) { define('TEXT_VISIT_LIST_HEAD_TIME','TEXT_VISIT_LIST_HEAD_TIME',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','TEXT_VISIT_LIST_MISSING',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','TEXT_YMD_AGE_DAYS',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','TEXT_YMD_AGE_MONTHS',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','TEXT_YMD_AGE_YEARS',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Birthdate',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_BLOODTYPE_LABEL')) { define('TEXT_BLOODTYPE_LABEL','Blood Type',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','Patient&nbsp;ID',false); }
	if (!defined('TEXT_CONTACTALTPHONE_LABEL')) { define('TEXT_CONTACTALTPHONE_LABEL','Alternate phone',false); }
	if (!defined('TEXT_CONTACTALTPHONE_MISSING')) { define('TEXT_CONTACTALTPHONE_MISSING','(alternate phone number not provided)',false); }
	if (!defined('TEXT_CONTACTPHONE_LABEL')) { define('TEXT_CONTACTPHONE_LABEL','Primary phone',false); }
	if (!defined('TEXT_CONTACTPHONE_MISSING')) { define('TEXT_CONTACTPHONE_MISSING','(primary phone number not provided)',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','Not specified',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','Discharge this patient',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','Edit this visit',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','Family ID',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','Search for another patient',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Patient name',false); }
	if (!defined('TEXT_HOMEADDRESS1_MISSING')) { define('TEXT_HOMEADDRESS1_MISSING','(Address 1 not specified)',false); }
	if (!defined('TEXT_HOMEADDRESS2_MISSING')) { define('TEXT_HOMEADDRESS2_MISSING','(Address 2 not specified)',false); }
	if (!defined('TEXT_HOMEADDRESS_LABEL')) { define('TEXT_HOMEADDRESS_LABEL','Home address',false); }
	if (!defined('TEXT_HOMECITY_MISSING')) { define('TEXT_HOMECITY_MISSING','(City not specified)',false); }
	if (!defined('TEXT_HOMECOUNTY_MISSING')) { define('TEXT_HOMECOUNTY_MISSING','(County not specified)',false); }
	if (!defined('TEXT_HOMENEIGHBORHOOD_MISSING')) { define('TEXT_HOMENEIGHBORHOOD_MISSING','(Neighborhood not specified)',false); }
	if (!defined('TEXT_HOMESTATE_MISSING')) { define('TEXT_HOMESTATE_MISSING','(State not specified)',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','Marital status',false); }
	if (!defined('TEXT_MARITAL_STATUS_NOT_SPECIFIED')) { define('TEXT_MARITAL_STATUS_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','The patient with that ID was not found.',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','See all of the information about the visit description',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','Next vaccination date',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_NO_PREVIOUS_VISITS')) { define('TEXT_NO_PREVIOUS_VISITS','No previous admission records',false); }
	if (!defined('TEXT_ORGAN_DONOR')) { define('TEXT_ORGAN_DONOR','[Organ Donor]',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','National ID',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Add a new patient',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Known allergies',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','Current medications',false); }
	if (!defined('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','Patient is currently in the clinic',false); }
	if (!defined('TEXT_PATIENT_DATA_HEAD')) { define('TEXT_PATIENT_DATA_HEAD','Patient data',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','Update this patient\'s info',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','The name or ID to find',false); }
	if (!defined('TEXT_PATIENT_INFO_PAGE_TITLE')) { define('TEXT_PATIENT_INFO_PAGE_TITLE','Patient Information',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','No medications on file',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','No known allergies',false); }
	if (!defined('TEXT_PATIENT_OPEN_NEW_VISIT')) { define('TEXT_PATIENT_OPEN_NEW_VISIT','Admit this patient to the clinic',false); }
	if (!defined('TEXT_PATIENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_VISIT_LIST_HEAD','Earlier visits to this clinic',false); }
	if (!defined('TEXT_PHONE_LABEL')) { define('TEXT_PHONE_LABEL','Contact info',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PREFERREDLANGUAGE_LABEL','Preferred language',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED')) { define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_PROFESSION_LABEL')) { define('TEXT_PROFESSION_LABEL','Profession',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_RESPONSIBLE_PARTY_LABEL','Responsbile Person',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED')) { define('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','Show visit details',false); }
	if (!defined('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED')) { define('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED','Not specified',false); }
	if (!defined('TEXT_VISIT_DATE_ONLY_FORMAT')) { define('TEXT_VISIT_DATE_ONLY_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','Visit actions',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Discharge',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','Edit',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','more...',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','View',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Reason for visit',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DATE')) { define('TEXT_VISIT_LIST_HEAD_DATE','Visit date',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1')) { define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','Diagnosis 1',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','Doctor',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_TIME')) { define('TEXT_VISIT_LIST_HEAD_TIME','Arrived',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','(missing)',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','d',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','m',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','y',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Fecha de nacimiento',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_BLOODTYPE_LABEL')) { define('TEXT_BLOODTYPE_LABEL','Tipo de Sangre',false); }
	if (!defined('TEXT_CLINICPATIENTID_LABEL')) { define('TEXT_CLINICPATIENTID_LABEL','Identidad&nbsp;del&nbsp;paciente',false); }
	if (!defined('TEXT_CONTACTALTPHONE_LABEL')) { define('TEXT_CONTACTALTPHONE_LABEL','Otro número telefónico',false); }
	if (!defined('TEXT_CONTACTALTPHONE_MISSING')) { define('TEXT_CONTACTALTPHONE_MISSING','(Otro número telefónico no especificado)',false); }
	if (!defined('TEXT_CONTACTPHONE_LABEL')) { define('TEXT_CONTACTPHONE_LABEL','Primer número telefónico',false); }
	if (!defined('TEXT_CONTACTPHONE_MISSING')) { define('TEXT_CONTACTPHONE_MISSING','(Primer número telefónico no especificado)',false); }
	if (!defined('TEXT_DIAGNOSIS_BLANK')) { define('TEXT_DIAGNOSIS_BLANK','(No especificado)',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','Dar de alta este paciente',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','Actualizar esta visita',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','Carpeta (familia)',false); }
	if (!defined('TEXT_FIND_ANOTHER_LINK')) { define('TEXT_FIND_ANOTHER_LINK','Buscar otro paciente',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Nombre del paciente',false); }
	if (!defined('TEXT_HOMEADDRESS1_MISSING')) { define('TEXT_HOMEADDRESS1_MISSING','(Dirección no especificada)',false); }
	if (!defined('TEXT_HOMEADDRESS2_MISSING')) { define('TEXT_HOMEADDRESS2_MISSING','(Dirección adicional no especificada)',false); }
	if (!defined('TEXT_HOMEADDRESS_LABEL')) { define('TEXT_HOMEADDRESS_LABEL','Dirección de la casa',false); }
	if (!defined('TEXT_HOMECITY_MISSING')) { define('TEXT_HOMECITY_MISSING','(Municipio no especificado)',false); }
	if (!defined('TEXT_HOMECOUNTY_MISSING')) { define('TEXT_HOMECOUNTY_MISSING','(Condado no especificado)',false); }
	if (!defined('TEXT_HOMENEIGHBORHOOD_MISSING')) { define('TEXT_HOMENEIGHBORHOOD_MISSING','(Localidad no especificada)',false); }
	if (!defined('TEXT_HOMESTATE_MISSING')) { define('TEXT_HOMESTATE_MISSING','(Departamento no especificado)',false); }
	if (!defined('TEXT_MARITAL_STATUS_LABEL')) { define('TEXT_MARITAL_STATUS_LABEL','Estado civil',false); }
	if (!defined('TEXT_MARITAL_STATUS_NOT_SPECIFIED')) { define('TEXT_MARITAL_STATUS_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND')) { define('TEXT_MESSAGE_PATIENT_ID_NOT_FOUND','No se encontró el paciente con ese ID.',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','Ver la descripción completa',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT')) { define('TEXT_NEXT_VAX_DATE_DISPLAY_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_NEXT_VAX_DATE_INPUT_LABEL')) { define('TEXT_NEXT_VAX_DATE_INPUT_LABEL','Próxima fecha de vacunación',false); }
	if (!defined('TEXT_NOT_SPECIFIED')) { define('TEXT_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_NO_PREVIOUS_VISITS')) { define('TEXT_NO_PREVIOUS_VISITS','No hay archivos de admisiones anteriores',false); }
	if (!defined('TEXT_ORGAN_DONOR')) { define('TEXT_ORGAN_DONOR','[Donante de órganos]',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','Cédula',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Agregar un paciente nuevo',false); }
	if (!defined('TEXT_PATIENT_ALLERGY_LIST_HEAD')) { define('TEXT_PATIENT_ALLERGY_LIST_HEAD','Alergias conocidas',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_MEDS_LIST_HEAD','Medicamentos actuales',false); }
	if (!defined('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_CURRENT_VISIT_LIST_HEAD','El paciente está en la clínica ahora',false); }
	if (!defined('TEXT_PATIENT_DATA_HEAD')) { define('TEXT_PATIENT_DATA_HEAD','Datos del paciente',false); }
	if (!defined('TEXT_PATIENT_EDIT_PATIENT_BUTTON')) { define('TEXT_PATIENT_EDIT_PATIENT_BUTTON','Actualizar la información del paciente',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','El nombre o número para encontrar',false); }
	if (!defined('TEXT_PATIENT_INFO_PAGE_TITLE')) { define('TEXT_PATIENT_INFO_PAGE_TITLE','Información del paciente',false); }
	if (!defined('TEXT_PATIENT_NO_CURRENT_MEDS')) { define('TEXT_PATIENT_NO_CURRENT_MEDS','No hay medicamentos en el archivo',false); }
	if (!defined('TEXT_PATIENT_NO_KNOWN_ALLERGIES')) { define('TEXT_PATIENT_NO_KNOWN_ALLERGIES','Sin alergias conocidas',false); }
	if (!defined('TEXT_PATIENT_OPEN_NEW_VISIT')) { define('TEXT_PATIENT_OPEN_NEW_VISIT','Abra una visita nueva para este paciente',false); }
	if (!defined('TEXT_PATIENT_VISIT_LIST_HEAD')) { define('TEXT_PATIENT_VISIT_LIST_HEAD','Visitas anteriores a esta clínica',false); }
	if (!defined('TEXT_PHONE_LABEL')) { define('TEXT_PHONE_LABEL','Información del contacto',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PREFERREDLANGUAGE_LABEL','Idioma preferido',false); }
	if (!defined('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED')) { define('TEXT_PREFERREDLANGUAGE_NOT_SPECIFIED','(no está especificado)',false); }
	if (!defined('TEXT_PROFESSION_LABEL')) { define('TEXT_PROFESSION_LABEL','Profesión',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_RESPONSIBLE_PARTY_LABEL','Encargado/a',false); }
	if (!defined('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED')) { define('TEXT_RESPONSIBLE_PARTY_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','M',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','H',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','Mostrar los detalles de la visita',false); }
	if (!defined('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED')) { define('TEXT_TEXT_PROFESSION_LABEL_NOT_SPECIFIED','No especificado',false); }
	if (!defined('TEXT_VISIT_DATE_ONLY_FORMAT')) { define('TEXT_VISIT_DATE_ONLY_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','Acciones de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Dar de alta',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','Actualizar',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','más...',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','Ver',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Motivo de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DATE')) { define('TEXT_VISIT_LIST_HEAD_DATE','Fecha de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1')) { define('TEXT_VISIT_LIST_HEAD_DIAGNOSIS1','Diagnóstico 1',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','Médico',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_TIME')) { define('TEXT_VISIT_LIST_HEAD_TIME','Llegó',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','No especificado',false); }
	if (!defined('TEXT_YMD_AGE_DAYS')) { define('TEXT_YMD_AGE_DAYS','d',false); }
	if (!defined('TEXT_YMD_AGE_MONTHS')) { define('TEXT_YMD_AGE_MONTHS','m',false); }
	if (!defined('TEXT_YMD_AGE_YEARS')) { define('TEXT_YMD_AGE_YEARS','a',false); }
}
//EOF
