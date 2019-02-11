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
	define('TEXT_BIRTHDATE_FORMAT','TEXT_BIRTHDATE_FORMAT',false);
	define('TEXT_BIRTHDATE_FORMAT_LABEL','TEXT_BIRTHDATE_FORMAT_LABEL',false);
	define('TEXT_BIRTHDATE_INPUT_LABEL','TEXT_BIRTHDATE_INPUT_LABEL',false);
	define('TEXT_BIRTHDAY_DAY_FORMAT','TEXT_BIRTHDAY_DAY_FORMAT',false);
	define('TEXT_BIRTHDAY_MONTH_FORMAT','TEXT_BIRTHDAY_MONTH_FORMAT',false);
	define('TEXT_BIRTHDAY_YEAR_FORMAT','TEXT_BIRTHDAY_YEAR_FORMAT',false);
	define('TEXT_BLANK_OPTION_SELECT','TEXT_BLANK_OPTION_SELECT',false);
	define('TEXT_EDIT_PATIENT_HEADING','TEXT_EDIT_PATIENT_HEADING',false);
	define('TEXT_FAMILYID_LABEL','TEXT_FAMILYID_LABEL',false);
	define('TEXT_NEW_PATIENT_HEADING','TEXT_NEW_PATIENT_HEADING',false);
	define('TEXT_NO_OPTION','TEXT_NO_OPTION',false);
	define('TEXT_PATIENTNATIONALID_LABEL','TEXT_PATIENTNATIONALID_LABEL',false);
	define('TEXT_PATIENTNATIONALID_PLACEHOLDER','TEXT_PATIENTNATIONALID_PLACEHOLDER',false);
	define('TEXT_PATIENT_CANCEL_ADD','TEXT_PATIENT_CANCEL_ADD',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER',false);
	define('TEXT_PATIENT_EDIT_PAGE_TITLE','TEXT_PATIENT_EDIT_PAGE_TITLE',false);
	define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','TEXT_PATIENT_EDIT_SUBMIT_BUTTON',false);
	define('TEXT_PATIENT_ID_LABEL','TEXT_PATIENT_ID_LABEL',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','TEXT_PATIENT_KNOWN_ALLERGIES_LABEL',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER',false);
	define('TEXT_PATIENT_NAME_LABEL','TEXT_PATIENT_NAME_LABEL',false);
	define('TEXT_PATIENT_NEW_ADDRESS_LABEL','TEXT_PATIENT_NEW_ADDRESS_LABEL',false);
	define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','TEXT_PATIENT_NEW_BLOODTYPE_LABEL',false);
	define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','TEXT_PATIENT_NEW_CITY_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_CONTACT_LABEL','TEXT_PATIENT_NEW_CONTACT_LABEL',false);
	define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL',false);
	define('TEXT_PATIENT_NEW_PAGE_TITLE','TEXT_PATIENT_NEW_PAGE_TITLE',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','TEXT_PATIENT_NEW_STATE_PLACEHOLDER',false);
	define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','TEXT_PATIENT_NEW_SUBMIT_BUTTON',false);
	define('TEXT_PATIENT_PERSONAL_LABEL','TEXT_PATIENT_PERSONAL_LABEL',false);
	define('TEXT_SEX_INPUT_LABEL','TEXT_SEX_INPUT_LABEL',false);
	define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false);
	define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false);
	define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false);
	define('TEXT_YES_OPTION','TEXT_YES_OPTION',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_FORMAT','M-D-Y',false);
	define('TEXT_BIRTHDATE_FORMAT_LABEL','(M-D-Y)',false);
	define('TEXT_BIRTHDATE_INPUT_LABEL','Birthdate',false);
	define('TEXT_BIRTHDAY_DAY_FORMAT','d',false);
	define('TEXT_BIRTHDAY_MONTH_FORMAT','m',false);
	define('TEXT_BIRTHDAY_YEAR_FORMAT','Y',false);
	define('TEXT_BLANK_OPTION_SELECT','Choose',false);
	define('TEXT_EDIT_PATIENT_HEADING','Update patient info',false);
	define('TEXT_FAMILYID_LABEL','Family ID',false);
	define('TEXT_NEW_PATIENT_HEADING','Add new patient',false);
	define('TEXT_NO_OPTION','No',false);
	define('TEXT_PATIENTNATIONALID_LABEL','National ID',false);
	define('TEXT_PATIENTNATIONALID_PLACEHOLDER','Patient\'s National ID number',false);
	define('TEXT_PATIENT_CANCEL_ADD','Cancel',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Current medications',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','Enter each medication, one medication and dose per line',false);
	define('TEXT_PATIENT_EDIT_PAGE_TITLE','Update Patient',false);
	define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','Update patient info',false);
	define('TEXT_PATIENT_ID_LABEL','Patient, Visit, or Family ID',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','Known allergies',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','Enter each known allergy, one allergy per line',false);
	define('TEXT_PATIENT_NAME_LABEL','Patient name',false);
	define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Home address',false);
	define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','Blood type',false);
	define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','City',false);
	define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','Alternate phone',false);
	define('TEXT_PATIENT_NEW_CONTACT_LABEL','Contact info',false);
	define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','Primary phone',false);
	define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','County',false);
	define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','Family ID',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','Home address',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','Optional address info',false);
	define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','First name',false);
	define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','Second last name',false);
	define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','Last name',false);
	define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','MI',false);
	define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','Neighborhood',false);
	define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','Organ donor?',false);
	define('TEXT_PATIENT_NEW_PAGE_TITLE','New Patient',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','Preferred language',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','Preferred language',false);
	define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','State',false);
	define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','Add new patient',false);
	define('TEXT_PATIENT_PERSONAL_LABEL','Personal info',false);
	define('TEXT_SEX_INPUT_LABEL','Sex',false);
	define('TEXT_SEX_OPTION_F','F',false);
	define('TEXT_SEX_OPTION_M','M',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_YES_OPTION','Yes',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_FORMAT','D-M-Y',false);
	define('TEXT_BIRTHDATE_FORMAT_LABEL','(D-M-A)',false);
	define('TEXT_BIRTHDATE_INPUT_LABEL','Fecha de nacimiento',false);
	define('TEXT_BIRTHDAY_DAY_FORMAT','d',false);
	define('TEXT_BIRTHDAY_MONTH_FORMAT','m',false);
	define('TEXT_BIRTHDAY_YEAR_FORMAT','Y',false);
	define('TEXT_BLANK_OPTION_SELECT','Escoge',false);
	define('TEXT_EDIT_PATIENT_HEADING','Actualizar la información del paciente',false);
	define('TEXT_FAMILYID_LABEL','Carpeta (familia)',false);
	define('TEXT_NEW_PATIENT_HEADING','Agregar paciente nuevo',false);
	define('TEXT_NO_OPTION','No',false);
	define('TEXT_PATIENTNATIONALID_LABEL','Cedula',false);
	define('TEXT_PATIENTNATIONALID_PLACEHOLDER','Numero de la cedula del paciente',false);
	define('TEXT_PATIENT_CANCEL_ADD','Cancelar',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_LABEL','Medicamentos actuales',false);
	define('TEXT_PATIENT_CURRENT_MEDICATIONS_PLACEHOLDER','Ingrese cada medicamento actual, un medicamento y dosis por línea',false);
	define('TEXT_PATIENT_EDIT_PAGE_TITLE','Editar Paciente',false);
	define('TEXT_PATIENT_EDIT_SUBMIT_BUTTON','Actualizar información del paciente',false);
	define('TEXT_PATIENT_ID_LABEL','Número del paciente, visita, o familia',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_LABEL','Alergias conocidas',false);
	define('TEXT_PATIENT_KNOWN_ALLERGIES_PLACEHOLDER','Ingrese cada alergia conocida, una alergia por línea',false);
	define('TEXT_PATIENT_NAME_LABEL','Nombre del paciente',false);
	define('TEXT_PATIENT_NEW_ADDRESS_LABEL','Dirección de la casa',false);
	define('TEXT_PATIENT_NEW_BLOODTYPE_LABEL','Tipo de Sangre',false);
	define('TEXT_PATIENT_NEW_CITY_PLACEHOLDER','Municipio',false);
	define('TEXT_PATIENT_NEW_CONTACT_ALT_PHONE_PLACEHOLDER','Otro número telefónico',false);
	define('TEXT_PATIENT_NEW_CONTACT_LABEL','Información del contacto',false);
	define('TEXT_PATIENT_NEW_CONTACT_PHONE_PLACEHOLDER','Primer número telefónico',false);
	define('TEXT_PATIENT_NEW_COUNTY_PLACEHOLDER','Condado',false);
	define('TEXT_PATIENT_NEW_FAMILYID_PLACEHOLDER','ID de la carpeta familiar',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS1_PLACEHOLDER','Dirección de la casa',false);
	define('TEXT_PATIENT_NEW_HOMEADDRESS2_PLACEHOLDER','Dirección adicional',false);
	define('TEXT_PATIENT_NEW_NAMEFIRST_PLACEHOLDER','Primer nombre',false);
	define('TEXT_PATIENT_NEW_NAMELAST2_PLACEHOLDER','Segundo apellido',false);
	define('TEXT_PATIENT_NEW_NAMELAST_PLACEHOLDER','Apellido',false);
	define('TEXT_PATIENT_NEW_NAMEMI_PLACEHOLDER','Inicial',false);
	define('TEXT_PATIENT_NEW_NEIGHBORHOOD_PLACEHOLDER','Localidad',false);
	define('TEXT_PATIENT_NEW_ORGAN_DONOR_LABEL','¿Donante de órganos?',false);
	define('TEXT_PATIENT_NEW_PAGE_TITLE','Nuevo Paciente',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_LABEL','Idioma preferido',false);
	define('TEXT_PATIENT_NEW_PREFERREDLANGUAGE_PLACEHOLDER','Idioma preferido',false);
	define('TEXT_PATIENT_NEW_STATE_PLACEHOLDER','Departamento',false);
	define('TEXT_PATIENT_NEW_SUBMIT_BUTTON','Agregar paciente nuevo',false);
	define('TEXT_PATIENT_PERSONAL_LABEL','Información personal',false);
	define('TEXT_SEX_INPUT_LABEL','Sexo',false);
	define('TEXT_SEX_OPTION_F','M',false);
	define('TEXT_SEX_OPTION_M','H',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_YES_OPTION','Si',false);
}
//EOF
