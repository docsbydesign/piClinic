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
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','TEXT_MESSAGE_ACCESS_FAILURE',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','TEXT_MESSAGE_DATABASE_OPEN_ERROR',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','TEXT_MESSAGE_GENERIC',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','TEXT_MESSAGE_INTERNAL_ERROR',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','TEXT_MESSAGE_LOGIN_FAILURE',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','TEXT_MESSAGE_NO_PATIENT_CREATED',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','TEXT_MESSAGE_NO_PATIENT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','TEXT_MESSAGE_NO_PATIENT_UPDATED',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','TEXT_MESSAGE_PATIENT_ID_IN_USE',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','TEXT_MESSAGE_REQUIRED_FIELD_MISSING',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','TEXT_MESSAGE_UNSUPPORTED_ERROR',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','TEXT_MESSAGE_USER_NOT_FOUND',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','You do not have access to the last page.<br>To view that page, log in with a different username.',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','Server Error - Database not opened.',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','There was a problem with the last entry. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','A serious internal error occured.',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','Username or password is not correct.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','Could not create a new patient record. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','Could not find any patients that match.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','Could not update the patient\'s info. Check your changes and try again.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','Could not create a new patient record. The patient ID is already assigned.',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','A required field was not filled in.',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','Cannot perform the action requested.',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','The staff member could not be found.',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','No tiene permiso para ver la última página. Para verla, iniciar una sesión con otro usuario.',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','Error con el servidor. El base de datos no abrió.',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','Hubo un problema con el último cambio. Revisa los datos y intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','El sistema encontró un error grave.',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','Usuario o contraseña no está correcto.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','No pude crear un expediente del paciente nuevo. Revisa los datos y intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','No se encontró ninguna paciente que coincide.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','No pude editar el expediente del paciente. Revisa los datos y intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','No pude crear un expediente del paciente nuevo. El ID del paciente esta usado por otro paciente.',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','Un campo requirido no tiene datos.',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','No puede hacer la acción pedido.',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','La persona no estuvo encontrado.',false); }
}
//EOF
