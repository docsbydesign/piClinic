<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 2/23/2019
 * Time: 2:06 PM
 */
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
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

// assumes $pageLanguage
require_once  dirname(__FILE__).'/./uitext/rptGroupsText.php';

function getReportGroupIndex ($group) {
    //This is the order that report groups will be sorted on the page. Leftmost is at the top
    $reportGroups = [TEXT_REPORT_GROUP_PATIENTS,TEXT_REPORT_GROUP_FINANCE,TEXT_REPORT_GROUP_QUERIES];

    for ($groupIdx = 0; $groupIdx < sizeof($reportGroups); $groupIdx++ ) {
        if ($reportGroups[$groupIdx] == $group) {
            return $groupIdx;
        }
    }
    return -1; // index not found
}
