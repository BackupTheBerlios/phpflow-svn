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

// *** phpflow symbol 'decision' ***

include("verifyVal.php"); // verify input values

if(!$valVerified)
{
	die("fatal error: input values NOT verified! check if valVerified.php is missing!");
}

function notzero($var)
{
	if($var==0) return 0; else return 1;
}

function varwordwrap($str,$maxlen)
{
	if(strlen($str)<$maxlen)
	{
		return $str;
	}
	if(substr($str, $maxlen, 1)!=" ")
	{
		$strlmt = strrpos(chop(substr($str, 0, $maxlen-1)), " ");
	} else {
		$strlmt = $maxlen-1;
	}
	$tmp_str = substr($str, 0, $strlmt);
	$str = substr($str, $strlmt+1);
	return $tmp_str;
}

// preparation calculations
$textareax = floor($width * 0.98);
$textareay = floor($height * 0.98);
log2file("[drawDcs] textareax: $textareax");
log2file("[drawDcs] textareay: $textareay");

// centralize the text vertically
$offsety = ($textareay%$fontheight)/2;
// offsets to mark the textarea
$x = (($width - $textareax)/2);
$y = (($height - $textareay)/2);

$maxlen = 0;
$words = explode(" ", $text);
foreach($words as $word)
{
	if(strlen($word)>$maxlen)
	{
		$maxlen = strlen($word);
	}
}

$i = 0;
while ($offsety < $textareay-$fontheight/2)
{
	//	centralize the text horizontally
	if ($offsety <= $textareay/2-$fontheight/2)
	{
		$offsetx[$i] = ($textareax/2)-($offsety*($textareax/$textareay));
		$leftx[$i] = $x+$offsetx[$i];
		$rightx[$i] = $x+$textareax-$offsetx[$i];
	} else {
		$offsetx[$i] = ($textareax*3/2)-(($offsety+$fontheight)*($textareax/$textareay));
		$leftx[$i] = $x+$textareax-$offsetx[$i];
		$rightx[$i] = $x+$offsetx[$i];
	}
	$maxcharsperline[$i] = floor(($rightx[$i]-$leftx[$i])/$fontwidth);
	if($maxcharsperline[$i]>$longestline)
	{
		$longestline = $maxcharsperline[$i];
	}
	$yval[$i] = $offsety;
	$offsety += $fontheight;
	$i++;
}

if($maxlen>max($maxcharsperline))
{
	$text = "TEXT TOO LONG";
}

$maxcharsinline = array_unique($maxcharsperline);
$maxcharsinline = array_filter($maxcharsinline, "notzero");
rsort($maxcharsinline);
$n = count($maxcharsinline)-1;
$m = 0;
if($i%2==0)
{
	$maxstrlen = 2*$maxcharsinline[0];
} else {
	$maxstrlen = $maxcharsinline[0];
}


do {
	if(strlen($text)<$maxstrlen)
	{
		foreach($words as $word)
		{
			if((strlen($textline[$m])+strlen($word))<$maxcharsinline[$n])
			{
				$textline[$m].= " ".$word;
			} elseif(strlen($word)==0) {
				break;
			} else {
				if($n==0 && $i%2==0) {
					$n=0;
				} elseif($n>0) {
					$n--;
				} elseif($n<count($maxcharsinline)) {
					$n++;
				} elseif($n=count($maxcharsinline)) {
					break;
				}
				$m++;
				$textline[$m].= " ".$word;
			}
		}
		break;
	}
	if($n==0 && count($maxcharsinline)==1)
	{
		break;
	} else {
		$n++;
	}
	$maxstrlen+=$maxcharsinline[$n]*2;	
} while (true);


// end preparations

header("Content-type: image/png");

$decision = ImageCreate($width+1, $height+1);
$white = ImageColorAllocate($decision, 255, 255, 255);
$black = ImageColorAllocate($decision, 0, 0, 0);

$points = array(0, floor($height/2), floor($width/2), 0, $width, floor($height/2), floor($width/2), $height);
ImagePolygon($decision, $points, 4, $black);


$yvalue=0;
if((strlen($textline[count($maxcharsperline)])=="0") && count($maxcharsperline)%2=="0")
{
	$yoffset = round($fontheight/2);
} else {
	$yoffset = 0;
}
log2file("[drawDcs] textline: ".strlen($textline[count($maxcharsperline)]));
for($n = 0; $n <= $m; $n++)
{
	log2file("[drawDcs] strlen of textline[$n]: ".strlen($textline[$n]));	
	if (strlen($textline[$n]=="0"))
	{
		$yvalue = round($fontheight/2);
		log2file("[drawDcs] strlen=0");
	} else {
		$xval = $leftx[$n] + (strlen($textline[$n])*$fontwidth)/3;
		log2file("[drawDcs] xval: $xval");
		log2file("[drawDcs] yval[$n]+yvalue+yoffset: $yval[$n]+$yvalue+$yoffset");
		log2file("[drawDcs] textline[$n]: $textline[$n]");
		ImageString($decision, $font, $xval, $yval[$n]+$yvalue+$yoffset, $textline[$n], $black);
		$yvalue = 0;
	}
}

ImagePNG($decision);
ImageDestroy($decision);
?>
