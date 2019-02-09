<?php
/*
 *	Copyright (c) 2018, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */
/*
*	clinicDash
*		Starts an authenticated session
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once './shared/piClinicConfig.php';
require_once './shared/headTag.php';
require_once './shared/dbUtils.php';
require_once './api/api_common.php';
require_once './api/session_common.php';
require_once './api/session_post.php';
require_once './api/session_delete.php';
require_once './shared/profile.php';
require_once './shared/security.php';
require_once './shared/ui_common.php';

$sessionInfo = getUiSessionInfo();

// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['sessionLanguage'];
// *************** HTML starts here ********************
?>
<?= pageHtmlTag($sessionInfo['sessionLanguage']) ?>
<?= pageHeadTag('TEST Clinic Dash') ?>
<body>
<h1>Clinic Dash</h1>
<pre>
<?= json_encode(['apiUserToken' => $sessionInfo['token']], JSON_PRETTY_PRINT) ?><br>
<?= json_encode($_SESSION, JSON_PRETTY_PRINT) ?><br>
<?= json_encode(['pageLanguage' => $sessionInfo['sessionLanguage']]) ?>
</pre>
<p><a href="/endUiSession.php" title="Log out and end session">Log out</a></p>
</body>
</html>