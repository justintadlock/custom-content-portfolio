<?php
/**
 * Admin functions for the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Set up the admin functionality.
add_action( 'admin_menu', 'ccp_admin_setup' );

/**
 * Adds actions where needed for setting up the plugin's admin functionality.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_admin_setup() {

	$project_type = ccp_get_project_post_type();

	// Custom columns on the edit portfolio items screen.
	add_filter( "manage_edit-{$project_type}_columns",        'ccp_edit_portfolio_item_columns'          );
	add_action( "manage_{$project_type}_posts_custom_column", 'ccp_manage_portfolio_item_columns', 10, 2 );
}

/**
 * Sets up custom columns on the portfolio items edit screen.
 *
 * @since  0.1.0
 * @access public
 * @param  array  $columns
 * @return array
 */
function ccp_edit_portfolio_item_columns( $columns ) {

	$new_columns = array(
		'cb'    => $columns['cb'],
		'title' => __( 'Project', 'custom-content-portfolio' )
	);

	if ( current_theme_supports( 'post-thumbnails' ) )
		$new_columns['thumbnail'] = __( 'Thumbnail', 'custom-content-portfolio' );

	return array_merge( $new_columns, $columns );
}

/**
 * Displays the content of custom portfolio item columns on the edit screen.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $column
 * @param  int     $post_id
 * @return void
 */
function ccp_manage_portfolio_item_columns( $column, $post_id ) {

	if ( 'thumbnail' === $column ) {

		if ( has_post_thumbnail() )
			the_post_thumbnail( array( 40, 40 ) );

		elseif ( function_exists( 'get_the_image' ) )
			get_the_image( array( 'scan' => true, 'width' => 40, 'height' => 40, 'link' => false ) );
	}
}
