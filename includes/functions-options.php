<?php

function ccp_get_portfolio_rewrite_base() {
	return apply_filters( 'ccp_get_portfolio_rewrite_base', ccp_get_setting( 'portfolio_rewrite_base' ) );
}

function ccp_get_project_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_setting( 'project_rewrite_slug' );

	return apply_filters( 'ccp_get_project_rewrite_slug', $slug );
}

function ccp_get_category_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_setting( 'category_rewrite_slug' );

	return apply_filters( 'ccp_get_category_rewrite_slug', $slug );
}

function ccp_get_tag_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_setting( 'tag_rewrite_slug' );

	return apply_filters( 'ccp_get_tag_rewrite_slug', $slug );
}

function ccp_get_author_rewrite_slug() {
	$slug = trailingslashit( ccp_get_portfolio_rewrite_base() ) . ccp_get_setting( 'author_rewrite_slug' );

	return apply_filters( 'ccp_get_author_rewrite_slug', $slug );
}

function ccp_get_setting( $setting ) {

	$defaults = ccp_get_default_settings();
	$settings = get_option( 'plugin_custom_content_portfolio', $defaults );

	$settings = wp_parse_args( $settings, $defaults );

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
		'portfolio_rewrite_base' => 'portfolio',
		'project_rewrite_slug'   => 'projects',
		'category_rewrite_slug'  => 'categories',
		'tag_rewrite_slug'       => 'tags',
		'author_rewrite_slug'    => 'authors'
	);

	return $settings;
}
