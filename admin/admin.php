<?php

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