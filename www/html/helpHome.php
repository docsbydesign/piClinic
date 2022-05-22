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
	<?= piClinicAppMenu(HELP_PAGE, $sessionInfo, $pageLanguage,__FILE__) ?>
	<div class="pageBody">
        <div class="clearFloat"></div>
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
					<?php
					$refDocs = [];
					$refDocs['EN'] = array();
					$refDocs['EN'][1] = array(
						"target"=>"icd10_1",
						"href"=>"/files/ICD10Volume1_en_2008.pdf",
						"text"=>"ICD-10,&nbsp;Volume&nbsp;1&nbsp;(Descriptions)");
					$refDocs['EN'][2] = array(
						"target"=>"icd10_2",
						"href"=>"/files/ICD10Volume2_en_2008.pdf",
						"text"=>"ICD-10,&nbsp;Volume&nbsp;2&nbsp;(Instructions)");
					$refDocs['EN'][3] = array(
						"target"=>"icd10_3",
						"href"=>"/files/ICD10Volume3_en_2008.pdf",
						"text"=>"ICD-10,&nbsp;Volume&nbsp;3&nbsp;(Index)");
					$refDocs['ES'] = [];
					$refDocs['ES'][1] = array(
						"target"=>"icd10_1",
						"href"=>"/files/CIE-10_2008_Chapter_1.pdf",
						"text"=>"CIE-10,&nbsp;Volumen&nbsp;1&nbsp;(Descripcciones&nbsp;de&nbsp;los&nbsp;codigos)");
					$refDocs['ES'][2] = array(
						"target"=>"icd10_2",
						"href"=>"/files/CIE-10_2008_Chapter_2.pdf",
						"text"=>"CIE-10,&nbsp;Volumen&nbsp;2&nbsp;(Manual&nbsp;de&nbsp;instrucciones)");
					$refDocs['ES'][3] = array(
						"target"=>"icd10_3",
						"href"=>"/files/CIE-10_2008_Chapter_3.pdf",
						"text"=>"CIE-10,&nbsp;Volumen&nbsp;3&nbsp;(Indice&nbsp;alfabético)");
					?>
                <table class="report">
                    <tr>
                        <th><?= TEXT_HELP_ENGLISH_DOC_HEADING ?></th>
                        <th><?= TEXT_HELP_SPANISH_DOC_HEADING ?></th>
                    </tr>
                    <tr>
						<?php
							foreach ($refDocs as $doclist) {
								echo '<td class="nowrap">';
								echo '  <ul>';
								foreach ($doclist as $doc) {
									$fileNotFound = 0;
									$docFilename = '/var/www/html'.$doc["href"];
									if (file_exists($docFilename)) {
										print ('    <li><a target="'.$doc["target"].'" href="'.$doc["href"].'">'.$doc["text"].'</a></li>');
									} else {
										$fileNotFound += 1;
									}
								}
								if ($fileNotFound > 0) {
									print ('    <li>Some files haven\'t been installed.</li>');
								}
								echo '  </ul>';
								echo '</td>';
						}
						?>
                    </tr>
                </table>
            </div>
        </div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $sessionInfo['parameters']); ?>
</html>
