<?php 
/**
 * Theme functions and definitions.
 */

/**
 * Load theme framework.
 */
require_once( get_template_directory().'/_framework/init.php' );

/**
 * Load theme function modules.
 */
require_once( get_template_directory().'/_includes/init.php' );

function ptheme_setup() {
	add_theme_support('post-thumbnails');
	//set_post_thumbnail_size(100, 100);
	
	register_nav_menus( array(
		'primary' => 'Main Nav',
		'footer_nav' => 'Footer Nav'
	) );
}
add_action( 'after_setup_theme', 'ptheme_setup' );