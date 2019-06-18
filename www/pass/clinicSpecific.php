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
 * This file is included by piClinicConfig.php to define the settings that are unique to the
 * specific clinic installation and should not be overwritten by software updates.
 *
 */

define('AUTOINCREMENT_CLINICPATIENTID', true, false); // used to suggest the next clinicPatientID

define('UI_DEFAULT_LANGUAGE','en', false); // Default language for UI text

define('PT_VALIDATE_MODE', PT_VALIDATE_NONE, false); // how to validate patient records before saving or updating


