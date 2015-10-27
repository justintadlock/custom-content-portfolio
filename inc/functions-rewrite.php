<?php
/**
 * Plugin rewrite functions.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2015, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

add_action( 'init', 'ccp_rewrite_rules', 5 );

function ccp_rewrite_rules() {

	$project_type = ccp_get_project_post_type();
	$author_slug  = ccp_get_author_rewrite_slug();

	add_rewrite_rule( $author_slug . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?post_type=' . $project_type . '&author_name=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( $author_slug . '/([^/]+)/?$',                   'index.php?post_type=' . $project_type . '&author_name=$matches[1]',                   'top' );
}
