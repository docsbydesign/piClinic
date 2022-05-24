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
*	Display smart usability-test instructions
*
*/
// set charset header
header('Content-type: text/html; charset=utf-8');
// include files
require_once dirname(__FILE__).'/../shared/piClinicConfig.php';
require_once dirname(__FILE__).'/../shared/headTag.php';
require_once dirname(__FILE__).'/../shared/dbUtils.php';
require_once dirname(__FILE__).'/../api/api_common.php';
require_once dirname(__FILE__).'/../shared/profile.php';
require_once dirname(__FILE__).'/../shared/security.php';
require_once dirname(__FILE__).'/../shared/ui_common.php';

$profileData = [];
profileLogStart ($profileData);

$pageLanguage = 'en';

// open DB or redirect to error URL
$errorUrl = 'https://piclinic.org';  // where to go in case the DB can't be opened.
$dbLink = _openDBforUI(null, $errorUrl);

$testInfo = [];

// get existing patient that is not currently in the clinic
$ptQueryString = "SELECT `firstName`, `lastName`,  `lastName2` FROM `patient` WHERE `patientID` = ".rand(1,100).";";
$ptResult = getDbRecords($dbLink, $ptQueryString);

if ($ptResult['count'] == 1) {
    $testInfo['ptName'] = $ptResult['data']['firstName'];
    $testInfo['ptName'] .= ' '. $ptResult['data']['lastName'];
    if (!empty($ptResult['data']['lastName'])) {
        $testInfo['ptName'] .= ' '. $ptResult['data']['lastName2'];
    }
} else {
    $testInfo['ptName'] = 'NO PATIENT NAME';
}

// get existing patient that is not currently in the clinic


// get a doctor who is registered in the clinic
$drQueryString = "SELECT `firstName`, `lastName` FROM `staff` WHERE `position` = 'DoctorGeneral';";
$drResult = getDbRecords($dbLink, $drQueryString);

if ($drResult['count'] > 0) {
    $drIdx = rand(0,$drResult['count']-1);
    $testInfo['drName'] = $drResult['data'][$drIdx]['firstName'];
    $testInfo['drName'] .= ' '. $drResult['data'][$drIdx]['lastName'];
} else {
    $testInfo['drName'] = 'NO DOCTOR NAME';
}

// get a diagnosis to apply to this patient
$icdQueryString = "SELECT `shortDescription` FROM `icd10` WHERE `language`='en' AND `icd10index` LIKE '___' LIMIT 1000;";
$icdResult = getDbRecords($dbLink, $icdQueryString);

if ($icdResult['count'] > 0) {
    $icdIdx = rand(0,$icdResult['count']-1);
    $testInfo['icdName'] = $icdResult['data'][$icdIdx]['shortDescription'];
} else {
    $testInfo['icdName'] = 'NO DIAGNOSIS';
}

$testInfo['newDiag'] = (rand(0,1) ? "(N) new" : "(S) subsequent");

// get a date that had a visit
$dateQueryString = "SELECT distinct DATE_FORMAT(`dateTimeIn`,'%M %d, %Y') AS `reportDate` FROM `visit` WHERE 1 order by `reportDate` desc;";
$dateResult = getDbRecords($dbLink, $dateQueryString);

if ($dateResult['count'] > 0) {
    $dateIdx = rand(0,$dateResult['count']-1);
    $testInfo['date'] = $dateResult['data'][$dateIdx]['reportDate'];
} else {
    $testInfo['date'] = 'NO DATE';
}
// get a new phone number for the patient
$testInfo['phNumber'] = '504-'.str_pad(strval(rand(2001,9999)),4,'0').'-'.str_pad(strval(rand(0,9999)),4,'0');

profileLogCheckpoint($profileData,'CODE_COMPLETE');
?>
<?= pageHtmlTag($pageLanguage) ?>
<?= pageHeadTag('Usability Test No. 1') ?>
<body>
	<?= piClinicTag(); ?>
	<div class="pageBody">
        <h1>piClinic Online Usability Test</h1>

        <p><strong>Context:</strong> You are an administrator at a small medical clinic. One of your jobs is to enter patient information into the system.</p>
        <p>After you complete the test, if you get stuck and can't continue, or should you choose to leave early, please enter a comment that you have ended the testing.</p>
        <p>Log in to the site at: <a href="https://dev.piclinic.org/clinicLogin.php" target="_blank">https://dev.piclinic.org/clinicLogin.php</a>
        <blockquote>
        <strong>User:</strong> OnlineTest<br>
        <strong>Pass:</strong> onlineTest!
        </blockquote>
        <p>Click “Comment” in the top right hand corner, and add a comment with: <strong>&lt;your name&gt; - starting</strong></p>
        <h2>Usability Test Tasks</h2>
        <p>Complete as many of these tasks as you can.</p>
        <ol>
            <li>A patient has just walked in and has an appointment scheduled for today. The patient’s name is <span style="text-decoration: underline"><?= $testInfo['ptName'] ?></span>. Search for this patient on the site.</li>
            <li>Update this patient’s primary phone number to <span style="text-decoration: underline"><?= $testInfo['phNumber'] ?></span>.</li>
            <li>The patient claims to feel generally ill and would like to be see the doctor. Admit the patient into the clinic. This visit type will be an <strong>Outpatient</strong>. The doctor the patient will see is <span style="text-decoration: underline"><?= $testInfo['drName'] ?></span>. Go back to the dashboard.</li>
            <li>Some time has passed and <?= $testInfo['ptName'] ?>, the patient you saw earlier, has seen the doctor and is now ready to leave. Discharge the patient from the clinic. The doctor diagnosed the patient with a <span style="text-decoration: underline"><?= $testInfo['newDiag'] ?></span> diagnosis of <span style="text-decoration: underline"><?= $testInfo['icdName'] ?></span>.</li>
            <li>One of the doctors needs to see which patients he cared for on <span style="text-decoration: underline"><?= $testInfo['date'] ?></span>. Pull up the daily outpatient log for that day. </li>
        </ol>
        <p>After you have finished testing, add a comment with- <strong>&lt;your name&gt; - finished.</strong>, and any feedback from your experience.</p>
	</div>
    <div class="noshow">
    <pre>
        <?= $ptQueryString ?><br>
        <?= json_encode($ptResult, JSON_PRETTY_PRINT) ?>
        <?= $drQueryString ?><br>
        <?= json_encode($drResult, JSON_PRETTY_PRINT) ?>
        <?= $dateQueryString ?><br>
        <?= json_encode($dateResult, JSON_PRETTY_PRINT) ?>
        <?= json_encode($testInfo, JSON_PRETTY_PRINT) ?>
    </pre>
    </div>
</body>
<?php @mysqli_close($dbLink); ?>
</html>
