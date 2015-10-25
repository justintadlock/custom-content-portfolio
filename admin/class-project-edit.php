<?php

/**
 * Project edit screen functionality.
 *
 * @since  1.0.0
 * @access public
 */
final class CCP_Project_Edit {

	public $manager = '';

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );
	}

	/**
	 * Runs on the page load. Checks if we're viewing the project post type and adds
	 * the appropriate actions/filters for the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$screen       = get_current_screen();
		$project_type = ccp_get_project_post_type();

		// Bail if not on the projects screen.
		if ( empty( $screen->post_type ) || $project_type !== $screen->post_type )
			return;

		// Create a new project details manager.
		$this->manager = new CCP_Fields_Manager( 'project_details' );

		// Enqueue scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// @todo - make a meta box.
		add_action( 'edit_form_after_editor', array( $this, 'project_details_box' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ) );
	}

	/**
	 * Load scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		wp_enqueue_style( 'ccp-admin' );
		wp_enqueue_script( 'ccp-edit-project' );
	}

	/**
	 * Output the project details box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @return void
	 */
	public function project_details_box( $post ) { ?>

		<div id="ccp-project-tabs" class="postbox">

			<h3><?php printf( esc_html__( 'Project Details: %s', 'members' ), '<span class="ccp-which-tab"></span>' ); ?></h3>

			<div class="inside">
				<?php $this->manager->display( $post->ID ); ?>
			</div><!-- .inside -->

		</div><!-- .postbox -->
	<?php }

	/**
	 * Save project details settings on post save.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function update( $post_id ) {

		$this->manager->update( $post_id );
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

CCP_Project_Edit::get_instance();
