<?php
/**
 * Admin-related functions and filters.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justintadlock@gmail.com>
 * @copyright  Copyright (c) 2013-2017, Justin Tadlock
 * @link       https://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register scripts and styles.
add_action( 'admin_enqueue_scripts', 'ccp_admin_register_scripts', 0 );
// add_action( 'admin_enqueue_scripts', 'ccp_admin_register_styles',  0 );

# Registers project details box sections, controls, and settings.
add_action( 'butterbean_register', 'ccp_project_details_register', 5, 2 );

# Filter post format support for projects.
add_action( 'load-post.php',     'ccp_post_format_support_filter' );
add_action( 'load-post-new.php', 'ccp_post_format_support_filter' );
add_action( 'load-edit.php',     'ccp_post_format_support_filter' );

/**
 * Registers admin scripts.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_admin_register_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'ccp-edit-project', ccp_plugin()->js_uri . "edit-project{$min}.js", array( 'jquery', 'wp-util' ), '', true );

	// Localize our script with some text we want to pass in.
	$i18n = array(
		'label_sticky'     => esc_html__( 'Sticky',     'custom-content-portfolio' ),
		'label_not_sticky' => esc_html__( 'Not Sticky', 'custom-content-portfolio' ),
	);

	wp_localize_script( 'ccp-edit-project', 'ccp_i18n', $i18n );
}

/**
 * Registers admin styles.
 *
 * @since       1.0.0
 * @deprecated  2.0.0  Note: May need function in the future.
 * @access      public
 * @return      void
 */
function ccp_admin_register_styles() {}

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_project_details_register( $butterbean, $post_type ) {

	if ( $post_type !== ccp_get_project_post_type() )
		return;

	$butterbean->register_manager( 'ccp-project',
		array(
			'post_type' => $post_type,
			'context'   => 'normal',
			'priority'  => 'high',
			'label'     => esc_html__( 'Project Details:', 'custom-content-portfolio' )
		)
	);

	$manager = $butterbean->get_manager( 'ccp-project' );

	/* === Register Sections === */

	// General section.
	$manager->register_section( 'general',
		array(
			'label' => esc_html__( 'General', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

	// Date section.
	$manager->register_section( 'date',
		array(
			'label' => esc_html__( 'Date', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-clock'
		)
	);

	// Description section.
	$manager->register_section( 'description',
		array(
			'label' => esc_html__( 'Description', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-edit'
		)
	);

	/* === Register Fields === */

	$url_args = array(
		'type'        => 'url',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'https://themehybrid.com' ),
		'label'       => esc_html__( 'URL', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the URL of the project Web page.', 'custom-content-portfolio' )
	);

	$client_args = array(
		'type'        => 'text',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Jane Doe', 'custom-content-portfolio' ) ),
		'label'       => esc_html__( 'Client', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the name of the client for the project.', 'custom-content-portfolio' )
	);

	$location_args = array(
		'type'        => 'text',
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Highland Home, AL', 'custom-content-portfolio' ) ),
		'label'       => esc_html__( 'Location', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the physical location of the project.', 'custom-content-portfolio' )
	);

	$start_date_args = array(
		'type'        => 'datetime',
		'section'     => 'date',
		'show_time'   => false,
		'label'       => esc_html__( 'Start Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project began.', 'custom-content-portfolio' )
	);

	$end_date_args = array(
		'type'        => 'datetime',
		'section'     => 'date',
		'show_time'   => false,
		'label'       => esc_html__( 'End Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project was completed.', 'custom-content-portfolio' )
	);

	$manager->register_field( 'url',      $url_args,      array( 'sanitize_callback' => 'esc_url_raw'       ) );
	$manager->register_field( 'client',   $client_args,   array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_field( 'location', $location_args, array( 'sanitize_callback' => 'wp_strip_all_tags' ) );

	$manager->register_field( 'start_date', $start_date_args, array( 'type' => 'datetime' ) );
	$manager->register_field( 'end_date',   $end_date_args,   array( 'type' => 'datetime' ) );

	/* === Register Controls === */

	$excerpt_args = array(
		'type'        => 'excerpt',
		'section'     => 'description',
		'label'       => esc_html__( 'Description', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Write a short description (excerpt) of the project.', 'custom-content-portfolio' )
	);

	$manager->register_control( 'excerpt', $excerpt_args );
}

/**
 * Helper function for getting the correct slug for the settings page.  This is useful
 * for add-on plugins that need to add custom setting sections or fields to the settings
 * screen for the plugin.
 *
 * @since  2.0.0
 * @access public
 * @return string
 */
function ccp_get_settings_page_slug() {

	return sprintf( '%s_page_ccp-settings', ccp_get_project_post_type() );
}

/**
 * Returns an array of post formats allowed for the project post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function ccp_get_allowed_project_formats() {

	return apply_filters( 'ccp_get_allowed_project_formats', array( 'audio', 'gallery', 'image', 'video' ) );
}

/**
 * If a theme supports post formats, limit project to only only the audio, image,
 * gallery, and video formats.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_post_format_support_filter() {

	$screen       = get_current_screen();
	$project_type = ccp_get_project_post_type();

	// Bail if not on the projects screen.
	if ( empty( $screen->post_type ) || $project_type !== $screen->post_type )
		return;

	// Check if the current theme supports formats.
	if ( current_theme_supports( 'post-formats' ) ) {

		$formats = get_theme_support( 'post-formats' );

		// If we have formats, add theme support for only the allowed formats.
		if ( isset( $formats[0] ) ) {
			$new_formats = array_intersect( $formats[0], ccp_get_allowed_project_formats() );

			// Remove post formats support.
			remove_theme_support( 'post-formats' );

			// If the theme supports the allowed formats, add support for them.
			if ( $new_formats )
				add_theme_support( 'post-formats', $new_formats );
		}
	}

	// Filter the default post format.
	add_filter( 'option_default_post_format', 'ccp_default_post_format_filter', 95 );
}

/**
 * Filters the default post format to make sure that it's in our list of supported formats.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $format
 * @return string
 */
function ccp_default_post_format_filter( $format ) {

	return in_array( $format, ccp_get_allowed_project_formats() ) ? $format : 'standard';
}

/**
 * Help sidebar for all of the help tabs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function ccp_get_help_sidebar_text() {

	// Get docs and help links.
	$docs_link = sprintf( '<li><a href="https://themehybrid.com/docs">%s</a></li>', esc_html__( 'Documentation', 'custom-content-portfolio' ) );
	$help_link = sprintf( '<li><a href="https://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'custom-content-portfolio' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'custom-content-portfolio' ),
		$docs_link,
		$help_link
	);
}
