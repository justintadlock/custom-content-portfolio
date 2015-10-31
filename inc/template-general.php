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

	return apply_filters( 'ccp_is_author', ccp_is_project_archive() && is_author( $author ) );
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
	return get_the_author_meta( 'display_name', absint( get_query_var( 'author' ) ) );
}
