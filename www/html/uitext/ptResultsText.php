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
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','TEXT_BIRTHDATE_LABEL',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','TEXT_BIRTHDAY_DATE_FORMAT',false); }
	if (!defined('TEXT_CANCEL_SEARCH')) { define('TEXT_CANCEL_SEARCH','TEXT_CANCEL_SEARCH',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','TEXT_FULLNAME_LABEL',false); }
	if (!defined('TEXT_HOMECITY_LABEL')) { define('TEXT_HOMECITY_LABEL','TEXT_HOMECITY_LABEL',false); }
	if (!defined('TEXT_PATIENTS_FOUND_PAGE_TITLE')) { define('TEXT_PATIENTS_FOUND_PAGE_TITLE','TEXT_PATIENTS_FOUND_PAGE_TITLE',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_ATA','TEXT_PATIENT_SEARCH_FOUND_ATA',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','TEXT_PATIENT_SEARCH_FOUND_NOT_ATA',false); }
	if (!defined('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','TEXT_PATIENT_SEARCH_NOT_FOUND_ATA',false); }
	if (!defined('TEXT_PATIENT_SEARCH_RESULTS_HEADING')) { define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','TEXT_PATIENT_SEARCH_RESULTS_HEADING',false); }
	if (!defined('TEXT_RETURN_TO_SEARCH_LINK')) { define('TEXT_RETURN_TO_SEARCH_LINK','TEXT_RETURN_TO_SEARCH_LINK',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Birthdate',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','m/d/Y',false); }
	if (!defined('TEXT_CANCEL_SEARCH')) { define('TEXT_CANCEL_SEARCH','Cancel',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Patient name',false); }
	if (!defined('TEXT_HOMECITY_LABEL')) { define('TEXT_HOMECITY_LABEL','City',false); }
	if (!defined('TEXT_PATIENTS_FOUND_PAGE_TITLE')) { define('TEXT_PATIENTS_FOUND_PAGE_TITLE','Matching patients found',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Add a new patient',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','The name or ID to find',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_ATA','Found these patients. Select one to enter their visit info or add a new patient above.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','Found these patients. Select one to view their info.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','No matching patients found. Retry your search or add a new patient above.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_RESULTS_HEADING')) { define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','Matching patients found',false); }
	if (!defined('TEXT_RETURN_TO_SEARCH_LINK')) { define('TEXT_RETURN_TO_SEARCH_LINK','Search again',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_BIRTHDATE_LABEL')) { define('TEXT_BIRTHDATE_LABEL','Fecha de nacimiento',false); }
	if (!defined('TEXT_BIRTHDAY_DATE_FORMAT')) { define('TEXT_BIRTHDAY_DATE_FORMAT','d-m-Y',false); }
	if (!defined('TEXT_CANCEL_SEARCH')) { define('TEXT_CANCEL_SEARCH','Cancelar',false); }
	if (!defined('TEXT_FULLNAME_LABEL')) { define('TEXT_FULLNAME_LABEL','Nombre del paciente',false); }
	if (!defined('TEXT_HOMECITY_LABEL')) { define('TEXT_HOMECITY_LABEL','Municipio',false); }
	if (!defined('TEXT_PATIENTS_FOUND_PAGE_TITLE')) { define('TEXT_PATIENTS_FOUND_PAGE_TITLE','Pacientes encontrados',false); }
	if (!defined('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON')) { define('TEXT_PATIENT_ADD_NEW_PATIENT_BUTTON','Agregar un paciente nuevo',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','El nombre o número para encontrar',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_ATA','Se encontraron estos pacientes. Haga clik para agregar su visita o agregar un paciente nuevo.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA')) { define('TEXT_PATIENT_SEARCH_FOUND_NOT_ATA','Se encontraron estos pacientes. Haga clik para ver los detalles.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA')) { define('TEXT_PATIENT_SEARCH_NOT_FOUND_ATA','No hay pacientes con este ID o nombre. Buscar de nuevo o agregar un paciente nuevo.',false); }
	if (!defined('TEXT_PATIENT_SEARCH_RESULTS_HEADING')) { define('TEXT_PATIENT_SEARCH_RESULTS_HEADING','Pacientes encontrados',false); }
	if (!defined('TEXT_RETURN_TO_SEARCH_LINK')) { define('TEXT_RETURN_TO_SEARCH_LINK','Buscar de nuevo',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','M',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','H',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false); }
}
//EOF
