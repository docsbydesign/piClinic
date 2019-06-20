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
 * When changing these values, change ONLY the second parameter in the definition.
 * DO NOT change the first or the last parameter.
 */
/***************
 * used to suggest the next clinicPatientID
 *      true: for clinicPatientID values that are integers only, the next patient ID will be prompted on add new patient
 *      false: no new patient ID will be prompted
 */
define('AUTOINCREMENT_CLINICPATIENTID', true, false);

/*
 *  used to set the system default language for the UI. Note that the UI language precedence is:
 *      1. the user-specified language (e.g. via the query parameter or the screen selection
 *      2. the session language preference (as derived from the user preference)
 *      3. the system preference
 *
 *      en: English
 *      es: Spanish
 */
define('UI_DEFAULT_LANGUAGE','en', false); // Default language for UI text

/*
 *  used to define the validation mode used to verify patient records are correctly formatted before saving
 *      PT_VALIDATE_NONE: No validation is performed
 *      PT_VALIDATE_CITY_SERIAL: Check that the patient and family ID are formatted as letters-number (e.g. Roatan-1)
*          Note that the content of the fields are not checked to see if they match the home city
 *      PT_VALIDATE_NAME_SERIAL: Check that the patient and family ID are formatted as letters-number (e.g. Sanchez-1)
 *          Note that the content of the fields are not checked to see if they match the family name
 */
define('PT_VALIDATE_MODE', PT_VALIDATE_NONE, false); // how to validate patient records before saving or updating

/*
 *  used to describe how the patient information screens are organized
 *      true: show the patient ID fields as Patient ID, Family ID, National ID
 *      false: show the patient ID fields as Family ID, Patient ID, National ID
 */
define('PT_FAMILY_ID_FIRST', true, false);

/*
 *  used to set the default height units for the clinic. The values are case sensitive.
 *      in: inches
 *      cm: centimeters
 *      mm: millimeters
 *
 */
define('VISIT_DEFAULT_HEIGHT_UNITS','cm',false);

/*
 *  used to set the default weight units for the clinic. The values are case sensitive.
 *      lbs: pounds
 *      kg: kilograms
 */
define('VISIT_DEFAULT_WEIGHT_UNITS','kg',false);

/*
 *  used to set the default temperature units for the clinic. The values are case sensitive.
 *      C: celsius (centegrade)
 *      F: fahrenheit
 */
define('VISIT_DEFAULT_TEMP_UNITS','C',false);


