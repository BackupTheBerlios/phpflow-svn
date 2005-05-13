<?php

/* PHPflow is a program which provides browser-viewable flowcharts
 * Copyright (C)2005  by Alex T.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation Europe e.V., Villa Vogelsang, Antonienallee 1,
 * 45279 Essen, Germany.
*/

// *** phpflow symbol 'subprocess' ***

include("verifyVal.php"); // verify input values

if(!$valVerified)
{
	die("fatal error: input values NOT verified! check if valVerified.php is missing!");
}

// preparation calculations
$textareax = floor($width * 0.87);
$textareay = floor($height * 0.98);
$maxcharsperline = floor($textareax / $fontwidth);
$maxlines = floor($textareay / $fontheight);

// calculate if the text fits into the space available
log2file("[drawSub] text: ".$text);
$text = wordwrap($text, $maxcharsperline, "\n", 1);
$lines = explode("\n", $text);
if(count($lines) > $maxlines)
{
	$lines = explode("\n", "TEXT TOO LONG");
}

// centralize the text vertically
$offsety = floor(floor($textareay/2-ceil((count($lines)/2)*$fontheight)));

// offsets to mark the textarea
$x = round(($width - $textareax)/2);
$y = round(($height - $textareay)/2);

// end preparations

header("Content-type: image/png");

$subprocess = ImageCreate($width+1, $height+1);
$white = ImageColorAllocate($subprocess, 255, 255, 255);
$black = ImageColorAllocate($subprocess, 0, 0, 0);

ImageRectangle($subprocess, 0, 0, $width, $height, $black);
$points = array(round($width*0.05), 0, round($width*0.05), $height, round($width*0.95), 0, round($width*0.95), $height);
ImageLine($subprocess, $points[0], $points[1], $points[2], $points[3], $black);
ImageLine($subprocess, $points[4], $points[5], $points[6], $points[7], $black);

while (list($numl, $line) = each($lines))
{
	// centralize the text horizontally
	$offsetx = round(($textareax-(strlen($line)*$fontwidth))/2);
	ImageString($subprocess, $font, $x+$offsetx, $y+$offsety, $line, $black);
	$offsety += $fontheight;
}    

ImagePNG($subprocess);
ImageDestroy($subprocess);

?>