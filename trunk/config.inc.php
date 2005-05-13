<?php

/* this file is part of the open source project PHPflow (phpflow.berlios.de) */

// *** phpflow configuration file ***

// local path where the ini-files are placed (trailing-slash required !)
$PHPFLOW["INI_DIR"]="data/";

// local path of the template-file
$PHPFLOW["TEMPLATE"]="phpflow.tmpl.htm";

// maximum width of all flowchart symbols
$PHPFLOW["MAX_WIDTH"]=400;
 
// maximum height of all flowchart symbols
$PHPFLOW["MAX_HEIGHT"]=300;

// log debug messages into a local file
$PHPFLOW["DEBUGMODE"]=FALSE;

// local path to file where debugmessages are logged if debugmode activated
$PHPFLOW["DEBUGFILE"]=getcwd()."/logs/phpflow.log";

?>