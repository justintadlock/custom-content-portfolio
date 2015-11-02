<?php
/**
 * Template tags related to portfolio projects for theme authors to use in their theme templates.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
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
 * Checks if viewing a single project.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $post
 * @return bool
 */
function ccp_is_single_project( $post = '' ) {

	$is_single = is_singular( ccp_get_project_post_type() );

	if ( $is_single && $post )
		$is_single = is_single( $post );

	return apply_filters( 'ccp_is_single_project', $is_single, $post );
}

/**
 * Checks if viewing the project archive.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function ccp_is_project_archive() {

	return apply_filters( 'ccp_is_project_archive', is_post_type_archive( ccp_get_project_post_type() ) && ! ccp_is_author() );
}

/**
 * Checks if the current post is a project.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @return bool
 */
function ccp_is_project( $post_id = '' ) {

	$post_id = ccp_get_project_id( $post_id );

	return apply_filters( 'ccp_is_project', ccp_get_project_post_type() === get_post_type( $post_id ), $post_id );
}

/**
 * Conditional check to see if a project has the "sticky" type.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $project_id
 * @return bool
 */
function ccp_is_project_sticky( $project_id = 0 ) {
	$project_id = ccp_get_project_id( $project_id );

	return apply_filters( 'ccp_is_project_sticky', in_array( $project_id, ccp_get_sticky_projects() ), $project_id );
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

	$post_id    = ccp_get_project_id( $post_id );
	$completed  = true;
	$start_date = ccp_get_project_meta( $post_id, 'start_date' );
	$end_date   = ccp_get_project_meta( $post_id, 'end_date'   );

	// If we have a start date but no end date, project is incomplete.
	if ( $start_date && ! $end_date )
		$completed = false;

	// Compare the start and end dates if we have both.
	else if ( $start_date && $end_date )
		$completed = mysql2date( 'Ymd', $start_date, false ) < mysql2date( 'Ymd', $end_date, false );

	// Make sure current date is greater than or equal to end date.
	if ( $end_date )
		$completed = date( 'Ymd' ) >= mysql2date( 'Ymd', $end_date, false );

	return apply_filters( 'ccp_is_project_complete', $completed, $post_id );
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

	$post_id     = ccp_get_project_id( $post_id );
	$in_progress = false;
	$start_date  = ccp_get_project_meta( $post_id, 'start_date' );
	$end_date    = ccp_get_project_meta( $post_id, 'end_date'   );

	// If we have a start date, the project is in progress.
	if ( $start_date )
		$in_progress = true;

	// If we have an end date, make sure current date is less than it.
	if ( $end_date )
		$in_progress = date( 'Ymd' ) < mysql2date( 'Ymd', $end_date, false );

	return apply_filters( 'ccp_is_project_in_progress', $in_progress, $post_id );
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

	$post_id = ccp_get_project_id( $post_id );

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
		'post_id' => ccp_get_project_id(),
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
		'post_id' => ccp_get_project_id(),
		'text'    => '%s',
		'before'  => '',
		'after'   => '',
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$client = ccp_get_project_meta( $args['post_id'], 'client' );

	if ( $client ) {

		$text = sprintf( $args['text'], sprintf( '<span class="project-data">%s</span>', $client ) );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], 'class="project-client"', $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_client', $html, $args['post_id'] );
}

/**
 * Prints the project location.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_project_location( $args = array() ) {
	echo ccp_get_project_location( $args );
}

/**
 * Returns the project location.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_project_location( $args = array() ) {

	$html = '';

	$defaults = array(
		'post_id' => ccp_get_project_id(),
		'text'    => '%s',
		'before'  => '',
		'after'   => '',
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$location = ccp_get_project_meta( $args['post_id'], 'location' );

	if ( $location ) {

		$text = sprintf( $args['text'], sprintf( '<span class="project-data">%s</span>', $location ) );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], 'class="project-location"', $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_location', $html, $args['post_id'] );
}

/**
 * Prints the project start_date.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_project_start_date( $args = array() ) {
	echo ccp_get_project_start_date( $args );
}

/**
 * Returns the project start_date.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_project_start_date( $args = array() ) {

	$html = '';

	$defaults = array(
		'post_id' => ccp_get_project_id(),
		'text'    => '%s',
		'format'  => get_option( 'date_format' ),
		'before'  => '',
		'after'   => '',
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$start_date = ccp_get_project_meta( $args['post_id'], 'start_date' );

	if ( $start_date ) {

		$datetime = sprintf( 'datetime="%s"', mysql2date( 'Y-m-d\TH:i:sP', $start_date, true ) );

		$text = sprintf( '<time class="project-data" %s>%s</time>', $datetime, mysql2date( $args['format'], $start_date, true ) );

		$text = sprintf( $args['text'], $text );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], 'class="project-start-date"', $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_start_date', $html, $args['post_id'] );
}

/**
 * Prints the project end_date.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_project_end_date( $args = array() ) {
	echo ccp_get_project_end_date( $args );
}

/**
 * Returns the project end_date.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_project_end_date( $args = array() ) {

	$html = '';

	$defaults = array(
		'post_id' => ccp_get_project_id(),
		'text'    => '%s',
		'format'  => get_option( 'date_format' ),
		'before'  => '',
		'after'   => '',
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$end_date = ccp_get_project_meta( $args['post_id'], 'end_date' );

	if ( $end_date ) {

		$datetime = sprintf( 'datetime="%s"', mysql2date( 'Y-m-d\TH:i:sP', $end_date, true ) );

		$text = sprintf( '<time class="project-data" %s>%s</time>', $datetime, mysql2date( $args['format'], $end_date, true ) );

		$text = sprintf( $args['text'], $text );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], 'class="project-end-date"', $text );
		$html .= $args['after'];
	}

	return apply_filters( 'ccp_get_project_end_date', $html, $args['post_id'] );
}
