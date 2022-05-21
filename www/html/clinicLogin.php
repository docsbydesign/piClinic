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
*	clinicLogin
*		Starts an authenticated session
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once('./api/api_common.php');
require_once('./shared/ui_common.php');
require_once('./shared/headTag.php');
// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
$requestData = $sessionInfo['parameters'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/clinicLoginText.php');

$loginData = array();
$loginData['username'] = '';
$loginData['password'] = '';
?>
<?= pageHtmlTag($sessionInfo['sessionLanguage']) ?>
<?= pageHeadTag(TEXT_CLINIC_LOGIN_PAGE_TITLE, 86400) ?>
<body>
	<?= piClinicTag(); ?>
	<div id="sessionMenu"><p>&nbsp;</p><!-- placeholder for this page --></div>
	<?php require ('uiErrorMessage.php') ?>
	<div id="loginDiv">
		<p class="piClinicPageTitle"><?= TEXT_CLINIC_LOGIN_PAGE_TITLE ?></p>
		<form enctype="multipart/form-data" action="/uihelp/startUiSession.php" method="post">
			<p><label class="piClinicFieldLabel"><?= TEXT_LOGIN_USERNAME ?>:</label><br>
				<?= dbFieldTextInput ($loginData, "username", TEXT_LOGIN_USERNAME_PLACEHOLDER, false, true,
					'text', 'piClinicEdit', 64, 'username' ) ?>
			</p>
			<p><label class="piClinicFieldLabel"><?= TEXT_LOGIN_PASSWORD ?>:</label><br>
				<?= dbFieldTextInput ($loginData, "password", TEXT_LOGIN_PASSWORD_PLACEHOLDER, true, false, 'password', 'piClinicEdit', 128, 'current-password') ?>
			</p>
			<?= (!empty($sessionInfo['sessionLanguage']) ? '<input type="hidden" id="langField" name="lang" value="'.$sessionInfo['sessionLanguage'].'" >': "") ?>
			<p class="piClinicButton"><button type="submit"><?= TEXT_LOGIN_SUBMIT_BUTTON ?></button></p>
		</form>
	</div>
</body>
</html>
