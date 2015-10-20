<?php
/**
 * Admin functions for the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admi
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Set up the admin functionality.
add_action( 'admin_menu', 'ccp_admin_setup' );

/**
 * Adds actions where needed for setting up the plugin's admin functionality.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_admin_setup() {

	// Waiting on @link http://core.trac.wordpress.org/ticket/9296
	//add_action( 'admin_init', 'ccp_admin_setup' );

	// Custom columns on the edit portfolio items screen.
	add_filter( 'manage_edit-portfolio_project_columns', 'ccp_edit_portfolio_item_columns' );
	add_action( 'manage_portfolio_project_posts_custom_column', 'ccp_manage_portfolio_item_columns', 10, 2 );
}

/**
 * Sets up custom columns on the portfolio items edit screen.
 *
 * @since  0.1.0
 * @access public
 * @param  array  $columns
 * @return array
 */
function ccp_edit_portfolio_item_columns( $columns ) {

	$new_columns = array(
		'cb'    => $columns['cb'],
		'title' => __( 'Project', 'custom-content-portfolio' )
	);

	if ( current_theme_supports( 'post-thumbnails' ) )
		$new_columns['thumbnail'] = __( 'Thumbnail', 'custom-content-portfolio' );

	return array_merge( $new_columns, $columns );
}

/**
 * Displays the content of custom portfolio item columns on the edit screen.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $column
 * @param  int     $post_id
 * @return void
 */
function ccp_manage_portfolio_item_columns( $column, $post_id ) {

	if ( 'thumbnail' === $column ) {

		if ( has_post_thumbnail() )
			the_post_thumbnail( array( 40, 40 ) );

		elseif ( function_exists( 'get_the_image' ) )
			get_the_image( array( 'scan' => true, 'width' => 40, 'height' => 40, 'link' => false ) );
	}
}

/**
 * Adds plugin settings.  At the moment, this function isn't being used because we're waiting for a bug fix
 * in core.  For more information, see: http://core.trac.wordpress.org/ticket/9296
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_plugin_settings() {

	/* Register settings for the 'permalink' screen in the admin. */
	register_setting(
		'permalink',
		'plugin_custom_content_portfolio',
		'ccp_validate_settings'
	);

	/* Adds a new settings section to the 'permalink' screen. */
	add_settings_section(
		'ccp-permalink',
		__( 'Portfolio Settings', 'custom-content-portfolio' ),
		'ccp_permalink_section',
		'permalink'
	);

	/* Get the plugin settings. */
	$settings = get_option( 'plugin_ccp', ccp_get_default_settings() );

	add_settings_field(
		'ccp-root',
		__( 'Portfolio archive', 'custom-content-portfolio' ),
		'ccp_root_field',
		'permalink',
		'ccp-permalink',
		$settings
	);
	add_settings_field(
		'ccp-base',
		__( 'Portfolio taxonomy slug', 'custom-content-portfolio' ),
		'ccp_base_field',
		'permalink',
		'ccp-permalink',
		$settings
	);
	add_settings_field(
		'ccp-item-base',
		__( 'Portfolio item slug', 'custom-content-portfolio' ),
		'ccp_item_base_field',
		'permalink',
		'ccp-permalink',
		$settings
	);
}

/**
 * Validates the plugin settings.
 *
 * @since  0.1.0
 * @access public
 * @param  array  $settings
 * @return array
 */
function ccp_validate_settings( $settings ) {

	// @todo Sanitize for alphanumeric characters
	// @todo Both the portfolio_base and portfolio_item_base can't match.

	$settings['portfolio_base'] = $settings['portfolio_base'];

	$settings['portfolio_item_base'] = $settings['portfolio_item_base'];

	$settings['portfolio_root'] = !empty( $settings['portfolio_root'] ) ? $settings['portfolio_root'] : 'portfolio';

	return $settings;
}

/**
 * Adds the portfolio permalink section.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_permalink_section() { ?>
	<table class="form-table">
		<?php do_settings_fields( 'permalink', 'custom-content-portfolio' ); ?>
	</table>
<?php }

/**
 * Adds the portfolio root settings field.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_root_field( $settings ) { ?>
	<input type="text" name="plugin_ccp[portfolio_root]" id="ccp-portfolio-root" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_root'] ); ?>" />
	<code><?php echo home_url( $settings['portfolio_root'] ); ?></code>
<?php }

/**
 * Adds the portfolio (taxonomy) base settings field.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_base_field( $settings ) { ?>
	<input type="text" name="plugin_ccp[portfolio_base]" id="ccp-portfolio-base" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_base'] ); ?>" />
	<code><?php echo trailingslashit( home_url( "{$settings['portfolio_root']}/{$settings['portfolio_base']}" ) ); ?>%portfolio%</code>
<?php }

/**
 * Adds the portfolio item (post type) base settings field.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_item_base_field( $settings ) { ?>
	<input type="text" name="plugin_ccp[portfolio_item_base]" id="ccp-portfolio-item-base" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_item_base'] ); ?>" />
	<code><?php echo trailingslashit( home_url( "{$settings['portfolio_root']}/{$settings['portfolio_item_base']}" ) ); ?>%postname%</code>
<?php }
