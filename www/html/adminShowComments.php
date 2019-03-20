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
*	List of most recent 100 comments entered into system
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
require_once './api/comment_common.php';
require_once './api/comment_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
$requestData = $sessionInfo['parameters'];
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/adminShowCommentsText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_ADMIN;
require('uiSessionInfo.php');

// open DB or redirect to error URL
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$commentResponse = null;
// get the list of comments, sorted by descending time
$commentQueryString = '';
$commentResponse = _comment_get ($dbLink, $sessionInfo['token'], $commentQueryString);

profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_COMMENT_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<div id="CommentListDiv">
<?php
	if (isset($commentResponse)) {
		if ($commentResponse['httpResponse'] != 200){
			echo ('<h1 class="pageHeading">'.TEXT_NO_COMMENT_RECORDS.'</h1>');
		} else {
			echo ('<h1 class="pageHeading" id="commentListHeading">'.TEXT_COMMENT_LIST_HEAD.'</h1>');
			$commentList = [];
			if ($commentResponse['count'] == 1) {
				// there's only one so make it an array element 
				// so the rest of the code works
				$commentList[0] = $commentResponse['data'];
			} else {
				$commentList = $commentResponse['data'];
			}
			// check to see if they are currently in the clinic
			if (!empty($commentList)) {
				$headerShown = false;
				foreach ($commentList as $comment) {
					if (!$headerShown) {
						echo ('<table class="piClinicList"><tr>');
						echo ('<th>'.TEXT_COMMENT_LIST_HEAD_CREATEDATE.'</th>');
						echo ('<th>'.TEXT_COMMENT_LIST_HEAD_USERNAME.'</th>');
						echo ('<th>'.TEXT_COMMENT_LIST_HEAD_PAGE.'</th>');								
						echo ('<th>'.TEXT_COMMENT_LIST_HEAD_TEXT.'</th>');
						echo ('</tr>');
						$headerShown = true;
					}
					echo ('<tr>');
					// createdDate
					echo ('<td class="nowrap'.(empty($comment['createdDate']) ? ' inactive': '').'">'.(empty($comment['createdDate']) ? TEXT_VALUE_NOT_SET : $comment['createdDate'] ).'</td>');
					// username 
					echo ('<td class="nowrap">'.(empty($comment['username']) ? TEXT_VALUE_NOT_SET : $comment['username'] ).'</td>');
					// Referring Page
					echo ('<td class="nowrap'.(empty($comment['referringPage']) ? ' inactive': '').'">'.(empty($comment['referringPage']) ? TEXT_VALUE_NOT_SET : $comment['referringPage'] ).'</td>');
					// Text
					echo ('<td class="'.(empty($comment['commentText']) ? ' inactive': '').'">'.(empty($comment['commentText']) ? TEXT_VALUE_NOT_SET : $comment['commentText'] ).'</td>');						
					echo ('</tr>');					
				}
				if ($headerShown) {
					echo ('</table>');
				}
			}
		} 
	} else {
		echo ('<h1 class="pageHeading">'.TEXT_NO_COMMENT_RECORDS.'</h1>');
	}

	@mysqli_close($dbLink);
?>
	</div>
	</div>
</body>
<?php $result = profileLogClose($profileData, __FILE__, $requestData); ?>
</html>
