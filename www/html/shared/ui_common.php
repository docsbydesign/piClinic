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
/*******************
 *
 *	Functions used by UI scripts
 *
 *********************/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header('Content-Type: text/html; charset=utf-8');
	exit;
}
require_once dirname(__FILE__).'/piClinicConfig.php';
require_once dirname(__FILE__).'/../api/api_common.php';

// Constants used by UI files
if (!defined('UI_COMMON_CONSTANTS')) {
	define('UI_COMMON_CONSTANTS', 'ui_common_constants', false);
	define('RPT_SHOW_DATA', 2, false);
	define('RPT_SHOW_REPORT', 1, false);

    define('MSG_BACKUP_FAIL',"MSG_BACKUP_FAIL",false);
    define('MSG_DB_OPEN_ERROR',"DB_OPEN_ERROR", false);
    define('MSG_LOGIN_FAILURE',"LOGIN_FAILURE", false);
    define('MSG_NOT_CREATED',"NOT_CREATED", false);
    define('MSG_NOT_FOUND',"NOT_FOUND", false);
    define('MSG_NOT_UPDATED',"NOT_UPDATED", false);
    define('MSG_NO_ACCESS',"NO_ACCESS", false);
    define('MSG_PATIENT_ID_IN_USE',"PATIENT_ID_IN_USE", false);
    define('MSG_REQUIRED_FIELD_MISSING',"REQUIRED_FIELD_MISSING", false);
    define('MSG_SESSION_TIMEOUT',"SESSION_TIMEOUT",false);
    define('MSG_TOPIC_NOT_FOUND',"MSG_TOPIC_NOT_FOUND", false);
    define('MSG_UNSUPPORTED',"UNSUPPORTED", false);
    define('MSG_USER_NOT_FOUND',"USER_NOT_FOUND", false);
    define('MSG_VALIDATION_FAILED',"VALIDATION_FAILED",false);
}

/*
 *  returns information about the current UI session
 */
function getUiSessionInfo() {
    $sessionInfo = array('token'=> null, 'username' => null, 'sessionLanguage'=> UI_DEFAULT_LANGUAGE, 'pageLanguage'=> UI_DEFAULT_LANGUAGE);
    // Get the session info
    if (empty(session_id())){
        session_start();
    }
    if (!empty($_SESSION)) {
        $sessionInfo = $_SESSION;
    }
    // make sure we still have the required values
    if(empty($sessionInfo['sessionLanguage'])) {
        $sessionInfo['sessionLanguage'] = UI_DEFAULT_LANGUAGE;
    }
    if(empty($sessionInfo['pageLanguage'])) {
        $sessionInfo['pageLanguage'] = UI_DEFAULT_LANGUAGE;
    }
    $sessionInfo['parameters'] = readRequestData();
    $sessionInfo['pageLanguage'] = getUiLanguage($sessionInfo['parameters']);
    return $sessionInfo;
}
/*
*	returns the language to use for UI strings
*/
function checkLanguageSupported($langIdArg) {
	// check only the first two characters
	$langID = substr($langIdArg, 0, 2);
	// only select supported languages
	switch ($langID) {
		// only select supported languages
		case UITEST_LANGUAGE:
			return ( UITEST_LANGUAGE );
		case UI_ENGLISH_LANGUAGE:
			return ( UI_ENGLISH_LANGUAGE );
		case UI_SPANISH_LANGUAGE:
			return ( UI_SPANISH_LANGUAGE );
		// more languages here
		default:
			// no language recognized so return empty string below
			break;
	}
	return "";	// return an empty string if no match
}

function getUiLanguage ($requestData){
	// set page UI language
	//  follow this sequence:
	//		1. Check query parameter for lang=
    //      2. Check if the language is specified for the current session
	//		2. Check for "Accept-Language" header
	//			sequence through options until first recognized
	//		3. use UI_DEFAULT_LANGUAGE
	$pageLanguage = "";

	//		1. Check query parameter for lang=
	if (!empty($requestData['lang'])) {
		$pageLanguage = checkLanguageSupported($requestData['lang']);
	}
	//      2. Check the session language
    if (empty($pageLanguage)) {
        if (empty(session_id())) {
            session_start();
        }
        if (!empty($_SESSION) && !empty($_SESSION['sessionLanguage'])) {
            $pageLanguage = checkLanguageSupported($_SESSION['sessionLanguage']);
        }
    }

	//		3. Check for "Accept-Language" header
	//			sequence through options until one is found
	if (empty($pageLanguage)) {
		// Check for Accept-Language header
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$acceptLangs = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach ($acceptLangs as $thisLang) {
				$thisLangOptions = explode(';', $thisLang);
				$pageLanguage = checkLanguageSupported($thisLangOptions[0]);
				if (!empty($pageLanguage)) {
					break;
				}
			}
		}
	}

	//		4. use UI_DEFAULT_LANGUAGE
	if (empty($pageLanguage)) {
		$pageLanguage = UI_DEFAULT_LANGUAGE;
	}

	return $pageLanguage;
}
 /*
*	Returns the HTML to display the controls for date and date/time entry
*		$format can have these field letters: M, D, Y, T (monthm day, year, time) separated by "-"
*			T must be specified if $defaultTime is provided.
*
*/
function outputDateInputFields ($format, $dateFieldName, $defaultMonth, $defaultDay, $defaultYear, $defaultTime=null, $requiredField=false, $limitYear=true) {
	$htmlString = '';
	$dateSep = '&nbsp;-&nbsp;';
	$timeSep = '&nbsp;&nbsp;';
	// HTML strings
	$monthInput = '<input class="twoDigitNumeric" type="number" '.($requiredField ? 'required ' : '').' id="new'.$dateFieldName.'Month" name="'.$dateFieldName.'Month" placeholder="'.TEXT_DATE_MONTH_PLACEHOLDER.'" min="1" max="12" value="'.$defaultMonth.'">';
	$dayInput = '<input class="twoDigitNumeric" type="number" '.($requiredField ? 'required ' : '').' id="new'.$dateFieldName.'Day" name="'.$dateFieldName.'Day" placeholder="'.TEXT_DATE_DAY_PLACEHOLDER.'" min="1" max="31" value="'.$defaultDay.'">';
	$yearInput = '<input class="fourDigitNumeric" type="number" '.($requiredField ? 'required ' : '').' id="new'.$dateFieldName.'Year" name="'.$dateFieldName.'Year" placeholder="'.TEXT_DATE_YEAR_PLACEHOLDER.'" min="1900" '.($limitYear ? 'max="'.date("Y").'" ' : '').'value="'.$defaultYear.'">';
	if (isset($defaultTime)) {
		$timeInput = '<input class="timeNumeric" type="time" '.($requiredField ? 'required ' : '').' id="new'.$dateFieldName.'Time" name="'.$dateFieldName.'Time" value="'.$defaultTime.'">';
	}

	/* DEBUG only code
	if (false) {
        // +++ DEBUG
        $dbgVals = array();
        $dbgVals['format'] = $format;
        $dbgVals['defaultMonth'] = $defaultMonth;
        $dbgVals['defaultDay'] = $defaultDay;
        $dbgVals['defaultYear'] = $defaultYear;
        $dbgVals['defaultTime']  =$defaultTime;

        $htmlString = '<pre>'.json_encode($dbgVals, JSON_PRETTY_PRINT).'</pre>';
        // --- DEBUG
    }
	*/
	
	$dateFields = explode("-", $format);
	if (count($dateFields) < 3) {
		// default to "M-D-Y"
		$dateFields[0] = 'M';
		$dateFields[1] = 'D';
		$dateFields[2] = 'Y';
		if (isset($defaultTime)) {
			$dateFields[3] = 'T';
		}
	}

	foreach ($dateFields as $fieldType) {
		switch ($fieldType) {
			case 'M':
				if (!empty($htmlString)) {
					$htmlString .= $dateSep;
				}
				$htmlString .= $monthInput;
				break;
			case 'D':
				if (!empty($htmlString)) {
					$htmlString .= $dateSep;
				}
				$htmlString .= $dayInput;
				break;
			case 'Y':
				if (!empty($htmlString)) {
					$htmlString .= $dateSep;
				}
				$htmlString .= $yearInput;
				break;
			case 'T':
				if (!empty($htmlString)) {
					$htmlString .= $timeSep;
				}
				$htmlString .= $timeInput;
				break;
			default:
				break;
		}
	}

	return $htmlString;
}
/*
*	Create an input control to collect data in a form that will be used to update a data table
*/
function dbFieldTextInput ($requestData, $dbFieldName, $placeholderText, $requiredField=false,
							$autofocus=false, $type='text', $class='piClinicEdit', $charLimit=255, $autocomplete=null) {
	$inputTag = '<input type="'.$type.'" id="'.$dbFieldName.'InputId" name="'.$dbFieldName.'" '.
		'value="'.(!empty($requestData[$dbFieldName]) ? $requestData[$dbFieldName] : ''). '" '.
		($autofocus == true ? 'autofocus="autofocus" ' : '').
		(isset($autocomplete) ? 'autocomplete="'.$autocomplete.'" ' : '').
		'placeholder="'.$placeholderText.'" '. 'class="'. $class . '" '.($requiredField ? ' required ' : '').
		'maxlength="'.(string)$charLimit.'"'.
		'>';
	return $inputTag;
}
/*
*	compute age in years, months, days
*		earlyDate and lateDate are PHP timestamps
*/
function dateDiffYMD ($earlyDateArg, $lateDateArg) {
    // initialize the default return value
	$dateDiffValue = [];
    $dateDiffValue['years'] = 0;
    $dateDiffValue['months'] = 0;
    $dateDiffValue['days'] = 0;

	// check for invalid parameters and return an empty result if one is not valid
	if (empty($earlyDateArg)) return $dateDiffValue;
    if ($earlyDateArg == '0000-00-00 00:00:00') return $dateDiffValue;
    if (empty($lateDateArg)) return $dateDiffValue;
    if ($lateDateArg == '0000-00-00 00:00:00') return $dateDiffValue;

    // if the dates are the same, bail out here
	if ($lateDateArg == $earlyDateArg) {
		return $dateDiffValue;
	}
	// make sure the dates are such that late > early
	$lateDate = $lateDateArg;
	$earlyDate = $earlyDateArg;
	if ($lateDateArg < $earlyDateArg) {
		$lateDate = $earlyDateArg;
		$earlyDate = $lateDateArg;
	}

	// get each field from the dates
	$ldYr = date('Y', $lateDate);
	$ldMo = date('m', $lateDate);
	$ldDa = date('d', $lateDate);
	$edYr = date('Y', $earlyDate);
	$edMo = date('m', $earlyDate);
	$edDa = date('d', $earlyDate);

	// used to compute 'carry' values for arithmetic
	$daysInMonth = [0,31,28,31,30,31,30,31,31,30,31,30,31];
	// make the dates subtractible by adjusting each value such that
	// all the later date values are greater than the earlier date values
	if ($ldDa < $edDa) {
		// carry the days from the previous month so the
		//  lateDate days value is larger than the earlyDate's
		$ldMo -= 1;
		// if the prev. mo. is before Januaary
		if ($ldMo == 0){
			// set it to December of prev. year.
			$ldMo = 12;
			$ldYr -= 1;
		}
		// Get the days from the previous month
		// assuming it's not a leap year
		$ldDa += $daysInMonth[$ldMo];
		// but change it if the ldMo is Feb of a leap year
		if ((($ldYr % 4) == 0) && ($ldMo == 2)) {
			// but only if it's not a 'fake' (exception) leap year
			if (($ldYr % 400) != 0) {
				// then add the leap day
				$ldDa += 1;
			}
		}
	}
	if ($ldMo < $edMo) {
		// get the months from the previous year
		$ldMo += 12;
		$ldYr -=1;
	}

	$dateDiffValue['years'] = $ldYr - $edYr;
	$dateDiffValue['months'] = $ldMo - $edMo;
	$dateDiffValue['days'] = $ldDa - $edDa;
	return $dateDiffValue;
}

function formatAgeFromBirthdate ($birthdate, $today=null, $yrText='y', $moText='m', $dyText='d', $parens=true) {

    if (empty($today)) {
		$today = time();
	}

    $lParen = '';
    $rParen = '';
	if ($parens) {
	    $lParen = '(';
	    $rParen = ')';
    }

    if (!empty($birthdate)) {
        // formats the birthdate display based on specified date
        $ageYMD = dateDiffYMD(strtotime($birthdate), $today);
        if (!empty($ageYMD)) {
            if ($ageYMD['years'] >= 1) {
                return ($lParen . $ageYMD['years'] . $yrText . $rParen);
            }
            if ($ageYMD['months'] >= 1) {
                return ($lParen . $ageYMD['months'] . $moText . $rParen);
            }
            if ($ageYMD['days'] > 0) {
                return ($lParen . $ageYMD['days'] . $dyText . $rParen);
            }
            // else return a blank string
            return '';
        } else {
            return '';
        }
    } else {
        return "";
    }
}

function makeUrlWithQueryParams ($url, $qParams) {
    if (!empty($qParams) && is_array($qParams) && !empty($url)) {
        $qParamString = http_build_query($qParams);
        if (!empty($qParamString)) {
            return $url . '?' . $qParamString;
        } else {
            return $url;
        }
    } else {
        return $url;
    }
}

function cleanUrlQueryParams($queryParamArray) {
    $qpReturn = array();
    if (!empty($queryParamArray)) {
        $qpArray = $queryParamArray;
        unset($qpArray['msg']);
        unset($qpArray['lang']);
        unset($qpArray[FROM_LINK]);
        unset($qpArray['__source']);
    }
    return $qpArray;
}

function cleanTheUrl ($urlToClean, $newQpArray = null, $fromLinkValue = null) {
    if (!empty($urlToClean)) {
        $urlQP = array();
        $urlParts = explode('?',$urlToClean);
        if (!empty($urlParts[0])) {
            $urlRoot = $urlParts[0];
        } else {
            // unable to break the URL, so return it now.
            return $urlToClean;
        }
        if (!empty($urlParts[1])) {
            parse_str($urlParts[1], $urlQP);
            $urlQP = cleanUrlQueryParams($urlQP);
        }
        if (!empty($newQpArray) && is_array($newQpArray)) {
            foreach ($newQpArray as $key=>$value) {
                $urlQP[$key] = $value;
            }
        }
        if (!empty($fromLinkValue)) {
            $urlQP[FROM_LINK] = $fromLinkValue;
        }
        return (makeUrlWithQueryParams($urlRoot, $urlQP));
    }
    return $urlToClean;
}

function cleanedCallingUrl ($newQpArray = null, $fromLinkValue = null) {
    return cleanTheUrl ($_SERVER['REQUEST_URI'], $newQpArray, $fromLinkValue);
}

function cleanedRefererUrl ($fromLinkValue = null) {
    return cleanTheUrl ($_SERVER['HTTP_REFERER'], null, $fromLinkValue);
}

function createFromLink ($queryParamName, $filePath, $linkData) {
    // get file name
    // clean file path down to just the local path
    $linkQP = $filePath;
    if (substr($filePath,0,strlen(ROOT_DIR_PATH)) == ROOT_DIR_PATH) {
        $linkQP = substr($filePath, strlen(ROOT_DIR_PATH));
    }
    // add on the link ID info
    $linkQP .= FROM_LINK_SEP.$linkData;
    // and if there's a query parameter with the delimiter, add that, too.
    if (!empty($queryParamName)){
        $linkQP = $queryParamName . '=' . $linkQP;
    }
    // return the result
    return $linkQP;
}

function formatDbDate ($dateValue, $dateFormatString, $emptyDateString) {
    /*
     * $date value = SQL date string (YYYY-MM-DD HH:MM:SS)
     * $dateFormatSrring = PHP date format string
     */
    if (!empty($dateValue) && $dateValue != '0000-00-00 00:00:00') {
        return date($dateFormatString, strtotime($dateValue));
    } else {
        return $emptyDateString;
    }
}
//EOF
