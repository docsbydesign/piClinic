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
require_once './api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

require_once './shared/ui_common.php';
require_once './uitext/uiSessionInfoText.php';

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
			// remove any old message values
			if (!empty($qsArray['msg'])) {
				unset($qsArray['msg']);
			}

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
	$logoutLink = '<a href="/endUiSession.php" title="'.TEXT_SESSION_LOGOUT_TITLE.'" >'.$logoutPrompt.'</a>';
}
if (isset($sessionToken) || isset($sessionUser)) {
	$sessionDiv = '<div id="sessionDiv" class="noprint">';
	$sessionSettings = '<a href="/staffAddEdit.php?UserEdit=true" ';
	$sessionSettings .= 'title="'.TEXT_SESSION_SETTINGS_TITLE.'">'.TEXT_SESSION_SETTINGS_LABEL. '</a>';
	if (isset($sessionToken)) {
		$sessionDiv .= '<p>'. $loggedInPrompt. '&nbsp;&nbsp;|&nbsp;&nbsp;';
		$sessionDiv .= $sessionSettings . '&nbsp;&nbsp;|&nbsp;&nbsp;' .$logoutLink. '</p>';
	}
	$sessionDiv .= '</div>';	
} else {
	// need to define this variable, even if it's empty
	$sessionDiv = '<div id="sessionDiv" style="display:none; ">&nbsp;</div>';	
}
//EOF
