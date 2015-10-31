<?php
/**
 * Plugin uninstall file.
 *
 * @package    CustomContentPortfolio
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Make sure we're actually uninstalling the plugin.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling the plugin.', 'custom-content-portfolio' ), '<code>' . __FILE__ . '</code>' ) );

/* === Delete plugin options. === */

// Remove pre-1.0.0 options.
delete_option( 'plugin_custom_content_portfolio' );

// Remove 1.0.0+ options.
delete_option( 'ccp_settings'        );
delete_option( 'ccp_sticky_projects' );

/* === Remove capabilities added by the plugin. === */

// Get the administrator role.
$role = get_role( 'administrator' );

// If the administrator role exists, remove added capabilities for the plugin.
if ( ! is_null( $role ) ) {

	// Remove pre-1.0.0 caps.
	$role->remove_cap( 'manage_portfolio'       );
	$role->remove_cap( 'create_portfolio_items' );
	$role->remove_cap( 'edit_portfolio_items'   );

	// Taxonomy caps.
	$role->remove_cap( 'manage_portfolio_categories' );
	$role->remove_cap( 'manage_portfolio_tags'       );

	// Post type caps.
	$role->remove_cap( 'create_portfolio_projects'           );
	$role->remove_cap( 'edit_portfolio_projects'             );
	$role->remove_cap( 'edit_others_portfolio_projects'      );
	$role->remove_cap( 'publish_portfolio_projects'          );
	$role->remove_cap( 'read_private_portfolio_projects'     );
	$role->remove_cap( 'delete_portfolio_projects'           );
	$role->remove_cap( 'delete_private_portfolio_projects'   );
	$role->remove_cap( 'delete_published_portfolio_projects' );
	$role->remove_cap( 'delete_others_portfolio_projects'    );
	$role->remove_cap( 'edit_private_portfolio_projects'     );
	$role->remove_cap( 'edit_published_portfolio_projects'   );
}
