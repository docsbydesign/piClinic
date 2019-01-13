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
		// default is to search the index field unless the parameter has a . in it,
		//  then search the code field.
		$queryField = 'icd10index';
		if (strpos($requestParameters['c'], '.') !== FALSE) {
			$queryField = 'icd10code';
		}
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