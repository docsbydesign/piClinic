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
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header(CONTENT_TYPE_HEADER_HTML);
	exit;	
}

if (!defined('REST_CONSTANTS')) {
	define('REST_CONSTANTS', 'database_constants', false);
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// include password(s)
	require_once('../../pass/dbPass.php');
	// Contains paths to files and folders shared by the 
	//  php scripts on the server.

	// configuration definitions
	define('API_DEBUG_MODE', true, false); 	// true, returns SQL data. Should be false for production
	define('API_PROFILE', true, false); // true to collect profiling information. Should be false for production
	define('API_LOG_FILEPATH', '/var/log/piclinic/', false); // the path to the log folder
	define('API_IMAGE_FILEPATH', '/var/local/piclinic/images/', false); // the system folder where image resources are stored
	define('API_DELETED_FILEPATH', '/var/local/piclinic/deleted/', false); // the system folder where image resources are stored
	define('API_MAX_FILESIZE', 2*1024*1024, false); // this must be less than or equal to the upload_max_filesize value in the PHP.INI
	define('DB_QUERY_LIMIT', ' LIMIT 100', false);	// added to queries to limit response size

	define('CONTENT_TYPE_JSON','application/json; charset=utf-8;', false);
    define('CONTENT_TYPE_HEADER_JSON','Content-Type: application/json; charset=utf-8;', false);
    define('CONTENT_TYPE_HTML','text/html; charset=utf-8;', false);
    define('CONTENT_TYPE_HEADER_HTML','Content-Type: text/html; charset=utf-8;', false);

	// languages
	define('UI_DEFAULT_LANGUAGE','en', false); 
	define('UITEST_LANGUAGE','ui',false);
	define('UI_ENGLISH_LANGUAGE','en', false);
	define('UI_SPANISH_LANGUAGE','es', false);
	
	// database interfaces
	define('DB_SERVER','localhost', false);
	define('DB_USER','CTS-user', false);
	define('DB_DATABASE_NAME','piclinic', false);
	
	// database tables
	define('DB_TABLE_PATIENT', 'patient', false);
	define('DB_TABLE_IMAGE','image', false);
	define('DB_TABLE_VISIT','visit', false);
	define('DB_TABLE_SESSION','session', false);
	define('DB_TABLE_STAFF','staff', false);
	define('DB_TABLE_COMMENT','comment', false);
	define('DB_TABLE_ICD10','icd10', false);
	define('DB_TABLE_LOGGER','logger', false);
	
	// database views
	define('DB_VIEW_VISIT_GET_WITH_AGEGROUP','visitGetWithAgeGroup',false);
	define('DB_VIEW_CLINIC_PATIENT_SUMMARY_1','ClinicPatientSummary1', false);
	define('DB_VIEW_PATIENT_GET','patientGet', false);
	define('DB_VIEW_IMAGE_GET','imageGet', false);
	define('DB_VIEW_STAFF_GET','staffGetByUser',false);
	define('DB_VIEW_STAFF_GET_BY_NAME','staffGetByName',false);
	define('DB_VIEW_VISIT_CHECK','visitCheck', false);
	define('DB_VIEW_VISIT_EDIT_GET','visitEditGet', false);
	define('DB_VIEW_VISIT_GET','visitGet', false);
	define('DB_VIEW_VISIT_START','visitStart', false);
	define('DB_VIEW_VISIT_OPEN','visitOpen', false);
	define('DB_VIEW_VISIT_TODAY','visitToday', false);
	define('DB_VIEW_VISIT_PATIENT_EDIT_GET','visitPatientEditGet', false);
	define('DB_VIEW_VISIT_PATIENT_GET','visitPatientGet', false);
	define('DB_VIEW_VISIT_OPEN_SUMMARY','visitOpenSummary', false);
	define('DB_VIEW_COMMENT_GET','commentGet', false);
	define('DB_VIEW_ICD10_GET','icd10Get',false);
}
//EOF
