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
 *	adminHelpAddEdit.php
 *		Logs a user's comment about the piClinic
 *
 *
 *      Expects:
 *          topicID (help topicID)  for EDIT, or missing to ADD
 *
 *
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files 
require_once './shared/ui_common.php';
require_once './shared/headTag.php';
require_once './shared/security.php';

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once './uitext/adminHelpAddEditText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_CLINIC;
require('./uiSessionInfo.php');

// open DB or redirect to error URL
$returnUrl =
    $errorUrl = '/adminHome.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$helpTopicToEdit = array();
$helpTopicToEdit['topicID'] = '';
$helpTopicToEdit['refPage'] = '';

$msg='';
if (!empty($sessionInfo['parameters']['topicID'])) {
    // get the data for the selected topic
    $getHelpString = 'SELECT * FROM `'.DB_TABLE_HELP.'` WHERE `topicID` = \''.
        $sessionInfo['parameters']['topicID'] . '\';';
    $getResult = getDbRecords($dbLink, $getHelpString);
    if ($getResult['httpResponse'] == 200) {
        // organize by supported languages
        $helpContent = array();
        if ($getResult['count'] == 1) {
            $helpContent[0] = $getResult['data'];
        } else {
            $helpContent = $getResult['data'];
        }
        $supportedLanguages = getSupportedUiLanguages();
        foreach ($supportedLanguages as $thisLanguage => $langName) {
            // find this language in the returned data
            foreach ($helpContent as $helpTopicContent) {
                if ($helpTopicContent['language'] == $thisLanguage) {
                    // found the matching entry so initialize the local variable for the UI
                    $helpTopicToEdit[$thisLanguage]['helpID'] = $helpTopicContent['helpID'];
                    $helpTopicToEdit['topicID'] = $helpTopicContent['topicID'];
                    $helpTopicToEdit['refPage'] = $helpTopicContent['refPage'];
                    $helpTopicToEdit[$thisLanguage]['helpText'] = $helpTopicContent['helpText'];
                    $helpTopicToEdit[$thisLanguage]['lastChangeBy'] = $sessionInfo['username'];
                    break;
                }
            }
        }
    } else {
        // get error info for debug
        $requestData['msg'] = MSG_TOPIC_NOT_FOUND;
    }
}

// make sure every supported language has an entry
$supportedLanguages = getSupportedUiLanguages();
foreach ($supportedLanguages as $thisLanguage => $langName) {
    // initialize an empty structure if not present
    if (empty($helpTopicToEdit[$thisLanguage])) {
        $helpTopicToEdit[$thisLanguage]['helpID'] = '';
        $helpTopicToEdit[$thisLanguage]['helpText'] = '';
        $helpTopicToEdit[$thisLanguage]['lastChangeBy'] = $sessionInfo['username'];
    }
}

function writeTopicMenu ($returnUrl, $lang) {
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a href="'.$returnUrl.'">'.TEXT_HELP_EDIT_CANCEL.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_HELP_TOPIC_EDIT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
	<?= writeTopicMenu ($returnUrl, $pageLanguage) ?>
	<div class="pageBody">
	<div id="commentDiv">
		<h1 class="pageHeading"><?= TEXT_HELP_TOPIC_EDIT_PAGE_TITLE ?></h1>
		<form enctype="multipart/form-data" action="/uihelp/addHelp.php" method="post">
            <?php
                echo '<div class="infoBlock">';
                echo '<div class="dataBlock">';
                echo '<label class="close">'.TEXT_HELP_TOPIC_ID .':</label><input type="text" name="topic[topicID]" value="' .$helpTopicToEdit['topicID']. '">';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<label class="close">'.TEXT_HELP_REF_PAGE .':</label><input type="text" name="topic[refPage]" value="' .$helpTopicToEdit['refPage']. '">';
                echo '</div>';
                echo '<input type="hidden" name="topic[lastChangeBy]" value="'.$sessionInfo['username'].'">';
                echo '<input type="hidden" name="topic[errorUrl]" value="/adminHelpAddEdit.php">';
                echo '<input type="hidden" name="topic[successUrl]" value="/adminHome.php">';
                echo '</div>';
        	    echo '<div class="clearFloat"></div>';
                echo '<div class="infoBlock">';
                echo '<hr>';
                foreach ($supportedLanguages as $thisLanguage => $langName) {
                    echo '<div class="dataBlock">';
                    echo '<label class="close">'.TEXT_HELP_TOPIC_TEXT .'&nbsp;('.$langName.'):</label><br>';
                    echo '<textarea class="commentTextEdit" name="topic['.$thisLanguage.'][helpText]" placeholder="" maxlength="4096">'.
                            $helpTopicToEdit[$thisLanguage]['helpText'].'</textarea>';
                    echo '<input type="hidden" name="topic['.$thisLanguage.'][helpID]" value="'.$helpTopicToEdit[$thisLanguage]['helpID'].'">';
                    echo '</div>';
                }
                echo '</div>';
                echo '<div class="clearFloat"></div>';
                echo '<hr>';
            ?>
			<p><button class="btn_submit" type="submit"><?= TEXT_HELP_SUBMIT_BUTTON ?></button></p>
		</form>
	</div>
	</div>
<div>
    <pre>
    <?= json_encode($helpTopicToEdit, JSON_PRETTY_PRINT) ?>
    <?= json_encode($helpContent, JSON_PRETTY_PRINT) ?>
    <br>
    <?= $getHelpString ?>
    </pre>
</div>
</body>
</html>