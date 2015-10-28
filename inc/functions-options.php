<?php
/**
 * Plugin options functions.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Returns the portfolio title.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_portfolio_title() {
	return apply_filters( 'ccp_get_portfolio_title', ccp_get_setting( 'portfolio_title' ) );
}

/**
 * Returns the portfolio description.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_portfolio_description() {
	return apply_filters( 'ccp_get_portfolio_description', ccp_get_setting( 'portfolio_description' ) );
}

/**
 * Returns the portfolio rewrite base. Used for the project archive and as a prefix for taxonomy,
 * author, and any other slugs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_portfolio_rewrite_base() {
	return apply_filters( 'ccp_get_portfolio_rewrite_base', ccp_get_setting( 'portfolio_rewrite_base' ) );
}

/**
 * Returns the project rewrite base. Used for single projects.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_project_rewrite_base() {
	return apply_filters( 'ccp_get_project_rewrite_base', ccp_get_setting( 'project_rewrite_base' ) );
}

/**
 * Returns the category rewrite base. Used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_category_rewrite_base() {
	return apply_filters( 'ccp_get_category_rewrite_base', ccp_get_setting( 'category_rewrite_base' ) );
}

/**
 * Returns the tag rewrite base. Used for tag archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_tag_rewrite_base() {
	return apply_filters( 'ccp_get_tag_rewrite_base', ccp_get_setting( 'tag_rewrite_base' ) );
}

/**
 * Returns the author rewrite base. Used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_author_rewrite_base() {
	return apply_filters( 'ccp_get_author_rewrite_base', ccp_get_setting( 'author_rewrite_base' ) );
}

/**
 * Returns the default category term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function ccp_get_default_category() {
	return apply_filters( 'ccp_get_default_category', 0 );
}

/**
 * Returns the default tag term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function ccp_get_default_tag() {
	return apply_filters( 'ccp_get_default_tag', 0 );
}

/**
 * Returns a plugin setting.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $setting
 * @return mixed
 */
function ccp_get_setting( $setting ) {

	$defaults = ccp_get_default_settings();
	$settings = wp_parse_args( get_option( 'ccp_settings', $defaults ), $defaults );

	return isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
}

/**
 * Returns the default settings for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return array
 */
function ccp_get_default_settings() {

	$settings = array(
		'portfolio_title'        => __( 'Portfolio', 'custom-content-portfolio' ),
		'portfolio_description'  => '',
		'portfolio_rewrite_base' => 'portfolio',
		'project_rewrite_base'   => 'projects',
		'category_rewrite_base'  => 'categories',
		'tag_rewrite_base'       => 'tags',
		'author_rewrite_base'    => 'authors'
	);

	return $settings;
}
