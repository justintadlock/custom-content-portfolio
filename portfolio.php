<?php
/**
 * Plugin Name: CPT - Portfolio
 * Plugin URI: http://themehybrid.com/plugins/
 * Description: A base for portfolios.
 * Version: 0.1 Alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * @package   CPTPortfolio
 * @version   0.1.0 - Alpha
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link      http://justintadlock.com
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class CPT_Portfolio {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 4 );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( &$this, 'activation' ) );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 0.1.0
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'CPT_PORTFOLIO_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'CPT_PORTFOLIO_INCLUDES', CPT_PORTFOLIO_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the admin directory. */
		define( 'CPT_PORTFOLIO_ADMIN', CPT_PORTFOLIO_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 0.1.0
	 */
	public function includes() {

		require_once( CPT_PORTFOLIO_INCLUDES . 'functions.php' );
		require_once( CPT_PORTFOLIO_INCLUDES . 'post-types.php' );
		require_once( CPT_PORTFOLIO_INCLUDES . 'taxonomies.php' );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since 0.1.0
	 */
	public function i18n() {

		/* Load the translation of the plugin. */
		//load_plugin_textdomain( 'cpt-portfolio', false, 'cpt-portfolio/languages' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 0.1.0
	 */
	public function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {
			require_once( CPT_PORTFOLIO_ADMIN . 'admin.php' );
		}
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since 0.1.0
	 */
	function activation() {

		/* Get the administrator role. */
		$role =& get_role( 'administrator' );

		/* If the administrator role exists, add required capabilities for the plugin. */
		if ( !empty( $role ) ) {

			$role->add_cap( 'manage_portfolio' );
			$role->add_cap( 'create_portfolio_items' );
			$role->add_cap( 'edit_portfolio_items' );
		}
	}
}

new CPT_Portfolio();

?>