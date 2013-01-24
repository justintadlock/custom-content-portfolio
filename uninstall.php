<?php

/* Make sure we're actually uninstalling the plugin. */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling a plugin.', 'cpt-portfolio' ), '<code>' . __FILE__ . '</code>' ) );

/* === Delete plugin options. === */

delete_option( 'plugin_cpt_portfolio' );

/* === Remove capabilities added by the plugin. === */

/* Get the administrator role. */
$role =& get_role( 'administrator' );

/* If the administrator role exists, add required capabilities for the plugin. */
if ( !empty( $role ) ) {

	$role->remove_cap( 'manage_portfolio' );
	$role->remove_cap( 'create_portfolio_items' );
	$role->remove_cap( 'edit_portfolio_items' );
}

?>