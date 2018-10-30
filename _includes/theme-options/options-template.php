<?php 
/**
 * General Theme Settings
 *
 * @package ptheme 
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
function pnq_options_template() {
	
	$template[] = array(
		'title' => 'Text Field',
		'desc' => 'This is the <a href="#">description</a> of the text option.',
		'type' => 'text',
		'id' => PNQ_FIELD_PREFIX.'text_field',
		'val' => '',
		'class' => ''
	);
	
	$template[] = array(
		'title' => 'Multi Text Fields',
		'desc' => 'This is multi text field description.',
		'type' => 'multi-text',
		'id' => PNQ_FIELD_PREFIX.'multi_text_fields',
		'multi' => array(
			array(
				'text' => 'Multi Text 1',
				'id' => PNQ_FIELD_PREFIX.'multi_text_field_1',
				'val' => 'default value 1',
				'class' => 'add-class-1 add-class-2',
				'attrs' => array()
			),
			array(
				'text' => 'Multi Text 2',
				'id' => PNQ_FIELD_PREFIX.'multi_text_field_2',
				'val' => 'default value 2',
				'class' => 'add-class-3',
				'attrs' => array(
					'custattr2' => 'cust2',
					'custattr' => 'cust'
				)
			)
		)
	);
	
	$template[] = array(
		'title' => 'Textarea Field',
		'desc' => 'This is description of textarea field',
		'type' => 'textarea',
		'id' => PNQ_FIELD_PREFIX.'textarea_field',
		'val' => 'input something..',
		'attrs' => array(
			'rows' => 5
		)
	);
	
	$template[] = array(
		'title' => 'Radio Field',
		'desc' => 'This is description of a radio field',
		'type' => 'radio',
		'id' => PNQ_FIELD_PREFIX.'radio_field',
		'multi' => array(
			array(
				'text' => 'radio text 1',
				'val' => '34.1'
			),
			array(
				'text' => 'radio text 2',
				'val' => '34.2'
			),
			array(
				'text' => 'radio text 3',
				'val' => '34.3'
			)
		)
	);
	
	$template[] = array(
		'title' => 'Checkbox Field',
		'desc' => 'This is description of a checkbox field.',
		'type' => 'checkbox',
		'id' => PNQ_FIELD_PREFIX.'checkbox_field',
		'val' => 'off',
		'text' => 'Checkbox Field Text'
	);
	
	$template[] = array(
		'title' => 'Multi Checkbox Field',
		'desc' => 'Multi checkbox description.',
		'type' => 'multi-checkbox',
		'id' => PNQ_FIELD_PREFIX.'multi_checkbox_fields',
		'multi' => array(
			array(
				'id' => PNQ_FIELD_PREFIX.'multi_checkbox_field_1',
				'val' => 'off',
				'text' => 'Multi Checkbox Text 1',
			),
			array(
				'id' => PNQ_FIELD_PREFIX.'multi_checkbox_field_2',
				'val' => 'on',
				'text' => 'Multi Checkbox Text 2',
			),
			array(
				'id' => PNQ_FIELD_PREFIX.'multi_checkbox_field_3',
				'val' => 'off',
				'text' => 'Multi Checkbox Text 3',
			)
		)
	);
	
	$template[] = array(
		'title' => 'Select Field',
		'desc' => 'Description of this field.',
		'type' => 'select',
		'id' => PNQ_FIELD_PREFIX.'select_field',
		'multi' => array(
			array(
				'text' => '61.1',
				'val' => '61.1'
			),
			array(
				'text' => '61.2',
				'val' => '61.2'
			),
			array(
				'text' => '61.3',
				'val' => '61.3'
			)
		)
	);
	
	$template[] = array(
		'title' => 'Color Field',
		'desc' => 'Choose a color for something.',
		'type' => 'color',
		'id' => PNQ_FIELD_PREFIX.'color_field',
		'val' => '#ef6c42',
		'attrs' => array(
			'data-default-color' => '#ffffff'
		)
	);
	
	$template[] = array(
		'title' => 'Date Field',
		'desc' => 'Choose a date.',
		'type' => 'date',
		'id' => PNQ_FIELD_PREFIX.'date_field',
	);
	
	$template[] = array(
		'title' => 'HTML Field',
		'type' => 'html',
		'id' => PNQ_FIELD_PREFIX.'html_field',
		'val' => 'a link to somewhere else: <a href="">link</a>'
	);
	
	$template[] = array(
		'title' => 'Custom Field',
		'desc' => 'Field which shows itself by calling custom function.',
		'type' => 'custom',
		'id' => PNQ_FIELD_PREFIX.'custom_field',
		'func' => 'pnq_custom_func',
	);
	
	$template[] = array(
		'title' => 'File Field',
		'type' => 'file',
		'desc' => 'Upload a file.',
		'id' => PNQ_FIELD_PREFIX.'file_field'
	);
	
	$template[] = array(
		'title' => 'Range Field',
		'type' => 'range',
		'desc' => 'Move the slider to set a value.',
		'id' => PNQ_FIELD_PREFIX . 'range_field',
		'attrs' => array(
			'max' => 100,
			'min' => 0,
			'step' => 1
		)
	);
	
	$template[] = array(
		'title' => 'Range Field',
		'type' => 'range',
		'desc' => 'Move the slider to set a value.',
		'id' => PNQ_FIELD_PREFIX . 'range_field2',
		'val' => 80,
		'attrs' => array(
			'max' => 500,
			'min' => 5,
			'step' => 5
		)
	);
	
	$template[] = array(
		'title' => 'Gallery Field',
		'type' => 'gallery',
		'desc' => 'Manipulates multiple images in one field.',
		'id' => PNQ_FIELD_PREFIX . 'gallery_field'
	);
	
	// add setting section info
	array_unshift( $template, array(
		'id' => PNQ_FIELD_PREFIX.'options_template',
		'title' => __('Options Template', 'pnq'),
		'description' => __('The option templates below demonstrate all option types', 'pnq'),
		'icon' => 'fa fa-file-text',
		'default' => true
	) );
	
	pnq_add_settings_page( $template );
}
add_action( 'admin_init', 'pnq_options_template' );

function pnq_custom_func( $args, $options ) {
	echo '<p>custom function excuted, args:</p>';
	echo '<code>';
	var_dump( $args );
	echo '</code>';
	echo '<p>options:</p>';
	echo '<code>';
	var_dump( $options );
	echo '</code>';
}