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

/*
 *  used to define workflow of visit open/admission
 *      true: show patient visit form to print after visit open
 *      false: return to dashboard after visit open
 */
define ('VISIT_PRINT_FORM_AFTER_OPEN',false,false);

/*
 *  used to enable debug info in API responses
 *      true: returns SQL data. Should be false for production
 *      false: No debug info returned
 */
define('API_DEBUG_MODE', false, false);

/*
 *  used to enable API profiling
 *      true: collect profiling information. Should be false for production
 *      false: do not log profiling information. Does not affect workflow event logging
 */
define('API_PROFILE', false, false);
