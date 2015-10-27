<?php
/**
 * Registers metadata and related functions for the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register meta on the 'init' hook.
add_action( 'init', 'ccp_register_meta' );

/**
 * Registers custom metadata for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_register_meta() {

	register_meta( 'post', 'url',        'esc_url_raw',       '__return_false' );
	register_meta( 'post', 'client',     'wp_strip_all_tags', '__return_false' );
	register_meta( 'post', 'location',   'wp_strip_all_tags', '__return_false' );
	register_meta( 'post', 'start_date', 'wp_strip_all_tags', '__return_false' );
	register_meta( 'post', 'end_date',   'wp_strip_all_tags', '__return_false' );
}

/**
 * Returns project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function ccp_get_project_meta( $post_id, $meta_key ) {

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Adds/updates project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @param  mixed   $meta_value
 * @return bool
 */
function ccp_set_project_meta( $post_id, $meta_key, $meta_value ) {

	return update_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * Deletes project metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function ccp_delete_project_meta( $post_id, $meta_key ) {

	return delete_post_meta( $post_id, $meta_key );
}
