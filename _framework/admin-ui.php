<?php 
/**
 * Admin Panel Functions.
 *
 * @package ptheme
 */

$pnq_theme_data = pnq_get_theme_data();
$pnq_theme_slug = pnq_get_theme_slug();
$pnq_options_name = pnq_get_option_name();

/**
 * Add an top-level administration menu.
 */
function pnq_add_admin_menu() {
	global $pnq_theme_data, $pnq_theme_slug;
	$theme_name = $pnq_theme_data['name'];	
	$theme_name = pnq_theme_name( $theme_name );	
		
	// Add theme option submenu
	$hook_suffix = add_theme_page(
		//$pnq_theme_slug, 
		$theme_name.__( ' Settings', 'pnq' ), 
		__( 'Theme Options', 'pnq' ),
		'manage_options', 
		$pnq_theme_slug, 
		'pnq_options_page'
	);

	// Load theme option page styles and scripts
	add_action( 'load-'.$hook_suffix, 'pnq_enqueue_admin_styles_scripts' );
}
add_action( 'admin_menu', 'pnq_add_admin_menu' );

/**
 * Generate theme options page.
 */
function pnq_options_page() {
	global $pnq_theme_data, $pnq_theme_slug, $pnq_settings_sections;

	// get current section
	$current_section = pnq_current_section();	
	
	// doesn't have a such section
	if( ! pnq_get_section( $current_section ) ) {		
		// add notice
		pnq_add_admin_notices( __( 'No such section, default loaded.', 'pnq' ), 'pnq-notice-forbidden' );
		
		// redirect
		$current_section = pnq_default_section( 'default', true );		
		$url = admin_url( 'admin.php?page='.$pnq_theme_slug.'&section='.$current_section );
		pnq_js_redirect( $url );
	}
	
	?>
		<div class="pnq-option-wrapper">
        	<div class="pnq-option-header">
            	<h1 class="pnq-theme-name"><?php echo pnq_theme_name( $pnq_theme_data['name'] ); ?><span class="pnq-theme-version"><?php echo $pnq_theme_data['version']; ?></span></h1>
                <a href="#" target="_blank" class="pnq-logo" title="Premium WordPress Themes and Plugins"><?php _e( 'Premium WordPress Themes and Plugins', 'pnq' ); ?></a>
            </div>
            <div class="pnq-option-content clearfix">
                <!-- start tab nav -->              
                <ul class="pnq-admin-menu">
                <?php foreach($pnq_settings_sections as $section) : ?>
                    <li id="<?php echo $section['id']; ?>" class="<?php echo $current_section == $section['id'] ? 'pnq-admin-menu-current' : 'pnq-admin-menu-item' ?>">
                    	<a href="<?php echo esc_url( admin_url( 'admin.php?page='.$pnq_theme_slug.'&section='.$section['id'] ) ); ?>"><i class="<?php echo $section['icon']; ?>"></i><span><?php echo $section['title']; ?></span></a>
                    </li>						
                <?php endforeach ?>
                </ul>
                <!-- end tab nav -->
                
                <div class="pnq-option-section">
                    <form action="options.php" method="post">    
    					<input type="hidden" name="current_section" value="<?php echo esc_attr($current_section); ?>" />
                        <?php 
                            settings_errors();
                            settings_fields ( $current_section );
                            do_settings_sections( $pnq_theme_slug );
                         ?>
                         <?php submit_button( __( 'Save Changes', 'pnq' ), 'primary', 'Update' ); ?>
                    </form>
                </div>
            </div>
        </div>    
    <?php
}

/**
 * Add settings sections and settings fields, register to wordpress.
 * 
 * @since 1.0
 *
 * @papam array $settings A set of theme options.
 */
function pnq_add_settings_page( $settings ) {
	global $pnq_theme_slug, $pnq_settings_sections;
	
	// save and retrive secton
	$settings_section = pnq_add_section( array_shift( $settings ) );
	
	// if not current section then don't register
	$current_section = pnq_current_section();
	if( ! $current_section || $current_section != $settings_section['id'] ) {
		return;
	}

	// add section to the root framework menu
	add_settings_section(
		$settings_section['id'],
		$settings_section['title'],
		'pnq_add_settings_section_callback',
		$pnq_theme_slug
	);
	
	// add fields to section
	foreach( $settings as $settings_fields ) {
		pnq_add_settings_fields( $settings_fields, $settings_section['id'] );
	}
	
	// register settings
	
	register_setting( $settings_section['id'], $settings_section['id'], 'pnq_sanitize_options_input' );	
}

/**
 * Wrapper for add_settings_field, provide default and additional prarms.
 * 
 * @since 1.0
 *
 * @param string $settings_fields Field param defined in theme-options module.
 * @param string $section_id The ID of the current section.
 */
function pnq_add_settings_fields( $settings_fields, $section_id ) {
	global $pnq_theme_slug;
	
	$default_fields = array(
		'title' => 'Default Field',
		'desc' => '',
		'type' => 'text',
		'id' => '',
		'val' => '',	
		'text' => '',
		'class' => '',
		'attrs' => array(),
		'func' => '',
		'multi' => array()	
	);
	
	extract( wp_parse_args( $settings_fields, pnq_default_settings_field( $default_fields ) ) );
	
	$field_args = array(
		'section' => $section_id,
		'title' => $title,
		'desc' => $desc,
		'type' => $type,
		'id' => $id,
		'val' => $val,
		'text' => $text,
		'class' => $class,
		'attrs' => $attrs,
		'func' => $func,
		'multi' => $multi
	);
	
	add_settings_field(
		$id,
		$title,
		'pnq_generate_form_field',
		$pnq_theme_slug,
		$section_id,
		$field_args
	);	
}

/**
 * The callback function of 'add_settings_section', generate section description.
 * 
 * @since 1.0
 * @param array $wp-section Section info passed by wordpress.
 */
function pnq_add_settings_section_callback( $wp_section ) {
	$section = pnq_get_section( $wp_section['id'] );
	echo '<p class="pnq-section-description">'.$section['description'].'</p>';
}

/**
 * The callback function of 'add_settings_field', 
 * generate form elements depends on the type of the fields.
 *
 * @since 1.0
 *
 * @param array $field Arguments that used to generate the field's html.
 */
function pnq_generate_form_field( $field ) {
	extract( $field );
	
	// if value not set, use default
	$option_name = $section;
	$options = get_option( $option_name );
	if( ! isset( $options[$id] ) ) {
		$options[$id] = $val;
	}
	
	// extra classes
	$field_class = ( $class != '' ) ? ' ' . $class : '';
	
	//extra attributes
	$ex_attrs = pnq_prepare_attrs( $attrs );
	
	// generate html
	$html = '';
	switch( $type ) {
		case 'text':
			$html .= "<input type='text' id='$id' name='".$option_name."[$id]' value='".esc_attr( $options[$id] )."' class='regular-text pnq-input-txt$field_class' $ex_attrs />";			
			break;	
		case 'multi-text':
			foreach( $multi as $multi_field ) {
				$field_class = ( isset( $multi_field['class'] ) && $multi_field['class'] != '' ) ? ' '.$multi_field['class'] : '';
				$ex_attrs = isset( $multi_field['attrs'] ) ? pnq_prepare_attrs( $multi_field['attrs'] ) : '';
				$val = array_key_exists( "$multi_field[id]", $options ) ? $options["$multi_field[id]"] : $multi_field['val'];
				$html .= "<label class='pnq-multi-block'><span class='pnq-multi-block-title'>".esc_html($multi_field['text'])."</span><input type='text' id='$multi_field[id]' name='".$option_name."[$multi_field[id]]' value='".esc_attr( $val )."' class='pnq-input-multi-txt pnq-input-sub$field_class' $ex_attrs /></label>";
			}
			break;
		case 'textarea':
			$html .= "<textarea id='$id' name='".$option_name."[$id]' class='large-text pnq-input-ta$field_class' $ex_attrs>".esc_html( $options[$id] )."</textarea>";
			break;
		case 'radio':
			$i = 1;
			foreach( $multi as $multi_field ) {
				$field_class = ( isset( $multi_field['class'] ) && $multi_field['class'] != '' ) ? ' '.$multi_field['class'] : '';
				$ex_attrs = isset( $multi_field['attrs'] ) ? pnq_prepare_attrs( $multi_field['attrs'] ) : '';
				$val = $multi_field['val'];
				$checked = $options[$id] == $multi_field['val'] ? 'checked="checked"' : '';
				$html .= "<label class='pnq-multi-block'><input type='radio' id='$id"."_"."$i' name='".$option_name."[$id]' value='".esc_attr( $val )."' class='pnq-input-radio$field_class' $ex_attrs $checked /> ".esc_html( $multi_field['text'] )."</label>";
				$i++;
			}
			break;
		case 'checkbox':
			$val = array_key_exists( "$id", $options ) ? $options[$id] : $field['val'];
			$checked = $val == 'on' ? 'checked="checked"' : '';
			$html .= "<input type='hidden' name='".$option_name."[$id]' value='off' />";
			$html .= "<label class='pnq-multi-block'><input type='checkbox' id='$id' name='".$option_name."[$id]' value='on' class='pnq-input-checkbox$field_class' $ex_attrs $checked /> ".$text."</label>";
			break;
		case 'multi-checkbox':
			foreach( $multi as $multi_field ) {
				$field_class = ( isset( $multi_field['class'] ) && $multi_field['class'] != '' ) ? ' '.$multi_field['class'] : '';
				$ex_attrs = isset( $multi_field['attrs'] ) ? pnq_prepare_attrs( $multi_field['attrs'] ) : '';
				$val = array_key_exists( "$multi_field[id]", $options ) ? $options[$multi_field['id']] : $multi_field['val'];
				$checked = $val == 'on' ? 'checked="checked"' : '';
				$html .= "<input type='hidden' name='".$option_name."[$multi_field[id]]' value='off' />";
				$html .= "<label class='pnq-multi-block'><input type='checkbox' id='$multi_field[id]' name='".$option_name."[$multi_field[id]]' value='on' class='pnq-input-multi-checkbox$field_class' $ex_attrs $checked /> ".esc_html( $multi_field['text'] )."</label>";
			}
			break;
		case 'select':
			$html .= "<select id='$id' name='".$option_name."[$id]' class='pnq-input-select pnq-input-top$field_class' $ex_attrs>";
			foreach( $multi as $multi_field ) {
				$selected = $options[$id] == $multi_field['val'] ? 'selected="selected"' : '';
				$html .= "<option value='".esc_attr( $multi_field['val'] )."' $selected>".esc_html( $multi_field['text'] )."</option>";
			}
			$html .= "</select>";
			break;
		case 'color':
			$html .= "<input type='text' id='$id' name='".$option_name."[$id]' value='".esc_attr( $options[$id] )."' class='pnq-input-color$field_class' $ex_attrs />";
			break;
		case 'date':
			$html .= "<input type='text' id='$id' name='".$option_name."[$id]' value='".esc_attr( $options[$id] )."' class='pnq-input-date$field_class' $ex_attrs />";
			break;
		case 'html':
			$html .= $val;
			break;
		case 'custom':
			if( function_exists( $func ) ) {
				call_user_func( $func, $field, $options );
			}
			break;
		case 'file':
			wp_enqueue_media();
			if( $options[$id] != '' ) {
				$allowed = array( 'jpeg', 'jpg', 'gif', 'png' );
				$type = substr( $options[$id], strripos( $options[$id], '.' )+1 );
				if( in_array( $type, $allowed ) ) {
					$html .= "<a id='preview_$id' href='".esc_url( $options[$id] )."' target='_blank'><img src='".esc_url( $options[$id] )."' alt='' class='pnq-option-image-preview pnq-input-top' /></a>";	
				}
			}
			$html .= "<div class='pnq-input-upload-opt'>";
			$html .= "<input type='url' id='$id' name='".$option_name."[$id]' value='".esc_attr( $options[$id] )."' class='pnq-input-url pnq-input-sub$field_class' $ex_attrs readonly='readonly' />";
			$html .= "<input type='button' id='upload_$id' value='".__('Browse', 'pnq')."' class='pnq-input-upload-btn button-secondary' />";
			$html .= "<input type='button' id='remove_$id' class='pnq-input-remove-btn button-secondary' value='".__( 'Remove', 'pnq' )."' />";
			$html .= "</div>";
			?>
            	<script>        				     	
					jQuery(document).ready(function($) {
						var pnq_media;
						var upload_btn = $('#upload_<?php echo $id; ?>');
						upload_btn.data('uploader_title', '<?php _e('Choose a File', 'pnq'); ?>');
						upload_btn.data('uploader_btn_text', '<?php _e('Select', 'pnq'); ?>');
						
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
								var target = $('#<?php echo $id; ?>');
								target.val(attachment.url);
								if(attachment.type === 'image') {
									var preview = $('#preview_<?php echo $id; ?>');
									if(preview.length > 0) {
										preview.attr('href', attachment.url);
										preview.find('img').attr('src', attachment.url);
									} else {
										$('<a id="preview_<?php echo $id; ?>" href="'+attachment.url+'" target="_blank"><img src="'+ attachment.url +'" class="pnq-option-image-preview pnq-input-top" /></a>').prependTo(upload_btn.parent().parent());
									}
									$('#preview_<?php echo $id; ?>').show();
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
		case 'range' :
			$html .= "<div class='pnq-input-range-opt'>";
			$html .= "<input type='text' id='$id' name='".$option_name."[$id]' value='".esc_attr( $options[$id] )."' class='pnq-input-range$field_class' readonly='readonly' />";
			$html .= "<div id='{$id}_slider' class='pnq-input-range-slider'></div>";
			$html .= "</div>";
			?>
            	<script>
                	jQuery(document).ready(function($) {
                        $('#<?php echo $id ?>_slider').slider({
							max: <?php echo $attrs['max']; ?>,
							min: <?php echo $attrs['min']; ?>,
							step: <?php echo $attrs['step']; ?>,
							value: <?php echo $options[$id] == '' ? $val : esc_attr( $options[$id] ); ?>,
							slide: function(event, ui) {
								$(ui.handle).closest(".pnq-input-range-opt").find(".pnq-input-range").val(ui.value);
							}
						});
                    });
                </script>
            <?php
			break;
		case 'gallery':
			wp_enqueue_media();
			$html .= "<div id='$id' class='pnq-input-gallery-opt'>";
			$html .= "<div class='pnq-gallery-wrapper clearfix'>";
			
			$gallery_imgs = json_decode( $options[$id], true );
			if ( $gallery_imgs ) {
				foreach ( $gallery_imgs as $img_id => $img_url ) {
					$html .= "<div id='$img_id' class='pnq-gallery-image'><img src='$img_url' /><i class='fa fa-times'></i></div>";
				}
			} else {

			}
			
			$html .= "</div>";
			$html .= "<input id='hidden_$id' type='hidden' name='" . $option_name . "[$id]' value='" . $options[$id] . "' />";
			$html .= "<input type='button' id='upload_$id' value='".__('Add Image', 'pnq')."' class='pnq-input-upload-btn button-secondary' />";
			$html .= "</div>";			
						
			?>
				<script>
                    jQuery(document).ready(function($) {
						// selected images in media model
						var selected = [];

						$('#<?php echo $id; ?> .pnq-gallery-wrapper').sortable({
							stop: function(event, ui) {
								bindData();
							}
						});
						$('#<?php echo $id; ?> .pnq-gallery-image').map(function(index, element) {
                            selected['"' + $(this).attr('id') + '"'] = $(this).find('img').attr('src');
                        });
						
                        var pnq_media;
                        var upload_btn = $('#upload_<?php echo $id; ?>');
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
								$('#<?php echo $id; ?> #hidden_<?php echo $id; ?>').val(strJSON(selected));
							});
							pnq_media.open();
						});
						
						function bindView(selected) {
							var $wrapper = $('#<?php echo $id; ?> .pnq-gallery-wrapper');
							var galleryImgs = '';
							for(var p in selected) {
								galleryImgs += "<div id=" + p + " class='pnq-gallery-image clearfix'><img src='" + selected[p] + "' /><i class='fa fa-times'></i></div>";
							}
							$wrapper.html(galleryImgs);
							bindHandler();
						}
						
						function bindData(noq) {
							var galleryItem = [];
							$('#<?php echo $id; ?> .pnq-gallery-image').map(function(index, element) {
								galleryItem['"' + $(this).attr('id') + '"'] = $(this).find('img').attr('src');
							});
							$('#<?php echo $id; ?> #hidden_<?php echo $id; ?>').val(strJSON(galleryItem));
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
		default : 
			pnq_add_admin_notices( __( 'No such type:', 'pnq' ).$type, 'pnq-notice-forbidden' );
			break;
	}
	
	if( $desc != '' ) $html .= '<p class="description">'. $desc .'</p>';
	echo $html;
}

/**
 * Sanitize user inputs.
 * 
 * @since 1.0
 *
 * @param array $input User input data.
 * @return array Sanitized data.
 */
function pnq_sanitize_options_input( $input ) {
	
	return $input;
}

/**
 * Add an admin notice.
 * 
 * @since 1.0
 *
 * @param string $content The Content of the notice.
 * @param string $type The notice type.
 */
function pnq_add_admin_notices( $content, $type = '' ) {
	$notice = array(
		'content' => $content,
		'type' => $type
	);
	$admin_notices = get_transient( 'pnq_admin_notices' );
	if( ! $admin_notices ) $admin_notices = array();
	$admin_notices[] = $notice;
	set_transient( 'pnq_admin_notices', $admin_notices );
}

/**
 * Print all admin notices 
 * 
 * @since 1.0
 */
function pnq_admin_notices() {
	$admin_notices = get_transient( 'pnq_admin_notices' );
	delete_transient( 'pnq_admin_notices' );
	
	if( ! $admin_notices ) return;
	foreach( $admin_notices as $notice ) {
		pnq_print_notice( $notice['content'], $notice['type'] );	
	}
}
add_action( 'admin_notices', 'pnq_admin_notices' );