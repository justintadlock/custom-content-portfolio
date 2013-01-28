<?php
/**
 * Uninstall procedure for the plugin.
 *
 * @package    CustomContentPortfolio
 * @since      0.1.0
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Make sure we're actually uninstalling the plugin. */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling the plugin.', 'custom-content-portfolio' ), '<code>' . __FILE__ . '</code>' ) );

/* === Delete plugin options. === */

delete_option( 'plugin_custom_content_portfolio' );

/* === Remove capabilities added by the plugin. === */

/* Get the administrator role. */
$role =& get_role( 'administrator' );

/* If the administrator role exists, remove added capabilities for the plugin. */
if ( !empty( $role ) ) {

	$role->remove_cap( 'manage_portfolio' );
	$role->remove_cap( 'create_portfolio_items' );
	$role->remove_cap( 'edit_portfolio_items' );
}

?>