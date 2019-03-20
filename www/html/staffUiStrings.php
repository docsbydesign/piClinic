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
/**
 * Created by PhpStorm.
 * User: BobW
 * Date: 3/6/2018
 * Time: 5:12 PM
 */
/* These strings are used in the UI and must be included after the language specific strings */
/* Define staff position values */
require_once('./uitext/staffUiStringsText.php');
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