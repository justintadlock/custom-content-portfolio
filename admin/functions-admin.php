<?php

add_action( 'admin_enqueue_scripts', 'ccp_admin_register_scripts', 0 );
add_action( 'admin_enqueue_scripts', 'ccp_admin_register_styles',  0 );

function ccp_admin_register_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'ccp-edit-project', ccp_plugin()->js_uri . "edit-project{$min}.js", array( 'jquery' ), '', true );
}

function ccp_admin_register_styles() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'ccp-admin', ccp_plugin()->css_uri . "admin{$min}.css" );
}

# Registers default groups.
add_action( 'ccp_project_details_manager_register', 'ccp_project_details_register', 5 );

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function ccp_project_details_register( $manager ) {

	$manager->register_section( 'general',
		array(
			'label' => esc_html__( 'General', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

	$manager->register_section( 'date',
		array(
			'label' => esc_html__( 'Date', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-clock'
		)
	);

	$manager->register_section( 'description',
		array(
			'label' => esc_html__( 'Description', 'custom-content-portfolio' ),
			'icon'  => 'dashicons-edit'
		)
	);

	$manager->register_control( 'url',
		array(
			'section'     => 'general',
			'label'       => esc_html__( 'URL', 'custom-content-portfolio' ),
			'description' => esc_html__( 'Enter the URL of the project Web page.', 'custom-content-portfolio' )
		)
	);

	$manager->register_control( 'client',
		array(
			'section'     => 'general',
			'label'       => esc_html__( 'Client', 'custom-content-portfolio' ),
			'description' => esc_html__( 'Enter the name of the client for the project.', 'custom-content-portfolio' )
		)
	);

	$manager->register_control( 'location',
		array(
			'section'     => 'general',
			'label'       => esc_html__( 'Location', 'custom-content-portfolio' ),
			'description' => esc_html__( 'Enter the physical location of the project.', 'custom-content-portfolio' )
		)
	);

	$manager->register_control( 'start_date',
		array(
			'object'      => 'CCP_Project_Details_Control_Date',
			'section'     => 'date',
			'label'       => esc_html__( 'Start Date', 'custom-content-portfolio' ),
			'description' => esc_html__( 'Select the date the project began.', 'custom-content-portfolio' )
		)
	);

	$manager->register_control( 'end_date',
		array(
			'object'      => 'CCP_Project_Details_Control_Date',
			'section'     => 'date',
			'label'       => esc_html__( 'End Date', 'custom-content-portfolio' ),
			'description' => esc_html__( 'Select the date the project was completed.', 'custom-content-portfolio' )
		)
	);

	if ( ! post_type_supports( ccp_get_project_post_type(), 'excerpt' ) ) {

		$manager->register_control( 'excerpt',
			array(
				'object'      => 'CCP_Project_Details_Control_Excerpt',
				'section'     => 'description',
				'label'       => esc_html__( 'Description', 'custom-content-portfolio' ),
				'description' => esc_html__( 'Write a short description (excerpt) of the project.', 'custom-content-portfolio' )
			)
		);
	}

	$manager->register_setting( 'url',      array( 'sanitize_callback' => 'esc_url_raw'       ) );
	$manager->register_setting( 'client',   array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_setting( 'location', array( 'sanitize_callback' => 'wp_strip_all_tags' ) );
	$manager->register_setting( 'start_date', array( 'object' => 'CCP_Project_Details_Setting_Date' ) );
	$manager->register_setting( 'end_date', array( 'object' => 'CCP_Project_Details_Setting_Date' ) );
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
	$docs_link = sprintf( '<li><a href="https://github.com/justintadlock/custom-content-portfolio/blob/master/readme.md">%s</a></li>', esc_html__( 'Documentation', 'custom-cotent-portfolio' ) );
	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'custom-content-portfolio' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'custom-content-portfolio' ),
		$docs_link,
		$help_link
	);
}
