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
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','TEXT_MESSAGE_ACCESS_FAILURE',false); }
	if (!defined('TEXT_MESSAGE_BACKUP_FAIL')) { define('TEXT_MESSAGE_BACKUP_FAIL','TEXT_MESSAGE_BACKUP_FAIL',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','TEXT_MESSAGE_DATABASE_OPEN_ERROR',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','TEXT_MESSAGE_GENERIC',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','TEXT_MESSAGE_INTERNAL_ERROR',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','TEXT_MESSAGE_LOGIN_FAILURE',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','TEXT_MESSAGE_NO_PATIENT_CREATED',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','TEXT_MESSAGE_NO_PATIENT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','TEXT_MESSAGE_NO_PATIENT_UPDATED',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','TEXT_MESSAGE_PATIENT_ID_IN_USE',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','TEXT_MESSAGE_REQUIRED_FIELD_MISSING',false); }
	if (!defined('TEXT_MESSAGE_SESSION_TIMEOUT')) { define('TEXT_MESSAGE_SESSION_TIMEOUT','TEXT_MESSAGE_SESSION_TIMEOUT',false); }
	if (!defined('TEXT_MESSAGE_TOPIC_NOT_FOUND')) { define('TEXT_MESSAGE_TOPIC_NOT_FOUND','TEXT_MESSAGE_TOPIC_NOT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','TEXT_MESSAGE_UNSUPPORTED_ERROR',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','TEXT_MESSAGE_USER_NOT_FOUND',false); }
	if (!defined('TEXT_MESSAGE_VALIDATION_FAIL')) { define('TEXT_MESSAGE_VALIDATION_FAIL','TEXT_MESSAGE_VALIDATION_FAIL',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','You do not have access to the last page.<br>To view that page, log in with a different username.',false); }
	if (!defined('TEXT_MESSAGE_BACKUP_FAIL')) { define('TEXT_MESSAGE_BACKUP_FAIL','Unable to create backup file',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','Server Error - Database not opened.',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','There was a problem with the last entry. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','A serious internal error occured.',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','Username or password is not correct.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','Could not create a new patient record. Check the data and try again.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','Could not find any patients that match.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','Could not update the patient\'s info. Check your changes and try again.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','Could not create a new patient record. The patient ID is already assigned.',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','A required field was not filled in.',false); }
	if (!defined('TEXT_MESSAGE_SESSION_TIMEOUT')) { define('TEXT_MESSAGE_SESSION_TIMEOUT','You haven\'t been using the system for a while so you were logged out',false); }
	if (!defined('TEXT_MESSAGE_TOPIC_NOT_FOUND')) { define('TEXT_MESSAGE_TOPIC_NOT_FOUND','Help topic not found.',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','Cannot perform the action requested.',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','The staff member could not be found.',false); }
	if (!defined('TEXT_MESSAGE_VALIDATION_FAIL')) { define('TEXT_MESSAGE_VALIDATION_FAIL','There is an incorrect value in the form',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_MESSAGE_ACCESS_FAILURE')) { define('TEXT_MESSAGE_ACCESS_FAILURE','No tiene permiso para ver la última página. Para verla, iniciar una sesión con otro usuario.',false); }
	if (!defined('TEXT_MESSAGE_BACKUP_FAIL')) { define('TEXT_MESSAGE_BACKUP_FAIL','No pudo crear archivo para descargar',false); }
	if (!defined('TEXT_MESSAGE_DATABASE_OPEN_ERROR')) { define('TEXT_MESSAGE_DATABASE_OPEN_ERROR','Error con el servidor. La base de datos no abrió.',false); }
	if (!defined('TEXT_MESSAGE_GENERIC')) { define('TEXT_MESSAGE_GENERIC','Hubo un problema con el último cambio. Revisa los datos e intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_INTERNAL_ERROR')) { define('TEXT_MESSAGE_INTERNAL_ERROR','El sistema encontró un error grave.',false); }
	if (!defined('TEXT_MESSAGE_LOGIN_FAILURE')) { define('TEXT_MESSAGE_LOGIN_FAILURE','Usuario o contraseña no está correcto.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_CREATED')) { define('TEXT_MESSAGE_NO_PATIENT_CREATED','No se puede crear un expediente del paciente nuevo. Revisa los datos e intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_FOUND')) { define('TEXT_MESSAGE_NO_PATIENT_FOUND','No se encontró ningún paciente que coincide.',false); }
	if (!defined('TEXT_MESSAGE_NO_PATIENT_UPDATED')) { define('TEXT_MESSAGE_NO_PATIENT_UPDATED','No se puede editar el expediente del paciente. Revisa los datos e intenta de nuevo.',false); }
	if (!defined('TEXT_MESSAGE_PATIENT_ID_IN_USE')) { define('TEXT_MESSAGE_PATIENT_ID_IN_USE','No se puede crear un expediente del paciente nuevo. El ID del paciente está usado por otro paciente.',false); }
	if (!defined('TEXT_MESSAGE_REQUIRED_FIELD_MISSING')) { define('TEXT_MESSAGE_REQUIRED_FIELD_MISSING','Un campo requirido no tiene datos.',false); }
	if (!defined('TEXT_MESSAGE_SESSION_TIMEOUT')) { define('TEXT_MESSAGE_SESSION_TIMEOUT','',false); }
	if (!defined('TEXT_MESSAGE_TOPIC_NOT_FOUND')) { define('TEXT_MESSAGE_TOPIC_NOT_FOUND','Contenido no encontrado',false); }
	if (!defined('TEXT_MESSAGE_UNSUPPORTED_ERROR')) { define('TEXT_MESSAGE_UNSUPPORTED_ERROR','No se puede hacer la acción pedida.',false); }
	if (!defined('TEXT_MESSAGE_USER_NOT_FOUND')) { define('TEXT_MESSAGE_USER_NOT_FOUND','La persona no fue encontrada.',false); }
	if (!defined('TEXT_MESSAGE_VALIDATION_FAIL')) { define('TEXT_MESSAGE_VALIDATION_FAIL','Hay un campo con un error ',false); }
}
//EOF
