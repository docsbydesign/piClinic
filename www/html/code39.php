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
 *	Function that creates and returns a code39 barcode image as a .png
 *
 *********************/
require_once './shared/piClinicConfig.php';
require_once './api/api_common.php';

$rData = readRequestData();
$displayText = 'BarCode missing';
$displayHeight = 60; // default sizes
$displayWidthUnit = 1; // default unit width
if (!empty($rData['code'])){
	$displayText = $rData['code'];
}
if (!empty($rData['y'])){
	$yArg = $rData['y'];
	if (is_numeric($yArg)) {
		$displayHeight = (int)$yArg;
	}
}
if (!empty($rData['w'])){
	$wArg = $rData['w'];
	if (is_numeric($wArg)) {
		$displayWidthUnit = (int)$wArg;
	}
}
// returns an image of the barcode that represents the $string 
// $string should be a Code 39-compliant string to encode.
// Any characters not in the array below will be discarded.
// 	Adapted from https://gist.github.com/BHSPitMonkey/570679 2/2/2018
function image_code39_barcode($string, $height, $unitWidth) {
	$yMargin = 2; // margin across top and bottom
	$xMargin = 5; // margin on left and right
	// validate parameters
	if ($height < 3 * $yMargin) {
		$height = 3 * $yMargin;
	}
	if ($height > 2000 * $yMargin) {
		$height = 2000 * $yMargin;
	}
	if ($unitWidth < 1) {
		$unitWidth = 1;
	}
	if ($unitWidth > 10) {
		$unitWidth = 10;
	}	
	$bcImgHeight = $height; // hard-coded height
	$bcBar = $unitWidth; // basic unit of width
	$bcImgWidth = 2100 * $bcBar ;	// wide enough for a long one (maybe 30 char?)
	$bcNarrow = $bcBar; // narrow bar width
	$bcWide = $bcBar * 3; // wide bar width = 3x narrow
	$bcXPos = $xMargin; // x pos to start drawing
	$bcX2Pos = $bcXPos; // x pos to end drawing
	// color code table for each character
	$code39 = array(
		'0'=>'NnNwWnWnN',	'1'=>'WnNwNnNnW',
		'2'=>'NnWwNnNnW',	'3'=>'WnWwNnNnN',
		'4'=>'NnNwWnNnW',	'5'=>'WnNwWnNnN',
		'6'=>'NnWwWnNnN',	'7'=>'NnNwNnWnW',
		'8'=>'WnNwNnWnN',	'9'=>'NnWwNnWnN',
		'A'=>'WnNnNwNnW',	'B'=>'NnWnNwNnW',
		'C'=>'WnWnNwNnN',	'D'=>'NnNnWwNnW',
		'E'=>'WnNnWwNnN',	'F'=>'NnWnWwNnN',
		'G'=>'NnNnNwWnW',	'H'=>'WnNnNwWnN',
		'I'=>'NnWnNwWnN',	'J'=>'NnNnWwWnN',
		'K'=>'WnNnNnNwW',	'L'=>'NnWnNnNwW',
		'M'=>'WnWnNnNwN',	'N'=>'NnNnWnNwW',
		'O'=>'WnNnWnNwN',	'P'=>'NnWnWnNwN',
		'Q'=>'NnNnNnWwW',	'R'=>'WnNnNnWwN',
		'S'=>'NnWnNnWwN',	'T'=>'NnNnWnWwN',
		'U'=>'WwNnNnNnW',	'V'=>'NwWnNnNnW',
		'W'=>'WwWnNnNnN',	'X'=>'NwNnWnNnW',
		'Y'=>'WwNnWnNnN',	'Z'=>'NwWnWnNnN',
		'-'=>'NwNnNnWnW',	'.'=>'WwNnNnWnN',
		' '=>'NwWnNnWnN',	'$'=>'NwNwNwNnN',
		'/'=>'NwNwNnNwN',	'+'=>'NwNnNwNwN',
		'%'=>'NnNwNwNwN',	'*'=>'NwNnWnWnN');
	
	// create graphic space
	$bcImg = imagecreatetruecolor($bcImgWidth,$bcImgHeight);
	// define color resources
	$white = imagecolorallocate($bcImg,0xFF,0xFF,0xFF);
	$black = imagecolorallocate($bcImg,0x00,0x00,0x00);
	// fill graphic space with white
	imagefilledrectangle($bcImg,0,0,$bcImgWidth,$bcImgHeight,$white);

	// Split the string up into its separate characters and iterate over them
	//  and add * required by code 39 to be at beginning and end of bars
	if (substr($string,0,1)!='*') $string = "*$string";
	if (substr($string,-1,1)!='*') $string = "$string*";
	
	$string = strtoupper($string); // basic code 39 supports only upper case letters
	$chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
	foreach ($chars as $char) {
		// Split this character's encoding string up into its separate characters and iterate
		$pattern = preg_split('//', $code39[$char], -1, PREG_SPLIT_NO_EMPTY);
		foreach ($pattern as $bar) {
			$barColor = 0;
			// Determine bar's appearance
			switch ($bar) {
				case 'W':
					$bcX2Pos = $bcXPos + $bcWide;
					$barColor = $black;
					break;
					
				case 'N':
					$bcX2Pos = $bcXPos + $bcNarrow;
					$barColor = $black;
					break;
					
				case 'w':
					$bcX2Pos = $bcXPos + $bcWide;
					$barColor = $white;
					break;
					
				case 'n':
					$bcX2Pos = $bcXPos + $bcNarrow;
					$barColor = $white;
					break;
			}
			// draw bar
			imagefilledrectangle($bcImg, $bcXPos, $yMargin, $bcX2Pos, $bcImgHeight-$yMargin, $barColor);
			$bcXPos = $bcX2Pos;
		}
		// a narrow white Separator between characters
		$bcX2Pos += $bcNarrow;
		imagefilledrectangle($bcImg, $bcXPos, $yMargin, $bcX2Pos, $bcImgHeight-$yMargin, $white);
		$bcXPos = $bcX2Pos;
	}

	$bcImg = imagecrop($bcImg, ['x' => 0, 'y' => 0, 'width' => $bcXPos+$xMargin, 'height' => $bcImgHeight]);
	ob_start(); //Stdout --> buffer
	imagepng($bcImg,null,5);
	$pngString = ob_get_contents(); //store stdout in $imgString
	ob_end_clean(); //clear buffer		
	// Free the png image memory
	imagedestroy($bcImg);
	return $pngString;
}

header("Content-Type: image/png");
echo (image_code39_barcode($displayText, $displayHeight, $displayWidthUnit));
return;
?>