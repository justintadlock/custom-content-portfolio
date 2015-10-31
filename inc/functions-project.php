<?php
/**
 * Plugin functions related to the project post type.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Adds a project to the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_add_sticky_project( $project_id ) {
	$project_id = ccp_get_project_id( $project_id );

	if ( ! ccp_is_project_sticky( $project_id ) )
		return update_option( 'ccp_sticky_projects', array_unique( array_merge( ccp_get_sticky_projects(), array( $project_id ) ) ) );

	return false;
}

/**
 * Removes a project from the list of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_remove_sticky_project( $project_id ) {
	$project_id = ccp_get_project_id( $project_id );

	if ( ccp_is_project_sticky( $project_id ) ) {
		$stickies = ccp_get_sticky_projects();
		$key      = array_search( $project_id, $stickies );

		if ( isset( $stickies[ $key ] ) ) {
			unset( $stickies[ $key ] );
			return update_option( 'ccp_sticky_projects', array_unique( $stickies ) );
		}
	}

	return false;
}

/**
 * Returns an array of sticky projects.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_sticky_projects() {
	return apply_filters( 'ccp_get_sticky_projects', get_option( 'ccp_sticky_projects', array() ) );
}
