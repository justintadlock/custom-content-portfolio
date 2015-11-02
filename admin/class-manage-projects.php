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
final class CCP_Manage_Projects {

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-edit.php', array( $this, 'load' ) );

		// Hook the handler to the manage projects load screen.
		add_action( 'ccp_load_manage_projects', array( $this, 'handler' ), 0 );

		// Add the help tabs.
		add_action( 'ccp_load_manage_projects', array( $this, 'add_help_tabs' ) );
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

		// Custom action for loading the manage projects screen.
		do_action( 'ccp_load_manage_projects' );

		// Filter the `request` vars.
		add_filter( 'request', array( $this, 'request' ) );

		// Add custom views.
		add_filter( "views_edit-{$project_type}", array( $this, 'views' ) );

		// Category and tag table filters.
		add_action( 'restrict_manage_posts', array( $this, 'categories_dropdown' ) );
		add_action( 'restrict_manage_posts', array( $this, 'tags_dropdown'       ) );

		// Custom columns on the edit portfolio items screen.
		add_filter( "manage_edit-{$project_type}_columns",        array( $this, 'columns' )              );
		add_action( "manage_{$project_type}_posts_custom_column", array( $this, 'custom_column' ), 10, 2 );

		// Print custom styles.
		add_action( 'admin_head', array( $this, 'print_styles' ) );

		// Filter post states (shown next to post title).
		add_filter( 'display_post_states', array( $this, 'display_post_states' ), 0, 2 );

		// Filter the row actions (shown below title).
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
	}

	/**
	 * Filter on the `request` hook to change what posts are loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $vars
	 * @return array
	 */
	public function request( $vars ) {

		$new_vars = array();

		// If viewing sticky projects.
		if ( isset( $_GET['sticky'] ) && 1 == $_GET['sticky'] )
			$new_vars['post__in'] = ccp_get_sticky_projects();

		// Return the vars, merging with the new ones.
		return array_merge( $vars, $new_vars );
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
			.fixed .column-taxonomy-<?php echo esc_attr( ccp_get_category_taxonomy() ); ?>,
			.fixed .column-taxonomy-<?php echo esc_attr( ccp_get_tag_taxonomy() ); ?> { width: 15%; }
		}</style>
	<?php }

	/**
	 * Add custom views (status list).
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $views
	 * @return array
	 */
	public function views( $views ) {

		$count = count( ccp_get_sticky_projects() );

		if ( 0 < $count ) {
			$post_type = ccp_get_project_post_type();

			$noop = _n( 'Sticky <span class="count">(%s)</span>', 'Sticky <span class="count">(%s)</span>', $count, 'custom-content-portfolio' );
			$text = sprintf( $noop, number_format_i18n( $count ) );

			$views['sticky'] = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'post_type' => $post_type, 'sticky' => 1 ), admin_url( 'edit.php' ) ), $text );
		}

		return $views;
	}

	/**
	 * Renders a categories dropdown below the table nav.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function categories_dropdown() {

		$this->terms_dropdown( ccp_get_category_taxonomy() );
	}

	/**
	 * Renders a tags dropdown below the table nav.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tags_dropdown() {

		$this->terms_dropdown( ccp_get_tag_taxonomy() );
	}

	/**
	 * Renders a terms dropdown based on the given taxonomy.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function terms_dropdown( $taxonomy ) {

		wp_dropdown_categories(
			array(
				'show_option_all' => false,
				'show_option_none'    => get_taxonomy( $taxonomy )->labels->all_items,
				'option_none_value'  => '',
				'orderby'            => 'name',
				'order'              => 'ASC',
				'show_count'         => true,
				'selected'           => isset( $_GET[ $taxonomy ] ) ? esc_attr( $_GET[ $taxonomy ] ) : '',
				'hierarchical'       => true,
				'name'               => $taxonomy,
				'id'                 => '',
				'class'              => 'postform',
				'taxonomy'           => $taxonomy,
				'hide_if_empty'      => true,
				'value_field'	     => 'slug',
			)
		);
	}

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
	 * Filter for the `post_states` hook.  We're going to add the project type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $states
	 * @param  object  $post
	 */
	public function display_post_states( $states, $post ) {

		if ( ccp_is_project_sticky( $post->ID ) )
			$states['sticky'] = esc_html__( 'Sticky', 'custom-content-portfolio' );

		return $states;
	}

	/**
	 * Custom row actions below the post title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $actions
	 * @param  object  $post
	 * @return array
	 */
	function row_actions( $actions, $post ) {

		$post_type_object = get_post_type_object( ccp_get_project_post_type() );
		$project_id = ccp_get_project_id( $post->ID );

		if ( 'trash' === get_post_status( $project_id ) || ! current_user_can( $post_type_object->cap->publish_posts ) )
			return $actions;

		$current_url = remove_query_arg( array( 'project_id', 'ccp_project_notice' ) );

		// Build text.
		$text = ccp_is_project_sticky( $project_id ) ? esc_html__( 'Unstick', 'custom-content-portfolio' ) : esc_html__( 'Stick', 'custom-content-portfolio' );

		// Build toggle URL.
		$url = add_query_arg( array( 'project_id' => $project_id, 'action' => 'ccp_toggle_sticky' ), $current_url );
		$url = wp_nonce_url( $url, "ccp_toggle_sticky_{$project_id}" );

		// Add sticky action.
		$actions['sticky'] = sprintf( '<a href="%s" class="%s">%s</a>', esc_url( $url ), 'sticky', esc_html( $text ) );

		// Move view action to the end.
		if ( isset( $actions['view'] ) ) {
			$view_action = $actions['view'];
			unset( $actions['view'] );

			$actions['view'] = $view_action;
		}

		return $actions;
	}

	/**
	 * Callback function for handling post status changes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function handler() {

		// Checks if the sticky toggle link was clicked.
		if ( isset( $_GET['action'] ) && 'ccp_toggle_sticky' === $_GET['action'] && isset( $_GET['project_id'] ) ) {

			$project_id = absint( ccp_get_project_id( $_GET['project_id'] ) );

			// Verify the nonce.
			check_admin_referer( "ccp_toggle_sticky_{$project_id}" );

			if ( ccp_is_project_sticky( $project_id ) )
				ccp_remove_sticky_project( $project_id );
			else
				ccp_add_sticky_project( $project_id );

			// Redirect to correct admin page.
			$redirect = add_query_arg( array( 'updated' => 1 ), remove_query_arg( array( 'action', 'project_id', '_wpnonce' ) ) );
			wp_safe_redirect( esc_url_raw( $redirect ) );

			// Always exit for good measure.
			exit();
		}

		return;
	}

	/**
	 * Adds custom help tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		$screen = get_current_screen();

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

CCP_Manage_Projects::get_instance();
