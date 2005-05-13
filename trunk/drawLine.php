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

// *** phpflow symbol 'line' ***

include("verifyVal.php"); // verify input values

if(!$valVerified)
{
	die("fatal error: input values NOT verified! check if valVerified.php is missing!");
}

// verify additional input vars
isset($_GET['s']) ? $start = $_GET['s'] : ( isset($_GET['start']) ? $start = $_GET['start'] : die("start not given !") );
isset($_GET['e']) ? $end = $_GET['e'] : ( isset($_GET['end']) ? $end = $_GET['end'] : die("end not given !") );
if ($end == $start)
{
    die("start and end mustn't be the same !");
}

// preparation calculations
switch ($start)
{
    case "top":
    $startx = round($width/2);
    $starty = 0;
    break;
    case "left":
    $startx = 0;
    $starty = round($height/2);
    break;
    case "right":
    $startx = $width;
    $starty = round($height/2);
    break;
    case "bottom":
    $startx = round($width/2);
    $starty = $height;
    break;
    default:
    die("start must be top, left, right or bottom!");
    break;
}

if($text != "_-NONE")
{
	$textareax = floor($width * 0.98);
	$textareay = floor($height * 0.98);
	$maxcharsperline = floor($textareax / $fontwidth);
	$maxlines = floor($textareay / $fontheight);

	// calculate if the text fits into the space available
	$text = wordwrap($text, $maxcharsperline, "\n", 1);
	$lines = explode("\n", $text);
	if(count($lines) > $maxlines)
	{
		$lines = explode("\n", "TEXT TOO LONG");
	}

	// centralize the text vertically
	$offsety = floor(floor($textareay/2-ceil((count($lines)/2)*$fontheight))*0.98);

	// offsets to mark the textarea
	$x = round(($width - $textareax)/2);
	$y = round(($height - $textareay)/2);
}

switch ($end)
{
    case "top":
    $endx = round($width/2);
    $endy = 0;
    break;
    case "left":
    $endx = 0;
    $endy = round($height/2);
    break;
    case "right":
    $endx = $width;
    $endy = round($height/2);
    break;
    case "bottom":
    $endx = round($width/2);
    $endy = $height;
    break;
    default:
    die("end must be top, left, right or bottom!");
    break;
}

// end preparations

header("Content-type: image/png");

$line = ImageCreate($width+1, $height+1);
$white = ImageColorAllocate($line, 255, 255, 255);
$black = ImageColorAllocate($line, 0, 0, 0);

ImageLine($line, $startx, $starty, round($width/2), round($height/2), $black);
ImageLine($line, round($width/2), round($height/2), $endx, $endy, $black);

if($text != "_-NONE")
{
	while (list($numl, $txtline) = each($lines))
	{
		// centralize the text horizontally
		$offsetx = round(($textareax-(strlen($txtline)*$fontwidth))/2);
		ImageString($line, $font, $x+$offsetx+$fontwidth, $y+$offsety-$fontheight/2, $txtline, $black);
		$offsety += $fontheight;
	}    
}

ImagePNG($line);
ImageDestroy($line);

?>