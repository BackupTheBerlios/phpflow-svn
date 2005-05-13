<?php

/* phpflow is a program which constructs webbased flowcharts
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

// *** phpflow centralized symbol input value verification ***

// Check that this file is not loaded directly.
if ( basename( __FILE__ ) == basename( $_SERVER["PHP_SELF"] ) ) exit();

include_once("common.php");

// verify if input vars given
isset($_GET['w']) ? $width = $_GET['w'] : ( isset($_GET['width']) ? $width = $_GET['width'] : die("width not given !") );
isset($_GET['h']) ? $height = $_GET['h'] : ( isset($_GET['height']) ? $height = $_GET['height'] : die("height not given !") );
isset($_GET['f']) ? $font = $_GET['f'] : ( isset($_GET['font']) ? $font = $_GET['font'] : $font="_-NONE" );
isset($_GET['t']) ? $text = $_GET['t'] : ( isset($_GET['text']) ? $text = $_GET['text'] : $text="_-NONE" );

// verify if input vars in range
if(($width < 0) || ($width > $PHPFLOW["MAX_WIDTH"]) || ($height < 0) || ($height > $PHPFLOW["MAX_HEIGHT"]))
{
    die("width or height value out of range!");
}
if($font != "_-NONE")
{
    if(($font < 1) || ($font > 5))
    {
				die("font value out of range. must be within 1 and 5!");
		}
}

// common var definitions
$fontwidth = ImageFontWidth($font);  
$fontheight = ImageFontHeight($font);
$text = str_replace("µ", "\n", stripslashes($text));	// this is a workaround

// signature that verifyVal ran successfully
$valVerified = true

?>