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

// *** phpflow symbol 'process' ***

@include('verifyVal.php');    // verify input values

if (!$valverified) {
  if (include_once('common.php')) {
    log2file('fatal error: input values NOT verified!
              check if verifyVal.php is missing!');
  }
  die('fatal error: input values NOT verified! 
       check if verifyVal.php is missing!');
}

// preparation calculations
$textareax = floor($width * 0.98);
$textareay = floor($height * 0.98);
$maxcharsperline = floor($textareax / $fontwidth);
$maxlines = floor($textareay / $fontheight);

// calculate if the text fits into the space available
log2file('[drawPrc] text: '.$text);
$text = wordwrap($text, $maxcharsperline, "\n", 1);
$lines = explode("\n", $text);
if(count($lines) > $maxlines) {
  $lines = explode("\n", "TEXT TOO LONG");
}

// centralize the text vertically
$offsety = floor(floor($textareay / 2 - ceil((count($lines) / 2) * 
                       $fontheight)) * 0.98);

// offsets to mark the textarea
$x = round(($width - $textareax) / 2);
$y = round(($height - $textareay) / 2);


// end preparations


header("Content-type: image/png");

$process = ImageCreate($width + 1, $height + 1);
$white = ImageColorAllocate($process, 255, 255, 255);
$black = ImageColorAllocate($process, 0, 0, 0);

ImageRectangle($process, 0, 0, $width, $height, $black);

while (list($numl, $line) = each($lines)) {
  // centralize the text horizontally
  $offsetx = round(($textareax - (strlen($line) * $fontwidth)) / 2);
  ImageString($process, $font, $x + $offsetx, $y + $offsety, $line, $black);
  $offsety += $fontheight;
}    

ImagePNG($process);
ImageDestroy($process);

?>
