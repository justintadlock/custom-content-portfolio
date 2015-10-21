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
 * Filter on 'post_type_archive_title' to allow for the use of the 'archive_title' label that isn't supported
 * by WordPress.  That's okay since we can roll our own labels.
 *
 * @since  0.1.0
 * @access public
 * @param  string $title
 * @return string
 */
function ccp_post_type_archive_title( $title ) {

	if ( is_post_type_archive( ccp_get_project_post_type() ) )
		$title = get_post_type_object( ccp_get_project_post_type() )->labels->archive_title;

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

	if ( ccp_get_project_post_type() !== $post->post_type )
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

	$project_type = ccp_get_project_post_type();

	if ( ! isset( $args['post_taxonomy'][ $project_type ] ) )
		$args['post_taxonomy'][ $project_type ] = 'portfolio_category';

	return $args;
}
