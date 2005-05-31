<?php

/* this file is part of the open source project PHPflow (phpflow.berlios.de) */

// *** phpflow configuration file ***

// local path where the ini-files are placed (trailing-slash required !)
$_PHPFLOW_config['INI_DIR'] = 'data/';

// local path of the template-file
$_PHPFLOW_config['TEMPLATE'] = 'phpflow.tmpl.htm';

// maximum width of all flowchart symbols
$_PHPFLOW_config['MAX_WIDTH'] = 400;
 
// maximum height of all flowchart symbols
$_PHPFLOW_config['MAX_HEIGHT'] = 300;

// log debug messages into a local file
$_PHPFLOW_config['DEBUGMODE'] = false;

// local path to file where debugmessages are logged if debugmode activated
$_PHPFLOW_config['DEBUGFILE'] = getcwd().'/logs/phpflow.log';

?>
