<?php 
/**
 * Load Admin Styles and Scripts
 *
 * @package ptheme
 */
 
function pnq_register_admin_scripts() {
	wp_register_style( 'admin-styles', PNQ_FRAMEWORK_URI.'/css/admin-styles.css' );
	wp_register_style( 'font-awesome', get_template_directory_uri().'/font-awesome/css/font-awesome.min.css' );
	wp_register_style( 'jquery-ui-smoothness', PNQ_FRAMEWORK_URI.'/css/smoothness/jquery-ui-1.10.3.css' );
	
	wp_register_script( 'admin-js', PNQ_FRAMEWORK_URI.'/js/admin-js.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker' ) );
}
add_action( 'admin_init', 'pnq_register_admin_scripts');
 
/**
 * Enqueue styles and scripts when theme option page is loaded.
 * 
 * @since 1.0
 */
function pnq_enqueue_admin_styles_scripts() {
	// styles
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'jquery-ui-smoothness' );
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'admin-styles' );
	
	// scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'admin-js' );
}

/**
 * Enqueue global styles and scripts that effect throughout the admin panel.
 * 
 * @since 1.0
 */
function pnq_enqueue_admin_global_scripts() {
	global $pagenow;
	
	wp_enqueue_style( 'admin-global', PNQ_FRAMEWORK_URI.'/css/admin-global.css' );
	
	if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'admin-meta-js', PNQ_FRAMEWORK_URI.'/js/admin-meta-js.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-slider' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'pnq_enqueue_admin_global_scripts' );