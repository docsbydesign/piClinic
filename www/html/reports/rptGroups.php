<?php
/**
 * Created by PhpStorm.
 * User: rbwatson
 * Date: 2/23/2019
 * Time: 2:06 PM
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
