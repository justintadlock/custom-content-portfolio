<?php
/**
 * Hooks into the Members plugin and registers capabilities.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justintadlock@gmail.com>
 * @copyright  Copyright (c) 2013-2017, Justin Tadlock
 * @link       https://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register cap groups.
add_action( 'members_register_cap_groups', 'ccp_register_cap_groups' );

# Register caps.
add_action( 'members_register_caps', 'ccp_register_caps' );

/**
 * Overwrites the cap group registered within the Members plugin.  We want
 * the label to read "Portfolio".
 *
 * @since  2.1.0
 * @access public
 * @return void
 */
function ccp_register_cap_groups() {

	$group = members_get_cap_group( 'type-' . ccp_get_project_post_type() );

	if ( $group ) {

		$group->label = __( 'Portfolio', 'custom-content-portfolio' );
	}
}

/**
 * Registers caps with the Members plugin.  This gives pretty labels for each
 * of the capabilities.
 *
 * @since  2.1.0
 * @access public
 * @return void
 */

function ccp_register_caps() {

	$caps  = array();
	$group = sprintf( 'type-%s', ccp_get_project_post_type() );

	// Project caps.
	$caps['create_portfolio_projects']           = __( 'Create Projects',           'custom-content-portfolio' );
	$caps['edit_portfolio_projects']             = __( 'Edit Projects',             'custom-content-portfolio' );
	$caps['edit_others_portfolio_projects']      = __( "Edit Others' Projects",     'custom-content-portfolio' );
	$caps['read_private_portfolio_projects']     = __( 'Read Private Projects',     'custom-content-portfolio' );
	$caps['delete_portfolio_projects']           = __( 'Delete Projects',           'custom-content-portfolio' );
	$caps['delete_private_portfolio_projects']   = __( 'Delete Private Projects',   'custom-content-portfolio' );
	$caps['delete_published_portfolio_projects'] = __( 'Delete Published Projects', 'custom-content-portfolio' );
	$caps['delete_others_portfolio_projects']    = __( "Delete Others' Projects",   'custom-content-portfolio' );
	$caps['edit_private_portfolio_projects']     = __( 'Edit Private Projects',     'custom-content-portfolio' );
	$caps['edit_published_portfolio_projects']   = __( 'Edit Published Projects',   'custom-content-portfolio' );
	$caps['publish_portfolio_projects']          = __( 'Publish Projects',          'custom-content-portfolio' );

	// Category caps.
	$caps['assign_portfolio_categories'] = __( 'Assign Project Categories', 'custom-content-portfolio' );
	$caps['delete_portfolio_categories'] = __( 'Delete Project Categories', 'custom-content-portfolio' );
	$caps['edit_portfolio_categories']   = __( 'Edit Project Categories',   'custom-content-portfolio' );
	$caps['manage_portfolio_categories'] = __( 'Manage Project Categories', 'custom-content-portfolio' );

	// Tag caps.
	$caps['assign_portfolio_tags'] = __( 'Assign Project Tags', 'custom-content-portfolio' );
	$caps['delete_portfolio_tags'] = __( 'Delete Project Tags', 'custom-content-portfolio' );
	$caps['edit_portfolio_tags']   = __( 'Edit Project Tags',   'custom-content-portfolio' );
	$caps['manage_portfolio_tags'] = __( 'Manage Project Tags', 'custom-content-portfolio' );

	// Register each of the capabilities.
	foreach ( $caps as $name => $label )
		members_register_cap( $name, array( 'label' => $label, 'group' => $group ) );
}
