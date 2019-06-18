<?php
/*
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
/*
 *
 *	Code fragment to decode and display error message text
 * 		as a div inline
 *
 *      ASSUMES:
 *          $requestData has been initialized
 *
 *
*/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/api/api_common.php';
//require_once 'uitext/appMenuText.php';
exitIfCalledFromBrowser(__FILE__);

if (isset($pageLanguage)) {
	require_once dirname(__FILE__).'/./uitext/uiErrorMessageText.php';
	if (((!empty($requestData)) && (!empty($requestData['msg']))) || (!empty($dbStatus))) {
		echo ('<div class="errorMessage" id="uiErrorMessage">');
		if (((!empty($requestData)) && (!empty($requestData['msg'])))) {
			echo ('<p>');
			switch ($requestData['msg']) {
				case MSG_NOT_FOUND :
					echo TEXT_MESSAGE_NO_PATIENT_FOUND;
					break;
					
				case MSG_NOT_CREATED :
					echo TEXT_MESSAGE_NO_PATIENT_CREATED;
					break;
				
				case MSG_NOT_UPDATED :
					echo TEXT_MESSAGE_NO_PATIENT_UPDATED;
					break;

				case MSG_PATIENT_ID_IN_USE :
					echo TEXT_MESSAGE_PATIENT_ID_IN_USE;
					break;
					
				case MSG_DB_OPEN_ERROR :
					echo TEXT_MESSAGE_DATABASE_OPEN_ERROR;
					break;

				case MSG_UNSUPPORTED :
					echo TEXT_MESSAGE_UNSUPPORTED_ERROR;
					break;
					
				case MSG_LOGIN_FAILURE :
					echo TEXT_MESSAGE_LOGIN_FAILURE;
					break;

				case MSG_NO_ACCESS :
					echo TEXT_MESSAGE_ACCESS_FAILURE;
					break;
					
				case MSG_REQUIRED_FIELD_MISSING :
					echo TEXT_MESSAGE_REQUIRED_FIELD_MISSING;
					break;

                case MSG_USER_NOT_FOUND :
                    echo TEXT_MESSAGE_USER_NOT_FOUND;
                    break;

                case MSG_TOPIC_NOT_FOUND :
                    echo TEXT_MESSAGE_TOPIC_NOT_FOUND;
                    break;

                case MSG_SESSION_TIMEOUT :
                    echo TEXT_MESSAGE_SESSION_TIMEOUT;
                    break;

                case MSG_BACKUP_FAIL:
                    echo TEXT_MESSAGE_BACKUP_FAIL;
                    break;

                case MSG_VALIDATION_FAILED:
                    echo TEXT_MESSAGE_VALIDATION_FAIL;
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
} else {
    echo "<!-- No page language set --!>\n";
}
//EOF