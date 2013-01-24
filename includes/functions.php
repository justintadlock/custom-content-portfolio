<?php
/**
 * Various functions, filters, and actions used by the plugin.
 *
 * @package    CPTPortfolio
 * @subpackage Includes
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/cpt-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Filter the post type archive title. */
add_filter( 'post_type_archive_title', 'cpt_portfolio_post_type_archive_title' );

/* Filter the post type permalink. */
add_filter( 'post_type_link', 'cpt_portfolio_post_type_link', 10, 2 );

/**
 * Returns the default settings for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
function cpt_portfolio_get_default_settings() {

	$settings = array(
		'portfolio_root'      => 'portfolio',
		'portfolio_base'      => '',          // defaults to 'portfolio_root'
		'portfolio_item_base' => 'item'
	);

	return $settings;
}

/**
 * Filter on 'post_type_archive_title' to allow for the use of the 'archive_title' label that isn't supported 
 * by WordPress.  That's okay since we can roll our own labels.
 *
 * @since  0.1.0
 * @access public
 * @param  string $title
 * @return string
 */
function cpt_portfolio_post_type_archive_title( $title ) {

	if ( is_post_type_archive( 'portfolio_item' ) ) {
		$post_type = get_post_type_object( 'portfolio_item' );
		$title = isset( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $title;
	}

	return $title;
}

/**
 * Filter on 'post_type_link' to allow users to use '%portfolio%' (the 'portfolio' taxonomy) in their 
 * portfolio item URLs.
 *
 * @since  0.1.0
 * @access public
 * @param  string $post_link
 * @param  object $post
 * @return string
 */
function cpt_portfolio_post_type_link( $post_link, $post ) {

	if ( 'portfolio_item' !== $post->post_type )
		return $post_link;

	/* Allow %portfolio% in the custom post type permalink. */
	if ( strpos( $post_link, '%portfolio%' ) ) {
	
		/* Get the terms. */
		$terms = get_the_terms( $post, 'portfolio' ); // @todo apply filters to tax name.

		/* Check that terms were returned. */
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$post_link = str_replace( '%portfolio%', $terms[0]->slug, $post_link );

		} else {
			$post_link = str_replace( '%portfolio%', 'item', $post_link );
		}
	}

	return $post_link;
}

?>