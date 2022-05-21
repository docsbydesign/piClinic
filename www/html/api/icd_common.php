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
/*******************
 *
 *	utility functions used by icd resource
 *
 *********************/
 /*
 *
 */
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

function makeIcdQueryStringFromRequestParameters ($requestParameters, $dbView = DB_TABLE_ICD10) {
	 // create query string for get operation
	$queryString = "SELECT * FROM `".
		$dbView. "` WHERE ";
	$paramCount = 0;
	// required parameters
	if (!empty($requestParameters['q'])) {
		$queryString .= "(`icd10code` LIKE '%".$requestParameters['q']."%' OR ";
		$queryString .= "`icd10index` LIKE '%".$requestParameters['q']."%' OR ";
		$queryString .= "`shortDescription` LIKE '%".$requestParameters['q']."%' ) AND ";
		$paramCount += 1;
	}

	if (!empty($requestParameters['qs'])) {
		$queryString .= "(`icd10code` LIKE '".$requestParameters['qs']."%' OR ";
		$queryString .= "`icd10index` LIKE '".$requestParameters['qs']."%' OR ";
		$queryString .= "`shortDescription` LIKE '".$requestParameters['qs']."%' ) AND ";
		$paramCount += 1;
	}

	if (!empty($requestParameters['ce'])) {
		$queryField = 'icd10index';
		if (strpos($requestParameters['ce'], '.') !== FALSE) {
			$queryField = 'icd10code';
		}
		$queryString .= "`". $queryField ."` = '".$requestParameters['ce']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['icd10index'])) {
		$queryField = 'icd10index';
		$queryString .= "`". $queryField ."` = '".$requestParameters['icd10index']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['c'])) {
		// default is to search the index field
        //  a decimal is assumed to be part of a regexp.
		$queryField = 'icd10index';
		$queryString .= "`". $queryField ."` REGEXP '".$requestParameters['c']."' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['t'])) {
		$queryString .= "`shortDescription` LIKE '%".$requestParameters['t']."%' AND ";
		$paramCount += 1;
	}
	if (!empty($requestParameters['mru'])) {
		// get the DB_LIMIT most recently used entries
		$queryString .= "`lastUsedDate` IS NOT NULL AND ";
		$paramCount += 1;
		// update/replace sort order to something appropriate for this option
		$requestParameters['sort'] = 'd';
	}
	// at least one of the preceding is required. If not present, there's no
	// point in continuing.
	if ($paramCount >= 1) {
		if (!empty($requestParameters['language'])) {
			$queryString .= "`language` = '".$requestParameters['language']."' AND ";
			$paramCount += 1;
		}
		$queryString .= "TRUE ";

		$sortOrder = 'icd10index';
		if (!empty($requestParameters['sort'])) {
			$sortStr = substr($requestParameters['sort'],0,1);
			switch ($sortStr) {
				case 't':
					$sortOrder = '`shortDescription` ASC';
					break;
				case 'd':
					$sortOrder = '`lastUsedDate` DESC';
					break;
				default:
					break;
			}

			$queryString .= " ORDER BY ".$sortOrder." ";
			$paramCount += 1;
		}
		$queryLimit = DB_QUERY_LIMIT;
		if (!empty($requestParameters['limit'])) {
			if (is_numeric($requestParameters['limit'])) {
				if (($requestParameters['limit'] > 0 ) &&
					($requestParameters['limit'] < 1000)) {
					$queryLimit = ' LIMIT '. $requestParameters['limit'];
				}
			}
		}

		$queryString .= $queryLimit.";";

	}
	if ($paramCount > 0) {
		return $queryString;
	} else {
		return '';
	}
 }
//EOF
