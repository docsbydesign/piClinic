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
*	Help home page
*
*   Query parameters:
 *      topic = 'home', 'workflow', 'icd'
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

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
require_once ('./uitext/helpHomeText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
require('uiSessionInfo.php');

// open DB or redirect to error URL1
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $pageLanguage, $errorUrl);
// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

// if here, the DB is open

// set a topic reference in case one is not in the request
$requestData['topic'] = (empty($requestData['topic']) ? 'home' : $requestData['topic']);

profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_PICLINIC_HELP_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(HELP_PAGE, $pageLanguage,__FILE__) ?>
	<div class="pageBody">
        <div style="clear: both;"></div>
        <div id="optionMenuDiv" class="noprint">
            <ul class="topLinkMenuList">
            <?php
                echo'<li class="firstLink close">';
                    echo TEXT_HELP_MENU_PROMPT.':';
                echo '</li>';
                echo'<li class="firstLink">';
                    $thisTopic = ($requestData['topic'] != 'home');
                    echo ($thisTopic ? '<a class="a_helpHome" href="/helpHome.php?topic=home">' : '').
                        TEXT_HELP_MENU_HOME .
                    ($thisTopic ? '</a>' : '');
                echo '</li>';
                echo'<li>';
                    $thisTopic = ($requestData['topic'] != 'workflow');
                    echo ($thisTopic ? '<a class="a_workflow" href="/helpHome.php?topic=workflow">' : '').
                        TEXT_HELP_MENU_WORKFLOW .
                        ($thisTopic ? '</a>' : '');
                echo '</li>';
                echo'<li>';
                    $thisTopic = ($requestData['topic'] != 'icd');
                    echo ($thisTopic ? '<a class="a_icd" href="/helpHome.php?topic=icd">' : '' ) .
                        TEXT_HELP_MENU_ICD  .
                        ($thisTopic ? '</a>' : '');
                echo '</li>';
            ?>
            </ul>
        </div>
        <div id="ReportList">
            <h1 class="pageHeading"><?= TEXT_PICLINIC_HELP_PAGE_TITLE ?></h1>
            <div id="HelpHome" class="<?= ($requestData['topic'] != 'home' ? 'hideDiv' : '') ?>">
                <p><?= TEXT_HELP_INTRODUCTION ?></p>
                <object type="image/svg+xml" data="/api/locImage.php?image=/assets/images/piClinic_Help_noKey.svg<?= '&language='.$pageLanguage ?>"></object>
            </div>
            <div id="HelpWorkflows" class="<?= ($requestData['topic'] != 'workflow' ? 'hideDiv' : '') ?>">
                <p><?= TEXT_HELP_WORKFLOW ?></p>
                <object type="image/svg+xml" data="/api/locImage.php?image=/assets/images/piClinic_Workflows.svg<?= '&language='.$pageLanguage ?>"></object>
            </div>
            <div id="HelpIcdRef" class="<?= ($requestData['topic'] != 'icd' ? 'hideDiv' : '') ?>">
                <p><?= TEXT_HELP_ICD ?></p>
                <table class="report">
                    <tr>
                        <th>English References</th>
                        <th>Referencias en Español</th>
                    </tr>
                    <tr>
                        <td class="nowrap">
                            <ul>
                                <li><a target="icd10_1" href="/files/ICD10Volume1_en_2008.pdf">ICD-10,&nbsp;Volume&nbsp;1&nbsp;(Descriptions)</a></li>
                                <li><a target="icd10_2" href="/files/ICD10Volume2_en_2008.pdf">ICD-10,&nbsp;Volume&nbsp;2&nbsp;(Instructions)</a></li>
                                <li><a target="icd10_3" href="/files/ICD10Volume3_en_2008.pdf">ICD-10,&nbsp;Volume&nbsp;3&nbsp;(Index)</a></li>
                            </ul>
                        </td>
                        <td class="nowrap">
                            <ul>
                                <li><a target="icd10_1" href="/files/CIE-10_2008_Chapter_1.pdf">CIE-10,&nbsp;Volumen&nbsp;1&nbsp;(Descripcciones&nbsp;de&nbsp;los&nbsp;codigos)</a></li>
                                <li><a target="icd10_2" href="/files/CIE-10_2008_Chapter_2.pdf">CIE-10,&nbsp;Volumen&nbsp;2&nbsp;(Manual&nbsp;de&nbsp;instrucciones)</a></li>
                                <li><a target="icd10_3" href="/files/CIE-10_2008_Chapter_3.pdf">CIE-10,&nbsp;Volumen&nbsp;3&nbsp;(Índice&nbsp;alfabético)</a></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $sessionInfo['parameters']); ?>
</html>