<?php
/*
 *
 * Copyright (c) 2018 by Robert B. Watson
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
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header('Content-Type: application/json; charset=utf-8;');
    header("HTTP/1.1 404 Not Found");
    echo <<<MESSAGE
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
MESSAGE;
    echo "\n<p>The requested URL " . $_SERVER['PHP_SELF'] ." was not found on this server.</p>\n";
    echo "<hr>\n";
    echo '<address>'. apache_get_version() . ' Server at ' . $_SERVER['SERVER_ADDR'] . ' Port '. $_SERVER['SERVER_PORT'] . "</address>\n";
    echo "</body></html>\n";
    exit(0);

}

if (!defined('REST_CONSTANTS')) {
	define('REST_CONSTANTS', 'database_constants', false);
	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// include password(s)
	require_once dirname(__FILE__).'/../../pass/dbPass.php';
	// Contains paths to files and folders shared by the
	//  php scripts on the server.

	// configuration definitions
	define('API_LOG_FILEPATH', '/var/log/piclinic/', false); // the path to the log folder
	define('API_IMAGE_FILEPATH', '/var/local/piclinic/images/', false); // the system folder where image resources are stored
	define('API_DELETED_FILEPATH', '/var/local/piclinic/deleted/', false); // the system folder where image resources are stored
	define('API_MAX_FILESIZE', 2*1024*1024, false); // this must be less than or equal to the upload_max_filesize value in the PHP.INI
    define('DB_QUERY_LIMIT_COUNT', 100, false); // this MUST match the value in DB_QUERY_LIMIT
	define('DB_QUERY_LIMIT', ' LIMIT 100', false);	// added to queries to limit response size

    define('MAX_TEXT_MESSAGE_LENGTH', 1023, false); // the size of textmsg.messageText field
    define('DEFAULT_SEND_SERVICE', 'LocalMobile', false); // the default text message service
    define('DEFAULT_TEXTMSG_MAX_SEND_ATTEMPTS', 5, false); // how many times to try sending a text message
    define('DEFAULT_RETRY_INTERVAL', 300, false); // how long (seconds) to wait between message attempts

	define('CONTENT_TYPE_JSON','application/json; charset=utf-8;', false);
    define('CONTENT_TYPE_HEADER_JSON','Content-Type: application/json; charset=utf-8;', false);
    define('CONTENT_TYPE_HTML','text/html; charset=utf-8;', false);
    define('CONTENT_TYPE_HEADER_HTML','Content-Type: text/html; charset=utf-8;', false);

    define('ROOT_DIR_PATH', '/var/www/html/', false); // used to filter out path in fromLink values
    define('FIRST_FROM_LINK_QP', '?fromLink', false); // query parameter string when first QP
    define('FROM_LINK_QP','&fromLink', false); // use in query paramter string after first QP
    define('FROM_LINK','fromLink',false); // used in form Values
    define('FROM_LINK_SEP','-',false); // character used to separate elements in From link

    define('UITEST_LANGUAGE','ui',false);
    define('UI_ENGLISH_LANGUAGE','en', false);
    define('UI_SPANISH_LANGUAGE','es', false);

    function isSupportedLanguage ($langToCheck) {
        switch ($langToCheck) {
            case UI_ENGLISH_LANGUAGE:
            case UI_SPANISH_LANGUAGE:
                return true;

            default:
                return false;
        }
    }


    define("PT_VALIDATE_NONE", 0, false);
    define("PT_VALIDATE_NAME_SERIAL", 1, false);
    define("PT_VALIDATE_CITY_SERIAL", 2, false);
    define("PT_VALIDATE_NEW",64,false);
    define("PT_VALIDATE_UPDATE",128,false);

	// database interfaces
	define('DB_SERVER','localhost', false);
	define('DB_USER','CTS-user', false);
	define('DB_DATABASE_NAME','piclinic', false);

	// database tables
    define('DB_TABLE_CLINIC','clinic',false);
    define('DB_TABLE_COMMENT','comment', false);
    define('DB_TABLE_HELP', 'help', false);
    define('DB_TABLE_ICD10','icd10', false);
    define('DB_TABLE_IMAGE','image', false);
    define('DB_TABLE_LOG','log', false);
	define('DB_TABLE_PATIENT', 'patient', false);
    define('DB_TABLE_SESSION','session', false);
    define('DB_TABLE_STAFF','staff', false);
    define('DB_TABLE_VISIT','visit', false);
    define('DB_TABLE_TEXTMSG','textmsg', false);
    define('DB_TABLE_WFLOG','wflog', false);

	// database views
    define('DB_VIEW_COMMENT_GET','commentGet', false);
    define('DB_VIEW_ICD10_GET','icd10Get',false);
    define('DB_VIEW_VISIT_EDIT_GET','visitEditGet', false);
    define('DB_VIEW_VISIT_GET','visitGet', false);
    define('DB_VIEW_VISIT_OPEN','visitOpen', false);
    define('DB_VIEW_VISIT_OPEN_SUMMARY','visitOpenSummary', false);
    define('DB_VIEW_VISIT_PATIENT_EDIT_GET','visitPatientEditGet', false);
    define('DB_VIEW_VISIT_PATIENT_GET','visitPatientGet', false);
    define('DB_VIEW_VISIT_START','visitStart', false);
    define('DB_VIEW_VISIT_TODAY','visitToday', false);
    define('DB_VIEW_CLINIC_PATIENT_SUMMARY_1','ClinicPatientSummary1', false);
    define('DB_VIEW_IMAGE_GET','imageGet', false);
    define('DB_VIEW_PATIENT_GET','patientGet', false);
    define('DB_VIEW_STAFF_GET','staffGetByUser',false);
    define('DB_VIEW_STAFF_GET_BY_NAME','staffGetByName',false);
    define('DB_VIEW_VISIT_CHECK','visitCheck', false);
    define('DB_VIEW_VISIT_GET_WITH_AGEGROUP','visitGetWithAgeGroup',false);
    define('DB_VIEW_THISCLINIC', 'thisClinicGet', false);

    // include password(s)
    require_once dirname(__FILE__).'/../../pass/clinicSpecific.php';
}
//EOF
