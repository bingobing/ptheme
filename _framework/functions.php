<?php 
/**
 * Framewrok General Funcitions.
 *
 * @package ptheme
 */

/**
 * Retrieve theme data. 
 * 
 * @since 1.0
 * @return array $theme_data Array of theme data.
 */
function pnq_get_theme_data() {
	$theme_obj = wp_get_theme();
	$is_child = is_child_theme();
	$theme_data = array(
		'name' => $is_child ? $theme_obj -> parent() -> get( 'Name' ) : $theme_obj -> get( 'Name' ),
		'uri' => $is_child ? $theme_obj -> parent() -> get( 'ThemeURI' ) : $theme_obj -> get( 'ThemeURI' ),
		'description' => $is_child ? $theme_obj -> parent() -> get( 'Description' ) : $theme_obj -> get( 'Description' ),
		'version' => $is_child ? $theme_obj -> parent() -> get( 'Version' ) : $theme_obj -> get( 'Version' ),
		'child' => $is_child
	);		
	return $theme_data;
}

/**
 * Retrieve theme name
 * 
 * @since 1.0
 * @return string $theme_name Name of the theme in the stylesheet.
 */
function pnq_get_theme_name() {
	$theme_name = '';
	$theme_obj = wp_get_theme();
	if( is_child_theme() ) {
		$theme_name = $theme_obj -> parent() -> get( 'Name' );	
	} else {
		$theme_name = $theme_obj -> get( 'Name' );	
	}
	return $theme_name;	
}

/**
 * Convert theme name to slug
 * 
 * @since 1.0
 * @return string $theme_slug Theme slug.
 */
function pnq_get_theme_slug() {
	$theme_name = pnq_get_theme_name();
	$theme_slug = pnq_str_to_slug( $theme_name );
	
	// apply filter 'pnq_theme_slug'
	$theme_slug = pnq_theme_slug( $theme_slug );
	
	return $theme_slug;	
}

/**
 * Create theme option name(depends on theme name) as the key for saving theme options to database 'options' table.
 *
 * @since 1.0
 *
 * @return string $option_name Key for saving theme options to databse.
 */
function pnq_get_option_name() {
	$theme_slug = pnq_get_theme_slug();
	$option_name = $theme_slug.'_options';	
	
	// apply filter 'pnq_option_name'
	$option_name = pnq_option_name( $option_name );
	
	return $option_name;
}

/**
 * Covert a string to a SEO friendly slug.
 * 
 * @since 1.0
 * @return string $str Slug ready string.
 */
function pnq_str_to_slug( $str ) {
	$str = preg_replace( '/\%/',' percentage',$str ); 
	$str = preg_replace( '/\@/',' at ',$str ); 
	$str = preg_replace( '/\&/',' and ',$str ); 
	$str = preg_replace( '/\s[\s]+/','-',$str );	// Strip off multiple spaces 
	$str = preg_replace( '/[\s\W]+/','-',$str );	// Strip off spaces and non-alpha-numeric 
	$str = preg_replace( '/^[\-]+/','',$str );		// Strip off the starting hyphens 
	$str = preg_replace( '/[\-]+$/','',$str );		// Strip off the ending hyphens 
	$str = strtolower( $str ); 

	return $str;	
}

/**
 * Print notice.
 * 
 * @since 1.0
 *
 * @param string $notice Content of the notice.
 * @param string $class The class attribute of the wrapper tag.
 * @param int $duration Notice duration in milliseconds, defaults to 0.
 * @return Echo notice html.
 */
function pnq_print_notice( $notice, $class = '', $duration = 0 ) {
	$class != '' ? $class = ' '.$class : '';
	
	echo "<div class='pnq-notice$class'>$notice</div>";
	
	$duration = absint( $duration );
	if( $duration ) {
		echo "<script type='text/javascript'>";
		echo 	"jQuery(document).ready(function($) {";
		echo		"$('.pnq-notice').delay(".$duration.").slideUp('normal');";
		echo		"$('.pnq-admin-menu li:last').addClass('last');";
		echo	 "});";
		echo "</script>";
	}
}

/**
 * Redirect to a given url use javascript.
 * 
 * @since 1.0
 *
 * @param string $url Url to Redirect to.
 * @return Echo redirect js to the browser.
 */
function pnq_js_redirect( $url ) {
	echo "<script language='javascript' type='text/javascript'>";
	echo "window.location.href='$url'";
	echo "</script>";	
}

/**
 * Convert array of attributes to string.
 * 
 * @since 1.0
 *
 * @param array $attrs Array of attributes.
 * @return string String of attributes.
 */
function pnq_prepare_attrs( $attrs ) {	
	$prepared_attrs = '';
	if( isset( $attrs ) ) {		
		foreach( $attrs as $attr_name => $attr_val ) {
			$prepared_attrs = $prepared_attrs . $attr_name . '="' . esc_attr( $attr_val ) . '"';
		}
	}	
	return $prepared_attrs;
}

/**
 * Maintains array of settings_sections.
 */
$pnq_settings_sections = array();

/**
 * Get section info
 *
 * @since 1.0
 *
 * @param string $section_id ID of the section.
 * @return array Section info.
 */
function pnq_get_section( $section_id ) {
	global $pnq_settings_sections;
	return isset( $pnq_settings_sections[$section_id] ) ? $pnq_settings_sections[$section_id] : false;
}

/**
 * Save section info to global variable.
 * 
 * @since 1.0
 *
 * @param array $section Section info defined in 'theme-options' module.
 * @return array $section Section info.
 */
function pnq_add_section( $section ) {
	global $pnq_settings_sections;	
	if( ! array_key_exists( $section['id'], $pnq_settings_sections ) ) {
		$pnq_settings_sections[$section['id']] = $section;
		return $section;	
	}
}

/**
 * Retrieve the spacific section as default section.
 * 
 * @since 1.0
 *
 * @param string $key Default key.
 * @param mixed $val Default value to be matched.
 * @return string ID of the section or false if not found.
 */
function pnq_default_section( $key, $val ) {
	global $pnq_settings_sections;
	foreach( $pnq_settings_sections as $section ) {
		if( isset( $section[$key]) && $section[$key] == $val ) {
			return $section['id'];	
		}
	}
	return false;
}

/**
 * Retrieve current section id.
 * 
 * @since 1.0
 *
 * @param string $index Section index used in $_GET.
 * @return string Current section id or false if not found.
 */
function pnq_current_section( $index = 'section' ) {
	global $pagenow;
	$current_section = isset( $_GET[$index] ) ? $_GET[$index] : pnq_default_section( 'default', true );
	
	if( $pagenow == 'options.php' ) {
		if( !isset( $_POST['current_section'] ) ) {
			//die('$_POST[\'current_section\'] not set!');
		} else {
		
			$current_section = $_POST['current_section'];
		}
	}
			
	return $current_section;
}

/**
 * Retrieve theme options using settings from _framework/theme-options.php
 * 
 * @since 1.0
 *
 * @return array $pnq_options Array of all theme options mapped in the _framework/theme-options.php.
 */
function pnq_get_theme_options() {
	
	global $pnq_settings_sections_map;
	$pnq_options = array();
	
	foreach( $pnq_settings_sections_map as $map => $id ) {
		$pnq_options[$map] = get_option( $id );
	}
	
	return $pnq_options;
}







