<?php 
/**
 * Theme general functions
 * 
 * @package ptheme
 */
 
/**
 * Theme options that will be used in front end.
 */
$pnq_theme_options = pnq_get_theme_options();
 
/**
 * Generate post pagination html for use in theme template file.
 * 
 * @since 1.0
 */
function pnq_paginating() {
	
	global $wp_query;
	$big = 999999999; 
			
	$pagination = paginate_links( array(
		'base' => str_replace($big, '%#%', get_pagenum_link( $big, true ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var( 'paged' ) ),
		'total' => $wp_query -> max_num_pages,
		'prev_text' => __( '&laquo; Previous', 'pnq' ),
		'next_text' => __( 'Next &raquo;', 'pnq' ),
		'type' => 'array'
	) );
	
	pnq_generate_pagination( $pagination );
}

/**
 * Generate comments pagination.
 * 
 * @since 1.0
 */
function pnq_paginating_comments() {
	
	$pagination = paginate_comments_links( array(
		'echo' => false,
		'type' => 'array',
		'prev_text' => __('&laquo; Previous', 'pnq'),
		'next_text' => __('Next &raquo;', 'pnq')
	) );
	
	pnq_generate_pagination( $pagination, 'pnq-comments-pagination' );
}

/**
 * Generate the pagination html.
 * 
 * @since 1.0
 *
 * @param array $pagination Array of pagination.
 * @param string $wrapper_class The class attributes assigned to the pagination wrapper. 
 */
function pnq_generate_pagination( $pagination, $wrapper_class = '' ) {
	if( ! empty( $pagination ) && is_array( $pagination ) ) {
		echo '<div class="pnq-pagination clearfix '.$wrapper_class.'"><ul>';

		foreach( $pagination as $page ) {
			echo '<li>'.$page.'</li>';	
		}
		echo '</ul></div>';
	}
}

/**
 * Diplay site logo.
 *
 * Display image logo if set, else display text logo.
 * 
 * @since 1.0
 */
function pnq_site_logo() {
	
	global $pnq_theme_options;
	$logo = $pnq_theme_options['general'][PNQ_FIELD_PREFIX.'site_logo'];
			
	 if( '' != $logo ) {
		?>
        <h1><a href="<?php echo home_url(); ?>"><img src="<?php echo esc_url( $logo ); ?>" alt="Logo" /></a></h1>
        <?php
	 } else {
		?>
        <hgroup>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
            <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
        </hgroup>
        <?php 
	}
}

/**
 * Output copyright info.
 * 
 * @since 1.0
 */
function pnq_copyright_info() {
	global $pnq_theme_options;
	echo $pnq_theme_options['general'][PNQ_FIELD_PREFIX.'copyright_info'];
}





