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
