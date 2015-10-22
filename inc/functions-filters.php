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
 * Filter on `post_type_link` to make sure that single portfolio projects have the correct 
 * permalink.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $post_link
 * @param  object  $post
 * @return string
 */
function ccp_post_type_link( $post_link, $post ) {

	// Bail if this isn't a portfolio project.
	if ( ccp_get_project_post_type() !== $post->post_type )
		return $post_link;

	$cat_taxonomy = ccp_get_category_taxonomy();
	$tag_taxonomy = ccp_get_tag_taxonomy();

	$author = $category = $tag = '';

	// Check for the category.
	if ( false !== strpos( $post_link, "%{$cat_taxonomy}%" ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, $cat_taxonomy );

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$category = $terms[0]->slug;
		}
	}

	// Check for the tag.
	if ( false !== strpos( $post_link, "%{$tag_taxonomy}%" ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, $tag_taxonomy );

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$tag = $terms[0]->slug;
		}
	}

	// Check for the author.
	if ( false !== strpos( $post_link, '%author%' ) ) {

		$authordata = get_userdata( $post->post_author );
		$author     = $authordata->user_nicename;
	}

	$rewrite_tags = array(
		'%portfolio_category%',
		'%portfolio_tag%',
		'%author%'
	);

	$map_tags = array(
		$category,
		$tag,
		$author
	);

	return str_replace( $rewrite_tags, $map_tags, $post_link );
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
