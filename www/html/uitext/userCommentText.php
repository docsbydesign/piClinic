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

// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
$apiCommonInclude = dirname(__FILE__).'/../api/api_common.php';
if (!file_exists($apiCommonInclude)) {
    // if not over one, try up one more directory and then over.
    $apiCommonInclude = dirname(__FILE__).'/../../api/api_common.php';
    if (!file_exists($apiCommonInclude)) {
        // if not over one, try up one more directory and then over.
        $apiCommonInclude = dirname(__FILE__).'/../../../api/api_common.php';
    }
}
require_once $apiCommonInclude;
exitIfCalledFromBrowser(__FILE__);

// Strings for UITEST_LANGUAGE
if ($pageLanguage == UITEST_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','TEXT_COMMENT_CANCEL',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','TEXT_COMMENT_PLACEHOLDER',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','TEXT_COMMENT_SUBMIT_BUTTON',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','TEXT_USER_COMMENTS_PAGE_TITLE',false); }
}
// Strings for UI_ENGLISH_LANGUAGE
if ($pageLanguage == UI_ENGLISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','Cancel',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','Enter your comment about the piClinic here',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','Submit',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','User comments',false); }
}
// Strings for UI_SPANISH_LANGUAGE
if ($pageLanguage == UI_SPANISH_LANGUAGE) {
	if (!defined('TEXT_COMMENT_CANCEL')) { define('TEXT_COMMENT_CANCEL','Cancelar',false); }
	if (!defined('TEXT_COMMENT_PLACEHOLDER')) { define('TEXT_COMMENT_PLACEHOLDER','Entrar su comentario del piClinic aquí',false); }
	if (!defined('TEXT_COMMENT_SUBMIT_BUTTON')) { define('TEXT_COMMENT_SUBMIT_BUTTON','Entregar',false); }
	if (!defined('TEXT_USER_COMMENTS_PAGE_TITLE')) { define('TEXT_USER_COMMENTS_PAGE_TITLE','Comentarios de los usuarios',false); }
}
//EOF
