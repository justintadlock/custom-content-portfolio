<?php
/**
 * Template Tags for theme authors to use in their theme templates.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Makes sure the post ID is an absolute integer if passed in.  Else, returns the result 
 * of `get_the_ID()`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @return int
 */
function ccp_get_project_id( $post_id = '' ) {

	return $post_id ? absint( $post_id ) : get_the_ID();
}

/**
 * Checks if the project has the "complete" post status.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @return bool
 */
function ccp_is_project_complete( $post_id = '' ) {

	$post_id = ccp_get_project_id( $post_id );

	return apply_filters( 'ccp_is_project_complete', ccp_get_complete_post_status() === get_post_status( $post_id ) );
}

/**
 * Checks if the project has the "in_progress" post status.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @return bool
 */
function ccp_is_project_in_progress( $post_id = '' ) {

	$post_id = ccp_get_project_id( $post_id );

	return apply_filters( 'ccp_is_project_in_progress', ccp_get_in_progress_post_status() === get_post_status( $post_id ) );
}

/**
 * Outputs the project URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @return void
 */
function ccp_project_url( $post_id = '' ) {

	$url = ccp_get_project_url( $post_id );

	echo $url ? esc_url( $url ) : '';
}

/**
 * Returns the project URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return string
 */
function ccp_get_project_url( $post_id = '' ) {

	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	return apply_filters( 'ccp_get_project_url', ccp_get_project_meta( $post_id, 'url' ), $post_id );
}

/**
 * Displays the project link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_project_link( $args = array() ) {
	echo ccp_get_project_link( $args );
}

/**
 * Returns the project link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_project_link( $args = array() ) {

	$html = '';

	$defaults = array(
		'post_id' => get_the_ID(),
		'text'    => '%s',
		'before'  => '',
		'after'   => '',
		'wrap'    => '<a %s>%s</a>',
	);

	$args = wp_parse_args( $args, $defaults );

	$url = ccp_get_project_meta( $args['post_id'], 'url' );

	if ( $url ) {

		$text = sprintf( $args['text'], $url );
		$attr = sprintf( 'class="project-link" href="%s"', esc_url( $url ) );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], $attr, $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_link', $html, $args['post_id'] );
}

/**
 * Prints the project client.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_project_client( $args = array() ) {
	echo ccp_get_project_client( $args );
}

/**
 * Returns the project client.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_project_client( $args = array() ) {

	$html = '';

	$defaults = array(
		'post_id' => get_the_ID(),
		'text'    => '%s',
		'before'  => '',
		'after'   => '',
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$client = ccp_get_project_meta( $args['post_id'], 'client' );

	if ( $client ) {

		$text = sprintf( $args['text'], $client );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], 'class="project-client"', $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_client', $html, $args['post_id'] );
}
