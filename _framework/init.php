<?php 
/**
 * Theme Framewrok Init
 *
 * @package ptheme
 */
 
define( 'PNQ_FRAMEWORK', 'ptheme' );
define( 'PNQ_FRAMEWORK_VERSION', '1.0' );
define( 'PNQ_FRAMEWORK_DIR', get_template_directory().'/_framework' );
define( 'PNQ_FRAMEWORK_URI', get_template_directory_uri().'/_framework' );
if ( ! defined( 'PNQ_FIELD_PREFIX' ) ) {
	define( 'PNQ_FIELD_PREFIX', 'pnq_' );
}

// Load Framewrok Components
require_once( PNQ_FRAMEWORK_DIR.'/hooks.php' );
require_once( PNQ_FRAMEWORK_DIR.'/functions.php' );
require_once( PNQ_FRAMEWORK_DIR.'/admin-ui.php' );
require_once( PNQ_FRAMEWORK_DIR.'/admin-meta.php' );
require_once( PNQ_FRAMEWORK_DIR.'/admin-scripts.php' );

// generic theme functions.
require_once( PNQ_FRAMEWORK_DIR.'/theme-options.php' );
require_once( PNQ_FRAMEWORK_DIR.'/theme-functions.php' );
