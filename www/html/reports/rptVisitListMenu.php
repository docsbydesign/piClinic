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
/*
 *      Report files for a report name ReportName
 *          rptReportNameMenu.php = the file the describes the report for the report menu page
 *          rptReportNameHome.php = the file with the report's home page
 *          rptReportNameHomeText.php = the auto-generated file with the UI text strings (in /reports/uitext)
 *          rptReportNameXXX.php = Additional files as needed for this report (in /reports/support)
 *          rptReportNameXXXText.php = auto-generated UI Text files for additional files (in /reports/uitext)
 *
*	rptVisitListMenu.php
*		displays a list of visits selected by the search criteria
*
*/
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

assert (!empty($pageLanguage)); // must have a language defined
// add this report to the report menu array
require_once dirname(__FILE__).'/uitext/rptVisitListMenuText.php';

assert (isset($reportList));

// add this report to the list

$thisReport = array();
$thisReport['group'] = TEXT_REPORT_GROUP_QUERIES;  // report menu group
$thisReport['index'] = 1;  // report index of entry in group (menu sorted with lowest at top
$thisReport['linkText'] = TEXT_REPORT_LINK_TEXT_VISIT_LIST; // the text to display as the link to the report
$thisReport['linkURI'] = TEXT_REPORT_LINK_URI_VISIT_LIST; // the relative URI of the report home (if different than the default)
$thisReport['linkTitle'] = TEXT_REPORT_LINK_TITLE_VISIT_LIST; // the link's hovertext
$thisReport['linkDesc'] = TEXT_REPORT_LINK_DESC_VISIT_LIST; // the longer description text to follow the link

array_push($reportList, $thisReport);
//EOF