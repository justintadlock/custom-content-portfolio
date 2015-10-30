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
 * Returns the name of the portfolio type taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_type_taxonomy() {

	return apply_filters( 'ccp_get_type_taxonomy', 'portfolio_type' );
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
 * Returns the capabilities for the portfolio type taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_type_capabilities() {

	$caps = array(
		'manage_terms' => 'do_not_allow',
		'edit_terms'   => 'do_not_allow',
		'delete_terms' => 'do_not_allow',
		'assign_terms' => 'publish_portfolio_projects',
	);

	return apply_filters( 'ccp_get_type_capabilities', $caps );
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
 * Returns the labels for the portfolio tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_type_labels() {

	$labels = array(
		'name'                       => __( 'Types',                           'custom-content-portfolio' ),
		'singular_name'              => __( 'Type',                            'custom-content-portfolio' ),
		'menu_name'                  => __( 'Types',                           'custom-content-portfolio' ),
		'name_admin_bar'             => __( 'Type',                            'custom-content-portfolio' ),
		'search_items'               => __( 'Search Types',                    'custom-content-portfolio' ),
		'popular_items'              => __( 'Popular Types',                   'custom-content-portfolio' ),
		'all_items'                  => __( 'All Types',                       'custom-content-portfolio' ),
		'edit_item'                  => __( 'Edit Type',                       'custom-content-portfolio' ),
		'view_item'                  => __( 'View Type',                       'custom-content-portfolio' ),
		'update_item'                => __( 'Update Type',                     'custom-content-portfolio' ),
		'add_new_item'               => __( 'Add New Type',                    'custom-content-portfolio' ),
		'new_item_name'              => __( 'New Type Name',                   'custom-content-portfolio' ),
		'not_found'                  => __( 'No types found.',                 'custom-content-portfolio' ),
		'no_terms'                   => __( 'No types',                        'custom-content-portfolio' ),
		'pagination'                 => __( 'Types list navigation',           'custom-content-portfolio' ),
		'list'                       => __( 'Types list',                      'custom-content-portfolio' ),

		// Non-hierarchical only.
		'separate_items_with_commas' => __( 'Separate types with commas',      'custom-content-portfolio' ),
		'add_or_remove_items'        => __( 'Add or remove types',             'custom-content-portfolio' ),
		'choose_from_most_used'      => __( 'Choose from the most used types', 'custom-content-portfolio' ),
	);

	return apply_filters( 'ccp_get_type_labels', $labels );
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
		'query_var'         => 'portfolio_category',
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
		'query_var'         => 'portfolio_tag',
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

	// Set up the arguments for the portfolio type taxonomy.
	$type_args = array(
		'public'            => false,
		'show_ui'           => false,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => true,
		'show_admin_column' => false,
		'hierarchical'      => false,
		'query_var'         => ccp_get_type_taxonomy(),
		'capabilities'      => ccp_get_type_capabilities(),
		'labels'            => ccp_get_type_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => false,
	);

	// Register the taxonomies.
	register_taxonomy( ccp_get_category_taxonomy(), ccp_get_project_post_type(), apply_filters( 'ccp_category_taxonomy_args', $cat_args ) );
	register_taxonomy( ccp_get_tag_taxonomy(),      ccp_get_project_post_type(), apply_filters( 'ccp_tag_taxonomy_args',      $tag_args ) );
	register_taxonomy( ccp_get_type_taxonomy(),     ccp_get_project_post_type(), apply_filters( 'ccp_type_taxonomy_args',     $type_args ) );
}
