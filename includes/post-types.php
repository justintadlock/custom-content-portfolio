<?php
/**
 * File for registering custom post types.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register custom post types on the 'init' hook.
add_action( 'init', 'ccp_register_post_types' );

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_register_post_types() {

	// Set up the arguments for the portfolio item post type.
	$project_args = array(
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 12,
		'menu_icon'           => 'dashicons-portfolio',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => ccp_get_portfolio_rewrite_base(),
		'query_var'           => 'portfolio_project',
		'capability_type'     => 'portfolio_project',
		'map_meta_cap'        => true,

		// Only 3 caps are needed: 'manage_portfolio', 'create_portfolio_projects', and 'edit_portfolio_projects'.
		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_portfolio_project',
			'read_post'              => 'read_portfolio_project',
			'delete_post'            => 'delete_portfolio_project',

			// primitive/meta caps
			'create_posts'           => 'create_portfolio_projects',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_portfolio_projects',
			'edit_others_posts'      => 'manage_portfolio',
			'publish_posts'          => 'manage_portfolio',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_portfolio',
			'delete_private_posts'   => 'manage_portfolio',
			'delete_published_posts' => 'manage_portfolio',
			'delete_others_posts'    => 'manage_portfolio',
			'edit_private_posts'     => 'edit_portfolio_projects',
			'edit_published_posts'   => 'edit_portfolio_projects'
		),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'       => ccp_get_project_rewrite_slug(),
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		// What features the post type supports.
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail'
		),

		// Labels used when displaying the posts.
		'labels' => array(
			'name'               => __( 'Projects',                   'custom-content-portfolio' ),
			'singular_name'      => __( 'Project',                    'custom-content-portfolio' ),
			'menu_name'          => __( 'Portfolio',                  'custom-content-portfolio' ),
			'name_admin_bar'     => __( 'Project',                    'custom-content-portfolio' ),
			'add_new'            => __( 'New Project',                'custom-content-portfolio' ),
			'add_new_item'       => __( 'Add New Project',            'custom-content-portfolio' ),
			'edit_item'          => __( 'Edit Project',               'custom-content-portfolio' ),
			'new_item'           => __( 'New Project',                'custom-content-portfolio' ),
			'view_item'          => __( 'View Project',               'custom-content-portfolio' ),
			'search_items'       => __( 'Search Projects',            'custom-content-portfolio' ),
			'not_found'          => __( 'No projects found',          'custom-content-portfolio' ),
			'not_found_in_trash' => __( 'No projects found in trash', 'custom-content-portfolio' ),
			'all_items'          => __( 'Projects',                   'custom-content-portfolio' ),

			// Custom labels b/c WordPress doesn't have anything to handle this.
			'archive_title'      => __( 'Portfolio',                  'custom-content-portfolio' ),
		)
	);

	// Register the post types.
	register_post_type( 'portfolio_project', $project_args );
}
