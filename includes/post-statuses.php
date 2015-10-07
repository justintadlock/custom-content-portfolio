<?php
/**
 * File for handling custom post statuses.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register custom post statuses.
add_action( 'init', 'ccp_register_post_statuses' );

/**
 * Returns the name of the `publish` post status.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_publish_post_status() {

	return apply_filters( 'ccp_get_publish_post_status', 'publish' );
}

/**
 * Returns the name of the `trash` post status.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_trash_post_status() {

	return apply_filters( 'ccp_get_trash_post_status', 'trash' );
}

/**
 * Returns the name of the `complete` post status.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_complete_post_status() {

	return apply_filters( 'ccp_get_complete_post_status', 'complete' );
}

/**
 * Returns the name of the `in_progress` post status.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_in_progress_post_status() {

	return apply_filters( 'ccp_get_in_progress_post_status', 'in_progress' );
}

/**
 * Returns the name of the `private` post status.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_private_post_status() {

	return apply_filters( 'ccp_get_private_post_status', 'private' );
}

/**
 * Get post statuses associated with the `portfolio_project` post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_project_post_statuses() {

	$statuses = array(
		ccp_get_publish_post_status(),
		ccp_get_complete_post_status(),
		ccp_get_in_progress_post_status(),
		ccp_get_trash_post_status(),
		ccp_get_private_post_status()
	);

	return apply_filters( 'ccp_get_project_post_statuses', $statuses );
}

/**
 * Returns an array of statuses that are considered "published".
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_published_post_statuses() {

	$statuses = array(
		ccp_get_publish_post_status(),
		ccp_get_complete_post_status(),
		ccp_get_in_progress_post_status(),
		ccp_get_private_post_status()
	);

	return apply_filters( 'ccp_get_published_post_statuses', $statuses );
}

/**
 * Checks if a particular status is considered "published".
 *
 * @since  1.0.0
 * @access public
 * @param  string  $status
 * @return bool
 */
function ccp_is_status_published( $status ) {

	return apply_filters( 'ccp_is_status_published', in_array( $status, ccp_get_published_post_statuses() ), $status );
}

/**
 * Registers custom post statuses.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_register_post_statuses() {

	// Complete status args.
	$complete_args = array(
		'label'                     => __( 'Completed', 'custom-content-portfolio' ),
		'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'custom-content-portfolio' ),
		'public'                    => true,
		'private'                   => false,
		'protected'                 => false,
		'publicly_queryable'        => true,
		'show_in_admin_status_list' => true,
		'show_in_admin_all_list'    => true,

		// Custom arguments.
		'ccp_label_verb'            => __( 'Complete', 'custom-content-portfolio' ),
		'ccp_show_in_status_select' => true,
		'ccp_capability'            => 'manage_portfolio',
	);

	// In-progress status args.
	$in_progress_args = array(
		'label'                     => __( 'In Progress', 'custom-content-portfolio' ),
		'label_count'               => _n_noop( 'In Progress <span class="count">(%s)</span>', 'In Progress <span class="count">(%s)</span>', 'custom-content-portfolio' ),
		'public'                    => true,
		'private'                   => false,
		'protected'                 => false,
		'publicly_queryable'        => true,
		'show_in_admin_status_list' => true,
		'show_in_admin_all_list'    => true,

		// Custom arguments.
		'ccp_label_verb'            => __( 'In Progress', 'custom-content-portfolio' ),
		'ccp_show_in_status_select' => true,
		'ccp_capability'            => 'manage_portfolio',
	);

	// Register statuses.
	register_post_status( ccp_get_in_progress_post_status(), apply_filters( 'ccp_in_progress_post_status_args', $in_progress_args ) );
	register_post_status( ccp_get_complete_post_status(),    apply_filters( 'ccp_complete_post_status_args',    $complete_args    ) );
}
