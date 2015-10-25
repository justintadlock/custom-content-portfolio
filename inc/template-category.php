<?php
/**
 * Template Tags for theme authors to use in their theme templates.
 *
 * @package    CustomContentPortfolio
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

function ccp_is_category( $term = '' ) {

	return apply_filters( 'ccp_is_category', is_tax( ccp_get_category_taxonomy(), $term ) );
}
