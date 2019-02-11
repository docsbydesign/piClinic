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
	define('TEXT_CANCEL_SEARCH','TEXT_CANCEL_SEARCH',false);
	define('TEXT_FULLNAME_LABEL','TEXT_FULLNAME_LABEL',false);
	define('TEXT_HOMECITY_LABEL','TEXT_HOMECITY_LABEL',false);
	define('TEXT_PATIENTS_FOUND_PAGE_TITLE','TEXT_PATIENTS_FOUND_PAGE_TITLE',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false);
	define('TEXT_PATIENT_SEARCH_FOUND_ATA','TEXT_PATIENT_SEARCH_FOUND_ATA',false);
	define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','TEXT_PATIENT_SEARCH_FOUND_NOT_ATA',false);
	define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','TEXT_PATIENT_SEARCH_NOT_FOUND_ATA',false);
	define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','TEXT_PATIENT_SEARCH_RESULTS_HEADING',false);
	define('TEXT_RETURN_TO_SEARCH_LINK','TEXT_RETURN_TO_SEARCH_LINK',false);
	define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false);
	define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false);
	define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_LABEL','Birthdate',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false);
	define('TEXT_CANCEL_SEARCH','Cancel',false);
	define('TEXT_FULLNAME_LABEL','Patient name',false);
	define('TEXT_HOMECITY_LABEL','City',false);
	define('TEXT_PATIENTS_FOUND_PAGE_TITLE','Matching patients found',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Add a new patient',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Patient\'s name or ID',false);
	define('TEXT_PATIENT_SEARCH_FOUND_ATA','Found these patients. Select one to enter their visit info or add a new patient above.',false);
	define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','Found these patients. Select one to view thier info.',false);
	define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','No matching patients found. Go back or add a new patient above.',false);
	define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','Matching patients found',false);
	define('TEXT_RETURN_TO_SEARCH_LINK','Search again',false);
	define('TEXT_SEX_OPTION_F','F',false);
	define('TEXT_SEX_OPTION_M','M',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_BIRTHDATE_LABEL','Fecha de nacimiento',false);
	define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false);
	define('TEXT_CANCEL_SEARCH','Cancelar',false);
	define('TEXT_FULLNAME_LABEL','Nombre del paciente',false);
	define('TEXT_HOMECITY_LABEL','Municipio',false);
	define('TEXT_PATIENTS_FOUND_PAGE_TITLE','Pacientes encontrados',false);
	define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Agregar un paciente nuevo',false);
	define('TEXT_PATIENT_ID_PLACEHOLDER','Identidad o nombre del paciente',false);
	define('TEXT_PATIENT_SEARCH_FOUND_ATA','Encontró estos pacientes. Haz clik para agregar su visita o agregue un paciente nuevo.',false);
	define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','Encontró estos pacientes. Haz clik para ver sus detalles.',false);
	define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','No hay patientes con este ID. Volver o agregar un paciente nuevo.',false);
	define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','Pacientes encontrados',false);
	define('TEXT_RETURN_TO_SEARCH_LINK','Buscar de nuevo',false);
	define('TEXT_SEX_OPTION_F','M',false);
	define('TEXT_SEX_OPTION_M','H',false);
	define('TEXT_SEX_OPTION_X','X',false);
	define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false);
}
//EOF
