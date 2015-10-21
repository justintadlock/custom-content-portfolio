<?php

function ccp_get_portfolio_title() {
	return apply_filters( 'ccp_get_portfolio_title', ccp_get_setting( 'portfolio_title' ) );
}

function ccp_get_portfolio_description() {
	return apply_filters( 'ccp_get_portfolio_description', ccp_get_setting( 'portfolio_description' ) );
}

function ccp_get_portfolio_rewrite_base() {
	return apply_filters( 'ccp_get_portfolio_rewrite_base', ccp_get_setting( 'portfolio_rewrite_base' ) );
}

function ccp_get_project_rewrite_base() {
	return apply_filters( 'ccp_get_project_rewrite_base', ccp_get_setting( 'project_rewrite_base' ) );
}

function ccp_get_category_rewrite_base() {
	return apply_filters( 'ccp_get_category_rewrite_base', ccp_get_setting( 'category_rewrite_base' ) );
}

function ccp_get_tag_rewrite_base() {
	return apply_filters( 'ccp_get_tag_rewrite_base', ccp_get_setting( 'tag_rewrite_base' ) );
}

function ccp_get_author_rewrite_base() {
	return apply_filters( 'ccp_get_author_rewrite_base', ccp_get_setting( 'author_rewrite_base' ) );
}

function ccp_get_project_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_project_rewrite_base();

	return apply_filters( 'ccp_get_project_rewrite_slug', $slug );
}

function ccp_get_category_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_category_rewrite_base();

	return apply_filters( 'ccp_get_category_rewrite_slug', $slug );
}

function ccp_get_tag_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_tag_rewrite_base();

	return apply_filters( 'ccp_get_tag_rewrite_slug', $slug );
}

function ccp_get_author_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_author_rewrite_base();

	return apply_filters( 'ccp_get_author_rewrite_slug', $slug );
}

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
