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

if (!defined('DB_UTILS')) {
	define('DB_UTILS', '_db_utils', false);
	
	function _openDB() {
		$link = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE_NAME);
		if ($link !== FALSE) {
				// set database character set to UTF8
			@mysqli_set_charset( $link, 'utf8' );
		}
		return $link;
	}
		
	function format_object_for_SQL_insert ($tableName, $object) {
		// set the dateCreated field
		$now = new DateTime();
		$object['createdDate'] = $now->format('Y-m-d H:i:s');
				
		// format the fields of the object to the appropriate SQL syntax		
		foreach ($object as $dbCol => $dbVal) {
			if ($dbCol == '__source') { continue; } // skip this, if present
			isset($dbColList) ? $dbColList .= ', ' : $dbColList = '';
			isset($dbValList) ? $dbValList .= ', ' : $dbValList = '';										
			$dbColList .= $dbCol;
			if (empty($dbVal) && (strlen($dbVal)==0)) {
				$dbValList .= 'NULL';
			} else {
				$escapedString = str_replace("'","''", $dbVal);
				$dbValList .= '\''.$escapedString.'\'';
			}							
		}
		$queryString = 'INSERT INTO '.$tableName.' ('.$dbColList.') VALUES ('.$dbValList.')';
		return $queryString;
	}

	function format_object_for_multikey_SQL_update (
			$tableName, $object, $keyFields, &$columnCount, $quoteValues = TRUE) {
		$columnCount = 0;
		foreach ($keyFields as $field)
		if (empty($object[$field])) {
			// all key fields must exist in the $object array.
			return "";
		}	
		// format the fields of the object to the appropriate SQL syntax
		$queryString = 'UPDATE '.$tableName.' SET ';
		$dbCols = 0;
		foreach ($object as $dbCol => $dbVal) {
			if ($dbCol == '__source') { continue; } // skip this, if present
			// don't try to update a key field
			if (!in_array($dbCol, $keyFields)) {
				if (empty($dbVal) && (strlen($dbVal)==0)) {
					$dbValString = 'NULL';
				} else {
					if ($quoteValues) {
						$escapedString = str_replace("'","''",$dbVal);
						$dbValString = '\''.$escapedString.'\'';
					} else {
						// note that the caller must ensure the value does not break
						// the query string.
						$dbValString = $dbVal;
					}
				}
				if ($dbCols > 0) {
					$queryString .= ',';
				}
				$queryString .= "`".$dbCol."` = ".$dbValString;
				$dbCols += 1;				
			}
		}
		$queryString .= " WHERE ";
		
		foreach ($keyFields as $field) {
			$queryString .= "`".$field."` = '".$object[$field]."' AND ";
		}
		$queryString .= 'TRUE;';

		$columnCount = $dbCols;
		return $queryString;
	}	

	
	function format_object_for_SQL_update ($tableName, $object, $primaryKeyField, &$columnCount) {
		$columnCount = 0;
		if (empty($object[$primaryKeyField])) {
			// the primary key field must exist in the $object array.
			return "";
		}	
		// format the fields of the object to the appropriate SQL syntax
		$queryString = 'UPDATE '.$tableName.' SET ';
		$dbCols = 0;
		foreach ($object as $dbCol => $dbVal) {
			if ($dbCol == '__source') { continue; } // skip this, if present
			// don't try to update the primary key
			if ($dbCol != $primaryKeyField) {
				if (empty($dbVal) && (strlen($dbVal)==0)) {
					$dbValString = 'NULL';
				} else {
					$escapedString = str_replace("'","''",$dbVal);
					$dbValString = '\''.$escapedString.'\'';
				}
				if ($dbCols > 0) {
					$queryString .= ',';
				}
				$queryString .= "`".$dbCol."` = ".$dbValString;
				$dbCols += 1;				
			}
		}
		$queryString .= " WHERE `".$primaryKeyField."` = '".$object[$primaryKeyField]."';";

		$columnCount = $dbCols;
		return $queryString;
	}	
	 /*
	 * 
	 *
	 */
	function getDbRecords($dbLink, $getQueryString) {
		$qResult = @mysqli_query($dbLink, $getQueryString);
		$returnValue['count'] = 0;
		if ($qResult === FALSE) {
			// SQL ERROR
			$dbInfo['getQueryString'] = $getQueryString;
			$dbInfo['sqlError'] = @mysqli_error($dbLink);
			// format response
			$returnValue['contentType'] = CONTENT_TYPE_JSON;
			if (API_DEBUG_MODE) {
				$returnValue['error'] = $dbInfo;
			}
			$returnValue['httpResponse'] = 404;
			$returnValue['httpReason']	= "Resource not found. Query Error.";
		} else {
			// success
			// format response based on how many records are returned
			$records = @mysqli_num_rows($qResult);
			switch ($records) {
				case 0:
					// format response
					$returnValue['contentType'] = CONTENT_TYPE_JSON;
					$returnValue['data'] = "";
					$returnValue['httpResponse'] = 404;
					$returnValue['httpReason']	= "Resource not found. No Records.";
					break;
					
				case 1:
					// format response
					$returnValue['contentType'] = CONTENT_TYPE_JSON;
					$returnValue['count'] = 1;
					$rowValue = @mysqli_fetch_assoc($qResult);				
					if (isset($rowValue)) {
						$returnValue['data'] = $rowValue;
						$returnValue['httpResponse'] = 200;
						$returnValue['httpReason']	= "Success-1";
					} else {
						$returnValue['data'] = "";
						$returnValue['count'] = 0;
						$returnValue['httpResponse'] = 500;
						$returnValue['httpReason']	= "Server error: Unable to read record from DB";
					}
					break;
					
				default:
					$dataRows = array();
					while ($patientRecord = @mysqli_fetch_assoc($qResult)) {
						array_push($dataRows, $patientRecord);
						$returnValue['count'] += 1;
					}
					$returnValue['contentType'] = CONTENT_TYPE_JSON;
					$rowValue = @mysqli_fetch_assoc($qResult);
					if (isset($dataRows)) {
						$returnValue['data'] = $dataRows;
						$returnValue['httpResponse'] = 200;
						$returnValue['httpReason']	= "Success-n";
					} else {
						$returnValue['data'] = "";
						$returnValue['count'] = 0;
						$returnValue['httpResponse'] = 500;
						$returnValue['httpReason']	= "Server error: Unable to read multiple records from DB";
					}				
					break;				
			}
			@mysqli_free_result($qResult);
		}
		return $returnValue;
	}

	function openDbForUi ($requestData, $pageLanguage, &$dbStatus) {
		require_once 'uiErrorMessageText.php';
		$dbInfo = array();
		$dbLink = _openDB();
		$dbOpenError = mysqli_connect_errno();
		if ( $dbOpenError  != 0  ) {
			// database not opened.
			$dbStatus['contentType'] = CONTENT_TYPE_JSON;
			if (API_DEBUG_MODE) {
				$dbInfo['sqlError'] = 'Error: '. $dbOpenError .', '.
					mysqli_connect_error();
				$dbInfo['requestData'] = $requestData;
				$dbInfo['language'] = $pageLanguage;
				$dbStatus['error'] = $dbInfo;
			}
			$dbStatus['httpResponse'] = 500;
			$dbStatus['httpReason']   = MESSAGE_DATABASE_OPEN_ERROR;
		}
		return $dbLink;
	}
}
//EOF