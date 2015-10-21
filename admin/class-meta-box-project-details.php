<?php

final class CCP_Meta_Box_Project_Details {

	/**
	 * Sets up the appropriate actions.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function __construct() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );
	}

	/**
	 * Fires on the page load hook to add actions specifically for the post and
	 * new post screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$project_type = ccp_get_project_post_type();

		// Add custom meta boxes.
		add_action( "add_meta_boxes_{$project_type}", array( $this, 'add_meta_boxes' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ) );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes() {

		add_meta_box( 'ccp-project-details', esc_html__( 'Project Details', 'custom-content-portfolio' ), array( $this, 'meta_box' ), ccp_get_project_post_type(), 'side', 'core' );
	}

	/**
	 * Outputs the meta box HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @global object  $wp_roles
	 * @return void
	 */
	public function meta_box( $post ) {

		// Nonce field to validate on save.
		wp_nonce_field( 'ccp_project_details_nonce', 'ccp_project_details' ); ?>

		<p>
			<label>
				<?php esc_html_e( 'URL', 'custom-content-portfolio' ); ?>
				<input type="text" class="widefat" name="ccp_project_url" value="<?php echo esc_url( ccp_get_project_meta( $post->ID, 'url' ) ); ?>" placeholder="http://example.com" />
				<span class="description"><?php esc_html_e( 'Enter the URL of the project Web page.', 'custom-content-portfolio' ); ?></span>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Client', 'custom-content-portfolio' ); ?>
				<input type="text" class="widefat" name="ccp_project_client" value="<?php echo esc_attr( ccp_get_project_meta( $post->ID, 'client' ) ); ?>" placeholder="<?php esc_attr_e( 'John Doe', 'custom-content-portfolio' ); ?>" />
				<span class="description"><?php esc_html_e( 'Enter the name of the client for the project.', 'custom-content-portfolio' ); ?></span>
			</label>
		</p>
	<?php }

	/**
	 * Saves the post meta.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function update( $post_id ) {

		// Verify the nonce.
		if ( ! isset( $_POST['ccp_project_details'] ) || ! wp_verify_nonce( $_POST['ccp_project_details'], 'ccp_project_details_nonce' ) )
			return;

		/* === Project URL === */

		// Get the old url.
		$old_url = ccp_get_project_meta( $post_id, 'url' );

		// Get the new url.
		$new_url = isset( $_POST['ccp_project_url'] ) ? esc_url_raw( $_POST['ccp_project_url'] ) : '';

		// If we have don't have a new url but do have an old one, delete it.
		if ( '' == $new_url && $old_url )
			ccp_delete_project_meta( $post_id, 'url' );

		// If the new url doesn't match the old url, set it.
		else if ( $new_url !== $old_url )
			ccp_set_project_meta( $post_id, 'url', $new_url );

		/* === Project Client === */

		// Get the old client.
		$old_client = ccp_get_project_meta( $post_id, 'client' );

		// Get the new client.
		$new_client = isset( $_POST['ccp_project_client'] ) ? strip_tags( $_POST['ccp_project_client'] ) : '';

		// If we have don't have a new client but do have an old one, delete it.
		if ( '' == $new_client && $old_client )
			ccp_delete_project_meta( $post_id, 'client' );

		// If the new client doesn't match the old client, set it.
		else if ( $new_client !== $old_client )
			ccp_set_project_meta( $post_id, 'client', $new_client );
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
			$instance = new CCP_Meta_Box_Project_Details;

		return $instance;
	}
}

CCP_Meta_Box_Project_Details::get_instance();
