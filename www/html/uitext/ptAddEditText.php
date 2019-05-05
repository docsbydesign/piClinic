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
	if (!defined('TEXT_BIRTHDATE_FORMAT')) { define('TEXT_BIRTHDATE_FORMAT','TEXT_BIRTHDATE_FORMAT',false); }
	if (!defined('TEXT_BIRTHDATE_FORMAT_LABEL')) { define('TEXT_BIRTHDATE_FORMAT_LABEL','TEXT_BIRTHDATE_FORMAT_LABEL',false); }
	if (!defined('TEXT_BIRTHDATE_INPUT_LABEL')) { define('TEXT_BIRTHDATE_INPUT_LABEL','TEXT_BIRTHDATE_INPUT_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DAY_FORMAT')) { define('TEXT_BIRTHDAY_DAY_FORMAT','TEXT_BIRTHDAY_DAY_FORMAT',false); }
	if (!defined('TEXT_BIRTHDAY_MONTH_FORMAT')) { define('TEXT_BIRTHDAY_MONTH_FORMAT','TEXT_BIRTHDAY_MONTH_FORMAT',false); }
	if (!defined('TEXT_BIRTHDAY_YEAR_FORMAT')) { define('TEXT_BIRTHDAY_YEAR_FORMAT','TEXT_BIRTHDAY_YEAR_FORMAT',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','TEXT_BLANK_OPTION_SELECT',false); }
	if (!defined('TEXT_EDIT_PATIENT_HEADING')) { define('TEXT_EDIT_PATIENT_HEADING','TEXT_EDIT_PATIENT_HEADING',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','TEXT_FAMILYID_LABEL',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','TEXT_MARRIED_OPTION',false); }
	if (!defined('TEXT_NEW_PATIENT_HEADING')) { define('TEXT_NEW_PATIENT_HEADING','TEXT_NEW_PATIENT_HEADING',false); }
	if (!defined('TEXT_NOT_MARRIED_OPTION')) { define('TEXT_NOT_MARRIED_OPTION','TEXT_NOT_MARRIED_OPTION',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','TEXT_NO_OPTION',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','TEXT_PATIENTNATIONALID_LABEL',false); }
	if (!defined('TEXT_PATIENTNATIONALID_PLACEHOLDER')) { define('TEXT_PATIENTNATIONALID_PLACEHOLDER','TEXT_PATIENTNATIONALID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_CANCEL_ADD')) { define('TEXT_PATIENT_CANCEL_ADD','TEXT_PATIENT_CANCEL_ADD',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_EDIT_PAGE_TITLE')) { define('TEXT_PATIENT_EDIT_PAGE_TITLE','TEXT_PATIENT_EDIT_PAGE_TITLE',false); }
	if (!defined('TEXT_PATIENT_EDIT_SUBMIT_BUTTON')) { define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','TEXT_PATIENT_EDIT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_ID_LABEL')) { define('TEXT_PATIENT_ID_LABEL','TEXT_PATIENT_ID_LABEL',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','TEXT_PATIENT_KNOWN_ALLERGIES_LABEL',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NAME_LABEL')) { define('TEXT_PATIENT_NAME_LABEL','TEXT_PATIENT_NAME_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','TEXT_PATIENT_NEW_ADDRESS_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_BLOODTYPE_LABEL')) { define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','TEXT_PATIENT_NEW_BLOODTYPE_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_CITY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','TEXT_PATIENT_NEW_CITY_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','TEXT_PATIENT_NEW_CONTACT_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL')) { define('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL','TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL')) { define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_PAGE_TITLE')) { define('TEXT_PATIENT_NEW_PAGE_TITLE','TEXT_PATIENT_NEW_PAGE_TITLE',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','TEXT_PATIENT_NEW_PROFESSION_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER','TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER','TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_STATE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','TEXT_PATIENT_NEW_STATE_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_NEW_SUBMIT_BUTTON')) { define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','TEXT_PATIENT_NEW_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_PERSONAL_LABEL')) { define('TEXT_PATIENT_PERSONAL_LABEL','TEXT_PATIENT_PERSONAL_LABEL',false); }
	if (!defined('TEXT_SEX_INPUT_LABEL')) { define('TEXT_SEX_INPUT_LABEL','TEXT_SEX_INPUT_LABEL',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','TEXT_YES_OPTION',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_FORMAT')) { define('TEXT_BIRTHDATE_FORMAT','M-D-Y',false); }
	if (!defined('TEXT_BIRTHDATE_FORMAT_LABEL')) { define('TEXT_BIRTHDATE_FORMAT_LABEL','(M-D-Y)',false); }
	if (!defined('TEXT_BIRTHDATE_INPUT_LABEL')) { define('TEXT_BIRTHDATE_INPUT_LABEL','Birthdate',false); }
	if (!defined('TEXT_BIRTHDAY_DAY_FORMAT')) { define('TEXT_BIRTHDAY_DAY_FORMAT','d',false); }
	if (!defined('TEXT_BIRTHDAY_MONTH_FORMAT')) { define('TEXT_BIRTHDAY_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_BIRTHDAY_YEAR_FORMAT')) { define('TEXT_BIRTHDAY_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Choose',false); }
	if (!defined('TEXT_EDIT_PATIENT_HEADING')) { define('TEXT_EDIT_PATIENT_HEADING','Update patient info',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','Family ID',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','Married',false); }
	if (!defined('TEXT_NEW_PATIENT_HEADING')) { define('TEXT_NEW_PATIENT_HEADING','Add new patient',false); }
	if (!defined('TEXT_NOT_MARRIED_OPTION')) { define('TEXT_NOT_MARRIED_OPTION','Not married',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','No',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','National ID',false); }
	if (!defined('TEXT_PATIENTNATIONALID_PLACEHOLDER')) { define('TEXT_PATIENTNATIONALID_PLACEHOLDER','Patient\'s National ID number',false); }
	if (!defined('TEXT_PATIENT_CANCEL_ADD')) { define('TEXT_PATIENT_CANCEL_ADD','Cancel',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Current medications',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','Enter each medication, one medication and dose per line',false); }
	if (!defined('TEXT_PATIENT_EDIT_PAGE_TITLE')) { define('TEXT_PATIENT_EDIT_PAGE_TITLE','Update Patient',false); }
	if (!defined('TEXT_PATIENT_EDIT_SUBMIT_BUTTON')) { define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','Update patient info',false); }
	if (!defined('TEXT_PATIENT_ID_LABEL')) { define('TEXT_PATIENT_ID_LABEL','Patient, Visit, or Family ID',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','Known allergies',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','Enter each known allergy, one allergy per line',false); }
	if (!defined('TEXT_PATIENT_NAME_LABEL')) { define('TEXT_PATIENT_NAME_LABEL','Patient name',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Home address',false); }
	if (!defined('TEXT_PATIENT_NEW_BLOODTYPE_LABEL')) { define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','Blood type',false); }
	if (!defined('TEXT_PATIENT_NEW_CITY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','City',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','Alternate phone',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','Contact info',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','Primary phone',false); }
	if (!defined('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','County',false); }
	if (!defined('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','Family ID',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','Home address',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','Optional address info',false); }
	if (!defined('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL')) { define('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL','Marital status',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','First name',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','Second last name',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','Last name',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','MI',false); }
	if (!defined('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','Neighborhood',false); }
	if (!defined('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL')) { define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','Organ donor?',false); }
	if (!defined('TEXT_PATIENT_NEW_PAGE_TITLE')) { define('TEXT_PATIENT_NEW_PAGE_TITLE','New Patient',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','Preferred language',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','Preferred language',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','Profession',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER','Enter the patient\'s profession',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','Responsible person',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER','Enter the name of the person responsbile for the patient',false); }
	if (!defined('TEXT_PATIENT_NEW_STATE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','State',false); }
	if (!defined('TEXT_PATIENT_NEW_SUBMIT_BUTTON')) { define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','Add new patient',false); }
	if (!defined('TEXT_PATIENT_PERSONAL_LABEL')) { define('TEXT_PATIENT_PERSONAL_LABEL','Personal info',false); }
	if (!defined('TEXT_SEX_INPUT_LABEL')) { define('TEXT_SEX_INPUT_LABEL','Sex',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','Yes',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_FORMAT')) { define('TEXT_BIRTHDATE_FORMAT','D-M-Y',false); }
	if (!defined('TEXT_BIRTHDATE_FORMAT_LABEL')) { define('TEXT_BIRTHDATE_FORMAT_LABEL','(D-M-A)',false); }
	if (!defined('TEXT_BIRTHDATE_INPUT_LABEL')) { define('TEXT_BIRTHDATE_INPUT_LABEL','Fecha de nacimiento',false); }
	if (!defined('TEXT_BIRTHDAY_DAY_FORMAT')) { define('TEXT_BIRTHDAY_DAY_FORMAT','d',false); }
	if (!defined('TEXT_BIRTHDAY_MONTH_FORMAT')) { define('TEXT_BIRTHDAY_MONTH_FORMAT','m',false); }
	if (!defined('TEXT_BIRTHDAY_YEAR_FORMAT')) { define('TEXT_BIRTHDAY_YEAR_FORMAT','Y',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Escoge',false); }
	if (!defined('TEXT_EDIT_PATIENT_HEADING')) { define('TEXT_EDIT_PATIENT_HEADING','Actualizar la información del paciente',false); }
	if (!defined('TEXT_FAMILYID_LABEL')) { define('TEXT_FAMILYID_LABEL','Carpeta (familia)',false); }
	if (!defined('TEXT_MARRIED_OPTION')) { define('TEXT_MARRIED_OPTION','Casado/a',false); }
	if (!defined('TEXT_NEW_PATIENT_HEADING')) { define('TEXT_NEW_PATIENT_HEADING','Agregar paciente nuevo',false); }
	if (!defined('TEXT_NOT_MARRIED_OPTION')) { define('TEXT_NOT_MARRIED_OPTION','Soltero/a',false); }
	if (!defined('TEXT_NO_OPTION')) { define('TEXT_NO_OPTION','No',false); }
	if (!defined('TEXT_PATIENTNATIONALID_LABEL')) { define('TEXT_PATIENTNATIONALID_LABEL','Cedula',false); }
	if (!defined('TEXT_PATIENTNATIONALID_PLACEHOLDER')) { define('TEXT_PATIENTNATIONALID_PLACEHOLDER','Numero de la cedula del paciente',false); }
	if (!defined('TEXT_PATIENT_CANCEL_ADD')) { define('TEXT_PATIENT_CANCEL_ADD','Cancelar',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Medicamentos actuales',false); }
	if (!defined('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER')) { define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','Ingrese cada medicamento actual, un medicamento y dosis por línea',false); }
	if (!defined('TEXT_PATIENT_EDIT_PAGE_TITLE')) { define('TEXT_PATIENT_EDIT_PAGE_TITLE','Editar Paciente',false); }
	if (!defined('TEXT_PATIENT_EDIT_SUBMIT_BUTTON')) { define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','Actualizar información del paciente',false); }
	if (!defined('TEXT_PATIENT_ID_LABEL')) { define('TEXT_PATIENT_ID_LABEL','Número del paciente, visita, o familia',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','Alergias conocidas',false); }
	if (!defined('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER')) { define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','Ingrese cada alergia conocida, una alergia por línea',false); }
	if (!defined('TEXT_PATIENT_NAME_LABEL')) { define('TEXT_PATIENT_NAME_LABEL','Nombre del paciente',false); }
	if (!defined('TEXT_PATIENT_NEW_ADDRESS_LABEL')) { define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Dirección de la casa',false); }
	if (!defined('TEXT_PATIENT_NEW_BLOODTYPE_LABEL')) { define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','Tipo de Sangre',false); }
	if (!defined('TEXT_PATIENT_NEW_CITY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','Municipio',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','Otro número telefónico',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_LABEL')) { define('TEXT_PATIENT_NEW_CONTACT_LABEL','Información del contacto',false); }
	if (!defined('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','Primer número telefónico',false); }
	if (!defined('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','Condado',false); }
	if (!defined('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','ID de la carpeta familiar',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','Dirección de la casa',false); }
	if (!defined('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','Dirección adicional',false); }
	if (!defined('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL')) { define('TEXT_PATIENT_NEW_MARITAL_STATUS_LABEL','Estado civil',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','Primer nombre',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','Segundo apellido',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','Apellido',false); }
	if (!defined('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','Inicial',false); }
	if (!defined('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','Localidad',false); }
	if (!defined('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL')) { define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','¿Donante de órganos?',false); }
	if (!defined('TEXT_PATIENT_NEW_PAGE_TITLE')) { define('TEXT_PATIENT_NEW_PAGE_TITLE','Nuevo Paciente',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','Idioma preferido',false); }
	if (!defined('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','Idioma preferido',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_LABEL')) { define('TEXT_PATIENT_NEW_PROFESSION_LABEL','Profesión',false); }
	if (!defined('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_PROFESSION_PLACEHOLDER','Entrar la profesión del paciente',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_LABEL','Encargado/a',false); }
	if (!defined('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_RESPONSIBLE_PARTY_PLACEHOLDER','Entrar el nombre del encargado',false); }
	if (!defined('TEXT_PATIENT_NEW_STATE_PLACEHOLDER')) { define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','Departamento',false); }
	if (!defined('TEXT_PATIENT_NEW_SUBMIT_BUTTON')) { define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','Agregar paciente nuevo',false); }
	if (!defined('TEXT_PATIENT_PERSONAL_LABEL')) { define('TEXT_PATIENT_PERSONAL_LABEL','Información personal',false); }
	if (!defined('TEXT_SEX_INPUT_LABEL')) { define('TEXT_SEX_INPUT_LABEL','Sexo',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','M',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','H',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_YES_OPTION')) { define('TEXT_YES_OPTION','Si',false); }
}
//EOF
