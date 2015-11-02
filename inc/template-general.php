<?php
/**
 * General template tags for theme authors to use in their themes.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Conditional tag to check if viewing any portfolio page.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function ccp_is_portfolio() {

	$is_portfolio = ccp_is_project_archive() || ccp_is_single_project() || ccp_is_author() || ccp_is_category() || ccp_is_tag();

	return apply_filters( 'ccp_is_portfolio', $is_portfolio );
}

/**
 * Conditional tag to check if viewing a portfolio category archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function ccp_is_category( $term = '' ) {

	return apply_filters( 'ccp_is_category', is_tax( ccp_get_category_taxonomy(), $term ) );
}

/**
 * Conditional tag to check if viewing a portfolio tag archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function ccp_is_tag( $term = '' ) {

	return apply_filters( 'ccp_is_tag', is_tax( ccp_get_tag_taxonomy(), $term ) );
}

/**
 * Conditional tag to check if viewing a project author archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $author
 * @return bool
 */
function ccp_is_author( $author = '' ) {

	return apply_filters( 'ccp_is_author', is_post_type_archive( ccp_get_project_post_type() ) && is_author( $author ) );
}

/**
 * Print the author archive title.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_single_author_title() {
	echo ccp_get_single_author_title();
}

/**
 * Retrieve the author archive title.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_get_single_author_title() {

	return apply_filters( 'ccp_get_single_author_title', get_the_author_meta( 'display_name', absint( get_query_var( 'author' ) ) ) );
}

/**
 * Returns the author portfolio archive URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $user_id
 * @global object  $wp_rewrite
 * @global object  $authordata
 * @return string
 */
function ccp_get_author_url( $user_id = 0 ) {
	global $wp_rewrite, $authordata;

	$url = '';

	// If no user ID, see if there's some author data we can get it from.
	if ( ! $user_id && is_object( $authordata ) )
		$user_id = $authordata->ID;

	// If we have a user ID, build the URL.
	if ( $user_id ) {

		// Get the author's nicename.
		$nicename = get_the_author_meta( 'user_nicename', $user_id );

		// Pretty permalinks.
		if ( $wp_rewrite->using_permalinks() )
			$url = home_url( user_trailingslashit( trailingslashit( ccp_get_author_rewrite_slug() ) . $nicename ) );

		// Ugly permalinks.
		else
			$url = add_query_arg( array( 'post_type' => ccp_get_project_post_type(), 'author_name' => $nicename ), home_url( '/' ) );
	}

	return apply_filters( 'ccp_get_author_url', $url, $user_id );
}
