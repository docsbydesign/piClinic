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
*	adminLogViewer
*
*		Finds the currently open log files and display the selected one in the UI
*		
*			query parameters:
*				logFile = filename of log file
*				log = the type of log file to view (latest opened by default)
*				date = the date of the log file to open (error log opened by default)
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
require_once './api/log_common.php';
require_once './api/log_get.php';

$profileData = [];
profileLogStart ($profileData);

// get the current session info (if any)
$sessionInfo = getUiSessionInfo();
$requestData = $sessionInfo['parameters'];
// $pageLanguage is used by the UI string include files.
$pageLanguage = $sessionInfo['pageLanguage'];
// load the strings for the page language
//	assumes $pageLanguage contains a valid language
require_once ('./uitext/adminLogViewerText.php');

// open session variables and check for access to this page
$pageAccessRequired = PAGE_ACCESS_ADMIN;
require('uiSessionInfo.php');

// open DB or redirect to error URL
$errorUrl = '/clinicDash.php';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI($sessionInfo['parameters'], $errorUrl);

// log any open workflows.
$logProcessed = logWorkflow($sessionInfo, __FILE__, $dbLink);

$logRequestData = filterRequestToValidParameters ($requestData);
$logResults = _log_get($dbLink, $sessionInfo['token'], $logRequestData);
$logFilters = _log_get($dbLink, $sessionInfo['token'], ['fieldOpts' => 'true']);

$logData = array();
if ($logResults['count'] == 1) {
    $logData[0] = $logResults['data'];
} else {
    $logData = $logResults['data'];
} // else if 0, leave empty

$filterOptions = array();
if ($logFilters['count'] > 1) {
    // sort the array
    $logFilterList = $logFilters['data'];
    array_multisort(array_column($logFilterList, 'fieldName'),  SORT_NATURAL | SORT_FLAG_CASE ,
        array_column($logFilterList, 'fieldValue'), SORT_NATURAL | SORT_FLAG_CASE,
        $logFilterList);
    // create the select box lists
    foreach ($logFilterList as $row) {
        if (empty($filterOptions[$row['fieldName']])){
            // if there's no option list for this field, create an empty one
            $filterOptions[$row['fieldName']] = [];
        }
        array_push($filterOptions[$row['fieldName']],$row['fieldValue']);
    }
}

?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag(TEXT_ADMIN_LOG_VIEWER_TITLE) ?>
<body>
	<?= piClinicTag(); ?>
	<?= $sessionDiv /* defined in uiSessionInfo.php above */ ?>
	<?php require ('uiErrorMessage.php') ?>
	<?= piClinicAppMenu(null,$sessionInfo, $pageLanguage, __FILE__) ?>
	<div class="pageBody">
	<h1 class="pageHeading"><?= TEXT_ADMIN_LOG_VIEWER_TITLE ?></h1>
	<div id="logSelectorDiv" class="noprint ">
		<form enctype="application/x-www-form-urlencoded" action="/adminLogViewer.php"  method="get">
            <div class="infoBlock">
            <label class="close"><?= TEXT_LOG_ACTION_FIELD_NAME_SELECT ?></label>:
                <select name="logAction" <?= empty($filterOptions['logAction']) ? 'disabled="disabled"' : '' ?>>
                    <?php
                        if (!empty($filterOptions['logAction'])) {
                            echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                            foreach ($filterOptions['logAction'] as $optionValue) {
                                if (empty($optionValue)) { continue; }
                                echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                            }
                        }
                    ?>
                </select>

            <label class="close"><?= TEXT_LOG_CLASS_FIELD_NAME_SELECT ?></label>:
            <select name="logClass" <?= empty($filterOptions['logClass']) ? 'disabled="disabled"' : '' ?>>
                <?php
                if (!empty($filterOptions['logClass'])) {
                    echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                    foreach ($filterOptions['logClass'] as $optionValue) {
                        if (empty($optionValue)) { continue; }
                        echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                    }
                }
                ?>
            </select>

            <label class="close"><?= TEXT_LOG_STATUS_FIELD_NAME_SELECT ?></label>:
            <select name="logStatusCode" <?= empty($filterOptions['logStatusCode']) ? 'disabled="disabled"' : '' ?>>
                <?php
                if (!empty($filterOptions['logStatusCode'])) {
                    echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                    foreach ($filterOptions['logStatusCode'] as $optionValue) {
                        if (empty($optionValue)) { continue; }
                        echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                    }
                }
                ?>
            </select>

            <label class="close"><?= TEXT_LOG_TABLE_FIELD_NAME_SELECT ?></label>:
            <select name="logTable" <?= empty($filterOptions['logTable']) ? 'disabled="disabled"' : '' ?>>
                <?php
                if (!empty($filterOptions['logTable'])) {
                    echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                    foreach ($filterOptions['logTable'] as $optionValue) {
                        if (empty($optionValue)) { continue; }
                        echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                    }
                }
                ?>
            </select>

            <label class="close"><?= TEXT_SOURCE_MODULE_FIELD_NAME_SELECT ?></label>:
            <select name="sourceModule" <?= empty($filterOptions['sourceModule']) ? 'disabled="disabled"' : '' ?>>
                <?php
                if (!empty($filterOptions['sourceModule'])) {
                    echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                    foreach ($filterOptions['sourceModule'] as $optionValue) {
                        if (empty($optionValue)) { continue; }
                        echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                    }
                }
                ?>
            </select>

            <label class="close"><?= TEXT_USER_TOKEN_FIELD_NAME_SELECT ?></label>:
            <select name="userToken" <?= empty($filterOptions['userToken']) ? 'disabled="disabled"' : '' ?>>
                <?php
                if (!empty($filterOptions['userToken'])) {
                    echo '<option value="">'.TEXT_BLANK_OPTION_SELECT.'</option>'."\n";
                    foreach ($filterOptions['userToken'] as $optionValue) {
                        if (empty($optionValue)) { continue; }
                        echo '<option value="'.$optionValue.'">'.$optionValue.'</option>'."\n";
                    }
                }
                ?>
            </select>
            </div>
            <div style="clear: both;"></div>
            <div class="infoBlock">
                <p><button type="submit"><?= TEXT_LOG_FILE_SUBMIT_BUTTON ?></button></p>
            </div>
        </form>
    </div>
    <div style="clear: both;"></div>
    <hr>
    <div class="infoBlock">
        <table>
            <tr>
                <th><?= TEXT_LOG_DISPLAY_ID ?></th>
                <th><?= TEXT_LOG_DISPLAY_SOURCE ?></th>
                <th><?= TEXT_LOG_DISPLAY_TOKEN ?></th>
                <th><?= TEXT_LOG_DISPLAY_CLASS ?></th>
                <th><?= TEXT_LOG_DISPLAY_TABLE ?></th>
                <th><?= TEXT_LOG_DISPLAY_ACTION ?></th>
                <th><?= TEXT_LOG_DISPLAY_QUERY_STRING ?></th>
                <th><?= TEXT_LOG_DISPLAY_BEFORE_DATA ?></th>
                <th><?= TEXT_LOG_DISPLAY_AFTER_DATA ?></th>
                <th><?= TEXT_LOG_DISPLAY_STATUS_CODE ?></th>
                <th><?= TEXT_LOG_DISPLAY_STATUS_MSG ?></th>
                <th><?= TEXT_LOG_DISPLAY_CREATED_DATE ?></th>
            </tr>
            <?php
                if (empty($logData)) {
                    echo '<tr colspan="11">TEXT_NO_LOG_DATA</tr>';
                } else {
                    foreach ($logData as $logEntry) {
                        echo '<tr>';
                            echo '<td>'. $logEntry['logId'] ."</td>\n";
                            echo '<td>'. substr($logEntry['sourceModule'], strlen('/var/www/html') )."</td>\n";
                            echo '<td>'. $logEntry['userToken'] ."</td>\n";
                            echo '<td>'. $logEntry['logClass'] ."</td>\n";
                            echo '<td>'. $logEntry['logTable'] ."</td>\n";
                            echo '<td>'. $logEntry['logAction'] ."</td>\n";
                            echo '<td>'. $logEntry['logQueryString'] ."</td>\n";
                            echo '<td><pre>'. json_encode(json_decode($logEntry['logBeforeData']), JSON_PRETTY_PRINT) ."</pre></td>\n";
                            echo '<td><pre>'. json_encode(json_decode($logEntry['logAfterData']), JSON_PRETTY_PRINT) ."</pre></td>\n";
                            echo '<td>'. $logEntry['logStatusCode'] ."</td>\n";
                            echo '<td>'. $logEntry['logStatusMessage'] ."</td>\n";
                            echo '<td>'. $logEntry['createdDate'] ."</td>\n";
                        echo '</tr>';
                    }
                }
            ?>
        </table>
    </div>
    <div style="clear: both;"></div>

	</div>
</body>
</html>