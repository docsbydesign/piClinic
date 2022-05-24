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
*	userComment.php
*		Logs a user's comment about the piClinic
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
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once './uitext/userCommentText.php';

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_READONLY;
require('uiSessionInfo.php');

$referringPageUrl = '';
$returnUrl = '';
$referringPage = '';
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
	$referringPageUrl = cleanedRefererUrl();
	$referringPage = parse_url($referringPageUrl, PHP_URL_PATH);
	$returnUrl = $referringPageUrl;
} else {
	$returnUrl =  '/clinicDash.php'; //default: return is the home page
}
$username = $sessionInfo['username'];
$now = new DateTime();
$commentOpenTime = $now->format('Y-m-d H:i:s');

function writeTopicMenu ($lang) {
	$returnUrl = '/clinicDash.php'.createFromLink (FIRST_FROM_LINK_QP, __FILE__, 'Cancel'); //default: return is the home page
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], basename(__FILE__ )) === FALSE)  {
        $returnUrl = cleanedRefererUrl();
		$returnUrl .= createFromLink ( ((strpos($returnUrl, "?") !== FALSE) ? FROM_LINK_QP : FIRST_FROM_LINK_QP ) , __FILE__, 'Cancel');
	}
	$topicMenu = '<div id="topicMenuDiv">'."\n";
	$topicMenu .= '<ul class="topLinkMenuList">'."\n";
	$topicMenu .= '<li class="firstLink"><a href="'.$returnUrl.'">'.TEXT_COMMENT_CANCEL.'</a></li>'."\n";
	$topicMenu .= '</ul></div>'."\n";
	return $topicMenu;
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_USER_COMMENTS_PAGE_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
	<?= writeTopicMenu ($pageLanguage) ?>
	<div class="pageBody">
	<div id="commentDiv">
		<h1 class="pageHeading"><?= TEXT_USER_COMMENTS_PAGE_TITLE ?></h1>
		<form enctype="multipart/form-data" action="/uihelp/addComment.php" method="post">
			<p><label><?= TEXT_COMMENT_CANCEL ?>:</label><br>
			<textarea name="commentText" id="CommentTextEdit" class="commentTextEdit" placeholder="<?= TEXT_COMMENT_PLACEHOLDER ?>" maxlength="2048"></textarea>
			</p>
			<input type="hidden" id="CommentDateField" name="commentDate" value="<?= $commentOpenTime ?>">
			<input type="hidden" id="UsernameField" name="username" value="<?= $username ?>">
			<input type="hidden" id="ReferringUrlField" name="referringUrl" value="<?= $referringPageUrl ?>">
			<input type="hidden" id="ReferringPageField" name="referringPage" value="<?= $referringPage ?>">
			<input type="hidden" id="ReturnUrlField" name="returnUrl" value="<?= $returnUrl ?>">
            <input type="hidden" id="SubmitBtnTag" name="<?= FROM_LINK ?>" value="<?= createFromLink (null, __FILE__, 'btn_submit') ?>">
			<p><button class="btn_submit" type="submit"><?= TEXT_COMMENT_SUBMIT_BUTTON ?></button></p>
		</form>
	</div>
	</div>
</body>
