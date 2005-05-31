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

// *** phpflow symbol 'arrow' ***

@include('verifyVal.php');    // verify input values

if (!$valverified) {
  if (include_once('common.php')) {
    log2file('fatal error: input values NOT verified! 
              check if verifyVal.php is missing!');
  }
  die('fatal error: input values NOT verified! 
       check if verifyVal.php is missing!');
}

// verify additional input vars
isset($_GET['s']) ? $start = $_GET['s'] : (isset($_GET['start']) ? 
                    $start = $_GET['start'] : die('start not given!'));
isset($_GET['e']) ? $end = $_GET['e'] : (isset($_GET['end']) ? 
                    $end = $_GET['end'] : die('end not given!'));
if ($end == $start) {
  die('start and end mustn\'t be the same!');
}

// preparation calculations
switch ($start) {
case 'top':
    $startx = round($width / 2);
    $starty = 0;
    break;

case 'left':
    $startx = 0;
    $starty = round($height / 2);
    break;

case 'right':
    $startx = $width;
    $starty = round($height / 2);
    break;

case 'bottom':
    $startx = round($width / 2);
    $starty = $height;
    break;

default:
    die('start must be top, left, right or bottom!');
    break;
}

if($text != '_-NONE') {
  $textareax = floor($width * 0.98);
  $textareay = floor($height * 0.98);
  $maxcharsperline = floor($textareax / $fontwidth);
  $maxlines = floor($textareay / $fontheight);

  // calculate if the text fits into the space available
  $text = wordwrap($text, $maxcharsperline, "\n", 1);
  $lines = explode("\n", $text);
  if(count($lines) > $maxlines) {
    $lines = explode("\n", 'TEXT TOO LONG');
  }

  // centralize the text vertically
  $offsety = floor(floor($textareay / 2 - ceil((count($lines) / 2) * 
                         $fontheight)) * 0.98);

  // offsets to mark the textarea
  $x = round(($width - $textareax) / 2);
  $y = round(($height - $textareay) / 2);
}

switch ($end) {
case 'top':
    $endx = round($width / 2);
    $endy = 0;
    $points = array($endx, $endy, $endx + 5, $endy + 9, $endx - 5, $endy + 9);
    break;

case 'left':
    $endx = 0;
    $endy = round($height / 2);
    $points = array($endx, $endy, $endx + 9, $endy + 5, $endx + 9, $endy - 5);
    break;

case 'right':
    $endx = $width;
    $endy = round($height / 2);
    $points = array($endx, $endy, $endx - 9, $endy + 5, $endx - 9, $endy - 5);
    break;

case 'bottom':
    $endx = round($width / 2);
    $endy = $height;
    $points = array($endx, $endy, $endx + 5, $endy - 9, $endx - 5, $endy - 9);
    break;

default:
    die('end must be top, left, right or bottom!');
    break;
}


// end preparations


header("Content-type: image/png");

$arrow = ImageCreate($width + 1, $height + 1);
$white = ImageColorAllocate($arrow, 255, 255, 255);
$black = ImageColorAllocate($arrow, 0, 0, 0);

ImageLine($arrow, $startx, $starty, round($width / 2), round($height / 2), 
          $black);
ImageLine($arrow, round($width / 2), round($height / 2), $endx, $endy, $black);
ImageFilledPolygon($arrow, $points, 3, $black);

if($text != '_-NONE') {
  while (list($numl, $line) = each($lines)) {
    // centralize the text horizontally
    $offsetx = round(($textareax - (strlen($line) * $fontwidth)) / 2);
    ImageString($arrow, $font, $x + $offsetx - $fontwidth, 
                $y + $offsety - $fontheight / 2, $line, $black);
    $offsety += $fontheight;
  }    
}

ImagePNG($arrow);
ImageDestroy($arrow);

?>
