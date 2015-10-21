<?php

/**
 * Sets up and handles the plugin settings screen.
 *
 * @since  1.0.0
 * @access public
 */
final class CCP_Settings_Page {

	/**
	 * Settings page name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $settings_page = '';

	/**
	 * Holds an array the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $settings = array();

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Sets up custom admin menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		// Create the settings page.
		$this->settings_page = add_submenu_page(
			'edit.php?post_type=portfolio_project',
			esc_html__( 'Portfolio Settings', 'custom-content-portfolio' ),
			esc_html__( 'Settings',           'custom-content-portfolio' ),
			apply_filters( 'ccp_settings_capability', 'manage_options' ),
			'settings',
			array( $this, 'settings_page' )
		);

		if ( $this->settings_page ) {

			// Register setings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Add help tabs.
		//	add_action( "load-{$this->settings_page}", array( $this, 'add_help_tabs' ) );

			// Enqueue scripts/styles.
		//	add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		}
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $hook_suffix
	 * @return void
	 */
	public function enqueue( $hook_suffix ) {

		if ( $this->settings_page !== $hook_suffix )
			return;

	//	wp_enqueue_script( 'members-settings' );
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	function register_settings() {

		// Get the current plugin settings w/o the defaults.
		$this->settings = get_option( 'ccp_settings', ccp_get_default_settings() );

		// Register the setting.
		register_setting( 'ccp_settings', 'ccp_settings', array( $this, 'validate_settings' ) );

		/* === Settings Sections === */

		add_settings_section( 'general',    esc_html__( 'General Settings', 'custom-content-portfolio' ), array( $this, 'section_general'    ), $this->settings_page );
		add_settings_section( 'permalinks', esc_html__( 'Permalinks',       'custom-content-portfolio' ), array( $this, 'section_permalinks' ), $this->settings_page );

		/* === Settings Fields === */

		// General section fields
		add_settings_field( 'portfolio_title',       esc_html__( 'Title',       'custom-content-portfolio' ), array( $this, 'field_portfolio_title'       ), $this->settings_page, 'general' );
		add_settings_field( 'portfolio_description', esc_html__( 'Description', 'custom-content-portfolio' ), array( $this, 'field_portfolio_description' ), $this->settings_page, 'general' );

		// Permalinks section fields.
		add_settings_field( 'portfolio_rewrite_base', esc_html__( 'Portfolio Base', 'custom-content-portfolio' ), array( $this, 'field_portfolio_rewrite_base' ), $this->settings_page, 'permalinks' );
		add_settings_field( 'project_rewrite_base',   esc_html__( 'Project Slug',   'custom-content-portfolio' ), array( $this, 'field_project_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'category_rewrite_base',  esc_html__( 'Category Slug',  'custom-content-portfolio' ), array( $this, 'field_category_rewrite_base'  ), $this->settings_page, 'permalinks' );
		add_settings_field( 'tag_rewrite_base',       esc_html__( 'Tag Slug',       'custom-content-portfolio' ), array( $this, 'field_tag_rewrite_base'       ), $this->settings_page, 'permalinks' );
	}

	/**
	 * Validates the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $input
	 * @return array
	 */
	function validate_settings( $settings ) {

		// Text boxes that cannot be empty.
		$settings['portfolio_rewrite_base'] = $settings['portfolio_rewrite_base'] ? sanitize_title_with_dashes( $settings['portfolio_rewrite_base'] ) : 'portfolio';
		$settings['project_rewrite_base']   = $settings['project_rewrite_base']   ? sanitize_title_with_dashes( $settings['project_rewrite_base']   ) : 'project';
		$settings['category_rewrite_base']  = $settings['category_rewrite_base']  ? sanitize_title_with_dashes( $settings['category_rewrite_base']  ) : 'categories';
		$settings['tag_rewrite_base']       = $settings['tag_rewrite_base']       ? sanitize_title_with_dashes( $settings['tag_rewrite_base']       ) : 'tags';
		$settings['portfolio_title']        = $settings['portfolio_title']        ? strip_tags( $settings['portfolio_title'] )                        : esc_html__( 'Portfolio', 'custom-content-portfolio' );

		// Kill evil scripts.
		$settings['portfolio_description'] = stripslashes( wp_filter_post_kses( addslashes( $settings['portfolio_description'] ) ) );

		// Return the validated/sanitized settings.
		return $settings;
	}

	/**
	 * General section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_general() { ?>

		<p class="description">
			<?php esc_html_e( 'General portfolio settings for your site.', 'custom-content-portfolio' ); ?>
		</p>
	<?php }

	/**
	 * Portfolio title field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_title() { ?>

		<label>
			<input type="text" name="ccp_settings[portfolio_title]" value="<?php echo esc_attr( ccp_get_portfolio_title() ); ?>" />
			<br />
			<span class="description"><?php esc_html_e( 'The name of your portfolio. Maybe used for the portfolio page title and other places, depending on your theme.', 'custom-content-portfolio' ); ?></span>
		</label>
	<?php }

	/**
	 * Portfolio description field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_description() {

		wp_editor(
			ccp_get_portfolio_description(),
			'ccp_portfolio_description',
			array(
				'textarea_name'    => 'ccp_settings[portfolio_description]',
				'drag_drop_upload' => true,
				'editor_height'    => 150
			)
		); ?>

		<p>
			<span class="description"><?php esc_html_e( 'Your portfolio description. This may be shown by your theme on the portfolio page.', 'custom-content-portfolio' ); ?></span>
		</p>
	<?php }

	/**
	 * Permalinks section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_permalinks() { ?>

		<p class="description">
			<?php esc_html_e( 'Set up custom permalinks for the portfolio section on your site.', 'custom-content-portfolio' ); ?>
		</p>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_portfolio_rewrite_base() { ?>

		<label>
			<input type="text" name="ccp_settings[portfolio_rewrite_base]" value="<?php echo esc_attr( ccp_get_portfolio_rewrite_base() ); ?>" />
			<br />
			<span class="description"><?php printf( esc_html__( 'The base slug for the portfolio section of the site: %s.', 'custom-content-portfolio' ), '<code>' . esc_url( home_url( ccp_get_portfolio_rewrite_base() ) ) . '</code>' ); ?></span>
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_project_rewrite_base() { ?>

		<label>
			<input type="text" name="ccp_settings[project_rewrite_base]" value="<?php echo esc_attr( ccp_get_project_rewrite_base() ); ?>" />
			<br />
			<span class="description"><?php printf( esc_html__( 'The base slug for portfolio projects: %s.', 'custom-content-portfolio' ), '<code>' . esc_url( home_url( ccp_get_project_rewrite_slug() ) ) . '</code>' ); ?></span>
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_category_rewrite_base() { ?>

		<label>
			<input type="text" name="ccp_settings[category_rewrite_base]" value="<?php echo esc_attr( ccp_get_category_rewrite_base() ); ?>" />
			<br />
			<span class="description"><?php printf( esc_html__( 'The base slug for portfolio categories: %s.', 'custom-content-portfolio' ), '<code>' . esc_url( home_url( ccp_get_category_rewrite_slug() ) ) . '</code>' ); ?></span>
		</label>
	<?php }

	/**
	 * Portfolio rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_tag_rewrite_base() { ?>

		<label>
			<input type="text" name="ccp_settings[tag_rewrite_base]" value="<?php echo esc_attr( ccp_get_tag_rewrite_base() ); ?>" />
			<br />
			<span class="description"><?php printf( esc_html__( 'The base slug for portfolio tags: %s.', 'custom-content-portfolio' ), '<code>' . esc_url( home_url( ccp_get_tag_rewrite_slug() ) ) . '</code>' ); ?></span>
		</label>
	<?php }

	/**
	 * Renders the settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_page() {

		flush_rewrite_rules(); ?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Portfolio Settings', 'custom-content-portfolio' ); ?></h1>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'ccp_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'custom-content-portfolio' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	/**
	 * Adds help tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		// Get the current screen.
		$screen = get_current_screen();

	/**
		// Roles/Caps help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'roles-caps',
				'title'    => esc_html__( 'Role and Capabilities', 'custom-content-portfolio' ),
				'callback' => array( $this, 'help_tab_roles_caps' )
			)
		);
	/**/

		// Get docs and help links.
	//	$docs_link = sprintf( '<li><a href="https://github.com/justintadlock/members/blob/master/readme.md">%s</a></li>', esc_html__( 'Documentation',  'custom-content-portfolio' ) );
	//	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>',                            esc_html__( 'Support Forums', 'custom-content-portfolio' ) );
	//	$tut_link  = sprintf( '<li><a href="http://justintadlock.com/archives/2009/08/30/users-roles-and-capabilities-in-wordpress">%s</a></li>', esc_html__( 'Users, Roles, and Capabilities', 'custom-content-portfolio' ) );

		// Set the help sidebar.
	//	$screen->set_help_sidebar( members_get_help_sidebar_text() );
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new self;

		return $instance;
	}
}

CCP_Settings_Page::get_instance();
