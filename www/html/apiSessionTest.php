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
//  Tests the caller's access token for authorization to call this API
//  EXPECTS: 
//		$sessionToken
//		$pageAccessRequired
//
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
	// the file was not included so return an error
	http_response_code(404);
	header(CONTENT_TYPE_HEADER_HTML);
	exit;	
}

// open session variables
$sessionToken = null;
$sessionUser = null;

function tokenHasAccess ($token, $pageAccessRequired = PAGE_ACCESS_READONLY) {

if (!checkUiSessionAccess (null, $sessionToken, $pageAccessRequired)) {
	// this assumes they had permission to access the referring page....
	if (empty($_SERVER['HTTP_REFERER'])) {
		// if no referrer, go to default no access page
		$noAccessUrl = NO_ACCESS_URL.'?msg=NO_ACCESS'.
			(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "");			
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
			$noAccessUrl = NO_ACCESS_URL.'?msg=NO_ACCESS'.
				(!empty($requestData['lang']) ? "&lang=".$pageLanguage : "");			
		} else {
			// add error message to referrer
			parse_str($referrerQS, $referrerQP);
			$queryString = '';
			$msgSet = false;
			foreach ($referrerQP as $key => $value) {
				if (!empty($queryString)) {
					$queryString .= '&';
				}
				if ($key == 'msg') {
					$queryString .= 'msg=NO_ACCESS';
					$msgSet = true;
				} else {
					$queryString .= $key.'='.$value;
				}
			}
			if (!$msgSet) {
				if (!empty($queryString)) {
					$queryString .= '&';
				}
				$queryString .= 'msg=NO_ACCESS';
				$msgSet = true;
			}
			$noAccessUrl = $referrerUrl.'?'.$queryString;
		}		
	}
	// log the error
	$retVal = [];
	// database not opened.
	$retVal['contentType'] = CONTENT_TYPE_JSON;
	$retVal['httpResponse'] = 401;
	$retVal['httpReason']   = "Access not granted to this page.";
	if (isset($dbInfo)) {
		$retVal['error'] = $dbInfo;
	}
	// use script name for this call and not __FILE__ to show
	//  the page being requested, not this script
	logUiError($requestData, $retVal, $_SERVER['SCRIPT_NAME'], $sessionUser);
	// this message overrides any preceding error
	$requestData['msg'] = 'DB_OPEN_ERROR';
	
	header ('Location: '.$noAccessUrl);
	return;
} // else continue to access the page
$sessionDiv = '';
$logoutLink = '<!-- no session token found -->';
if (isset($sessionUser)) {
	$loggedInPrompt = SESSION_NAME_PROMPT.': '.$sessionUser;
	$logoutPrompt = SESSION_LOGOUT_LINK;
} else {
	$logoutPrompt = SESSION_LOGOUT_LINK;
}
if (isset($sessionToken)){
	$logoutLink = '<a href="/endUiSession.php?Token='.$sessionToken.
	(!empty($requestData['lang']) ? '&lang='.$pageLanguage : '').
	'" title="'.SESSION_LOGOUT_TITLE.'" >'.$logoutPrompt.'</a>';	
}
if (isset($sessionToken) || isset($sessionUser)) {
	$sessionDiv = '<div id="sessionDiv" class="noprint">';
	$sessionSettings = '<a href="/staffAddEdit.php?UserEdit=true'.(!empty($requestData['lang']) ? '&lang='.$pageLanguage : '').'" ';
	$sessionSettings .= 'title="'.SESSION_SETTINGS_TITLE.'">'.SESSION_SETTINGS_LABEL. '</a>';
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
