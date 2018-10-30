<?php 
/**
 * Meta box template
 *
 * @package ptheme
 */

// add template meta box to post and page editor screen
function pnq_template_metaboxes() {
	$meta_box = array(
		'id' => PNQ_FRAMEWORK_URI.'template_metabox',
		'title' => 'Metabox Template',
		'post_type' => array('post', 'page'),
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
				'title' => __('Text Meta Field', 'pnq'),
				'desc' => __('Description of test meta field.', 'pnq'),
				'type' => 'text',
				'id' => PNQ_FIELD_PREFIX.'meta_text',
				'val' => '1',
			),
			array(
				'title' => __('Textarea Meta Field', 'pnq'),
				'desc' => __('Description of testarea meta field.', 'pnq'),
				'type' => 'textarea',
				'id' => PNQ_FIELD_PREFIX.'meta_textare',
				'val' => '2',
				'attrs' => array(
					'rows' => 3
				)
			),
			array(
				'title' => __('Radio Meta Field', 'pnq'),
				'desc' => __('Choose one in the two options.', 'pnq'),
				'type' => 'radio',
				'id' => PNQ_FIELD_PREFIX.'meta_radio',
				'multi' => array(
					array(
						'text' => __('3.1', 'pnq'),
						'val' => '3.1'
					),
					array(
						'text' => __('3.2', 'pnq'),
						'val' => '3.2'
					)
				)
			),
			array(
				'title' => __('Checkbox Meta Field', 'pnq'),
				'desc' => __('Choose one or more from these options.', 'pnq'),
				'type' => 'checkbox',
				'id' => PNQ_FIELD_PREFIX.'meta_checkbox',
				'val' => 'off',
				'text' => 'Checkbox Item 1'
			),
			array(
				'title' => __('Select Meta Field', 'pnq'),
				'desc' => __('Select from the dropdown list.', 'pnq'),
				'type' => 'select',
				'id' => PNQ_FIELD_PREFIX.'meta_select',
				'multi' => array(
					array(
						'text' => '5.1',
						'val' => '5.1'
					),
					array(
						'text' => '5.2',
						'val' => '5.2'
					),
					array(
						'text' => '5.3',
						'val' => '5.3'
					)
				)
			),
			array(
				'title' => __('File Meta Field', 'pnq'),
				'desc' => __('Upload an attachment', 'pnq'),
				'type' => 'file',
				'id' => PNQ_FIELD_PREFIX.'meta_file'				
			),
			array(
				'title' => __('Tax Meta Field - Select Post Taxonomy', 'pnq'),
				'desc' => __('Meta field whose data comes from database.', 'pnq'),
				'type' => 'tax',
				'id' => PNQ_FIELD_PREFIX.'meta_stax',
				'tax' => 'category'
			),
			array(
				'title' => __('Multi-Tax Meta Field - Select Post Taxonomy', 'pnq'),
				'desc' => __('Meta field whose data comes from database.', 'pnq'),
				'type' => 'multi-tax',
				'id' => PNQ_FIELD_PREFIX.'meta_ctax',
				'tax' => 'category'
			),
			array(
				'title' => __('Color Meta Field - Select Color', 'pnq'),
				'desc' => __('Color meta field.', 'pnq'),
				'type' => 'color',
				'id' => PNQ_FIELD_PREFIX.'meta_color',
				'val' => ''
			),
			array(
				'title' => __( 'Range Meta Field', 'pnq' ),
				'desc' => __( 'Slide to set a value', 'pnq' ),
				'type' => 'range',
				'id' => PNQ_FIELD_PREFIX.'meta_range',
				'val' => 4,
				'attrs' => array(
					'max' => 100,
					'min' => 0,
					'step' => 1
				)
			),
			array(
				'title' => __( 'Range Meta Field', 'pnq' ),
				'desc' => __( 'Slide to set a value', 'pnq' ),
				'type' => 'range',
				'id' => PNQ_FIELD_PREFIX.'meta_range2',
				'val' => 55,
				'attrs' => array(
					'max' => 500,
					'min' => 5,
					'step' => 5
				)
			),
			array(
				'title' => __( 'Gallery Meta Field', 'pnq' ),
				'desc' => __( 'Manipulate multiple images in one field.', 'pnq' ),
				'type' => 'gallery',
				'id' => PNQ_FIELD_PREFIX . 'meta_gallery_1',
			)
		)
	);
	
	pnq_add_post_meta( $meta_box );
}
add_action( 'add_meta_boxes', 'pnq_template_metaboxes' );

