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

// *** phpflow ini-wrapper ***

if (!@include_once('common.php')) {
  die('fatal error: could not load common.php!');
}
 
header("Content-Type: text/html; charset=ISO-8859-1");

// begin
$phpflow_info = $_PHPFLOW_config['INFO'];
$file = $_GET['file'];

// verify ini-file
if ($file == NULL) {
  die('need source file specified!');
}
if(!$ini_data = @parse_ini_file($_PHPFLOW_config['INI_DIR'].$file, TRUE)) {
  die('source file not found or not an ini-file!');
}

// load general data
$title = $ini_data["general"]["title"];
$headline = $ini_data["general"]["headline"];
$subheadline = $ini_data["general"]["subheadline"];
$width = $ini_data["general"]["element_width"];
$height = $ini_data["general"]["element_height"];
$arrow_space = $ini_data["general"]["arrow_space"];
$font = $ini_data["general"]["font_size"];

// load symbol data into flowchart table
$flowchart = "<table border=0 cellpadding=0 cellspacing=0>\n";
while (list($row_nr, $row_data) = each($ini_data)) {
  if (strstr($row_nr, "row")) {
    $column = 1;
    $rowpos = substr($row_nr, 3);
    $flowchart .= "<tr>\n";
    $type = $row_data["type".$column];
    do {
      $linksrc = "";
      $noborder = "";
      if($row_data["link".$column]) {
        $linksrc = $row_data["link".$column];
        $linksrc = " href=\"".$linksrc."\"";
        $noborder = " border=0";
      }
      $flowchart .= "<td align=\"center\"><a name=\"row".$rowpos."col".$column.
                    "\"".$linksrc.">";
      switch ($type) {
      case "Arrow":
          $start = $row_data["start".$column];
          $end = $row_data["end".$column];
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawArr.php?w=$width&amp;h=$arrow_space".
                        "&amp;s=$start&amp;e=$end&amp;f=$font&amp;t=$text\"".
                        "$noborder alt=\"Arrow: '$start-$end'\">";		
          break;

      case "ArrowDown":
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawArr.php?w=$width&amp;h=$arrow_space".
                        "&amp;s=top&amp;e=bottom&amp;f=$font&amp;t=$text\"".
                        "$noborder alt=\"Arrow: 'top-bottom'\">";		
          break;

      case "ArrowLeft":
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawArr.php?w=$arrow_space&amp;h=".
                        "$arrow_space&amp;s=right&amp;e=left&amp;f=$font&amp;".
                        "t=$text\"$noborder alt=\"Arrow: 'right-left'\">";		
          break;

      case "ArrowRight":
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawArr.php?w=$arrow_space&amp;h=".
                        "$arrow_space&amp;s=left&amp;e=right&amp;f=$font&amp;".
                        "t=$text\"$noborder alt=\"Arrow: 'left-right'\">";		
          break;

      case "Decision":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] Dcs-text: '.$text);
          $flowchart .= "<img src=\"drawDcs.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Decision: ".
                        "'$text'\">";
          break;

      case "Input/Output":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] IO-text: '.$text);
          $flowchart .= "<img src=\"drawIO.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Input/Output: ".
                        "'$text'\">";
          break;

      case "Line":
          $start = $row_data["start".$column];
          $end = $row_data["end".$column];
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawLine.php?w=$width&amp;h=$height&amp;".
                        "s=$start&amp;e=$end&amp;f=$font&amp;t=$text\"".
                        "$noborder alt=\"Line: '$start-$end'\">";		
          break;

      case "LineShort":
          $start = $row_data["start".$column];
          $end = $row_data["end".$column];
          $text = rawurlencode($row_data["text".$column]);
          $flowchart .= "<img src=\"drawLine.php?w=$arrow_space&amp;h=".
                        "$arrow_space&amp;s=$start&amp;e=$end&amp;f=$font".
                        "&amp;t=$text\"$noborder alt=\"Line(short): ".
                        "'$start-$end'\">";		
         break;

      case "LoopBegin":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] LpB-text: '.$text);
          $flowchart .= "<img src=\"drawLpB.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Loop Begin: ".
                        "'$text'\">";
          break;

      case "LoopEnd":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] LpE-text: '.$text);
          $flowchart .= "<img src=\"drawLpE.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Loop End: ".
                        "'$text'\">";
          break;

      case "Process":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] Prc-text: '.$text);
          $flowchart .= "<img src=\"drawPrc.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Process: ".
                        "'$text'\">";
          break;

      case "Subprocess":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] Sub-text: '.$text);
          $flowchart .= "<img src=\"drawSub.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Subprocess: ".
                        "'$text'\">";
          break;

      case "Terminator":
          $text = rawurlencode($row_data["text".$column]);
          log2file('[ini-wrapper] Trm-text: '.$text);
          $flowchart .= "<img src=\"drawTrm.php?w=$width&amp;h=$height&amp;".
                        "f=$font&amp;t=$text\"$noborder alt=\"Terminator: ".
                        "'$text'\">";
          break;

      case "none":
          $flowchart .= "&nbsp;";
          break;

      default:
          if (isset($type)) {
            die('unknown type.');
          }
          break;
      }
      $flowchart .= "</a></td>\n";
      $column++;
      $type = $row_data["type".$column];
    } while(isset($type));
    $flowchart .= "</tr>\n";
  }      
}
$flowchart .= "</table>";


// fill template


$tmpl = fopen($_PHPFLOW_config['TEMPLATE'], "r");
if(!$tmpl) {
  die("could not open ".$_PHPFLOW_config['TEMPLATE']."!");
}
$tmplsource = fread($tmpl,filesize($_PHPFLOW_config['TEMPLATE'])); 
fclose($tmpl);
$source = addslashes($tmplsource); 
eval("\$source=\"$source\";"); 
$source = stripslashes($source); 
echo $source;
?>
