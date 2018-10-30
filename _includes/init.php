<?php
/**
 * Theme Module Init
 * 
 * @package ptheme
 */

define( 'PNQ_MODULE_PATH', get_template_directory().'/_includes' );
define( 'PNQ_MODULE_URI', get_template_directory_uri().'/_includes' );

// load theme options
require_once( PNQ_MODULE_PATH.'/theme-options/options-template.php' );
require_once( PNQ_MODULE_PATH.'/theme-options/general-options.php' );

// load theme mata
require_once( PNQ_MODULE_PATH.'/theme-meta/meta-template.php' );

// load theme plugins
//require_once( PNQ_MODULE_PATH.'/theme-plugins/theme-plugins.php' );
