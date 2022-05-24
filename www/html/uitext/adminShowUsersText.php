<?php
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','TEXT_ACCESS_GRANTED_OPTION_ADMIN',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','TEXT_ACCESS_GRANTED_OPTION_CLINIC',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','TEXT_ACCESS_GRANTED_OPTION_RO',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','TEXT_ACCESS_GRANTED_OPTION_STAFF',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN')) { define('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN','TEXT_ACCESS_GRANTED_OPTION_UNKNOWN',false); }
	if (!defined('TEXT_ADD_NEW_USER')) { define('TEXT_ADD_NEW_USER','TEXT_ADD_NEW_USER',false); }
	if (!defined('TEXT_NO_STAFF_RECORDS')) { define('TEXT_NO_STAFF_RECORDS','TEXT_NO_STAFF_RECORDS',false); }
	if (!defined('TEXT_PICLINIC_USERS_PAGE_TITLE')) { define('TEXT_PICLINIC_USERS_PAGE_TITLE','TEXT_PICLINIC_USERS_PAGE_TITLE',false); }
	if (!defined('TEXT_STAFF_DATE_FORMAT')) { define('TEXT_STAFF_DATE_FORMAT','TEXT_STAFF_DATE_FORMAT',false); }
	if (!defined('TEXT_STAFF_EDIT_USERNAME_TITLE')) { define('TEXT_STAFF_EDIT_USERNAME_TITLE','TEXT_STAFF_EDIT_USERNAME_TITLE',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD')) { define('TEXT_STAFF_LIST_HEAD','TEXT_STAFF_LIST_HEAD',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED')) { define('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED','TEXT_STAFF_LIST_HEAD_ACCESSGRANTED',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CONTACTINFO')) { define('TEXT_STAFF_LIST_HEAD_CONTACTINFO','TEXT_STAFF_LIST_HEAD_CONTACTINFO',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CREATEDDATE')) { define('TEXT_STAFF_LIST_HEAD_CREATEDDATE','TEXT_STAFF_LIST_HEAD_CREATEDDATE',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_LASTLOGIN')) { define('TEXT_STAFF_LIST_HEAD_LASTLOGIN','TEXT_STAFF_LIST_HEAD_LASTLOGIN',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MEMBERID')) { define('TEXT_STAFF_LIST_HEAD_MEMBERID','TEXT_STAFF_LIST_HEAD_MEMBERID',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE')) { define('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE','TEXT_STAFF_LIST_HEAD_MODIFIEDDATE',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_NAME')) { define('TEXT_STAFF_LIST_HEAD_NAME','TEXT_STAFF_LIST_HEAD_NAME',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_POSITION')) { define('TEXT_STAFF_LIST_HEAD_POSITION','TEXT_STAFF_LIST_HEAD_POSITION',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_USERNAME')) { define('TEXT_STAFF_LIST_HEAD_USERNAME','TEXT_STAFF_LIST_HEAD_USERNAME',false); }
	if (!defined('TEXT_STAFF_POSITION_UNKNOWN')) { define('TEXT_STAFF_POSITION_UNKNOWN','TEXT_STAFF_POSITION_UNKNOWN',false); }
	if (!defined('TEXT_USERNAME_NOT_ACTIVE')) { define('TEXT_USERNAME_NOT_ACTIVE','TEXT_USERNAME_NOT_ACTIVE',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','TEXT_VALUE_NOT_SET',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','System admin',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Clinic admin',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','Authorized user',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Clinic staff',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN')) { define('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN','Unknown',false); }
	if (!defined('TEXT_ADD_NEW_USER')) { define('TEXT_ADD_NEW_USER','Add new user or staff member',false); }
	if (!defined('TEXT_NO_STAFF_RECORDS')) { define('TEXT_NO_STAFF_RECORDS','No users or staff members found',false); }
	if (!defined('TEXT_PICLINIC_USERS_PAGE_TITLE')) { define('TEXT_PICLINIC_USERS_PAGE_TITLE','piClinic users and clinic staff',false); }
	if (!defined('TEXT_STAFF_DATE_FORMAT')) { define('TEXT_STAFF_DATE_FORMAT','m/d/Y H:i',false); }
	if (!defined('TEXT_STAFF_EDIT_USERNAME_TITLE')) { define('TEXT_STAFF_EDIT_USERNAME_TITLE','Edit user properties',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD')) { define('TEXT_STAFF_LIST_HEAD','piClinic users and clinic staff',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED')) { define('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED','Access',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CONTACTINFO')) { define('TEXT_STAFF_LIST_HEAD_CONTACTINFO','Contact info',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CREATEDDATE')) { define('TEXT_STAFF_LIST_HEAD_CREATEDDATE','Date added',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_LASTLOGIN')) { define('TEXT_STAFF_LIST_HEAD_LASTLOGIN','Date of last login',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MEMBERID')) { define('TEXT_STAFF_LIST_HEAD_MEMBERID','Clinic ID',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE')) { define('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE','Date last updated',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_NAME')) { define('TEXT_STAFF_LIST_HEAD_NAME','Name',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_POSITION')) { define('TEXT_STAFF_LIST_HEAD_POSITION','Clinic group',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_USERNAME')) { define('TEXT_STAFF_LIST_HEAD_USERNAME','Username',false); }
	if (!defined('TEXT_STAFF_POSITION_UNKNOWN')) { define('TEXT_STAFF_POSITION_UNKNOWN','Unknown',false); }
	if (!defined('TEXT_USERNAME_NOT_ACTIVE')) { define('TEXT_USERNAME_NOT_ACTIVE','Inactive username',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','(not set)',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_ADMIN')) { define('TEXT_ACCESS_GRANTED_OPTION_ADMIN','Administrador del sistema',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_CLINIC')) { define('TEXT_ACCESS_GRANTED_OPTION_CLINIC','Administrador de la clínica',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_RO')) { define('TEXT_ACCESS_GRANTED_OPTION_RO','Usuario autorizado',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_STAFF')) { define('TEXT_ACCESS_GRANTED_OPTION_STAFF','Personal de la clínica',false); }
	if (!defined('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN')) { define('TEXT_ACCESS_GRANTED_OPTION_UNKNOWN','Desconocido',false); }
	if (!defined('TEXT_ADD_NEW_USER')) { define('TEXT_ADD_NEW_USER','Agrega una persona o usuario nuevo',false); }
	if (!defined('TEXT_NO_STAFF_RECORDS')) { define('TEXT_NO_STAFF_RECORDS','No hay usuarios o personal de la clínica',false); }
	if (!defined('TEXT_PICLINIC_USERS_PAGE_TITLE')) { define('TEXT_PICLINIC_USERS_PAGE_TITLE','Usuarios del sistema piClinic y personal de la clínica',false); }
	if (!defined('TEXT_STAFF_DATE_FORMAT')) { define('TEXT_STAFF_DATE_FORMAT','d-m-Y H:i',false); }
	if (!defined('TEXT_STAFF_EDIT_USERNAME_TITLE')) { define('TEXT_STAFF_EDIT_USERNAME_TITLE','Actualizar los propiedades del usuario',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD')) { define('TEXT_STAFF_LIST_HEAD','Usuarios del sistema piClinic y personal de la clínica',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED')) { define('TEXT_STAFF_LIST_HEAD_ACCESSGRANTED','Permiso',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CONTACTINFO')) { define('TEXT_STAFF_LIST_HEAD_CONTACTINFO','Como contactar',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_CREATEDDATE')) { define('TEXT_STAFF_LIST_HEAD_CREATEDDATE','Fecha ingresada',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_LASTLOGIN')) { define('TEXT_STAFF_LIST_HEAD_LASTLOGIN','Fecha de su última sesión',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MEMBERID')) { define('TEXT_STAFF_LIST_HEAD_MEMBERID','ID de la clínica',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE')) { define('TEXT_STAFF_LIST_HEAD_MODIFIEDDATE','Fecha de último cambio',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_NAME')) { define('TEXT_STAFF_LIST_HEAD_NAME','Nombre',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_POSITION')) { define('TEXT_STAFF_LIST_HEAD_POSITION','Grupo en la clínica',false); }
	if (!defined('TEXT_STAFF_LIST_HEAD_USERNAME')) { define('TEXT_STAFF_LIST_HEAD_USERNAME','Usuario',false); }
	if (!defined('TEXT_STAFF_POSITION_UNKNOWN')) { define('TEXT_STAFF_POSITION_UNKNOWN','Desconocido',false); }
	if (!defined('TEXT_USERNAME_NOT_ACTIVE')) { define('TEXT_USERNAME_NOT_ACTIVE','Usuario inactivo',false); }
	if (!defined('TEXT_VALUE_NOT_SET')) { define('TEXT_VALUE_NOT_SET','(vacio)',false); }
}
//EOF
