<?php
/**
 * Plugin Name: Custom Content - Portfolio
 * Plugin URI: http://themehybrid.com/plugins/custom-content-portfolio
 * Description: Portfolio manager for WordPress.  This plugin allows you to manage, edit, and create new portfolio items in an unlimited number of portfolios.
 * Version: 0.1-alpha
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * @package   CustomContentPortfolio
 * @version   0.1.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link      http://themehybrid.com/plugins/custom-content-portfolio
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Custom_Content_Portfolio {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
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
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'CC_PORTFOLIO_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'CC_PORTFOLIO_INCLUDES', CC_PORTFOLIO_DIR . trailingslashit( 'includes' ) );

		define( 'CC_PORTFOLIO_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the admin directory. */
		define( 'CC_PORTFOLIO_ADMIN', CC_PORTFOLIO_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function includes() {

		require_once( CC_PORTFOLIO_INCLUDES . 'functions.php' );
		require_once( CC_PORTFOLIO_INCLUDES . 'meta.php' );
		require_once( CC_PORTFOLIO_INCLUDES . 'post-types.php' );
		require_once( CC_PORTFOLIO_INCLUDES . 'taxonomies.php' );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		/* Load the translation of the plugin. */
		//load_plugin_textdomain( 'cc-portfolio', false, 'cc-portfolio/languages' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function admin() {

		if ( is_admin() )
			require_once( CC_PORTFOLIO_ADMIN . 'admin.php' );
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
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

new Custom_Content_Portfolio();

?>