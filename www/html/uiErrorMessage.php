<?php
/*
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
/*
*	Code fragment to decode and display error message text
* 		as a div inline 
*
*/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api/api_common.php';
require_once 'uitext/appMenuText.php';
exitIfCalledFromBrowser(__FILE__);

if(isset($pageLanguage)) {
	require_once('./uitext/uiErrorMessageText.php');
	if (((!empty($requestData)) && (!empty($requestData['msg']))) || (!empty($dbStatus))) {
		echo ('<div class="errorMessage" id="uiErrorMessage">');
		if (((!empty($requestData)) && (!empty($requestData['msg'])))) {
			echo ('<p>');
			switch ($requestData['msg']) {
				case "NOT_FOUND" :
					echo TEXT_MESSAGE_NO_PATIENT_FOUND;
					break;
					
				case "NOT_CREATED" :
					echo TEXT_MESSAGE_NO_PATIENT_CREATED;
					break;
				
				case "NOT_UPDATED" :
					echo TEXT_MESSAGE_NO_PATIENT_UPDATED;
					break;

				case "PATIENT_ID_IN_USE":
					echo TEXT_MESSAGE_PATIENT_ID_IN_USE;
					break;
					
				case "DB_OPEN_ERROR":
					echo TEXT_MESSAGE_DATABASE_OPEN_ERROR;
					break;

				case "UNSUPPORTED":
					echo TEXT_MESSAGE_UNSUPPORTED_ERROR;
					break;
					
				case "LOGIN_FAILURE":
					echo TEXT_MESSAGE_LOGIN_FAILURE;
					break;

				case "NO_ACCESS":
					echo TEXT_MESSAGE_ACCESS_FAILURE;
					break;
					
				case "REQUIRED_FIELD_MISSING":
					echo TEXT_MESSAGE_REQUIRED_FIELD_MISSING;
					break;

				default:
					echo TEXT_MESSAGE_GENERIC;
					break;
			}
			echo '</p>';
		}
		// check for an internal error
		if (!empty($dbStatus)) {
			if (isset($dbStatus['httpReason'])){
				echo '<p class="errorHeading">'.$dbStatus['httpReason'].'</p>';
			} else {
				echo '<p class="errorHeading">'.TEXT_MESSAGE_INTERNAL_ERROR.'</p>';				
			}
		}
		// clear msg to prevent them from stacking up
        unset ($requestData['msg']);
		echo '</div>';
	}
}
?>