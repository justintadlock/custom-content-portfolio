<?php
/**
 * Manage projects admin screen.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

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

		// Overview help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'overview',
				'title'    => esc_html__( 'Overview', 'custom-content-portfolio' ),
				'callback' => array( $this, 'help_tab_overview' )
			)
		);

		// Screen content help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'screen_content',
				'title'    => esc_html__( 'Screen Content', 'custom-content-portfolio' ),
				'callback' => array( $this, 'help_tab_screen_content' )
			)
		);

		// Available actions help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'available_actions',
				'title'    => esc_html__( 'Available Actions', 'custom-content-portfolio' ),
				'callback' => array( $this, 'help_tab_available_actions' )
			)
		);

		// Set the help sidebar.
		$screen->set_help_sidebar( ccp_get_help_sidebar_text() );
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
	 * Displays the overview help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_overview() { ?>

		<p>
			<?php esc_html_e( 'This screen provides access to all of your portfolio projects. You can customize the display of this screen to suit your workflow.', 'custom-content-portfolio' ); ?>
		</p>
	<?php }

	/**
	 * Displays the screen content help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_screen_content() { ?>

		<p>
			<?php esc_html_e( "You can customize the display of this screen's contents in a number of ways:", 'custom-content-portfolio' ); ?>
		</p>

		<ul>
			<li><?php esc_html_e( 'You can hide/display columns based on your needs and decide how many projects to list per screen using the Screen Options tab.', 'custom-content-portfolio' ); ?></li>
			<li><?php esc_html_e( 'You can filter the list of projects by post status using the text links in the upper left to show All, Published, Draft, or Trashed projects. The default view is to show all projects.', 'custom-content-portfolio' ); ?></li>
			<li><?php esc_html_e( 'You can view projects in a simple title list or with an excerpt. Choose the view you prefer by clicking on the icons at the top of the list on the right.', 'custom-content-portfolio' ); ?></li>
			<li><?php esc_html_e( 'You can refine the list to show only projects in a specific category, with a specific tag, or from a specific month by using the dropdown menus above the projects list. Click the Filter button after making your selection. You also can refine the list by clicking on the project author, category or tag in the posts list.', 'custom-content-portfolio' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the available actions help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_available_actions() { ?>

		<p>
			<?php esc_html_e( 'Hovering over a row in the projects list will display action links that allow you to manage your project. You can perform the following actions:', 'custom-content-portfolio' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>Edit</strong> takes you to the editing screen for that project. You can also reach that screen by clicking on the project title.', 'custom-content-portfolio' ); ?></li>
			<li><?php _e( '<strong>Quick Edit</strong> provides inline access to the metadata of your project, allowing you to update project details without leaving this screen.', 'custom-content-portfolio' ); ?></li>
			<li><?php _e( '<strong>Trash</strong> removes your project from this list and places it in the trash, from which you can permanently delete it.', 'custom-content-portfolio' ); ?></li>
			<li><?php _e( "<strong>Preview</strong> will show you what your draft project will look like if you publish it. View will take you to your live site to view the project. Which link is available depends on your project's status.", 'custom-content-portfolio' ); ?></li>
		</ul>
	<?php }

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
