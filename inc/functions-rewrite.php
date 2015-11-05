<?php
/**
 * Plugin rewrite functions.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Add custom rewrite rules.
add_action( 'init', 'ccp_rewrite_rules', 5 );

/**
 * Adds custom rewrite rules for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_rewrite_rules() {

	$project_type = ccp_get_project_post_type();
	$author_slug  = ccp_get_author_rewrite_slug();

	// Where to place the rewrite rules.  If no rewrite base, put them at the bottom.
	$after = ccp_get_author_rewrite_base() ? 'top' : 'bottom';

	add_rewrite_rule( $author_slug . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?post_type=' . $project_type . '&author_name=$matches[1]&paged=$matches[2]', $after );
	add_rewrite_rule( $author_slug . '/([^/]+)/?$',                   'index.php?post_type=' . $project_type . '&author_name=$matches[1]',                   $after );
}

/**
 * Returns the project rewrite slug used for single projects.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_project_rewrite_slug() {
	$portfolio_base = ccp_get_portfolio_rewrite_base();
	$project_base   = ccp_get_project_rewrite_base();

	$slug = $project_base ? trailingslashit( $portfolio_base ) . $project_base : $portfolio_base;

	return apply_filters( 'ccp_get_project_rewrite_slug', $slug );
}

/**
 * Returns the category rewrite slug used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_category_rewrite_slug() {
	$portfolio_base = ccp_get_portfolio_rewrite_base();
	$category_base  = ccp_get_category_rewrite_base();

	$slug = $category_base ? trailingslashit( $portfolio_base ) . $category_base : $portfolio_base;

	return apply_filters( 'ccp_get_category_rewrite_slug', $slug );
}

/**
 * Returns the tag rewrite slug used for tag archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_tag_rewrite_slug() {
	$portfolio_base = ccp_get_portfolio_rewrite_base();
	$tag_base       = ccp_get_tag_rewrite_base();

	$slug = $tag_base ? trailingslashit( $portfolio_base ) . $tag_base : $portfolio_base;

	return apply_filters( 'ccp_get_tag_rewrite_slug', $slug );
}

/**
 * Returns the author rewrite slug used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_author_rewrite_slug() {
	$portfolio_base = ccp_get_portfolio_rewrite_base();
	$author_base  = ccp_get_author_rewrite_base();

	$slug = $author_base ? trailingslashit( $portfolio_base ) . $author_base : $portfolio_base;

	return apply_filters( 'ccp_get_author_rewrite_slug', $slug );
}
