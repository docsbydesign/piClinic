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
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','TEXT_ACCESS_GRANTED_OPTION_ADMIN',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','TEXT_ACCESS_GRANTED_OPTION_CLINIC',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','TEXT_ACCESS_GRANTED_OPTION_RO',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','TEXT_ACCESS_GRANTED_OPTION_STAFF',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','TEXT_BLANK_OPTION_SELECT',false); }
	if (!defined('TEXT_CANCEL_EDIT')) { define('TEXT_CANCEL_EDIT','TEXT_CANCEL_EDIT',false); }
	if (!defined('TEXT_EDIT_STAFF_HEADING')) { define('TEXT_EDIT_STAFF_HEADING','TEXT_EDIT_STAFF_HEADING',false); }
	if (!defined('TEXT_NEW_STAFF_HEADING')) { define('TEXT_NEW_STAFF_HEADING','TEXT_NEW_STAFF_HEADING',false); }
	if (!defined('TEXT_PAGE_EDIT_STAFF_TITLE')) { define('TEXT_PAGE_EDIT_STAFF_TITLE','TEXT_PAGE_EDIT_STAFF_TITLE',false); }
	if (!defined('TEXT_PAGE_NEW_STAFF_TITLE')) { define('TEXT_PAGE_NEW_STAFF_TITLE','TEXT_PAGE_NEW_STAFF_TITLE',false); }
	if (!defined('TEXT_STAFF_ACCESS_LABEL')) { define('TEXT_STAFF_ACCESS_LABEL','TEXT_STAFF_ACCESS_LABEL',false); }
	if (!defined('TEXT_STAFF_ACCOUNT_INFO_LABEL')) { define('TEXT_STAFF_ACCOUNT_INFO_LABEL','TEXT_STAFF_ACCOUNT_INFO_LABEL',false); }
	if (!defined('TEXT_STAFF_ACTIVE_LABEL')) { define('TEXT_STAFF_ACTIVE_LABEL','TEXT_STAFF_ACTIVE_LABEL',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_ACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','TEXT_STAFF_ACTIVE_OPTION_ACTIVE',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_INACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','TEXT_STAFF_ACTIVE_OPTION_INACTIVE',false); }
	if (!defined('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_CLINIC_INFO_LABEL')) { define('TEXT_STAFF_CLINIC_INFO_LABEL','TEXT_STAFF_CLINIC_INFO_LABEL',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_LABEL')) { define('TEXT_STAFF_CONTACTINFO_LABEL','TEXT_STAFF_CONTACTINFO_LABEL',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','TEXT_STAFF_CONTACTINFO_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_EDIT_SUBMIT_BUTTON')) { define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','TEXT_STAFF_EDIT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','TEXT_STAFF_LANGUAGE_OPTION_ENGLISH',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_SPANISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','TEXT_STAFF_LANGUAGE_OPTION_SPANISH',false); }
	if (!defined('TEXT_STAFF_MEMBERID_LABEL')) { define('TEXT_STAFF_MEMBERID_LABEL','TEXT_STAFF_MEMBERID_LABEL',false); }
	if (!defined('TEXT_STAFF_MEMBERID_PLACEHOLDER')) { define('TEXT_STAFF_MEMBERID_PLACEHOLDER','TEXT_STAFF_MEMBERID_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_NAMEFIRST_PLACEHOLDER')) { define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','TEXT_STAFF_NAMEFIRST_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_NAMELAST_PLACEHOLDER')) { define('TEXT_STAFF_NAMELAST_PLACEHOLDER','TEXT_STAFF_NAMELAST_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','TEXT_STAFF_NAME_LABEL',false); }
	if (!defined('TEXT_STAFF_NEW_SUBMIT_BUTTON')) { define('TEXT_STAFF_NEW_SUBMIT_BUTTON','TEXT_STAFF_NEW_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_STAFF_PASSWORD_PLACEHOLDER')) { define('TEXT_STAFF_PASSWORD_PLACEHOLDER','TEXT_STAFF_PASSWORD_PLACEHOLDER',false); }
	if (!defined('TEXT_STAFF_POSITION_LABEL')) { define('TEXT_STAFF_POSITION_LABEL','TEXT_STAFF_POSITION_LABEL',false); }
	if (!defined('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL')) { define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','TEXT_STAFF_PREFERRED_LANGUAGE_LABEL',false); }
	if (!defined('TEXT_STAFF_USERNAME_LABEL')) { define('TEXT_STAFF_USERNAME_LABEL','TEXT_STAFF_USERNAME_LABEL',false); }
	if (!defined('TEXT_STAFF_USERNAME_PLACEHOLDER')) { define('TEXT_STAFF_USERNAME_PLACEHOLDER','TEXT_STAFF_USERNAME_PLACEHOLDER',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','System admin',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Clinic admin',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','Authorized user',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Clinic staff',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Choose',false); }
	if (!defined('TEXT_CANCEL_EDIT')) { define('TEXT_CANCEL_EDIT','Cancel',false); }
	if (!defined('TEXT_EDIT_STAFF_HEADING')) { define('TEXT_EDIT_STAFF_HEADING','Update staff information',false); }
	if (!defined('TEXT_NEW_STAFF_HEADING')) { define('TEXT_NEW_STAFF_HEADING','Add new staff member to piClinic',false); }
	if (!defined('TEXT_PAGE_EDIT_STAFF_TITLE')) { define('TEXT_PAGE_EDIT_STAFF_TITLE','Update staff information',false); }
	if (!defined('TEXT_PAGE_NEW_STAFF_TITLE')) { define('TEXT_PAGE_NEW_STAFF_TITLE','Add new staff member to piClinic',false); }
	if (!defined('TEXT_STAFF_ACCESS_LABEL')) { define('TEXT_STAFF_ACCESS_LABEL','piClinic system permission',false); }
	if (!defined('TEXT_STAFF_ACCOUNT_INFO_LABEL')) { define('TEXT_STAFF_ACCOUNT_INFO_LABEL','User information',false); }
	if (!defined('TEXT_STAFF_ACTIVE_LABEL')) { define('TEXT_STAFF_ACTIVE_LABEL','Account status',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_ACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','Active',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_INACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','Inactive',false); }
	if (!defined('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','Alternate email address or telephone number',false); }
	if (!defined('TEXT_STAFF_CLINIC_INFO_LABEL')) { define('TEXT_STAFF_CLINIC_INFO_LABEL','Clinic information',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_LABEL')) { define('TEXT_STAFF_CONTACTINFO_LABEL','Contact info',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','Email address or telephone number',false); }
	if (!defined('TEXT_STAFF_EDIT_SUBMIT_BUTTON')) { define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','Update information',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','English',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_SPANISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','Spanish',false); }
	if (!defined('TEXT_STAFF_MEMBERID_LABEL')) { define('TEXT_STAFF_MEMBERID_LABEL','Clinic ID',false); }
	if (!defined('TEXT_STAFF_MEMBERID_PLACEHOLDER')) { define('TEXT_STAFF_MEMBERID_PLACEHOLDER','ID provided by clinic',false); }
	if (!defined('TEXT_STAFF_NAMEFIRST_PLACEHOLDER')) { define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','First name',false); }
	if (!defined('TEXT_STAFF_NAMELAST_PLACEHOLDER')) { define('TEXT_STAFF_NAMELAST_PLACEHOLDER','Last name',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','Professional',false); }
	if (!defined('TEXT_STAFF_NEW_SUBMIT_BUTTON')) { define('TEXT_STAFF_NEW_SUBMIT_BUTTON','Add new staff member or user',false); }
	if (!defined('TEXT_STAFF_PASSWORD_PLACEHOLDER')) { define('TEXT_STAFF_PASSWORD_PLACEHOLDER','Your new password',false); }
	if (!defined('TEXT_STAFF_POSITION_LABEL')) { define('TEXT_STAFF_POSITION_LABEL','Position in the clinic',false); }
	if (!defined('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL')) { define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','Display language',false); }
	if (!defined('TEXT_STAFF_USERNAME_LABEL')) { define('TEXT_STAFF_USERNAME_LABEL','Username/Password',false); }
	if (!defined('TEXT_STAFF_USERNAME_PLACEHOLDER')) { define('TEXT_STAFF_USERNAME_PLACEHOLDER','A unique username',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','Administrador del sistema',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Administrador de la clínica',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','Usuario autorizado',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Personal de la clínica',false); }
	if (!defined('TEXT_BLANK_OPTION_SELECT')) { define('TEXT_BLANK_OPTION_SELECT','Seleccione',false); }
	if (!defined('TEXT_CANCEL_EDIT')) { define('TEXT_CANCEL_EDIT','Cancelar',false); }
	if (!defined('TEXT_EDIT_STAFF_HEADING')) { define('TEXT_EDIT_STAFF_HEADING','Actualizar los datos personales',false); }
	if (!defined('TEXT_NEW_STAFF_HEADING')) { define('TEXT_NEW_STAFF_HEADING','Agregar una persona nueva al sistema piClinic',false); }
	if (!defined('TEXT_PAGE_EDIT_STAFF_TITLE')) { define('TEXT_PAGE_EDIT_STAFF_TITLE','Actualizar los datos personales',false); }
	if (!defined('TEXT_PAGE_NEW_STAFF_TITLE')) { define('TEXT_PAGE_NEW_STAFF_TITLE','Agregar una persona nueva al sistema piClinic',false); }
	if (!defined('TEXT_STAFF_ACCESS_LABEL')) { define('TEXT_STAFF_ACCESS_LABEL','Permiso para ingresar a los datos del sistema piClinic',false); }
	if (!defined('TEXT_STAFF_ACCOUNT_INFO_LABEL')) { define('TEXT_STAFF_ACCOUNT_INFO_LABEL','Información del usuario',false); }
	if (!defined('TEXT_STAFF_ACTIVE_LABEL')) { define('TEXT_STAFF_ACTIVE_LABEL','Estado de la cuenta',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_ACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','Activo',false); }
	if (!defined('TEXT_STAFF_ACTIVE_OPTION_INACTIVE')) { define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','Inactivo',false); }
	if (!defined('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','Alt. dirección de email o número telefónico',false); }
	if (!defined('TEXT_STAFF_CLINIC_INFO_LABEL')) { define('TEXT_STAFF_CLINIC_INFO_LABEL','Información de la clínica',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_LABEL')) { define('TEXT_STAFF_CONTACTINFO_LABEL','Como contactar esa persona',false); }
	if (!defined('TEXT_STAFF_CONTACTINFO_PLACEHOLDER')) { define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','Dirección de email o número telefónico',false); }
	if (!defined('TEXT_STAFF_EDIT_SUBMIT_BUTTON')) { define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','Actualizar información',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','Inglés',false); }
	if (!defined('TEXT_STAFF_LANGUAGE_OPTION_SPANISH')) { define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','Español',false); }
	if (!defined('TEXT_STAFF_MEMBERID_LABEL')) { define('TEXT_STAFF_MEMBERID_LABEL','ID de la clínica',false); }
	if (!defined('TEXT_STAFF_MEMBERID_PLACEHOLDER')) { define('TEXT_STAFF_MEMBERID_PLACEHOLDER','ID de la clínica',false); }
	if (!defined('TEXT_STAFF_NAMEFIRST_PLACEHOLDER')) { define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','Nombre',false); }
	if (!defined('TEXT_STAFF_NAMELAST_PLACEHOLDER')) { define('TEXT_STAFF_NAMELAST_PLACEHOLDER','Apellido',false); }
	if (!defined('TEXT_STAFF_NAME_LABEL')) { define('TEXT_STAFF_NAME_LABEL','Profesional',false); }
	if (!defined('TEXT_STAFF_NEW_SUBMIT_BUTTON')) { define('TEXT_STAFF_NEW_SUBMIT_BUTTON','Agrega una persona o usuario nuevo',false); }
	if (!defined('TEXT_STAFF_PASSWORD_PLACEHOLDER')) { define('TEXT_STAFF_PASSWORD_PLACEHOLDER','Su contraseña nueva',false); }
	if (!defined('TEXT_STAFF_POSITION_LABEL')) { define('TEXT_STAFF_POSITION_LABEL','Puesto en la clínica',false); }
	if (!defined('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL')) { define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','Idioma de la pantalla',false); }
	if (!defined('TEXT_STAFF_USERNAME_LABEL')) { define('TEXT_STAFF_USERNAME_LABEL','Usuario/Contraseña',false); }
	if (!defined('TEXT_STAFF_USERNAME_PLACEHOLDER')) { define('TEXT_STAFF_USERNAME_PLACEHOLDER','Un usuario único',false); }
}
//EOF
