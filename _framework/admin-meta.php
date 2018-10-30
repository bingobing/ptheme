<?php 
/**
 * Admin meta box functions.
 *
 * @package ptheme
 */

/**
 * Add meta boxes to the administrative interface.
 * 
 * @since 1.0
 *
 * @param mixed $meta_box Metabox configuration.
 */
function pnq_add_post_meta( $meta_box ) {	
	if( ! $meta_box ) {
		return false;
	}

	if( is_array( $meta_box['post_type'] ) ) {
		foreach( $meta_box['post_type'] as $post_type ) {
			add_meta_box(
				$meta_box['id'], 
				$meta_box['title'], 
				'pnq_generate_post_meta_fields', 
				$post_type, 
				$meta_box['context'], 
				$meta_box['priority'], 
				$meta_box['fields']
			);
		}
	} else {
		add_meta_box(
			$meta_box['id'], 
			$meta_box['title'], 
			'pnq_generate_post_meta_fields', 
			$meta_box['post_type'], 
			$meta_box['context'], 
			$meta_box['priority'], 
			$meta_box['fields']
		);
	}
}

/**
 * Callback function used to generate the meta field.
 * 
 * @since 1.0
 *
 * @param WP_Post $post The WP_Post object.
 * @param array $meta_box Metabox configuration.
 */
function pnq_generate_post_meta_fields( $post, $meta_box ) {
	if( ! is_array( $meta_box['args'] ) ) {	
		return false;	
	}
	$fields = $meta_box['args'];

	wp_nonce_field( basename(__FILE__), 'pnq_metabox_nonce' );
	$html = '<div class="pnq-meta-inside">';
	foreach( $fields as $field ) {
		$html .= '<div class="pnq-meta-item">';
		$html .= '<label for="'.$field['id'].'" class="pnq-meta-title">'.esc_html( $field['title'] ).'</label>';
		
		$attrs = isset($field['attrs']) ? pnq_prepare_attrs( $field['attrs'] ) : '';
		$meta = get_post_meta( $post->ID, $field['id'], true );
		$desc = ! isset( $field['desc'] ) ? '' : '<p class="description">'.$field['desc'].'</p>';
		if ( ! isset( $field['val'] ) ) {
			$field['val'] = '';
		}
		
		switch( $field['type'] ) {
			case 'text':
				$html .= '<input type="text" id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" value="'.esc_attr( ($meta ? $meta : $field['val']) ).'" class="pnq-meta-text" />';
				break;
			case 'textarea':
				$html .= '<textarea id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" class="pnq-meta-textarea" '.$attrs.'>'.esc_attr( ($meta ? $meta : $field['val']) ).'</textarea>';
				break;
			case 'radio' :
				if( ! isset( $field['multi'] ) || empty( $field['multi'] ) ) {
					break;
				}
				if( ! isset( $meta ) || empty( $meta ) ) {
					$meta = $field['val'];
				}
				$i = 1;
				foreach( $field['multi'] as $multi_field ) {
					$val = $multi_field['val'];
					$checked = $val == $meta ? 'checked="checked"' : '';
					$html .= '<label><input type="radio" id="'.$field['id'].'_'.$i++.'" name="pnq_meta['.$field['id'].']" class="pnq-meta-radio" value="'.esc_attr( $val ).'" '.$checked.' /> '.esc_html( $multi_field['text'] ).'</label>';
				}
				break;
			case 'checkbox' :
				if( ! isset( $meta ) || empty( $meta ) ) {
					$meta = $field['val'];
				}
				$checked = $meta == 'on' ? 'checked="checked"' : '';
				$html .= '<input type="hidden" name="pnq_meta['.$field['id'].']" value="off" />';
				$html .= '<label><input type="checkbox" id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" class="pnq-meta-checkbox" value="on" '.$checked.' /> '.esc_html( $field['text'] ).'</label>';
				break;
			case 'select' :
				if( ! isset( $field['multi'] ) || empty( $field['multi'] )) {
					continue;
				}
				$html .= '<select id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" class="pnq-meta-select" >';
				foreach( $field['multi'] as $opt ) {
					$selected = $opt['val'] == $meta ? 'selected="selected"' : '';
					$html .= '<option value="'.esc_attr( $opt['val'] ).'" '.$selected.'>'.esc_html( $opt['text'] ).'</option>';
				}
				$html .= '</select>';
				break;
			case 'file' :
				if( $meta != '' ) {
					$allowed = array( 'jpeg', 'jpg', 'gif', 'png' );
					$type = substr( $meta, strripos( $meta, '.' )+1 );
					if( in_array( $type, $allowed ) ) {
						$html .= '<a id="preview_'.$field['id'].'" href="'.esc_url( $meta ).'" target="_blank"><img src="'.esc_url( $meta ).'" alt="" class="pnq-meta-image-preview" /></a>';	
					}
				}
				$html .= '<input type="url" id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" value="'.esc_attr( $meta ).'" readonly="readonly" />';
				$html .= '<input type="button" id="upload_'.$field['id'].'" value="'.__('Browse', 'pnq').'" class="pnq-input-upload-btn button-secondary" />';
				$html .= '<input type="button" id="remove_'.$field['id'].'" value="'.__('Remove', 'pnq').'" class="pnq-input-remove-btn button-secondary" />';
				?>
					<script>        				     	
						jQuery(document).ready(function($) {
							var pnq_media;
							var upload_btn = $('#upload_<?php echo $field['id']; ?>');
							upload_btn.data('uploader_title', '<?php _e('Choose a File', 'pnq'); ?>');
							upload_btn.data('uploader_btn_text', '<?php _e('Select', 'pnq') ?>');
							
							upload_btn.click(function(e) {
								e.preventDefault();
								if(pnq_media) {
									return pnq_media.open();
								}
								pnq_media = wp.media.frames.pnq_media = wp.media({
									title : $(this).data('uploader_title'),
									button : {
										text : $(this).data('uploader_btn_text')	
									},
									multiple : false
								});
								pnq_media.on('select', function() {
									attachment = pnq_media.state().get('selection').first().toJSON();
									var target = $('#<?php echo $field['id']; ?>');
									target.val(attachment.url);
									if(attachment.type === 'image') {
										var preview = $('#preview_<?php echo $field['id']; ?>');
										if(preview.length > 0) {
											preview.attr('href', attachment.url);
											preview.find('img').attr('src', attachment.url);
										} else {
											$('<a id="preview_<?php echo $field['id']; ?>" href="'+attachment.url+'" target="_blank"><img src="'+ attachment.url +'" class="pnq-meta-image-preview" /></a>').insertAfter(upload_btn.parent().find('.pnq-meta-title'));
										}
										$('#preview_<?php echo $field['id']; ?>').show();
									}
								});
								pnq_media.open();
							});
							
							$('.pnq-input-remove-btn').click(function(e) {
								e.preventDefault();
								var id = $(this).attr('id').replace('remove_', '');
								$('#' + id).val('');
								$('#preview_' + id).hide();
							});
						});
					</script>
				<?php
				break;
			case 'tax' : 
				if( ! isset( $field['tax'] ) ) {
					continue;	
				}
				$terms = get_terms( array( $field['tax'] ), array( 'hide_empty' => false ) );
				if( is_wp_error( $terms ) ) {
					continue;	
				}
				
				$html .= '<select id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" class="pnq-meta-select">';
				foreach( $terms as $term ) {
					$selected = $meta == $term -> term_id ? 'selected="selected"' : '';
					$html .= '<option value="'.$term -> term_id.'" '.$selected.'>'.$term -> name.'</option>';
				}
				$html .= '</select>';	
				break;
			case 'multi-tax' :
				if( ! isset( $field['tax'] ) ) {
					continue;	
				}			
				$terms = get_terms( array( $field['tax'] ), array( 'hide_empty' => false ) );
				if( is_wp_error( $terms ) ) {
					break;	
				}
				$html .= '<input type="hidden" name="pnq_meta['.$field['id'].']" value="" />';
				$i = 1;
				foreach( $terms as $term ) {
					$checked = '';
					if( is_array( $meta ) && in_array( $term -> term_id, $meta ) ) {
						$checked = 'checked="checked"';
					}
					$html .= '<label for="'.$field['id'].'"><input type="checkbox" id="'.$field['id'].'_'.$i++.'" name="pnq_meta['.$field['id'].'][]" value="'.$term -> term_id.'" class="pnq-meta-checkbox" '.$checked.' /> '.$term -> name.'</label>';
				}
								
				break;
			case 'color' :
				$html .= '<input type="text" id="'.$field['id'].'" name="pnq_meta['.$field['id'].']" value="'.esc_attr( ($meta ? $meta : $field['val']) ).'" class="pnq-meta-color" />';
				break;
			case 'range' :
				$html .= '<div class="pnq-input-range-opt">';
				$html .= '<input type="text" id="' . $field['id'] . '" name="pnq_meta['.$field['id'].']" value="'.esc_attr( ($meta != '' ? $meta : $field['val']) ).'" class="pnq-input-range" readonly="readonly" />';
				$html .= '<div id="' . $field['id'] . '_slider" class="pnq-input-range-slider"></div>';
				$html .= '</div>';
				?>
					<script>
						jQuery(document).ready(function($) {
							$('#<?php echo $field['id'] ?>_slider').slider({
								max: <?php echo $field['attrs']['max']; ?>,
								min: <?php echo $field['attrs']['min']; ?>,
								step: <?php echo $field['attrs']['step']; ?>,
								value: <?php echo $meta == '' ? $field['val'] : esc_attr( $meta ); ?>,
								slide: function(event, ui) {
									$(ui.handle).closest(".pnq-input-range-opt").find(".pnq-input-range").val(ui.value);
								}
							});
						});
					</script>
				<?php
				break;
			case 'gallery' :
				wp_enqueue_media();
				$html .= '<div id="' . $field['id'] . '" class="pnq-input-gallery-opt">';
				$html .= '<div class="pnq-gallery-wrapper clearfix">';
				$meta = html_entity_decode($meta);
				$gallery_imgs = json_decode( $meta, true );
				if ( $gallery_imgs ) {
					foreach ( $gallery_imgs as $img_id => $img_url ) {
						$html .= '<div id="' . $img_id . '" class="pnq-gallery-image"><img src="'.$img_url . '" /><i class="fa fa-times"></i></div>';
					}
				} else {
	
				}
				
				$html .= '</div>';
				$html .= '<input id="hidden_' . $field['id'] . '" type="hidden" name="pnq_meta['.$field['id'].']" value=' . $meta . ' />';
				$html .= '<input type="button" id="upload_' . $field['id'] . '" value="' . __('Add Image', 'pnq') . '" class="pnq-input-upload-btn button-secondary" />';
				$html .= '</div>';			

				?>
					<script>
						jQuery(document).ready(function($) {
							// selected images in media model
							var selected = [];
	
							$('#<?php echo $field['id']; ?> .pnq-gallery-wrapper').sortable({
								stop: function(event, ui) {
									bindData();
								}
							});
							$('#<?php echo $field['id']; ?> .pnq-gallery-image').map(function(index, element) {
								selected['"' + $(this).attr('id') + '"'] = $(this).find('img').attr('src');
							});
							
							var pnq_media;
							var upload_btn = $('#upload_<?php echo $field['id']; ?>');
							upload_btn.data('uploader_title', '<?php _e('Add Image', 'pnq'); ?>');
							upload_btn.data('uploader_btn_text', '<?php _e('Select', 'pnq'); ?>');
							
							upload_btn.click(function(e) {
								if(pnq_media) {
									return pnq_media.open();
								}
								pnq_media = wp.media.frames.pnq_media = wp.media({
									title : $(this).data('uploader_title'),
									button : {
										text : $(this).data('uploader_btn_text')	
									},
									library: {
										type: 'image'
									},
									multiple : true
								});
								pnq_media.on('select', function() {
									var selections = pnq_media.state().get('selection');
									
									selections.map(function(attachment) {
										attachment = attachment.toJSON();
										//alert(attachment.id + '-----' + attachment.url);
										
										selected['"' + attachment.id + '"'] = attachment.url;
									});
									bindView(selected);
									$('#<?php echo $field['id']; ?> #hidden_<?php echo $field['id']; ?>').val(strJSON(selected));
								});
								pnq_media.open();
							});
							
							function bindView(selected) {
								var $wrapper = $('#<?php echo $field['id']; ?> .pnq-gallery-wrapper');
								var galleryImgs = '';
								for(var p in selected) {
									galleryImgs += "<div id=" + p + " class='pnq-gallery-image clearfix'><img src='" + selected[p] + "' /><i class='fa fa-times'></i></div>";
								}
								$wrapper.html(galleryImgs);
								bindHandler();
							}
							
							function bindData(noq) {
								var galleryItem = [];
								$('#<?php echo $field['id']; ?> .pnq-gallery-image').map(function(index, element) {
									galleryItem['"' + $(this).attr('id') + '"'] = $(this).find('img').attr('src');
								});
								$('#<?php echo $field['id']; ?> #hidden_<?php echo $field['id']; ?>').val(strJSON(galleryItem));
							}
							
							function strJSON(arr) {						
								if(associatedArrayLength(arr) == 0) {
									return '';	
								}
								
								var jsonStr = '{';
								for(var p in arr) {
									jsonStr += p +':"' + arr[p] + '",';
								}
								jsonStr = jsonStr.substring(0, jsonStr.length-1);
								jsonStr += '}';
								jsonStr = jsonStr.replace(/""/g, '\"');
								
								return jsonStr;
							}
							
							function associatedArrayLength(obj){    
								var count=0;       
								for(var name in obj){       
									if(typeof obj[name] == "object"){               
										count+=associatedArrayLength(obj[name]);         
									 }else{  
										count++;  
									 }  
								}    
								return count;    
							}  
							
							function bindHandler() {
								$('.pnq-gallery-image').on('mouseover', function() {
									$(this).find('.fa-times').show();
								});
								$('.pnq-gallery-image').on('mouseout', function() {
									$(this).find('.fa-times').hide();
								});	
								$('.pnq-gallery-image .fa-times').click(function() {
									$(this).parent().remove();
									bindData();
								});
							}
							bindHandler();				
						});
					</script>
				<?php	
				break;
			case 'custom' :
				if( function_exists( $field['func'] ) ) {
					$output = call_user_func( $field['func'], $field, $meta );
					$html .= $output;
				}
				break;
			default :
				break;	
		}
		$html .= $desc;
		$html .= '</div>';
	}
	$html .= '</div>';
	
	echo $html;
}

/**
 * Save post metadata when create or update the post.
 * 
 * @since 1.0
 *
 * @param string $post_id The ID of the current post.
 * @param WP_Post $post The WP_Post object of the current post.
 */
function pnq_save_post_meta( $post_id, $post ) {
	
	// check nonce
	if( ! isset( $_POST['pnq_metabox_nonce'] ) || !wp_verify_nonce( $_POST['pnq_metabox_nonce'], basename(__FILE__) ) ) {
		return;
	}
	
	// check permission
	$post_type = get_post_type_object( $post -> post_type );
	if( ! current_user_can( $post_type -> cap -> edit_post, $post_id ) ) {
		return;
	}
	
	if( ! isset( $_POST['pnq_meta'] ) || ! is_array( $_POST['pnq_meta'] ) ) {
		return;
	}
	
	// save meta
	foreach( $_POST['pnq_meta'] as $key => $val ) {
		// sanitize data		
		if( is_array( $val ) ) {
			foreach( $val as $v ) {
				$v = stripslashes( htmlspecialchars( $v ) );	
			}
		} else {
			$val = stripslashes( htmlspecialchars( $val ) );
		}
		
		update_post_meta( $post_id, $key, $val );
	}
}
add_action( 'save_post', 'pnq_save_post_meta', 10, 2 );