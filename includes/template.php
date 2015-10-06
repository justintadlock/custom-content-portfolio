<?php
/**
 * Template Tags for theme authors
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Returns the portfolio item URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return string
 */
function ccp_get_project_url( $post_id = '' ) {

	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	$url = get_post_meta( $post_id, 'url', true );

	return $url ? esc_url( $url ) : '';
}

/**
 * Displays the portfolio item link.
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
 * Returns a link to the porfolio item URL if it has been set.
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

	$url = ccp_get_project_url( $args['post_id'] );

	if ( $url ) {

		$text = sprintf( $args['text'], $url );
		$attr = sprintf( 'class="portfolio-item-link" href="%s"', $url );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], $attr, $text );
		$html .= $args['after'];
	}

	return $html;
}
