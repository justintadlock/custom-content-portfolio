<?php
/**
 * Template Tags for theme authors
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @since      1.0.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Displays the portfolio item link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function ccp_portfolio_item_link( $args = array() ) {
	echo ccp_get_portfolio_item_link( $args );
}

/**
 * Returns a link to the porfolio item URL if it has been set.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function ccp_get_portfolio_item_link( $args = array() ) {

	$html = '';

	$defaults = array(
		'text'   => '%s',
		'before' => '',
		'after'  => '',
		'wrap'   => '<a %s>%s</a>',
	);

	$args = wp_parse_args( $args, $defaults );


	$url = get_post_meta( get_the_ID(), 'portfolio_item_url', true );

	if ( !empty( $url ) ) {

		$text = sprintf( $args['text'], esc_html( $url ) );
		$attr = sprintf( 'class="portfolio-item-link" href="%s"', esc_url( $url ) );

		$html .= $args['before'];
		$html .= sprintf( $args['wrap'], $attr, $text );
		$html .= $args['after'];
	}

	return $html;
}
