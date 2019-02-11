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
	define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','TEXT_ACCESS_GRANTED_OPTION_ADMIN',false);
	define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','TEXT_ACCESS_GRANTED_OPTION_CLINIC',false);
	define('TEXT_ACCESS_GRANTED_OPTION_RO','TEXT_ACCESS_GRANTED_OPTION_RO',false);
	define('TEXT_ACCESS_GRANTED_OPTION_STAFF','TEXT_ACCESS_GRANTED_OPTION_STAFF',false);
	define('TEXT_BLANK_OPTION_SELECT','TEXT_BLANK_OPTION_SELECT',false);
	define('TEXT_CANCEL_EDIT','TEXT_CANCEL_EDIT',false);
	define('TEXT_EDIT_STAFF_HEADING','TEXT_EDIT_STAFF_HEADING',false);
	define('TEXT_NEW_STAFF_HEADING','TEXT_NEW_STAFF_HEADING',false);
	define('TEXT_PAGE_EDIT_STAFF_TITLE','TEXT_PAGE_EDIT_STAFF_TITLE',false);
	define('TEXT_PAGE_NEW_STAFF_TITLE','TEXT_PAGE_NEW_STAFF_TITLE',false);
	define('TEXT_STAFF_ACCESS_LABEL','TEXT_STAFF_ACCESS_LABEL',false);
	define('TEXT_STAFF_ACCOUNT_INFO_LABEL','TEXT_STAFF_ACCOUNT_INFO_LABEL',false);
	define('TEXT_STAFF_ACTIVE_LABEL','TEXT_STAFF_ACTIVE_LABEL',false);
	define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','TEXT_STAFF_ACTIVE_OPTION_ACTIVE',false);
	define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','TEXT_STAFF_ACTIVE_OPTION_INACTIVE',false);
	define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER',false);
	define('TEXT_STAFF_CLINIC_INFO_LABEL','TEXT_STAFF_CLINIC_INFO_LABEL',false);
	define('TEXT_STAFF_CONTACTINFO_LABEL','TEXT_STAFF_CONTACTINFO_LABEL',false);
	define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','TEXT_STAFF_CONTACTINFO_PLACEHOLDER',false);
	define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','TEXT_STAFF_EDIT_SUBMIT_BUTTON',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','TEXT_STAFF_LANGUAGE_OPTION_ENGLISH',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','TEXT_STAFF_LANGUAGE_OPTION_SPANISH',false);
	define('TEXT_STAFF_MEMBERID_LABEL','TEXT_STAFF_MEMBERID_LABEL',false);
	define('TEXT_STAFF_MEMBERID_PLACEHOLDER','TEXT_STAFF_MEMBERID_PLACEHOLDER',false);
	define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','TEXT_STAFF_NAMEFIRST_PLACEHOLDER',false);
	define('TEXT_STAFF_NAMELAST_PLACEHOLDER','TEXT_STAFF_NAMELAST_PLACEHOLDER',false);
	define('TEXT_STAFF_NAME_LABEL','TEXT_STAFF_NAME_LABEL',false);
	define('TEXT_STAFF_NEW_SUBMIT_BUTTON','TEXT_STAFF_NEW_SUBMIT_BUTTON',false);
	define('TEXT_STAFF_PASSWORD_PLACEHOLDER','TEXT_STAFF_PASSWORD_PLACEHOLDER',false);
	define('TEXT_STAFF_POSITION_LABEL','TEXT_STAFF_POSITION_LABEL',false);
	define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','TEXT_STAFF_PREFERRED_LANGUAGE_LABEL',false);
	define('TEXT_STAFF_RESET_NEW_BUTTON','TEXT_STAFF_RESET_NEW_BUTTON',false);
	define('TEXT_STAFF_USERNAME_LABEL','TEXT_STAFF_USERNAME_LABEL',false);
	define('TEXT_STAFF_USERNAME_PLACEHOLDER','TEXT_STAFF_USERNAME_PLACEHOLDER',false);
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','System admin',false);
	define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Clinic admin',false);
	define('TEXT_ACCESS_GRANTED_OPTION_RO','Authorized user',false);
	define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Clinic staff',false);
	define('TEXT_BLANK_OPTION_SELECT','Choose',false);
	define('TEXT_CANCEL_EDIT','Cancel',false);
	define('TEXT_EDIT_STAFF_HEADING','Update staff information',false);
	define('TEXT_NEW_STAFF_HEADING','Add new staff member to piClinic',false);
	define('TEXT_PAGE_EDIT_STAFF_TITLE','Update staff information',false);
	define('TEXT_PAGE_NEW_STAFF_TITLE','Add new staff member to piClinic',false);
	define('TEXT_STAFF_ACCESS_LABEL','piClinic system permission',false);
	define('TEXT_STAFF_ACCOUNT_INFO_LABEL','User information',false);
	define('TEXT_STAFF_ACTIVE_LABEL','Account status',false);
	define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','Active',false);
	define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','Inactive',false);
	define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','Alternate email address or telephone number',false);
	define('TEXT_STAFF_CLINIC_INFO_LABEL','Clinic information',false);
	define('TEXT_STAFF_CONTACTINFO_LABEL','Contact info',false);
	define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','Email address or telephone number',false);
	define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','Update information',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','English',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','Spanish',false);
	define('TEXT_STAFF_MEMBERID_LABEL','Clinic ID',false);
	define('TEXT_STAFF_MEMBERID_PLACEHOLDER','ID provided by clinic',false);
	define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','First name',false);
	define('TEXT_STAFF_NAMELAST_PLACEHOLDER','Last name',false);
	define('TEXT_STAFF_NAME_LABEL','Name',false);
	define('TEXT_STAFF_NEW_SUBMIT_BUTTON','Add new staff member or user',false);
	define('TEXT_STAFF_PASSWORD_PLACEHOLDER','Your new password',false);
	define('TEXT_STAFF_POSITION_LABEL','Position in the clinic',false);
	define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','Display language',false);
	define('TEXT_STAFF_RESET_NEW_BUTTON','Clear fields',false);
	define('TEXT_STAFF_USERNAME_LABEL','Username/Password',false);
	define('TEXT_STAFF_USERNAME_PLACEHOLDER','A unique username',false);
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','Administrador del sistema',false);
	define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Administrador de la clínica',false);
	define('TEXT_ACCESS_GRANTED_OPTION_RO','Usuario atorizado',false);
	define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Personal de la clínica',false);
	define('TEXT_BLANK_OPTION_SELECT','Escoge',false);
	define('TEXT_CANCEL_EDIT','Cancelar',false);
	define('TEXT_EDIT_STAFF_HEADING','Actualizar los datos personal',false);
	define('TEXT_NEW_STAFF_HEADING','Agrega una persona nueva al sistema piClinic',false);
	define('TEXT_PAGE_EDIT_STAFF_TITLE','Actualizar los datos personal',false);
	define('TEXT_PAGE_NEW_STAFF_TITLE','Agrega una persona nueva al sistema piClinic',false);
	define('TEXT_STAFF_ACCESS_LABEL','Permisso para acessar los datos del sistema piClinic',false);
	define('TEXT_STAFF_ACCOUNT_INFO_LABEL','Información del usuario',false);
	define('TEXT_STAFF_ACTIVE_LABEL','Estado de la cuenta',false);
	define('TEXT_STAFF_ACTIVE_OPTION_ACTIVE','Activo',false);
	define('TEXT_STAFF_ACTIVE_OPTION_INACTIVE','Inactivo',false);
	define('TEXT_STAFF_ALTCONTACTINFO_PLACEHOLDER','Alt. dirección de email o número telefónico',false);
	define('TEXT_STAFF_CLINIC_INFO_LABEL','Información de la clínica',false);
	define('TEXT_STAFF_CONTACTINFO_LABEL','Como contactar esa persona',false);
	define('TEXT_STAFF_CONTACTINFO_PLACEHOLDER','Dirección de email o número telefónico',false);
	define('TEXT_STAFF_EDIT_SUBMIT_BUTTON','Actualizar información',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_ENGLISH','Inglés',false);
	define('TEXT_STAFF_LANGUAGE_OPTION_SPANISH','Español',false);
	define('TEXT_STAFF_MEMBERID_LABEL','ID de la clínica',false);
	define('TEXT_STAFF_MEMBERID_PLACEHOLDER','ID de la clínica',false);
	define('TEXT_STAFF_NAMEFIRST_PLACEHOLDER','Nombre',false);
	define('TEXT_STAFF_NAMELAST_PLACEHOLDER','Apellido',false);
	define('TEXT_STAFF_NAME_LABEL','Nombre',false);
	define('TEXT_STAFF_NEW_SUBMIT_BUTTON','Agrega una persona o usuario nuevo',false);
	define('TEXT_STAFF_PASSWORD_PLACEHOLDER','Su contraseña nueva',false);
	define('TEXT_STAFF_POSITION_LABEL','Puesto en la clínica',false);
	define('TEXT_STAFF_PREFERRED_LANGUAGE_LABEL','Idioma de la pantalla',false);
	define('TEXT_STAFF_RESET_NEW_BUTTON','Borrar todo',false);
	define('TEXT_STAFF_USERNAME_LABEL','Usuario/Contraseña',false);
	define('TEXT_STAFF_USERNAME_PLACEHOLDER','Un usuario unico',false);
}
//EOF
