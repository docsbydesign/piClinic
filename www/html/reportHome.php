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
*	Report Dashboard (home page)
*
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once './shared/piClinicConfig.php';
require_once './shared/headTag.php';
require_once './shared/dbUtils.php';
require_once './api/api_common.php';
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
require_once './uitext/reportHomeText.php';

require './reports/rptGroups.php';

// buiild the list of reports
$reportFiles = array();
$reportUriRoot = '/reports';
$reportPath = './'.$reportUriRoot;
if ($handle = opendir($reportPath)) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        $matchVal = array();
        if (preg_match('/rpt(.+)Menu\.php/', $entry, $matchVal )) {
            // if ($entry != "." && $entry != "..") {
            array_push($reportFiles, ['file' => $reportPath.'/'.$entry, 'group'=>$matchVal,'home'=> $reportUriRoot.'/rpt'.$matchVal[1].'Home.php']);
        }
   }
   closedir($handle);
}

// get report info from the report files
//
$reportList = array();
foreach ($reportFiles as $report) {
    // include report info
    if (file_exists($report['file'])) {
        require_once ($report['file']);
    }
    if (!empty($reportList)) {
        if (empty($reportList[sizeof($reportList)-1]['linkURI'])) {
            $reportList[sizeof($reportList)-1]['linkURI'] = $report['home'];
        }
        $reportList[sizeof($reportList)-1]['groupIdx'] =
            getReportGroupIndex ($reportList[sizeof($reportList)-1]['group']);
    }
}

usort($reportList, "sortReportByGroupIndex");
// sort reports by group and then by index

function sortReportByGroupIndex($r1, $r2) {
     if ($r1['groupIdx'] == $r2['groupIdx']) {
        if($r1['index'] == $r2['index']) {
            return 0;
        }
        return ($r1['index'] < $r2['index']) ? -1 : 1;
    }
    return ($r1['groupIdx'] < $r2['groupIdx']) ? -1 : 1;
}

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
$referrerUrlOverride = NO_ACCESS_URL;
require './uiSessionInfo.php';
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_REPORT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(REPORT_PAGE, $sessionInfo, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
		<h1 class="pageHeading"><?= TEXT_REPORT_PAGE_TITLE ?></h1>
            <?php
                $ptGroup = -1;
                foreach($reportList as $report) {

                    if ($ptGroup != $report['group']) {
                        if ($ptGroup >= 0) {
                            echo '</ul>'."\n";
                        }
                        $ptGroup = $report['group'];
                        echo '<hr>'."\n";
                        echo '<ul class="optionList">'."\n";
                    }
                    echo '<li><a href="'.$report['linkURI'].'" title="'.$report['linkTitle'].'">'.$report['linkText'].'</a>:&nbsp;'.$report['linkDesc'].'</li>'."\n";
                }
                echo '</ul>'."\n";
                echo '<hr>'."\n";
            ?>
	</div>
</body>
</html>
