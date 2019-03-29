<?php
/*
 *	Copyright (c) 2019, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
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
function piClinicAppMenu($thisPage, $pageLanguage, $hostFile) {
	// $pageLanguage is used by appMenuText.php.
	require_once dirname(__FILE__).'/../uitext/appMenuText.php';
	$menuItems = [];
	$menuItems[0] = array ('link' => HOME_PAGE, 'linkText' => TEXT_CLINIC_HOME, 'linkClass' => 'a_mainHome');
	$menuItems[1] = array ('link' => REPORT_PAGE, 'linkText' => TEXT_CLINIC_REPORTS, 'linkClass' => 'a_mainReports');
	$menuItems[2] = array ('link' => ADMIN_PAGE, 'linkText' => TEXT_CLINIC_ADMIN, 'linkClass' => 'a_mainAdmin');
	$menuItems[3] = array ('link' => HELP_PAGE, 'linkText' => TEXT_CLINIC_HELP, 'linkClass' => 'a_mainHelp');
	
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
	$menuDiv .= "</div></div><div style=\"clear: both;\"></div>";
	return $menuDiv;
}
?>
