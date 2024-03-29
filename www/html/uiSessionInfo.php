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
//  EXPECTS:
//		An open and valid session
//		$sessionInfo array that includes valid:
//			token
//			pageLanguage
//		$pageLanguage
//		$pageAccessRequired
//		$referrerUrlOverride (optional)
//
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/./api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

require_once dirname(__FILE__).'/./shared/ui_common.php';
require_once dirname(__FILE__).'/./uitext/uiSessionInfoText.php';


function getSupportedUiLanguages() {
    $langArray = array();
    $langArray[UI_ENGLISH_LANGUAGE] = TEXT_STAFF_LANGUAGE_OPTION_ENGLISH;
    $langArray[UI_SPANISH_LANGUAGE] = TEXT_STAFF_LANGUAGE_OPTION_SPANISH;
    return $langArray;
}


// open session variables
$sessionToken = $sessionInfo['token'];
$sessionUser = $sessionInfo['username'];

if (empty($pageAccessRequired)) {
	// default access is any authorized user
	$pageAccessRequired = PAGE_ACCESS_READONLY;
}
// check access and continue if valid or redirect if not
if (!checkUiSessionAccess (null, $sessionToken, $pageAccessRequired)) {
	// this assumes they had permission to access the referring page....
	if (empty($_SERVER['HTTP_REFERER'])) {
		// if no referrer, go to default no access page
		$noAccessUrl = makeUrlWithQueryParams(NO_ACCESS_URL,['msg'=>MSG_NO_ACCESS]);
	} else {
		// if a preferred error url is provided use that, otherwise use the referrer
		if (isset($referrerUrlOverride)) {
			$referrerUrl = $referrerUrlOverride;
			$referrerQS = $_SERVER['QUERY_STRING']; // use the current query string
		} else {
			// split the referrer into URL & QS
			$referrerUrl = '';
			$referrerQS = '';
			$refUrlParts = explode('?',$_SERVER['HTTP_REFERER']);
			if (isset($refUrlParts[0])){
				$referrerUrl = $refUrlParts[0];
			}
			if (isset($refUrlParts[1])){
				$referrerQS = $refUrlParts[1];
			}
		}
		// test to see if the referrer is the current page. If so, empty it.
		if (basename($referrerUrl) == basename($_SERVER['SCRIPT_NAME'])) {
			// somehow, we're referring to the current page so clear
			// the referrerUrl and let the rest of the logic do the right thing
			//  if the user does not have access to this page and the
			//  current page is the error URL, the browser will get stuck in a loop.
			$referrerUrl = '';
		}
		if (empty($referrerUrl)) {
			// use default URL
			$noAccessUrl = makeUrlWithQueryParams(NO_ACCESS_URL,['msg'=>MSG_NO_ACCESS]);
		} else {
			// convert the query string into an associative array
			$qsArray = parse_url ( $referrerQS, PHP_URL_QUERY );
            // set the correct error message
            $qsArray['msg'] = MSG_NO_ACCESS;
			$noAccessUrl = makeUrlWithQueryParams($referrerUrl, $qsArray);
		}
	}
	// log the error
	$retVal = [];
	// database not opened.
	$retVal['contentType'] = 'Content-Type: application/json; charset=utf-8';
	$retVal['httpResponse'] = 403;
	$retVal['httpReason']   = "Access not granted to this page.";
	if (isset($dbInfo)) {
		$retVal['error'] = $dbInfo;
	}
	// use script name for this call and not __FILE__ to show
	//  the page being requested, not this script
	logUiError($requestData, $retVal, $_SERVER['SCRIPT_NAME'], $sessionUser);
	// this message overrides any preceding error
	header ('Location: '.$noAccessUrl);
	return;
} // else continue to access the page
$sessionDiv = '';
$logoutLink = '<!-- no session token found -->';
if (isset($sessionUser)) {
	$loggedInPrompt = TEXT_SESSION_NAME_PROMPT.': '.$sessionUser;
	$logoutPrompt = TEXT_SESSION_LOGOUT_LINK;
} else {
	$logoutPrompt = TEXT_SESSION_LOGOUT_LINK;
}
if (isset($sessionToken)){
	$logoutLink = '<a href="/uihelp/endUiSession.php" title="'.TEXT_SESSION_LOGOUT_TITLE.'" >'.$logoutPrompt.'</a>';
}
// make language option list.
$langArray = getSupportedUiLanguages();
$langLinkText = '';

foreach ($langArray as $langId => $langText) {
    if ($langId != $sessionInfo['pageLanguage']) {
        // don't create a link to show the current language
        $pageLangUrl = cleanedCallingUrl (['lang'=>$langId]);
        if (!empty($langLinkText)) {
            // add a separator
            $langLinkText .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $langLinkText .= '<a href="'.$pageLangUrl.'" class="langLing">'.$langText.'</a>';
    }
}

if (isset($sessionToken) || isset($sessionUser)) {
	$sessionDiv = '<div id="sessionMenu" class="noprint">';
	// build session info menu
    $sessionDiv .= '<div id="sessionDiv" class="noprint">';
	$sessionSettings = '<a href="/staffAddEdit.php?useredit=true" ';
	$sessionSettings .= 'title="'.TEXT_SESSION_SETTINGS_TITLE.'">'.TEXT_SESSION_SETTINGS_LABEL. '</a>';
	if (isset($sessionToken)) {
		$sessionDiv .= '<p>'. $loggedInPrompt. '&nbsp;&nbsp;|&nbsp;&nbsp;';
		$sessionDiv .= $sessionSettings . '&nbsp;&nbsp;|&nbsp;&nbsp;' .$logoutLink. '</p>';
	}
	$sessionDiv .= '</div>';
	// build session page language
    $sessionDiv .= '<div id="sessionPageMenu" class="noprint"><p>'.TEXT_SHOW_LANGUAGE_PROMPT.': '.$langLinkText.'</p></div>';
    $sessionDiv .= '</div>';
    $sessionDiv .= '<div class="clearFloat"></div>';
} else {
	// need to define this variable, even if it's empty
	$sessionDiv = '<div id="sessionMenu" class="noshow">&nbsp;</div>';
    $sessionDiv .= '<div class="clearFloat"></div>';
}
//EOF
