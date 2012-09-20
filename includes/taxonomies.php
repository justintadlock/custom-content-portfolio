<?php

add_action( 'init', 'cpt_portfolio_register_taxonomies', 9 );

/**
 * Register taxonomies for the plugin.
 *
 * @since 0.1.0
 */
function cpt_portfolio_register_taxonomies() {

	/* Get the plugin settings. */
	$settings = get_option( 'plugin_cpt_portfolio', cpt_portfolio_get_default_settings() );

	/* Set up the arguments for the portfolio taxonomy. */
	$args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'hierarchical'      => true,       // @todo Decide whether to go hierarchical or not.
		'query_var'         => 'portfolio',

		/* Only 2 caps are needed: 'manage_portfolio' and 'edit_portfolio_items'. */
		'capabilities' => array(
			'manage_terms' => 'manage_portfolio',
			'edit_terms'   => 'manage_portfolio',
			'delete_terms' => 'manage_portfolio',
			'assign_terms' => 'edit_portfolio_items',
		),

		/* The rewrite handles the URL structure. */
		'rewrite' => array(
			'slug'         => !empty( $settings['portfolio_base'] ) ? "{$settings['portfolio_root']}/{$settings['portfolio_base']}" : $settings['portfolio_root'],
			'with_front'   => false,
			'hierarchical' => true,
			'ep_mask'      => EP_NONE
		),

		/* Labels used when displaying taxonomy and terms. */
		'labels' => array(
			'name'                       => __( 'Portfolios',                           'cpt-portfolio' ),
			'singular_name'              => __( 'Portfolio',                            'cpt-portfolio' ),
			'menu_name'                  => __( 'Portfolios',                           'cpt-portfolio' ),
			'name_admin_bar'             => __( 'Portfolio',                            'cpt-portfolio' ),
			'search_items'               => __( 'Search Portfolios',                    'cpt-portfolio' ),
			'popular_items'              => __( 'Popular Portfolios',                   'cpt-portfolio' ),
			'all_items'                  => __( 'All Portfolios',                       'cpt-portfolio' ),
			'edit_item'                  => __( 'Edit Portfolio',                       'cpt-portfolio' ),
			'view_item'                  => __( 'View Portfolio',                       'cpt-portfolio' ),
			'update_item'                => __( 'Update Portfolio',                     'cpt-portfolio' ),
			'add_new_item'               => __( 'Add New Portfolio',                    'cpt-portfolio' ),
			'new_item_name'              => __( 'New Portfolio Name',                   'cpt-portfolio' ),
			'separate_items_with_commas' => __( 'Separate portfolios with commas',      'cpt-portfolio' ),
			'add_or_remove_items'        => __( 'Add or remove portfolios',             'cpt-portfolio' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolios', 'cpt-portfolio' ),
			'parent_item'                => __( 'Parent Portfolio',                     'cpt-portfolio' ),
			'paent_item_colon'           => __( 'Parent Portfolio:',                    'cpt-portfolio' )
		)
	);

	/* Register the 'portfolio' taxonomy. */
	register_taxonomy( 'portfolio', array( 'portfolio_item' ), $args );
}

?>