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
/***************
 *
 *	Functions used by all API scripts
 *
 *********************/
require_once '../shared/piClinicConfig.php';

function logInvalidTokenError ($dbLink, $returnValue, $token, $actionName, $logData) {
    if (!is_array($returnValue)) {
        $returnValue = array();
    }
    $returnValue['contentType'] = CONTENT_TYPE_JSON;
    $returnValue['httpResponse'] = 400;
    $returnValue['httpReason']	= "Unable to access " . $actionName . " resources. Invalid token.";
    $logData['userToken'] = $token;
    $logData['logStatusCode'] = $returnValue['httpResponse'];
    $logData['logStatusMessage'] = $returnValue['httpReason'];
    writeEntryToLog ($dbLink, $logData);
    return $returnValue;
}


function exitIfCalledFromBrowser($scriptFile) {
    if (basename($scriptFile) == basename($_SERVER['SCRIPT_NAME'])) {
        // the file was not included so return an error
        http_response_code(404);
        header(CONTENT_TYPE_HEADER_HTML);
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
}
/*
* formats and outputs the info from the fields returned by a worker function
*	requires:
*		$results['httpResponse'] - the numeric resposnse value to return
*		$results['contentType'] - the MIME type of the data being returned
*		$results['httpReason'] - the textual explanation of the response
*
*	uses:
*		$results['data'] - the response value as an array (if available)
*
*/
function outputResults($results) {
	
	$outval = array();

	if (isset($results['format'])) {
		if ($results['format'] == 'image') {
			// look up  and output file 
			if (!isset($results['data'])) {
				// no image data so reconfigure response to server error
				$results ['httpResponse'] = 500;
				$results ['contentType'] = CONTENT_TYPE_JSON;
				$results ['httpReason'] = 'No image data returned from database.';
				// and then trickle through to the next block
			} else {
				// get image data object
				$imageData = $results['data'];
			}
			if ($results ['httpResponse'] == 200) {
				// get file path and open file
				if (file_exists($imageData['ImagePath'])) {
					$imageBytes = file_get_contents($imageData['ImagePath']);
					$imageSize = strlen($imageBytes);
					if ($imageSize > 0) {
						// write the image
						http_response_code($results['httpResponse']);
						header("Access-Control-Allow-Origin: *"); // allow CORS in browsers
						header("Response-String: ".$results['httpReason']);
						header('Content-type: '.$imageData['MimeType'].';');
						header("Content-Length: " . $imageSize);
						echo $imageBytes;
						/*
						* FUNCTION EXITS HERE
						*/			
						return;						
					} else {
						// empty image file
						// no image data so reconfigure response to server error
						$results ['httpResponse'] = 500;
						$results ['contentType'] = CONTENT_TYPE_JSON;
						$results ['httpReason'] = 'Image file is empty.';
						// and then trickle through to the next block
					}
				} else {
					// empty missing file
					// no file found so reconfigure response to server error
					$results ['httpResponse'] = 404;
					$results ['contentType'] = CONTENT_TYPE_JSON;
					$results ['httpReason'] = 'Image file is missing.';
					// and then trickle through to the next block
				}	
			}
			// if here, then a JSON object is being written
			$outval['format'] = $results['format'];
		}
	}
	// output a JSON object 
	http_response_code($results['httpResponse']);
	if (empty($results['contentType'])) {
        $results['contentType'] = CONTENT_TYPE_JSON;
    }
    header("content-type: ". $results['contentType']);
	header("Access-Control-Allow-Origin: *"); // allow CORS in browsers
	header("Response-String: ".$results['httpReason']);

	// else, if here, format a JSON object to return

    $outval['count'] = 0;
    if (isset($results['data'])) {
        $outval['data'] = $results['data'];
		if (isset($results['count'])) {
			$outval['count'] = $results['count'];
		}
	} else {
		$outval['data'] = '';
	}
	if (isset($results['error'])) {
		// copy http response header status values to error object
		$outval['error'] = $results['error'];
	}
	if (API_DEBUG_MODE) {
		if (isset($results['debug'])) {
			// copy http response header status values to error object
			$outval['debug'] = $results['debug'];
		}		
	}
	// create status field to put this info in the data response
	$outval['status'] = array();
	$outval['status']['httpResponse'] = $results['httpResponse'];
	$outval['status']['httpReason'] = $results['httpReason'];
	
	// and send it out
	echo json_encode($outval);
}
/*
*   Finds the data passed in to an API endpoint and formats it as an associative array
*/
function readRequestData() {
	// get the query paramater data from the request 
	// if the data is not in the the post form, try the query string
	$requestData = '';
	if (empty($requestData)) {
		if (isset($_GET) && (count($_GET) > 0)) {
			$requestData = $_GET;
			if (API_DEBUG_MODE) {
				$requestData['__source'] = 'GET';
			}
		}
	}
	if (empty($requestData)) {
		if (isset($_POST) && (count($_POST) > 0)) {
			$requestData = $_POST;
			if (API_DEBUG_MODE) {
				$requestData['__source'] = 'POST';
			}
		}
	}
	if (empty($requestData)) {
		$json = file_get_contents('php://input');
		if (isset($json)) {
			$requestData = json_decode($json, true);
			if (API_DEBUG_MODE) {
				$requestData['__source'] = 'input';
			}
		}
	}
	return $requestData;
}
/*
 *  Checks token string for correct format before sending to DB
 */
function validTokenString($token) {
    // Check for valid length
    if (strlen($token) != 36) {
        return false;
    }
    // Check for valid characters
    $illegalCharacters = '([\W])';
    if (preg_match($illegalCharacters, $token) == 1) {
        // an illegal character was found in the token
        return false;
    }
    // Check for valid underscore character placement
    $usLocs = [8,13,18,23];
    foreach ($usLocs as $usIdx) {
        if ($token[$usIdx] != '_'){
            return false;
        }
    }
    // passed the tests
    return true;
}

//EOF