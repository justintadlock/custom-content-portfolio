<?php

add_action( 'admin_menu', 'cpt_portfolio_admin' );

function cpt_portfolio_admin() {
	add_action( 'add_meta_boxes', 'cpt_portfolio_add_meta_boxes' );
	add_action( 'save_post', 'cpt_portfolio_item_info_meta_box_save', 10, 2 );
}

function cpt_portfolio_add_meta_boxes( $post_type ) {

	if ( 'portfolio_item' === $post_type ) {

		add_meta_box( 
			'cpt-portfolio-item-info', 
			__( 'Project Info', 'cpt-portfolio' ), 
			'cpt_portfolio_item_info_meta_box_display', 
			$post_type, 
			'side', 
			'core'
		);
	}
}

function cpt_portfolio_item_info_meta_box_display( $post, $metabox ) {
	wp_nonce_field( basename( __FILE__ ), 'cpt-portfolio-item-info-nonce' ); ?>

	<p>
		<label for="cpt-portfolio-item-url"><?php _e( 'Project <abbr title="Uniform Resource Locator">URL</abbr>', 'cpt-portfolio' ); ?></label>
		<br />
		<input type="text" name="cpt-portfolio-item-url" id="cpt-portfolio-item-url" value="<?php echo esc_url( get_post_meta( $post->ID, 'portfolio_item_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>


	<?php
}

function cpt_portfolio_item_info_meta_box_save( $post_id, $post ) {

	if ( !isset( $_POST['cpt-portfolio-item-info-nonce'] ) || !wp_verify_nonce( $_POST['cpt-portfolio-item-info-nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$meta = array(
		'portfolio_item_url' => esc_url( $_POST['cpt-portfolio-item-url'] )
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

// Waiting on @link http://core.trac.wordpress.org/ticket/9296
add_action( 'admin_init', 'cpt_portfolio_admin_setup' );

/**
 * @since 0.1.0
 */
function cpt_portfolio_admin_setup() {

	/**
	 * Register settings for the 'permalink' screen in the admin. Note this won't work until fixed 
	 * in WP core.
	 * @link http://core.trac.wordpress.org/ticket/9296
	 */
	register_setting(
		'permalink',
		'plugin_cpt_portfolio',
		'cpt_portfolio_validate_settings'
	);

	/* Adds a new settings section to the 'permalink' screen. */
	add_settings_section(
		'cpt-portfolio-permalink',
		__( 'Portfolio Settings', 'cpt-portfolio' ),
		'cpt_portfolio_permalink_section',
		'permalink'
	);

	/* Get the plugin settings. */
	$settings = get_option( 'plugin_cpt_portfolio', cpt_portfolio_get_default_settings() );

	add_settings_field(
		'cpt-portfolio-root',
		__( 'Portfolio archive', 'cpt-portfolio' ),
		'cpt_portfolio_root_field',
		'permalink',
		'cpt-portfolio-permalink',
		$settings
	);
	add_settings_field(
		'cpt-portfolio-base',
		__( 'Portfolio taxonomy slug', 'cpt-portfolio' ),
		'cpt_portfolio_base_field',
		'permalink',
		'cpt-portfolio-permalink',
		$settings
	);
	add_settings_field(
		'cpt-portfolio-item-base',
		__( 'Portfolio item slug', 'cpt-portfolio' ),
		'cpt_portfolio_item_base_field',
		'permalink',
		'cpt-portfolio-permalink',
		$settings
	);
}

/**
 * @since 0.1.0
 */
function cpt_portfolio_validate_settings( $settings ) {

	// @todo Sanitize for alphanumeric characters
	// @todo Both the portfolio_base and portfolio_item_base can't match.

	$settings['portfolio_base'] = $settings['portfolio_base'];

	$settings['portfolio_item_base'] = $settings['portfolio_item_base'];

	$settings['portfolio_root'] = !empty( $settings['portfolio_root'] ) ? $settings['portfolio_root'] : 'portfolio';

	return $settings;
}

/**
 * @since 0.1.0
 */
function cpt_portfolio_permalink_section() { ?>
	<table class="form-table">
		<?php do_settings_fields( 'permalink', 'cpt-portfolio' ); ?>
	</table>
<?php }

/**
 * @since 0.1.0
 */
function cpt_portfolio_root_field( $settings ) { ?>
	<input type="text" name="plugin_cpt_portfolio[portfolio_root]" id="cpt-portfolio-root" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_root'] ); ?>" />
	<code><?php echo home_url( $settings['portfolio_root'] ); ?></code> 
<?php }

/**
 * @since 0.1.0
 */
function cpt_portfolio_base_field( $settings ) { ?>
	<input type="text" name="plugin_cpt_portfolio[portfolio_base]" id="cpt-portfolio-base" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_base'] ); ?>" />
	<code><?php echo trailingslashit( home_url( "{$settings['portfolio_root']}/{$settings['portfolio_base']}" ) ); ?>%portfolio%</code> 
<?php }

/**
 * @since 0.1.0
 */
function cpt_portfolio_item_base_field( $settings ) { ?>
	<input type="text" name="plugin_cpt_portfolio[portfolio_item_base]" id="cpt-portfolio-item-base" class="regular-text code" value="<?php echo esc_attr( $settings['portfolio_item_base'] ); ?>" />
	<code><?php echo trailingslashit( home_url( "{$settings['portfolio_root']}/{$settings['portfolio_item_base']}" ) ); ?>%postname%</code> 
<?php }

?>