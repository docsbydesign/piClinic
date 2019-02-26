<?php
/*
 *
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
*	textFileUtils
*		functions to export data as a downloadable text file (tsv, csv)
*
*/
/*
	Expects:
		$file		a file handle opened for write access
		$header		a 1-dimension array of strings that contains the 
						field names in the order they will be displayed
		$delimiter	a single character that will be used to separate the fields
						a tab is used by default

*/
function writeTextHeader ($file, $header, $delimiter="\t") {
	$lineTerm = "\r\n";
	$outstring = implode ($delimiter, $header).$lineTerm;
	return fwrite ($file, $outstring);
}

/*
	Expects:
		$file		a file handle opened for write access
		$header		a 1-dimension array of strings that contains the 
						field names in the order they will be displayed
		$record		an associative array that contains the data to write identified
						by the fields listed in $header
		$delimiter	a single character that will be used to separate the fields
						a tab is used by default

*/
function writeTextRecord ($file, $header, $record, $delimiter="\t") {
	$lineTerm = "\r\n";
	$outstring = '';
	$firstField = 1;
	// write the values in the order found in the header,
	//   if no header, then write them as they appear in the array
	if ($header != NULL) {
		foreach ($header as $field) {
			if (!$firstField) {
				$outstring .= $delimiter;
			} else {
				$firstField = 0;
			}
			if (isset($record[$field])) {
				// if a delimiter is found in the value, wrap the value in quotes
				$outstring .= (strpos($record[$field], $delimiter) !== FALSE) ? '"'.$record[$field].'"' : $record[$field];
			} 		
		}
	} else {
		foreach ($record as $field) {
			if (!$firstField) {
				$outstring .= $delimiter;
			} else {
				$firstField = 0;
			}
			if (isset($field)) {
				// if a delimiter is found in the value, wrap the value in quotes
				$outstring .= (strpos($field, $delimiter) !== FALSE) ? '"'.$field.'"' : $field;
			} 		
		}		
	}
	$outstring .= $lineTerm;
	return fwrite ($file, $outstring);
}
