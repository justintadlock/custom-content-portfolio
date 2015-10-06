<?php
/**
 * File for registering custom taxonomies.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register taxonomies on the 'init' hook.
add_action( 'init', 'ccp_register_taxonomies', 9 );

/**
 * Register taxonomies for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void.
 */
function ccp_register_taxonomies() {

	// Get the plugin settings.
	$settings = get_option( 'plugin_custom_content_portfolio', ccp_get_default_settings() );

	// Set up the arguments for the portfolio category taxonomy.
	$cat_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'query_var'         => 'portfolio_category',

		// Only 2 caps are needed: 'manage_portfolio' and 'edit_portfolio_projects'.
		'capabilities' => array(
			'manage_terms' => 'manage_portfolio',
			'edit_terms'   => 'manage_portfolio',
			'delete_terms' => 'manage_portfolio',
			'assign_terms' => 'edit_portfolio_projects',
		),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => 'portfolio/category',
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),

		// Labels used when displaying taxonomy and terms.
		'labels' => array(
			'name'                       => __( 'Portfolios',                           'custom-content-portfolio' ),
			'singular_name'              => __( 'Portfolio',                            'custom-content-portfolio' ),
			'menu_name'                  => __( 'Portfolios',                           'custom-content-portfolio' ),
			'name_admin_bar'             => __( 'Portfolio',                            'custom-content-portfolio' ),
			'search_items'               => __( 'Search Portfolios',                    'custom-content-portfolio' ),
			'popular_items'              => __( 'Popular Portfolios',                   'custom-content-portfolio' ),
			'all_items'                  => __( 'All Portfolios',                       'custom-content-portfolio' ),
			'edit_item'                  => __( 'Edit Portfolio',                       'custom-content-portfolio' ),
			'view_item'                  => __( 'View Portfolio',                       'custom-content-portfolio' ),
			'update_item'                => __( 'Update Portfolio',                     'custom-content-portfolio' ),
			'add_new_item'               => __( 'Add New Portfolio',                    'custom-content-portfolio' ),
			'new_item_name'              => __( 'New Portfolio Name',                   'custom-content-portfolio' ),
			'separate_items_with_commas' => __( 'Separate portfolios with commas',      'custom-content-portfolio' ),
			'add_or_remove_items'        => __( 'Add or remove portfolios',             'custom-content-portfolio' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolios', 'custom-content-portfolio' ),
		)
	);

	// Set up the arguments for the portfolio tag taxonomy.
	$tag_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => false,
		'query_var'         => 'portfolio_tag',

		// Only 2 caps are needed: 'manage_portfolio' and 'edit_portfolio_projects'.
		'capabilities' => array(
			'manage_terms' => 'manage_portfolio',
			'edit_terms'   => 'manage_portfolio',
			'delete_terms' => 'manage_portfolio',
			'assign_terms' => 'edit_portfolio_projects',
		),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => 'portfolio/tags',
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),

		// Labels used when displaying taxonomy and terms.
		'labels' => array(
			'name'                       => __( 'Tags',                           'custom-content-portfolio' ),
			'singular_name'              => __( 'Tag',                            'custom-content-portfolio' ),
			'menu_name'                  => __( 'Tags',                           'custom-content-portfolio' ),
			'name_admin_bar'             => __( 'Tag',                            'custom-content-portfolio' ),
			'search_items'               => __( 'Search Tags',                    'custom-content-portfolio' ),
			'popular_items'              => __( 'Popular Tags',                   'custom-content-portfolio' ),
			'all_items'                  => __( 'All Tags',                       'custom-content-portfolio' ),
			'edit_item'                  => __( 'Edit Tag',                       'custom-content-portfolio' ),
			'view_item'                  => __( 'View Tag',                       'custom-content-portfolio' ),
			'update_item'                => __( 'Update Tag',                     'custom-content-portfolio' ),
			'add_new_item'               => __( 'Add New Tag',                    'custom-content-portfolio' ),
			'new_item_name'              => __( 'New Tag Name',                   'custom-content-portfolio' ),
			'separate_items_with_commas' => __( 'Separate tags with commas',      'custom-content-portfolio' ),
			'add_or_remove_items'        => __( 'Add or remove tags',             'custom-content-portfolio' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'custom-content-portfolio' ),
		)
	);

	// Register the taxonomies.
	register_taxonomy( 'portfolio_category', array( 'portfolio_project' ), $cat_args );
	register_taxonomy( 'portfolio_tag',      array( 'portfolio_project' ), $tag_args );
}
