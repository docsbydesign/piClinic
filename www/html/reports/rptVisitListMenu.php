<?php
/*
 *
 * Copyright 2020 by Robert B. Watson
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
