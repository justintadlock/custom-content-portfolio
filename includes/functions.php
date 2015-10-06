<?php
/**
 * Various functions, filters, and actions used by the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Filter the post type archive title.
add_filter( 'post_type_archive_title', 'ccp_post_type_archive_title' );

# Filter the post type permalink.
add_filter( 'post_type_link', 'ccp_post_type_link', 10, 2 );

# Filter the Breadcrumb Trail plugin args.
add_filter( 'breadcrumb_trail_args', 'ccp_breadcrumb_trail_args', 15 );

/**
 * Returns the default settings for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
function ccp_get_default_settings() {

	$settings = array(
		'portfolio_root'      => 'portfolio',
		'portfolio_base'      => '',          // defaults to 'portfolio_root'
		'portfolio_item_base' => '%portfolio%'
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
function ccp_post_type_archive_title( $title ) {

	if ( is_post_type_archive( 'portfolio_project' ) ) {
		$post_type = get_post_type_object( 'portfolio_project' );
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
function ccp_post_type_link( $post_link, $post ) {

	if ( 'portfolio_project' !== $post->post_type )
		return $post_link;

	// Allow %portfolio% in the custom post type permalink.
	if ( false !== strpos( $post_link, '%portfolio%' ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, 'portfolio_category' ); // @todo apply filters to tax name.

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$post_link = str_replace( '%portfolio%', $terms[0]->slug, $post_link );

		} else {
			$post_link = str_replace( '%portfolio%', 'project', $post_link );
		}
	}

	return $post_link;
}

/**
 * Filters the Breadcrumb Trail plugin arguments.  We're basically just telling it to show the
 * `portfolio_category` taxonomy when viewing single portfolio projects.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return array
 */
function ccp_breadcrumb_trail_args( $args ) {

	if ( ! isset( $args['post_taxonomy']['portfolio_project'] ) )
		$args['post_taxonomy']['portfolio_project'] = 'portfolio_category';

	return $args;
}

/**
 * Filters the 'breadcrumb_trail_items' hook from the Breadcrumb Trail plugin and the script version
 * included in the Hybrid Core framework.  At best, this is a neat hack to add the portfolio to the
 * single view of portfolio items based off the '%portfolio%' rewrite tag.  At worst, it's potentially
 * a huge management nightmare in the long term.  A better solution is definitely needed baked right
 * into Breadcrumb Trail itself that takes advantage of its built-in features for figuring out this type
 * of thing.
 *
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_breadcrumb_trail_items( $items ) {
	return $items;
}
