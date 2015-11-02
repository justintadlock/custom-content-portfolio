<?php
/**
 * Admin-related functions and filters.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register scripts and styles.
add_action( 'admin_enqueue_scripts', 'ccp_admin_register_scripts', 0 );
add_action( 'admin_enqueue_scripts', 'ccp_admin_register_styles',  0 );

# Registers project details box sections, controls, and settings.
add_action( 'ccp_project_details_manager_register', 'ccp_project_details_register', 5 );

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

	wp_register_script( 'ccp-edit-project', ccp_plugin()->js_uri . "edit-project{$min}.js", array( 'jquery' ), '', true );

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
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_admin_register_styles() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'ccp-admin', ccp_plugin()->css_uri . "admin{$min}.css" );
}

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_project_details_register( $manager ) {

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

	/* === Register Controls === */

	$url_args = array(
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://themehybrid.com' ),
		'label'       => esc_html__( 'URL', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the URL of the project Web page.', 'custom-content-portfolio' )
	);

	$client_args = array(
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Jane Doe', 'custom-content-portfolio' ) ),
		'label'       => esc_html__( 'Client', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the name of the client for the project.', 'custom-content-portfolio' )
	);

	$location_args = array(
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => __( 'Highland Home, AL', 'custom-content-portfolio' ) ),
		'label'       => esc_html__( 'Location', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Enter the physical location of the project.', 'custom-content-portfolio' )
	);

	$start_date_args = array(
		'section'     => 'date',
		'label'       => esc_html__( 'Start Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project began.', 'custom-content-portfolio' )
	);

	$end_date_args = array(
		'section'     => 'date',
		'label'       => esc_html__( 'End Date', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Select the date the project was completed.', 'custom-content-portfolio' )
	);

	$excerpt_args = array(
		'section'     => 'description',
		'type'        => 'textarea',
		'attr'        => array( 'id' => 'excerpt', 'name' => 'excerpt' ),
		'label'       => esc_html__( 'Description', 'custom-content-portfolio' ),
		'description' => esc_html__( 'Write a short description (excerpt) of the project.', 'custom-content-portfolio' )
	);

	$manager->register_control( new CCP_Fields_Control(         $manager, 'url',        $url_args        ) );
	$manager->register_control( new CCP_Fields_Control(         $manager, 'client',     $client_args     ) );
	$manager->register_control( new CCP_Fields_Control(         $manager, 'location',   $location_args   ) );
	$manager->register_control( new CCP_Fields_Control_Date(    $manager, 'start_date', $start_date_args ) );
	$manager->register_control( new CCP_Fields_Control_Date(    $manager, 'end_date',   $end_date_args   ) );
	$manager->register_control( new CCP_Fields_Control_Excerpt( $manager, 'excerpt',    $excerpt_args    ) );

	/* === Register Settings === */

	$manager->register_setting( 'url',      array( 'sanitize_callback' => 'esc_url_raw'       ) );
	$manager->register_setting( 'client',   array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_setting( 'location', array( 'sanitize_callback' => 'wp_strip_all_tags' ) );

	$manager->register_setting( new CCP_Fields_Setting_Date( $manager, 'start_date' ) );
	$manager->register_setting( new CCP_Fields_Setting_Date( $manager, 'end_date' ) );
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
	$docs_link = sprintf( '<li><a href="http://themehybrid.com/docs">%s</a></li>', esc_html__( 'Documentation', 'custom-cotent-portfolio' ) );
	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'custom-content-portfolio' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'custom-content-portfolio' ),
		$docs_link,
		$help_link
	);
}
