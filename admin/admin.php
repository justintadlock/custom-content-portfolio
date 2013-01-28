<?php
/**
 * Admin functions for the plugin.
 *
 * @package    CustomContentPortfolio
 * @subpackage Admin
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up the admin functionality. */
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

	/* Custom columns on the edit portfolio items screen. */
	add_filter( 'manage_edit-portfolio_item_columns', 'ccp_edit_portfolio_item_columns' );
	add_action( 'manage_portfolio_item_posts_custom_column', 'ccp_manage_portfolio_item_columns', 10, 2 );

	/* Add meta boxes an save metadata. */
	add_action( 'add_meta_boxes', 'ccp_add_meta_boxes' );
	add_action( 'save_post', 'ccp_portfolio_item_info_meta_box_save', 10, 2 );

	/* Add 32px screen icon. */
	add_action( 'admin_head', 'ccp_admin_head_style' );
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

	unset( $columns['title'] );
	unset( $columns['taxonomy-portfolio'] );

	$new_columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Portfolio Item', 'custom-content-portfolio' )
	);

	if ( current_theme_supports( 'post-thumbnails' ) )
		$new_columns['thumbnail'] = __( 'Thumbnail', 'custom-content-portfolio' );

	$new_columns['taxonomy-portfolio'] = __( 'Portfolio', 'custom-content-portfolio' );

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
	global $post;

	switch( $column ) {

		case 'thumbnail' :

			if ( has_post_thumbnail() )
				the_post_thumbnail( array( 40, 40 ) );

			elseif ( function_exists( 'get_the_image' ) )
				get_the_image( array( 'image_scan' => true, 'width' => 40, 'height' => 40 ) );

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/**
 * Registers new meta boxes for the 'portfolio_item' post editing screen in the admin.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $post_type
 * @return void
 */
function ccp_add_meta_boxes( $post_type ) {

	if ( 'portfolio_item' === $post_type ) {

		add_meta_box( 
			'ccp-item-info', 
			__( 'Project Info', 'custom-content-portfolio' ), 
			'ccp_portfolio_item_info_meta_box_display', 
			$post_type, 
			'side', 
			'core'
		);
	}
}

/**
 * Displays the content of the portfolio item info meta box.
 *
 * @since  0.1.0
 * @access public
 * @param  object  $post
 * @param  array   $metabox
 * @return void
 */
function ccp_portfolio_item_info_meta_box_display( $post, $metabox ) {

	wp_nonce_field( basename( __FILE__ ), 'ccp-portfolio-item-info-nonce' ); ?>

	<p>
		<label for="ccp-portfolio-item-url"><?php _e( 'Project <abbr title="Uniform Resource Locator">URL</abbr>', 'custom-content-portfolio' ); ?></label>
		<br />
		<input type="text" name="ccp-portfolio-item-url" id="ccp-portfolio-item-url" value="<?php echo esc_url( get_post_meta( $post->ID, 'portfolio_item_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<?php

	/* Allow devs to hook in their own stuff here. */
	do_action( 'ccp_item_info_meta_box', $post, $metabox );
}

/**
 * Saves the metadata for the portfolio item info meta box.
 *
 * @since  0.1.0
 * @access public
 * @param  int     $post_id
 * @param  object  $post
 * @return void
 */
function ccp_portfolio_item_info_meta_box_save( $post_id, $post ) {

	if ( !isset( $_POST['ccp-portfolio-item-info-nonce'] ) || !wp_verify_nonce( $_POST['ccp-portfolio-item-info-nonce'], basename( __FILE__ ) ) )
		return;

	$meta = array(
		'portfolio_item_url' => esc_url( $_POST['ccp-portfolio-item-url'] )
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If there is no new meta value but an old value exists, delete it. */
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		/* If a new meta value was added and there was no previous value, add it. */
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
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

/**
 * Overwrites the screen icon for portfolio screens in the admin.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccp_admin_head_style() {
        global $post_type;

	if ( 'portfolio_item' === $post_type ) { ?>
		<style type="text/css">
			#icon-edit.icon32-posts-portfolio_item {
				background: transparent url( '<?php echo CCP_URI . 'images/screen-icon.png'; ?>' ) no-repeat;
			}
		</style>
	<?php }
}

?>