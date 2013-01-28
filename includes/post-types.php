<?php
/**
 * File for registering custom post types.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Register custom post types on the 'init' hook. */
add_action( 'init', 'ccp_register_post_types' );

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_register_post_types() {

	/* Get the plugin settings. */
	$settings = get_option( 'plugin_custom_content_portfolio', ccp_get_default_settings() );

	/* Set up the arguments for the portfolio item post type. */
	$args = array(
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 12,
		'menu_icon'           => CCP_URI . 'images/menu-icon.png',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => $settings['portfolio_root'],
		'query_var'           => 'portfolio_item',
		'capability_type'     => 'portfolio_item',
		'map_meta_cap'        => true,

		/* Only 3 caps are needed: 'manage_portfolio', 'create_portfolio_items', and 'edit_portfolio_items'. */
		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_portfolio_item',
			'read_post'              => 'read_portfolio_item',
			'delete_post'            => 'delete_portfolio_item',

			// primitive/meta caps
			'create_posts'           => 'create_portfolio_items',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_portfolio_items',
			'edit_others_posts'      => 'manage_portfolio',
			'publish_posts'          => 'manage_portfolio',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_portfolio',
			'delete_private_posts'   => 'manage_portfolio',
			'delete_published_posts' => 'manage_portfolio',
			'delete_others_posts'    => 'manage_portfolio',
			'edit_private_posts'     => 'edit_portfolio_items',
			'edit_published_posts'   => 'edit_portfolio_items'
		),

		/* The rewrite handles the URL structure. */
		'rewrite' => array(
			'slug'       => !empty( $settings['portfolio_item_base'] ) ? "{$settings['portfolio_root']}/{$settings['portfolio_item_base']}" : $settings['portfolio_root'],
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		/* What features the post type supports. */
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail'
		),

		/* Labels used when displaying the posts. */
		'labels' => array(
			'name'               => __( 'Portfolio Items',                   'custom-content-portfolio' ),
			'singular_name'      => __( 'Portfolio Item',                    'custom-content-portfolio' ),
			'menu_name'          => __( 'Portfolio',                         'custom-content-portfolio' ),
			'name_admin_bar'     => __( 'Portfolio Item',                    'custom-content-portfolio' ),
			'add_new'            => __( 'Add New',                           'custom-content-portfolio' ),
			'add_new_item'       => __( 'Add New Portfolio Item',            'custom-content-portfolio' ),
			'edit_item'          => __( 'Edit Portfolio Item',               'custom-content-portfolio' ),
			'new_item'           => __( 'New Portfolio Item',                'custom-content-portfolio' ),
			'view_item'          => __( 'View Portfolio Item',               'custom-content-portfolio' ),
			'search_items'       => __( 'Search Portfolio',                  'custom-content-portfolio' ),
			'not_found'          => __( 'No portfolio items found',          'custom-content-portfolio' ),
			'not_found_in_trash' => __( 'No portfolio items found in trash', 'custom-content-portfolio' ),
			'all_items'          => __( 'Portfolio Items',                   'custom-content-portfolio' ),

			// Custom labels b/c WordPress doesn't have anything to handle this.
			'archive_title'      => __( 'Portfolio',                         'custom-content-portfolio' ),
		)
	);

	/* Register the portfolio item post type. */
	register_post_type( 'portfolio_item', $args );
}

?>