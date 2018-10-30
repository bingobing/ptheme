<?php 
/**
 * All hooks used in the framework.
 *
 * @package ptheme
 */
  
/**
 * Used to modify default settings field value.
 *
 * @since 1.0
 *
 * @param array $default_fields Values to be modify.
 * @param mixed $var (optional)Additional variables passed to the filder functions.
 */
function pnq_default_settings_field( $default_fields, $var = '' ) {
	return apply_filters( 'pnq_default_settings_field', $default_fields, $var );
}

/**
 * Wrapper for filter hook 'pnq_theme_slug',
 * use this filter to customize theme slug.
 * 
 * @since 1.0
 *
 * @param array $theme_slug Values to be modify.
 * @param mixed $var (optional)Additional variables passed to the filder functions.
 */
function pnq_theme_slug( $theme_slug, $var = '' ) {
	return apply_filters( 'pnq_theme_slug', $theme_slug, $var );
}

/**
 * Wrapper for filter hook 'pnq_option_name',
 * use this filter to customize option name used as the key for saving data to database.
 * 
 * @since 1.0
 *
 * @param array $option_name Values to be modify.
 * @param mixed $var (optional)Additional variables passed to the filder functions.
 */

function pnq_option_name( $option_name, $var = '' ) {
	return apply_filters( 'pnq_option_name', $option_name, $var );
}

/**
 * Wrapper for filter hook 'pnq_module_menu_position',
 * Hook to this filter to change module position in the admin panel.
 * 
 * @since 1.0
 *
 * @param int $menu_position Current menu position.
 * @param mixed $var (optional)Additional variables passed to the filter functions.
 */
function pnq_module_menu_position( $menu_position, $var = '' ) {
	return apply_filters( 'pnq_module_menu_position', $menu_position, $var );
}

/**
 * Allow theme developer to change display name in Dashboard.
 * 
 * @since 1.0
 *
 * @param string $theme_name The Original name of the theme.
 * @param mixed $var (optional)Additional variables passed to the filter functions.
 */
function pnq_theme_name( $theme_ame, $var = '' ) {
	return apply_filters( 'pnq_theme_name', $theme_ame, $var );
}