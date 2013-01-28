<?php
/**
 * Plugin Name: Custom Content Portfolio
 * Plugin URI: http://themehybrid.com/plugins/custom-content-portfolio
 * Description: Portfolio manager for WordPress.  This plugin allows you to manage, edit, and create new portfolio items in an unlimited number of portfolios.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * The Custom Content Portfolio plugin was created to solve the problem of theme developers continuing 
 * to incorrectly add custom post types to handle portfolios within their themes.  This plugin allows 
 * any theme developer to build a "portfolio" theme without having to code the functionality.  This 
 * gives more time for design and makes users happy because their data isn't lost when they switch to 
 * a new theme.  Oh, and, this plugin lets creative folk put together a portfolio of their work on 
 * their site.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   CustomContentPortfolio
 * @version   0.1.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2013, Justin Tadlock
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
		define( 'CCP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'CCP_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'CCP_INCLUDES', CCP_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the admin directory. */
		define( 'CCP_ADMIN', CCP_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function includes() {

		require_once( CCP_INCLUDES . 'functions.php' );
		require_once( CCP_INCLUDES . 'meta.php' );
		require_once( CCP_INCLUDES . 'post-types.php' );
		require_once( CCP_INCLUDES . 'taxonomies.php' );
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
		load_plugin_textdomain( 'custom-content-portfolio', false, 'custom-content-portfolio/languages' );
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
			require_once( CCP_ADMIN . 'admin.php' );
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