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
/*
*
*	return HTML tag
*
*
*/
 function pageHtmlTag($lang) {
	return "<!DOCTYPE html>\n".
		'<html xmlns="http://www.w3.org/1999/xhtml" lang="'.$lang.'">';
 }
/*
*
*	return HTML page head tag
*
*
*/
function pageHeadTag ($pageTitle, $sessionTimeout=1440, $additionalMarkup=null) {
	$headTag = "<head>\n";
	$headTag .= '<meta http-equiv="refresh" content="'.$sessionTimeout.'; url=/uihelp/endUiSession.php?msg='.MSG_SESSION_TIMEOUT.'">';
	$headTag .= '<title>'.$pageTitle."</title>\n";
	$headTag .= '<meta charset="utf-8"/>';
	$headTag .= '<link href="/assets/css/piClinic.css" rel="stylesheet">'."\n";
	if (!empty($additionalMarkup)) {
		$headTag .=  $additionalMarkup. "\n";
	}
	$headTag .= "</head>\n";
	return $headTag;
}
/*
*
*	return HTML for the piClinic banner
*
*/
function piClinicTag () {
	$piTag = "<div id=\"piClinicBannerDiv\" class=\"noprint\">\n";
	$piTag .= "<p class=\"piClinicBannerText\">piClinic</p>\n";
	$piTag	.= "</div>\n";
	return $piTag;
}
/*
*
*	piClinic menu to main functions
*		the link to the current page is disabled
*
*/
define('HOME_PAGE','/clinicDash.php',false);
define('REPORT_PAGE','/reportHome.php',false);
define('ADMIN_PAGE','/adminHome.php',false);
define('HELP_PAGE','/helpHome.php',false);
/**
 * @param $thisPage
 * @param $pageLanguage
 * @return string
 */
function piClinicAppMenu($thisPage, $session, $pageLanguage, $hostFile) {
	// $pageLanguage is used by appMenuText.php.
	require_once dirname(__FILE__).'/../uitext/appMenuText.php';
	$menuItems = array();
	$menuIdx = 0;
	if (checkUiSessionAccess (null, $session['token'], PAGE_ACCESS_READONLY)) {
		$menuItems[$menuIdx++] = array ('link' => HOME_PAGE, 'linkText' => TEXT_CLINIC_HOME, 'linkClass' => 'a_mainHome');
	}
	if (checkUiSessionAccess (null, $session['token'], PAGE_ACCESS_READONLY)) {
		$menuItems[$menuIdx++] = array ('link' => REPORT_PAGE, 'linkText' => TEXT_CLINIC_REPORTS, 'linkClass' => 'a_mainReports');
	}
	if (checkUiSessionAccess (null, $session['token'], PAGE_ACCESS_CLINIC)) {
		$menuItems[$menuIdx++] = array ('link' => ADMIN_PAGE, 'linkText' => TEXT_CLINIC_ADMIN, 'linkClass' => 'a_mainAdmin');
	}
	if (checkUiSessionAccess (null, $session['token'], PAGE_ACCESS_READONLY)) {
		$menuItems[$menuIdx++] = array ('link' => HELP_PAGE, 'linkText' => TEXT_CLINIC_HELP, 'linkClass' => 'a_mainHelp');
	}

	$menuDiv = "<div id=\"topLinkMenuDiv\" class=\"noprint\"><div id=\"appMenu\">\n";
	$menuDiv .= "<ul class=\"topLinkMenuList\">\n";
	$firstLink = true;
	foreach ($menuItems as $item) {
		// show each menu item as a link unless:
		//	$thisPage is defined AND it's the name of the menu item
		// home menu
		if (empty($thisPage) || (!empty($thisPage) && ($thisPage != $item['link']))) {
			$menuDiv .= "<li".($firstLink ? " class=\"firstLink\"" : "").">\n";
			$menuDiv .= "<a class=\"" .$item['linkClass']. "\" href=\"".$item['link'].createFromLink(FIRST_FROM_LINK_QP,$hostFile, $item['linkClass'])."\">".$item['linkText']."</a>\n";
			$menuDiv .= "</li>\n";
		} else {
			$menuDiv .= "<li class=\"currentPage".($firstLink ? " firstLink" : "")."\">\n";
			$menuDiv .= $item['linkText'];
			$menuDiv .= "</li>\n";
		}
		if ($firstLink) {
			$firstLink = false;
		}
	}
	$menuDiv .= "</ul></div>\n";
	$menuDiv .= "<div id=\"commentMenu\">\n";
	$menuDiv .= "<a class=\"a_mainComment\" href=\"/userComment.php".createFromLink(FIRST_FROM_LINK_QP,$hostFile, 'a_mainComment')."\" title=\"".
		TEXT_CLINIC_COMMENT_TITLE. "\">".TEXT_CLINIC_COMMENT."</a>\n";
	$menuDiv .= "</div></div><div class=\"clearFloat\"></div>";
	return $menuDiv;
}
?>
