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
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
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
