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

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// @todo - make a meta box.
		add_action( 'edit_form_after_editor', array( $this, 'project_details_box' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ), 10, 2 );
	}

	public function enqueue() {

		wp_enqueue_style( 'ccp-admin' );

		wp_enqueue_script( 'ccp-edit-project' );
	}

	public function project_details_box( $post ) {

		$this->manager = new CCP_Project_Details_Manager( $post );

		$this->manager->display();
	}

	public function update( $post_id, $post = '' ) {

		if ( ! $post )
			$post = get_post( $post_id );

		if ( ! $this->manager )
			$this->manager = new CCP_Project_Details_Manager( $post );

		$this->manager->update();
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
