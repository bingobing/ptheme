<?php 
/**
 * General Theme Settings
 *
 * @package Sitebase 
 */

/**
 * Theme Options Template
 * Option Structure
 * array(
 *		'title' => 'Default Option',
 *		'desc' => '<p>This is the discription of a default option</p>',
 *		'type' => 'text',
 *		'id' => 'default_id',
 *		'val' => 'default value',	
 *		'text' => '',
 *		'class' => '',
 *		'attrs' => array(),
 *		'multi' => array(),
 *		'func' => ''
 * )
 */
function pnq_general_options() {

	$general_options[] = array(
		'title' => 'Logo',
		'desc' => 'Upload an image as logo',
		'type' => 'file',
		'id' => PNQ_FIELD_PREFIX.'site_logo',
		'attrs' => array(
			'title' => 'Sitebase'
		)
	);
	
	$general_options[] = array(		
		'title' => 'Copyright Info',
		'desc' => 'Input some copyright info.',
		'type' => 'textarea',
		'id' => PNQ_FIELD_PREFIX.'copyright_info',
		'attrs' => array(
			'rows' => 2
		)
	);	
	
	// add setting section info
	array_unshift( $general_options, array(
		'id' => PNQ_FIELD_PREFIX.'general_options',
		'title' => __('General Options', 'pnq'),
		'description' => __('General settings of the theme.', 'pnq'),
		'icon' => 'fa fa-cog'
	) );
	
	pnq_add_settings_page( $general_options );
}
add_action( 'admin_init', 'pnq_general_options' );
