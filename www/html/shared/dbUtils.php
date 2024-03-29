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

    function _openDBforAPI($requestData) {
        $dbLink = _openDB();
        $dbOpenError = mysqli_connect_errno();
        $retVal = array();
        $dbInfo = array();
        if ($dbOpenError  != 0) {
            // database not opened. Log and return an error
            $retVal['contentType'] = CONTENT_TYPE_JSON;
            $dbInfo['sqlError'] = 'Error: '. $dbOpenError .', '.
                mysqli_connect_error();
            $retVal['error'] = $dbInfo;
            $retVal['httpResponse'] = 500;
            $retVal['httpReason']   = "Server Error - Database not opened.";
            $username = 'NotSpecified';
            if (!empty($requestData['token'])) {
                if (!empty($requestData['username'])) {
                    $username = $requestData['username'];
                }
                $requestData['token'];
            }
            logApiError($requestData, $retVal['httpResponse'], __FILE__, $username, 'DB', $retVal['httpReason']);
            outputResults( $retVal);
            exit; // this is the end of the line if there's no DB access
        } else {
            return $dbLink;
        }
	}

    function _openDBforUI($requestData, $errorUrl) {
        $dbLink = _openDB();
        $dbOpenError = mysqli_connect_errno();
        $retVal = array();
        $dbInfo = array();
        if ($dbOpenError  != 0) {
            // database not opened. Log and return an error
            $retVal['contentType'] = CONTENT_TYPE_JSON;
            $retVal['httpResponse'] = 500;
            $retVal['httpReason'] = 'Server Error - Database not opened.';
            $dbInfo['dbError'] = $dbOpenError .', '. mysqli_connect_error();
            header('contentType: '.$retVal['contentType']);
            header('X-piClinic-DbError: '. $dbInfo['dbError']);
            header('X-piClinic-ErrorResponse: '.$retVal['httpResponse']);
            header('X-piClinic-ErrorMessage: '.$retVal['httpReason']);
            if (!empty($requestData['token'])) {
                if (!empty($requestData['username'])) {
                    $username = $requestData['username'];
                }
                $requestData['token'];
            }
            logUiError($requestData, $retVal['httpResponse'], __FILE__, $username, 'DB Open',$retVal['httpReason']);
            if (!empty($errorUrl)) {
                // issue a redirect if there's a url to redirect
                $redirectUrl = $errorUrl . '?msg=DB_OPEN_ERROR';
                header('Location: '.$redirectUrl);
                exit; // this is the end of the line if there's no DB access
            } else {
                // if no error URL, return null and let the caller handle the error.
                return null;
            }
        } else {
            return $dbLink;
        }
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
			$dbColList .= '`'.$dbCol.'`';
			if (empty($dbVal) && (strlen($dbVal)==0)) {
				$dbValList .= 'NULL';
			} else {
				$escapedString = '';
				if (is_array($dbVal)) {
				    $escapedString = json_encode($dbVal);
				} else {
                    $escapedString = str_replace("'","''", $dbVal);
				}
				$dbValList .= '\''.$escapedString.'\'';
			}
		}
		$queryString = 'INSERT INTO `'.$tableName.'` ('.$dbColList.') VALUES ('.$dbValList.')';
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
                        // don't quote numbers or function names
                        if (!is_numeric($dbVal)) {
                            $escapedString = str_replace("'","''",$dbVal);
                            // don't quote SQL function names
                            if (mb_strtolower($escapedString) == 'now()') {
                                $dbValString = $escapedString;
                            } else {
                                $dbValString = '\''.$escapedString.'\'';
                            }
                        } else {
                            $dbValString = $dbVal;
                        }
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
                    // don't quote numbers or function names
                    if (!is_numeric($dbVal)) {
                        $escapedString = str_replace("'","''",$dbVal);
                        // don't quote SQL function names
                        if (mb_strtolower($escapedString) == 'now()') {
                            $dbValString = $escapedString;
                        } else {
                            $dbValString = '\''.$escapedString.'\'';
                        }
                    } else {
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
			if (is_object($qResult)) { @mysqli_free_result($qResult); }
		}
		return $returnValue;
	}
}
//EOF
