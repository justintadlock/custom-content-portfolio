<?php

/**
 * Adds additional columns and features to the projects admin screen.
 *
 * @since  1.0.0
 * @access public
 */
final class CCP_Admin_Projects {

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-edit.php', array( $this, 'load' ) );
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

		// Custom columns on the edit portfolio items screen.
		add_filter( "manage_edit-{$project_type}_columns",        array( $this, 'columns' )              );
		add_action( "manage_{$project_type}_posts_custom_column", array( $this, 'custom_column' ), 10, 2 );

		// Print custom styles.
		add_action( 'admin_head', array( $this, 'print_styles' ) );
	}

	/**
	 * Print styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $hook_suffix
	 * @return void
	 */
	public function print_styles() { ?>

		<style type="text/css">@media only screen and (min-width: 783px) {
			.fixed .column-thumbnail { width: 100px; }
			.fixed .column-taxonomy-portfolio_category,
			.fixed .column-taxonomy-portfolio_tag { width: 15%; }
		}</style>
	<?php }

	/**
	 * Sets up custom columns on the projects edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $columns
	 * @return array
	 */
	public function columns( $columns ) {

		$new_columns = array(
			'cb'    => $columns['cb'],
			'title' => __( 'Project', 'custom-content-portfolio' )
		);

		if ( current_theme_supports( 'post-thumbnails' ) )
			$new_columns['thumbnail'] = __( 'Thumbnail', 'custom-content-portfolio' );

		$columns = array_merge( $new_columns, $columns );

		$columns['title'] = $new_columns['title'];

		return $columns;
	}

	/**
	 * Displays the content of custom project columns on the edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $column
	 * @param  int     $post_id
	 * @return void
	 */
	public function custom_column( $column, $post_id ) {

		if ( 'thumbnail' === $column ) {

			if ( has_post_thumbnail() )
				the_post_thumbnail( array( 75, 75 ) );

			elseif ( function_exists( 'get_the_image' ) )
				get_the_image( array( 'scan' => true, 'width' => 75, 'link' => false ) );
		}
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

CCP_Admin_Projects::get_instance();
