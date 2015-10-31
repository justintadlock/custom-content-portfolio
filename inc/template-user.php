<?php
/**
 * Template tags related to portfolio users/authors for theme authors to use in their theme templates.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

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
