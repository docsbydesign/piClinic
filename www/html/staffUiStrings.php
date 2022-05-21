<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
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
/* These strings are used in the UI and must be included after the language specific strings */
/* Define staff position values */
require_once dirname(__FILE__).'/./uitext/staffUiStringsText.php';
define('MEDICALSTAFF',1,false);
define('NONMEDICALSTAFF',0,false);
$staffPositions = [
    ["Nurse", TEXT_STAFF_POSITION_OPTION_NURSE,MEDICALSTAFF],
    ["NursesAid", TEXT_STAFF_POSITION_OPTION_NURSESAID,MEDICALSTAFF],
    ["DoctorGeneral", TEXT_STAFF_POSITION_OPTION_DOCTORGENERAL,MEDICALSTAFF],
    ["DoctorSpecialist", TEXT_STAFF_POSITION_OPTION_DOCTORSPECIALIST,MEDICALSTAFF],
    ["NursingStudent", TEXT_STAFF_POSITION_OPTION_NURSINGSTUDENT,MEDICALSTAFF],
    ["MedicalStudent", TEXT_STAFF_POSITION_OPTION_MEDICALSTUDENT,MEDICALSTAFF],
    ["ClinicStaff", TEXT_STAFF_POSITION_OPTION_CLINICSTAFF,NONMEDICALSTAFF],
    ["Other", TEXT_STAFF_POSITION_OPTION_OTHER,NONMEDICALSTAFF]
];
?>
