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
	if (!defined('TEXT_CLINIC_DASH_PAGE_TITLE')) { define('TEXT_CLINIC_DASH_PAGE_TITLE','TEXT_CLINIC_DASH_PAGE_TITLE',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','TEXT_DISCHARGE_VISIT_INFO',false); }
	if (!defined('TEXT_EARLIER_VISIT_NOTE')) { define('TEXT_EARLIER_VISIT_NOTE','TEXT_EARLIER_VISIT_NOTE',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','TEXT_EDIT_VISIT_INFO',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','TEXT_MORE_VISIT_INFO',false); }
	if (!defined('TEXT_NO_OPEN_VISITS')) { define('TEXT_NO_OPEN_VISITS','TEXT_NO_OPEN_VISITS',false); }
	if (!defined('TEXT_OPEN_VISIT_LIST_HEAD')) { define('TEXT_OPEN_VISIT_LIST_HEAD','TEXT_OPEN_VISIT_LIST_HEAD',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','TEXT_PATIENT_ID_PLACEHOLDER',false); }
	if (!defined('TEXT_SEARCH_PATIENT_ID_LABEL')) { define('TEXT_SEARCH_PATIENT_ID_LABEL','TEXT_SEARCH_PATIENT_ID_LABEL',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','TEXT_SEX_OPTION_F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','TEXT_SEX_OPTION_M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','TEXT_SEX_OPTION_X',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','TEXT_SHOW_PATIENT_INFO',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','TEXT_SHOW_PATIENT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','TEXT_SHOW_VISIT_INFO',false); }
	if (!defined('TEXT_SORT_THIS_COLUMN')) { define('TEXT_SORT_THIS_COLUMN','TEXT_SORT_THIS_COLUMN',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','TEXT_VISIT_DATE_FORMAT',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','TEXT_VISIT_LIST_ACTIONS',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','TEXT_VISIT_LIST_ACTION_DISCHARGE',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','TEXT_VISIT_LIST_ACTION_EDIT',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','TEXT_VISIT_LIST_ACTION_MORE',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','TEXT_VISIT_LIST_ACTION_VIEW',false); }
	if (!defined('TEXT_VISIT_LIST_ARRIVED_DATE')) { define('TEXT_VISIT_LIST_ARRIVED_DATE','TEXT_VISIT_LIST_ARRIVED_DATE',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','TEXT_VISIT_LIST_HEAD_COMPLAINT',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','TEXT_VISIT_LIST_HEAD_DOCTOR',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NAME')) { define('TEXT_VISIT_LIST_HEAD_NAME','TEXT_VISIT_LIST_HEAD_NAME',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','TEXT_VISIT_LIST_MISSING',false); }
	if (!defined('TEXT_VISIT_TIME_FORMAT')) { define('TEXT_VISIT_TIME_FORMAT','TEXT_VISIT_TIME_FORMAT',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_CLINIC_DASH_PAGE_TITLE')) { define('TEXT_CLINIC_DASH_PAGE_TITLE','Clinic information',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','Discharge this patient',false); }
	if (!defined('TEXT_EARLIER_VISIT_NOTE')) { define('TEXT_EARLIER_VISIT_NOTE','Visits from an earlier date',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','Edit this visit',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','See all of the information about the visit description',false); }
	if (!defined('TEXT_NO_OPEN_VISITS')) { define('TEXT_NO_OPEN_VISITS','No patients admitted to the clinic.',false); }
	if (!defined('TEXT_OPEN_VISIT_LIST_HEAD')) { define('TEXT_OPEN_VISIT_LIST_HEAD','Patients admitted to the clinic',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','The name or ID to find',false); }
	if (!defined('TEXT_SEARCH_PATIENT_ID_LABEL')) { define('TEXT_SEARCH_PATIENT_ID_LABEL','Patient, Visit, or Family ID',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','F',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','M',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Show patient details',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Search',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','Show visit details',false); }
	if (!defined('TEXT_SORT_THIS_COLUMN')) { define('TEXT_SORT_THIS_COLUMN','Sort by this column',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','Visit actions',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Discharge',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','Edit',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','more...',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','View',false); }
	if (!defined('TEXT_VISIT_LIST_ARRIVED_DATE')) { define('TEXT_VISIT_LIST_ARRIVED_DATE','Arrived',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Reason for visit',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','Doctor',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NAME')) { define('TEXT_VISIT_LIST_HEAD_NAME','Name',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','(missing)',false); }
	if (!defined('TEXT_VISIT_TIME_FORMAT')) { define('TEXT_VISIT_TIME_FORMAT','h:i',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_CLINIC_DASH_PAGE_TITLE')) { define('TEXT_CLINIC_DASH_PAGE_TITLE','Información de la clínica',false); }
	if (!defined('TEXT_DISCHARGE_VISIT_INFO')) { define('TEXT_DISCHARGE_VISIT_INFO','Dar de alta este paciente',false); }
	if (!defined('TEXT_EARLIER_VISIT_NOTE')) { define('TEXT_EARLIER_VISIT_NOTE','Visitas de una fecha anterior',false); }
	if (!defined('TEXT_EDIT_VISIT_INFO')) { define('TEXT_EDIT_VISIT_INFO','Actualizar esta visita',false); }
	if (!defined('TEXT_MORE_VISIT_INFO')) { define('TEXT_MORE_VISIT_INFO','Ver la descripción completa',false); }
	if (!defined('TEXT_NO_OPEN_VISITS')) { define('TEXT_NO_OPEN_VISITS','No hay pacientes admitidos a la clínica.',false); }
	if (!defined('TEXT_OPEN_VISIT_LIST_HEAD')) { define('TEXT_OPEN_VISIT_LIST_HEAD','Pacientes admitidos a la clínica',false); }
	if (!defined('TEXT_PATIENT_ID_PLACEHOLDER')) { define('TEXT_PATIENT_ID_PLACEHOLDER','El nombre o número para encontrar',false); }
	if (!defined('TEXT_SEARCH_PATIENT_ID_LABEL')) { define('TEXT_SEARCH_PATIENT_ID_LABEL','Número del paciente, visita, o familia',false); }
	if (!defined('TEXT_SEX_OPTION_F')) { define('TEXT_SEX_OPTION_F','M',false); }
	if (!defined('TEXT_SEX_OPTION_M')) { define('TEXT_SEX_OPTION_M','H',false); }
	if (!defined('TEXT_SEX_OPTION_X')) { define('TEXT_SEX_OPTION_X','X',false); }
	if (!defined('TEXT_SHOW_PATIENT_INFO')) { define('TEXT_SHOW_PATIENT_INFO','Mostrar los detalles del paciente',false); }
	if (!defined('TEXT_SHOW_PATIENT_SUBMIT_BUTTON')) { define('TEXT_SHOW_PATIENT_SUBMIT_BUTTON','Buscar',false); }
	if (!defined('TEXT_SHOW_VISIT_INFO')) { define('TEXT_SHOW_VISIT_INFO','Mostrar los detalles de la visita',false); }
	if (!defined('TEXT_SORT_THIS_COLUMN')) { define('TEXT_SORT_THIS_COLUMN','Ordenar por esta columna',false); }
	if (!defined('TEXT_VISIT_DATE_FORMAT')) { define('TEXT_VISIT_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_VISIT_LIST_ACTIONS')) { define('TEXT_VISIT_LIST_ACTIONS','Acciones de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_DISCHARGE')) { define('TEXT_VISIT_LIST_ACTION_DISCHARGE','Dar de alta',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_EDIT')) { define('TEXT_VISIT_LIST_ACTION_EDIT','Actualizar',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_MORE')) { define('TEXT_VISIT_LIST_ACTION_MORE','más...',false); }
	if (!defined('TEXT_VISIT_LIST_ACTION_VIEW')) { define('TEXT_VISIT_LIST_ACTION_VIEW','Ver',false); }
	if (!defined('TEXT_VISIT_LIST_ARRIVED_DATE')) { define('TEXT_VISIT_LIST_ARRIVED_DATE','Llegó',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_COMPLAINT')) { define('TEXT_VISIT_LIST_HEAD_COMPLAINT','Motivo de la visita',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_DOCTOR')) { define('TEXT_VISIT_LIST_HEAD_DOCTOR','Médico',false); }
	if (!defined('TEXT_VISIT_LIST_HEAD_NAME')) { define('TEXT_VISIT_LIST_HEAD_NAME','Nombre',false); }
	if (!defined('TEXT_VISIT_LIST_MISSING')) { define('TEXT_VISIT_LIST_MISSING','(no especificado)',false); }
	if (!defined('TEXT_VISIT_TIME_FORMAT')) { define('TEXT_VISIT_TIME_FORMAT','H:i',false); }
}
//EOF
