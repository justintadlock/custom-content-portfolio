<?php
/**
 * File for registering custom taxonomies.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register taxonomies on the 'init' hook.
add_action( 'init', 'ccp_register_taxonomies', 9 );

# Filter the term updated messages.
add_filter( 'term_updated_messages', 'ccp_term_updated_messages', 5 );

/**
 * Returns the name of the portfolio category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_category_taxonomy() {

	return apply_filters( 'ccp_get_category_taxonomy', 'portfolio_category' );
}

/**
 * Returns the name of the portfolio tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_tag_taxonomy() {

	return apply_filters( 'ccp_get_tag_taxonomy', 'portfolio_tag' );
}

/**
 * Returns the capabilities for the portfolio category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_category_capabilities() {

	$caps = array(
		'manage_terms' => 'manage_portfolio_categories',
		'edit_terms'   => 'manage_portfolio_categories',
		'delete_terms' => 'manage_portfolio_categories',
		'assign_terms' => 'edit_portfolio_projects'
	);

	return apply_filters( 'ccp_get_category_capabilities', $caps );
}

/**
 * Returns the capabilities for the portfolio tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_tag_capabilities() {

	$caps = array(
		'manage_terms' => 'manage_portfolio_tags',
		'edit_terms'   => 'manage_portfolio_tags',
		'delete_terms' => 'manage_portfolio_tags',
		'assign_terms' => 'edit_portfolio_projects',
	);

	return apply_filters( 'ccp_get_tag_capabilities', $caps );
}

/**
 * Returns the labels for the portfolio category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_category_labels() {

	$labels = array(
		'name'                       => __( 'Categories',                           'custom-content-portfolio' ),
		'singular_name'              => __( 'Category',                             'custom-content-portfolio' ),
		'menu_name'                  => __( 'Categories',                           'custom-content-portfolio' ),
		'name_admin_bar'             => __( 'Category',                             'custom-content-portfolio' ),
		'search_items'               => __( 'Search Categories',                    'custom-content-portfolio' ),
		'popular_items'              => __( 'Popular Categories',                   'custom-content-portfolio' ),
		'all_items'                  => __( 'All Categories',                       'custom-content-portfolio' ),
		'edit_item'                  => __( 'Edit Category',                        'custom-content-portfolio' ),
		'view_item'                  => __( 'View Category',                        'custom-content-portfolio' ),
		'update_item'                => __( 'Update Category',                      'custom-content-portfolio' ),
		'add_new_item'               => __( 'Add New Category',                     'custom-content-portfolio' ),
		'new_item_name'              => __( 'New Category Name',                    'custom-content-portfolio' ),
		'not_found'                  => __( 'No categories found.',                 'custom-content-portfolio' ),
		'no_terms'                   => __( 'No categories',                        'custom-content-portfolio' ),
		'pagination'                 => __( 'Categories list navigation',           'custom-content-portfolio' ),
		'list'                       => __( 'Categories list',                      'custom-content-portfolio' ),

		// Hierarchical only.
		'select_name'                => __( 'Select Category',                      'custom-content-portfolio' ),
		'parent_item'                => __( 'Parent Category',                      'custom-content-portfolio' ),
		'parent_item_colon'          => __( 'Parent Category:',                     'custom-content-portfolio' ),
	);

	return apply_filters( 'ccp_get_category_labels', $labels );
}

/**
 * Returns the labels for the portfolio tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_tag_labels() {

	$labels = array(
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
		'not_found'                  => __( 'No tags found.',                 'custom-content-portfolio' ),
		'no_terms'                   => __( 'No tags',                        'custom-content-portfolio' ),
		'pagination'                 => __( 'Tags list navigation',           'custom-content-portfolio' ),
		'list'                       => __( 'Tags list',                      'custom-content-portfolio' ),

		// Non-hierarchical only.
		'separate_items_with_commas' => __( 'Separate tags with commas',      'custom-content-portfolio' ),
		'add_or_remove_items'        => __( 'Add or remove tags',             'custom-content-portfolio' ),
		'choose_from_most_used'      => __( 'Choose from the most used tags', 'custom-content-portfolio' ),
	);

	return apply_filters( 'ccp_get_tag_labels', $labels );
}

/**
 * Register taxonomies for the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void.
 */
function ccp_register_taxonomies() {

	// Set up the arguments for the portfolio category taxonomy.
	$cat_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'query_var'         => ccp_get_category_taxonomy(),
		'capabilities'      => ccp_get_category_capabilities(),
		'labels'            => ccp_get_category_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => ccp_get_category_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),
	);

	// Set up the arguments for the portfolio tag taxonomy.
	$tag_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => false,
		'query_var'         => ccp_get_tag_taxonomy(),
		'capabilities'      => ccp_get_tag_capabilities(),
		'labels'            => ccp_get_tag_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => ccp_get_tag_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),
	);

	// Register the taxonomies.
	register_taxonomy( ccp_get_category_taxonomy(), ccp_get_project_post_type(), apply_filters( 'ccp_category_taxonomy_args', $cat_args ) );
	register_taxonomy( ccp_get_tag_taxonomy(),      ccp_get_project_post_type(), apply_filters( 'ccp_tag_taxonomy_args',      $tag_args ) );
}

/**
 * Filters the term updated messages in the admin.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @return array
 */
function ccp_term_updated_messages( $messages ) {

	$cat_taxonomy = ccp_get_category_taxonomy();
	$tag_taxonomy = ccp_get_tag_taxonomy();

	// Add the portfolio category messages.
	$messages[ $cat_taxonomy ] = array(
		0 => '',
		1 => __( 'Category added.',       'custom-content-portfolio' ),
		2 => __( 'Category deleted.',     'custom-content-portfolio' ),
		3 => __( 'Category updated.',     'custom-content-portfolio' ),
		4 => __( 'Category not added.',   'custom-content-portfolio' ),
		5 => __( 'Category not updated.', 'custom-content-portfolio' ),
		6 => __( 'Categories deleted.',   'custom-content-portfolio' ),
	);

	// Add the portfolio tag messages.
	$messages[ $tag_taxonomy ] = array(
		0 => '',
		1 => __( 'Tag added.',       'custom-content-portfolio' ),
		2 => __( 'Tag deleted.',     'custom-content-portfolio' ),
		3 => __( 'Tag updated.',     'custom-content-portfolio' ),
		4 => __( 'Tag not added.',   'custom-content-portfolio' ),
		5 => __( 'Tag not updated.', 'custom-content-portfolio' ),
		6 => __( 'Tags deleted.',    'custom-content-portfolio' ),
	);

	return $messages;
}
