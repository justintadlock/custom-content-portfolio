<?php
/**
 * Deprecated functions.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress.
 * If a developer wants to set up a custom method for sanitizing the data, they should use the
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @since  0.1.0
 * @access public
 * @param  mixed  $meta_value The value of the data to sanitize.
 * @param  string $meta_key   The meta key name.
 * @param  string $meta_type  The type of metadata (post, comment, user, etc.)
 * @return mixed  $meta_value
 */
function ccp_sanitize_meta( $meta_value, $meta_key, $meta_type ) {

	if ( 'url' === $meta_key )
		return esc_url( $meta_value );

	return strip_tags( $meta_value );
}

/**
 * Filters the 'breadcrumb_trail_items' hook from the Breadcrumb Trail plugin and the script version
 * included in the Hybrid Core framework.  At best, this is a neat hack to add the portfolio to the
 * single view of portfolio items based off the '%portfolio%' rewrite tag.  At worst, it's potentially
 * a huge management nightmare in the long term.  A better solution is definitely needed baked right
 * into Breadcrumb Trail itself that takes advantage of its built-in features for figuring out this type
 * of thing.
 *
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_breadcrumb_trail_items( $items ) {
	return $items;
}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_add_meta_boxes() {}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_portfolio_item_info_meta_box_display() {}

/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_portfolio_item_info_meta_box_save() {}


/**
 * @since      0.1.0
 * @deprecated 1.0.0
 */
function ccp_admin_head_style() {}
